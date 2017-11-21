//<?php
/**
 * DashboardLastWebUsers
 *
 * Last registered webusers widget for Evo 1.4
 *
 * @author    Nicola Lambathakis http://www.tattoocms.it
 * @category    plugin
 * @version    3.2.1 RC
 * @license	 http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @events OnManagerWelcomeHome,OnManagerMainFrameHeaderHTMLBlock
 * @internal    @installset base
 * @internal    @modx_category Dashboard
 * @internal    @properties  &wdgVisibility=Show widget for:;menu;All,AdminOnly,AdminExcluded,ThisRoleOnly,ThisUserOnly;All &ThisRole=Show only to this role id:;string;;;enter the role id &ThisUser=Show only to this username:;string;;;enter the username  &wdgTitle= Widget Title:;string;Last Webuser  &wdgicon= widget icon:;string;fa-users  &wdgposition=widget position:;list;1,2,3,4,5,6,7,8,9,10;1 &wdgsizex=widget x size:;list;12,6,4,3;12 &LastUsersLimit=How many users:;string;10 &EnablePopup= Enable popup icon:;list;no,yes;yes &EnablePhoto= Enable user photo:;list;no,yes;no &showDeleteButton= Show Delete Button:;list;yes,no;yes &WidgetID= Unique Widget ID:;string;LastWebUserBox &HeadBG= Widget Title Background color:;string; &HeadColor= Widget title color:;string;
 */

/******
DashboardLastWebUsers 3.2.1 RC
OnManagerWelcomeHome

&wdgVisibility=Show widget for:;menu;All,AdminOnly,AdminExcluded,ThisRoleOnly,ThisUserOnly;All &ThisRole=Show only to this role id:;string;;;enter the role id &ThisUser=Show only to this username:;string;;;enter the username  &wdgTitle= Widget Title:;string;Last Webuser  &wdgicon= widget icon:;string;fa-users  &wdgposition=widget position:;list;1,2,3,4,5,6,7,8,9,10;1 &wdgsizex=widget x size:;list;12,6,4,3;12 &LastUsersLimit=How many users:;string;10 &EnablePopup= Enable popup icon:;list;no,yes;yes &EnablePhoto= Enable user photo:;list;no,yes;no &showDeleteButton= Show Delete Button:;list;yes,no;yes &WidgetID= Unique Widget ID:;string;LastWebUserBox &HeadBG= Widget Title Background color:;string; &HeadColor= Widget title color:;string;
****
*/
// Run the main code
include($modx->config['base_path'].'assets/plugins/dashboardlastwebusers/lastwebuser.php');