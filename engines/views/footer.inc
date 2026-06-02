<?php

$tpl->assign('CURRENT_YEAR', date("Y"));
$tpl->assign('STR_COPYRIGHT', core::getLanguage('str', 'copyright'));

//menu
$tpl->assign('MENU_ABOUT_SERVICE', core::getLanguage('menu', 'about_service'));
$tpl->assign('MENU_POSSIBILITY', core::getLanguage('menu', 'possibility'));
$tpl->assign('MENU_ADVERTISING', core::getLanguage('menu', 'advertising'));
$tpl->assign('MENU_TERMS_OF_USE', core::getLanguage('menu', 'terms_of_use'));
$tpl->assign('MENU_RULES', core::getLanguage('menu', 'rules'));
$tpl->assign('MENU_FEEDBACK', core::getLanguage('menu', 'feedback'));