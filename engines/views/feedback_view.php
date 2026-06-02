<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

//include template
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'feedback'));	

include_once "footer.inc";
		
//display content
$tpl->display();