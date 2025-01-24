<?php
if(!defined('MODX_BASE_PATH')){die('Invalid access');}

// Input sanitization
$ThisRole = isset($ThisRole) ? $modx->db->escape($ThisRole) : '';
$ThisUser = isset($ThisUser) ? $modx->db->escape($ThisUser) : '';
$HeadColor = isset($HeadColor) ? $modx->db->escape($HeadColor) : '';
$HeadBG = isset($HeadBG) ? $modx->db->escape($HeadBG) : '';
$LastUsersA = '';
$thPhoto = isset($thPhoto) ? $modx->db->escape($thPhoto) : '';
$jsOutput = isset($jsOutput) ? $modx->db->escape($jsOutput) : '';
$DateFormat = isset($DateFormat) ? $modx->db->escape($DateFormat) : 'd-m-Y H:i:s';
$LastUsersLimit = isset($LastUsersLimit) ? (int)$LastUsersLimit : 10;
$EnablePhoto = isset($EnablePhoto) ? $modx->db->escape($EnablePhoto) : 'no';
$EnablePopup = isset($EnablePopup) ? $modx->db->escape($EnablePopup) : 'no';
$showDeleteButton = isset($showDeleteButton) ? $modx->db->escape($showDeleteButton) : 'no';
$wdgVisibility = isset($wdgVisibility) ? $modx->db->escape($wdgVisibility) : '';
$wdgposition = isset($wdgposition) ? (int)$wdgposition : 0;
$wdgsizex = isset($wdgsizex) ? (int)$wdgsizex : 12;
$wdgicon = isset($wdgicon) ? $modx->db->escape($wdgicon) : 'fa-users';
$wdgTitle = isset($wdgTitle) ? $modx->db->escape($wdgTitle) : 'Last Web Users';

// Manager role
$internalKey = $modx->getLoginUserID();
$role = $_SESSION['mgrRole'];
$user = $_SESSION['mgrShortname'];

// Access control
if(($role!=1 && $wdgVisibility == 'AdminOnly') || 
   ($role==1 && $wdgVisibility == 'AdminExcluded') || 
   ($role!=$ThisRole && $wdgVisibility == 'ThisRoleOnly') || 
   ($user!=$ThisUser && $wdgVisibility == 'ThisUserOnly')) {
    return;
}

global $modx, $_lang;

// Get plugin id
$result = $modx->db->select('id', $modx->getFullTableName("site_plugins"), 
    "name='" . $modx->db->escape($modx->event->activePlugin) . "' AND disabled=0");
$pluginid = $modx->db->getValue($result);

if($modx->hasPermission('edit_plugin')) {
    $button_pl_config = '<a data-toggle="tooltip" href="javascript:;" title="' . htmlspecialchars($_lang["settings_config"], ENT_QUOTES) . '" class="text-muted pull-right float-right" onclick="parent.modx.popup({url:\''. MODX_MANAGER_URL.'?a=102&id='.$pluginid.'&tab=1\',title1:\'' . htmlspecialchars($_lang["settings_config"], ENT_QUOTES) . '\',icon:\'fa-cog\',iframe:\'iframe\',selector2:\'#tabConfig\',position:\'center center\',width:\'80%\',height:\'80%\',hide:0,hover:0,overlay:1,overlayclose:1})" ><i class="fa fa-cog" style="color:'.htmlspecialchars($HeadColor, ENT_QUOTES).';"></i> </a>';
}

$modx->setPlaceholder('button_pl_config', $button_pl_config);

// Tables
if (intval(substr($modx->config['settings_version'],0,1)) < 3) {
    $webuserstable = $modx->getFullTableName('web_users');
    $webuserattribstable = $modx->getFullTableName('web_user_attributes');
} else {
    $webuserstable = $modx->getFullTableName('users');
    $webuserattribstable = $modx->getFullTableName('user_attributes');
}

// Query with specific fields
$select = array(
    $webuserattribstable.'.id',
    $webuserattribstable.'.fullname',
    $webuserattribstable.'.email',
    $webuserattribstable.'.photo',
    $webuserattribstable.'.mobilephone',
    $webuserattribstable.'.phone',
    $webuserattribstable.'.gender',
    $webuserattribstable.'.country',
    $webuserattribstable.'.street',
    $webuserattribstable.'.city',
    $webuserattribstable.'.state',
    $webuserattribstable.'.zip',
    $webuserattribstable.'.createdon',
    $webuserstable.'.username'
);

$sql = 'SELECT '.implode(',', $select).' FROM '.$webuserattribstable.'
    INNER JOIN '.$webuserstable.' ON '.$webuserattribstable.'.internalKey='.$webuserstable.'.id
    ORDER BY '.$webuserattribstable.'.id DESC LIMIT '.(int)$LastUsersLimit;

$result = $modx->db->query($sql);

if(!$result) {
    return 'Query error: ' . $modx->db->getError();
}

