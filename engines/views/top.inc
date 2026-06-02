<?php

if($_GET['id_user']){

	$id_user = core::database()->escape((int)Core_Array::getRequest('id_user'));	

	core::user()->setUser_id($id_user);
	$profile = core::user()->getUserInfo();
	$profile_avatar = core::documentparser()->userAvatar($profile);
	$profile_firstname = $profile["firstname"];
	$profile_lastname = $profile['lastname'];
	$profile_secondname = $profile['secondname'];
	$profile_about = $profile['about'];
	$profile_about_sport = $profile['about_sport'];
	$profile_cover_page = core::documentparser()->coverPage($profile);
	$profile_usersetting = core::user()->getUserSetting();	
 	$last_visit = core::user()->getUserLastVisit();	
	
	if(!core::user()->checkUserOnline($id_user)) {
		$was_online = core::getLanguage('str', 'last_visit');
		
		$was_online = str_replace('%DATE%', core::documentparser()->mysql_russian_datetime($last_visit['last_activity']), $was_online);
		$was_online = str_replace('%TIME%', $last_visit['time'], $was_online);	
		if ($was_online!=' в ')	
		$tpl->assign('USER_LAST_VISIT', $was_online);		
	}		
}
else if($_GET['id_community']){

	$profile_avatar = core::documentparser()->communityAvatar($community);
	$profile_about = Sport::getSportType($community['id_sport_type']);
	$profile_cover_page = core::documentparser()->communityCoverPage($community);
	
	$tpl->assign('PROFILE_COMMUNITY_NAME', $community["name"]);
	$tpl->assign('PROFILE_COMMUNITY_PLACE', $community["place"]);
	$tpl->assign('PROFILE_COMMUNITY_SPORT', $community["sport_type"]);
}
else{
	$profile_avatar = core::documentparser()->userAvatar($user);
	$profile_firstname = $user["firstname"];
	$profile_lastname = $user['lastname'];
	$profile_secondname = $user['secondname'];
	$profile_about = $user['about'];
	$profile_about_sport = $user['about_sport'];
	$profile_cover_page = core::documentparser()->coverPage($user);
}

if($_SESSION['user_authorization'] == "ok"){
	if($_GET['id_user'])
		$tpl->assign('STATUS_USER', core::user()->checkUserOnline(core::database()->escape((int)Core_Array::getRequest('id_user'))) ? 'online' : 'offline');
	else
		$tpl->assign('STATUS_USER', core::user()->checkUserOnline($user['id']) ? 'online' : 'offline');

	$my_avatar = core::documentparser()->userAvatar($user);
}

$tpl->assign('STR_LOGOUT', core::getLanguage('str', 'logout'));
$tpl->assign('STR_SEARCH', core::getLanguage('str', 'search'));
$tpl->assign('STR_CLOSE', core::getLanguage('str', 'close'));
$tpl->assign('MENU_PROFILE', core::getLanguage('menu', 'profile'));
$tpl->assign('MENU_EDITPROFILE', core::getLanguage('menu', 'editprofile'));
$tpl->assign('MENU_NEWS', core::getLanguage('menu', 'news'));
$tpl->assign('MENU_FRIENDS', core::getLanguage('menu', 'friends'));
$tpl->assign('MENU_SHARE', core::getLanguage('menu', 'share'));
$tpl->assign('MENU_PHOTOALBUMS', core::getLanguage('menu', 'photoalbums'));
$tpl->assign('MENU_VIDEOALBUMS', core::getLanguage('menu', 'videoalbums'));
$tpl->assign('MENU_TEAMS', core::getLanguage('menu', 'teams'));
$tpl->assign('MENU_GROUPS', core::getLanguage('menu', 'groups'));
$tpl->assign('MENU_PLAYGROUNDS', core::getLanguage('menu', 'playgrounds'));
$tpl->assign('MENU_SHOPS', core::getLanguage('menu', 'shops'));
$tpl->assign('MENU_FITNESS', core::getLanguage('menu', 'fitness'));
$tpl->assign('MENU_EVENTS', core::getLanguage('menu', 'events'));
$tpl->assign('MENU_CALENDAR', core::getLanguage('menu', 'calendar'));
$tpl->assign('ID_USER', $_SESSION['id_user'] ? $_SESSION['id_user'] : $_COOKIE['id_user']);
$tpl->assign('PROFILE_FIRSTNAME', $profile_firstname);
$tpl->assign('PROFILE_LASTNAME', $profile_lastname);
$tpl->assign('PROFILE_SECONDNAME', $profile_secondname);
$tpl->assign('PROFILE_ABOUT', $profile_about);
$tpl->assign('PROFILE_ABOUT_SPORT', $profile_about_sport);
$tpl->assign('PROFILE_AVATAR', $profile_avatar);
$tpl->assign('PROFILE_COVER_PAGE', $profile_cover_page);
$tpl->assign('STR_INCORRECT_FIELD', core::getLanguage('str', 'incorrect_field'));
$tpl->assign('STR_ACCEPT', core::getLanguage('str', 'accept'));
$tpl->assign('STR_SEARCHING', core::getLanguage('str', 'searching'));
$tpl->assign('STR_USER_HAS_RESTRICTED_ACCESS_TO_THIS_SECTION', core::getLanguage('str', 'user_has_restricted_access_to_this_section'));

