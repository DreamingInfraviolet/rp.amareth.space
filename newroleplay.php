<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

define('PUN_ROOT', dirname(__FILE__).'/');
require PUN_ROOT.'include/common.php';

if ($pun_user['g_read_board'] == '0' or $pun_user['is_guest'])
	message($lang_common['No view'], false, '403 Forbidden');


        if ($pun_config['o_feed_type'] == '1')
        	$page_head = array('feed' => '<link rel="alternate" type="application/rss+xml" href="extern.php?action=feed&amp;type=rss" title="'.$lang_common['RSS active topics feed'].'" />');
        else if ($pun_config['o_feed_type'] == '2')
        	$page_head = array('feed' => '<link rel="alternate" type="application/atom+xml" href="extern.php?action=feed&amp;type=atom" title="'.$lang_common['Atom active topics feed'].'" />');

$forum_actions = array();

$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']));
define('PUN_ALLOW_INDEX', 1);
define('PUN_ACTIVE_PAGE', 'index');
require PUN_ROOT.'header.php';




//////////////////////////////////

?>

<div class="form-horizontal">
    <div id="error">
    </div>

    <div class="form-group">
        <label>Title</label>
        <input class="form-control" type="text" id="title">
        <label>Introduction</label>
        <textarea class="form-control" id="firstPost"></textarea>
    </div>
    <div class="form-group">
        <label>Friends</label>
        <span id="friends"></span>
        <button type="button" onclick="addFriend()" class="btn">Add Friend</button>
    </div>
    <div class="form-group">
        <label><bold>Allow HTML</bold> - Check this if you want to allow members to
            include HTML in their posts (only allow if you trust all participants involved)</label>
        <input class="form-control" type="checkbox" id="allowHtml">
        <label><bold>Allow Multipost</bold> - Check this if you want to allow memers
            to add multiple posts in direct succession. Disabling this can be helpful
            to prevent post spamming.</label>
        <input class="form-control" type="checkbox" id="allowMultipost">
    </div>
    <div class="form-group">
        <input class="form-control" type="submit" onclick="creatRp()">
    </div>
</div>

<?php

/////////////////////////////////



$footer_style = 'newrp';
require PUN_ROOT.'footer.php';
