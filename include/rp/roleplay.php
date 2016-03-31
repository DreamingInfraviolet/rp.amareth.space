<?php
require_once "user.php";

class Roleplay
{
    private $id;
    private $title;
    private $users;
    private $owner;
    private $posts;

    private function __construct(){}

    public static function create($ownerUsername, $title, $allowHtml, $allowMultipost)
    {
        Mysqlw::instance()->wqueryE("INSERT INTO roleplays
                                    (owner, title, status) VALUES (?,?,?)",
                                    array($ownerUsername, $title, "active"),
                                    "Unable to create new roleplay.");
        $rp = Roleplay::fromId(Mysqlw::instance()->con->insert_id);
        $rp->setProperty("allowHtml", $allowHtml);
        $rp->setProperty("allowMultipost", $allowMultipost);
        return $rp;
    }

    public static function fromId($id)
    {
        $rp = new Roleplay();
        $row = Mysqlw::instance()->wqueryE("SELECT * FROM roleplays WHERE id=?",
            array($id), "Unable to select rp session by id.");
        if(Mysqlw::instance()->affected_rows==0)
            throw new Exception("Roleplay $id does not exist.");
        $row = $row->fetch_object();
        $rp->id    = $id;
        $rp->title = $row->title;
        $rp->users = null; //lazy initialisation
        $rp->posts = null; //lazy initialisation
        $rp->owner = User::fromUsername($row->owner);
        return $rp;
    }

    public static function existsId($id)
    {
        $row = Mysqlw::instance()->wqueryE("SELECT * FROM roleplays WHERE id=?",
            array($id), "Unable to select rp session by id.");
        return Mysqlw::instance()->affected_rows!=0;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getPosts()
    {
        if($this->posts==null)
        {
            $res = Mysqlw::instance()->wqueryE("SELECT * FROM posts WHERE roleplay=? ORDER BY postDate ASC",
                array($this->id), "Unable to retrieve posts.");
            $this->posts = array();
            while($obj=$res->fetch_object())
                $this->posts[]=$obj;
        }
        return $this->posts;
    }

    public function makePost($post, $user)
    {
        if(!empty($this->getProperty("allowHtml")))
            $post=htmlspecialchars($post);
        $latestPost = $this->getLatestPost();
        if(!empty($latestPost) and !empty($this->getProperty("allowMultipost") and
            $this->getLatestPost()->owner==$user->getUsername()))
                throw new Exception("You must wait for someone else to post before you can do so!");

        Mysqlw::instance()->wqueryE("INSERT INTO posts
        	(owner, roleplay, `text`) VALUES (?,?,?)",
            array($user->getUsername(), $this->id, $post),
            "Unable to make post.");
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLatestPost()
    {

    }

    public function printTr()
    {
        echo "<tr>\n";
        echo "<td><a href='/roleplay.php?id=".$this->id."'>".$this->title."</a></td>\n";
        echo "<td>".$this->owner->getUsername()."</td>\n";
        echo "</tr>\n";
    }

    public static function allActive()
    {
        return Roleplay::forCondition("status='active'");
    }

    public static function allAbandoned()
    {
        return Roleplay::forCondition("status='abandoned'");
    }

    public static function allCompleted()
    {
        return Roleplay::forCondition("status='completed'");
    }

    public static function forUser($user)
    {
            $sessionIdResults = Mysqlw::instance()->wqueryE("SELECT rpId FROM participations WHERE playerUsername=?",
                array($user->getUsername()), "Unable to retrieve user roleplays.");
            $ans = array();
            while($obj=$sessionIdResults->fetch_object())
            {
                $id = $obj->rpId;
                $ans[] = Roleplay::fromId($id);
            }
            return $ans;
    }

    private static function forCondition($cond, $params=array())
    {
        $res = Mysqlw::instance()->wqueryE("SELECT id FROM roleplays WHERE ".$cond,
            $params, "Unable to select sessions.");
        $arr = array();
        while($obj=$res->fetch_object())
            $arr[] = Roleplay::fromId($obj->id);
        return $arr;
    }

    public function setProperty($property, $value)
    {
        $this->prepareProperties();
        $this->properties[$property]=$value;
        Mysqlw::instance()->wQueryE("UPDATE roleplays SET properties=? WHERE id=?",
            array(json_encode($this->properties), $this->id),
            "Unable to save properties to the database.");
        if(Mysqlw::instance()->affected_rows==0)
            throw new Exception("Can't set property: user not found.");
    }

    public function getProperty($property)
    {
        $this->prepareProperties();
        return isset($this->properties[$property]) ? $this->properties[$property]:null;
    }

    private function prepareProperties()
    {
        if(empty($this->properties))
            $this->properties=array();
    }
}

?>