if ($_GET['id_user']) $tpl->assign('PROFILE_USER_ID', $_GET['id_user']); 

$tpl->assign('TOP_FIRSTNAME', $user['firstname']);
$tpl->assign('TOP_LASTNAME', $user['lastname']);
$tpl->assign('TOP_AVATAR', $my_avatar);
$tpl->assign('STR_WAS_ONLINE', core::getLanguage('str', 'was_online'));
$tpl->assign('TOP_BUTTON_INVITE_FRIENDS', core::getLanguage('button', 'invite_friends'));
$tpl->assign('TOP_BUTTON_EDIT', core::getLanguage('button', 'edit'));
$tpl->assign('STR_YOU_HAVE_SENT_REQUEST', core::getLanguage('str', 'you_have_sent_request'));
$tpl->assign('STR_ADD_COVER_PHOTO', core::getLanguage('str', 'add_cover_photo'));
$tpl->assign('STR_CHANGE_COVER_PHOTO', core::getLanguage('str', 'add_cover_photo'));

if($_GET['id_user'] && $user['id'] != $profile['id'] && $profile['banned'] != 1 && $profile['deleted'] != 1 && $_SESSION['user_authorization'] == "ok") {
	$tpl->assign('SHOW_MESSAGES_LINK', 'show');		
	$tpl->assign('BUTTON_TOP_SEND_MESSAGE', core::getLanguage('button', 'top_send_message'));		
			
	if(core::user()->permissionUser($user['id'], $profile_usersetting['permission_view_profile'])) $tpl->assign('PROFILELINKS', 'show');

	if(core::user()->permissionUser($user['id'], $profile_usersetting['permission_view_photo'])) 
		$tpl->assign("PROFILE_PHOTO_PERMIT", "display");
	else
		$tpl->assign("PROFILE_PHOTO_PERMIT", "hide");
	
	if(core::user()->permissionUser($user['id'], $profile_usersetting['permission_view_video'])) 
		$tpl->assign("PROFILE_VIDEO_PERMIT", "display");
	else
		$tpl->assign("PROFILE_VIDEO_PERMIT", "hide");
	
	if(core::user()->permissionUser($user['id'], $profile_usersetting['permission_view_friends'])) 
		$tpl->assign("PROFILE_FRIENDS_PERMIT", "display");	
	else
		$tpl->assign("PROFILE_FRIENDS_PERMIT", "hide");

	$tpl->assign("PROFILE_TEAMS_PERMIT", "display");			
	$tpl->assign('PROFILE_ID_USER', $profile['id']);	
	$tpl->assign('STR_PROFILE_PHOTO_LINK', core::getLanguage('str', 'profile_photo_link'));		
	$tpl->assign('STR_PROFILE_VIDEO_LINK', core::getLanguage('str', 'profile_video_link'));		
	$tpl->assign('STR_PROFILE_FRIENDS_LINK', core::getLanguage('str', 'profile_friends_link'));		
	$tpl->assign('STR_PROFILE_GROUPS_LINK', core::getLanguage('str', 'profile_groups_link'));		
	$tpl->assign('STR_PROFILE_TEAMS_LINKS', core::getLanguage('str', 'profile_teams_links'));	
	$tpl->assign('TASK', $_GET['task']);
}
else if($_SESSION['user_authorization'] != "ok" && $_GET['id_user']){

	if($profile_usersetting['permission_view_profile'] != 1 || $profile_usersetting['permission_view_profile'] != 2) $tpl->assign('PROFILELINKS', 'show');
	
	if($profile_usersetting['permission_view_photo'] != 1 || $profile_usersetting['permission_view_photo'] != 2) 
		$tpl->assign("PROFILE_PHOTO_PERMIT", "display");	
	else
		$tpl->assign("PROFILE_PHOTO_PERMIT", "hide");
		
	if($profile_usersetting['permission_view_video'] != 1 || $profile_usersetting['permission_view_video'] != 2) 
		$tpl->assign("PROFILE_VIDEO_PERMIT", "display");	
	else
		$tpl->assign("PROFILE_VIDEO_PERMIT", "hide");

	if($profile_usersetting['permission_view_friends'] != 1 || $profile_usersetting['permission_view_friends'] != 2) 
		$tpl->assign("PROFILE_FRIENDS_PERMIT", "display");	
	else
		$tpl->assign("PROFILE_FRIENDS_PERMIT", "hide");			
		
	$tpl->assign("PROFILE_TEAMS_PERMIT", "hide");			
	$tpl->assign('PROFILE_ID_USER', $profile['id']);	
	$tpl->assign('STR_PROFILE_PHOTO_LINK', core::getLanguage('str', 'profile_photo_link'));		
	$tpl->assign('STR_PROFILE_VIDEO_LINK', core::getLanguage('str', 'profile_video_link'));		
	$tpl->assign('STR_PROFILE_FRIENDS_LINK', core::getLanguage('str', 'profile_friends_link'));		
	$tpl->assign('STR_PROFILE_GROUPS_LINK', core::getLanguage('str', 'profile_groups_link'));		
	$tpl->assign('STR_PROFILE_TEAMS_LINKS', core::getLanguage('str', 'profile_teams_links'));	
}
			
