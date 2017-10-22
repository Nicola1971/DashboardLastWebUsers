<?php
/******
dashboardlastwebusers  3.1.3 RC

@properties &wdgVisibility=Show widget for:;menu;All,AdminOnly,AdminExcluded,ThisRoleOnly,ThisUserOnly;All &ThisRole=Show only to this role id:;string;;;enter the role id &ThisUser=Show only to this username:;string;;;enter the username  &wdgTitle= widget Title:;string;Last Webuser  &wdgicon= widget icon:;string;fa-users  &wdgposition=widget position:;list;1,2,3,4,5,6,7,8,9,10;1 &wdgsizex=widget x size:;list;12,6,4,3;12 &LastUsersLimit=How many users:;string;10 &EnablePopup= Enable popup icon:;list;no,yes;yes &EnablePhoto= Enable user photo:;list;no,yes;no &showDeleteButton= Show Delete Button:;list;yes,no;yes &WidgetID= Unique Widget ID:;string;LastWebUserBox
****
*/
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}
// get manager role
$internalKey = $modx->getLoginUserID();
$sid = $modx->sid;
$role = $_SESSION['mgrRole'];
$user = $_SESSION['mgrShortname'];
// show widget only to Admin role 1
if(($role!=1) AND ($wdgVisibility == 'AdminOnly')) {}
// show widget to all manager users excluded Admin role 1
else if(($role==1) AND ($wdgVisibility == 'AdminExcluded')) {}
// show widget only to "this" role id
else if(($role!=$ThisRole) AND ($wdgVisibility == 'ThisRoleOnly')) {}
// show widget only to "this" username
else if(($user!=$ThisUser) AND ($wdgVisibility == 'ThisUserOnly')) {}
else {
// get language
global $modx,$_lang;
// get plugin id
$result = $modx->db->select('id', $this->getFullTableName("site_plugins"), "name='{$modx->event->activePlugin}' AND disabled=0");
$pluginid = $modx->db->getValue($result);
if($modx->hasPermission('edit_plugin')) {
$button_pl_config = '<a data-toggle="tooltip" href="javascript:;" title="' . $_lang["settings_config"] . '" class="text-muted pull-right" onclick="parent.modx.popup({url:\''. MODX_MANAGER_URL.'?a=102&id='.$pluginid.'&tab=1\',title1:\'' . $_lang["settings_config"] . '\',icon:\'fa-cog\',iframe:\'iframe\',selector2:\'#tabConfig\',position:\'center center\',width:\'80%\',height:\'80%\',wrap:\'evo-tab-page-home\',hide:0,hover:0,overlay:1,overlayclose:1})" ><i class="fa fa-cog"></i> </a>';
}
$modx->setPlaceholder('button_pl_config', $button_pl_config);

//widget name
$WidgetID = isset($WidgetID) ? $WidgetID : 'LastWebUserBox';
//output
$WidgetOutput = isset($WidgetOutput) ? $WidgetOutput : '';
// popup
$EnablePopup = isset($EnablePopup) ? $EnablePopup : 'no';
//events
$webuserstable = $modx->getFullTableName('web_users');
$webuserattribstable = $modx->getFullTableName('web_user_attributes');
$e = &$modx->Event;
$output ='';
	$result = $modx->db->query( 'SELECT '.$webuserattribstable.'.id, '.$webuserstable.'.id, '.$webuserattribstable.'.fullname, '.$webuserattribstable.'.email, '.$webuserattribstable.'.photo, '.$webuserattribstable.'.mobilephone, '.$webuserattribstable.'.phone,  '.$webuserattribstable.'.gender, '.$webuserattribstable.'.country, '.$webuserattribstable.'.street, '.$webuserattribstable.'.city, '.$webuserattribstable.'.state, '.$webuserattribstable.'.zip, '.$webuserstable.'.username FROM '.$webuserattribstable.' 
    INNER JOIN '.$webuserstable.'
    ON '.$webuserattribstable.'.id='.$webuserstable.'.id
    ORDER BY '.$webuserattribstable.'.id DESC LIMIT '.$LastUsersLimit.' ' );

while ($row = $modx->db->getRow($result))
	{
	global $_lang;
	$getuserimage = $row['photo'];
	if (empty($getuserimage))
		{
		$userimage = 'assets/plugins/dashboardlastwebusers/user.png'; //default image if tv is empty
		}
	  else
		{
		$userimage = $getuserimage;
		}

	$getusergender = $row['gender'];
	if ($getusergender == 0)
		{
		$usergender = $_lang['user_other'];
		}
	  else
	if ($getusergender == 1)
		{
		$usergender = $_lang['user_male'];
		}
	  else
	if ($getusergender == 2)
		{
		$usergender = $_lang['user_female'];
		}
	  else
		{
		$usergender = $getusergender;
		}
 if ($EnablePhoto == yes)
		{
        $colspan = '6';
        }
        else {
        $colspan = '5';
        }
	 if ($EnablePhoto == yes)
		{
        $thPhoto = '<th>' . $_lang['user_photo'] . '</th>';
		$LastUsersA.= '<tr><td data-toggle="collapse" data-target=".collapse-user' . $row['id'] . '"><img src="../' . $userimage . '" class="img-responsive img-user" height="60" width="60"> </td><td><span class="label label-info">' . $row['id'] . '</span> </td><td><a target="main" href="index.php?a=88&id=' . $row['id'] . ' "><b>' . $row['username'] . '</b></a></td>  <td>' . $row['fullname'] . '</td><td>' . $row['email'] . '  </td><td style="text-align: right;" class="actions">';
		}
	  else
		{
		$LastUsersA.= '<td data-toggle="collapse" data-target=".collapse-user' . $row['id'] . '" width="5%"><span class="label label-info">' . $row['id'] . '</span> </td><td><a target="main" href="index.php?a=88&id=' . $row['id'] . ' "><b>' . $row['username'] . '</b></a></td>  <td>' . $row['fullname'] . '</td><td>' . $row['email'] . '  </td><td style="text-align: right;" class="actions">';
		}

	if ($EnablePopup == yes)
		{
		$LastUsersA.= '<a onclick="window.open(\'index.php?a=88&id=' . $row['id'] . '\',\'WebUser\',\'width=800,height=600,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')" style="cursor: pointer;"> <i class="fa fa-external-link"></i> </a> ';
		}

	if ($EnablePopup == no)
		{
		$LastUsersA.= '
	<a target="main" href="index.php?a=88&id=' . $row['id'] . ' "><i class="fa fa-pencil-square-o"></i></a> ';
		}

	if ($showDeleteButton == yes)
		{
		$LastUsersA.= ' <a onclick="return confirm(\'' . $_lang['confirm_delete_user'] . '\')" target="main" href="index.php?a=90&id=' . $row['id'] . ' "><i class="fa fa-trash"></i></a> ';
		}

	$LastUsersA.= '<span class="user_overview"><a title="' . $_lang["overview"] . '" data-toggle="collapse" data-target=".collapse-user' . $row['id'] . '"><i class="fa fa-info" aria-hidden="true"></i></a></span></td></tr>
    <tr class="resource-overview-accordian collapse collapse-user' . $row['id'] . '"><td colspan="'.$colspan.'" class="hiddenRow"><div class="overview-body text-small">
    <div class="col-sm-6">
    <ul class="list-group">
    <li>' . $_lang['user_email'] . ': <b>' . $row['email'] . '</b></li>
    <li>' . $_lang['user_mobile'] . ': <b>' . $row['mobilephone'] . '</b></li>
    <li>' . $_lang['user_phone'] . ': <b>' . $row['phone'] . '</b></li>
    <li>' . $_lang['user_gender'] . ': <b>' . $usergender . '</b></li>
    </ul>
    </div>
    <div class="col-sm-6">
    <ul class="list-group">
    <li>' . $_lang['user_city'] . ': <b>' . $row['city'] . '</b></li>
    <li>' . $_lang['user_street'] . ' : <b>' . $row['street'] . '</b></li>
    <li>' . $_lang['user_state'] . ' : <b>' . $row['state'] . '</b></li>
    <li>' . $_lang['user_zip'] . ': <b>' . $row['zip'] . '</b></li>
    </ul>
    </div>
    </div>
    </td></tr>
    ';
	}

$WidgetOutput = '
	<table class="table data table-webusers"> 
      <thead>
      <tr>
        '.$thPhoto.'
        <th>' . $_lang['id'] . '</th>
        <th>' . $_lang['name'] . '</th>
        <th>' . $_lang['user_full_name'] . '</th>
        <th>' . $_lang['user_email'] . '</th>
        <th style="width: 1%; text-align: center">[%mgrlog_action%]</th>
      </tr>
    </thead><tbody>' . $LastUsersA . '</tbody></table>

';

// end widget

$e = & $modx->Event;
switch ($e->name)
	{
/*load styles with OnManagerMainFrameHeaderHTMLBlock*/
case 'OnManagerMainFrameHeaderHTMLBlock':
$cssOutput = '<link type="text/css" rel="stylesheet" href="../assets/plugins/dashboardlastwebusers/css/style.css">';
$e->output($cssOutput.$jsOutput);
break;
case 'OnManagerWelcomeHome':
	$widgets['DashboardWU'] = array(
		'menuindex' => '' . $wdgposition . '',
		'id' => 'DashboardWU' . $pluginid . '',
		'cols' => 'col-md-' . $wdgsizex . '',
		'icon' => '' . $wdgicon . '',
		'title' => '' . $wdgTitle . ' ' . $button_pl_config . '',
		'body' => '<div class="widget-stage"><div id="DashboardUserList" class="table-responsive">
				' . $WidgetOutput . ' </div></div>',
		'hide' => '0'
	);
	$e->output(serialize($widgets));
	break;
	}
}