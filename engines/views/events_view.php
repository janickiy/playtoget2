<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

if($_SESSION['user_authorization'] == "ok"){
	Auth::authorization();

	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

	core::user()->setUser_id($_SESSION['id_user']);
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

	$tpl->assign('STR_SHOW_MORE', core::getLanguage('str', 'show_more'));
	$tpl->assign('STR_START_TIME', core::getLanguage('str', 'start_time'));
	$tpl->assign('STR_END_TIME', core::getLanguage('str', 'end_time'));
	$tpl->assign('STR_SPECIFY_TIME_END', core::getLanguage('str', 'specify_time_end'));
	$tpl->assign('BUTTON_CHANGE_COVER', core::getLanguage('button', 'change_cover'));
	$tpl->assign('STR_YOUR_COMMENT', core::getLanguage('str', 'your_comment'));
	$tpl->assign('PHOTOALBUMABLE_TYPE', 'event');

	switch ($_REQUEST['action'])
	{
		case 'create_event':
	
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));		
			$id_sport_types = Core_Array::getRequest('id_sport_types');		
			$event_date_to = core::documentparser()->convertToDbFormat(trim(Core_Array::getRequest('event_date_to')));
			$event_hour_to = Core_Array::getRequest('event_hour_to');
			$event_minute_to = Core_Array::getRequest('event_minute_to');		
			$event_date_from = core::documentparser()->convertToDbFormat(trim(Core_Array::getRequest('event_date_from')));
			$event_hour_from = Core_Array::getRequest('event_hour_from');
			$event_minute_from = Core_Array::getRequest('event_minute_from');
			$description = trim(Core_Array::getRequest('description'));	
			$address = htmlspecialchars(trim(Core_Array::getRequest('address')));
			$id_place = Core_Array::getRequest('id_place');
			$id_sport = Core_Array::getRequest('id_sport');
			$file_cover = Core_Array::getRequest('file_cover');
		
			if(empty($name)) $error = core::getLanguage('error', 'not_all_fields_are_filled');
			if(empty($id_sport)) $error = core::getLanguage('error', 'not_all_fields_are_filled');
			if(empty($event_date_from)) $error = core::getLanguage('error', 'not_all_fields_are_filled');
			if(empty($description)) $error = core::getLanguage('error', 'not_all_fields_are_filled');	
		
			if(empty($error)){
				$fields = array();
				$fields['id'] = 0;
				$fields['name'] = $name;
						
				$fields['date_from'] = $event_date_from . " " . $event_hour_from . ":" . $event_minute_from . ":00";

				if($event_date_to && $event_hour_to && $event_minute_to) $fields['date_to'] = $event_date_to . " " . $event_hour_to . ":" . $event_minute_to . ":00";
			
				if(is_numeric($id_place)){
					$city = Places::getCityInfo($id_place);			
					if($city['name_ru']) $fields['place'] = $city['name_ru'];			
				}
			
				if(is_numeric($id_sport)){
					$sport_type = Sport::getSportType($id_sport);			
					if($sport_type) $fields['sport_type'] = $sport_type;
				}			
			
				$fields['description'] = $description;
				if($file_cover) $fields['cover_page'] = basename($file_cover);			
				$fields['created_at'] = date("Y-m-d H:i:s");			
				$fields['address'] = $address; 
			
				$id_event = Events::addEvent($fields, $user['id']);

				if($id_event){
				
					Events::addMember($id_event, $user['id'], 'user', 1);
					Places::addGeoTarget($id_event, 'event', $id_place);
				
					header("Location: ./?task=events&id_event=" . $id_event);
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}
			}
			$error_msg = $error;	
		
		break;

		case 'edit_event':

			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));		
			$id_event = core::database()->escape(Core_Array::getRequest('id_event'));		
			$event_date_from = core::documentparser()->convertToDbFormat(trim(Core_Array::getRequest('event_date_from')));
			$event_hour_from = Core_Array::getRequest('event_hour_from');
			$event_minute_from = Core_Array::getRequest('event_minute_from');		
			$event_date_to = core::documentparser()->convertToDbFormat(trim(Core_Array::getRequest('event_date_to')));
			$event_hour_to = Core_Array::getRequest('event_hour_to');
			$event_minute_to = Core_Array::getRequest('event_minute_to');		
			$description = trim(Core_Array::getRequest('description'));
			$address = htmlspecialchars(trim(Core_Array::getRequest('address')));
			$id_place = Core_Array::getRequest('id_place');
			$id_sport = Core_Array::getRequest('id_sport');
			$file_cover = Core_Array::getRequest('file_cover');		
		
			if(empty($name)) $error = core::getLanguage('error', 'not_all_fields_are_filled');
			if(empty($event_date_from)) $error = core::getLanguage('error', 'not_all_fields_are_filled');
			if(empty($description)) $error = core::getLanguage('error', 'not_all_fields_are_filled');
		
			if(empty($error) && Events::checkOwnerEvent($id_event, $user['id'], 'user')){
				$fields = array();
				$fields['name'] = $name;			
				$fields['date_from'] = $event_date_from . " " . $event_hour_from . ":" .$event_minute_from . ":00";
			
				if($event_date_to && $event_hour_to && $event_minute_to) $fields['date_to'] = $event_date_to . " " . $event_hour_to . ":" . $event_minute_to . ":00";			
			
				$fields['description'] = $description;
				$fields['address'] = $address;
				$fields['updated_at'] = date("Y-m-d H:i:s");			
		
				if(is_numeric($id_place)){
					$city = Places::getCityInfo($id_place);			
					if($city['name_ru']) $fields['place'] = $city['name_ru'];			
				}
			
				if(is_numeric($id_sport)){
					$sport_type = Sport::getSportType($id_sport);			
					if($sport_type) $fields['sport_type'] = $sport_type;
				}
	
				if($file_cover) $fields['cover_page'] = basename($file_cover);
			
				Places::addGeoTarget($id_event, 'event', $id_place);
			
				$result = Events::editEvent($fields, $id_event, $user['id']);
			
				if($result){
					header("Location: ./?task=events");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}
			}
			else $error_msg = $error;	
		
		break;

		case 'create_photoalbum':
	
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
			$id_event = core::database()->escape((int)Core_Array::getRequest('id_event'));
		
			$errors = array();
		
			if(empty($name)) $errors[] = core::getLanguage('error', 'empty_album_name');
			if(!empty($name) && Photoalbum::checkNameExists($name, $id_event, 'event')) $errors[] = core::getLanguage('error', 'album_name_exists');
		
			if(count($errors) == 0){
				$fields = array();
		
				$fields['id'] = 0;		
				$fields['name'] = $name;	
				$fields['created_at'] = date("Y-m-d H:i:s");	
				$fields['photoalbumable_type'] = 'event';
				$fields['id_owner'] = $id_event;
			
				$result = Photoalbum::createAlbum($fields);	

				if($result)	{
					header("Location: ./?task=events&id_event=" . $id_event . "&q=photoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}			
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;
	
		case 'edit_photoalbum':
	
			$id_event = core::database()->escape((int)Core_Array::getRequest('id_event'));
			$name = htmlspecialchars(trim($_POST['name']));
		
			$errors = array();
		
			if(empty($name)) $errors[] = core::getLanguage('error', 'empty_album_name');

			if(count($errors) == 0){
				$fields = array();
				$fields['name'] = $name;
				$fields['updated_at'] = date("Y-m-d H:i:s");			
			
				$result = Photoalbum::editAlbum($fields, Core_Array::getRequest('id_album'));
			
				if($result)	{
					header("Location: ./?task=events&id_event=" . $id_event . "&q=photoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}			
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;
	
		case 'remove_photoalbum':

			$id_event = core::database()->escape((int)Core_Array::getRequest('id_event'));
	
			if(Events::checkOwnerEvent($id_event, $user['id'], 'user')){
				$result = Photoalbum::removeAlbum(Core_Array::getRequest('id_album'));
			
				$errors = array();	
			
				if($result)	{
					header("Location: ./?task=events&id_event=" . $id_event . "&q=photoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}	
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;
	
		case 'create_videoalbum':
	
			$errors = array();
	
			$id_event = core::database()->escape((int)Core_Array::getRequest('id_event'));
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));

			if(empty($name)) $errors[] = core::getLanguage('error', 'empty_album_name');
			if(!empty($name) && Videoalbum::checkNameExists($name, $id_event, 'event')) $errors[] = core::getLanguage('error', 'album_name_exists');
	
			if(count($errors) == 0){
				$fields = array();
		
				$fields['id'] = 0;		
				$fields['name'] = $name;	
				$fields['created_at'] = date("Y-m-d H:i:s");	
				$fields['videoalbumable_type'] = 'event';
				$fields['id_owner'] = $id_event;	
		
				$result = Videoalbum::createAlbum($fields);	

				if($result)	{
					header("Location: ./?task=events&id_event=" . $id_event . "&q=videoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}
			}else $error_msg = core::getLanguage('error', 'web_apps_error');		
	
		break;
	
		case 'edit_videoalbum':
	
			$id_event = core::database()->escape((int)Core_Array::getRequest('id_event'));
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
		
			$errors = array();
		
			if(empty($name)) $errors[] = core::getLanguage('error', 'empty_album_name');

			if(count($errors) == 0){
				$fields = array();
				$fields['name'] = $name;
				$fields['updated_at'] = date("Y-m-d H:i:s");			
			
				$result = Videoalbum::editAlbum($fields, Core_Array::getRequest('id_album'), $id_event);
			
				if($result)	{
					header("Location: ./?task=events&id_event=" . $id_event . "&q=videoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}			
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;
	
		case 'remove_videoalbum':
	
			$id_event = core::database()->escape((int)Core_Array::getRequest('id_event'));
			$result = Videoalbum::removeAlbum(Core_Array::getRequest('id_album'), $id_event);	
	
			if($result)	{
				header("Location: ./?task=events&id_event=" . $id_event . "&q=videoalbums");
				exit;
			}
			else{
				$error_msg = core::getLanguage('error', 'web_apps_error');
			}
		
		break;
	
		case 'add_video':
		
			$video = htmlspecialchars(trim(Core_Array::getRequest('video')));
			$description = htmlspecialchars(trim(Core_Array::getRequest('description')));
			$id_event = core::database()->escape(Core_Array::getRequest('id_event'));
			$set_video = core::documentparser()->detect_video_id($video);
		
			$errors[] = array();
		
			if($set_video['video']){
				$fields = array();
				$fields['id'] = 0;
				$fields['id_videoalbum'] = Core_Array::getRequest('id_videoalbum');
				$fields['provider'] = $set_video['provider'];			
				$fields['video'] = $set_video['video'];			
				$fields['description'] = $description;			
				$fields['id_owner'] = $id_event;
				$fields['created_at'] = date("Y-m-d H:i:s");
			
				$result = Videoalbum::addVideo($fields);
			
				if($result)	{
					header("Location: ./?task=events&id_event=" . $id_event . "&q=videoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}			
			}
			else
				$error_msg = core::getLanguage('error', 'web_apps_error');
		
		break;
	}

	if($_GET['id_event']){	

		$tpl->assign('ID_EVENT', $_GET['id_event']);
		$tpl->assign('ID_OWNER', $_GET['id_event']);
		$tpl->assign('VIDEOALBUMABLE_TYPE', 'event');	
		$id_event = core::database()->escape((int)Core_Array::getRequest('id_event'));		
		
		if(Events::checkExistence($id_event) or !is_numeric($_GET['id_event'])){
			header("HTTP/1.1 404 Not Found");
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;
		}	
		
		$event = Events::getEventInfo($id_event);
	
		$tpl->assign('STYLE', 'opacity:0');	
		$tpl->assign('EVENT_NAME', $event['name']);		
		$tpl->assign('STR_FEED', core::getLanguage('str', 'feed'));
		$tpl->assign('STR_MEMBERS', core::getLanguage('str', 'members'));	
		$tpl->assign('STR_PHOTO', core::getLanguage('str', 'photo'));	
		$tpl->assign('STR_VIDEO', core::getLanguage('str', 'video'));
		
		if($event['banned'] == 1) 
			$tpl->assign('EVENT_COVER_PAGE', 'templates/images/noimage.png');
		else
			$tpl->assign('EVENT_COVER_PAGE', core::documentparser()->eventAvatar($event['cover_page']));		
		
		$tpl->assign('BUTTON_JOIN_TO_EVENT', core::getLanguage('button', 'join_to_community'));	
		$tpl->assign('BUTTON_LEAVE_EVENT', core::getLanguage('button', 'leave_community'));	
		$tpl->assign('BUTTON_ACCEPT INVITATION', core::getLanguage('button', 'accept invitation'));	
		$tpl->assign('EVENT_DESCRIPTION', nl2br($event['description']));	
		$tpl->assign('EVENT_ADDRESS', $event['address']);
		$tpl->assign('STR_ALL_PHOTOS', core::getLanguage('str', 'all_photos'));
		$tpl->assign('STR_ALL_VIDEOS', core::getLanguage('str', 'all_videos'));	
		$tpl->assign('CITY', $event['place']);
	
		$photoalbum_path = './?task=events&id_event=' . $id_event . '&q=photoalbums';
		$redirect_photo_album = '/?task=events&id_event=' . $id_event . '&q=photoalbums';
		$photoalbum_remove_path = './?task=events&id_event=' . $id_event . '&action=remove_photoalbum';
		$photoalbum_edit_path = './?task=events&id_event=' . $id_event . '&q=edit_photoalbum';
	
		$path_video = './?task=events&id_event=' . $id_event . '&q=videoalbums';	
		$path_remove_video = './?task=events&id_event=' . $id_event . '&q=videoalbums&action=remove_videoalbum';	
		$path_edit_video = './?task=events&id_event=' . $id_event . '&q=edit_videoalbum';

		if(Events::checkEventMember($id_event, $user['id'], 'user') == 1)		
			$tpl->assign('EVENT_MEMBER', 'owner');
		else if(Events::checkEventMember($id_event, $user['id'], 'user') == 2)
			$tpl->assign('EVENT_MEMBER', 'admin');
		else if(Events::checkEventMember($id_event, $user['id'], 'user') == 3)
			$tpl->assign('EVENT_MEMBER', 'member');
		else if(Events::checkEventMember($id_event, $user['id'], 'user') == 4)
			$tpl->assign('EVENT_MEMBER', 'invited');
		else if(Events::checkEventMember($id_event, $user['id'], 'user') == 5)
			$tpl->assign('EVENT_MEMBER', 'blocked');
		else if(Events::checkEventMember($id_event, $user['id'], 'user') == '0')
			$tpl->assign('EVENT_MEMBER', 'applied');
		else if(!Events::checkEventMember($id_event, $user['id'], 'user'))
			$tpl->assign('EVENT_MEMBER', 'none');
		
		if($user['id']) $tpl->assign('SHOW_COMMMENT_FORM', 'show');
		if(Events::checkOwnerEvent($id_event, $user['id'], 'user'))  $tpl->assign('ALLOW_EDIT', 'yes');
		if($event['banned'] == 1) $tpl->assign('BLOCK_PAGE', 'yes');			
	
		$tpl->assign('STR_EVENT_SUSPENDED', core::getLanguage('str', 'event_suspended'));	

		switch ($_GET['q']) {
		
			case 'edit':
		
				if(Events::checkExistence($id_event) or !Events::checkOwnerEvent($id_event, $user['id'], 'user')){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}
	
				$tpl->assign('QUERY', $_GET['q']);	
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'edit_event'));		
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);			
				$tpl->assign('STR_REQUIRED_FIELDS', core::getLanguage('str', 'required_fields'));			
				$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));		
				$tpl->assign('STR_SPORT_TYPE', core::getLanguage('str', 'sport_type'));		
				$tpl->assign('STR_DATE', core::getLanguage('str', 'date'));		
				$tpl->assign('STR_TIME', core::getLanguage('str', 'time'));		
				$tpl->assign('STR_DESCRIPTION', core::getLanguage('str', 'description'));			
				$tpl->assign('STR_ADDRESS', core::getLanguage('str', 'address'));			
				$tpl->assign('STR_LOCATION', core::getLanguage('str', 'location'));			
				$tpl->assign('STR_DATE_FORMAT', core::getLanguage('str', 'event_date_format'));	
	
				preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})\s+([0-9]{2}):([0-9]{2}):([0-9]{2})/i', $event['date_from'], $out1);
				preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})\s+([0-9]{2}):([0-9]{2}):([0-9]{2})/i', $event['date_to'], $out2);	

				if(!empty($_POST['event_date_to']) or !empty($event['date_to'])) $tpl->assign('SHOW_TIME_END', 'yes');	
	
				$tpl->assign('ACTION_EVENT', 'edit_event');	
				$tpl->assign('NAME', $_POST['name'] ? $_POST['name'] : $event['name']);	
				$tpl->assign('DESCRIPTION', $_POST['description'] ? $_POST['description'] : $event['description']);			
				$tpl->assign('ADDRESS', $_POST['address'] ? $_POST['address'] : $event['address']);			
				$tpl->assign('EVENT_DATE_FROM', $_POST['event_date_from'] ? $_POST['event_date_from'] : $out1[3] . '.' . $out1[2] . '.' . $out1[1]);
				$tpl->assign('EVENT_HOUR_FROM', $_POST['event_hour_from'] ? $_POST['event_hour_from'] : $out1[4]);
				$tpl->assign('EVENT_MINUTE_FROM', $_POST['event_minute_from'] ? $_POST['event_minute_from'] : $out1[5]);
				$tpl->assign('EVENT_DATE_TO', $_POST['event_date_to'] ? $_POST['event_date_to'] : $out2[3] . '.' . $out2[2] . '.' . $out2[1]);
				$tpl->assign('EVENT_HOUR_TO', $_POST['event_hour_to'] ? $_POST['event_hour_to'] : $out2[4]);
				$tpl->assign('EVENT_MINUTE_TO', $_POST['event_minute_to'] ? $_POST['event_minute_to'] : $out2[5]);			
				$tpl->assign('ID_PLACE', $_POST['id_place'] ? $_POST['id_place'] : Places::getTargetPlaceId($id_event, 'event'));	
				$tpl->assign('PLACE', $_POST['place'] ? $_POST['place'] : $event['place']);
				$tpl->assign('SPORT_TYPE', $_POST['sport_type'] ? $_POST['sport_type'] : $event['sport_type']);			
				$tpl->assign('BUTTON', core::getLanguage('button', 'edit'));
		
			break;
		
			case 'members':
		
				$tpl->assign('QUERY', $_GET['q']);
			
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'members'));			
				$tpl->assign('NUMBERMEMBER', Events::countMembers($id_event, 'user'));			
			
				$arr_users = Events::getEventsMemberList($id_event, 'user');
			
				if($arr_users){
					foreach($arr_users as $row){
						$rowBlock = $tpl->fetch('row_members');	
						core::user()->setUser_id($row['id_member']);
						$member = core::user()->getUserInfo();				
						$rowBlock->assign('ID_USER', $row['id_member']);			
						$rowBlock->assign('SEL', $user['id']);
						$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($member));					
						$rowBlock->assign('FIRSTNAME', $member['firstname']);					
						$rowBlock->assign('LASTNAME', $member['lastname']);
						$rowBlock->assign('CITY', $member['city']);	
						$rowBlock->assign('STATUS', Events::getEventRole(Events::getMemberShipStatus($id_event,  $row['id_member'], 'user')));
						$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['id_member']) ? 'online' : 'offline');
						$tpl->assign('row_members', $rowBlock);
					}
				}else $tpl->assign('NO_MEMBERS', core::getLanguage('str', 'empty'));
				
				$arr_teams = Events::getEventsMemberList($id_event, 'team');
			
				if($arr_teams){
					$tpl->assign('STR_TEAMS', core::getLanguage('str', 'teams'));
					$tpl->assign('NUMBERTEAMS', Events::countMembers($id_event, 'team'));	
				
					foreach($arr_teams as $row){
						$rowBlock = $tpl->fetch('row_teams');	
						$member = Communities::getCommunityInfo($row['id_member']);
						$rowBlock->assign('ID', $member['id']);		
						$rowBlock->assign('NAME', $member['name']);		
						$rowBlock->assign('ABOUT', $member['about']);					
						$rowBlock->assign('ID_USER', $user['id']);
						$rowBlock->assign('CITY', $member['place']);
						$rowBlock->assign('SPORT_TYPE', $member['sport_type']);	
						$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countAllMemberCommunity($member['id']), core::getLanguage('str', 'participants_friends')));
				
						if(Communities::getCommunityType($member['id']) == 1)
							$rowBlock->assign('TYPE', core::getLanguage('str', 'private_community')); 
						else if(Communities::getCommunityType($member['id']) == 2)
							$rowBlock->assign('TYPE', core::getLanguage('str', 'closed_community')); 
						else
							$rowBlock->assign('TYPE', core::getLanguage('str', 'open_community'));
		
						$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($member));					
						$tpl->assign('row_teams', $rowBlock);
					}
				}else $tpl->assign('NO_TEAMS', core::getLanguage('str', 'empty'));
			
				$arr_groups = Events::getEventsMemberList($id_event, 'group');
			
				if($arr_groups){
					$tpl->assign('STR_GROUPS', core::getLanguage('str', 'groups'));
					$tpl->assign('NUMBERGROUPS', Events::countMembers($id_event, 'group'));
				
					foreach($arr_groups as $row){
						$rowBlock = $tpl->fetch('row_groups');	
						$member = Communities::getCommunityInfo($row['id_member']);
						$rowBlock->assign('ID', $member['id']);		
						$rowBlock->assign('NAME', $member['name']);		
						$rowBlock->assign('ABOUT', $member['about']);					
						$rowBlock->assign('ID_USER', $user['id']);
						$rowBlock->assign('CITY', $member['place']);
						$rowBlock->assign('SPORT_TYPE', $member['sport_type']);	
						$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countAllMemberCommunity($member['id']), core::getLanguage('str', 'participants_friends')));
				
						if(Communities::getCommunityType($member['id']) == 1)
							$rowBlock->assign('TYPE', core::getLanguage('str', 'private_community')); 
						else if(Communities::getCommunityType($member['id']) == 2)
							$rowBlock->assign('TYPE', core::getLanguage('str', 'closed_community')); 
						else
							$rowBlock->assign('TYPE', core::getLanguage('str', 'open_community'));
		
						$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($member));					
						$tpl->assign('row_groups', $rowBlock);
					}
				}else $tpl->assign('NO_GROUPS', core::getLanguage('str', 'empty'));			
			
			break;
		
			case 'photoalbums':
		
				$tpl->assign('QUERY', $_GET['q']);	

				if($_GET['id_album']){
					$tpl->assign('ID_ALBUM', $_GET['id_album']);				
					$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'photoalbums'));

					$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
				
					if(Photoalbum::checkExistence($id_album) or !is_numeric($_GET['id_album'])){
						header("HTTP/1.1 404 Not Found");
						header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
						exit;
					}
	
					$tpl->assign('STR_EDIT', core::getLanguage('str', 'edit'));		
					$tpl->assign('STR_REMOVE', core::getLanguage('str', 'remove'));		
		
					$info = Photoalbum::getPhotoAlbumInfo($id_album);
		
					$tpl->assign('PHOTOALBUM_NAME', $info['name']);	
					$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);
				
					$arr_photos = Photoalbum::getPicList($id_album, 9, 0);

					if($arr_photos){
						foreach($arr_photos as $row){
							$rowBlock = $tpl->fetch('row_photos_list');
							$rowBlock->assign('ID_PHOTO', $row['id']);
							$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], $info['photoalbumable_type']));	
							$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], $info['photoalbumable_type']));
							$rowBlock->assign('DESCRIPTION', $row['description']);
							if($row['id_owner'] == $user['id']) $rowBlock->assign('ALLOW_EDIT', 'show');
							$tpl->assign('row_photos_list', $rowBlock);	
						}
					}			
					else $tpl->assign('NO_IMAGES', core::getLanguage('str', 'empty'));
				}
				else{		
			
					$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'photoalbums'));
		
					if(Events::checkOwnerEvent($id_event, $user['id'], 'user')){ 
						$tpl->assign('SHOW_ADD_PHOTOS_MENU', 'show');
					}
				
					$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);
					$tpl->assign('STR_ADD_PHOTOS', core::getLanguage('str', 'add_photos'));		
					$tpl->assign('STR_CREATE_PHOTOALBUM', core::getLanguage('str', 'create_photoalbum'));
					$tpl->assign('STR_OR', core::getLanguage('str', 'or'));
					$tpl->assign('STR_POPULAR_PHOTOS', core::getLanguage('str', 'popular_photos'));			
					$tpl->assign('STR_MY_ALBUMS', core::getLanguage('str', 'photoalbums'));	
					$tpl->assign('STR_MY_PHOTOS', core::getLanguage('title', 'photoalbums'));
					$tpl->assign('NUMBER_POPULAR_PHOTOS', Photoalbum::NumberTotalPopPhotos('event'));		
					$tpl->assign('NUMBER_MY_ALBUMS', Photoalbum::getNumberAlbums($id_event, 'event'));		
					$tpl->assign('NUMBER_MY_PHOTOS', Photoalbum::NumberPhotos($id_event, 'event'));	
					$tpl->assign('NO_POP_PHOTOS', core::getLanguage('str', 'empty'));
					
					$arr_albums = Photoalbum::getAlbumList($id_event, 'event');
		
					if($arr_albums){
						foreach($arr_albums as $row){
							$rowBlock = $tpl->fetch('row_my_album_list');	
							$rowBlock->assign('ID', $row['id']);	
							$rowBlock->assign('NAME', $row['name']);				
							$rowBlock->assign('CREATED_AT', core::documentparser()->mysql_russian_date($arr_album[$i]['created_at']));	
							$rowBlock->assign('UPDATED_AT', core::documentparser()->mysql_russian_date($arr_album[$i]['updated_at']));	
							$rowBlock->assign('STR_UPDATED', core::getLanguage('str', 'updated'));					
							$rowBlock->assign('STR_PICTURES', core::getLanguage('str', 'pictures'));			
							$rowBlock->assign('STR_CREATED', core::getLanguage('str', 'created'));	
							$rowBlock->assign('PHOTOALBUM_PATH', $photoalbum_path);				
							$rowBlock->assign('PHOTOALBUM_REMOVE_PATH', $photoalbum_remove_path);				
							$rowBlock->assign('PHOTOALBUM_EDIT_PATH', $photoalbum_edit_path);				
				
							if($id_user == $row['id_owner'] || Events::checkOwnerEvent($id_event, $user['id'], 'user')) $rowBlock->assign('SHOW_EDIT_LINKS', 'show');
					
							$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));					
							$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));					

							$pic = Photoalbum::getMainImage($row['id']);
				
							$image = ($pic['small_photo'] && file_exists(core::documentparser()->photogalleryPic($pic['small_photo'], 'event'))) ? core::documentparser()->photogalleryPic($pic['small_photo'], 'event') : 'templates/images/default_group.png';
							$rowBlock->assign('IMAGE', $image);
				
							$tpl->assign('row_my_album_list', $rowBlock);			
						}
					}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
	
					$arr_photos = Photoalbum::getPhotosList($id_event, 'event', 6, 0);
		
					if($arr_photos){
						foreach($arr_photos as $row){
							$rowBlock = $tpl->fetch('row_my_photos_list');
							$rowBlock->assign('ID', $row['id']);
							$rowBlock->assign('ID_PHOTO', $row['id_photo']);
							$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], 'event'));				
							$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], 'event'));
							
							if($row['id_owner'] == $user['id']) $rowBlock->assign('ALLOW_EDIT', 'show');
							
							$rowBlock->assign('DESCRIPTION', $row['description']);
							$tpl->assign('row_my_photos_list', $rowBlock);
						}			
					}
					else $tpl->assign('NO_PHOTOS', core::getLanguage('str', 'empty'));
				}
		
			break;	

			case 'edit_photoalbum':
		
				$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));			
				$photoalbums = Photoalbum::getPhotoAlbumInfo($id_album);
		
				$tpl->assign('QUERY', $_GET['q']);			
				$tpl->assign('STR_EDIT_PHOTOALBUMS', core::getLanguage('title', 'edit_photoalbums'));
				$tpl->assign('ID_ALBUM', $_GET['id_album']);			
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);			
				$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));			
				$tpl->assign('NAME', $_POST['name'] ? $_POST['name'] : $photoalbums['name']);			
				$tpl->assign('BUTTON', core::getLanguage('button', 'edit'));			
			
			break;
		
			case 'add_photo':
		
				$tpl->assign('QUERY', $_GET['q']);			
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'add_photos'));
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);			
				$tpl->assign('REDIRECT_PHOTO_ALBUM', $redirect_photo_album);

				if(Photoalbum::getNumberAlbums($id_event, 'event') == 0){
					$fields = array();
					$fields['id'] = 0;
					$fields['name'] = core::getLanguage('str', 'album');
					$fields['created_at'] = date("Y-m-d H:i:s");
					$fields['photoalbumable_type'] = 'event';
					$fields['id_owner'] = $id_event;		
			
					Photoalbum::createAlbum($fields);	
				}			
			
				$arr = Photoalbum::getAlbumsOptionList($id_event, 'event');
			
				if(is_array($arr)){			
				
					$tpl->assign('SHOW_CATEGORY_LIST', 'show');
				
					foreach($arr as $row){
						$rowBlock = $tpl->fetch('row_option_album');
						$rowBlock->assign('ID', $row['id']);	
						$rowBlock->assign('NAME', $row['name']);	
						$tpl->assign('row_option_album', $rowBlock);
					}
				}
		
			break;
		
			case 'create_photoalbum':
		
				$tpl->assign('QUERY', $_GET['q']);			
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'create_photoalbum'));			
				$tpl->assign('STR_CREATE_PHOTOALBUM', core::getLanguage('str', 'create_photoalbum'));			
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);			
				$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));			
				$tpl->assign('BUTTON', core::getLanguage('button', 'create'));			
				$tpl->assign('NAME', $_POST['name']);					
		
			break;
		
			case 'videoalbums':
		
				$tpl->assign('QUERY', $_GET['q']);	
				$tpl->assign('STR_MY_ALBUMS', core::getLanguage('str', 'videoalbums'));
			
				if($_GET['id_album']){
					$tpl->assign('ID_ALBUM', $_GET['id_album']);				
		
					$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));	

					if(Videoalbum::checkExistence($id_album) or !is_numeric($_GET['id_album'])){
						header("HTTP/1.1 404 Not Found");
						header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
						exit;
					}
		
				$album = Videoalbum::getAlbumInfo($id_album, $id_event);
				$tpl->assign('TITLE_PAGE', $album['name']);		
				$tpl->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				$tpl->assign('STR_REMOVE', core::getLanguage('str', 'remove'));
				$tpl->assign('PATH_VIDEO', $path_video);
				
				$arr_videosalbum = Videoalbum::getVideosAlbumList($id_album, 6, 0);
				
				if($arr_videosalbum){
					foreach($arr_videosalbum as $row){
						$rowBlock = $tpl->fetch('row_videos_list');
						$rowBlock->assign('ID', $row['id']);					
						$rowBlock->assign('DESCRIPTION', $row['description']);
						$rowBlock->assign('VIDEO', core::documentparser()->getVideoPlayer($row['provider'], $row['video']));
						$rowBlock->assign('THUMB', core::documentparser()->getThumb($row['provider'], $row['video']));			
						$rowBlock->assign('NUMBERVIEWS', Videoalbum::getNumberVideoViews($row['id']));	
						$tpl->assign('row_videos_list', $rowBlock);
					}
				}
				else $tpl->assign('NO_VIDEOS', core::getLanguage('str', 'empty'));	
			}
			else{
				
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'videoalbums'));
			
				if(Events::checkOwnerEvent($id_event, $user['id'], 'user')) $tpl->assign('SHOW_ADD_VIDEO_MENU', 'show');	
		
				$tpl->assign('PATH_VIDEO', $path_video);
				$tpl->assign('PATH_REMOVE_VIDEO', $path_remove_video);		
				$tpl->assign('STR_ADD_NEW_VIDEO', core::getLanguage('str', 'add_new_video'));
				$tpl->assign('STR_CREATE_NEW_ALBUM', core::getLanguage('str', 'create_new_album'));
				$tpl->assign('STR_OR', core::getLanguage('str', 'or'));		
				$tpl->assign('STR_MY_ALBUMS', core::getLanguage('str', 'albums'));
				$tpl->assign('STR_POPULAR_VIDEOS',  core::getLanguage('str', 'popular_videos'));
				$tpl->assign('STR_MY_VIDEOS', core::getLanguage('str', 'videos'));		
				$tpl->assign('NUMBER_ALBUMS', Videoalbum::NumberAlbums($id_event, 'event'));
				$tpl->assign('NUMBER_POPULAR_VIDEOS', Videoalbum::getNumberPopVideos('event'));		
				$tpl->assign('NUMBER_MY_VIDEOS', Videoalbum::NumberVideos($id_event, 'event'));			
				$tpl->assign('NO_POP_VIDEOS', core::getLanguage('str', 'empty'));					
		
				$arr_albums = Videoalbum::getAlbumList($id_event, 'event');
		
				if($arr_albums){
					foreach($arr_albums as $row){
						$rowBlock = $tpl->fetch('row_my_videoalbum_list');	
						$rowBlock->assign('ID', $row['id']);	
						$rowBlock->assign('NAME', $row['name']);	
						$rowBlock->assign('THUMB', Videoalbum::getThumb($row['id']));	
						$rowBlock->assign('CREATED_AT', core::documentparser()->mysql_russian_date($row['created_at']));	
						$rowBlock->assign('UPDATED_AT', core::documentparser()->mysql_russian_date($row['updated_at']));	
						$rowBlock->assign('STR_UPDATED', core::getLanguage('str', 'updated'));					
						$rowBlock->assign('STR_CREATED', core::getLanguage('str', 'created'));
						$rowBlock->assign('PATH_VIDEO', $path_video);
						$rowBlock->assign('PATH_REMOVE_VIDEO', $path_remove_video);						
						$rowBlock->assign('PATH_EDIT_VIDEO', $path_edit_video);						
						$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));					
						$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));
						$tpl->assign('row_my_videoalbum_list', $rowBlock);			
					}
				}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
		
				$arr_my_videos = Videoalbum::getVideosList($id_event, 'event', 6, 0);
		
				if($arr_my_videos){
					foreach($arr_my_videos as $row){
						$rowBlock = $tpl->fetch('row_my_videos_list');					
						$rowBlock->assign('ID', $row['id']);
						$rowBlock->assign('ID_VIDEO', $row['id_video']);					
						$rowBlock->assign('DESCRIPTION', $arow['description']);
						$rowBlock->assign('THUMB', core::documentparser()->getThumb($row['provider'], $row['video']));
						$rowBlock->assign('VIDEO', core::documentparser()->getVideoPlayer($row['provider'], $row['video']));					
						$rowBlock->assign('NUMBERVIEWS', Videoalbum::getNumberVideoViews($row['id_video']));						
						$tpl->assign('row_my_videos_list', $rowBlock);
					}	
				}	
				else $tpl->assign('NO_MY_VIDEOS', core::getLanguage('str', 'empty'));
			}			

		break;
		
		case 'add_video':
		
			$tpl->assign('QUERY', $_GET['q']);				
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'add_new_video'));
	
			if(!empty($error_msg)) {
				$tpl->assign('STR_ERROR', core::getLanguage('str', 'error'));
				$tpl->assign('ERROR_ALERT', $error_msg);
			}	
	
			if(Videoalbum::NumberAlbums($id_event, 'event') == 0){
				$fields = array();
				$fields['id'] = 0;		
				$fields['name'] = core::getLanguage('str', 'videoalbum');		
				$fields['created_at'] = date("Y-m-d H:i:s");
				$fields['videoalbumable_type'] = 'event';		
				$fields['id_owner'] = $id_event;	
		
				Videoalbum::createAlbum($fields);		
			}		

			foreach(Videoalbum::getVideoAlbumOption($id_event, 'event') as $row){
				$rowBlock = $tpl->fetch('row_option_videoalbum');
				$rowBlock->assign('ID', $row['id']);
				$rowBlock->assign('NAME', $row['name']);				
				$tpl->assign('row_option_videoalbum', $rowBlock);
			}
	
			$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);	
			$tpl->assign('STR_DESCRIPTION', core::getLanguage('str', 'description'));		
			$tpl->assign('STR_ALBUM', core::getLanguage('str', 'album'));	
			$tpl->assign('OPTION_ID', $_POST['id_videoalbum']);	
			$tpl->assign('VIDEO', $_POST['video']);	
			$tpl->assign('DESCRIPTION', $_POST['description']);
			$tpl->assign('BUTTON_ADD', core::getLanguage('button', 'add'));	
		
		break;
		
		case 'create_videoalbum':
		
			$tpl->assign('QUERY', $_GET['q']);
			
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'create_videoalbum'));
			$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
			$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));	
			$tpl->assign('STR_CREATE_VIDEOALBUM', core::getLanguage('str', 'create_videoalbum'));
			$tpl->assign('NAME', $_POST['name']);
			$tpl->assign('BUTTON', core::getLanguage('button', 'create'));		
		
		break;		
		
		case 'edit_videoalbum':
		
			$tpl->assign('QUERY', $_GET['q']);			
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'edit_videoalbum'));			
			$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);			
			$tpl->assign('STR_EDIT_VIDEOALBUM', core::getLanguage('str', 'edit_videoalbum'));
			$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));				
			$videoalbum = Videoalbum::getVideoAlbumInfo((int)Core_Array::getRequest('id_album'));			
			$tpl->assign('NAME', $_POST['name'] ? $_POST['name'] : $videoalbum['name']);			
			$tpl->assign('BUTTON', core::getLanguage('button', 'edit'));
		
		break;
		
		default:			
	
			$tpl->assign('TITLE_PAGE', $event['name']);				
			$tpl->assign('STR_WHATS_INTERESTING', core::getLanguage('str', 'whats_interesting'));
			$tpl->assign('STR_REPLY', core::getLanguage('str', 'reply'));

			if(Events::checkOwnerEvent($id_event, $user['id'], 'user')) $tpl->assign('ADMIN', 'yes');			
		
			$arr = Comments::treeComments(0, Comments::getCommentList($id_event, 'event', 10, 0));

			foreach($arr as $row){
				$rowBlock = $tpl->fetch('row_comments');	

				if (Events::checkOwnerEvent($id_event, $row['id_user'], 'user')){
					$avatar = core::documentparser()->eventAvatar($event['cover_page']);
					$name = $event['name'];	
					$rowBlock->assign('NAME', $name);
				}
				else{
					$avatar = core::documentparser()->userAvatar($row);
				}
					
				$rowBlock->assign('ID', $row['id_comment']);
				$rowBlock->assign('ID_PARENT', $row['id_parent']);	
				$rowBlock->assign('ID_USER', $row['id_user']);			
				$rowBlock->assign('ID_USER_SESSION', $user['id']);
				$rowBlock->assign('ID_CONTENT', $row['id_content']);		
				$rowBlock->assign('AVATAR', $avatar);
				$rowBlock->assign('FIRSTNAME', $row['firstname']);		
				$rowBlock->assign('LASTNAME', $row['lastname']);		
				$rowBlock->assign('CREATED', $row['created']);
				$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['id_user']) ? 'online' : 'offline');				
				$rowBlock->assign('CONTENT', core::documentparser()->link_replace($row['content']));
				$rowBlock->assign('NUMBERTELL', Comments::getNumberTell($row['id_comment'], 'comment'));
				$rowBlock->assign('NUMBERLIKED', Comments::getNumberLiked($row['id_comment'], 'comment'));
				
				if($user['id'] == $row['id_user'])
					$rowBlock->assign('BUTTON_SHARE', 'hide');
				else
					$rowBlock->assign('BUTTON_SHARE', 'show');					
				
				if($row['id_parent'] == 0){
					foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
						$rowAttach = $rowBlock->fetch('row_attach');
						$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
						$rowAttach->assign('SMALL_PHOTO', PATH_COMMENT_ATTACHMENTS . $photo['small_photo']);
						$rowAttach->assign('ID_PHOTO', $photo['id_photo']);
						$rowBlock->assign('row_attach', $rowAttach);
					}
				}
				else{
					foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
						$rowAttach = $rowBlock->fetch('row_reply_attach');
						$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
						$rowAttach->assign('SMALL_PHOTO', PATH_COMMENT_ATTACHMENTS . $photo['small_photo']);
						$rowAttach->assign('ID_PHOTO', $photo['id_photo']);
						$rowBlock->assign('row_reply_attach', $rowAttach);
					}
				}	
	
				$rowBlock->assign('STR_REPLY', core::getLanguage('str', 'reply'));
				$tpl->assign('row_comments', $rowBlock);
			}						
		}
	}
	else{
	
		if($_GET['q'] == 'create'){
			$tpl->assign('QUERY', $_GET['q']);			
			$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);			
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'create_event'));		
			$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));		
			$tpl->assign('STR_SPORT_TYPE', core::getLanguage('str', 'sport_type'));		
			$tpl->assign('STR_DATE', core::getLanguage('str', 'date'));		
			$tpl->assign('STR_TIME', core::getLanguage('str', 'time'));		
			$tpl->assign('STR_DESCRIPTION', core::getLanguage('str', 'description'));		
			$tpl->assign('STR_ADDRESS', core::getLanguage('str', 'address'));		
			$tpl->assign('STR_LOCATION', core::getLanguage('str', 'location'));				
			$tpl->assign('BUTTON_ADD_PICTURE', core::getLanguage('button', 'add_picture'));
			$tpl->assign('BUTTON', core::getLanguage('button', 'create'));
			$tpl->assign('STR_DATE_FORMAT', core::getLanguage('str', 'event_date_format'));				
		
			if(!empty($_POST['event_date_to'])) $tpl->assign('SHOW_TIME_END', 'yes'); 		
		
			$tpl->assign('ACTION_EVENT', 'create_event');
			$tpl->assign('NAME', $_POST['name']);		
			$tpl->assign('SPORT', $_POST['sport']);		
			$tpl->assign('PLACE', $_POST['place']);		
			$tpl->assign('EVENT_DATE_TO', $_POST['event_date_to']);		
			$tpl->assign('EVENT_HOUR_TO', $_POST['event_hour_to']);		
			$tpl->assign('EVENT_TIME_TO', $_POST['event_time_to']);	
			$tpl->assign('EVENT_DATE_FROM', $_POST['event_date_from']);		
			$tpl->assign('EVENT_HOUR_FROM', $_POST['event_hour_from']);		
			$tpl->assign('EVENT_TIME_FROM', $_POST['event_time_from']);			
			$tpl->assign('DESCRIPTION', $_POST['description']);	
			$tpl->assign('ADDRESS', $_POST['address']);
			$tpl->assign('ID_PLACE', $_POST['id_place']);	
			$tpl->assign('EVENT_COVER_PAGE', core::documentparser()->eventAvatar(NULL));		
		}
		else{		
			$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
			$tpl->assign('BUTTON_SEARCH_FOR_EVENT', core::getLanguage('button', 'search_for_event'));
			$tpl->assign('BUTTON_CREATE_EVENT', core::getLanguage('button', 'create_event'));
			$tpl->assign('STR_POPULAR_EVENT', core::getLanguage('str', 'popular_events'));						
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'events'));				
			$tpl->assign('NUMBERPOPULAREVENTS', Events::getNumberPopularEvents());		
			$tpl->assign('STR_KEYWORD', core::getLanguage('str', 'keyword'));		
			$tpl->assign('STR_LOOKING_FOR_EVENT_IN_CITY', core::getLanguage('str', 'looking_for_event_in_city'));		
			$tpl->assign('STR_LOOKING_FOR_SPORT_TYPE', core::getLanguage('str', 'looking_for_sport_type'));	
			$tpl->assign('STR_THERE_ARENT_POP_EVENTS', core::getLanguage('str', 'there_arent_pop_events'));
			$tpl->assign('STR_YOU_DONT_TAKE_PART_IN_EVENTS', core::getLanguage('str', 'you_dont_take_part_in_events'));			
			$tpl->assign('STR_YOU_DONT_HAVE_INVITATIONS', core::getLanguage('str', 'you_dont_have_invitations'));		
			
			$arr_pop_events = Events::getPopularEventList(5, 0);

			if($arr_pop_events){
				foreach($arr_pop_events as $row){
					$rowBlock = $tpl->fetch('pop_event_row');	
					$rowBlock->assign('ID', $row['id']);	
					$rowBlock->assign('NAME', $row['name']);
					$rowBlock->assign('AVATAR', core::documentparser()->eventAvatar($row['cover_page']));		
					$rowBlock->assign('SPORT_TYPE', $row['sport_type']);
					$rowBlock->assign('CITY', $row['place']);
					$rowBlock->assign('ROLE', Events::getEventRole(Events::getMemberShipStatus($row['id'], $user['id'], 'user')));				
					$rowBlock->assign('DESCRIPTION', $row['description']);
					$rowBlock->assign('PARTICIPANTS_FRIENDS', str_replace('%MEMBERS%', Events::countMembers($row['id'], 'user'), core::getLanguage('str', 'participants_friends')));

					$date_interval_event_beginning = str_replace('%DATE_FROM%', core::documentparser()->mysql_russian_datetime($row['date_from']), core::getLanguage('str', 'date_interval_event_beginning'));
					$date_interval_event_beginning = str_replace('%TIME_FROM%', $row['time_from'], $date_interval_event_beginning);
					$rowBlock->assign('DATE_INTERVAL_EVENT_BEGINNING', $date_interval_event_beginning);
				
					if(!empty($row['date_to'])){
						$date_interval_event_end = str_replace('%DATE_TO%', core::documentparser()->mysql_russian_datetime($row['date_to']), core::getLanguage('str', 'date_interval_event_end'));
						$date_interval_event_end = str_replace('%TIME_TO%', $row['time_to'], $date_interval_event_end);					
						$rowBlock->assign('DATE_INTERVAL_EVENT_END', $date_interval_event_end);
					}
				
					if(Events::getEventStatus($row['id']) == 'continues') 
						$rowBlock->assign('STATUS', core::getLanguage('str', 'event_continues'));
					else if(Events::getEventStatus($row['id']) == 'end')	
						$rowBlock->assign('STATUS', core::getLanguage('str', 'event_completed'));

					if(Events::checkOwnerEvent($row['id_event'], $user['id'], 'user')) $rowBlock->assign('ALLOW_EDIT', 'yes');
				
					$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				
					$tpl->assign('pop_event_row', $rowBlock);			
				}	
			}
			else $tpl->assign('NO_POP_EVENTS', core::getLanguage('str', 'empty'));

			$tpl->assign('STR_MY_EVENTS', core::getLanguage('str', 'my_events'));
			$tpl->assign('NUMBERMYEVENTS', Events::getNumberMyEvents($user['id'], 'user'));
			
			$arr_my_events = Events::getMyEvents($user['id'], 'user', 5, 0);			

			if($arr_my_events){
				foreach($arr_my_events as $row){
					$rowBlock = $tpl->fetch('my_event_row');
					$rowBlock->assign('ID', $row['id_event']);	
					$rowBlock->assign('NAME', $row['name']);
					$rowBlock->assign('AVATAR', core::documentparser()->eventAvatar($row['cover_page']));		
					$rowBlock->assign('SPORT_TYPE', $row['sport_type']);
					$rowBlock->assign('CITY', $row['place']);
					$rowBlock->assign('ROLE', Events::getEventRole(Events::getMemberShipStatus($row['id_event'], $user['id'], 'user')));
					$rowBlock->assign('DESCRIPTION', $row['description']);
					$rowBlock->assign('PARTICIPANTS_FRIENDS', str_replace('%MEMBERS%', Events::countMembers($row['id_event'], 'user'), core::getLanguage('str', 'participants_friends')));
					$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));				
			
					$date_interval_event_beginning = str_replace('%DATE_FROM%', core::documentparser()->mysql_russian_datetime($row['date_from']), core::getLanguage('str', 'date_interval_event_beginning'));
					$date_interval_event_beginning = str_replace('%TIME_FROM%', $row['time_from'], $date_interval_event_beginning);
					$rowBlock->assign('DATE_INTERVAL_EVENT_BEGINNING', $date_interval_event_beginning);
				
					if(!empty($row['date_to'])){
						$date_interval_event_end = str_replace('%DATE_TO%', core::documentparser()->mysql_russian_datetime($row['date_to']), core::getLanguage('str', 'date_interval_event_end'));
						$date_interval_event_end = str_replace('%TIME_TO%', $row['time_to'], $date_interval_event_end);
					
						$rowBlock->assign('DATE_INTERVAL_EVENT_END', $date_interval_event_end);
					}
				
					if(Events::getEventStatus($row['id_event']) == 'continues') 
						$rowBlock->assign('STATUS', core::getLanguage('str', 'event_continues'));
					else if(Events::getEventStatus($row['id_event']) == 'end')	
						$rowBlock->assign('STATUS', core::getLanguage('str', 'event_completed'));				
			
					if(Events::checkOwnerEvent($row['id_event'], $user['id'], 'user'))  $rowBlock->assign('ALLOW_EDIT', 'yes');
			
					$tpl->assign('my_event_row', $rowBlock);
				}	
			}
			else $tpl->assign('NO_MY_EVENTS', core::getLanguage('str', 'empty'));
		
			$tpl->assign('STR_AM_INVITED', core::getLanguage('str', 'am_invited'));
		
			$arr_invited_me_events = Events::getInvitedMeEvents($user['id'], 'user', 5, 0);	
		
			if($arr_invited_me_events){
				foreach($arr_invited_me_events as $row){
					$rowBlock = $tpl->fetch('invited_me_events_row');
					$rowBlock->assign('ID', $row['id_event']);	
					$rowBlock->assign('NAME', $row['name']);
					$rowBlock->assign('AVATAR', core::documentparser()->eventAvatar($row['cover_page']));		
					$rowBlock->assign('SPORT_TYPE', $row['sport_type']);
					$rowBlock->assign('CITY', $row['place']);
					$rowBlock->assign('DESCRIPTION', $row['description']);
					$rowBlock->assign('PARTICIPANTS_FRIENDS', str_replace('%MEMBERS%', Events::countMembers($row['id_event'], 'user'), core::getLanguage('str', 'participants_friends')));
			
					$date_interval_event_beginning = str_replace('%DATE_FROM%', core::documentparser()->mysql_russian_datetime($row['date_from']), core::getLanguage('str', 'date_interval_event_beginning'));
					$date_interval_event_beginning = str_replace('%TIME_FROM%', $row['time_from'], $date_interval_event_beginning);
					$rowBlock->assign('DATE_INTERVAL_EVENT_BEGINNING', $date_interval_event_beginning);
				
					if(!empty($row['date_to'])){
						$date_interval_event_end = str_replace('%DATE_TO%', core::documentparser()->mysql_russian_datetime($row['date_to']), core::getLanguage('str', 'date_interval_event_end'));
						$date_interval_event_end = str_replace('%TIME_TO%', $row['time_to'], $date_interval_event_end);
					
						$rowBlock->assign('DATE_INTERVAL_EVENT_END', $date_interval_event_end);
					}
			
					$tpl->assign('invited_me_events_row', $rowBlock);
				}	
			}
			else $tpl->assign('NO_INVITED_ME', core::getLanguage('str', 'empty'));		
		}
	}

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

	include_once "footer.inc";
		
	$tpl->display();
}
else{
	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");
	
	if(empty($_GET['id_event'])){
		header("Location: http://" . $_SERVER['SERVER_NAME']);
		exit;
	}
	
	$tpl->assign('OPEN_PAGE', 'yes');	
	
	$css = array();
	$css[] = './templates/css/bootstrap-theme.min.css';
	$css[] = './templates/css/bootstrap.min.css';
	$css[] = './templates/css/style.css';
	$css[] = './templates/css/select2-bootstrap.css';
	$css[] = './templates/css/select2.css';

	foreach($css as $row){
		$rowBlock = $tpl->fetch('row_css_list2');
		$rowBlock->assign('CSS', core::documentparser()->showCSS($row));
		$tpl->assign('row_css_list2', $rowBlock);
	}

	$js = array();
	$js[] = './templates/js/jquery-3.7.1.min.js';

	foreach($js as $row){
		$rowBlock = $tpl->fetch('row_js_list2');
		$rowBlock->assign('JS', core::documentparser()->showJS($row));
		$tpl->assign('row_js_list2', $rowBlock);
	}
	
	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";
	
	$tpl->assign('ID_EVENT', $_GET['id_event']);
	$tpl->assign('ID_OWNER', $_GET['id_event']);
	$tpl->assign('VIDEOALBUMABLE_TYPE', 'event');	
	$id_event = core::database()->escape((int)Core_Array::getRequest('id_event'));
	$event = Events::getEventInfo($id_event);
	
	$tpl->assign('STYLE', 'opacity:0');	
	$tpl->assign('EVENT_NAME', $event['name']);		
	$tpl->assign('STR_FEED', core::getLanguage('str', 'feed'));
	$tpl->assign('STR_MEMBERS', core::getLanguage('str', 'members'));	
	$tpl->assign('STR_PHOTO', core::getLanguage('str', 'photo'));	
	$tpl->assign('STR_VIDEO', core::getLanguage('str', 'video'));
	
	if($event['banned'] == 1) 
		$tpl->assign('EVENT_COVER_PAGE', 'templates/images/noimage.png');
	else 
		$tpl->assign('EVENT_COVER_PAGE', core::documentparser()->eventAvatar($event['cover_page']));	
	
	$tpl->assign('BUTTON_JOIN_TO_EVENT', core::getLanguage('button', 'join_to_community'));	
	$tpl->assign('BUTTON_LEAVE_EVENT', core::getLanguage('button', 'leave_community'));	
	$tpl->assign('BUTTON_ACCEPT INVITATION', core::getLanguage('button', 'accept invitation'));	
	$tpl->assign('EVENT_DESCRIPTION', nl2br($event['description']));	
	$tpl->assign('DESCRIPTION', $event['description']);
	$tpl->assign('EVENT_ADDRESS', $event['address']);
	$tpl->assign('STR_ALL_PHOTOS', core::getLanguage('str', 'all_photos'));
	$tpl->assign('STR_ALL_VIDEOS', core::getLanguage('str', 'all_videos'));	
	$tpl->assign('CITY', $event['place']);				
		
	if($event['banned'] == 1) $tpl->assign('BLOCK_PAGE', 'yes');
	
	$tpl->assign('STR_EVENT_SUSPENDED', core::getLanguage('str', 'event_suspended'));
	
	switch ($_GET['q']) {
		
		case 'members':
		
			$tpl->assign('QUERY', $_GET['q']);			
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'members'));			
			$tpl->assign('NUMBERMEMBER', Events::countMembers($id_event, 'user'));			
			
			$arr_users = Events::getEventsMemberList($id_event, 'user');
			
			if($arr_users){
				foreach($arr_users as $row){
					$rowBlock = $tpl->fetch('row_members');	
					core::user()->setUser_id($row['id_member']);
					$member = core::user()->getUserInfo();				
					$rowBlock->assign('ID_USER', $row['id_member']);			
					$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($member));					
					$rowBlock->assign('FIRSTNAME', $member['firstname']);					
					$rowBlock->assign('LASTNAME', $member['lastname']);
					$rowBlock->assign('CITY', $member['city']);	
					$rowBlock->assign('STATUS', Events::getEventRole(Events::getMemberShipStatus($id_event,  $row['id_member'], 'user')));
					$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['id_member']) ? 'online' : 'offline');
					$tpl->assign('row_members', $rowBlock);
				}
			}else $tpl->assign('NO_MEMBERS', core::getLanguage('str', 'empty'));
				
			$arr_teams = Events::getEventsMemberList($id_event, 'team');
			
			if($arr_teams){
				$tpl->assign('STR_TEAMS', core::getLanguage('str', 'teams'));
				$tpl->assign('NUMBERTEAMS', Events::countMembers($id_event, 'team'));	
				
				foreach($arr_teams as $row){
					$rowBlock = $tpl->fetch('row_teams');	
					$member = Communities::getCommunityInfo($row['id_member']);
					$rowBlock->assign('ID', $member['id']);		
					$rowBlock->assign('NAME', $member['name']);		
					$rowBlock->assign('ABOUT', $member['about']);					
					$rowBlock->assign('CITY', $member['place']);
					$rowBlock->assign('SPORT_TYPE', $member['sport_type']);	
					$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countAllMemberCommunity($member['id']), core::getLanguage('str', 'participants_friends')));
				
					if(Communities::getCommunityType($member['id']) == 1)
						$rowBlock->assign('TYPE', core::getLanguage('str', 'private_community')); 
					else if(Communities::getCommunityType($member['id']) == 2)
						$rowBlock->assign('TYPE', core::getLanguage('str', 'closed_community')); 
					else
						$rowBlock->assign('TYPE', core::getLanguage('str', 'open_community'));
		
					$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($member));					
					$tpl->assign('row_teams', $rowBlock);
				}
			}else $tpl->assign('NO_TEAMS', core::getLanguage('str', 'empty'));
			
			$arr_groups = Events::getEventsMemberList($id_event, 'group');
			
			if($arr_groups){
				$tpl->assign('STR_GROUPS', core::getLanguage('str', 'groups'));
				$tpl->assign('NUMBERGROUPS', Events::countMembers($id_event, 'group'));
				
				foreach($arr_groups as $row){
					$rowBlock = $tpl->fetch('row_groups');	
					$member = Communities::getCommunityInfo($row['id_member']);
					$rowBlock->assign('ID', $member['id']);		
					$rowBlock->assign('NAME', $member['name']);		
					$rowBlock->assign('ABOUT', $member['about']);					
					$rowBlock->assign('CITY', $member['place']);
					$rowBlock->assign('SPORT_TYPE', $member['sport_type']);	
					$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countAllMemberCommunity($member['id']), core::getLanguage('str', 'participants_friends')));
				
					if(Communities::getCommunityType($member['id']) == 1)
						$rowBlock->assign('TYPE', core::getLanguage('str', 'private_community')); 
					else if(Communities::getCommunityType($member['id']) == 2)
						$rowBlock->assign('TYPE', core::getLanguage('str', 'closed_community')); 
					else
						$rowBlock->assign('TYPE', core::getLanguage('str', 'open_community'));
		
					$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($member));					
					$tpl->assign('row_groups', $rowBlock);
				}
			}else $tpl->assign('NO_GROUPS', core::getLanguage('str', 'empty'));			
			
		break;
		
		case 'photoalbums':
		
			$tpl->assign('QUERY', $_GET['q']);	

			if($_GET['id_album']){
				$tpl->assign('ID_ALBUM', $_GET['id_album']);				
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'photoalbums'));

				$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
				
				if(Photoalbum::checkExistence($id_album) or !is_numeric($_GET['id_album'])){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}	
				
				$tpl->assign('STR_EDIT', core::getLanguage('str', 'edit'));		
				$tpl->assign('STR_REMOVE', core::getLanguage('str', 'remove'));		
		
				$info = Photoalbum::getPhotoAlbumInfo($id_album);
		
				$tpl->assign('PHOTOALBUM_NAME', $info['name']);	
				$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);
				
				$arr_photos = Photoalbum::getPicList($id_album, 9, 0);

				if($arr_photos){
					foreach($arr_photos as $row){
						$rowBlock = $tpl->fetch('row_photos_list');
						$rowBlock->assign('ID_PHOTO', $row['id']);
						$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], $info['photoalbumable_type']));	
						$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], $info['photoalbumable_type']));
						$rowBlock->assign('DESCRIPTION', $row['description']);
						$tpl->assign('row_photos_list', $rowBlock);	
					}
				}			
				else $tpl->assign('NO_IMAGES', core::getLanguage('str', 'empty'));
			}
			else{		
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'photoalbums'));
				
				$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);
				$tpl->assign('STR_ADD_PHOTOS', core::getLanguage('str', 'add_photos'));		
				$tpl->assign('STR_CREATE_PHOTOALBUM', core::getLanguage('str', 'create_photoalbum'));
				$tpl->assign('STR_OR', core::getLanguage('str', 'or'));
				$tpl->assign('STR_POPULAR_PHOTOS', core::getLanguage('str', 'popular_photos'));			
				$tpl->assign('STR_MY_ALBUMS', core::getLanguage('str', 'photoalbums'));	
				$tpl->assign('STR_MY_PHOTOS', core::getLanguage('title', 'photoalbums'));
				$tpl->assign('NUMBER_POPULAR_PHOTOS', Photoalbum::NumberTotalPopPhotos('event'));		
				$tpl->assign('NUMBER_MY_ALBUMS', Photoalbum::getNumberAlbums($id_event, 'event'));		
				$tpl->assign('NUMBER_MY_PHOTOS', Photoalbum::NumberPhotos($id_event, 'event'));	
				$tpl->assign('NO_POP_PHOTOS', core::getLanguage('str', 'empty'));
					
				$arr_albums = Photoalbum::getAlbumList($id_event, 'event');
		
				if($arr_albums){
					foreach($arr_albums as $row){
						$rowBlock = $tpl->fetch('row_my_album_list');	
						$rowBlock->assign('ID', $row['id']);	
						$rowBlock->assign('NAME', $row['name']);				
						$rowBlock->assign('CREATED_AT', core::documentparser()->mysql_russian_date($arr_album[$i]['created_at']));	
						$rowBlock->assign('UPDATED_AT', core::documentparser()->mysql_russian_date($arr_album[$i]['updated_at']));	
						$rowBlock->assign('STR_UPDATED', core::getLanguage('str', 'updated'));					
						$rowBlock->assign('STR_PICTURES', core::getLanguage('str', 'pictures'));			
						$rowBlock->assign('STR_CREATED', core::getLanguage('str', 'created'));	
						$rowBlock->assign('PHOTOALBUM_PATH', $photoalbum_path);				
						$rowBlock->assign('PHOTOALBUM_REMOVE_PATH', $photoalbum_remove_path);				
						$rowBlock->assign('PHOTOALBUM_EDIT_PATH', $photoalbum_edit_path);

						$pic = Photoalbum::getMainImage($row['id']);
				
						$image = ($pic['small_photo'] && file_exists(core::documentparser()->photogalleryPic($pic['small_photo'], 'event'))) ? core::documentparser()->photogalleryPic($pic['small_photo'], 'event') : 'templates/images/default_group.png';
						$rowBlock->assign('IMAGE', $image);
				
						$tpl->assign('row_my_album_list', $rowBlock);			
					}
				}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
	
				$arr_photos = Photoalbum::getPhotosList($id_event, 'event', 6, 0);
		
				if($arr_photos){
					foreach($arr_photos as $row){
						$rowBlock = $tpl->fetch('row_my_photos_list');
						$rowBlock->assign('ID', $row['id']);
						$rowBlock->assign('ID_PHOTO', $row['id_photo']);
						$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], 'event'));				
						$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], 'event'));							
						$rowBlock->assign('DESCRIPTION', $row['description']);
						$tpl->assign('row_my_photos_list', $rowBlock);
					}			
				}
				else $tpl->assign('NO_PHOTOS', core::getLanguage('str', 'empty'));
			}
		
		break;	
			
		case 'videoalbums':
		
			$tpl->assign('QUERY', $_GET['q']);	
			$tpl->assign('STR_MY_ALBUMS', core::getLanguage('str', 'videoalbums'));
			
			if($_GET['id_album']){
				$tpl->assign('ID_ALBUM', $_GET['id_album']);				
		
				$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));	

				if(Videoalbum::checkExistence($id_album) or !is_numeric($_GET['id_album'])){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}
		
				$album = Videoalbum::getAlbumInfo($id_album, $id_event);
				$tpl->assign('TITLE_PAGE', $album['name']);		
				$tpl->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				$tpl->assign('STR_REMOVE', core::getLanguage('str', 'remove'));
				$tpl->assign('PATH_VIDEO', $path_video);
				
				$arr_videosalbum = Videoalbum::getVideosAlbumList($id_album, 6, 0);
				
				if($arr_videosalbum){
					foreach($arr_videosalbum as $row){
						$rowBlock = $tpl->fetch('row_videos_list');
						$rowBlock->assign('ID', $row['id']);					
						$rowBlock->assign('DESCRIPTION', $row['description']);
						$rowBlock->assign('VIDEO', core::documentparser()->getVideoPlayer($row['provider'], $row['video']));
						$rowBlock->assign('THUMB', core::documentparser()->getThumb($row['provider'], $row['video']));			
						$rowBlock->assign('NUMBERVIEWS', Videoalbum::getNumberVideoViews($row['id']));	
						$tpl->assign('row_videos_list', $rowBlock);
					}
				}
				else $tpl->assign('NO_VIDEOS', core::getLanguage('str', 'empty'));	
			}
			else{
				
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'videoalbums'));
		
				$tpl->assign('PATH_VIDEO', $path_video);
				$tpl->assign('PATH_REMOVE_VIDEO', $path_remove_video);				
				$tpl->assign('STR_ADD_NEW_VIDEO', core::getLanguage('str', 'add_new_video'));
				$tpl->assign('STR_CREATE_NEW_ALBUM', core::getLanguage('str', 'create_new_album'));
				$tpl->assign('STR_OR', core::getLanguage('str', 'or'));		
				$tpl->assign('STR_MY_ALBUMS', core::getLanguage('str', 'albums'));
				$tpl->assign('STR_POPULAR_VIDEOS',  core::getLanguage('str', 'popular_videos'));
				$tpl->assign('STR_MY_VIDEOS', core::getLanguage('str', 'videos'));		
				$tpl->assign('NUMBER_ALBUMS', Videoalbum::NumberAlbums($id_event, 'event'));
				$tpl->assign('NUMBER_POPULAR_VIDEOS', Videoalbum::getNumberPopVideos('event'));		
				$tpl->assign('NUMBER_MY_VIDEOS', Videoalbum::NumberVideos($id_event, 'event'));				
				$tpl->assign('NO_POP_VIDEOS', core::getLanguage('str', 'empty'));					
		
				$arr_albums = Videoalbum::getAlbumList($id_event, 'event');
		
				if($arr_albums){
					foreach($arr_albums as $row){
						$rowBlock = $tpl->fetch('row_my_videoalbum_list');	
						$rowBlock->assign('ID', $row['id']);	
						$rowBlock->assign('NAME', $row['name']);	
						$rowBlock->assign('THUMB', Videoalbum::getThumb($row['id']));	
						$rowBlock->assign('CREATED_AT', core::documentparser()->mysql_russian_date($row['created_at']));	
						$rowBlock->assign('UPDATED_AT', core::documentparser()->mysql_russian_date($row['updated_at']));	
						$rowBlock->assign('STR_UPDATED', core::getLanguage('str', 'updated'));					
						$rowBlock->assign('STR_CREATED', core::getLanguage('str', 'created'));
						$rowBlock->assign('PATH_VIDEO', $path_video);
						$rowBlock->assign('PATH_REMOVE_VIDEO', $path_remove_video);						
						$rowBlock->assign('PATH_EDIT_VIDEO', $path_edit_video);						
						$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));					
						$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));
						$tpl->assign('row_my_videoalbum_list', $rowBlock);			
					}
				}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
		
				$arr_my_videos = Videoalbum::getVideosList($id_event, 'event', 6, 0);
		
				if($arr_my_videos){
					foreach($arr_my_videos as $row){
						$rowBlock = $tpl->fetch('row_my_videos_list');					
						$rowBlock->assign('ID', $row['id']);
						$rowBlock->assign('ID_VIDEO', $row['id_video']);					
						$rowBlock->assign('DESCRIPTION', $arow['description']);
						$rowBlock->assign('THUMB', core::documentparser()->getThumb($row['provider'], $row['video']));
						$rowBlock->assign('VIDEO', core::documentparser()->getVideoPlayer($row['provider'], $row['video']));					
						$rowBlock->assign('NUMBERVIEWS', Videoalbum::getNumberVideoViews($row['id_video']));						
						$tpl->assign('row_my_videos_list', $rowBlock);
					}	
				}	
				else $tpl->assign('NO_MY_VIDEOS', core::getLanguage('str', 'empty'));
			}			

		break;		
	}	
	
	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

	include_once "footer.inc";	
	
	$tpl->display();
}