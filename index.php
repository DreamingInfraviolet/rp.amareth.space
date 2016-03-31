<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

define('PUN_ROOT', dirname(__FILE__).'/');
require PUN_ROOT.'include/common.php';

if ($pun_user['g_read_board'] == '0')
	message($lang_common['No view'], false, '403 Forbidden');

$forum_actions = array();

$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']));
define('PUN_ALLOW_INDEX', 1);
define('PUN_ACTIVE_PAGE', 'index');
require PUN_ROOT.'header.php';




//////////////////////////////////

?>
<a href="newroleplay.php">New Roleplay</a>
<table>
    <tr><td>Anima Seteine</td><td>Sapphire Moon</td><td>Welcome to the land of the lost ;)</td></tr>
    <tr><td>Anima Seteine</td><td>Sapphire Moon</td><td>Welcome to the land of the lost ;)</td></tr>
    <tr><td>Anima Seteine</td><td>Sapphire Moon</td><td>Welcome to the land of the lost ;)</td></tr>
</table>

<?php

/////////////////////////////////



$footer_style = 'home';
require PUN_ROOT.'footer.php';
