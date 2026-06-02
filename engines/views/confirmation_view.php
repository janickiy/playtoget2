<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

if(empty($_GET['id']) or empty($_GET['confirmation_token'])) $error_msg = core::getLanguage('error', 'confirmation');

if(!empty($_GET['id']) and $data->checkConfirm()) {
	$error_msg = core::getLanguage('error', 'confirmed');
}

$tpl->assign('STR_CONFIRMATION_OF_REGISTRATION', core::getLanguage('str', 'confirmation_of_registration'));
$tpl->assign('STR_GO_TO_SITE', core::getLanguage('str', 'go_to_site'));
$tpl->assign('SERVER_NAME', $_SERVER['SERVER_NAME']);

if(empty($error_msg)){
	if($data->checkToken()){
		$result = $data->doConfirm();
		
		if($result){
			
			Auth::Login($_GET['id'], 1);
			
			//header("Location: ./?task=edit_profile");
			//exit;	

			$tpl->assign('MSG_ALERT', core::getLanguage('msg', 'success_confirmation'));
		}
		else
			$error_msg = core::getLanguage('error', 'confirmation');	
	}
	else $error_msg = core::getLanguage('error', 'confirmation_wrong_token');	
}

if($error_msg){
	$tpl->assign('STR_ERROR', core::getLanguage('str', 'error'));
	$tpl->assign('ERROR_ALERT', $error_msg);
}

include "footer.inc";

$tpl->display();