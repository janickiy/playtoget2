<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

if($_SESSION['user_authorization'] == "ok"){

	Auth::authorization();

	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

	core::user()->setUser_id($_SESSION['user_id']);
	$user = core::user()->getUserInfo();

	core::user()->setUserActivity();

	$tpl->assign('NUMBERMESSAGE', core::user()->MessageNotification());
	$tpl->assign('NUMBERINVITATION', core::user()->AddFriendsNotification());
	$tpl->assign('STR_REMOVE_FROM_ADMINISTRATORS', core::getLanguage('str', 'remove_from_administrators'));	
	$tpl->assign('STR_UNBLOCK_USER', core::getLanguage('str', 'unblock_user'));	
	$tpl->assign('STR_COMMUNITY_HASNT_EVENTS', core::getLanguage('str', 'team_hasnt_events'));

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

	switch ($_REQUEST['action']) {
	
		case 'create_team':
	
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
			$type = Core_Array::getRequest('type');
			$about = htmlspecialchars(trim(Core_Array::getRequest('about')));
			$id_place = Core_Array::getRequest('id_place');
			$id_sport = Core_Array::getRequest('id_sport');
			$file_ava = Core_Array::getRequest('file_ava');
			$cover_page = Core_Array::getRequest('file_cover');
	
			$errors = array();

			if(empty($name)) $errors[] = core::getLanguage('error', 'empty_group_name');			
	
			if(count($errors) == 0){
			
				$fields = array();
				$fields['id'] = 0;
				$fields['type'] = 'team';
				$fields['name'] = $name;
				$fields['about'] = $about;
				$fields['created_at'] = date("Y-m-d H:i:s");
				$fields['avatar'] = basename($file_ava);
				$fields['cover_page'] = basename($cover_page);
		
				if(is_numeric($id_place)){
					$city = Places::getCityInfo($id_place);
			
					if($city['name_ru']) $fields['place'] = $city['name_ru'];			
				}

				if(is_numeric($id_sport)){
					$sport_type = Sport::getSportType($id_sport);
			
					if($sport_type) $fields['sport_type'] = $sport_type;
				}
			
				$insert_id = Communities::addNewCommunity($fields, $user['id']);	
		
				if($insert_id){
					Places::addGeoTarget($insert_id, 'team', $id_place);
				
					header('Location: http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&community_id=' . $insert_id);		
					exit();
				}		
				else
					$errors[] = core::getLanguage('error', 'web_apps_error');
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;	
	
		case 'create_photoalbum':
	
			if(Communities::getPermissionPhoto($communities_settings['permission_photo'], $community_id, $user['id'])){
				$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
				$community_id = core::database()->escape(Core_Array::getRequest('community_id'));
		
				$errors = array();
		
				if(empty($name)) $errors[] = core::getLanguage('error', 'empty_album_name');
				if(!empty($name) && Photoalbum::checkNameExists($name, $community_id, 'team')) $errors[] = core::getLanguage('error', 'album_name_exists');
		
				if(count($errors) == 0){
					$fields = array();
		
					$fields['id'] = 0;		
					$fields['name'] = $name;	
					$fields['created_at'] = date("Y-m-d H:i:s");	
					$fields['photoalbumable_type'] = 'team';
					$fields['owner_id'] = $community_id;
			
					$result = Photoalbum::createAlbum($fields);	

					if($result)	{
						header("Location: ./?task=teams&community_id=" . $community_id . "&q=photoalbums");
						exit;
					}
					else{
						$error_msg = core::getLanguage('error', 'web_apps_error');
					}			
				}
				else $error_msg = core::getLanguage('error', 'web_apps_error');
			}else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;
	
		case 'edit_photoalbum':
	
			$name = htmlspecialchars(trim($_POST['name']));
			$community_id = core::database()->escape((int)Core_Array::getRequest('community_id'));
			$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
		
			$errors = array();
		
			if(empty($name)) $errors[] = core::getLanguage('error', 'empty_album_name');

			if(count($errors) == 0 and (Communities::checkOwnerCommunity($community_id, $user['id']) || Communities::checkAdminCommunity($community_id, $user['id']))){
				$fields = array();
				$fields['name'] = $name;
				$fields['updated_at'] = date("Y-m-d H:i:s");			
			
				$result = Photoalbum::editAlbum($fields, $id_album);
			
				if($result)	{
					header("Location: ./?task=teams&community_id=" . $community_id . "&q=photoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}			
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;
	
		case 'remove_photoalbum':
	
			$community_id = core::database()->escape((int)Core_Array::getRequest('community_id'));
			$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));

			if(Communities::checkOwnerCommunity($community_id, $user['id']) || Communities::checkAdminCommunity($community_id, $user['id'])){
		
				$result = Photoalbum::removeAlbum((int)Core_Array::getRequest('id_album'));
			
				if($result)	{
					header("Location: ./?task=teams&community_id=" . $community_id . "&q=photoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
		
		break;
	
		case 'create_videoalbum':
	
			if(Communities::getPermissionVideo($communities_settings['permission_photo'], $community_id, $user['id'])){
	
				$errors = array();
	
				$community_id = core::database()->escape((int)Core_Array::getRequest('community_id'));
				$name = htmlspecialchars(trim((int)Core_Array::getRequest('name')));
				$community_id = Core_Array::getRequest('community_id');
				if(empty($name)) $errors[] = core::getLanguage('error', 'empty_album_name');
				if(!empty($name) && Videoalbum::checkNameExists($name, $community_id, 'team')) $errors[] = core::getLanguage('error', 'album_name_exists');
	
				if(count($errors) == 0){
					$fields = array();
		
					$fields['id'] = 0;		
					$fields['name'] = $name;	
					$fields['created_at'] = date("Y-m-d H:i:s");	
					$fields['videoalbumable_type'] = 'team';
					$fields['owner_id'] = $community_id;	
		
					$result = Videoalbum::createAlbum($fields);	

					if($result)	{
						header("Location: ./?task=teams&community_id=" . $community_id . "&q=videoalbums");
						exit;
					}
					else{
						$error_msg = core::getLanguage('error', 'web_apps_error');
					}
				}else $error_msg = core::getLanguage('error', 'web_apps_error');	
			}else $error_msg = core::getLanguage('error', 'web_apps_error');		
	
		break;
	
		case 'edit_videoalbum':
	
			$community_id = core::database()->escape((int)Core_Array::getRequest('community_id'));
			$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
		
			$errors = array();
		
			if(empty($name)) $errors[] = core::getLanguage('error', 'empty_album_name');

			if(count($errors) == 0 and (Communities::checkOwnerCommunity($community_id, $user['id']) || Communities::checkAdminCommunity($community_id, $user['id']))){
				$fields = array();
				$fields['name'] = $name;
				$fields['updated_at'] = date("Y-m-d H:i:s");			
			
				$result = Videoalbum::editAlbum($fields, $id_album, $community_id);
			
				if($result)	{
					header("Location: ./?task=teams&community_id=" . $community_id . "&q=videoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}			
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;
	
		case 'remove_videoalbum':
	
			if(Communities::checkOwnerCommunity($community_id, $user['id']) || Communities::checkAdminCommunity($community_id, $user['id'])){
				$community_id = core::database()->escape((int)Core_Array::getRequest('community_id'));
				$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
		
				$result = Videoalbum::removeAlbum($id_album, $community_id);	
	
				if($result)	{
					header("Location: ./?task=teams&community_id=" . $community_id . "&q=videoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}
			}
		
		break;
	
		case 'add_video':
		
			if(Communities::getPermissionVideo($communities_settings['permission_photo'], $community_id, $user['id'])){ 
		
				$video = htmlspecialchars(trim(Core_Array::getRequest('video')));
				$description = htmlspecialchars(trim(Core_Array::getRequest('description')));
				$community_id = Core_Array::getRequest('community_id');
				$set_video = core::documentparser()->detect_video_id($video);
		
				$errors[] = array();
		
				if($set_video['video']){
					$fields = array();
					$fields['id'] = 0;
					$fields['videoalbum_id'] = Core_Array::getRequest('videoalbum_id');
					$fields['provider'] = $set_video['provider'];			
					$fields['video'] = $set_video['video'];			
					$fields['description'] = $description;			
					$fields['owner_id'] = $community_id;
					$fields['created_at'] = date("Y-m-d H:i:s");
			
					$result = Videoalbum::addVideo($fields);
			
					if($result)	{
						header("Location: ./?task=teams&community_id=" . $community_id . "&q=videoalbums");
						exit;
					}
					else{
						$error_msg = core::getLanguage('error', 'web_apps_error');
					}			
				}
				else $error_msg = core::getLanguage('error', 'web_apps_error');	
			} else $error_msg = core::getLanguage('error', 'web_apps_error');		
		
		break;
	
		case 'edit':
	
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
			$about = htmlspecialchars(trim(Core_Array::getRequest('about')));
			$id_sport = Core_Array::getRequest('id_sport');
			$community_id = core::database()->escape(Core_Array::getRequest('community_id'));
			$file_ava = Core_Array::getRequest('file_ava');
			$cover_page = Core_Array::getRequest('file_cover');
			$id_place = Core_Array::getRequest('id_place');		
			
			if(Communities::checkOwnerCommunity($community_id, $user['id'])){
	
				$errors = array();
	
				if(empty($name)) $errors[] = core::getLanguage('error', 'empty_team_name');	
				if(count($errors) == 0 && Communities::checkOwnerCommunity($community_id, $user['id'])){
					$fields = array();
					$fields['name'] = $name;
					$fields['about'] = $about;
					$fields['updated_at'] = date("Y-m-d H:i:s");
					
					if($file_ava) $fields['avatar'] = basename($file_ava);
					if($cover_page) $fields['cover_page'] = basename($cover_page);			
			
					$settings = array();			
					$settings['permission_wall'] = is_numeric($_POST['community']['permission_wall']) ? $_POST['community']['permission_wall'] : 0;			
					$settings['permission_photo'] = is_numeric($_POST['community']['permission_photo']) ? $_POST['community']['permission_photo'] : 0;			
					$settings['permission_video'] = is_numeric($_POST['community']['permission_video']) ? $_POST['community']['permission_video'] : 0;			
					$settings['type'] = is_numeric($_POST['community']['type']) ? $_POST['community']['type'] : 0;			
			
					if(is_numeric($id_place)){
						$city = Places::getCityInfo($id_place);			
						if($city['name_ru']) $fields['place'] = $city['name_ru'];			
					}

					if(is_numeric($id_sport)){
						$sport_type = Sport::getSportType($id_sport);			
						if($sport_type) $fields['sport_type'] = $sport_type;
					}			
			
					$result = Communities::editCommunity($fields, $settings, 'team', $community_id);	
			
					if($result){
						header('Location: http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&community_id=' . $community_id);		
						exit();
					}else $error_msg = core::getLanguage('error', 'web_apps_error');
				}else $error_msg = core::getLanguage('error', 'web_apps_error');
			}else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;
	}

	if($_GET['community_id']){
		$community_id = core::database()->escape((int)Core_Array::getRequest('community_id'));	
		$community = Communities::getCommunityInfo($community_id);
		$communities_settings = Communities::getCommunitySettings($community_id);
		$tpl->assign('COMMUNITY_DESCRIPTION', nl2br($community['about']));	
		if($community['banned'] == 1) $tpl->assign('BLOCK_PAGE', 'yes');
	}

	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

	$tpl->assign('STR_CHOOSE_CITY', core::getLanguage('str', 'choose_city'));
	$tpl->assign('STR_SEARCH_FITNESS_BY_NAME', core::getLanguage('str', 'search_fitness_by_name'));
	$tpl->assign('STR_SEND', core::getLanguage('str', 'send'));	
	$tpl->assign('STR_YOUR_COMMENT', core::getLanguage('str', 'your_comment'));
	$tpl->assign('BUTTON_JOIN_TO_COMMUNITY', core::getLanguage('button', 'join_to_community'));	
	$tpl->assign('BUTTON_LEAVE_COMMUNITY', core::getLanguage('button', 'leave_community'));	
	$tpl->assign('BUTTON_ACCEPT INVITATION', core::getLanguage('button', 'accept invitation'));

	$tpl->assign('STR_ALL_PHOTOS', core::getLanguage('str', 'all_photos'));
	$tpl->assign('STR_ALL_VIDEOS', core::getLanguage('str', 'all_videos'));
	$tpl->assign('BUTTON_EDIT_PHOTO', core::getLanguage('button', 'edit_photo'));	
	$tpl->assign('BUTTON_CHANGE_COVER', core::getLanguage('button', 'change_cover'));

	$create_community = './?task=teams&q=create';

	$tpl->assign('PHOTOALBUMABLE_TYPE', 'team');	

	if($_GET['community_id']){	
	
		$community_id = core::database()->escape((int)Core_Array::getRequest('community_id'));
	
		if(Communities::checkExistence($community_id, 'team') or !is_numeric($_GET['community_id'])){
			header("HTTP/1.1 404 Not Found");
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;
		}
	
		if(Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id'])){
			$tpl->assign('COMMENT_AVATAR', core::documentparser()->communityAvatar($community));
		}
		else{
			$tpl->assign('COMMENT_AVATAR', core::documentparser()->userAvatar($user));
		}
	
		if(Communities::checkOwnerCommunity($community_id, $user['id'])) {
			$tpl->assign('ALLOW_EDIT', 'yes');
			$tpl->assign('COMMUNITY_TYPE', $community['type']);		
		}	
	
		if($communities_settings['permission_photo'] == 1 or Communities::getMemberShipStatus($community_id, $user['id']) == 4) $tpl->assign('COMMUNITY_PHOTOALBUMS', 'hide');	
		if($communities_settings['permission_video'] == 1 or Communities::getMemberShipStatus($community_id, $user['id']) == 4) $tpl->assign('COMMUNITY_VIDEOALBUMS', 'hide');	
	
		if($communities_settings['permission_video'] == 1 && $_GET['q'] == 'videoalbums'){
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;
		}

		if($communities_settings['permission_photo'] == 1 && $_GET['q'] == 'photoalbums'){
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;
		}	
		
		$tpl->assign('ID_COMMUNITY', $_GET['community_id']);
		$tpl->assign('STR_COMMUNITY_FEED', core::getLanguage('str', 'feed'));	
		$tpl->assign('STR_COMMUNITY_MEMBERS', core::getLanguage('str', 'members'));	
		$tpl->assign('STR_COMMUNITY_PHOTO', core::getLanguage('str', 'photo'));	
		$tpl->assign('STR_COMMUNITY_VIDEO', core::getLanguage('str', 'video'));
		$tpl->assign('STR_COMMUNITY_EVENTS', core::getLanguage('str', 'events'));	
		$tpl->assign('ID_OWNER', $_GET['community_id']);
		$tpl->assign('VIDEOALBUMABLE_TYPE', 'team');	
		$tpl->assign('STR_COMMUNITY_INFO', core::getLanguage('str', 'community_info'));	
		$tpl->assign('STR_COMMUNITY_ADMINISTRATORS', core::getLanguage('str', 'community_administrators'));	
		$tpl->assign('STR_COMMUNITY_PRIVACY', core::getLanguage('str', 'privacy'));	
		$tpl->assign('STR_COMMUNITY_BLACK_LIST', core::getLanguage('str', 'black_list'));	
		$tpl->assign('STR_PERMISSION_PHOTOS', core::getLanguage('str', 'permission_photos'));	
		$tpl->assign('STR_PERMISSION_VIDEO', core::getLanguage('str', 'permission_video'));	
		$tpl->assign('STR_TYPE_COMMUNITY', core::getLanguage('str', 'type_community'));		
		$tpl->assign('STR_PERMISSION_PHOTO_OPEN', core::getLanguage('str', 'permission_photo_open'));	
		$tpl->assign('STR_PERMISSION_PHOTO_DISABLE', core::getLanguage('str', 'permission_photo_disable'));	
		$tpl->assign('STR_PERMISSION_PHOTO_LIMITED', core::getLanguage('str', 'permission_photo_limited'));	
		$tpl->assign('STR_PERMISSION_VIDEO_OPEN', core::getLanguage('str', 'permission_video_open'));	
		$tpl->assign('STR_PERMISSION_VIDEO_DISABLE', core::getLanguage('str', 'permission_video_disable'));	
		$tpl->assign('STR_PERMISSION_VIDEO_LIMITED', core::getLanguage('str', 'permission_video_limited'));	
		$tpl->assign('STR_PERMISSION_PHOTO_HINT', core::getLanguage('str', 'permission_photo_hint'));	
		$tpl->assign('STR_PERMISSION_VIDEO_HINT', core::getLanguage('str', 'permission_video_hint'));	
		$tpl->assign('STR_TYPE_HINT', core::getLanguage('str', 'type_hint'));	
		$tpl->assign('STR_COMMUNITY_TYPE_OPEN', core::getLanguage('str', 'community_type_open'));	
		$tpl->assign('STR_COMMUNITY_TYPE_PRIVATE', core::getLanguage('str', 'community_type_private'));		
		$tpl->assign('STR_PERMISSION_WALL_OPEN', core::getLanguage('str', 'permission_wall_open'));	
		$tpl->assign('STR_PERMISSION_WALL_DISABLE', core::getLanguage('str', 'permission_wall_disable'));	
		$tpl->assign('STR_PERMISSION_WALL_LIMETED', core::getLanguage('str', 'permission_wall_limeted'));	
		$tpl->assign('STR_PERMISSION_WALL_CLOSED', core::getLanguage('str', 'permission_wall_closed'));	
		$tpl->assign('STR_PERMISSION_WALL_HINT', core::getLanguage('str', 'permission_wall_hint'));		
		$tpl->assign('STR_COMMUNITY_TYPE_CLOSED', core::getLanguage('str', 'community_type_closed'));	
		$tpl->assign('STR_ADD_TO_ADMINISTRATORS', core::getLanguage('str', 'add_to_administrators'));		
		$tpl->assign('STR_BLOCK_USER', core::getLanguage('str', 'block_user'));	
		
		$photoalbum_path = './?task=teams&community_id=' . $community_id . '&q=photoalbums';	
		$photoalbum_remove_path = './?task=teams&community_id=' . $community_id . '&action=remove_photoalbum';
		$photoalbum_edit_path = './?task=teams&community_id=' . $community_id . '&q=edit_photoalbum';
		$redirect_photo_album = '/?task=teams&community_id=' . $community_id . '&q=photoalbums';	
		$path_video = './?task=teams&community_id=' . $community_id . '&q=videoalbums';	
		$path_remove_video = './?task=teams&community_id=' . $community_id . '&q=videoalbums&action=remove_videoalbum';	
		$path_edit_video = './?task=teams&community_id=' . $community_id . '&q=edit_videoalbum';		
	
		if(Communities::getMemberShipStatus($community_id, $user['id']) == 1)		
			$tpl->assign('COMMUNITY_MEMBER', 'owner');
		else if(Communities::getMemberShipStatus($community_id, $user['id']) == 2)
			$tpl->assign('COMMUNITY_MEMBER', 'member');
		else if(Communities::getMemberShipStatus($community_id, $user['id']) == 3)
			$tpl->assign('COMMUNITY_MEMBER', 'admin');
		else if(Communities::getMemberShipStatus($community_id, $user['id']) == 4)
			$tpl->assign('COMMUNITY_MEMBER', 'blocked');
		else if(Communities::getMemberShipStatus($community_id, $user['id']) == 5)
			$tpl->assign('COMMUNITY_MEMBER', 'invited');
		else if(Communities::getMemberShipStatus($community_id, $user['id']) == '0'){
			$tpl->assign('COMMUNITY_MEMBER', 'applied');
		}
		else if(!Communities::getMemberShipStatus($community_id, $user['id']))
			$tpl->assign('COMMUNITY_MEMBER', 'none');	
		
		$tpl->assign('STR_COMMUNITY_SUSPENDED', core::getLanguage('str', 'community_suspended'));

		switch ($_GET['q']) {
		
			case 'members':
		
				$tpl->assign('QUERY', $_GET['q']);
				$tpl->assign('NUMBERMEMBER', Communities::countAllMemberCommunity($community_id));			
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'members'));			
				$tpl->assign('STR_MEMBERS', core::getLanguage('str', 'members'));
			
				$arr_members = Communities::getMemberAllList($community_id);			
			
				if($arr_members){
					foreach($arr_members as $row){
						$rowBlock = $tpl->fetch('row_members');	
						$rowBlock->assign('ID_USER', $row['user_id']);			
						$rowBlock->assign('SEL', $user['id']);
						$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));	
						$rowBlock->assign('STATUS', Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $row['user_id'])));
	
						if(Communities::checkOwnerCommunity($community_id, $user['id']) && Communities::getUserStatus($community_id, $row['user_id']) != 1 && Communities::getUserStatus($community_id, $row['user_id']) != 3) $rowBlock->assign('SHOW_ADD_TO_ADMIN_LINK', 'yes');
					
						if(Communities::getUserStatus($community_id, $row['user_id']) != 3 && Communities::checkOwnerCommunity($community_id, $user['id'])){
							if(Communities::getUserStatus($community_id, $row['user_id']) != 1 and Communities::getUserStatus($community_id, $row['user_id']) != 3){
								$rowBlock->assign('SHOW_USER_BLOCK_LINK', 'yes');
							}
						}
	
						$rowBlock->assign('FIRSTNAME', $row['firstname']);					
						$rowBlock->assign('LASTNAME', $row['lastname']);
						$rowBlock->assign('CITY', $row['city']);					
						$rowBlock->assign('ID_COMMUNITY', $community_id);
						$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline');
						$tpl->assign('row_members', $rowBlock);
					}
				}else $tpl->assign('NO_MEMBERS', core::getLanguage('str', 'empty'));			

				if(Communities::checkOwnerCommunity($community_id, $user['id']) and Communities::checkAdminCommunity($community_id, $user['id'])){
					$arr_members = Communities::getMemberList($community_id, 0);			
			
					if($arr_members){
						foreach($arr_members as $row){
							$rowBlock = $tpl->fetch('row_application_members');	
							$rowBlock->assign('ID_USER', $row['user_id']);			
							$rowBlock->assign('SEL', $user['id']);
							$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));					
							$rowBlock->assign('FIRSTNAME', $row['firstname']);					
							$rowBlock->assign('LASTNAME', $row['lastname']);
							$rowBlock->assign('CITY', $row['city']);					
							$rowBlock->assign('ID_COMMUNITY', $community_id);
							$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline');
							$tpl->assign('row_application_members', $rowBlock);
						}
					}else $tpl->assign('NO_APPLICATIONS', core::getLanguage('str', 'empty'));
				}else $tpl->assign('NO_APPLICATIONS', core::getLanguage('str', 'empty'));		
   
			break;		
		
			case 'photoalbums':
		
				if(Communities::getPermissionPhoto($communities_settings['permission_photo'], $community_id, $user['id']) == false){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}		
		
				$tpl->assign('QUERY', $_GET['q']);	

				if($_GET['id_album']){
					$tpl->assign('ID_ALBUM', $_GET['id_album']);
					$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
				
					if(Photoalbum::checkExistence($id_album) or !is_numeric($_GET['id_album'])){
						header("HTTP/1.1 404 Not Found");
						header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
						exit;
					}
					
					$info = Photoalbum::getPhotoAlbumInfo($id_album);
				
					$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'photoalbums'));					
					$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);
					$tpl->assign('STR_EDIT', core::getLanguage('str', 'edit'));		
					$tpl->assign('STR_REMOVE', core::getLanguage('str', 'remove'));			
					$tpl->assign('PHOTOALBUM_NAME', $info['name']);	
	
					$arr_photos = Photoalbum::getPicList($id_album, 9, 0);

					if($arr_photos){
						foreach($arr_photos as $row){
							$rowBlock = $tpl->fetch('row_photos_list');
							$rowBlock->assign('ID_PHOTO', $row['id']);
							$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], $info['photoalbumable_type']));	
							$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], $info['photoalbumable_type']));
							$rowBlock->assign('DESCRIPTION', $row['description']);
						
							if($row['owner_id'] == $user['id'] or Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id'])) $rowBlock->assign('ALLOW_EDIT', 'yes');
								
							$rowBlock->assign('STR_REMOVE_PHOTO', core::getLanguage('str', 'remove_photo'));							
							$tpl->assign('row_photos_list', $rowBlock);	
						}
					}			
					else $tpl->assign('NO_IMAGES', core::getLanguage('str', 'empty'));
				}
				else{
				
					$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'community_photos'));
		
					if($communities_settings['permission_photo'] != 1 and (($communities_settings['permission_photo'] == 0 or !$communities_settings['permission_photo']) || Communities::checkOwnerCommunity($community_id, $user['id']) || Communities::checkAdminCommunity($community_id, $user['id']))){ 
						$tpl->assign('SHOW_ADD_PHOTOS_MENU', 'show');
					}
				
					$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);
					$tpl->assign('STR_ADD_PHOTOS', core::getLanguage('str', 'add_photos'));		
					$tpl->assign('STR_CREATE_PHOTOALBUM', core::getLanguage('str', 'create_photoalbum'));
					$tpl->assign('STR_OR', core::getLanguage('str', 'or'));
					$tpl->assign('STR_POPULAR_PHOTOS', core::getLanguage('str', 'popular_photos'));			
					$tpl->assign('STR_SHOW_MORE', core::getLanguage('str', 'show_more'));		
					$tpl->assign('STR_MY_ALBUMS', core::getLanguage('str', 'community_album'));	
					$tpl->assign('STR_MY_PHOTOS', core::getLanguage('str', 'community_photos'));
					$tpl->assign('NUMBER_POPULAR_PHOTOS', Photoalbum::NumberTotalPopPhotos('team'));		
					$tpl->assign('NUMBER_MY_ALBUMS', Photoalbum::getNumberAlbums($community_id, 'team'));		
					$tpl->assign('NUMBER_MY_PHOTOS', Photoalbum::NumberPhotos($community_id, 'team'));				
					$tpl->assign('NO_POP_PHOTOS', core::getLanguage('str', 'empty'));				
		
					$arr_albums = Photoalbum::getAlbumList($community_id, 'team');
		
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
				
							if(($user_id == $row['owner_id'] and $communities_settings['permission_photo'] != 2) || Communities::checkOwnerCommunity($community_id, $user['id']) || Communities::checkAdminCommunity($community_id, $user['id'])) $rowBlock->assign('SHOW_EDIT_LINKS', 'show');
					
							$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));					
							$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));					

							$pic = Photoalbum::getMainImage($row['id']);
				
							$image = ($pic['small_photo'] && file_exists(core::documentparser()->photogalleryPic($pic['small_photo'], 'team'))) ? core::documentparser()->photogalleryPic($pic['small_photo'], 'team') : 'templates/images/default_group.png';
							$rowBlock->assign('IMAGE', $image);
