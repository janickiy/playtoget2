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
		case 'create_photoalbum':	
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
			
			if(empty($name)) $error_msg = core::getLanguage('error', 'empty_album_name');
			if(!empty($name) && Photoalbum::checkNameExists($name, $user['id'], 'user')) $error_msg = core::getLanguage('error', 'album_name_exists');
	
			if(!$error_msg){
				$fields = array();
		
				$fields['id'] = 0;		
				$fields['name'] = $name;	
				$fields['created_at'] = date("Y-m-d H:i:s");	
				$fields['photoalbumable_type'] = 'user';
				$fields['owner_id'] = $user['id'];
			
				$result = Photoalbum::createAlbum($fields);	

				if($result)	{
					header("Location: ./?task=photoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}
			}	
	
		break;
	
		case 'remove':
	
			$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
	
			if(Photoalbum::checkOwner($id_album, $user['id'])){
	
				$result = Photoalbum::removeAlbum($id_album);	
	
				if($result)	{
					header("Location: ./?task=photoalbums");
					exit;
				}
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;
	
		case 'edit_photoalbum':
	
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
			$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
		
			if(empty($name)) $error_msg = core::getLanguage('error', 'empty_album_name');
		
			if(!$error_msg && Photoalbum::checkOwner($id_album, $user['id'])){
				$fields = array();
				$fields['name'] = $name;
				$fields['updated_at'] = date("Y-m-d H:i:s");
		
				$result = Photoalbum::editAlbum($fields, $id_album);
		
				if($result){
					header("Location: ./?task=photoalbums");
					exit;
				}
				else{
					$error_msg = core::getLanguage('error', 'web_apps_error');
				}
			}
			else $error_msg = core::getLanguage('error', 'web_apps_error');
	
		break;
	}

	if(!empty($error_msg)){
		$tpl->assign('MSG_ERROR_ALERT', $error_msg);
	}

	if(!empty($success_msg)) $tpl->assign('MSG_ALERT', $success_msg);

	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";

	$tpl->assign('STR_HIDE', core::getLanguage('str', 'hide'));
	$tpl->assign('STR_SHOW', core::getLanguage('str', 'show'));	
	$tpl->assign('STR_THERE_ARE_NO_MORE_ENTRIES', core::getLanguage('str', 'there_are_no_more_entries'));
	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));
	$tpl->assign('STR_REPLY', core::getLanguage('str', 'reply'));
	$tpl->assign('PHOTOALBUMABLE_TYPE', 'user');

	$photoalbum_remove_path = './?task=photoalbums&action=remove';
	$photoalbum_edit_path = './?task=photoalbums&q=edit_photoalbum';
	$photoalbum_path = './?task=photoalbums';
	$redirect_photo_album = './?task=photoalbums';

	$tpl->assign('STR_ALL_PHOTOS', core::getLanguage('str', 'all_photos'));

	if($_GET["q"] == "create_photoalbum"){	
		$tpl->assign('QUERY', $_GET["q"]);
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'create_photoalbum'));
	
		if(!empty($error_msg)) {
			$tpl->assign('STR_ERROR', core::getLanguage('str', 'error'));
			$tpl->assign('ERROR_ALERT', $error_msg);
		}
	
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
		$tpl->assign('NAME', $_POST['name']);	
		$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));
		$tpl->assign('BUTTON', core::getLanguage('button', 'create'));		
	}
	else if($_GET["q"] == "add_photo"){
		$tpl->assign('QUERY', $_GET["q"]);	
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'add_photos'));	
	
		$tpl->assign('REDIRECT_PHOTO_ALBUM', $redirect_photo_album);
	
		if(!empty($error_msg)) {
			$tpl->assign('STR_ERROR', core::getLanguage('str', 'error'));
			$tpl->assign('ERROR_ALERT', $error_msg);
		}
	
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);	
	
		if(Photoalbum::getNumberAlbums($user['id'], 'user') == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['name'] = core::getLanguage('str', 'my_album');
			$fields['created_at'] = date("Y-m-d H:i:s");
			$fields['photoalbumable_type'] = 'user';
			$fields['owner_id'] = $user['id'];		
			
			Photoalbum::createAlbum($fields);	
		}	
	
		$arr_option_list = Photoalbum::getAlbumsOptionList($user['id'], 'user');	
	
		if($arr_option_list){
			$tpl->assign('SHOW_CATEGORY_LIST', 'show');
		
			foreach($arr_option_list as $row){
				$rowBlock = $tpl->fetch('row_option_album');
				$rowBlock->assign('ID', $row['id']);	
				$rowBlock->assign('NAME', $row['name']);	
				$tpl->assign('row_option_album', $rowBlock);			
			}		
		}		
	}
	else if($_GET["q"] == "edit_photoalbum"){
		$tpl->assign('QUERY', $_GET["q"]);	
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'edit_photoalbums'));
	
		if(!empty($error_msg)) {
			$tpl->assign('STR_ERROR', core::getLanguage('str', 'error'));
			$tpl->assign('ERROR_ALERT', $error_msg);
		}
	
		$row = Photoalbum::getPhotoAlbumInfo((int)Core_Array::getRequest('id_album'));	
		$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
		$tpl->assign('NAME', $row['name']);
		$tpl->assign('BUTTON', core::getLanguage('button', 'edit'));	
	}
	else{	
		$user_id = $_GET['user_id'] ? core::database()->escape((int)Core_Array::getRequest('user_id')) : $user['id'];
		$tpl->assign('ID_OWNER', $user_id);
	
		if($_GET['user_id'] == $user['id'] or !$_GET['user_id']){ 
			$tpl->assign('SHOW_ADD_PHOTOS_MENU', 'show');
		}		
		
		include_once "user_profile_info.inc";	
	
		if($_GET['id_album']){			
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'photoalbums'));	
		
			$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
			$id_album = (int)$id_album;			
			
			if(Photoalbum::checkExistence($id_album) or !is_numeric($_GET['id_album'])){
				header("HTTP/1.1 404 Not Found");
				header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
				exit;
			}		
			
			$info = Photoalbum::getPhotoAlbumInfo($id_album);
			
			$tpl->assign('STR_EDIT', core::getLanguage('str', 'edit'));		
			$tpl->assign('STR_REMOVE', core::getLanguage('str', 'remove'));			
			$tpl->assign('PHOTOALBUM_NAME', $info['name']);				
			$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);			
			$tpl->assign('ID_ALBUM', $_GET['id_album']);	
			
			$arr_photos = Photoalbum::getPicList($id_album, 9, 0);

			if($arr_photos){
				foreach($arr_photos as $row){
					$rowBlock = $tpl->fetch('row_photos_list');
					$rowBlock->assign('ID_PHOTO', $row['id']);
					$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], $info['photoalbumable_type']));	
					$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], $info['photoalbumable_type']));
					$rowBlock->assign('DESCRIPTION', $row['description']);
				
					if($row['owner_id'] == $user['id']) $rowBlock->assign('ALLOW_EDIT', 'show');
					
					$rowBlock->assign('STR_REMOVE_PHOTO', core::getLanguage('str', 'remove_photo'));
					$tpl->assign('row_photos_list', $rowBlock);	
				}
			}			
			else $tpl->assign('STR_NO_IMAGE', core::getLanguage('str', 'empty'));				
		}
		else{		
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'photoalbums'));			
			$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);			
			$tpl->assign('STR_ADD_PHOTOS', core::getLanguage('str', 'add_photos'));		
			$tpl->assign('STR_CREATE_PHOTOALBUM', core::getLanguage('str', 'create_photoalbum'));
			$tpl->assign('STR_OR', core::getLanguage('str', 'or'));
			$tpl->assign('STR_POPULAR_PHOTOS', core::getLanguage('str', 'popular_photos'));			
			$tpl->assign('STR_SHOW_MORE', core::getLanguage('str', 'show_more'));		
			$tpl->assign('STR_MY_ALBUMS', $_GET['user_id'] ? core::getLanguage('str', 'user_albums') : core::getLanguage('str', 'my_albums'));	
			$tpl->assign('STR_MY_PHOTOS', $_GET['user_id'] ? core::getLanguage('str', 'user_photos') : core::getLanguage('str', 'my_photos'));
			$tpl->assign('NUMBER_POPULAR_PHOTOS', Photoalbum::NumberTotalPopPhotos($user_id, 'user'));		
			$tpl->assign('NUMBER_MY_ALBUMS', Photoalbum::getNumberAlbums($user_id, 'user')+Photoalbum::getNumberAlbums($user_id, 'user_attach'));		
			$tpl->assign('NUMBER_MY_PHOTOS', Photoalbum::NumberPhotos($user_id, 'user'));		
		
			if(!isset($_GET['user_id'])&&$_GET['task'] == 'photoalbums'){
				$arr_pop_photos = Photoalbum::getPopularPhotos('user',9, 0);
			
				if($arr_pop_photos){			
					foreach($arr_pop_photos as $row){
						$rowBlock = $tpl->fetch('row_pop_photos_list');
						$rowBlock->assign('ID', $row['id']);
						$rowBlock->assign('ID_PHOTO', $row['photo_id']);
						$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], 'user'));				
						$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], 'user'));
						$rowBlock->assign('DESCRIPTION', $row['description']);
						$tpl->assign('row_pop_photos_list', $rowBlock);				
					}			
				}
				else $tpl->assign('NO_POP_PHOTOS', core::getLanguage('str', 'empty'));	
			}else $tpl->assign('NO_POP_PHOTOS', core::getLanguage('str', 'empty'));		
		
			$arr_albums = Photoalbum::getAlbumList($user_id, 'user');
			$arr_albums = array_merge($arr_albums, Photoalbum::getAlbumList($user_id, 'user_attach'));
			
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
				
					if(Photoalbum::checkOwner($row['id'], $user['id'])) $rowBlock->assign('SHOW_EDIT_LINKS', 'show');
				
					$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));					
					$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));					

					$pic = Photoalbum::getMainImage($row['id']);
				
					if($_GET['user_id']) $rowBlock->assign('PROFILE_USER_ID', $_GET['user_id']);				

					if ($pic['small_photo'] && file_exists(core::documentparser()->photogalleryPic($pic['small_photo'], 'user'))){
						$image = core::documentparser()->photogalleryPic($pic['small_photo'], 'user');
					}
					elseif ($pic['small_photo'] && file_exists(core::documentparser()->photogalleryPic($pic['small_photo'], 'user_attach'))) {
						$image = core::documentparser()->photogalleryPic($pic['small_photo'], 'user_attach');
					}
					else{
						$image = 'templates/images/default_group.png';
					}
					
					$rowBlock->assign('IMAGE', $image);
				
					$tpl->assign('row_my_album_list', $rowBlock);			
				}
			}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
	
			$arr_photos = Photoalbum::getPhotosList($user_id, 'user', 6, 0);

			if($arr_photos){
				foreach($arr_photos as $row){
					$rowBlock = $tpl->fetch('row_my_photos_list');
					$rowBlock->assign('ID', $row['id']);
					$rowBlock->assign('ID_PHOTO', $row['photo_id']);
					$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], 'user'));				
					$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], 'user'));
					$rowBlock->assign('DESCRIPTION', $row['description']);					
				
					if($row['owner_id'] == $user['id']) $rowBlock->assign('ALLOW_EDIT', 'show');				

					$rowBlock->assign('STR_REMOVE_PHOTO', core::getLanguage('str', 'remove_photo'));				
					$tpl->assign('row_my_photos_list', $rowBlock);
				}			
			}
			else $tpl->assign('NO_PHOTOS', core::getLanguage('str', 'empty'));				
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
	
	$photoalbum_remove_path = './?task=photoalbums&action=remove';
	$photoalbum_edit_path = './?task=photoalbums&q=edit_photoalbum';
	$photoalbum_path = './?task=photoalbums';
	$redirect_photo_album = './?task=photoalbums';

	$tpl->assign('STR_ALL_PHOTOS', core::getLanguage('str', 'all_photos'));
	
	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";
	
	$user_id = core::database()->escape((int)Core_Array::getRequest('user_id'));
	$tpl->assign('ID_OWNER', $user_id);
	
	include_once "user_profile_info.inc";	
	
	if($_GET['id_album']){			
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'photoalbums'));	
		
		$id_album = core::database()->escape((int)Core_Array::getRequest('id_album'));
		$id_album = (int)$id_album;
		$info = Photoalbum::getPhotoAlbumInfo($id_album);
			
		$tpl->assign('PHOTOALBUM_NAME', $info['name']);				
		$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);			
		$tpl->assign('ID_ALBUM', $_GET['id_album']);	
			
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
		else $tpl->assign('STR_NO_IMAGE', core::getLanguage('str', 'empty'));				
	}
	else{		
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'photoalbums'));			
		$tpl->assign('PHOTOALBUM_PATH', $photoalbum_path);			
		$tpl->assign('STR_ADD_PHOTOS', core::getLanguage('str', 'add_photos'));		
		$tpl->assign('STR_CREATE_PHOTOALBUM', core::getLanguage('str', 'create_photoalbum'));
		$tpl->assign('STR_OR', core::getLanguage('str', 'or'));
		$tpl->assign('STR_POPULAR_PHOTOS', core::getLanguage('str', 'popular_photos'));			
		$tpl->assign('STR_SHOW_MORE', core::getLanguage('str', 'show_more'));		
		$tpl->assign('STR_MY_ALBUMS', $_GET['user_id'] ? core::getLanguage('str', 'user_albums') : core::getLanguage('str', 'my_albums'));	
		$tpl->assign('STR_MY_PHOTOS', $_GET['user_id'] ? core::getLanguage('str', 'user_photos') : core::getLanguage('str', 'my_photos'));
		$tpl->assign('NUMBER_POPULAR_PHOTOS', Photoalbum::NumberTotalPopPhotos($user_id, 'user'));		
		$tpl->assign('NUMBER_MY_ALBUMS', Photoalbum::getNumberAlbums($user_id, 'user')+Photoalbum::getNumberAlbums($user_id, 'user_attach'));		
		$tpl->assign('NUMBER_MY_PHOTOS', Photoalbum::NumberPhotos($user_id, 'user'));		
		
		if(!isset($_GET['user_id']) && $_GET['task'] == 'photoalbums'){
			$arr_pop_photos = Photoalbum::getPopularPhotos('user',9, 0);
			
			if($arr_pop_photos){			
				foreach($arr_pop_photos as $row){
					$rowBlock = $tpl->fetch('row_pop_photos_list');
					$rowBlock->assign('ID', $row['id']);
					$rowBlock->assign('ID_PHOTO', $row['photo_id']);
					$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], 'user'));				
					$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], 'user'));
					$rowBlock->assign('DESCRIPTION', $row['description']);
					$tpl->assign('row_pop_photos_list', $rowBlock);				
				}			
			}
			else $tpl->assign('NO_POP_PHOTOS', core::getLanguage('str', 'empty'));	
		}else $tpl->assign('NO_POP_PHOTOS', core::getLanguage('str', 'empty'));		
		
		$arr_albums = Photoalbum::getAlbumList($user_id, 'user');
		$arr_albums = array_merge($arr_albums, Photoalbum::getAlbumList($user_id, 'user_attach'));
			
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
				
				if($_GET['user_id']) $rowBlock->assign('PROFILE_USER_ID', $_GET['user_id']);				

				if ($pic['small_photo'] && file_exists(core::documentparser()->photogalleryPic($pic['small_photo'], 'user'))){
					$image = core::documentparser()->photogalleryPic($pic['small_photo'], 'user');
				}
				elseif ($pic['small_photo'] && file_exists(core::documentparser()->photogalleryPic($pic['small_photo'], 'user_attach'))) {
					$image = core::documentparser()->photogalleryPic($pic['small_photo'], 'user_attach');
				}
				else{
					$image = 'templates/images/default_group.png';
				}
					
				$rowBlock->assign('IMAGE', $image);
				
				$tpl->assign('row_my_album_list', $rowBlock);			
			}
		}else $tpl->assign('NO_ALBUMS', core::getLanguage('str', 'empty'));
	
		$arr_photos = Photoalbum::getPhotosList($user_id, 'user', 6, 0);

		if($arr_photos){
			foreach($arr_photos as $row){
				$rowBlock = $tpl->fetch('row_my_photos_list');
				$rowBlock->assign('ID', $row['id']);
				$rowBlock->assign('ID_PHOTO', $row['photo_id']);
				$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], 'user'));				
				$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], 'user'));
				$rowBlock->assign('DESCRIPTION', $row['description']);					
				$tpl->assign('row_my_photos_list', $rowBlock);
			}			
		}
		else $tpl->assign('NO_PHOTOS', core::getLanguage('str', 'empty'));				
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
