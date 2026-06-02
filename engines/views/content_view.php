<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$id_content = core::database()->escape((int)Core_Array::getRequest('id_content'));

if(empty($id_content)) {
	header("HTTP/1.1 500 server error");
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/500.html"); 
	exit;
}

if($data->existContent($id_content)) {
	header("HTTP/1.1 404 Not Found");
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
	exit;
}

$content = $data->getContentInfo($id_content);

$tpl->assign('TITLE', $content['title']);
$tpl->assign('TITLE_PAGE', $content['title']);
$tpl->assign('META_DESCRIPTION', $content['meta_discription']);
$tpl->assign('META_KEYWORDS', $content['meta_keywords']);
$tpl->assign('CONTENT', $content['text']);

include_once "footer.inc";
		
$tpl->display();