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

	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";

	$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'friends'));	

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

	$id_user = $_GET['id_user'] ? core::database()->escape((int)Core_Array::getRequest('id_user')) : $user['id'];

	$tpl->assign('STR_SHOW_MORE', core::getLanguage('str', 'show_more'));
	$tpl->assign('STR_POSSIBLE_FRIEND', core::getLanguage('str', 'possible_friend'));
	$tpl->assign('STR_MY_FRIENDS', core::getLanguage('str', $_GET['id_user'] ? 'friends' : 'my_friends'));
	$tpl->assign('STR_FRIENDS_REQUEST', core::getLanguage('str', 'friends_request'));
	$tpl->assign('STR_OUTGOING_REQUEST', core::getLanguage('str', 'outgoing_request'));

	include_once "user_profile_info.inc";

	if(!$_GET['id_user'] or $_GET['id_user'] == $user['id']) {

		$arr_possiblefriends = Friends::getPossibleFriendsList($id_user, 6, 0);
	
		if($arr_possiblefriends){
			foreach($arr_possiblefriends as $row){
				$rowBlock = $tpl->fetch('row_possible_friends');
				$rowBlock->assign('ID_FRIEND', $row['id_user']);			
				$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));					
				$rowBlock->assign('FIRSTNAME', $row['firstname']);					
				$rowBlock->assign('LASTNAME', $row['lastname']);
				$rowBlock->assign('CITY', $row['city']);	
				$rowBlock->assign('STR_SEND_MESSAGE', core::getLanguage('str', 'send_message'));	
				$rowBlock->assign('STR_ADD_AS_FRIEND', core::getLanguage('str', 'add_as_friend'));		
				$rowBlock->assign('STR_VIEW_FRIENDS', core::getLanguage('str', 'view_friends'));		
				$rowBlock->assign('SEL', $user['id']);	
				$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['id_user']) ? 'online' : 'offline');			
				$tpl->assign('row_possible_friends', $rowBlock);	
			}		
		}else $tpl->assign('NO_POSSIBLE_FRIENDS', core::getLanguage('str', 'empty'));	
	}else $tpl->assign('NO_POSSIBLE_FRIENDS', core::getLanguage('str', 'empty'));	
	
	$arr_friends = Friends::getFriendsList($id_user, 10, 0);
	
	if($arr_friends){	
		$tpl->assign('NUMBER_FRIENDS', Friends::NumberFriends($id_user));
		
		if (Friends::NumberFriends($id_user)>10) $tpl->assign('SHOW_MORE_MY_FRIENDS', 'show');
		
		foreach($arr_friends as $row){
			core::user()->setUser_id($row['id_user']);
	
			$rowBlock = $tpl->fetch('row_my_friends');
			$rowBlock->assign('ID_FRIEND', $row['id_user']);	
			$rowBlock->assign('SEL', $user['id']);
			$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));
			$rowBlock->assign('FIRSTNAME', $row['firstname']);					
			$rowBlock->assign('LASTNAME', $row['lastname']);
			$rowBlock->assign('CITY', $row['city']);	
			$rowBlock->assign('STR_SEND_MESSAGE', core::getLanguage('str', 'send_message'));	
			$rowBlock->assign('STR_VIEW_FRIENDS', core::getLanguage('str', 'view_riends'));	
			$rowBlock->assign('STR_REMOVE_FRIEND', core::getLanguage('str', 'add_as_friend'));	
			$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['id_user']) ? 'online' : 'offline');
		
			if(!$_GET['id_user'] or $_GET['id_user'] == $user['id']) $rowBlock->assign('REMOVE_FRIEND', 'show');
	
			$tpl->assign('row_my_friends', $rowBlock);
		}
	}else $tpl->assign('NO_FRIENDS', core::getLanguage('str', 'empty'));	
	
	if(!$_GET['id_user'] or $_GET['id_user'] == $user['id']) {	
	
		$arr_friends_request = Friends::getFriendsRequestList($user['id'], 10, 0);
	
		if($arr_friends_request){
	
			$tpl->assign('NUMBER_FRIENDS_REQUEST', Friends::NumberFriendsRequest($user['id']));
	
			foreach($arr_friends_request as $row){
				core::user()->setUser_id($row['id_friend']);
		
				$rowBlock = $tpl->fetch('row_request_friends');
				$rowBlock->assign('ID_FRIEND', $row['id_user']);	
				$rowBlock->assign('SEL', $user['id']);
				$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));
				$rowBlock->assign('FIRSTNAME', $row['firstname']);					
				$rowBlock->assign('LASTNAME', $row['lastname']);
				$rowBlock->assign('CITY', $row['city']);				
				$rowBlock->assign('STR_SEND_MESSAGE', core::getLanguage('str', 'send_message'));	
				$rowBlock->assign('STR_VIEW_FRIENDS', core::getLanguage('str', 'view_riends'));	
				$rowBlock->assign('STR_ADD_AS_FRIEND', core::getLanguage('str', 'add_as_friend'));	
				$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['id_user']) ? 'online' : 'offline');
				$tpl->assign('row_request_friends', $rowBlock);
			}
		}
		else $tpl->assign('NO_FRIENDS_REQUEST', core::getLanguage('str', 'empty'));
	}else $tpl->assign('NO_FRIENDS_REQUEST', core::getLanguage('str', 'empty'));

	if(!$_GET['id_user'] or $_GET['id_user'] == $user['id']) {	

		$arr_outgoing_request = Friends::getOutgoingRequestList($user['id'], 10, 0);
	
		if($arr_outgoing_request){
	
			$tpl->assign('OUTGOING_REQUEST', Friends::NumberOutgoingRequest($user['id']));
	
			foreach($arr_outgoing_request as $row){
				core::user()->setUser_id($row['id_friend']);
		
				$rowBlock = $tpl->fetch('row_outgoing_request');
				$rowBlock->assign('ID_FRIEND', $row['id_user']);	
				$rowBlock->assign('SEL', $user['id']);
				$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));
				$rowBlock->assign('FIRSTNAME', $row['firstname']);					
				$rowBlock->assign('LASTNAME', $row['lastname']);
				$rowBlock->assign('CITY', $row['city']);		
				$rowBlock->assign('STR_SEND_MESSAGE', core::getLanguage('str', 'send_message'));	
				$rowBlock->assign('STR_VIEW_FRIENDS', core::getLanguage('str', 'view_riends'));	
				$rowBlock->assign('STR_ADD_AS_FRIEND', core::getLanguage('str', 'add_as_friend'));
				$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['id_user']) ? 'online' : 'offline');	
				$tpl->assign('row_outgoing_request', $rowBlock);
			}
		}
		else $tpl->assign('NO_OUTGOING_REQUEST', core::getLanguage('str', 'empty'));
	}else $tpl->assign('NO_OUTGOING_REQUEST', core::getLanguage('str', 'empty'));

	include_once "footer.inc";
		
	$tpl->display();
	
}
else{
	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");
	
	$tpl->assign('OPEN_PAGE', 'yes');	
	
	if(empty($_GET['id_user'])){
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
	
	$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'friends'));	

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

	$id_user = core::database()->escape((int)Core_Array::getRequest('id_user'));

	$tpl->assign('STR_SHOW_MORE', core::getLanguage('str', 'show_more'));
	$tpl->assign('STR_POSSIBLE_FRIEND', core::getLanguage('str', 'possible_friend'));
	$tpl->assign('STR_MY_FRIENDS', core::getLanguage('str', $_GET['id_user'] ? 'friends' : 'my_friends'));
	$tpl->assign('STR_FRIENDS_REQUEST', core::getLanguage('str', 'friends_request'));
	$tpl->assign('STR_OUTGOING_REQUEST', core::getLanguage('str', 'outgoing_request'));

	include_once "user_profile_info.inc";
	
	$arr_friends = Friends::getFriendsList($id_user, 10, 0);
	
	if($arr_friends){	
		$tpl->assign('NUMBER_FRIENDS', Friends::NumberFriends($id_user));
		
		if (Friends::NumberFriends($id_user) > 10) $tpl->assign('SHOW_MORE_MY_FRIENDS', 'show');
		
		foreach($arr_friends as $row){
			core::user()->setUser_id($row['id_user']);
			$rowBlock = $tpl->fetch('row_my_friends');
			$rowBlock->assign('ID_FRIEND', $row['id_user']);	
			$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));
			$rowBlock->assign('FIRSTNAME', $row['firstname']);					
			$rowBlock->assign('LASTNAME', $row['lastname']);
			$rowBlock->assign('CITY', $row['city']);	
			$rowBlock->assign('STR_SEND_MESSAGE', core::getLanguage('str', 'send_message'));	
			$rowBlock->assign('STR_VIEW_FRIENDS', core::getLanguage('str', 'view_riends'));			
			$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['id_user']) ? 'online' : 'offline');
			$tpl->assign('row_my_friends', $rowBlock);
		}
	}else $tpl->assign('NO_FRIENDS', core::getLanguage('str', 'empty'));	

	$tpl->assign('NO_OUTGOING_REQUEST', core::getLanguage('str', 'empty'));
	$tpl->assign('NO_FRIENDS_REQUEST', core::getLanguage('str', 'empty'));
	$tpl->assign('NO_POSSIBLE_FRIENDS', core::getLanguage('str', 'empty'));
	
	include_once "footer.inc";	
	
	$tpl->display();	
}	