<?php

$tpl->assign('STR_SPORT_NEWS', core::getLanguage('str', 'sport_news'));
$tpl->assign('STR_EVENTS', core::getLanguage('title', 'events'));
$tpl->assign('NUMBEREVENT',Events::getNumberPopularEvents());


foreach(Rss::getRssNews(5) as $row){	
	$rowBlock = $tpl->fetch('row_sport_news_block');
	$rowBlock->assign('LINK', $row['link']);
	$rowBlock->assign('TITLE', $row['title']);
	$rowBlock->assign('DESCRIPTION', $row['description']);
	$rowBlock->assign('PUBDATE', $row['time']);
	$tpl->assign('row_sport_news_block', $rowBlock);
}

foreach(Events::getPopularEventList(3, 0) as $row){
	$rowBlock = $tpl->fetch('row_recommended_events');
	$rowBlock->assign('ID', $row['id_event']);
	$rowBlock->assign('NAME', $row['name']);
	$rowBlock->assign('AVATAR', core::documentparser()->eventAvatar($row['cover_page']));
	$rowBlock->assign('PARTICIPANTS_FRIENDS', str_replace('%MEMBERS%', Events::countMembers($row['id_event'], 'user'), core::getLanguage('str', 'participants_friends')));
	$tpl->assign('row_recommended_events', $rowBlock);
}