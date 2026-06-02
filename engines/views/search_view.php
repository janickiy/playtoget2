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

	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";

	$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'search'));	
	$tpl->assign('STR_SHOW_MORE', core::getLanguage('str', 'show_more'));

	$tpl->assign('STR_GROUPS', core::getLanguage('str', 'groups'));
	$tpl->assign('STR_TEAMS', core::getLanguage('str', 'teams'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));
	$tpl->assign('STR_SEARCHING_RESULTS', core::getLanguage('str', 'searching_results'));
	$tpl->assign('STR_USERS', core::getLanguage('str', 'users'));

	if($_GET['q'] == 'all_search'){
		$tpl->assign('QUERY', $_GET['q']);	
		$tpl->assign('SEARCH', urldecode(core::database()->escape(Core_Array::getRequest('search'))));
	
		$id_place = core::database()->escape(Core_Array::getRequest('id_place'));
		$id_sport = core::database()->escape(Core_Array::getRequest('id_sport'));
		
		if(is_numeric($id_sport)) $sport = Sport::getSportType($id_sport);
		if(is_numeric($id_place)) $place = Places::getCityInfo($id_place);
		
		$searh_true = false;
		$arr_groups = Communities::getAllCommunitiesList('group', 5, 0);

		if($arr_groups){
	
			$tpl->assign('SHOW_GROUP_SEARCH_RESULT', 'show');
			$searh_true = true;
			
			foreach($arr_groups as $row){
				$rowBlock = $tpl->fetch('group_search_result_row');
				$rowBlock->assign('ID', $row['community_id']);
				$rowBlock->assign('NAME', $row['name']);	
				$rowBlock->assign('ABOUT', $row['about']);						
				$rowBlock->assign('CITY', $row['place']);
				$rowBlock->assign('SPORT_TYPE', $row['sport_type']);	

				$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countMemberCommunity($row['community_id'], 2), core::getLanguage('str', 'participants_friends')));
					
				if(Communities::checkOwnerCommunity($row['community_id'], $user['id'])){
					$rowBlock->assign('ALLOW_EDIT', 'yes');
					$rowBlock->assign('OWNER_COMMUNITY', 'yes');
				} 					
	
				$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($row));		
				$rowBlock->assign('MEMBER_STATUS', Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $user['id'])));			
				$tpl->assign('group_search_result_row', $rowBlock);		
			}	
		}

		$arr_teams = Communities::getAllCommunitiesList('team', 5, 0);

		if($arr_teams){
			$searh_true = true;
			$tpl->assign('SHOW_TEAM_SEARCH_RESULT', 'show');
	
			foreach($arr_teams as $row){
				$rowBlock = $tpl->fetch('team_search_result_row');
				$rowBlock->assign('ID', $row['community_id']);
				$rowBlock->assign('NAME', $row['name']);	
				$rowBlock->assign('ABOUT', $row['about']);						
				$rowBlock->assign('CITY', $row['place']);
				$rowBlock->assign('SPORT_TYPE', $row['sport_type']);					

				$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countMemberCommunity($row['community_id'], 2), core::getLanguage('str', 'participants_friends')));
					
				if(Communities::checkOwnerCommunity($row['community_id'], $user['id'])){
					$rowBlock->assign('ALLOW_EDIT', 'yes');
					$rowBlock->assign('OWNER_COMMUNITY', 'yes');
				} 					
	
				$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($row));		
				$rowBlock->assign('MEMBER_STATUS', Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $user['id'])));
			
				$tpl->assign('team_search_result_row', $rowBlock);		
			}	
		}

		$arr_shops = SportBlocks::getSportBlocksList('shop', 5, 0);

		if($arr_shops){
			$searh_true = true;
			$tpl->assign('SHOW_SHOPS_SEARCH_RESULT', 'show');
	
			foreach($arr_shops as $row){
				$avatar = ($row['avatar'] && file_exists(PATH_SPORTBLOCKS_AVATAR_IMAGES . $row['avatar'])) ? PATH_SPORTBLOCKS_AVATAR_IMAGES . $row['avatar'] : 'templates/images/default_group.png';
		
				$rowBlock = $tpl->fetch('shop_search_result_row');
			
				$rowBlock->assign('ID', $row['id']);		
				$rowBlock->assign('NAME', $row['name']);				
				$rowBlock->assign('PLACE', $row['place']);				
				$rowBlock->assign('ABOUT', $row['about']);				
				$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				$rowBlock->assign('AVATAR', core::documentparser()->sportblockAvatar($row['avatar']));	
				$tpl->assign('shop_search_result_row', $rowBlock);	
			}
		}

		$arr_playgrounds = SportBlocks::getSportBlocksList('playground', 5, 0);

		if($arr_playgrounds){
			$searh_true = true;
			$tpl->assign('SHOW_PLAYGROUND_SEARCH_RESULT', 'show');
	
			foreach($arr_playgrounds as $row){
				$avatar = ($row['avatar'] && file_exists(PATH_SPORTBLOCKS_AVATAR_IMAGES . $row['avatar'])) ? PATH_SPORTBLOCKS_AVATAR_IMAGES . $row['avatar'] : 'templates/images/default_group.png';
				$rowBlock = $tpl->fetch('playground_search_result_row');	

				if(SportBlocks::checkOwner($row["id"], $user['id'])) $rowBlock->assign('SHOW_EDIT_FORM', 'show');
				
				$rowBlock->assign('ID', $row['id']);		
				$rowBlock->assign('NAME', $row['name']);				
				$rowBlock->assign('PLACE', $row['place']);				
				$rowBlock->assign('ABOUT', $row['about']);				
				$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				$rowBlock->assign('AVATAR', core::documentparser()->sportblockAvatar($row['avatar']));				

				$tpl->assign('playground_search_result_row', $rowBlock);		
			}
		}

		$arr_fitness = SportBlocks::getSportBlocksList('fitness', 5, 0);

		if($arr_fitness){
			$searh_true = true;
			$tpl->assign('SHOW_FITNES_SEARCH_RESULT', 'show');
	
			foreach($arr_fitness as $row){
				$avatar = ($row['avatar'] && file_exists(PATH_SPORTBLOCKS_AVATAR_IMAGES . $row['avatar'])) ? PATH_SPORTBLOCKS_AVATAR_IMAGES . $row['avatar'] : 'templates/images/default_group.png';
		
				$rowBlock = $tpl->fetch('fitnes_search_result_row');				
				$rowBlock->assign('ID', $row['id']);		
				$rowBlock->assign('NAME', $row['name']);				
				$rowBlock->assign('PLACE', $row['place']);				
				$rowBlock->assign('ABOUT', $row['about']);				
				$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				$rowBlock->assign('AVATAR', core::documentparser()->sportblockAvatar($row['avatar']));
				$tpl->assign('fitnes_search_result_row', $rowBlock);		
			}
		}
	
		$arr_users = $data->getUsersList(10, 0);
	
		if($arr_users){
			$searh_true = true;
			$tpl->assign('SHOW_USER_SEARCH_RESULT', 'show');
	
			foreach($arr_users as $row){
				$rowBlock = $tpl->fetch('users_search_result_row');
				$rowBlock->assign('ID', $row['id']);	
				$rowBlock->assign('ID_USER', $user['id']);
				$rowBlock->assign('SEL', $user['id']);
				$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));
				$rowBlock->assign('FIRSTNAME', $row['firstname']);					
				$rowBlock->assign('LASTNAME', $row['lastname']);
				$rowBlock->assign('CITY', $row['place']);	
				$rowBlock->assign('STR_SEND_MESSAGE', core::getLanguage('str', 'send_message'));	
				$rowBlock->assign('STR_VIEW_FRIENDS', core::getLanguage('str', 'view_riends'));	
				$rowBlock->assign('STR_REMOVE_FRIEND', core::getLanguage('str', 'add_as_friend'));	
				$tpl->assign('users_search_result_row', $rowBlock);		
			}
		}
	
		$arr_events = Events::getEventsList(5, 0);
	
		if($arr_events){
			$tpl->assign('SHOW_EVENT_SEARCH_RESULT', 'show');
			$searh_true = true;
			
			foreach($arr_events as $row){
				$rowBlock = $tpl->fetch('events_search_result_row');				
				$rowBlock->assign('ID', $row['event_id']);	
				$rowBlock->assign('NAME', $row['name']);
				$rowBlock->assign('AVATAR', core::documentparser()->eventAvatar($row['cover_page']));		
				$rowBlock->assign('SPORT_TYPE', $row['sport_type']);
				$rowBlock->assign('CITY', $row['place']);
				$rowBlock->assign('DATE', core::documentparser()->mysql_russian_datetime($row['date']));
				$rowBlock->assign('DESCRIPTION', $row['description']);
				$rowBlock->assign('PARTICIPANTS_FRIENDS', str_replace('%MEMBERS%', Events::countMembers($row['event_id']), core::getLanguage('str', 'participants_friends')));			
				$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));				
				$rowBlock->assign('STATUS', Events::getEventStatus($row['event_id']) ? core::getLanguage('str', 'event_continues') : core::getLanguage('str', 'event_completed'));	
				$tpl->assign('events_search_result_row', $rowBlock);	
			}		
		}
		
		if (!$searh_true) $tpl->assign('SEARCH_NOTHING_RESULT', core::getLanguage('str', 'search_nothing_result'));
	}
	else if($_GET['q'] == 'group'){
		$tpl->assign('QUERY', $_GET['q']);
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
		$tpl->assign('COMMUNITY_TYPE', 'group');	
		$tpl->assign('STR_SEARCH_COMMUNITY_IN_CITY', core::getLanguage('str', 'looking_for_group_in_city'));	
		$tpl->assign('STR_SEARCH_SPORT_TYPE', core::getLanguage('str', 'looking_for_sport_type'));	
		$tpl->assign('STR_LOOKING_FOR_COMMUNITY_IN_CITY', core::getLanguage('str', 'looking_for_group_in_city'));
		$tpl->assign('BUTTON_CREATE_COMMUNITY', core::getLanguage('button', 'create_group'));
		$tpl->assign('STR_KEYWORD', core::getLanguage('str', 'keyword'));	
		$tpl->assign('BUTTON_SEARCH_COMMUNITY', core::getLanguage('button', 'search_group'));	
		$tpl->assign('SELECTED_ID_PLACE', $_REQUEST['id_place']);		
		$tpl->assign('SELECTED_ID_SPORT_TYPE', $_REQUEST['id_sport_type']);	
		$tpl->assign('SEARCH', urldecode(core::database()->escape(Core_Array::getRequest('search'))));		
		$tpl->assign('PLACE', urldecode(core::database()->escape(Core_Array::getRequest('place'))));
		$tpl->assign('SPORT', urldecode(core::database()->escape(Core_Array::getRequest('sport'))));
		
		$tpl->assign('CREATE_COMMUNITY', './?task=groups&q=create');
	
		$arr_communities = Communities::getAllCommunitiesList('group', 10, 0);

		if($arr_communities){
			foreach($arr_communities as $row){
				$rowBlock = $tpl->fetch('row_group_list');	
	
				$rowBlock->assign('ID', $row['community_id']);
				$rowBlock->assign('NAME', $row['name']);	
				$rowBlock->assign('ABOUT', $row['about']);						
				$rowBlock->assign('CITY', $row['place']);
				$rowBlock->assign('SPORT_TYPE', $row['sport_type']);
				$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countMemberCommunity($row['community_id'], 2), core::getLanguage('str', 'participants_friends')));
					
				if(Communities::checkOwnerCommunity($row['community_id'], $user['id'])){
					$rowBlock->assign('ALLOW_EDIT', 'yes');
					$rowBlock->assign('OWNER_COMMUNITY', 'yes');
				} 					
	
				$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($row));		
				$rowBlock->assign('MEMBER_STATUS', Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $user['id'])));			
				
				$tpl->assign('row_group_list', $rowBlock);
			}		
		}else $tpl->assign('NOTHING_FOUND', core::getLanguage('msg', 'not_found'));
	}
	else if($_GET['q'] == 'team'){
		$tpl->assign('QUERY', $_GET['q']);
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);	
		$tpl->assign('COMMUNITY_TYPE', 'team');	
		$tpl->assign('STR_SEARCH_COMMUNITY_IN_CITY', core::getLanguage('str', 'looking_for_team_in_city'));	
		$tpl->assign('STR_SEARCH_SPORT_TYPE', core::getLanguage('str', 'looking_for_sport_type'));	
		$tpl->assign('BUTTON_CREATE_COMMUNITY', core::getLanguage('button', 'create_team'));
		$tpl->assign('STR_KEYWORD', core::getLanguage('str', 'keyword'));	
		$tpl->assign('SELECTED_ID_PLACE', $_REQUEST['id_place']);		
		$tpl->assign('SELECTED_ID_SPORT_TYPE', $_REQUEST['id_sport_type']);	
		$tpl->assign('SEARCH', urldecode(core::database()->escape(Core_Array::getRequest('search'))));
		$tpl->assign('PLACE', urldecode(core::database()->escape(Core_Array::getRequest('place'))));
		$tpl->assign('SPORT', urldecode(core::database()->escape(Core_Array::getRequest('sport'))));
		$tpl->assign('CREATE_COMMUNITY', './?task=teams&q=create');
	
		$arr_communities = Communities::getAllCommunitiesList('team', 10, 0);	
	
		if($arr_communities){
			foreach($arr_communities as $row){		
				$rowBlock = $tpl->fetch('row_team_list');	
				$rowBlock->assign('ID', $row['community_id']);
				$rowBlock->assign('NAME', $row['name']);	
				$rowBlock->assign('ABOUT', $row['about']);						
				$rowBlock->assign('CITY', $row['place']);
				$rowBlock->assign('SPORT_TYPE', $row['sport_type']);	
				$rowBlock->assign('STR_MEMBER', str_replace('%MEMBERS%', Communities::countMemberCommunity($row['community_id'], 2), core::getLanguage('str', 'participants_friends')));
					
				if(Communities::checkOwnerCommunity($row['community_id'], $user['id'])){
					$rowBlock->assign('ALLOW_EDIT', 'yes');
					$rowBlock->assign('OWNER_COMMUNITY', 'yes');
				} 					
	
				$rowBlock->assign('AVATAR', core::documentparser()->communityAvatar($row));		
				$rowBlock->assign('MEMBER_STATUS', Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $user['id'])));			
				$tpl->assign('row_team_list', $rowBlock);
			}
		}else $tpl->assign('NOTHING_FOUND', core::getLanguage('msg', 'not_found'));
	}
	else if($_GET['q'] == 'event'){
		$tpl->assign('QUERY', $_GET['q']);
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);	
		$tpl->assign('BUTTON_CREATE_EVENT', core::getLanguage('button', 'create_event'));	
		$tpl->assign('STR_KEYWORD', core::getLanguage('str', 'keyword'));
		$tpl->assign('STR_LOOKING_FOR_EVENT_IN_CITY', core::getLanguage('str', 'looking_for_event_in_city'));		
		$tpl->assign('STR_LOOKING_FOR_SPORT_TYPE', core::getLanguage('str', 'looking_for_sport_type'));		
		$tpl->assign('SEARCH', urldecode(core::database()->escape(Core_Array::getRequest('search'))));
		$tpl->assign('PLACE', urldecode(core::database()->escape(Core_Array::getRequest('place'))));
		$tpl->assign('SPORT', urldecode(core::database()->escape(Core_Array::getRequest('sport'))));
		
		$arr_events = Events::getEventsList(10, 0);
		
		if($arr_events){
			foreach($arr_events as $row){
				$rowBlock = $tpl->fetch('row_events_list');	
				$rowBlock->assign('ID', $row['event_id']);	
				$rowBlock->assign('NAME', $row['name']);
				$rowBlock->assign('AVATAR', core::documentparser()->eventAvatar($row['cover_page']));		
				$rowBlock->assign('SPORT_TYPE', $row['sport_type']);
				$rowBlock->assign('CITY', $row['place']);
				$rowBlock->assign('DATE', core::documentparser()->mysql_russian_datetime($row['date']));
				$rowBlock->assign('DESCRIPTION', $row['description']);
				$rowBlock->assign('PARTICIPANTS_FRIENDS', str_replace('%MEMBERS%', Events::countMembers($row['event_id']), core::getLanguage('str', 'participants_friends')));			
				$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));				
				$rowBlock->assign('STATUS', Events::getEventStatus($row['event_id']) ? core::getLanguage('str', 'event_continues') : core::getLanguage('str', 'event_completed'));	
				$tpl->assign('row_events_list', $rowBlock);
			}		
		}else $tpl->assign('NOTHING_FOUND', core::getLanguage('msg', 'not_found'));	
	}
	else if($_GET['q'] == 'shop'){
	
		$tpl->assign('QUERY', $_GET['q']);	
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);	
		$tpl->assign('STR_SEARCH_SPORT_BLOCKS_IN_CITY', core::getLanguage('str', 'looking_for_shop_in_city'));		
		$tpl->assign('STR_KEYWORD', core::getLanguage('str', 'search_shop_by_name'));		
		$tpl->assign('BUTTON_CREATE_SPORT_BLOCKS', core::getLanguage('button', 'create_shop'));	
		$tpl->assign('CREATE_SPORT_BLOCKS', './?task=shops&q=create');	
		$tpl->assign('SEARCH', urldecode(core::database()->escape(Core_Array::getRequest('search'))));
		$tpl->assign('PLACE', urldecode(core::database()->escape(Core_Array::getRequest('place'))));
		$tpl->assign('SPORT_BLOCKS_TYPE', 'shop');
	
		$arr_sportblocks = SportBlocks::getSportBlocksList('shop', 10, 0);
	
		if($arr_sportblocks){
			foreach($arr_sportblocks as $row){
				$rowBlock = $tpl->fetch('row_shop_block');	

				if(SportBlocks::checkOwner($row["id"], $user['id'])) $rowBlock->assign('SHOW_EDIT_FORM', 'show');
				
				$rowBlock->assign('ID', $row['id']);		
				$rowBlock->assign('NAME', $row['name']);				
				$rowBlock->assign('PLACE', $row['place']);				
				$rowBlock->assign('ABOUT', $row['about']);				
				$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				$rowBlock->assign('AVATAR', core::documentparser()->sportblockAvatar($row['avatar']));			
				$tpl->assign('row_shop_block', $rowBlock);
			}		
		}else $tpl->assign('NOTHING_FOUND', core::getLanguage('msg', 'not_found'));	
	}	
	else if($_GET['q'] == 'playground'){	
		$tpl->assign('QUERY', $_GET['q']);
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);	
		$tpl->assign('STR_SEARCH_SPORT_BLOCKS_IN_CITY', core::getLanguage('str', 'looking_for_playground_in_city'));		
		$tpl->assign('STR_KEYWORD', core::getLanguage('str', 'search_playground_by_name'));		
		$tpl->assign('BUTTON_CREATE_SPORT_BLOCKS', core::getLanguage('button', 'create_playground'));	
		$tpl->assign('CREATE_SPORT_BLOCKS', './?task=playgrounds&q=create');	
		$tpl->assign('SEARCH', urldecode(core::database()->escape(Core_Array::getRequest('search'))));
		$tpl->assign('PLACE', urldecode(core::database()->escape(Core_Array::getRequest('place'))));
		$tpl->assign('SPORT_BLOCKS_TYPE', 'playground');

		$arr_sportblocks = SportBlocks::getSportBlocksList('playground', 10, 0);
	
		if($arr_sportblocks){
			foreach($arr_sportblocks as $row){
				$rowBlock = $tpl->fetch('row_playground_block');	

				if(SportBlocks::checkOwner($row["id"], $user['id'])) $rowBlock->assign('SHOW_EDIT_FORM', 'show');
				
				$rowBlock->assign('ID', $row['id']);		
				$rowBlock->assign('NAME', $row['name']);				
				$rowBlock->assign('PLACE', $row['place']);				
				$rowBlock->assign('ABOUT', $row['about']);				
				$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				$rowBlock->assign('AVATAR', core::documentparser()->sportblockAvatar($row['avatar']));	
				
				$tpl->assign('row_playground_block', $rowBlock);
			}	
		}else $tpl->assign('NOTHING_FOUND', core::getLanguage('msg', 'not_found'));	
	}
	else if($_GET['q'] == 'fitness'){	
		$tpl->assign('QUERY', $_GET['q']);	
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);	
		$tpl->assign('STR_SEARCH_SPORT_BLOCKS_IN_CITY', core::getLanguage('str', 'looking_for_fitness_in_city'));		
		$tpl->assign('STR_KEYWORD', core::getLanguage('str', 'search_fitness_by_name'));		
		$tpl->assign('BUTTON_CREATE_SPORT_BLOCKS', core::getLanguage('button', 'create_fitness'));	
		$tpl->assign('CREATE_SPORT_BLOCKS', './?task=fitness&q=create');	
		$tpl->assign('SEARCH', urldecode(core::database()->escape(Core_Array::getRequest('search'))));
		$tpl->assign('PLACE', urldecode(core::database()->escape(Core_Array::getRequest('place'))));
		$tpl->assign('SPORT_BLOCKS_TYPE', 'fitness');	
	
		$arr_sportblocks = SportBlocks::getSportBlocksList('fitness', 10, 0);
	
		if($arr_sportblocks){
			foreach($arr_sportblocks as $row){
				$rowBlock = $tpl->fetch('row_fitnes_block');	

				if(SportBlocks::checkOwner($row["id"], $user['id'])) $rowBlock->assign('SHOW_EDIT_FORM', 'show');
				
				$rowBlock->assign('ID', $row['id']);		
				$rowBlock->assign('NAME', $row['name']);				
				$rowBlock->assign('PLACE', $row['place']);				
				$rowBlock->assign('ABOUT', $row['about']);				
				$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
				$rowBlock->assign('AVATAR', core::documentparser()->sportblockAvatar($row['avatar']));	
		
				$tpl->assign('row_fitnes_block', $rowBlock);
			}		
		}else $tpl->assign('NOTHING_FOUND', core::getLanguage('msg', 'not_found'));	
	}
	else if($_GET['q'] == 'user'){
		$tpl->assign('QUERY', $_GET['q']);	
		$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
		$tpl->assign('SEARCH', urldecode(core::database()->escape(Core_Array::getRequest('search'))));		
		$tpl->assign('SEX', urldecode(core::database()->escape(Core_Array::getRequest('sex'))));		
		$tpl->assign('PLACE', urldecode(core::database()->escape(Core_Array::getRequest('place'))));		
		$tpl->assign('SPORT', urldecode(core::database()->escape(Core_Array::getRequest('sport'))));
		$tpl->assign('PHOTO', Core_Array::getRequest('photo'));
		
		if(is_numeric($_REQUEST['min_age'])) $tpl->assign('MIN_AGE', Core_Array::getRequest('min_age'));		
		if(is_numeric($_REQUEST['max_age'])) $tpl->assign('MAX_AGE', Core_Array::getRequest('max_age'));		
	
		$arr_user = $data->getUsersList(10, 0);
	
		if($arr_user){
			foreach($arr_user as $row){
				$rowBlock = $tpl->fetch('row_users');
				$rowBlock->assign('ID', $row['id']);	
				$rowBlock->assign('ID_USER', $user['id']);
				$rowBlock->assign('SEL', $row['id']);
				$rowBlock->assign('AVATAR', core::documentparser()->userAvatar($row));
				$rowBlock->assign('FIRSTNAME', $row['firstname']);					
				$rowBlock->assign('LASTNAME', $row['lastname']);
				$rowBlock->assign('CITY', $row['city']);	
				$rowBlock->assign('STR_SEND_MESSAGE', core::getLanguage('str', 'send_message'));	
				$rowBlock->assign('STR_VIEW_FRIENDS', core::getLanguage('str', 'view_riends'));	
				$rowBlock->assign('STR_REMOVE_FRIEND', core::getLanguage('str', 'add_as_friend'));	
				$tpl->assign('row_users', $rowBlock);
			}
		}else $tpl->assign('NOTHING_FOUND', core::getLanguage('msg', 'not_found'));
	}

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));

	include_once "footer.inc";		
			
	$tpl->display();