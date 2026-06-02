<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

if($_SESSION['user_authorization'] == "ok"){

	Auth::authorization();


	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

	core::user()->setUser_id($_SESSION['user_id']);
	$user = core::user()->getUserInfo();
	$settings = core::user()->getUserSetting();
	$user_id = core::database()->escape((int)Core_Array::getRequest('user_id'));

	core::user()->setUserActivity();
	
	if(core::user()->checkExistence(core::database()->escape(Core_Array::getRequest('user_id'))) or !is_numeric(Core_Array::getRequest('user_id'))){
		header("HTTP/1.1 404 Not Found");
		header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
		exit;	
	}

	if(isset($_GET['user_id']) && empty($_GET['user_id'])){
		header("HTTP/1.1 500 server error");
		header("Location: http://" . $_SERVER['SERVER_NAME'] . "/500.html"); 
		exit;
	}
	
	$tpl->assign('NUMBERMESSAGE', core::user()->MessageNotification());
	$tpl->assign('NUMBERINVITATION', core::user()->AddFriendsNotification());

	$css = array();
	$css[] = './templates/css/bootstrap-theme.min.css';
	$css[] = './templates/css/bootstrap.min.css';
	$css[] = './templates/css/style.css';
	$css[] = './templates/css/owl.carousel.css';
	$css[] = './templates/css/owl.theme.css';
	$css[] = './templates/css/owl.transitions.css';

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

	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";

	if($_GET['q'] == 'messages'){
		$tpl->assign('QUERY', $_GET['q']);
	
		$receiver_id = core::database()->escape((int)Core_Array::getRequest('sel'));
	
		if(core::user()->checkExistence($receiver_id)){
			header("HTTP/1.1 404 Not Found");
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;	
		}
	
		core::user()->setUser_id($receiver_id);
		$receiver = core::user()->getUserInfo();
		$receiver_settings = core::user()->getUserSetting();
	
		$data->markReadMsg($receiver_id, $user['id']);	
	
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'messages'));	
		$tpl->assign('TO_DIALOGUES_LIST', core::getLanguage('str', 'to_dialogues_list'));
		$tpl->assign('STR_DIALOG', core::getLanguage('str', 'dialog'));	
		$tpl->assign('SEL', $_GET['sel']);	
		$tpl->assign('STR_SEND_MESSAGE', core::getLanguage('str', 'send_message'));	
		$tpl->assign('STR_SEND', core::getLanguage('str', 'send'));	
		$tpl->assign('MY_AVATAR', core::documentparser()->userAvatar($user));
		$tpl->assign('RECEIVER_AVATAR', core::documentparser()->userAvatar($receiver));
		$tpl->assign('RECEIVER_FIRSNAME', $receiver['firstname']);
		$tpl->assign('RECEIVER_LASTNAME', $receiver['lastname']);
		$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
		$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
		$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
		$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
		$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
		$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));
	
		if($data->permissionSendMessage($user['id'], $receiver_id, $receiver_settings['permission_send_message'])) $tpl->assign("PERMISSION_MESSAGE", "yes");	
	
		$arr_messages = $data->getMessagesList($receiver_id, $user['id'], 10);

		if($arr_messages){

			foreach($arr_messages as $row){
				$rowBlock = $tpl->fetch('row_messages');
				$rowBlock->assign('ID', $row['id']);
				$rowBlock->assign('ID_USER', $row['user_id']);	
				$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));		
				$rowBlock->assign('FIRSTNAME', $row['firstname']);
				$rowBlock->assign('LASTNAME', $row['lastname']);		
				$rowBlock->assign('CREATED', $row['created']);		
				$rowBlock->assign('CONTENT', core::documentparser()->link_replace($row['content']));
		
					foreach(Attach::getAttachList($row['id'], 'message') as $row2){
						$rowAttach = $rowBlock->fetch('row_attach_message');
						$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
						$photo_src = Photoalbum::getPhotoSmallSrc($photo);
						if($photo_src){
							$rowAttach->assign('SMALL_PHOTO', $photo_src);
							$rowAttach->assign('ID_PHOTO', $photo['photo_id']);
							$rowBlock->assign('row_attach_message', $rowAttach);
						}
					}	
			
					foreach(Attach::getAttachList($row['id'], 'message') as $row2){
						$rowAttach = $rowBlock->fetch('row_attach_reply_message');
						$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
						$photo_src = Photoalbum::getPhotoSmallSrc($photo);
						if($photo_src){
							$rowAttach->assign('SMALL_PHOTO', $photo_src);
							$rowAttach->assign('ID_PHOTO', $photo['photo_id']);
							$rowBlock->assign('row_attach_reply_message', $rowAttach);
						}
					}
				
				$tpl->assign('row_messages', $rowBlock);
			}
		}
		else $tpl->assign('NO_MESSAGES', core::getLanguage('str', 'empty'));

		$tpl->assign('ID_RECEIVER', $profile['id']); 
		$tpl->assign('STR_THERE_ARE_NO_MORE_ENTRIES', core::getLanguage('str', 'there_are_no_more_entries'));
		$tpl->assign('STR_CLICK', core::getLanguage('str', 'click'));	
		$tpl->assign('ID_SENDER', $user['id']);	
	}
	else if($_GET['q'] == 'dialogues'){
		$tpl->assign('QUERY', $_GET['q']);	
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'messages'));	
		$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
		$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
		$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
		$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
		$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
		$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

		$arr_friends = Friends::getFriendsList($user['id'], 10, 0);

		if($arr_friends){
		
			foreach($arr_friends as $row){
				core::user()->setUser_id($row['user_id']);
		
				$rowBlock = $tpl->fetch('row_my_friends');
				$rowBlock->assign('ID_FRIEND', $row['user_id']);
				$rowBlock->assign('ID_USER', $user['id']);
				$rowBlock->assign('SEL', $user['id']);
				$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));
				$rowBlock->assign('FIRSTNAME', $row['firstname']);					
				$rowBlock->assign('LASTNAME', $row['lastname']);
				$rowBlock->assign('CITY', $row['city']);	
				$rowBlock->assign('STR_SEND_MESSAGE', core::getLanguage('str', 'send_message'));	
				$rowBlock->assign('STR_VIEW_FRIENDS', core::getLanguage('str', 'view_riends'));	
				$rowBlock->assign('STR_REMOVE_FRIEND', core::getLanguage('str', 'add_as_friend'));	
				$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline');
		
				$tpl->assign('row_my_friends', $rowBlock);
			}
		}else $tpl->assign('NO_FRIENDS', core::getLanguage('str', 'empty'));	

		$arr_dialogues = $data->getDialogues($user['id']);
		
		if($arr_dialogues){
			foreach($arr_dialogues as $row){
				$message = $data->getLastMessage($row['receiver_id'], $row['sender_id']);
	
				$rowBlock = $tpl->fetch('row_dialogues');	
				$rowBlock->assign('ID_USER', $user['id']);
				$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));	
				$rowBlock->assign('LAST_MSG_ID_USER', $row['id']);
				$rowBlock->assign('LAST_MSG_AVATAR', core::documentparser()->userAvatar($message[0]));
				$rowBlock->assign('DIALOGUE_ID_USER',$message[0]['user_id']);		
				$rowBlock->assign('FIRSTNAME', $row['firstname']);
				$rowBlock->assign('LASTNAME', $row['lastname']);		
				$rowBlock->assign('CONTENT', core::documentparser()->link_replace($message[0]['content']));		
				$rowBlock->assign('CREATED_AT', $message[0]['created']);		
				$rowBlock->assign('STATUS', $message[0]['status']);		
				$rowBlock->assign('ID_RECEIVER', $row['user_id']);		
				$rowBlock->assign('DIALOGUE_AVATAR', core::documentparser()->userAvatar($message[0]));			
				$rowBlock->assign('DIALOGUE_FIRSTNAME', $message[0]['firstname']);		
				$rowBlock->assign('DIALOGUE_LASTNAME', $message[0]['lastname']);	
		
				$tpl->assign('row_dialogues', $rowBlock);
			}
		}
		else $tpl->assign('NO_DIALOGUES', core::getLanguage('str', 'empty'));
	}
	else{
		
		$tpl->assign('BLOCK_USER', core::user()->checkFriends($user['id'], 2) ? 'yes' : 'no');	
		$tpl->assign('STR_BLOCK_USER', core::getLanguage('str', 'block_user'));	
		$tpl->assign('STR_UNBLOCK_USER', core::getLanguage('str', 'unblock_user'));		
	
		include_once "user_profile_info.inc";	
	
		$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'feed'));	
		$tpl->assign('ID_CONTENT', $_GET['user_id']);
		$tpl->assign('STR_REPLY', core::getLanguage('str', 'reply'));
		$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
		$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
		$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
		$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
		$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
		$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
		$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));
		$tpl->assign('STR_THERE_ARE_NO_MORE_ENTRIES', core::getLanguage('str', 'there_are_no_more_entries'));
		$tpl->assign('STR_CLICK', core::getLanguage('str', 'click'));
		$tpl->assign('STR_SEND', core::getLanguage('str', 'send'));
		$tpl->assign('STR_YOUR_COMMENT', core::getLanguage('str', 'your_comment'));
		$tpl->assign('STR_WHATS_INTERESTING', core::getLanguage('str', 'whats_interesting'));
	
		if($user_id != $user['id']){
			core::user()->setUser_id($user_id);
			$owner = core::user()->getUserInfo();
			$owner_settings = core::user()->getUserSetting();
			
			if($owner['banned'] == 1) $tpl->assign('BLOCK_PAGE', 'yes');
			else if($owner['deleted'] == 1) $tpl->assign('CLOSED_PAGE', 'yes');
	
			if(core::user()->permissionUser($user['id'], $owner_settings['permission_view_wall']) && Friends::checkBlock($user['id'], $user_id)) $tpl->assign("PERMISSION_WALL", "yes");
		}
		else{
			if($settings['permission_view_wall'] != 2) $tpl->assign("PERMISSION_WALL", "yes");
		}		

		$arr = Comments::treeComments(0, Comments::getCommentList((int)core::database()->escape(Core_Array::getRequest('user_id')), 'user', 10, 0));

		foreach($arr as $row){
			$rowBlock = $tpl->fetch('row_comments');	
			$rowBlock->assign('ID', $row['id_comment']);
			$rowBlock->assign('ID_PARENT', $row['parent_id']);	
			$rowBlock->assign('ID_USER', $row['user_id']);
			$rowBlock->assign('ID_USER_SESSION', $user['id']);
			$rowBlock->assign('ID_CONTENT', $row['content_id']);
			$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));
			$rowBlock->assign('FIRSTNAME', $row['firstname']);		
			$rowBlock->assign('LASTNAME', $row['lastname']);		
			$rowBlock->assign('CREATED', $row['created']);		
			$rowBlock->assign('CONTENT', core::documentparser()->link_replace($row['content']));
			$rowBlock->assign('NUMBERTELL', Comments::getNumberTell($row['id_comment'], 'comment'));
			$rowBlock->assign('NUMBERLIKED', Comments::getNumberLiked($row['id_comment'], 'comment'));	
			$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline');
		
			if($user['id'] == $row['user_id']){
				$rowBlock->assign('BUTTON_SHARE', 'hide');
			}
			else{
				$rowBlock->assign('BUTTON_SHARE', 'show');
			}			
		
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
	
			$rowBlock->assign('STR_REPLY', core::getLanguage('str', 'reply'));
			$tpl->assign('row_comments', $rowBlock);
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
	$css[] = './templates/css/owl.carousel.css';
	$css[] = './templates/css/owl.theme.css';
	$css[] = './templates/css/owl.transitions.css';

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

	$user_id = core::database()->escape((int)Core_Array::getRequest('user_id'));
	
	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";	
	
	include_once "user_profile_info.inc";	
	
	
	$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'feed'));	
	$tpl->assign('ID_CONTENT', $_GET['user_id']);
		
	core::user()->setUser_id($user_id);
	$owner = core::user()->getUserInfo();
	$owner_settings = core::user()->getUserSetting();
			
	if($owner['banned'] == 1) $tpl->assign('BLOCK_PAGE', 'yes');
	else if($owner['deleted'] == 1) $tpl->assign('CLOSED_PAGE', 'yes');
	
	if($owner_settings['permission_view_wall'] !=1 || $owner_settings['permission_view_wall'] !=2) $tpl->assign("PERMISSION_WALL", "yes");	
			
	$arr = Comments::treeComments(0, Comments::getCommentList((int)core::database()->escape(Core_Array::getRequest('user_id')), 'user', 10, 0));

	foreach($arr as $row){
		$rowBlock = $tpl->fetch('row_comments');	
		$rowBlock->assign('ID', $row['id_comment']);
		$rowBlock->assign('ID_PARENT', $row['parent_id']);	
		$rowBlock->assign('ID_USER', $row['user_id']);
		$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));
		$rowBlock->assign('FIRSTNAME', $row['firstname']);		
		$rowBlock->assign('LASTNAME', $row['lastname']);		
		$rowBlock->assign('CREATED', $row['created']);		
		$rowBlock->assign('CONTENT', core::documentparser()->link_replace($row['content']));
		$rowBlock->assign('NUMBERTELL', Comments::getNumberTell($row['id_comment'], 'comment'));
		$rowBlock->assign('NUMBERLIKED', Comments::getNumberLiked($row['id_comment'], 'comment'));	
		$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline');
		$rowBlock->assign('OPEN_PAGE', 'yes');		
		
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
	
		$rowBlock->assign('STR_REPLY', core::getLanguage('str', 'reply'));
		$tpl->assign('row_comments', $rowBlock);
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
