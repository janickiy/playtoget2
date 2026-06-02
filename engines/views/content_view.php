<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$content_id = core::database()->escape((int)Core_Array::getRequest('content_id'));

if(empty($content_id)) {
	header("HTTP/1.1 500 server error");
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/500.html"); 
	exit;
}

if($data->existContent($content_id)) {
	header("HTTP/1.1 404 Not Found");
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
	exit;
}

$content = $data->getContentInfo($content_id);

$tpl->assign('TITLE', $content['title']);
$tpl->assign('TITLE_PAGE', $content['title']);
$tpl->assign('META_DESCRIPTION', $content['meta_discription']);
$tpl->assign('META_KEYWORDS', $content['meta_keywords']);
$tpl->assign('CONTENT', $content['text']);

include_once "footer.inc";
		
$tpl->display();