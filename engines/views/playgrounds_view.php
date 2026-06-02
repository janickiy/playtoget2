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
	
	$tpl->assign('STR_YOUR_BROWSER_DOESNT_SUPPORT', core::getLanguage('str', 'your_browser_doesnt_support'));	
	$tpl->assign('BUTTON_ADD_FILES', core::getLanguage('button', 'add_files'));	
	$tpl->assign('BUTTON_DOWNLOAD_FILES', core::getLanguage('button', 'download_files'));	

	switch ($_REQUEST['action'])
	{
		case 'create':
	
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
			$about = htmlspecialchars(trim(Core_Array::getRequest('about')));		
			$address = htmlspecialchars(trim(Core_Array::getRequest('address')));		
			$phone = htmlspecialchars(trim(Core_Array::getRequest('phone')));		
			$email = htmlspecialchars(trim(Core_Array::getRequest('email')));
			$file_ava = Core_Array::getRequest('file_ava');
			$website = htmlspecialchars(trim(Core_Array::getRequest('website')));		
			$id_place = core::database()->escape(Core_Array::getRequest('id_place'));
			$id_sport_block = core::database()->escape(Core_Array::getRequest('id_sport_block'));
	
			if(empty($name)) $error = core::getLanguage('error', 'not_all_fields_are_filled');
			if(empty($address)) $error = core::getLanguage('error', 'not_all_fields_are_filled');
		
			if(empty($error)){
				$fields = array();
				$fields['id'] = 0;			
				$fields['name'] = $name;			
				$fields['about'] = $about;
			
				if(is_numeric($id_place)){
					$city = Places::getCityInfo($id_place);
			
					if($city['name_ru']) $fields['place'] = $city['name_ru'];			
				}
			
				$fields['address'] = $address;			
				$fields['phone'] = $phone;	
				$fields['email'] = $email;
				$fields['avatar'] = basename($file_ava);
				$fields['website'] = $website;
				$fields['type'] = 'playground';			 
				$fields['owner_id'] = $user['id'];			
				$fields['created_at'] = date("Y-m-d H:i:s");
			
				$id_sport_block = SportBlocks::createSportBlock($fields);
			
				if($id_sport_block){
				
					if(Photoalbum::getNumberAlbums($id_sport_block, 'playground') == 0){
						$fields = array();
						$fields['id'] = 0;
						$fields['name'] = $name;
						$fields['created_at'] = date("Y-m-d H:i:s");
						$fields['photoalbumable_type'] = 'playground';
						$fields['owner_id'] = $id_sport_block;		
			
						Photoalbum::createAlbum($fields);	
					}				
				
					header('Location: http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_sport_block=' . $id_sport_block);		
					exit();
				}		
				else
					$error_msg = core::getLanguage('error', 'web_apps_error');
			}		
	
		break;
	
		case 'edit':
	
			$name = htmlspecialchars(trim(Core_Array::getRequest('name')));
			$about = htmlspecialchars(trim(Core_Array::getRequest('about')));		
			$address = htmlspecialchars(trim(Core_Array::getRequest('address')));		
			$phone = htmlspecialchars(trim(Core_Array::getRequest('phone')));		
			$email = htmlspecialchars(trim(Core_Array::getRequest('email')));
			$file_ava = Core_Array::getRequest('file_ava');
			$website = htmlspecialchars(trim(Core_Array::getRequest('website')));		
			$id_place = core::database()->escape(Core_Array::getRequest('id_place'));
			$id_sport_block = core::database()->escape(Core_Array::getRequest('id_sport_block'));
	
			if(empty($name)) $error = core::getLanguage('error', 'not_all_fields_are_filled');
			if(empty($address)) $error = core::getLanguage('error', 'not_all_fields_are_filled');	
	
			if(empty($error) && SportBlocks::checkOwner($id_sport_block, $user['id'])){
				$fields = array();
				$fields['name'] = $name;
				$fields['about'] = $about;
		
				if(is_numeric($id_place)){
					$city = Places::getCityInfo($id_place);
			
					if($city['name_ru']) $fields['place'] = $city['name_ru'];			
				}
		
				$fields['address'] = $address;
				$fields['phone'] = $phone;	
				$fields['email'] = $email;	
				$fields['avatar'] = basename($file_ava);
				$fields['website'] = $website;		
				$fields['updated_at'] = date("Y-m-d H:i:s");
			
				$result = SportBlocks::editSportBlock($fields, $id_sport_block);	
			
				if($result){
					header('Location: http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_sport_block=' . $id_sport_block);		
					exit();
				}		
				else
					$error_msg = core::getLanguage('error', 'web_apps_error');
			}
			else $error_msg = $error;
	
		break;	
	}

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

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

	$tpl->assign('AVATAR', $profile_avatar);
	$tpl->assign('FIRSTNAME', $user['firstname']);
	$tpl->assign('LASTNAME', $user['lastname']);
	$tpl->assign('ABOUT', $user['about']);
	$tpl->assign('PROFILE_COVER_PAGE', core::documentparser()->coverPage($user));

	if($_GET['id_sport_block']){	
		$tpl->assign('ID_SPORT_BLOCK', $_GET['id_sport_block']);		
		$id_sport_block = core::database()->escape((int)Core_Array::getRequest('id_sport_block'));	

		if(SportBlocks::checkExistence($id_sport_block, 'playground') or !is_numeric($_GET['id_sport_block'])){
			header("HTTP/1.1 404 Not Found");
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;
		}	
	
		$sport_block = SportBlocks::getSportBlocksInfo($id_sport_block);	
		
		if($sport_block['banned'] == 1) $tpl->assign('BLOCK_PAGE', 'yes');		
		
		$tpl->assign('STR_PAGE_LOCKED', core::getLanguage('str', 'page_locked'));
	
		if(Photoalbum::getNumberAlbums($id_sport_block, 'playground') == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['name'] = $sport_block['name'];
			$fields['created_at'] = date("Y-m-d H:i:s");
			$fields['photoalbumable_type'] = 'playground';
			$fields['owner_id'] = $id_sport_block;		
			
			Photoalbum::createAlbum($fields);	
		}
	
		$tpl->assign('INFO_SPORT_BLOCK_NAME', $sport_block['name']);	
		$tpl->assign('INFO_SPORT_BLOCK_ABOUT', nl2br($sport_block['about']));
		$tpl->assign('INFO_SPORT_BLOCK_PLACE', $sport_block['place']);
		$tpl->assign('INFO_SPORT_BLOCK_ADDRESS', $sport_block['address']);
		$tpl->assign('INFO_SPORT_BLOCK_PHONE', $sport_block['phone']);
		$tpl->assign('INFO_SPORT_BLOCK_EMAIL', $sport_block['email']);
		$tpl->assign('INFO_SPORT_BLOCK_WEBSITE', $sport_block['website']);
		$tpl->assign('SPORT_BLOCK_AVATAR', core::documentparser()->sportblockAvatar($sport_block['avatar']));
		$tpl->assign('SPORT_BLOCK_TYPE', 'playgrounds');
		$tpl->assign('ID_OWNER', $sport_block['owner_id']);
		$tpl->assign('ID_SPORT_BLOCK_PHOTO_ALBUM', SportBlocks::getSportBlockIdPhotoAlbum($id_sport_block, 'playground'));
	
		if(Photoalbum::getNumberAlbums($id_sport_block, 'playground') > 0){
	
			$arr_photos = Photoalbum::getAllPicList(SportBlocks::getSportBlockIdPhotoAlbum($id_sport_block, 'playground'));
	
			if($arr_photos){
				foreach($arr_photos as $row){
					$rowBlock = $tpl->fetch('row_small_images_list');
					$rowBlock->assign('ID_PHOTO', $row['photo_id']);
					$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], $row['photoalbumable_type']));	
					$tpl->assign('row_small_images_list', $rowBlock);	
				}
			
				foreach($arr_photos as $row){
					$rowBlock = $tpl->fetch('row_big_images_list');
					$rowBlock->assign('ID_PHOTO', $row['photo_id']);
					$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], $row['photoalbumable_type']));
					$tpl->assign('row_big_images_list', $rowBlock);	
				}			
			
				foreach($arr_photos as $row){
					$rowBlock = $tpl->fetch('row_photos_list');
					$rowBlock->assign('ID_PHOTO', $row['photo_id']);
					$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], $row['photoalbumable_type']));	
					$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], $row['photoalbumable_type']));
					$rowBlock->assign('DESCRIPTION', $row['description']);
					$rowBlock->assign('STR_REMOVE_PHOTO', core::getLanguage('str', 'remove_photo'));						
					$tpl->assign('row_photos_list', $rowBlock);	
				}
			}			
			else $tpl->assign('NO_IMAGES', core::getLanguage('str', 'empty'));
		}	
	
		switch ($_GET['q']) {
		
			case 'edit':
		
				if(!SportBlocks::checkOwner($id_sport_block, $user['id'])){
					header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
				}	
		
				$tpl->assign('QUERY', $_GET['q']);

				$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);	
				$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'edit_playground'));
				$tpl->assign('FORM_ACTION', 'edit');
				$tpl->assign('STR_NAME', core::getLanguage('str', 'playground'));		
				$tpl->assign('STR_DESCRIPTION', core::getLanguage('str', 'description'));		
				$tpl->assign('STR_CITY', core::getLanguage('str', 'city'));		
				$tpl->assign('STR_ADDRESS', core::getLanguage('str', 'address'));		
				$tpl->assign('STR_PHONE', core::getLanguage('str', 'phone'));		
				$tpl->assign('STR_EMAIL', core::getLanguage('str', 'email_address'));		
				$tpl->assign('STR_WEBSITE', core::getLanguage('str', 'website'));		
				$tpl->assign('BUTTON_EDIT_PHOTO', core::getLanguage('button', 'edit_photo'));			
				$tpl->assign('BUTTON', core::getLanguage('button', 'save_changes'));
			
				$tpl->assign('SPORT_BLOCK_NAME', $_POST['name'] ? $_POST['name'] : $sport_block['name']);			
				$tpl->assign('SPORT_BLOCK_ADDRESS', $_POST['address'] ? $_POST['address'] : $sport_block['address']);	
				$tpl->assign('SPORT_BLOCK_ID_PLACE', $_POST['id_place']);			
				$tpl->assign('SPORT_BLOCK_PLACE', $_POST['place'] ? $_POST['place'] : $sport_block['place']);			
				$tpl->assign('SPORT_BLOCK_ABOUT', $_POST['about'] ? $_POST['about'] : $sport_block['about']);			
				$tpl->assign('SPORT_BLOCK_PHONE', $_POST['phone'] ? $_POST['phone'] : $sport_block['phone']);			
				$tpl->assign('SPORT_BLOCK_EMAIL', $_POST['email'] ? $_POST['email'] : $sport_block['email']);				
				$tpl->assign('SPORT_BLOCK_WEBSITE', $_POST['email'] ? $_POST['website'] : $sport_block['website']);			
		
			break;
		
			default:
		
				$tpl->assign('TITLE_PAGE', $sport_block['name']);
		}	
	}
	else{

		if($_GET['q'] == 'create'){
			$tpl->assign('QUERY', $_GET['q']);		
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'create_playground'));			
			$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);		
			$tpl->assign('FORM_ACTION', 'create');
			$tpl->assign('STR_NAME', core::getLanguage('str', 'playground'));		
			$tpl->assign('STR_DESCRIPTION', core::getLanguage('str', 'description'));		
			$tpl->assign('STR_CITY', core::getLanguage('str', 'city'));		
			$tpl->assign('STR_ADDRESS', core::getLanguage('str', 'address'));		
			$tpl->assign('STR_PHONE', core::getLanguage('str', 'phone'));		
			$tpl->assign('STR_EMAIL', core::getLanguage('str', 'email_address'));		
			$tpl->assign('STR_WEBSITE', core::getLanguage('str', 'website'));		
			$tpl->assign('SPORT_BLOCK_AVATAR', 'templates/images/noimage.png');		
			$tpl->assign('BUTTON_EDIT_PHOTO', core::getLanguage('button', 'edit_photo'));		
			$tpl->assign('SPORT_BLOCK_NAME', $_POST['name']);		
			$tpl->assign('SPORT_BLOCK_ABOUT', $_POST['about']);		
			$tpl->assign('SPORT_BLOCK_PLACE', $_POST['place']);		
			$tpl->assign('SPORT_BLOCK_ADDRESS', $_POST['address']);		
			$tpl->assign('SPORT_BLOCK_MAP', $_POST['map']);		
			$tpl->assign('SPORT_BLOCK_PHONE', $_POST['phone']);		
			$tpl->assign('SPORT_BLOCK_EMAIL', $_POST['email']);		
			$tpl->assign('SPORT_BLOCK_WEBSITE', $_POST['website']);		
			$tpl->assign('BUTTON', core::getLanguage('button', 'create_playground'));
		}
		else{		
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'playgrounds'));
			$tpl->assign('SPORT_BLOCKS_TYPE', 'playground');		
			$tpl->assign('CREATE_SPORT_BLOCKS', './?task=playgrounds&q=create');			
			$tpl->assign('STR_SEARCH_SPORT_BLOCKS_IN_CITY', core::getLanguage('str', 'looking_for_playground_in_city'));		
			$tpl->assign('STR_KEYWORD', core::getLanguage('str', 'search_playground_by_name'));		
			$tpl->assign('BUTTON_CREATE_SPORT_BLOCKS', core::getLanguage('button', 'create_playground'));	

			$arr_sportblocks = SportBlocks::getSportBlocks('playground', 10, 0);
	
			if($arr_sportblocks){
				foreach($arr_sportblocks as $row){
					$rowBlock = $tpl->fetch('row_my_id_sport_block');	
					$rowBlock->assign('ID', $row['id']);		
					$rowBlock->assign('NAME', $row['name']);				
					$rowBlock->assign('PLACE', $row['place']);				
					$rowBlock->assign('ABOUT', $row['about']);				
					$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				
					if($row['owner_id'] == $user['id']) $rowBlock->assign('SHOW_EDTI_LINK', 'show');
				
					$rowBlock->assign('AVATAR', core::documentparser()->sportblockAvatar($row['avatar']));				
					$tpl->assign('row_my_id_sport_block', $rowBlock);
				}
			}
		}
	}

	include_once "footer.inc";
		
	$tpl->display();	
}
else {
	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");
	
	$tpl->assign('OPEN_PAGE', 'yes');
	
	if(empty($_GET['id_sport_block'])){
		header("Location: http://" . $_SERVER['SERVER_NAME']);
		exit;
	}		
	
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

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));
		
	$tpl->assign('ID_SPORT_BLOCK', $_GET['id_sport_block']);		
	$id_sport_block = core::database()->escape((int)Core_Array::getRequest('id_sport_block'));	

	if(SportBlocks::checkExistence($id_sport_block, 'playground') or !is_numeric($_GET['id_sport_block'])){
		header("HTTP/1.1 404 Not Found");
		header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
		exit;
	}	
	
	$sport_block = SportBlocks::getSportBlocksInfo($id_sport_block);	
		
	if($sport_block['banned'] == 1) $tpl->assign('BLOCK_PAGE', 'yes');		
		
	$tpl->assign('STR_PAGE_LOCKED', core::getLanguage('str', 'page_locked'));
	
	if(Photoalbum::getNumberAlbums($id_sport_block, 'playground') == 0){
		$fields = array();
		$fields['id'] = 0;
		$fields['name'] = $sport_block['name'];
		$fields['created_at'] = date("Y-m-d H:i:s");
		$fields['photoalbumable_type'] = 'playground';
		$fields['owner_id'] = $id_sport_block;		
			
		Photoalbum::createAlbum($fields);	
	}
	
	$tpl->assign('INFO_SPORT_BLOCK_NAME', $sport_block['name']);	
	$tpl->assign('INFO_SPORT_BLOCK_ABOUT', nl2br($sport_block['about']));
	$tpl->assign('DESCRIPTION', $sport_block['about']);
	$tpl->assign('INFO_SPORT_BLOCK_PLACE', $sport_block['place']);
	$tpl->assign('INFO_SPORT_BLOCK_ADDRESS', $sport_block['address']);
	$tpl->assign('INFO_SPORT_BLOCK_PHONE', $sport_block['phone']);
	$tpl->assign('INFO_SPORT_BLOCK_EMAIL', $sport_block['email']);
	$tpl->assign('INFO_SPORT_BLOCK_WEBSITE', $sport_block['website']);
	$tpl->assign('SPORT_BLOCK_AVATAR', core::documentparser()->sportblockAvatar($sport_block['avatar']));
	$tpl->assign('SPORT_BLOCK_TYPE', 'playgrounds');
	$tpl->assign('ID_OWNER', $sport_block['owner_id']);
	$tpl->assign('ID_SPORT_BLOCK_PHOTO_ALBUM', SportBlocks::getSportBlockIdPhotoAlbum($id_sport_block, 'playground'));
	
	if(Photoalbum::getNumberAlbums($id_sport_block, 'playground') > 0){
		$arr_photos = Photoalbum::getAllPicList(SportBlocks::getSportBlockIdPhotoAlbum($id_sport_block, 'playground'));
	
		if($arr_photos){
			foreach($arr_photos as $row){
				$rowBlock = $tpl->fetch('row_small_images_list');
				$rowBlock->assign('ID_PHOTO', $row['photo_id']);
				$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], $row['photoalbumable_type']));	
				$tpl->assign('row_small_images_list', $rowBlock);	
			}
			
			foreach($arr_photos as $row){
				$rowBlock = $tpl->fetch('row_big_images_list');
				$rowBlock->assign('ID_PHOTO', $row['photo_id']);
				$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], $row['photoalbumable_type']));
				$tpl->assign('row_big_images_list', $rowBlock);	
			}			
			
			foreach($arr_photos as $row){
				$rowBlock = $tpl->fetch('row_photos_list');
				$rowBlock->assign('ID_PHOTO', $row['photo_id']);
				$rowBlock->assign('SMALL_IMAGE', core::documentparser()->photogalleryPic($row['small_photo'], $row['photoalbumable_type']));	
				$rowBlock->assign('BIG_IMAGE', core::documentparser()->photogalleryPic($row['photo'], $row['photoalbumable_type']));
				$rowBlock->assign('DESCRIPTION', $row['description']);
				$rowBlock->assign('STR_REMOVE_PHOTO', core::getLanguage('str', 'remove_photo'));						
				$tpl->assign('row_photos_list', $rowBlock);	
			}
		}			
		else $tpl->assign('NO_IMAGES', core::getLanguage('str', 'empty'));
	}	
		
	$tpl->assign('TITLE_PAGE', $sport_block['name']);			
	
	include_once "footer.inc";
	
	$tpl->display();
}