if($profile['id'] && $profile['id'] != $user['id'] && $profile['banned'] != 1 && $profile['deleted'] != 1 && $_SESSION['user_authorization'] == "ok") {
	$friendship = core::user()->getFriendship($user['id']);	

	if(core::user()->checkInvited($user['id']) && !core::user()->checkBlock($user['id']))
		$friendsstatus = 'invated';
	else if($profile['id'] == $friendship['id_friend'] && $friendship['status'] == '0')
		$friendsstatus = 'invitation_sent';
	else if(core::user()->checkBlock($user['id']))
		$friendsstatus = 'block';
	else if(core::user()->checkFriends($user['id'], 1))
		$friendsstatus = 'friend';
	else
		$friendsstatus = 'nofriend';			
	
	$tpl->assign('SHOW_FRIENDS_BUTTON', 'show');			
	$tpl->assign('BUTTON_TOP_ADD_AS_FRIEND', core::getLanguage('button', 'top_add_as_friend'));
	$tpl->assign('FRIENDSSTATUS', $friendsstatus);	

	$tpl->assign('BUTTON_TOP_REMOVE_FRIEND', core::getLanguage('button', 'top_remove_friend'));	
	$tpl->assign('STR_INVITATION_SENT', core::getLanguage('str', 'invitation_sent'));		
	$tpl->assign('BUTTON_TOP_ACCEPT_FRIENDSHIP', core::getLanguage('button', 'top_accept_friendship'));
}

if($_SESSION['user_authorization'] == "ok"){	
	$tpl->assign('BUTTON_TOP_UNBLOCK_USER', core::getLanguage('button', 'top_unblock_user'));
	$tpl->assign('BUTTON_TOP_BLOCK_USER', core::getLanguage('button', 'top_block_user'));
	$tpl->assign('NUMBER_INVITED_ME', Events::getNumberInvitedMeEvents($user['id'], 'user'));
	$tpl->assign('NUMBER_INVITED_ME_TEAM', Communities::getNumberInvitedMeCommunities($user['id'], 'team'));
	$tpl->assign('NUMBER_INVITED_ME_GROUP', Communities::getNumberInvitedMeCommunities($user['id'], 'group'));
}