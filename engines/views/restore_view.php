<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

if($_POST['action'] == 'send_restore_link'){
	$_POST['email'] = trim($_POST['email']);
	
	if(empty($_POST['email'])){
		$error = core::getLanguage('error', 'empty_email');
	}
	else{
		if(core::documentparser()->check_email($_POST['email'])){
			$error_msg = core::getLanguage('error', 'wrong_email');
		}
		else if($data->checkExistEmail() == FALSE){
			$error_msg = core::getLanguage('error', 'user_not_found');
		}
		else{
			$reset_password_token = core::documentparser()->generateCode(20);
			$result = $data->setReset($reset_password_token, $_POST['email']);

			if($result){				
				Auth::Logout();
				
				$restore_link = "http://".$_SERVER['SERVER_NAME']."/?task=restore&reset_password_token=".$reset_password_token."";
				
				$msg = core::getLanguage('msg', 'restore');
				$msg = str_replace('%SITENAME%', core::getLanguage('str', 'sitename'), $msg);
				$msg = str_replace('%RESTORE_LINK%', $restore_link, $msg);

				if($data->sendRestoreLink($_POST['email'], core::getLanguage('subject', 'restore'), $msg)) $success_msg = core::getLanguage('msg', 'send_restore_link');				
			}
			else
				$error_msg = core::getLanguage('error', 'web_apps_error');			
		}		
	}	
}
else if($_POST['action'] == 'reset_password'){
	$errors = array();
	
	$password = trim($_POST['password']);
	$again_password = trim($_POST['again_password']);
	
	$reset_password_token = core::database()->escape($_GET['reset_password_token']);
	
	if(!empty($password) && strlen($password) < 6) $errors[] = core::getLanguage('error', 'short_password');
	if(empty($password)) $errors[] = core::getLanguage('error', 'empty_password');
	if(empty($again_password)) $errors[] = core::getLanguage('error', 'empty_confirm_password');	
	if($password != $again_password) $errors[] = core::getLanguage('error', 'passwords_dont_match');
	if(empty($_GET['reset_password_token'])) $errors[] = core::getLanguage('error', 'incorrect_token');
	if(!empty($_GET['reset_password_token']) && $data->checkExistResetToken($reset_password_token) == FALSE) $errors[] = core::getLanguage('error', 'incorrect_token');
	
	if(count($errors) == 0){
		$result = $data->changePassword($password, $_GET['reset_password_token']);	
	
		if($result){
			$success_msg = core::getLanguage('msg', 'password_change');			
		}
		else
			$error_msg = core::getLanguage('error', 'change_password');
	}
}

//include template
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");
$tpl->assign('TITLE', core::getLanguage('title', 'restore'));

if(isset($_GET['reset_password_token']) && $data->checkExistResetToken($_GET['reset_password_token']) == TRUE) {
	$tpl->assign('RESET_PASSWORD_TOKEN', $_GET['reset_password_token']);
	$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'restore'));
	$tpl->assign('STR_RESTORE_FORM_TITLE', core::getLanguage('str', 'restore_form_title'));
	$tpl->assign('STR_EMAIL_REGISTRATION_FORM', core::getLanguage('str', 'email_registration_form'));
	$tpl->assign('STR_CONFIRM_PASSWORD_FORM', core::getLanguage('str', 'confirm_password_form'));
	$tpl->assign('STR_PASSWORD_FORM', core::getLanguage('str', 'password_form'));
	$tpl->assign('BUTTON_CHANGE', core::getLanguage('button', 'change'));		
}

$tpl->assign('STR_CHANGE_PASSWORD_FORM_TITLE', core::getLanguage('str', 'change_password_form_title'));
$tpl->assign('MSG_ALERT', $success_msg);
$tpl->assign('ERROR_ALERT', $error_msg);

if(count($errors) > 0){
	$errorBlock = $tpl->fetch('show_errors');
	$errorBlock->assign('STR_IDENTIFIED_FOLLOWING_ERRORS', core::getLanguage('str', 'identified_following_errors'));
			
	foreach($errors as $row){
		$rowBlock = $errorBlock->fetch('row');
		$rowBlock->assign('ERROR', $row);
		$errorBlock->assign('row', $rowBlock);
	}
		
	$tpl->assign('show_errors', $errorBlock);
}

$tpl->assign('STR_CHANGE_PASSWORD', core::getLanguage('str', 'change_password'));
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_ERROR', core::getLanguage('str', 'error'));
$tpl->assign('BUTTON_SEND', core::getLanguage('button', 'send'));
$tpl->assign('STR_EMAIL', core::getLanguage('str', 'email'));
$tpl->assign('STR_REQUIRED_FIELDS', core::getLanguage('str', 'required_fields'));

$tpl->assign('EMAIL', $email);
$tpl->assign('STR_INSTRUCTION_RESTORE', core::getLanguage('str', 'instruction_restore'));

$tpl->assign('CURRENT_YEAR', date("Y"));
$tpl->assign('STR_COPYRIGHT', core::getLanguage('str', 'copyright'));
$tpl->assign('MENU_ABOUT_SERVICE', core::getLanguage('menu', 'about_service'));
$tpl->assign('MENU_POSSIBILITY', core::getLanguage('menu', 'possibility'));
$tpl->assign('MENU_ADVERTISING', core::getLanguage('menu', 'advertising'));
$tpl->assign('MENU_TERMS_OF_USE', core::getLanguage('menu', 'terms_of_use'));
$tpl->assign('MENU_RULES', core::getLanguage('menu', 'rules'));
$tpl->assign('MENU_FEEDBACK', core::getLanguage('menu', 'feedback'));
		
//display content
$tpl->display();