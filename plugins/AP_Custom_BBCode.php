<?php

/**
 * The Custom BBCode plugin allows administrators to add new BBCode tags
 *
 * Copyright (C) 2014  Samuel Rush (ratburntro44@yahoo.com)
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
        exit;

// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define('PUN_PLUGIN_LOADED', 1);

if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
	require PUN_ROOT.'include/cache.php';

// Load the custom bbcode language files
if (file_exists(PUN_ROOT.'lang/'.$pun_user['language'].'/custom_bbcode.php'))
     	require PUN_ROOT.'lang/'.$pun_user['language'].'/custom_bbcode.php';
     else
     	require PUN_ROOT.'lang/English/custom_bbcode.php';

if($_GET['action'] == 'delete') { // If the user is deleting a tag
	$name = $db->escape($_GET['name']);
	$type = intval($_GET['type']);
	if(($type != 1) && ($type != 2))
		$type = 1;
	$db->query('DELETE FROM '.$db->prefix.'bbcode WHERE name=\''.$name.'\' AND type='.$type.'') or redirect('admin_loader.php?plugin=AP_Custom_BBCode.php', 'Failed to delete tag "'.$name.'" from database');
	redirect('admin_loader.php?plugin=AP_Custom_BBCode.php', 'Successfully deleted tag "'.$name.'" from database');
}
else if($_GET['action'] == 'edit') { // If the user is editing a tag
	$name = $db->escape($_GET['name']);
	$type = intval($_GET['type']);
	if(($type != 1) && ($type != 2))
		$type = 1;
	$tag = $db->fetch_assoc($db->query('SELECT * FROM '.$db->prefix.'bbcode WHERE name=\''.$name.'\' AND type='.$type));
	// Display the admin navigation menu
	generate_admin_menu($plugin);

	?>
	<div class="blockform">
		<h2><span><?php echo $lang_custom_bbcode['edit tag']; ?></span></h2>
		<div class="box">
			<div class="inform">
				<form method="POST" action="?plugin=AP_Custom_BBCode.php&amp;action=doedit">
					<a href="?plugin=AP_Custom_BBCode.php"><?php echo $lang_custom_bbcode['back']; ?></a>
					<p><?php echo $lang_custom_bbcode['instructions']; ?></p>
					<p><?php echo $lang_custom_bbcode['instruct name']; ?></p>
					<p><?php echo $lang_custom_bbcode['instruct type']; ?></p>
					<p><?php echo $lang_custom_bbcode['instruct note']; ?></p>
					<p><?php echo $lang_custom_bbcode['instruct output']; ?></p>
					<fieldset>
						<legend><?php echo $lang_custom_bbcode['editing']; ?> '<?php echo $name; ?>'</legend>
						<input type="hidden" name="oldname" value="<?php echo $name; ?>" />
						<input type="hidden" name="oldtype" value="<?php echo $tag['type']; ?>" />
						<p><?php echo $lang_custom_bbcode['name']; ?>: <input type="text" name="edit_name" value="<?php echo $name; ?>"/></p>
						<p><?php echo $lang_custom_bbcode['type']; ?>: <input type="radio" name="edit_type" value="1" <?php if($tag['type'] == 1) {echo 'checked=\'true\'';} ?> /> 1 <input type="radio" name="edit_type" value="2" <?php if($tag['type'] == 2) {echo 'checked=\'true\'';} ?> /> 2 </p>
						<p><?php echo $lang_custom_bbcode['output']; ?>:</p>
						<textarea name="edit_output" rows="10" cols="50"><?php echo $tag['output']; ?></textarea>
						<p><input type="submit" name="Submit" value="Submit" /></p>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<?php

}
else if($_GET['action'] == 'new') { // If the user is adding a tag
	// Display the admin navigation menu
	generate_admin_menu($plugin);
	?>
	<div class="blockform">
		<h2><span><?php echo $lang_custom_bbcode['new tag']; ?></span></h2>
		<div class="box">
			<div class="inform">
				<form method="POST" action="?plugin=AP_Custom_BBCode.php&amp;action=donew">
					<a href="?plugin=AP_Custom_BBCode.php"><?php echo $lang_custom_bbcode['back']; ?></a>
					<p><?php echo $lang_custom_bbcode['instructions']; ?></p>
					<p><?php echo $lang_custom_bbcode['instruct name']; ?></p>
					<p><?php echo $lang_custom_bbcode['instruct type']; ?></p>
					<p><?php echo $lang_custom_bbcode['instruct note']; ?></p>
					<p><?php echo $lang_custom_bbcode['instruct output']; ?></p>
					<fieldset>
						<legend><?php echo $lang_custom_bbcode['new tag']; ?></legend>
						<p><?php echo $lang_custom_bbcode['name']; ?>: <input type="text" name="add_name"/></p>
						<p><?php echo $lang_custom_bbcode['type']; ?>: <input type="radio" name="add_type" /> 1 <input type="radio" name="add_type" value="2" /> 2 </p>
						<p><?php echo $lang_custom_bbcode['output']; ?>:</p>
						<textarea name="add_output" rows="10" cols="50"></textarea>
						<p><input type="submit" name="Submit" value="Submit" /></p>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<?php

}
else if($_GET['action'] == 'doedit') { // Update the database for the tag (and update cache once implemented)
	$tags = $db->query('SELECT * FROM '.$db->prefix.'bbcode'); // get all existing tags for reference
	$oldname = $db->escape($_POST['oldname']);

	$oldtype = $db->escape($_POST['oldtype']);

	$name = $db->escape($_POST['edit_name']);

	$name = preg_replace('/[^a-zA-Z0-9]/', '', $name);

	$name = strtolower($name);

	$type = intval($_POST['edit_type']);
	if(($type != 1) && ($type != 2)) {
		$type = 1;
	}

	$output = $db->escape($_POST['edit_output']);

	if(($name != $oldname) || ($type != $oldtype)) {
		$tagscopy = $tags; // cycle through without using $tags
		while($row = $db->fetch_assoc($tagscopy)) {
			if(($name == $row['name']) && ($type == $row['type'])) {
				redirect('admin_loader.php?plugin=AP_Custom_BBCode.php&action=edit&name='.$oldname, 'Tag name and type combination is already in use');
			}
		}
	}

	$db->query('UPDATE '.$db->prefix.'bbcode SET name=\''.$name.'\', type='.$type.', output=\''.$output.'\' WHERE name=\''.$oldname.'\' AND type='.$oldtype) or redirect('admin_loader.php?plugin=AP_Custom_BBCode.php&action=edit&name='.$oldname, 'Edit failed');

	generate_bbcode_cache();
	redirect('admin_loader.php?plugin=AP_Custom_BBCode.php', 'Tag successfully updated');

}
else if($_GET['action'] == 'donew') { // Update the database for the tag (and update cache once implemented)
	$tags = $db->query('SELECT * FROM '.$db->prefix.'bbcode'); // get all existing tags for reference
	$name = $db->escape($_POST['add_name']);

	$name = preg_replace('/[^a-zA-Z0-9]/', '', $name);

	$name = strtolower($name);

	$type = intval($_POST['add_type']);
	if(($type != 1) && ($type != 2)) {
		$type = 1;
	}

	$output = $db->escape($_POST['add_output']);

	$tagscopy = $tags; // cycle through without using $tags
	while($row = $db->fetch_assoc($tagscopy)) {
		if(($name == $row['name']) && ($type == $row['type'])) {
			redirect('admin_loader.php?plugin=AP_Custom_BBCode.php&action=edit&name='.$oldname, 'Tag name and type combination is already in use');
		}
	}
	$db->query('INSERT INTO '.$db->prefix.'bbcode (name, type, output) VALUES (\''.$name.'\', '.$type.', \''.$output.'\')');
    if($db->affected_rows()==0)
        message("Unable to add bbcode: " . $db->error_msg);

	generate_bbcode_cache();
	redirect('admin_loader.php?plugin=AP_Custom_BBCode.php', 'Tag successfully added');

}
else {
	// Display the admin navigation menu
	generate_admin_menu($plugin);

?>
        <div id="exampleplugin" class="plugin blockform">
                <h2><span><?php echo $lang_custom_bbcode['custom bbcode']; ?></span></h2>
                <div class="box">
                        <div class="inbox">
                                <p><?php echo $lang_custom_bbcode['plugin']; ?></p>
                                <p><?php echo $lang_custom_bbcode['instructions']; ?></p>
                                <p><?php echo $lang_custom_bbcode['instruct add']; ?></p>
                                <p><?php echo $lang_custom_bbcode['instruct edit']; ?></p>
                                <p><?php echo $lang_custom_bbcode['instruct delete']; ?></p>
                        </div>
                </div>
                </div>


  <div class="blockform">
                <h2 class="block2"><span><?php echo $lang_custom_bbcode['edit bbcodes']; ?></span></h2>
                <div class="box">
                              <div class="inform">
                                      <fieldset>
                                              <legend><?php echo $lang_custom_bbcode['bbcode list']; ?></legend>
                                              <div class="infldset">
                                                      <table class="aligntop" cellspacing="0">
                                                      <tr>
                                                      	<th><?php echo $lang_custom_bbcode['name']; ?></th>
                                                      	<th><?php echo $lang_custom_bbcode['edit']; ?></th>
                                                      	<th><?php echo $lang_custom_bbcode['delete']; ?></th>
                                                      </tr>
                                                      <?php
                                                      /*
                                                      	Here we need code to display all of the Custom BBCode tags already made.
                                                      */
                                                      $tags = $db->query('SELECT * FROM '.$db->prefix.'bbcode');
                                                      while($row = $db->fetch_assoc($tags)) {
                                                        echo '<tr>';
                                                        echo '<td>';
                                                        echo $row['name'];
                                                        echo '</td>';
                                                        echo '<td>';
                                                        echo '<a href="?plugin=AP_Custom_BBCode.php&action=edit&name='.$row['name'].'&type='.$row['type'].'">Edit</a>';
                                                        echo '</td>';
                                                        echo '<td>';
                                                        echo '<a href="?plugin=AP_Custom_BBCode.php&action=delete&name='.$row['name'].'&type='.$row['type'].'">Delete</a>';
                                                        echo '</td>';
                                                        echo '</tr>';
                                                      }

                                                      ?>
                                                      </table>
                                                      <a href="?plugin=AP_Custom_BBCode.php&action=new"><?php echo $lang_custom_bbcode['add new']; ?></a>
                                              </div>
                                      </fieldset>
                              </div>
                </div>
        </div>
        </div>
<?php
}
