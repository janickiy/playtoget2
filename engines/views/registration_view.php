<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

//include template
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$tpl->assign('TITLE', core::getLanguage('title', 'registration'));

if($_POST['action']){
	$email      = trim($_POST['email']);
	$firstname  = trim($_POST['firstname']);
	$lastname   = trim($_POST['lastname']);
	$secondname = trim($_POST['secondname']);
	$password   = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);
	
	$error = array();	
	
	// check the data
	if(empty($email)) $error[] = core::getLanguage('error', 'empty_email');
	if(empty($firstname)) $error[] = core::getLanguage('error', 'empty_firstname');
	if(empty($lastname)) $error[] = core::getLanguage('error', 'empty_lastname');
	if(empty($password)) $error[] = core::getLanguage('error', 'empty_password');
	if(empty($confirm_password)) $error[] = core::getLanguage('error', 'empty_confirm_password');
	if(!$_POST['use_terms']) $error[] = core::getLanguage('error', 'use_terms');
	
	if(!empty($email) and core::documentparser()->check_email($email)){
		$error[] = core::getLanguage('error', 'wrong_email');
	}
	
	if(!empty($email) and $data->checkExistEmail($email)){
		$error[] = core::getLanguage('error', 'email_isnt_free');		
	}
	
	if($password and $confirm_password and $password != $confirm_password){
		$error[] = core::getLanguage('error', 'passwords_dont_match');
	}

	if(!empty($password)){
		if(strlen($password) < 6) $error[] = core::getLanguage('error', 'short_password'); 
	}
	
	$errors[] = core::getLanguage('error', 'short_password');	
	
	if(count($error) == 0){
		$token = core::documentparser()->generateCode(20);
		
		$fields = array();
		$fields['id']       = 0;
		$fields['email']    = $email;
		$fields['password'] = md5($password);
		$fields['confirmation_token'] = $token;
		$fields['firstname']  = $firstname;
		$fields['lastname']   = $lastname;
		$fields['secondname'] = $secondname;		
		$fields['created_at'] = date("Y-m-d H:i:s");		
		$fields['confirmation_sent_at'] = date("Y-m-d H:i:s");	

		$id_user = $data->addUser($fields);
		
		if($id_user){
			$confirm_link = "http://".$_SERVER['SERVER_NAME']."/?task=confirmation&id=".$id_user."&confirmation_token=".$token;
			$success_msg = core::getLanguage('msg', 'success_registration');
			
			$msg = core::getLanguage('msg', 'registration');
			$msg = str_replace('%EMAIL%', $email, $msg);
			$msg = str_replace('%SITENAME%', core::getLanguage('str', 'sitename'), $msg);
			$msg = str_replace('%CONFIRM_LINK%', $confirm_link, $msg);			
			
			$data->sendNotification($email, core::getLanguage('subject', 'registration'), $msg);
		}
		else{
			$error_msg = core::getLanguage('msg', 'error_registration');
		}		
	}
}

//alert
if(count($error) > 0){
	$errorBlock = $tpl->fetch('show_errors');
	$errorBlock->assign('STR_IDENTIFIED_FOLLOWING_ERRORS', core::getLanguage('str', 'identified_following_errors'));
			
	foreach($error as $row){
		$rowBlock = $errorBlock->fetch('row');
		$rowBlock->assign('ERROR', $row);
		$errorBlock->assign('row', $rowBlock);
	}
		
	$tpl->assign('show_errors', $errorBlock);
}

if(!empty($error_msg)) {
	$tpl->assign('ERROR_ALERT', $error_msg);
}
//$success_msg = core::getLanguage('msg', 'success_registration');
if(!empty($success_msg)){ 
	$tpl->assign('MSG_ALERT', $success_msg);
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'registration'));
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_EMAIL_REGISTRATION_FORM', core::getLanguage('str', 'email_registration_form'));
$tpl->assign('STR_REGISTRATION_FORM_TITLE', core::getLanguage('str', 'registration_form_title'));
$tpl->assign('STR_FIRSTNAME_REGISTRATION_FORM', core::getLanguage('str', 'firstname_registration_form'));
$tpl->assign('STR_LASTNAME_REGISTRATION_FORM', core::getLanguage('str', 'lastname_registration_form'));
$tpl->assign('STR_SECONDNAME_REGISTRATION_FORM', core::getLanguage('str', 'secondname_registration_form'));
$tpl->assign('STR_PASSWORD_REGISTRATION_FORM', core::getLanguage('str', 'password_registration_form'));
$tpl->assign('STR_CONFIRM_PASSWORD_FORM', core::getLanguage('str', 'confirm_password_form'));
$tpl->assign('STR_ACCEPT_AGREEMENT_FORM', core::getLanguage('str', 'accept_agreement_form'));
$tpl->assign('STR_USE_TERMS', core::getLanguage('str', 'use_terms'));
$tpl->assign('BUTTON_SIGN_UP', core::getLanguage('button', 'sign_up'));
$tpl->assign('STR_REQUIRED_FIELDS', core::getLanguage('str', 'required_fields'));
$tpl->assign('STR_FIRSTNAME', core::getLanguage('str', 'firstname_registration_form'));
$tpl->assign('STR_LASTNAME', core::getLanguage('str', 'lastname_registration_form'));
$tpl->assign('STR_SECONDNAME', core::getLanguage('str', 'secondname_registration_form'));
$tpl->assign('STR_PASSWORD', core::getLanguage('str', 'password_registration_form'));
$tpl->assign('STR_CONFIRM_PASSWORD', core::getLanguage('str', 'confirm_password_form'));

$tpl->assign('EMAIL', $_POST['email']);
$tpl->assign('FIRSTNAME', $_POST['firstname']);
$tpl->assign('LASTNAME', $_POST['lastname']);
$tpl->assign('SECONDNAME', $_POST['secondname']);
$tpl->assign('USE_TERMS', $_POST['use_terms']);

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