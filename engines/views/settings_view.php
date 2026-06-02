<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

Auth::authorization();

	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

	if($_POST['action']){
		core::user()->setUser_id($_SESSION['user_id']);
		$user = core::user()->getUserInfo();
		core::user()->setUserActivity();
	
		$fields = Array();
		$fields['contact_email'] = htmlspecialchars(trim($_POST['user']['contact_email']));
		$fields['phone'] = htmlspecialchars(trim($_POST['user']['phone']));	
		$fields['skype'] = htmlspecialchars(trim($_POST['user']['skype']));	
		$fields['website'] = htmlspecialchars(trim($_POST['user']['website']));	
		$fields['permission_send_message'] = is_numeric($_POST['user']['permission_send_message']) ? $_POST['user']['permission_send_message'] : 0;
		$fields['permission_view_profile'] = is_numeric($_POST['user']['permission_view_profile']) ? $_POST['user']['permission_view_profile'] : 0;
		$fields['permission_view_friends'] = is_numeric($_POST['user']['permission_view_friends']) ? $_POST['user']['permission_view_friends'] : 0;
		$fields['permission_view_photo'] = is_numeric($_POST['user']['permission_view_photo']) ? $_POST['user']['permission_view_photo'] : 0;
		$fields['permission_view_video'] = is_numeric($_POST['user']['permission_view_video']) ? $_POST['user']['permission_view_video'] : 0;
		$fields['permission_view_wall'] = is_numeric($_POST['user']['permission_view_wall']) ? $_POST['user']['permission_view_wall'] : 0;
		$fields['permission_comment_photo'] = is_numeric($_POST['user']['permission_comment_photo']) ? $_POST['user']['permission_comment_photo'] : 0;
		$fields['permission_comment_video'] = is_numeric($_POST['user']['permission_comment_video']) ? $_POST['user']['permission_comment_video'] : 0;
		$fields['permission_comment_wall'] = is_numeric($_POST['user']['permission_comment_wall']) ? $_POST['user']['permission_comment_wall'] : 0;	
		$fields['notification_friends_request'] = $_POST['user']['notification_friends_request'] == 'on' ? "yes" : "no";	
		$fields['notification_private_messages'] = $_POST['user']['notification_private_messages'] == 'on' ? "yes" : "no";	
		$fields['notification_wall_comments'] = $_POST['user']['notification_wall_comments'] == 'on' ? "yes" : "no";	
		$fields['notification_picture_comments'] = $_POST['user']['notification_picture_comments'] == 'on' ? "yes" : "no";	
		$fields['notification_video_comments'] = $_POST['user']['notification_video_comments'] == 'on' ? "yes" : "no";	
		$fields['notification_events'] = $_POST['user']['notification_events'] == 'on' ? "yes" : "no";	
		$fields['notification_birthdays'] = $_POST['user']['notification_birthdays'] == 'on' ? "yes" : "no";	
		$fields['notification_answers_in_comments'] = $_POST['user']['notification_answers_in_comments'] == 'on' ? "yes" : "no";	
		
		$file_ava = Core_Array::getRequest('file_ava');
		$cover_page = Core_Array::getRequest('file_cover');
		if($file_ava) $fields['avatar'] = basename($file_ava);
		if($cover_page) $fields['cover_page'] = basename($cover_page);

		if($data->changeUserSettings($fields, $user['id'])){
			$success_msg = core::getLanguage('msg', 'changes_added');
		}
		else{
			$error_msg = core::getLanguage('error', 'web_apps_error');
		}
	}

	core::user()->setUser_id($_SESSION['user_id']);
	$user = core::user()->getUserInfo();
	core::user()->setUserActivity();

	$tpl->assign('NUMBERMESSAGE', core::user()->MessageNotification());
	$tpl->assign('NUMBERINVITATION', core::user()->AddFriendsNotification());

	$css = array();
	$css[] = './templates/css/bootstrap-theme.min.css';
	$css[] = './templates/css/bootstrap.min.css';
	$css[] = './templates/css/style.css';
	$css[] = './templates/css/select2-bootstrap.css';
	$css[] = './templates/css/select2.css';

	foreach($css as $row){
		$rowBlock = $tpl->fetch('row_css_list');
		$rowBlock->assign('CSS', core::documentparser()->showCSS($row));
		$tpl->assign('row_css_list', $rowBlock);
	}

	$js = array();
	$js[] = './templates/js/jquery-3.7.1.min.js';

	foreach($js as $row){
		$rowBlock = $tpl->fetch('row_js_list');
		$rowBlock->assign('JS', core::documentparser()->showJS($row));
		$tpl->assign('row_js_list', $rowBlock);
	}

	if(!empty($error_msg)){
		$tpl->assign('MSG_ERROR_ALERT', $error_msg);
	}

	if(!empty($success_msg)) $tpl->assign('MSG_ALERT', $success_msg);

	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";
	
	$tpl->assign('EDIT_COVER', 'yes');
	$tpl->assign('EDIT_AVATAR', 'yes');
	$tpl->assign('EDIT_COVER_TOP', 'yes');
	$tpl->assign('TEAM_AVATAR', $profile_avatar);
	$tpl->assign('TEAM_COVER_PAGE', $profile_cover_page);

	$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'settings'));	

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));
	$tpl->assign('STR_CONTACTS', core::getLanguage('str', 'contacts'));
	$tpl->assign('STR_PRIVACY', core::getLanguage('str', 'privacy'));
	$tpl->assign('STR_SECURITY', core::getLanguage('str', 'security'));
	$tpl->assign('STR_NOTIFICATIONS', core::getLanguage('str', 'notifications'));
	$tpl->assign('STR_BLACK_LIST', core::getLanguage('str', 'black_list'));
	$tpl->assign('STR_FRIENDS_REQUEST', core::getLanguage('str', 'friends_request'));
	$tpl->assign('STR_PRIVATE_MESSAGES', core::getLanguage('str', 'private_messages'));
	$tpl->assign('STR_WALL_COMMENTS', core::getLanguage('str', 'wall_comments'));
	$tpl->assign('STR_PICTURE_COMMENTS', core::getLanguage('str', 'picture_comments'));
	$tpl->assign('STR_VIDEO_COMMENTS', core::getLanguage('str', 'video_comments'));
	$tpl->assign('STR_BIRTHDAYS', core::getLanguage('str', 'birthdays'));
	$tpl->assign('STR_EMAIL', core::getLanguage('str', 'email'));
	$tpl->assign('STR_CELLPHONE', core::getLanguage('str', 'cellphone'));
	$tpl->assign('STR_PERMISSION_SEND_MESSAGE', core::getLanguage('str', 'permission_send_message'));
	$tpl->assign('STR_ALL', core::getLanguage('str', 'all'));
	$tpl->assign('STR_FRIENDS', core::getLanguage('str','friends'));
	$tpl->assign('STR_NOBODY', core::getLanguage('str','nobody'));
	$tpl->assign('STR_PERMISSION_VIEW_PROFILE', core::getLanguage('str', 'permission_view_profile'));
	$tpl->assign('STR_PERMISSION_VIEW_FRIENDS', core::getLanguage('str', 'permission_view_friends'));
	$tpl->assign('STR_PERMISSION_VIEW_PHOTO', core::getLanguage('str', 'permission_view_photo'));
	$tpl->assign('STR_PERMISSION_VIEW_VIDEO', core::getLanguage('str', 'permission_view_video'));
	$tpl->assign('STR_PERMISSION_VIEW_WALL', core::getLanguage('str', 'permission_view_wall'));
	$tpl->assign('STR_PERMISSION_COMMENT_PHOTO', core::getLanguage('str', 'permission_comment_photo'));
	$tpl->assign('STR_PERMISSION_COMMENT_VIDEO', core::getLanguage('str', 'permission_comment_video'));
	$tpl->assign('STR_PERMISSION_COMMENT_WALL', core::getLanguage('str', 'permission_comment_wall'));
	$tpl->assign('STR_NOTIFICATION_ANSWERS_IN_COMMENTS', core::getLanguage('str', 'notification_answers_in_comments'));
	$tpl->assign('STR_PERSONAL_WEBSITE', core::getLanguage('str', 'personal_website'));
	
	$usersettings = core::user()->getUserSetting();

	$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
	$tpl->assign('CONTACT_EMAIL', $_POST['contact_email'] ? $_POST['contact_email'] : $user['contact_email']);
	$tpl->assign('PHONE', $_POST['phone'] ? $_POST['phone'] : $user['phone']);
	$tpl->assign('SKYPE', $_POST['skype'] ? $_POST['skype'] : $user['skype']);
	$tpl->assign('WEBSITE', $_POST['website'] ? $_POST['website'] : $user['website']);

	$tpl->assign('PERMISSION_SEND_MESSAGE', $_POST['user']['permission_send_message'] ? $_POST['user']['permission_send_message'] : $usersettings['permission_send_message']);
	$tpl->assign('PERMISSION_VIEW_PROFILE', $_POST['user']['permission_view_profile'] ? $_POST['user']['permission_view_profile'] : $usersettings['permission_view_profile']);
	$tpl->assign('PERMISSION_VIEW_FRIENDS', $_POST['user']['permission_view_friends'] ? $_POST['user']['permission_view_friends'] : $usersettings['permission_view_friends']);
	$tpl->assign('PERMISSION_VIEW_PHOTO', $_POST['user']['permission_view_photo'] ? $_POST['user']['permission_view_photo'] : $usersettings['permission_view_photo']);
	$tpl->assign('PERMISSION_VIEW_VIDEO', $_POST['user']['permission_view_video'] ? $_POST['user']['permission_view_video'] : $usersettings['permission_view_video']);
	$tpl->assign('PERMISSION_VIEW_WALL', $_POST['user']['permission_view_wall'] ? $_POST['user']['permission_view_wall'] : $usersettings['permission_view_wall']);
	$tpl->assign('PERMISSION_COMMENT_PHOTO', $_POST['user']['permission_comment_photo'] ? $_POST['user']['permission_comment_photo'] : $usersettings['permission_comment_photo']);
	$tpl->assign('PERMISSION_COMMENT_VIDEO', $_POST['user']['permission_comment_video'] ? $_POST['user']['permission_comment_video'] : $usersettings['permission_comment_video']);
	$tpl->assign('PERMISSION_COMMENT_WALL', $_POST['user']['permission_comment_wall'] ? $_POST['user']['permission_comment_wall'] : $usersettings['permission_comment_wall']);

	$tpl->assign('%NUMBERUSERS', core::user()->NumberUsers());
	$tpl->assign('STR_YOUR_BLACKLIST_HAS_PAGES', str_replace('%NUMBERUSERS%', core::user()->NumberUsers(), core::getLanguage('str', 'your_blacklist_has_pages')));

	foreach(core::user()->getBlockUsersList() as $row){
		$rowBlock = $tpl->fetch('row_block_users');
		$rowBlock->assign('ID_USER', $row['friend_id']);	
		$rowBlock->assign('FIRSTNAME', $row['firstname']);
		$rowBlock->assign('LASTNAME', $row['lastname']);	
		$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));	
		$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));
		$tpl->assign('row_block_users', $rowBlock);	
	}

	$tpl->assign('MY_IP', core::documentparser()->getIP());

	foreach(core::user()->getLastActivity(10) as $row)
	{
		$rowBlock = $tpl->fetch('row_logs');	
		$rowBlock->assign('IP', $row['ip']);
		$rowBlock->assign('OS', core::documentparser()->getOS($row['user_agent']));	
		$rowBlock->assign('BROWSER', core::documentparser()->getUserBrowser($row['user_agent']));	
		$rowBlock->assign('TIME', $row['time']);	
		$tpl->assign('row_logs', $rowBlock);	
	}

	$tpl->assign('NOTIFICATION_FRIENDS_REQUEST', $_POST['user']['notification_friends_request'] ? ($_POST['user']['notification_friends_request'] == 'on' ? "yes" : "no") : $usersettings['notification_friends_request']);
	$tpl->assign('NOTIFICATION_PRIVATE_MESSAGES', $_POST['user']['notification_private_messages'] ? ($_POST['user']['notification_private_messages'] == 'on' ? "yes" : "no") : $usersettings['notification_private_messages']);
	$tpl->assign('NOTIFICATION_WALL_COMMENTS', $_POST['user']['notification_wall_comments'] ? ($_POST['user']['notification_wall_comments'] == 'on' ? "yes" : "no") : $usersettings['notification_wall_comments']);
	$tpl->assign('NOTIFICATION_PICTURE_COMMENTS', $_POST['user']['notification_picture_comments'] ? ($_POST['user']['notification_picture_comments'] == 'on' ? "yes" : "no") : $usersettings['notification_picture_comments']);
	$tpl->assign('NOTIFICATION_VIDEO_COMMENTS', $_POST['user']['notification_video_comments'] ? ($_POST['user']['notification_video_comments'] == 'on' ? "yes" : "no") : $usersettings['notification_video_comments']);
	$tpl->assign('NOTIFICATION_ANSWERS_IN_COMMENTS', $_POST['user']['notification_answers_in_comments'] ? ($_POST['user']['notification_answers_in_comments'] == 'on' ? "yes" : "no") : $usersettings['notification_answers_in_comments']);
	$tpl->assign('NOTIFICATION_EVENTS', $_POST['user']['notification_events'] ? ($_POST['user']['notification_events'] == 'on' ? "yes" : "no") : $usersettings['notification_events']);
	$tpl->assign('NOTIFICATION_BIRTHDAYS', $_POST['user']['notification_birthdays'] ? ($_POST['user']['notification_birthdays'] == 'on' ? "yes" : "no") : $usersettings['notification_birthdays']);

	$tpl->assign('BUTTON_APPLY', core::getLanguage('button', 'apply'));

	include_once "footer.inc";
		
	$tpl->display();
