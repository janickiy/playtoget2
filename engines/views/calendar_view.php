<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

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

$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'calendar'));	

$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

include_once "footer.inc";
		
$tpl->display();