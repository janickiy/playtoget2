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

	$css = array();
	$css[] = './templates/css/bootstrap-theme.min.css';
	$css[] = './templates/css/bootstrap.min.css';
	$css[] = './templates/css/style.css';
	$css[] = './templates/css/owl.carousel.css';
	$css[] = './templates/css/owl.theme.css';
	$css[] = './templates/css/owl.transitions.css';
	$css[] = './templates/css/lightbox.css';

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

	switch ($_REQUEST['action']){
		case 'create_videoalbum':	
	
			$errors = array();
	
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
			
			if(empty($name)) $errors[] = core::getLanguage('error', 'empty_album_name');
			if(!empty($name) && Videoalbum::checkNameExists($name, $user['id'], 'user')) $errors[] = core::getLanguage('error', 'album_name_exists');
	
			if(count($errors) == 0){
				$fields = array();		
				$fields['id'] = 0;		
				$fields['name'] = $name;	
				$fields['created_at'] = date("Y-m-d H:i:s");	
				$fields['videoalbumable_type'] = 'user';
				$fields['owner_id'] = $user['id'];	
		
				$result = Videoalbum::createAlbum($fields);	

				if($result)	{
					header("Location: ./?task=videoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;		
	
		case 'edit_videoalbum':
	
			$errors = array();
	
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
			
			if(empty($name)) $errors[] = core::getLanguage('error', 'empty_album_name');
			
			$id_album = core::database()->escape(Core_Array::getRequest('id_album'));
		
			if(count($errors) == 0){
				$fields = array();
				$fields['name'] = $name;
				$fields['updated_at'] = date("Y-m-d H:i:s");		
		
				$result = Videoalbum::editAlbum($fields, $id_album, $user['user_id']);
		
				if($result){
					header("Location: ./?task=videoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
		
		break;
	
		case 'add_video':
	
			$video = htmlspecialchars(trim(Core_Array::getRequest('video')));
			$description = htmlspecialchars(trim(Core_Array::getRequest('description')));
		
			$set_video = core::documentparser()->detect_video_id($video);
		
			if($set_video['video']){
				$fields = array();
				$fields['id'] = 0;
				$fields['videoalbum_id'] = Core_Array::getRequest('videoalbum_id');
				$fields['provider'] = $set_video['provider'];			
				$fields['video'] = $set_video['video'];			
				$fields['description'] = $description;			
				$fields['owner_id'] = $user['id'];
				$fields['created_at'] = date("Y-m-d H:i:s");
			
				$result = Videoalbum::addVideo($fields);
			
				if($result)	{
					header("Location: ./?task=videoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}			
			}
			else
				$error_msg = core::getLanguage('error', 'video_has_been_added');
		
		break;	
	
		case 'remove':

			$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
	
			$result = Videoalbum::removeAlbum($id_album, $user['id']);	
	
			if($result)	{
				header("Location: ./?task=videoalbums");
				exit;
			}
	
		break;
	}	

	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";

	$tpl->assign('STR_HIDE', core::getLanguage('str', 'hide'));	
	$tpl->assign('STR_SHOW', core::getLanguage('str', 'show'));	
	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));
	$tpl->assign('STR_ALL_VIDEOS', core::getLanguage('str', 'all_videos'));

	$path_video = './?task=videoalbums';	
	$path_remove_video = './?task=videoalbums&q=videoalbums&action=remove';	
	$path_edit_video = './?task=videoalbums&q=edit_videoalbum';

	if($_GET["q"] == "create_videoalbum"){
		$tpl->assign('QUERY', $_GET["q"]);	
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'create_videoalbum'));
	
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
		$tpl->assign('NAME', $_POST['name']);	
		$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));
		$tpl->assign('BUTTON', core::getLanguage('button', 'create'));		
	}
	else if($_GET["q"] == "add_video"){
		$tpl->assign('QUERY', $_GET["q"]);
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'add_new_video'));
	
		if(Videoalbum::NumberAlbums($user['id'], 'user') == 0){
			$fields = array();
		
			$fields['id'] = 0;		
			$fields['name'] = core::getLanguage('str', 'my_album');		
			$fields['created_at'] = date("Y-m-d H:i:s");
			$fields['videoalbumable_type'] = 'user';		
			$fields['owner_id'] = $user['id'];	
		
			Videoalbum::createAlbum($fields);		
		}

		foreach(Videoalbum::getVideoAlbumOption($user['id'], 'user') as $row){
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
	}
	else if($_GET["q"] == "edit_videoalbum"){
	
		$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
	
		if(Videoalbum::checkExistence($id_album) or !Videoalbum::checkOwner($id_album, $user['id'])){
			header("HTTP/1.1 404 Not Found");
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;
		}
	 
		$album = Videoalbum::getAlbumInfo($id_album, $user['id']);
	
		$tpl->assign('QUERY', $_GET["q"]);	
		$name = $_POST['NAME'] ? $_POST['NAME'] : $album['name'];	
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'edit_videoalbum'));	
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);	
		$tpl->assign('NAME', $name);	
		$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));		
		$tpl->assign('BUTTON', core::getLanguage('button', 'edit'));	
	}
	else{
	
		$user_id = $_GET['user_id'] ? core::database()->escape((int)Core_Array::getRequest('user_id')) : $user['id'];
		$tpl->assign('ID_OWNER', $user_id);
		$tpl->assign('VIDEOALBUMABLE_TYPE', 'user');				
		
		if(!$_GET['user_id'] or $_GET['user_id'] == $user['id']) $tpl->assign('SHOW_ADD_VIDEO_MENU', 'show');	
		
		include_once "user_profile_info.inc";	
	
		if($_GET['id_album']){
			$tpl->assign('ID_ALBUMS', $_GET['id_album']);
			$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
			$id_album = (int)$id_album;
			
			if(Videoalbum::checkExistence($id_album) or !is_numeric($_GET['id_album'])){
				header("HTTP/1.1 404 Not Found");
				header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
				exit;
			}			
		
			$album = Videoalbum::getAlbumInfo($id_album, $user['id']);
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

				if($row['owner_id'] == $user['id']) $rowBlock->assign('ALLOW_EDIT', 'show');

				$rowBlock->assign('STR_REMOVE_VIDEO', core::getLanguage('str', 'remove_video'));			
				$tpl->assign('row_videos_list', $rowBlock);
			}
		}
		else{
		
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'videoalbums'));			
			$tpl->assign('PATH_VIDEO', $path_video);
			$tpl->assign('STR_ADD_NEW_VIDEO', core::getLanguage('str', 'add_new_video'));
			$tpl->assign('STR_CREATE_NEW_ALBUM', core::getLanguage('str', 'create_new_album'));
			$tpl->assign('STR_OR', core::getLanguage('str', 'or'));	
			$tpl->assign('STR_SHOW_MORE', core::getLanguage('str', 'show_more'));		
			$tpl->assign('STR_MY_ALBUMS', $_GET['user_id'] ? core::getLanguage('str', 'user_albums') : core::getLanguage('str', 'my_albums'));
			$tpl->assign('STR_POPULAR_VIDEOS',  core::getLanguage('str', 'popular_videos'));
			$tpl->assign('STR_MY_VIDEOS', $_GET['user_id'] ? core::getLanguage('str', 'user_videos') : core::getLanguage('str', 'my_videos'));		
			$tpl->assign('NUMBER_ALBUMS', Videoalbum::NumberAlbums($user_id, 'user'));
			$tpl->assign('NUMBER_POPULAR_VIDEOS', Videoalbum::getNumberPopVideos('user'));		
			$tpl->assign('NUMBER_MY_VIDEOS', Videoalbum::NumberVideos($user_id, 'user'));		
		
			if(!$_GET['user_id'] or $_GET['user_id'] == $user['id']) {
		
				$arr_pop_videos = Videoalbum::getPopularVideos('user', 6, 0);		
		
				if($arr_pop_videos){
					foreach($arr_pop_videos as $row){
						$rowBlock = $tpl->fetch('row_pop_videos_list');
						$rowBlock->assign('ID', $row['id']);
						$rowBlock->assign('ID_VIDEO', $row['video_id']);
						$rowBlock->assign('THUMB', core::documentparser()->getThumb($row['provider'], $row['video']));	
						$rowBlock->assign('VIDEO', core::documentparser()->getVideoPlayer($row['provider'], $row['video']));					
						$rowBlock->assign('NUMBERVIEWS', Videoalbum::getNumberVideoViews($row['video_id']));	
						$rowBlock->assign('DESCRIPTION', $row['description']);
						$tpl->assign('row_pop_videos_list', $rowBlock);
					}
				}else $tpl->assign('NO_POP_VIDEOS', core::getLanguage('str', 'empty'));		
			}else $tpl->assign('NO_POP_VIDEOS', core::getLanguage('str', 'empty'));			
		
			$arr_albums = Videoalbum::getAlbumList($user_id, 'user');
		
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
				
					if(Videoalbum::checkOwner($row['id'], $user['id'])) $rowBlock->assign('SHOW_EDIT_LINKS', 'show');					
					if($_GET['user_id']) $rowBlock->assign('PROFILE_USER_ID', $_GET['user_id']);				
				
					$tpl->assign('row_my_videoalbum_list', $rowBlock);			
				}
			}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
		
			$arr_my_videos = Videoalbum::getVideosList($user_id, 'user', 6, 0);
		
			if($arr_my_videos){
				foreach($arr_my_videos as $row){
					$rowBlock = $tpl->fetch('row_my_videos_list');					
					$rowBlock->assign('ID', $row['id']);
					$rowBlock->assign('ID_VIDEO', $row['video_id']);					
					$rowBlock->assign('DESCRIPTION', $arow['description']);
					$rowBlock->assign('THUMB', core::documentparser()->getThumb($row['provider'], $row['video']));
					$rowBlock->assign('VIDEO', core::documentparser()->getVideoPlayer($row['provider'], $row['video']));					
					$rowBlock->assign('NUMBERVIEWS', Videoalbum::getNumberVideoViews($row['video_id']));
				
					if($row['owner_id'] == $user['id']) $rowBlock->assign('ALLOW_EDIT', 'show');
				
					$rowBlock->assign('STR_REMOVE_VIDEO', core::getLanguage('str', 'remove_video'));				
					$tpl->assign('row_my_videos_list', $rowBlock);
				}	
			}	
			else $tpl->assign('NO_MY_VIDEOS', core::getLanguage('str', 'empty'));		
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
	
	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";	
	
	$user_id = core::database()->escape((int)Core_Array::getRequest('user_id'));
	$tpl->assign('ID_OWNER', $user_id);
	$tpl->assign('VIDEOALBUMABLE_TYPE', 'user');
		
	include_once "user_profile_info.inc";	
	
	$path_video = './?task=videoalbums';	
	$path_remove_video = './?task=videoalbums&q=videoalbums&action=remove';	
	$path_edit_video = './?task=videoalbums&q=edit_videoalbum';	
	
	if($_GET['id_album']){
		$tpl->assign('ID_ALBUMS', $_GET['id_album']);
		$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
		$album = Videoalbum::getAlbumInfo($id_album, $user['id']);
		
		$tpl->assign('TITLE_PAGE', $album['name']);		
		$tpl->assign('PATH_VIDEO', $path_video);		

		foreach(Videoalbum::getVideosAlbumList($id_album, 6, 0) as $row){
			$rowBlock = $tpl->fetch('row_videos_list');
			$rowBlock->assign('ID', $row['id']);					
			$rowBlock->assign('DESCRIPTION', $row['description']);
			$rowBlock->assign('VIDEO', core::documentparser()->getVideoPlayer($row['provider'], $row['video']));
			$rowBlock->assign('THUMB', core::documentparser()->getThumb($row['provider'], $row['video']));			
			$rowBlock->assign('NUMBERVIEWS', Videoalbum::getNumberVideoViews($row['id']));
			$tpl->assign('row_videos_list', $rowBlock);
		}
	}
	else{
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'videoalbums'));			
		
		$tpl->assign('PATH_VIDEO', $path_video);
		$tpl->assign('STR_OR', core::getLanguage('str', 'or'));	
		$tpl->assign('STR_SHOW_MORE', core::getLanguage('str', 'show_more'));		
		$tpl->assign('STR_MY_ALBUMS', $_GET['user_id'] ? core::getLanguage('str', 'user_albums') : core::getLanguage('str', 'my_albums'));
		$tpl->assign('STR_MY_VIDEOS', $_GET['user_id'] ? core::getLanguage('str', 'user_videos') : core::getLanguage('str', 'my_videos'));		
		$tpl->assign('NUMBER_ALBUMS', Videoalbum::NumberAlbums($user_id, 'user'));
		$tpl->assign('NUMBER_MY_VIDEOS', Videoalbum::NumberVideos($user_id, 'user'));			
		$tpl->assign('NO_POP_VIDEOS', core::getLanguage('str', 'empty'));			
		
		$arr_albums = Videoalbum::getAlbumList($user_id, 'user');
		
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
				
				if($_GET['user_id']) $rowBlock->assign('PROFILE_USER_ID', $_GET['user_id']);
				
				$tpl->assign('row_my_videoalbum_list', $rowBlock);			
			}
		}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
		
		$arr_my_videos = Videoalbum::getVideosList($user_id, 'user', 6, 0);
		
		if($arr_my_videos){
				foreach($arr_my_videos as $row){
					$rowBlock = $tpl->fetch('row_my_videos_list');					
					$rowBlock->assign('ID', $row['id']);
					$rowBlock->assign('ID_VIDEO', $row['video_id']);					
					$rowBlock->assign('DESCRIPTION', $arow['description']);
					$rowBlock->assign('THUMB', core::documentparser()->getThumb($row['provider'], $row['video']));
					$rowBlock->assign('VIDEO', core::documentparser()->getVideoPlayer($row['provider'], $row['video']));					
					$rowBlock->assign('NUMBERVIEWS', Videoalbum::getNumberVideoViews($row['video_id']));
				
					if($row['owner_id'] == $user['id']) $rowBlock->assign('ALLOW_EDIT', 'show');
				
					$rowBlock->assign('STR_REMOVE_VIDEO', core::getLanguage('str', 'remove_video'));				
					$tpl->assign('row_my_videos_list', $rowBlock);
				}	
		}	
		else $tpl->assign('NO_MY_VIDEOS', core::getLanguage('str', 'empty'));		
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