while ($row = $modx->db->getRow($result)) {
    // Sanitize user data
    foreach(['username','fullname','email','city','street','state','zip'] as $field) {
        $row[$field] = htmlspecialchars($row[$field], ENT_QUOTES, 'UTF-8');
    }
    
    $userimage = !empty($row['photo']) ? 
        htmlspecialchars($row['photo'], ENT_QUOTES) : 
        'assets/plugins/dashboardlastwebusers/user.png';

    // Gender translation
	$usergender = $row['gender'];
	switch((int)$row['gender']) {
		case 0:
			$usergender = $_lang['user_other'];
			break;
		case 1:
			$usergender = $_lang['user_male'];
			break;
		case 2:
			$usergender = $_lang['user_female'];
			break;
	}
        
    $colspan = $EnablePhoto == 'yes' ? '7' : '6';
    
    if ($EnablePhoto == 'yes') {
        $thPhoto = '<th>' . $_lang['user_photo'] . '</th>';
        $LastUsersA .= '<tr><td data-toggle="collapse" data-target=".collapse-user'.$row['id'].'"><img src="../'.$userimage.'" class="img-responsive img-user" height="60" width="60"></td><td><span class="label label-info">'.$row['id'].'</span></td><td><a target="main" href="index.php?a=88&id='.$row['id'].'"><b>'.$row['username'].'</b></a></td><td data-toggle="collapse" data-target=".collapse-user'.$row['id'].'">'.$row['fullname'].'</td><td data-toggle="collapse" data-target=".collapse-user'.$row['id'].'">'.$row['email'].'</td><td data-toggle="collapse" data-target=".collapse-user'.$row['id'].'">'.date($DateFormat, $row['createdon']).'</td><td style="text-align: right;" class="actions">';
    } else {
        $LastUsersA .= '<td data-toggle="collapse" data-target=".collapse-user'.$row['id'].'" width="5%"><span class="label label-info">'.$row['id'].'</span></td><td data-toggle="collapse" data-target=".collapse-user'.$row['id'].'"><a target="main" href="index.php?a=88&id='.$row['id'].'"><b>'.$row['username'].'</b></a></td><td data-toggle="collapse" data-target=".collapse-user'.$row['id'].'">'.$row['fullname'].'</td><td data-toggle="collapse" data-target=".collapse-user'.$row['id'].'">'.$row['email'].'</td><td data-toggle="collapse" data-target=".collapse-user'.$row['id'].'">'.date($DateFormat, $row['createdon']).'</td><td style="text-align: right;" class="actions">';
    }

    if ($EnablePopup == 'yes') {
        $LastUsersA .= '<a onclick="window.open(\'index.php?a=88&id='.$row['id'].'\',\'WebUser\',\'width=800,height=600,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')" style="cursor: pointer;"><i class="fa fa-external-link"></i></a> ';
    } else {
        $LastUsersA .= '<a target="main" href="index.php?a=88&id='.$row['id'].'"><i class="fa fa-pencil-square-o"></i></a> ';
    }

    if ($showDeleteButton == 'yes') {
        $LastUsersA .= ' <a onclick="return confirm(\''.htmlspecialchars($_lang['confirm_delete_user'], ENT_QUOTES).'\')" target="main" href="index.php?a=90&id='.$row['id'].'"><i class="fa fa-trash"></i></a> ';
    }

    $LastUsersA .= '<span class="user_overview"><a title="overview" data-toggle="collapse" data-target=".collapse-user'.$row['id'].'"><i class="fa fa-info" aria-hidden="true"></i></a></span></td></tr>
    <tr class="resource-overview-accordian collapse collapse-user'.$row['id'].'"><td colspan="'.$colspan.'" class="hiddenRow"><div class="overview-body text-small">
    <div class="col-sm-6">
    <ul class="list-group">
    <li>'.$_lang['user_email'].': <b>'.$row['email'].'</b></li>
    <li>'.$_lang['user_mobile'].': <b>'.$row['mobilephone'].'</b></li>
    <li>'.$_lang['user_phone'].': <b>'.$row['phone'].'</b></li>
    <li>'.$_lang['user_gender'].': <b>'.$usergender.'</b></li>
    </ul>
    </div>
    <div class="col-sm-6">
    <ul class="list-group">
    <li>'.$_lang['user_city'].': <b>'.$row['city'].'</b></li>
    <li>'.$_lang['user_street'].' : <b>'.$row['street'].'</b></li>
    <li>'.$_lang['user_state'].' : <b>'.$row['state'].'</b></li>
    <li>'.$_lang['user_zip'].': <b>'.$row['zip'].'</b></li>
    </ul>
    </div>
    </div>
    </td></tr>';
}

$WidgetOutput = '<table class="table data table-webusers"><thead><tr>'.$thPhoto.'<th>'.$_lang['id'].'</th><th>'.$_lang['name'].'</th><th>'.$_lang['user_full_name'].'</th><th>'.$_lang['user_email'].'</th><th>'.$_lang['date'].'</th><th style="width: 1%; text-align: center">[%mgrlog_action%]</th></tr></thead><tbody>'.$LastUsersA.'</tbody></table>';

$e = & $modx->Event;
switch ($e->name) {
    case 'OnManagerMainFrameHeaderHTMLBlock':
        $manager_theme = $modx->config['manager_theme'];
        $cssFile = $manager_theme == "EvoFLAT" ? 'style_flat.css' : 'style.css';
        $e->output('<link type="text/css" rel="stylesheet" href="../assets/plugins/dashboardlastwebusers/css/'.$cssFile.'">');
        break;
        
    case 'OnManagerWelcomeHome':
        $widgets['DashboardWU'] = array(
            'menuindex' => $wdgposition,
            'id' => 'DashboardWU'.$pluginid,
            'cols' => 'col-md-'.$wdgsizex,
            'headAttr' => 'style="background-color:'.$HeadBG.'; color:'.$HeadColor.';"',
            'bodyAttr' => '',
            'icon' => $wdgicon,
            'title' => $wdgTitle.' '.$button_pl_config,
            'body' => '<div class="widget-stage"><div id="DashboardUserList" class="table-responsive">'.$WidgetOutput.'</div></div>',
            'hide' => '0'
        );
        $e->output(serialize($widgets));
        break;
}