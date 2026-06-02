<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

if($_GET['q'] == 'xml'){
	header('Content-Type: application/xml; charset=utf-8');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

	$arr = $data->sitemap();

	for($i=0; $i<count($arr); $i++){
		echo core::documentparser()->sitemap_url_gen(core::documentparser()->getPageUrl($arr[$i]['type'], $arr[$i]['id']), $arr[$i]['updated'], 'weekly', $arr[$i]['priority']);
	}

	echo "</urlset>";

}else{
	session_start();	
	
	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");
	
	if($_SESSION['user_authorization'] == "ok"){
	
		Auth::authorization();		

		core::user()->setUser_id($_SESSION['id_user']);
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
	}
	else{
		$tpl->assign('OPEN_PAGE', 'yes');
		
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
	}		

	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";

	$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'sitemap'));	
	
	$arr = $data->sitemap();
	
	for($i=0; $i<count($arr); $i++){
		if($arr[$i]['type'] == 'group'){
			$rowBlock = $tpl->fetch('row_groups_map');
			$rowBlock->assign('ID', $arr[$i]['id']);
			$rowBlock->assign('NAME', Communities::getCommunityName($arr[$i]['id']));			
			$rowBlock->assign('URL', core::documentparser()->getPageUrl($arr[$i]['type'], $arr[$i]['id']));			
			$tpl->assign('row_groups_map', $rowBlock);
		}
		
		if($arr[$i]['type'] == 'team'){
			$rowBlock = $tpl->fetch('row_teams_map');
			$rowBlock->assign('ID', $arr[$i]['id']);
			$rowBlock->assign('NAME', Communities::getCommunityName($arr[$i]['id']));
			$rowBlock->assign('URL', core::documentparser()->getPageUrl($arr[$i]['type'], $arr[$i]['id']));	
			$tpl->assign('row_teams_map', $rowBlock);
		}
		
		if($arr[$i]['type'] == 'event'){
			$rowBlock = $tpl->fetch('row_events_map');
			$rowBlock->assign('ID', $arr[$i]['id']);
			$rowBlock->assign('NAME', Events::getEventName($arr[$i]['id']));
			$rowBlock->assign('URL', core::documentparser()->getPageUrl($arr[$i]['type'], $arr[$i]['id']));	
			$tpl->assign('row_events_map', $rowBlock);
		}
		
		if($arr[$i]['type'] == 'playground'){
			$rowBlock = $tpl->fetch('row_playgrounds_map');
			$rowBlock->assign('ID', $arr[$i]['id']);
			$rowBlock->assign('URL', core::documentparser()->getPageUrl($arr[$i]['type'], $arr[$i]['id']));	
			$rowBlock->assign('NAME', SportBlocks::getSportBlockName($arr[$i]['id']));			
			$tpl->assign('row_playgrounds_map', $rowBlock);
		}	
		
		if($arr[$i]['type'] == 'shop'){
			$rowBlock = $tpl->fetch('row_shops_map');
			$rowBlock->assign('ID', $arr[$i]['id']);
			$rowBlock->assign('NAME', SportBlocks::getSportBlockName($arr[$i]['id']));
			$rowBlock->assign('URL', core::documentparser()->getPageUrl($arr[$i]['type'], $arr[$i]['id']));	
			$tpl->assign('row_shops_map', $rowBlock);			
		}
	
		
		if($arr[$i]['type'] == 'fitness'){
			$rowBlock = $tpl->fetch('row_fitness_map');
			$rowBlock->assign('ID', $arr[$i]['id']);
			$rowBlock->assign('NAME', SportBlocks::getSportBlockName($arr[$i]['id']));
			$rowBlock->assign('URL', core::documentparser()->getPageUrl($arr[$i]['type'], $arr[$i]['id']));	
			$tpl->assign('row_fitness_map', $rowBlock);			
		}
	}	

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

	include_once "footer.inc";
		
	$tpl->display();	
}