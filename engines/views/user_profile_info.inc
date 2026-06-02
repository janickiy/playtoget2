<?php

if($profile['id'] && $profile['banned'] != 1 && $profile['deleted'] != 1){
	foreach(Sport::getUserSportTypeList($profile['id']) as $row){
		$rowBlock = $tpl->fetch('row_top_profile_sport');
		$rowBlock->assign('STR_PROFILE_TOP_SPORT_TYPE', core::getLanguage('str', 'sport_type'));
		$rowBlock->assign('STR_PROFILE_TOP_SPORT_LEVEL', core::getLanguage('str', 'sport_level'));
		$rowBlock->assign('STR_PROFILE_TOP_SEARCH_TEAM', core::getLanguage('str', 'search_team'));
		$rowBlock->assign('SPORT_TYPE', $row['sport_type']);		
		$rowBlock->assign('SPORT_LEVEL', $row['sport_level']);		
		$rowBlock->assign('SEARCH_TEAM', $row['search_team'] == 1 ? core::getLanguage('str', 'yes') : core::getLanguage('str', 'no'));
		$tpl->assign('row_top_profile_sport', $rowBlock);	
	}
}
	
if($profile['banned'] != 1 && $profile['deleted'] != 1)	{
	$tpl->assign('SHOW_ROLLOUT', 'show');
	$tpl->assign('STR_PROFILE_TOP_SPORTS_ACHIVMENTS', core::getLanguage('str', 'sports_achivments'));
	$tpl->assign('SPORTS_ACHIVMENTS', $profile['about_sport']);	
	$tpl->assign('STR_PROFILE_TOP_BIRTHDAY', core::getLanguage('str', 'birthday'));
	$tpl->assign('STR_PROFILE_TOP_CITY', core::getLanguage('str', 'city'));	
	$tpl->assign('STR_PROFILE_TOP_PHONE', core::getLanguage('str', 'phone'));		
	$tpl->assign('STR_PROFILE_TOP_EMAIL', core::getLanguage('str', 'email'));	
	$tpl->assign('STR_PROFILE_TOP_EDUCATION', core::getLanguage('str', 'education'));	
	$tpl->assign('STR_PROFILE_TOP_WORK_PLACE', core::getLanguage('str', 'work_place'));		
	$tpl->assign('STR_ROLLOUT', core::getLanguage('str', 'rollout'));	
	$tpl->assign('STR_ROLLIN', core::getLanguage('str', 'rollin'));	
	$tpl->assign('STR_PERSONAL_WEBSITE', core::getLanguage('str', 'personal_website'));
	$tpl->assign('USER_BIRTHDAY', core::documentparser()->mysql_russian_date($profile['birthday']));	
	$tpl->assign('USER_PLACE', $profile['city']);
	$tpl->assign('USER_PHONE', $profile['phone']);
	$tpl->assign('USER_CONTACT_EMAIL', $profile['contact_email']);
	$tpl->assign('USER_SKYPE', $profile['skype']);
	$tpl->assign('USER_WEBSITE', $profile['website']);
}
	
if($profile['id'] && $profile['banned'] != 1 && $profile['deleted'] != 1)	{

	$arr_education = Occupation::getOccupationsList($profile['id'], 1);

	if($arr_education){
		$tpl->assign('PROFILE_TOP_EDUCATION', 'show');

		foreach($arr_education as $row){
			$rowBlock = $tpl->fetch('row_top_profile_education');		
			$rowBlock->assign('NAME', $row['name']);
			$rowBlock->assign('description', $row['description']);		
			$rowBlock->assign('CITY', $row['city']);
			$rowBlock->assign('MONTH_START', $row['month_start']);		
			$rowBlock->assign('YEAR_START', $row['year_start']);		
			$rowBlock->assign('MONTH_FINISH', $row['month_finish']);		
			$rowBlock->assign('YEAR_FINISH', $row['year_finish']);		
			$tpl->assign('row_top_profile_education', $rowBlock);
		}
	}

	$arr_job = Occupation::getOccupationsList($profile['id'], 3);
	
	if($arr_job){
		$tpl->assign('PROFILE_TOP_WORK_PLACE', 'show');	
	
		foreach($arr_job as $row){
			$rowBlock = $tpl->fetch('row_top_profile_job');		
			$rowBlock->assign('NAME', $row['name']);
			$rowBlock->assign('description', $row['description']);		
			$rowBlock->assign('CITY', $row['city']);
			$rowBlock->assign('MONTH_START', $row['month_start']);		
			$rowBlock->assign('YEAR_START', $row['year_start']);		
			$rowBlock->assign('MONTH_FINISH', $row['month_finish']);		
			$rowBlock->assign('YEAR_FINISH', $row['year_finish']);		
			$tpl->assign('row_top_profile_job', $rowBlock);
		}
	}	
}