-							$tpl->assign('row_my_album_list', $rowBlock);			
						}
					}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
	
					$arr_photos = Photoalbum::getPhotosList($community_id, 'team', 6, 0);
		
					if($arr_photos){
						foreach($arr_photos as $row){
							$rowBlock = $tpl->fetch('row_my_photos_list');
							$rowBlock->assign('ID', $row['id']);
							$rowBlock->assign('ID_PHOTO', $row['photo_id']);
							$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], 'team'));				
							$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], 'team'));
							$rowBlock->assign('DESCRIPTION', $row['description']);
						
							if($row['owner_id'] == $user['id'] or Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id'])) $rowBlock->assign('ALLOW_EDIT', 'yes');
						
							$rowBlock->assign('STR_REMOVE_PHOTO', core::getLanguage('str', 'remove_photo'));						
							$tpl->assign('row_my_photos_list', $rowBlock);
						}			
					}
					else $tpl->assign('NO_PHOTOS', core::getLanguage('str', 'empty'));
				}
		
			break;
		
			case 'edit_photoalbum':
		
				if(Communities::getPermissionPhoto($communities_settings['permission_photo'], $community_id, $user['id']) == false){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}
		
				$photoalbums = Photoalbum::getPhotoAlbumInfo(core::database()->escape((int)Core_Array::getRequest('id_album')));		
	
				$tpl->assign('QUERY', $_GET['q']);
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'edit_photoalbums'));				
				$tpl->assign('STR_EDIT_PHOTOALBUMS', core::getLanguage('title', 'edit_photoalbums'));
				$tpl->assign('ID_ALBUM', $_GET['id_album']);			
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);			
				$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));			
				$tpl->assign('NAME', $_POST['name'] ? $_POST['name'] : $photoalbums['name']);			
				$tpl->assign('BUTTON', core::getLanguage('button', 'edit'));			
			
			break;	
		
			case 'create_photoalbum':
		
				if(Communities::getPermissionPhoto($communities_settings['permission_photo'], $community_id, $user['id']) == false){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}		
		
				$tpl->assign('QUERY', $_GET['q']);			
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'create_photoalbum'));			
				$tpl->assign('STR_CREATE_PHOTOALBUM', core::getLanguage('str', 'create_photoalbum'));			
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);			
				$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));			
				$tpl->assign('BUTTON', core::getLanguage('button', 'create'));			
				$tpl->assign('NAME', $_POST['name']);					
		
			break;		
		
			case 'videoalbums':
		
				if(Communities::getPermissionVideo($communities_settings['permission_video'], $community_id, $user['id']) == false){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}		
		
				$tpl->assign('QUERY', $_GET['q']);	
		
				if($_GET['id_album']){
					$tpl->assign('ID_ALBUM', $_GET['id_album']);				
					$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
					
					if(Videoalbum::checkExistence($id_album) or !is_numeric($_GET['id_album'])){
						header("HTTP/1.1 404 Not Found");
						header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
						exit;
					}			
		
					$album = Videoalbum::getAlbumInfo($id_album, $community_id);
					$tpl->assign('TITLE_PAGE', $album['name']);						
					$tpl->assign('STR_EDIT', core::getLanguage('str', 'edit'));
					$tpl->assign('STR_REMOVE', core::getLanguage('str', 'remove'));	
					$tpl->assign('PATH_VIDEO', $path_video);				

					foreach(Videoalbum::getVideosAlbumList($id_album, 6, 0) as $row){
						$rowBlock = $tpl->fetch('row_videos_list');
						$rowBlock->assign('ID', $row['id']);					
						$rowBlock->assign('DESCRIPTION', $row['description']);
						$rowBlock->assign('VIDEO', core::documentparser()->getVideoPlayer($row['provider'], $row['video']));
						$rowBlock->assign('THUMB', core::documentparser()->getThumb($row['provider'], $row['video']));			
						$rowBlock->assign('NUMBERVIEWS', Videoalbum::getNumberVideoViews($row['id']));	
					
						if($row['owner_id'] == $user['id'] || Communities::checkOwnerCommunity($community_id, $user['id']) || Communities::checkAdminCommunity($community_id, $user['id'])){
							$rowBlock->assign('ALLOW_EDIT', 'show');
						}
						
						$rowBlock->assign('STR_REMOVE_VIDEO', core::getLanguage('str', 'remove_video'));					
					
						$tpl->assign('row_videos_list', $rowBlock);
					}				
				}
				else{
				
					$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'videoalbums'));	
				
					if($communities_settings['permission_video'] != 1 and (($communities_settings['permission_video'] == 0 or !$communities_settings['permission_video']) || Communities::checkOwnerCommunity($community_id, $user['id']) || Communities::checkAdminCommunity($community_id, $user['id']))){
						$tpl->assign('SHOW_ADD_VIDEO_MENU', 'show');
					}					
		
					$tpl->assign('PATH_VIDEO', $path_video);				
					$tpl->assign('PATH_REMOVE_VIDEO', $path_remove_video);			
					$tpl->assign('STR_ADD_NEW_VIDEO', core::getLanguage('str', 'add_new_video'));
					$tpl->assign('STR_CREATE_NEW_ALBUM', core::getLanguage('str', 'create_new_album'));
					$tpl->assign('STR_OR', core::getLanguage('str', 'or'));							
					$tpl->assign('STR_MY_ALBUMS', $_GET['user_id'] ? core::getLanguage('str', 'user_albums') : core::getLanguage('str', 'community_album'));
					$tpl->assign('STR_POPULAR_VIDEOS',  core::getLanguage('str', 'popular_videos'));
					$tpl->assign('STR_MY_VIDEOS', $_GET['user_id'] ? core::getLanguage('str', 'user_videos') : core::getLanguage('str', 'community_album'));		
					$tpl->assign('NUMBER_ALBUMS', Videoalbum::NumberAlbums($community_id, 'team'));
					$tpl->assign('NUMBER_POPULAR_VIDEOS', Videoalbum::getNumberPopVideos('team'));		
					$tpl->assign('NUMBER_MY_VIDEOS', Videoalbum::NumberVideos($community_id, 'team'));			
					$tpl->assign('NO_POP_VIDEOS', core::getLanguage('str', 'empty'));	
		
					$arr_albums = Videoalbum::getAlbumList($community_id, 'team');
		
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

							if($row['owner_id'] == $user['id'] || Communities::checkOwnerCommunity($community_id, $user['id']) || Communities::checkAdminCommunity($community_id, $user['id'])){
								$rowBlock->assign('SHOW_EDIT_LINKS', 'show');
							}
						
							$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));					
							$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));
							$tpl->assign('row_my_videoalbum_list', $rowBlock);			
						}
					}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
			
					$arr_my_videos = Videoalbum::getVideosList($community_id, 'team', 6, 0);
		
					if($arr_my_videos){
						foreach($arr_my_videos as $row){
							$rowBlock = $tpl->fetch('row_my_videos_list');					
							$rowBlock->assign('ID', $row['id']);
							$rowBlock->assign('ID_VIDEO', $row['video_id']);					
							$rowBlock->assign('DESCRIPTION', $arow['description']);
							$rowBlock->assign('THUMB', core::documentparser()->getThumb($row['provider'], $row['video']));
							$rowBlock->assign('VIDEO', core::documentparser()->getVideoPlayer($row['provider'], $row['video']));					
							$rowBlock->assign('NUMBERVIEWS', Videoalbum::getNumberVideoViews($row['video_id']));	

							if($row['owner_id'] == $user['id'] || Communities::checkOwnerCommunity($community_id, $user['id']) || Communities::checkAdminCommunity($community_id, $user['id'])){
								$rowBlock->assign('ALLOW_EDIT', 'show');
							}
						
							$rowBlock->assign('STR_REMOVE_VIDEO', core::getLanguage('str', 'remove_video'));						
						
							$tpl->assign('row_my_videos_list', $rowBlock);
						}	
					}	
					else $tpl->assign('NO_MY_VIDEOS', core::getLanguage('str', 'empty'));
				}			

			break;
		
			case 'add_video':
		
				if(Communities::getPermissionVideo($communities_settings['permission_video'], $community_id, $user['id']) == false){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}
		
				$tpl->assign('QUERY', $_GET['q']);				
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'add_new_video'));
	
				if(!empty($error_msg)) {
					$tpl->assign('STR_ERROR', core::getLanguage('str', 'error'));
					$tpl->assign('ERROR_ALERT', $error_msg);
				}	
	
				if(Videoalbum::NumberAlbums($community_id, 'team') == 0){
					$fields = array();
					$fields['id'] = 0;		
					$fields['name'] = core::getLanguage('str', 'community_album');		
					$fields['created_at'] = date("Y-m-d H:i:s");
					$fields['videoalbumable_type'] = 'team';		
					$fields['owner_id'] = $community_id;	
		
					Videoalbum::createAlbum($fields);		
				}

				foreach(Videoalbum::getVideoAlbumOption($community_id, 'team') as $row){
					$rowBlock = $tpl->fetch('row_option_videoalbum');
					$rowBlock->assign('ID', $row['id']);
					$rowBlock->assign('NAME', $row['name']);				
					$tpl->assign('row_option_videoalbum', $rowBlock);
				}
	
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
				$tpl->assign('STR_VIDEO', core::getLanguage('str', 'video'));	
				$tpl->assign('STR_DESCRIPTION', core::getLanguage('str', 'description'));		
				$tpl->assign('STR_ALBUM', core::getLanguage('str', 'album'));	
				$tpl->assign('OPTION_ID', $_POST['videoalbum_id']);	
				$tpl->assign('VIDEO', $_POST['video']);	
				$tpl->assign('DESCRIPTION', $_POST['description']);
				$tpl->assign('BUTTON_ADD', core::getLanguage('button', 'add'));	
		
			break;
		
			case 'create_videoalbum':
		
				if(Communities::getPermissionVideo($communities_settings['permission_video'], $community_id, $user['id']) == false){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}
		
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'create_videoalbum'));		
				$tpl->assign('QUERY', $_GET['q']);
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
				$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));	
				$tpl->assign('STR_CREATE_VIDEOALBUM', core::getLanguage('str', 'create_videoalbum'));
				$tpl->assign('NAME', $_POST['name']);
				$tpl->assign('BUTTON', core::getLanguage('button', 'create'));		
		
			break;		
		
			case 'edit_videoalbum':	

				if(Communities::getPermissionVideo($communities_settings['permission_video'], $community_id, $user['id']) == false){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}
		
				$videoalbum = Videoalbum::getVideoAlbumInfo(core::database()->escape((int)Core_Array::getRequest('id_album')));			
		
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'edit_videoalbum'));		
				$tpl->assign('QUERY', $_GET['q']);
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);			
				$tpl->assign('STR_EDIT_VIDEOALBUM', core::getLanguage('str', 'edit_videoalbum'));
				$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));						
				$tpl->assign('NAME', $_POST['name'] ? $_POST['name'] : $videoalbum['name']);			
				$tpl->assign('BUTTON', core::getLanguage('button', 'edit'));
		
			break;
		
			case 'events':
		
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'team_events'));			
				$tpl->assign('QUERY', $_GET['q']);	

				if(Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id'])) $tpl->assign('SHOW_ADD_EVENTS_FORM', 'show');
				
				$events = Events::getEventsOfMember($community_id, 'team');
				if ($events)
					foreach($events as $row){
						$rowBlock = $tpl->fetch('row_community_events');					
						$rowBlock->assign('ID', $row['event_id']);	
						$rowBlock->assign('NAME', $row['name']);
						$rowBlock->assign('AVATAR', core::documentparser()->eventAvatar($row['cover_page']));		
						$rowBlock->assign('SPORT_TYPE', $row['sport_type']);
						$rowBlock->assign('CITY', $row['place']);
						$rowBlock->assign('DATE', core::documentparser()->mysql_russian_datetime($row['date']));
						$rowBlock->assign('DESCRIPTION', $row['description']);
						$rowBlock->assign('PARTICIPANTS_COMMUNITY', str_replace('%MEMBERS%', Events::countMembers($row['event_id'], 'team'), core::getLanguage('str', 'participants_teams')));
						$rowBlock->assign('STATUS', Events::getEventStatus($row['event_id']) ? core::getLanguage('str', 'event_continues') : core::getLanguage('str', 'event_completed'));
						$tpl->assign('row_community_events', $rowBlock);
					}
				else{
					$tpl->assign('NO_EVENTS', 'yes');
				}			
		
			break;
		
			case 'edit':
		
				if(!Communities::checkOwnerCommunity($community_id, $user['id'])){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}	
		
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'edit_team'));		
				$tpl->assign('QUERY', $_GET['q']);
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);		
				$tpl->assign('STR_DESCRIPTION', core::getLanguage('str', 'description'));
				$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));	
				$tpl->assign('STR_PLACE', core::getLanguage('str', 'place'));	
				$tpl->assign('STR_SPORT_TYPE', core::getLanguage('str', 'sport_type'));	
				$tpl->assign('STR_IMAGE', core::getLanguage('str', 'image'));		
				$tpl->assign('COMMUNITY_NAME', $_POST['name'] ? $_POST['name'] : $community['name']);	
				$tpl->assign('COMMUNITY_ABOUT',  $_POST['about'] ? $_POST['about'] : $community['about']);	
				$tpl->assign('COMMUNITY_AVATAR', core::documentparser()->communityAvatar($community));			
				$tpl->assign('COMMUNITY_COVER_PAGE', core::documentparser()->communityCoverPage($community));			
				$tpl->assign('COMMUNITY_PLACE', $_POST['place'] ? $_POST['place'] : $community['place']);
				$tpl->assign('COMMUNITY_ID_PLACE', $_POST['id_place']);	
				$tpl->assign('COMMUNITY_SPORT', $_POST['sport'] ? $_POST['sport'] : $community['sport_type']);
				$tpl->assign('COMMUNITY_ID_SPORT',  $_POST['id_sport']);	
			
				$arr_administrators = Communities::getMemberList($community_id, 3);			
			
				if($arr_administrators){
					foreach($arr_administrators as $row){
						$rowBlock = $tpl->fetch('row_administrators');	
						$rowBlock->assign('ID_USER', $row['user_id']);			
						$rowBlock->assign('SEL', $user['id']);
						$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));					
						$rowBlock->assign('FIRSTNAME', $row['firstname']);					
						$rowBlock->assign('LASTNAME', $row['lastname']);
						$rowBlock->assign('CITY', $row['city']);					
						$rowBlock->assign('ID_COMMUNITY', $community_id);
						$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline');
						$tpl->assign('row_administrators', $rowBlock);
					}
				}else $tpl->assign('NO_ADMINISTRATORS', core::getLanguage('str', 'empty'));			
			
				$arr_blocked = Communities::getMemberList($community_id, 4);			
			
				if($arr_blocked){
					foreach($arr_blocked as $row){
						$rowBlock = $tpl->fetch('row_blocked');	
						$rowBlock->assign('ID_USER', $row['user_id']);			
						$rowBlock->assign('SEL', $user['id']);
						$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));					
						$rowBlock->assign('FIRSTNAME', $row['firstname']);					
						$rowBlock->assign('LASTNAME', $row['lastname']);
						$rowBlock->assign('CITY', $row['city']);					
						$rowBlock->assign('ID_COMMUNITY', $community_id);
						$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline');
						$tpl->assign('row_blocked', $rowBlock);
					}
				}else $tpl->assign('NO_BLOCKED', core::getLanguage('str', 'empty'));			

				$tpl->assign('PERMISSION_WALL', $_POST['community']['permission_wall'] ? $_POST['community']['permission_wall'] : $communities_settings['permission_wall']);
				$tpl->assign('PERMISSION_PHOTO', $_POST['community']['permission_photo'] ? $_POST['community']['permission_photo'] : $communities_settings['permission_photo']);
				$tpl->assign('PERMISSION_VIDEO', $_POST['community']['permission_video'] ? $_POST['community']['permission_video'] : $communities_settings['permission_video']);
				$tpl->assign('TYPE', $_POST['community']['type'] ? $_POST['community']['type'] : $communities_settings['type']);	
				$tpl->assign('BUTTON_SAVE_CHANGES', core::getLanguage('button', 'save_changes'));
		
			break;
		
			case 'add_photo':
		
				if(Communities::getPermissionPhoto($communities_settings['permission_photo'], $community_id, $user['id']) == false){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}
		
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'add_photos'));		
				$tpl->assign('QUERY', $_GET['q']);
				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);			
				$tpl->assign('REDIRECT_PHOTO_ALBUM', $redirect_photo_album);

				if(Photoalbum::getNumberAlbums($community_id, 'team') == 0){
					$fields = array();
					$fields['id'] = 0;
					$fields['name'] = core::getLanguage('str', 'community_album');
					$fields['created_at'] = date("Y-m-d H:i:s");
					$fields['photoalbumable_type'] = 'team';
					$fields['owner_id'] = $community_id;		
			
					Photoalbum::createAlbum($fields);	
				}			
			
				$arr = Photoalbum::getAlbumsOptionList($community_id, 'team');
			
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
		
			default:
		
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'feed'));	
				$tpl->assign('STR_WHATS_INTERESTING', core::getLanguage('str', 'whats_interesting'));
				$tpl->assign('STR_REPLY', core::getLanguage('str', 'reply'));			
		
				if(Communities::getPermissionWall($communities_settings['permission_wall'], $community_id, $user['id'])) {
					$tpl->assign('SHOW_COMMMENT_FORM', 'show');
				}			
				
				if(Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id'])) $tpl->assign('ADMIN', 'yes');
		
				if($communities_settings['permission_wall'] != 1){
					$arr = Comments::treeComments(0, Comments::getCommentList($community_id, 'team', 10, 0));

					foreach($arr as $row){
						$rowBlock = $tpl->fetch('row_comments');	
						$rowBlock->assign('ID', $row['id_comment']);
						$rowBlock->assign('ID_PARENT', $row['parent_id']);	
						$rowBlock->assign('ID_USER', $row['user_id']);			
						$rowBlock->assign('ID_USER_SESSION', $user['id']);
						$rowBlock->assign('ID_CONTENT', $row['content_id']);				
						$rowBlock->assign('ID_COMMUNITY', $row['content_id']);
						$rowBlock->assign('COMMUNITY_NAME', $community['name']);
						$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline');
		
						$rowBlock->assign('COMMENT_AVATAR', Comments::getCommentAvatar($row['id_comment']));
						$rowBlock->assign('COMMENT_NAME', Comments::getCommentAuthorName($row['id_comment']));	
						$rowBlock->assign('BEHALFABLE_TYPE', $row['behalfable_type']);			
							
						$rowBlock->assign('CREATED', $row['created']);		
						$rowBlock->assign('CONTENT', core::documentparser()->link_replace($row['content']));
						$rowBlock->assign('NUMBERTELL', Comments::getNumberTell($row['id_comment'], 'comment'));
						$rowBlock->assign('NUMBERLIKED', Comments::getNumberLiked($row['id_comment'], 'comment'));
				
						if($user['id'] == $row['user_id'])
							$rowBlock->assign('BUTTON_SHARE', 'hide');
						else
							$rowBlock->assign('BUTTON_SHARE', 'show');	
				
						if($row['parent_id'] == 0){
								foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
									$rowAttach = $rowBlock->fetch('row_attach');
									$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
									$photo_src = Photoalbum::getPhotoSmallSrc($photo);
									if($photo_src){
										$rowAttach->assign('SMALL_PHOTO', $photo_src);
										$rowAttach->assign('ID_PHOTO', $photo['photo_id']);
										$rowBlock->assign('row_attach', $rowAttach);
									}
								}
							}
							else{
								foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
									$rowAttach = $rowBlock->fetch('row_reply_attach');
									$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
									$photo_src = Photoalbum::getPhotoSmallSrc($photo);
									if($photo_src){
										$rowAttach->assign('SMALL_PHOTO', $photo_src);
										$rowAttach->assign('ID_PHOTO', $photo['photo_id']);
										$rowBlock->assign('row_reply_attach', $rowAttach);
									}
								}
							}	
					
						if(Communities::getPermissionWall($communities_settings['permission_wall'], $community_id, $user['id']) && $communities_settings['permission_wall'] != 3) {
							$rowBlock->assign('SHOW_REPLY_FORM', 'show');
						}
	
						$rowBlock->assign('STR_REPLY', core::getLanguage('str', 'reply'));
						$tpl->assign('row_comments', $rowBlock);
					}
				}
		}		
	}
	else{
		if($_GET['q'] == 'create'){
			$tpl->assign('QUERY', $_GET['q']);	
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'create_team'));				
			$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);		
			$tpl->assign('STR_DESCRIPTION', core::getLanguage('str', 'description'));
			$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));	
			$tpl->assign('STR_PLACE', core::getLanguage('str', 'place'));	
			$tpl->assign('STR_SPORT_TYPE', core::getLanguage('str', 'sport_type'));	
			$tpl->assign('STR_IMAGE', core::getLanguage('str', 'image'));			
			$tpl->assign('COMMUNITY_NAME', htmlspecialchars($_POST['name']));	
			$tpl->assign('COMMUNITY_ABOUT',  htmlspecialchars($_POST['about']));	
			$tpl->assign('COMMUNITY_PLACE', $_POST['place']);
			$tpl->assign('COMMUNITY_ID_PLACE', $_POST['id_place']);	
			$tpl->assign('COMMUNITY_SPORT', $_POST['sport']);
			$tpl->assign('COMMUNITY_ID_SPORT',  $_POST['id_sport']);
			$tpl->assign('COMMUNITY_AVATAR', 'templates/images/noimage.png');
			$tpl->assign('COMMUNITY_COVER_PAGE', 'templates/images/default_group.png');	
			$tpl->assign('BUTTON_SAVE_CHANGES', core::getLanguage('button', 'add'));
			$tpl->assign('BUTTON', core::getLanguage('button', 'create_team'));			
		}
		else{
			$user_id = $_GET['user_id'] ? core::database()->escape((int)Core_Array::getRequest('user_id')) : $user['id'];		
		
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'teams'));	
			$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
		
			if(!$_GET['user_id']) $tpl->assign('SHOW_SEARCH_FORM', 'show'); 
			
			$tpl->assign('STR_KEYWORD', core::getLanguage('str', 'keyword'));	
			$tpl->assign('BUTTON_SEARCH_COMMUNITY', core::getLanguage('button', 'search_team'));	
			$tpl->assign('BUTTON_CREATE_COMMUNITY', core::getLanguage('button', 'create_team'));	
			$tpl->assign('COMMUNITY_TYPE', 'team');	
			$tpl->assign('STR_SEARCH_COMMUNITY_IN_CITY', core::getLanguage('str', 'looking_for_team_in_city'));	
			$tpl->assign('STR_SEARCH_SPORT_TYPE', core::getLanguage('str', 'looking_for_sport_type'));	
			$tpl->assign('CREATE_COMMUNITY', $create_community);
			$tpl->assign('STR_THERE_ARE_NOT_POP_COMMUNITIES', core::getLanguage('str', 'there_are_not_pop_teams'));
			$tpl->assign('STR_YOU_HAVENT_JOINED_COMMUNITIES', core::getLanguage('str', 'you_havent_joined_teams'));			
			$tpl->assign('STR_YOU_DONT_HAVE_INVITATIONS', core::getLanguage('str', 'you_dont_have_invitations'));
			
			include_once "user_profile_info.inc";		
	
			$arr_pop_community = Communities::getPopularCommunitiesList('team', 5);
		
			$tpl->assign('STR_POPULAR_COMMUNITY', core::getLanguage('str', 'popular_teams'));	
			$tpl->assign('NUMBER_POPULAR_COMMUNITIES', Communities::getNumberPopularCommunities('team'));
			$tpl->assign('STR_MY_COMMUNITIES', $_GET['user_id'] ? core::getLanguage('str', 'teams') : core::getLanguage('str', 'my_teams'));
			$tpl->assign('NUMBER_MY_COMMUNITIES', Communities::getNumberMyCommunities($user_id, 'team'));	
			$tpl->assign('STR_THERE_ARE_NO_MORE_ENTRIES', core::getLanguage('str', 'there_are_no_more_entries'));

			if(!$_GET['user_id'] or $_GET['user_id'] == $user['id']) {
				if($arr_pop_community){
					foreach($arr_pop_community as $row){
						$rowBlock = $tpl->fetch('row_pop_communities_list');
						$rowBlock->assign('ID', $row['community_id']);
						$rowBlock->assign('NAME', $row['name']);	
						$rowBlock->assign('ABOUT', $row['about']);	

						if(Communities::getCommunityType($row['community_id']) == 1)
							$rowBlock->assign('TYPE', core::getLanguage('str', 'private_community')); 
						else if(Communities::getCommunityType($row['community_id']) == 2)
							$rowBlock->assign('TYPE', core::getLanguage('str', 'closed_community')); 
						else
							$rowBlock->assign('TYPE', core::getLanguage('str', 'open_community'));
					
						$rowBlock->assign('CITY', $row['place']);
						$rowBlock->assign('SPORT_TYPE', $row['sport_type']);					
						$rowBlock->assign('ID_USER', $user['id']);	
						$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countAllMemberCommunity($row['community_id']), core::getLanguage('str', 'participants_friends')));
					
						if(Communities::checkOwnerCommunity($row['community_id'], $user['id'])){
							$rowBlock->assign('ALLOW_EDIT', 'yes');
						} 
					
						$rowBlock->assign('ID_USER', $user['id']);			
						$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($row));		
						$rowBlock->assign('STATUS', Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $user['id'])));
						$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
						$tpl->assign('row_pop_communities_list', $rowBlock);
					}	
				}
				else{
					$tpl->assign('NO_POP_COMMUNITIES', core::getLanguage('str', 'empty'));
				}
			} else $tpl->assign('NO_MY_PAGE', 'yes');

			$arr_my_community = Communities::getMyCommunitiesList($user_id, 'team', 5, 0);
		
			if($arr_my_community){
				foreach($arr_my_community as $row){
					$rowBlock = $tpl->fetch('row_my_communities_list');	
					$rowBlock->assign('ID', $row['community_id']);		
					$rowBlock->assign('NAME', $row['name']);		
					$rowBlock->assign('ABOUT', $row['about']);					
					$rowBlock->assign('ID_USER', $user['id']);
					$rowBlock->assign('CITY', $row['place']);
					$rowBlock->assign('SPORT_TYPE', $row['sport_type']);	
					$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countAllMemberCommunity($row['community_id']), core::getLanguage('str', 'participants_friends')));
					
					if(Communities::checkOwnerCommunity($row['community_id'], $user['id'])) {
						$rowBlock->assign('ALLOW_EDIT', 'yes');
					}	
				
					$rowBlock->assign('STATUS', Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $user['id'])));				
				
					if(Communities::getCommunityType($row['community_id']) == 1)
						$rowBlock->assign('TYPE', core::getLanguage('str', 'private_community')); 
					else if(Communities::getCommunityType($row['community_id']) == 2)
						$rowBlock->assign('TYPE', core::getLanguage('str', 'closed_community')); 
					else
						$rowBlock->assign('TYPE', core::getLanguage('str', 'open_community'));
		
					$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($row));						
					$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));					
					$tpl->assign('row_my_communities_list', $rowBlock);
				}
			}
			else $tpl->assign('NO_MY_COMMUNITIES', core::getLanguage('str', 'empty'));	
		
			$tpl->assign('NUMBER_INVITED_ME_COMMUNITIES', Communities::getNumberInvitedMeCommunities($user['id'], 'team'));
			$tpl->assign('STR_AM_INVITED', core::getLanguage('str', 'am_invited'));
		
			$arr_invited_me_community = Communities::getInvitedMeCommunity($user['id'], 'team', 5, 0);
			
			if($arr_invited_me_community){
				foreach($arr_invited_me_community as $row){
					$rowBlock = $tpl->fetch('row_invited_me_community_list');
					$rowBlock->assign('ID', $row['community_id']);
					$rowBlock->assign('NAME', $row['name']);	
					$rowBlock->assign('ABOUT', $row['about']);	

					if(Communities::getCommunityType($row['community_id']) == 1)
						$rowBlock->assign('TYPE', core::getLanguage('str', 'private_community')); 
					else if(Communities::getCommunityType($row['community_id']) == 2)
						$rowBlock->assign('TYPE', core::getLanguage('str', 'closed_community')); 
					else
						$rowBlock->assign('TYPE', core::getLanguage('str', 'open_community'));
					
					$rowBlock->assign('CITY', $row['place']);
					$rowBlock->assign('SPORT_TYPE', $row['sport_type']);					
					$rowBlock->assign('ID_USER', $user['id']);	
					$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countAllMemberCommunity($row['community_id']), core::getLanguage('str', 'participants_friends')));					
					$rowBlock->assign('ID_USER', $user['id']);			
					$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($row));		
					$rowBlock->assign('STATUS', Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $user['id'])));
					$tpl->assign('row_invited_me_community_list', $rowBlock);
				}	
			}
			else{
				$tpl->assign('NO_INVITED_ME_COMMUNITIES', core::getLanguage('str', 'empty'));
			}
			
		}			
	}

	include_once "footer.inc";
		
	$tpl->display();
}
else{
	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");
	
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
	
	if(empty($_GET['community_id'])){
		header("Location: http://" . $_SERVER['SERVER_NAME']);
		exit;	
	}
	else{
		$community_id = core::database()->escape((int)Core_Array::getRequest('community_id'));
	
		if(Communities::checkExistence($community_id, 'team') or !is_numeric($_GET['community_id'])){
			header("HTTP/1.1 404 Not Found");
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;
		}
	
		if($communities_settings['permission_photo'] == 1) $tpl->assign('COMMUNITY_PHOTOALBUMS', 'hide');	
		if($communities_settings['permission_video'] == 1) $tpl->assign('COMMUNITY_VIDEOALBUMS', 'hide');	
	
		if($communities_settings['permission_video'] == 1 && $_GET['q'] == 'videoalbums'){
			header("HTTP/1.1 404 Not Found");
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;
		}

		if($communities_settings['permission_photo'] == 1 && $_GET['q'] == 'photoalbums'){
			header("HTTP/1.1 404 Not Found");
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;
		}
		
		if($_GET['community_id']){
			$community_id = core::database()->escape((int)Core_Array::getRequest('community_id'));	
			$community = Communities::getCommunityInfo($community_id);
			$communities_settings = Communities::getCommunitySettings($community_id);
			$tpl->assign('COMMUNITY_DESCRIPTION', nl2br($community['about']));	
			$tpl->assign('DESCRIPTION', $community['about']);
		}
	
		include_once "top.inc";
		include_once "left_block.inc";
		include_once "right_block.inc";

		$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
		$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
		$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
		$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
		$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
		$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
		$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));
		$tpl->assign('ID_COMMUNITY', $_GET['community_id']);
		$tpl->assign('STR_COMMUNITY_FEED', core::getLanguage('str', 'feed'));	
		$tpl->assign('STR_COMMUNITY_MEMBERS', core::getLanguage('str', 'members'));	
		$tpl->assign('STR_COMMUNITY_PHOTO', core::getLanguage('str', 'photo'));	
		$tpl->assign('STR_COMMUNITY_VIDEO', core::getLanguage('str', 'video'));
		$tpl->assign('STR_COMMUNITY_EVENTS', core::getLanguage('str', 'events'));
		$tpl->assign('ID_OWNER', $_GET['community_id']);
		$tpl->assign('VIDEOALBUMABLE_TYPE', 'team');	
		$tpl->assign('STR_COMMUNITY_INFO', core::getLanguage('str', 'community_info'));	
		$tpl->assign('STR_COMMUNITY_ADMINISTRATORS', core::getLanguage('str', 'community_administrators'));	
		$tpl->assign('STR_COMMUNITY_PRIVACY', core::getLanguage('str', 'privacy'));	
		$tpl->assign('STR_COMMUNITY_BLACK_LIST', core::getLanguage('str', 'black_list'));	
		$tpl->assign('STR_PERMISSION_PHOTOS', core::getLanguage('str', 'permission_photos'));	
		$tpl->assign('STR_PERMISSION_VIDEO', core::getLanguage('str', 'permission_video'));	
		$tpl->assign('STR_TYPE_COMMUNITY', core::getLanguage('str', 'type_community'));		
		
		if($community['banned']) $tpl->assign('BLOCK_PAGE', 'yes');
		
		$tpl->assign('STR_COMMUNITY_SUSPENDED', core::getLanguage('str', 'community_suspended'));
		
		switch ($_GET['q']) {
			
			case 'members':
		
				$tpl->assign('QUERY', $_GET['q']);
				$tpl->assign('NUMBERMEMBER', Communities::countAllMemberCommunity($community_id));			
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'members'));			
				$tpl->assign('STR_MEMBERS', core::getLanguage('str', 'members'));
			
				$arr_members = Communities::getMemberAllList($community_id);			
			
				if($arr_members){
					foreach($arr_members as $row){
						$rowBlock = $tpl->fetch('row_members');	
						$rowBlock->assign('ID_USER', $row['user_id']);			
						$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));	
						$rowBlock->assign('STATUS', Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $row['user_id'])));
	
						$rowBlock->assign('FIRSTNAME', $row['firstname']);					
						$rowBlock->assign('LASTNAME', $row['lastname']);
						$rowBlock->assign('CITY', $row['city']);					
						$rowBlock->assign('ID_COMMUNITY', $community_id);
						$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline');
						$tpl->assign('row_members', $rowBlock);
					}
				}else $tpl->assign('NO_MEMBERS', core::getLanguage('str', 'empty'));			

			break;		
		
			case 'photoalbums':
		
				$tpl->assign('QUERY', $_GET['q']);	

				if($_GET['id_album']){
					$tpl->assign('ID_ALBUM', $_GET['id_album']);
					$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
				
					if(Photoalbum::checkExistence($id_album) or !is_numeric($_GET['id_album'])){
						header("HTTP/1.1 404 Not Found");
						header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
						exit;
					}
				
					$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'photoalbums'));				
					$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);	
		
					$info = Photoalbum::getPhotoAlbumInfo($id_album);
		
					$tpl->assign('PHOTOALBUM_NAME', $info['name']);	
	
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
					$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'community_photos'));
				
					$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);
					$tpl->assign('STR_ADD_PHOTOS', core::getLanguage('str', 'add_photos'));		
					$tpl->assign('STR_CREATE_PHOTOALBUM', core::getLanguage('str', 'create_photoalbum'));
					$tpl->assign('STR_OR', core::getLanguage('str', 'or'));
					$tpl->assign('STR_POPULAR_PHOTOS', core::getLanguage('str', 'popular_photos'));
					$tpl->assign('STR_SHOW_MORE', core::getLanguage('str', 'show_more'));		
					$tpl->assign('STR_MY_ALBUMS', core::getLanguage('str', 'community_album'));	
					$tpl->assign('STR_MY_PHOTOS', core::getLanguage('str', 'community_photos'));
					$tpl->assign('NUMBER_POPULAR_PHOTOS', Photoalbum::NumberTotalPopPhotos('team'));		
					$tpl->assign('NUMBER_MY_ALBUMS', Photoalbum::getNumberAlbums($community_id, 'team'));		
					$tpl->assign('NUMBER_MY_PHOTOS', Photoalbum::NumberPhotos($community_id, 'team'));			
					$tpl->assign('NO_POP_PHOTOS', core::getLanguage('str', 'empty'));				
		
					$arr_albums = Photoalbum::getAlbumList($community_id, 'team');
		
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
				
							$image = ($pic['small_photo'] && file_exists(core::documentparser()->photogalleryPic($pic['small_photo'], 'team'))) ? core::documentparser()->photogalleryPic($pic['small_photo'], 'team') : 'templates/images/default_group.png';
							$rowBlock->assign('IMAGE', $image);
				
							$tpl->assign('row_my_album_list', $rowBlock);			
						}
					}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
	
					$arr_photos = Photoalbum::getPhotosList($community_id, 'team', 6, 0);
		
					if($arr_photos){
						foreach($arr_photos as $row){
							$rowBlock = $tpl->fetch('row_my_photos_list');
							$rowBlock->assign('ID', $row['id']);
							$rowBlock->assign('ID_PHOTO', $row['photo_id']);
							$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], 'team'));				
							$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], 'team'));
							$rowBlock->assign('DESCRIPTION', $row['description']);
							$rowBlock->assign('STR_REMOVE_PHOTO', core::getLanguage('str', 'remove_photo'));
							$tpl->assign('row_my_photos_list', $rowBlock);
						}			
					}
					else $tpl->assign('NO_PHOTOS', core::getLanguage('str', 'empty'));
				}
		
			break;			
			
			case 'events':
		
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'group_events'));			
				$tpl->assign('QUERY', $_GET['q']);
			
				$events = Events::getEventsOfMember($community_id, 'team');
				
				if ($events)
					foreach($events as $row){
						$rowBlock = $tpl->fetch('row_community_events');					
						$rowBlock->assign('ID', $row['event_id']);	
						$rowBlock->assign('NAME', $row['name']);
						$rowBlock->assign('AVATAR', core::documentparser()->eventAvatar($row['cover_page']));		
						$rowBlock->assign('SPORT_TYPE', $row['sport_type']);
						$rowBlock->assign('CITY', $row['place']);
						$rowBlock->assign('DATE', core::documentparser()->mysql_russian_datetime($row['date']));
						$rowBlock->assign('DESCRIPTION', $row['description']);
						$rowBlock->assign('PARTICIPANTS_COMMUNITY', str_replace('%MEMBERS%', Events::countMembers($row['event_id'], 'team'), core::getLanguage('str', 'participants_teams')));
						$rowBlock->assign('STATUS', Events::getEventStatus($row['event_id']) ? core::getLanguage('str', 'event_continues') : core::getLanguage('str', 'event_completed'));
						$tpl->assign('row_community_events', $rowBlock);
					}
				else{
					$tpl->assign('NO_EVENTS', 'yes');
				}
		
			break;			
		}	
	}
	
	include_once "footer.inc";
	
	$tpl->display();
}
