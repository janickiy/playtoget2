<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

Auth::authorization();

	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

	core::user()->setUser_id($_SESSION['user_id']);
	$user = core::user()->getUserInfo();
	core::user()->setUserActivity();

	core::user()->setUser_id($_SESSION['user_id']);
	$user = core::user()->getUserInfo();

	$tpl->assign('NUMBERMESSAGE', core::user()->MessageNotification());
	$tpl->assign('NUMBERINVITATION', core::user()->AddFriendsNotification());

	$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'edit_profile'));
	$tpl->assign('TITLE', core::getLanguage('title', 'edit_profile'));

	if(!empty($error_msg)){
		$tpl->assign('STR_ERROR', core::getLanguage('str', 'error'));
		$tpl->assign('ERROR_ALERT', $error_msg);
	}

	if(!empty($success_msg)) $tpl->assign('MSG_ALERT', $success_msg);

	if($errors){
		$errorBlock = $tpl->fetch('show_errors');
		$errorBlock->assign('STR_IDENTIFIED_FOLLOWING_ERRORS', core::getLanguage('str', 'identified_following_errors'));
			
		foreach($errors as $row){
			$rowBlock = $errorBlock->fetch('row');
			$rowBlock->assign('ERROR', $row);
			$errorBlock->assign('row', $rowBlock);
		}
		
		$tpl->assign('show_errors', $errorBlock);
	}

	$tpl->assign('STR_MAIN', core::getLanguage('str', 'main'));
	$tpl->assign('STR_EDUCATION_AND_JOB', core::getLanguage('str', 'education_and_job'));
	$tpl->assign('STR_SPORT_ACHIVMENTS', core::getLanguage('str', 'sport_achivments'));

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

	$tpl->assign('STR_PHOTO', core::getLanguage('str', 'photo'));
	$tpl->assign('STR_PERSONAL_INFORMATION', core::getLanguage('str', 'personal_information'));
	$tpl->assign('STR_EDUCATION', core::getLanguage('str', 'education'));
	$tpl->assign('STR_MY_SPORTS', core::getLanguage('str', 'my_sports'));

	$tpl->assign('STR_JOB', core::getLanguage('str', 'job'));
	$tpl->assign('STR_JOB_PLACE', core::getLanguage('str', 'job_place'));
	$tpl->assign('STR_JOB_NAME', core::getLanguage('str', 'job_name'));
	$tpl->assign('STR_JOB_DESCRIPTION', core::getLanguage('str', 'job_description'));
	$tpl->assign('STR_JOB_MONTH_START', core::getLanguage('str', 'job_month_start'));
	$tpl->assign('STR_JOB_MONTH_FINISH', core::getLanguage('str', 'job_month_finish'));
	$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
	$tpl->assign('STR_ABOUT', core::getLanguage('str', 'about'));
	$tpl->assign('STR_FIRSTNAME', core::getLanguage('str', 'firstname'));
	$tpl->assign('STR_LASTNAME', core::getLanguage('str', 'lastname'));
	$tpl->assign('STR_SECONDNAME', core::getLanguage('str', 'secondname'));
	$tpl->assign('STR_SEX', core::getLanguage('str', 'sex'));
	$tpl->assign('STR_MALE', core::getLanguage('str', 'male'));
	$tpl->assign('STR_FEMALE', core::getLanguage('str', 'female'));
	$tpl->assign('STR_BIRTHDAY', core::getLanguage('str', 'birthday'));
	$tpl->assign('STR_CITY', core::getLanguage('str', 'city'));
	$tpl->assign('STR_COVER_IMAGE', core::getLanguage('str', 'cover_image'));
	$tpl->assign('STR_EDUCATION_PLACE', core::getLanguage('str', 'education_place'));
	$tpl->assign('STR_EDUCATION_NAME', core::getLanguage('str', 'education_name'));
	$tpl->assign('STR_EDUCATION_DESCRIPTION', core::getLanguage('str', 'education_description'));
	$tpl->assign('STR_EDUCATION_MONTH_START', core::getLanguage('str', 'education_month_start'));
	$tpl->assign('STR_EDUCATION_YEAR_START', core::getLanguage('str', 'education_year_start'));
	$tpl->assign('STR_EDUCATION_MONTH_FINISH', core::getLanguage('str', 'education_month_finish'));
	$tpl->assign('STR_EDUCATION_YEAR_FINISH', core::getLanguage('str', 'education_year_finish'));
	$tpl->assign('STR_MONTH_JAN', core::getLanguage('str', 'month_jan'));
	$tpl->assign('STR_MONTH_FEB', core::getLanguage('str', 'month_feb'));
	$tpl->assign('STR_MONTH_MARCH', core::getLanguage('str', 'month_march'));
	$tpl->assign('STR_MONTH_APR', core::getLanguage('str', 'month_apr'));
	$tpl->assign('STR_MONTH_MAY', core::getLanguage('str', 'month_may'));
	$tpl->assign('STR_MONTH_JUN', core::getLanguage('str', 'month_jun'));
	$tpl->assign('STR_MONTH_JUL', core::getLanguage('str', 'month_jul'));
	$tpl->assign('STR_MONTH_AUG', core::getLanguage('str', 'month_aug'));
	$tpl->assign('STR_MONTH_SEP', core::getLanguage('str', 'month_sep'));
	$tpl->assign('STR_MONTH_OCT', core::getLanguage('str', 'month_oct'));
	$tpl->assign('STR_MONTH_NOV', core::getLanguage('str', 'month_nov')) ;
	$tpl->assign('STR_MONTH_DEC', core::getLanguage('str', 'month_dec'));
	$tpl->assign('STR_SPORT_TYPE', core::getLanguage('str', 'sport_type'));
	$tpl->assign('STR_LEVEL', core::getLanguage('str', 'sport_level'));
	$tpl->assign('STR_NO', core::getLanguage('str', 'no'));
	$tpl->assign('STR_JOB_YEAR_START', core::getLanguage('str', 'job_year_start'));
	$tpl->assign('STR_JOB_YEAR_FINISH', core::getLanguage('str', 'job_year_finish'));

	

	foreach($data->getSportLevelList() as $row){	
		$rowBlock = $tpl->fetch('OPTION_SPORT_LEVEL');	
		$rowBlock->assign('ID_LEVEL', $row['id']);	
		$rowBlock->assign('LEVEL_NAME', $row['name']);		
		$tpl->assign('OPTION_SPORT_LEVEL', $rowBlock);
	}
	
	$tpl->assign('STR_SEARCH_TEAM', core::getLanguage('str', 'search_team'));
	$tpl->assign('STR_ABOUT_SPORT', core::getLanguage('str', 'about_sport'));

	$current_year = date("Y");

	for($i=$current_year; $i>1949; $i--){
		$rowBlock = $tpl->fetch('OPTION_YEARS_LIST_START');	
		$rowBlock->assign('YEAR', $i);	
		$tpl->assign('OPTION_YEARS_LIST_START', $rowBlock);
	}

	for($i=$current_year; $i>1949; $i--){
		$rowBlock = $tpl->fetch('OPTION_YEARS_LIST_END');	
		$rowBlock->assign('YEAR', $i);	
		$tpl->assign('OPTION_YEARS_LIST_END', $rowBlock);
	}

	for($i=$current_year; $i>1949; $i--){
		$rowBlock = $tpl->fetch('OPTION_JOB_YEARS_LIST_START');	
		$rowBlock->assign('YEAR', $i);	
		$tpl->assign('OPTION_JOB_YEARS_LIST_START', $rowBlock);
	}

	for($i=$current_year; $i>1949; $i--){
		$rowBlock = $tpl->fetch('OPTION_JOB_YEARS_LIST_END');	
		$rowBlock->assign('YEAR', $i);	
		$tpl->assign('OPTION_JOB_YEARS_LIST_END', $rowBlock);
	}

	$tpl->assign('STR_BIRTHDAY_DATE_FORMAT', core::getLanguage('str', 'birthday_date_format'));
	$tpl->assign('STR_LEVEL', core::getLanguage('str', 'level'));
	$tpl->assign('SAVE_CHANGES', core::getLanguage('button', 'save_changes'));
	$tpl->assign('BUTTON_EDIT_PHOTO', core::getLanguage('button','edit_photo'));
	$tpl->assign('BUTTON_CHANGE_COVER', core::getLanguage('button', 'change_cover'));
	$tpl->assign('BUTTON_REMOVE_EDUCATION', core::getLanguage('button', 'remove_education'));
	$tpl->assign('BUTTON_REMOVE_SPORT_TYPE', core::getLanguage('button', 'remove_sport_type'));
	$tpl->assign('BUTTON_ADD_NEW_EDUCATION', core::getLanguage('button', 'add_new_education'));
	$tpl->assign('BUTTON_ADD_SPORT_TYPE', core::getLanguage('button', 'add_new_sport_type'));
	$tpl->assign('BUTTON_ADD_NEW_JOB', core::getLanguage('button', 'add_new_job'));
	$tpl->assign('BUTTON_REMOVE_JOB', core::getLanguage('button', 'remove_job'));
	$tpl->assign('EDIT_COVER', 'yes');

	foreach(Occupation::getOccupationsList($user['id'], 1) as $row){
		$rowEducationBlock = $tpl->fetch('EDUCATION_ROW');	
		$rowEducationBlock->assign('STR_EDUCATION_PLACE', core::getLanguage('str', 'education_place'));	
		$rowEducationBlock->assign('STR_EDUCATION_NAME', core::getLanguage('str', 'education_name'));	
		$rowEducationBlock->assign('STR_EDUCATION_DESCRIPTION', core::getLanguage('str', 'education_description'));	
		$rowEducationBlock->assign('STR_EDUCATION_MONTH_START', core::getLanguage('str', 'education_month_start'));		
		$rowEducationBlock->assign('STR_EDUCATION_YEAR_START', core::getLanguage('str', 'education_year_start'));	
		$rowEducationBlock->assign('STR_EDUCATION_MONTH_FINISH', core::getLanguage('str', 'education_month_finish'));	
		$rowEducationBlock->assign('STR_EDUCATION_YEAR_FINISH', core::getLanguage('str', 'education_year_finish'));	
		$rowEducationBlock->assign('BUTTON_REMOVE_EDUCATION', core::getLanguage('button', 'remove_education'));	
		$rowEducationBlock->assign('STR_MONTH_JAN', core::getLanguage('str', 'month_jan'));	
		$rowEducationBlock->assign('STR_MONTH_FEB', core::getLanguage('str', 'month_feb'));	
		$rowEducationBlock->assign('STR_MONTH_MARCH', core::getLanguage('str', 'month_march'));	
		$rowEducationBlock->assign('STR_MONTH_APR', core::getLanguage('str', 'month_apr'));	
		$rowEducationBlock->assign('STR_MONTH_MAY', core::getLanguage('str', 'month_may'));	
		$rowEducationBlock->assign('STR_MONTH_JUN', core::getLanguage('str', 'month_jun'));	
		$rowEducationBlock->assign('STR_MONTH_JUL', core::getLanguage('str', 'month_jul'));	
		$rowEducationBlock->assign('STR_MONTH_AUG', core::getLanguage('str', 'month_aug'));	
		$rowEducationBlock->assign('STR_MONTH_SEP', core::getLanguage('str', 'month_sep'));	
		$rowEducationBlock->assign('STR_MONTH_OCT', core::getLanguage('str', 'month_oct'));	
		$rowEducationBlock->assign('STR_MONTH_NOV', core::getLanguage('str', 'month_nov'));	
		$rowEducationBlock->assign('STR_MONTH_DEC', core::getLanguage('str', 'month_dec'));
	
		for($n=$current_year; $n>1949; $n--){
			$rowBlock = $rowEducationBlock->fetch('EDUCATION_ROW_OPTION_YEARS_LIST_START');				
			$rowBlock->assign('YEAR', $n);	
			$rowBlock->assign('STR_NO', core::getLanguage('str', 'no'));
			$rowBlock->assign('EDUCATION_YEAR_START', $row['year_start']);			
			$rowEducationBlock->assign('EDUCATION_ROW_OPTION_YEARS_LIST_START', $rowBlock);
		}	
	
		$rowEducationBlock->assign('ID', $row['id']);
		$rowEducationBlock->assign('NAME', $row['name']);
		$rowEducationBlock->assign('DESCRIPTION', $row['description']);		
		$rowEducationBlock->assign('MONTH_START', $row['month_start']);		
		$rowEducationBlock->assign('MONTH_FINISH', $row['month_finish']);		
		$rowEducationBlock->assign('PLACE', $row['city'] ? $row['city'] :  core::getLanguage('str', 'empty'));	
		$rowEducationBlock->assign('ID_PLACE', Places::getTargetPlaceId($row['id'], 'occupation'));
		
		for($n=$current_year; $n>1949; $n--){
			$rowBlock = $rowEducationBlock->fetch('EDUCATION_ROW_OPTION_YEARS_LIST_FINISH');	
			
			$rowBlock->assign('YEAR', $n);	
			$rowBlock->assign('STR_NO', core::getLanguage('str', 'no'));
			$rowBlock->assign('EDUCATION_YEAR_FINISH', $row['year_finish']);	
			
			$rowEducationBlock->assign('EDUCATION_ROW_OPTION_YEARS_LIST_FINISH', $rowBlock);
		}		
		
		$rowEducationBlock->assign('STR_NO', core::getLanguage('str', 'no'));		
		$tpl->assign('EDUCATION_ROW', $rowEducationBlock);	
	}

	foreach(Occupation::getOccupationsList($user['id'], 3) as $row){
		$rowJobBlock = $tpl->fetch('JOB_ROW');	
		$rowJobBlock->assign('STR_JOB_PLACE', core::getLanguage('str', 'job_place'));	
		$rowJobBlock->assign('STR_JOB_NAME', core::getLanguage('str', 'job_name'));	
		$rowJobBlock->assign('STR_JOB_DESCRIPTION', core::getLanguage('str', 'job_description'));	
		$rowJobBlock->assign('STR_JOB_MONTH_START', core::getLanguage('str', 'job_month_start'));		
		$rowJobBlock->assign('STR_JOB_YEAR_START', core::getLanguage('str', 'job_year_start'));	
		$rowJobBlock->assign('STR_JOB_MONTH_FINISH', core::getLanguage('str', 'job_month_finish'));	
		$rowJobBlock->assign('STR_JOB_YEAR_FINISH', core::getLanguage('str', 'job_year_finish'));	
		$rowJobBlock->assign('BUTTON_REMOVE_JOB', core::getLanguage('button', 'remove_job'));	
		$rowJobBlock->assign('STR_MONTH_JAN', core::getLanguage('str', 'month_jan'));	
		$rowJobBlock->assign('STR_MONTH_FEB', core::getLanguage('str', 'month_feb'));	
		$rowJobBlock->assign('STR_MONTH_MARCH', core::getLanguage('str', 'month_march'));	
		$rowJobBlock->assign('STR_MONTH_APR', core::getLanguage('str', 'month_apr'));	
		$rowJobBlock->assign('STR_MONTH_MAY', core::getLanguage('str', 'month_may'));	
		$rowJobBlock->assign('STR_MONTH_JUN', core::getLanguage('str', 'month_jun'));	
		$rowJobBlock->assign('STR_MONTH_JUL', core::getLanguage('str', 'month_jul'));	
		$rowJobBlock->assign('STR_MONTH_AUG', core::getLanguage('str', 'month_aug'));	
		$rowJobBlock->assign('STR_MONTH_SEP', core::getLanguage('str', 'month_sep'));	
		$rowJobBlock->assign('STR_MONTH_OCT', core::getLanguage('str', 'month_oct'));	
		$rowJobBlock->assign('STR_MONTH_NOV', core::getLanguage('str', 'month_nov'));	
		$rowJobBlock->assign('STR_MONTH_DEC', core::getLanguage('str', 'month_dec'));
	
		for($n=$current_year; $n>1949; $n--){
			$rowBlock = $rowJobBlock->fetch('JOB_ROW_OPTION_YEARS_LIST_START');				
			$rowBlock->assign('YEAR', $n);	
			$rowBlock->assign('STR_NO', core::getLanguage('str', 'no'));
			$rowBlock->assign('JOB_YEAR_START', $row['year_start']);			
			$rowJobBlock->assign('JOB_ROW_OPTION_YEARS_LIST_START', $rowBlock);
		}	
	
		$rowJobBlock->assign('ID', $row['id']);
		$rowJobBlock->assign('NAME', $row['name']);	
		$rowJobBlock->assign('DESCRIPTION',$row['description']);			
		$rowJobBlock->assign('MONTH_START', $row['month_start']);		
		$rowJobBlock->assign('MONTH_FINISH', $row['month_finish']);
		$rowJobBlock->assign('PLACE', $row['city'] ? $row['city'] :  core::getLanguage('str', 'empty'));
		$rowJobBlock->assign('ID_PLACE', Places::getTargetPlaceId($row['id'], 'occupation'));	
		
		for($n=$current_year; $n>1949; $n--){
			$rowBlock = $rowJobBlock->fetch('JOB_ROW_OPTION_YEARS_LIST_FINISH');			
			$rowBlock->assign('YEAR', $n);	
			$rowBlock->assign('STR_NO', core::getLanguage('str', 'no'));
			$rowBlock->assign('JOB_YEAR_FINISH', $row['year_finish']);				
			$rowJobBlock->assign('JOB_ROW_OPTION_YEARS_LIST_FINISH', $rowBlock);
		}

		$rowJobBlock->assign('STR_NO', core::getLanguage('str', 'no'));		
		$tpl->assign('JOB_ROW', $rowJobBlock);	
	}

	foreach($data->getAchivmentsList($user['id']) as $row){
		$rowAchivmentsBlock = $tpl->fetch('ACHIVMENTS_ROW');	
		$rowAchivmentsBlock->assign('ID', $row['id']);	
		$rowAchivmentsBlock->assign('SPORT_TYPE', $row['sport_type']);	
		$rowAchivmentsBlock->assign('STR_NO', core::getLanguage('str', 'no'));
		$rowAchivmentsBlock->assign('STR_SPORT_TYPE', core::getLanguage('str', 'sport_type'));		
		$rowAchivmentsBlock->assign('STR_LEVEL', core::getLanguage('str', 'level'));		
		$rowAchivmentsBlock->assign('STR_SEARCH_TEAM', core::getLanguage('str', 'search_team'));		
		$rowAchivmentsBlock->assign('SEARCH_TEAM', $row['search_team']);		
		
		foreach($data->getSportLevelList() as $row2){
			$rowBlock = $rowAchivmentsBlock->fetch('ACHIVMENTS_OPTION_SPORT_LEVEL');	
			$rowBlock->assign('ID_LEVEL', $row2['id']);	
			$rowBlock->assign('LEVEL_NAME', $row2['name']);
			$rowBlock->assign('ACHIVMENTS_ID_LEVEL', $row['sport_level_id']);
			$rowAchivmentsBlock->assign('ACHIVMENTS_OPTION_SPORT_LEVEL', $rowBlock);		
		}			
		
		$rowAchivmentsBlock->assign('BUTTON_REMOVE_SPORT_TYPE', core::getLanguage('button', 'remove_sport_type'));			
		$tpl->assign('ACHIVMENTS_ROW', $rowAchivmentsBlock);
	}

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

	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";

	$tpl->assign('AVATAR', $profile_avatar);
	$tpl->assign('FIRSTNAME', $user['firstname']);
	$tpl->assign('LASTNAME', $user['lastname']);
	$tpl->assign('SECONDNAME', $user['secondname']);
	$tpl->assign('ABOUT', $user['about']);
	$tpl->assign('BIRTHDAY', $user['user_birthday']);
	$tpl->assign('CITY_NAME', $user['city'] ? $user['city'] :  core::getLanguage('str', 'empty'));
	$tpl->assign('OPTION_SEX', $user['sex']);
	$tpl->assign('ABOUT_SPORT', $user['about_sport']);

	include_once "footer.inc";
		
	$tpl->display();
