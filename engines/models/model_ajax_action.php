<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_ajax_action extends Model
{
	public function addComment($fields)
	{
		return core::database()->insert($fields, core::database()->getTableName('comments'));
	}
	
	public function makeUpComment($id_comment)
	{
		$id_comment = core::database()->escape($id_comment);
		
		$query = "SELECT *, a.id AS id_comment, DATE_FORMAT(a.created_at,'%d.%m.%Y %H:%i') AS created, a.user_id AS user_id FROM " . core::database()->getTableName('comments') . " a LEFT JOIN  " . core::database()->getTableName('users') . " b ON a.user_id=b.id WHERE a.id=" . $id_comment;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);
	}
	
	public function liked($content_id, $likeable_type, $user_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('likes') . " WHERE (content_id=" . $content_id  . ") AND (user_id=" . $user_id . ") AND (likeable_type='" . $likeable_type . "')";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['user_id'] = $user_id;
			$fields['likeable_type'] = $likeable_type;
			$fields['content_id'] = $content_id;
			$fields['time'] = date("Y-m-d H:i:s");
		
			core::database()->insert($fields, core::database()->getTableName('likes'));
		}
		else{
			core::database()->delete(core::database()->getTableName('likes'), "(content_id=" . $content_id  . ") AND (user_id=" . $user_id . ") AND (likeable_type='" . $likeable_type . "')",'');
		}

		$query = "SELECT * FROM " . core::database()->getTableName('likes') . " WHERE content_id=" . $content_id  . " AND likeable_type='" . $likeable_type . "'";
		$result = core::database()->querySQL($query);
			
		return core::database()->getRecordCount($result);
	}
	
	public function shared($content_id, $shareable_type, $user_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('share') . " WHERE (content_id=" . $content_id  . ") AND (user_id=" . $user_id . ") AND (shareable_type='" . $shareable_type . "')";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['user_id'] = $user_id;
			$fields['shareable_type'] = $shareable_type;			
			$fields['time'] = date("Y-m-d H:i:s");			
			$fields['content_id'] = $content_id;
		
			core::database()->insert($fields, core::database()->getTableName('share'));
		}
		
		$query = "SELECT * FROM " . core::database()->getTableName('share') . " WHERE content_id=" . $content_id  . " AND shareable_type='" . $shareable_type . "'";
		$result = core::database()->querySQL($query);
			
		return core::database()->getRecordCount($result);
	}	
	
	
	public function getPopularPhotos($owner_id, $offset, $postnumbers)
	{
		$from = core::database()->getTableName('photos');
		$parameters = '*';
		$where = "WHERE owner_id=" . $owner_id ."";
		$limit = "LIMIT ".$postnumbers." OFFSET ".$offset."";
		$order = "ORDER by id DESC";
		
		$result = core::database()->select($parameters, $from, $where, $group, $order, $limit);
		
		return core::database()->getColumnArray($result);
	}
	
	public function addMessage($fields)
	{
		return core::database()->insert($fields, core::database()->getTableName('messages'));		
	}		
	
	public function makeUpMessage($id_message)
	{
		$id_message = core::database()->escape($id_message);
		
		$query = "SELECT *, a.id AS id_message, DATE_FORMAT(a.created_at,'%d.%m.%Y %H:%i') AS created, a.sender_id AS sender_id FROM " . core::database()->getTableName('messages') . " a LEFT JOIN  " . core::database()->getTableName('users') . " b ON a.sender_id=b.id WHERE a.id=" . $id_message;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);
	}

	public function getMessagesListAjax($offset, $number, $sender_id, $receiver_id)
	{
		
		$query = "SELECT *, a.id AS id, b.id AS user_id, DATE_FORMAT(a.created_at,'%d.%m.%y %H:%i') as created FROM " . core::database()->getTableName('messages') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.sender_id 
					WHERE (sender_id=" . $sender_id . " AND receiver_id=" . $receiver_id . " AND status IN (0,1,3)) OR (sender_id=" . $receiver_id . " AND receiver_id=" . $sender_id . " AND status IN (0,1,2)) 
					ORDER BY a.created_at DESC
					LIMIT " . $number . " OFFSET ".$offset." 
					
					";
					
		$result = core::database()->querySQL($query);
		
		return  array_reverse(core::database()->getColumnArray($result));		
	}

	public function getNewMessagesAfter($last_id, $user_id, $receiver_id = 0, $limit = 20)
	{
		$last_id = (int)$last_id;
		$user_id = (int)$user_id;
		$receiver_id = (int)$receiver_id;
		$limit = (int)$limit;
		if($limit <= 0) $limit = 20;

		if($receiver_id > 0) {
			$where = "a.id > " . $last_id . " AND ((a.sender_id=" . $user_id . " AND a.receiver_id=" . $receiver_id . " AND a.status IN (0,1,3)) OR (a.sender_id=" . $receiver_id . " AND a.receiver_id=" . $user_id . " AND a.status IN (0,1,2)))";
		}
		else {
			$where = "a.id > " . $last_id . " AND a.receiver_id=" . $user_id . " AND a.status=0";
		}

		$query = "SELECT *, a.id AS id, b.id AS user_id, DATE_FORMAT(a.created_at,'%d.%m.%y %H:%i') as created FROM " . core::database()->getTableName('messages') . " a
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.sender_id
					WHERE " . $where . "
					ORDER BY a.id ASC
					LIMIT " . $limit;

		$result = core::database()->querySQL($query);

		return core::database()->getColumnArray($result);
	}
	
	public function changeFriendsStatus($friend_id, $user_id, $status)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE (user_id=" . $user_id . " AND friend_id=" . $friend_id . ") OR (user_id=" . $friend_id . " AND friend_id=" . $user_id . ")";
		$result = core::database()->querySQL($query);
			
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['user_id'] = $user_id;
			$fields['friend_id'] = $friend_id;			
			$fields['status'] = $status;			
			
			$result = core::database()->insert($fields, core::database()->getTableName('friends'));
			
			if($result) return $status;			
		}
		else{
			$row = core::database()->getRow($result);
			
			if($row['status'] == 0){
				$update = "UPDATE " . core::database()->getTableName('friends') . " SET status=" . $status . ", added=NOW() WHERE user_id=" . $user_id . " AND friend_id=" . $friend_id;

				if(core::database()->querySQL($update)){
					return $status;
				}
			}
		}
	}
	
	public function removeFriend($user_id, $friend_id)
	{
		if(core::database()->delete(core::database()->getTableName('friends'), "((user_id=" . $friend_id . " AND friend_id=" . $user_id.") OR (user_id=" . $user_id . " AND friend_id=" . $friend_id. "))",'')) return TRUE;
		else return FALSE;
	}
	
	public function editUserProfile($fields, $user_id){

		$result = TRUE;
		$document_root = !empty($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/') : rtrim(dirname(dirname(__DIR__)), '/');
		$tmp_dir = $document_root . '/tmp/';
		$avatar_dir = $document_root . '/' . PATH_USER_AVATAR_IMAGES;
		$cover_page_dir = $document_root . '/' . PATH_USER_COVER_PAGE_IMAGES;
		$fields['avatar'] = !empty($fields['avatar']) ? basename($fields['avatar']) : '';
		$fields['cover_page'] = !empty($fields['cover_page']) ? basename($fields['cover_page']) : '';
		$avatar_tmp = $fields['avatar'] ? $tmp_dir . $fields['avatar'] : '';
		$cover_page_tmp = $fields['cover_page'] ? $tmp_dir . $fields['cover_page'] : '';
		
		core::database()->querySQL('SET AUTOCOMMIT=0');
		core::database()->querySQL('START TRANSACTION');
		
		if($fields['avatar'] && file_exists($avatar_tmp)) $fields['user']['avatar'] = $user_id . '_' . $fields['avatar'];
		if($fields['cover_page'] && file_exists($cover_page_tmp)) $fields['user']['cover_page'] = $user_id . '_' . $fields['cover_page'];	

		$query = "SELECT avatar, cover_page FROM " . core::database()->getTableName('users') . " WHERE id=" . $user_id;
		$rt =  core::database()->querySQL($query);
		$pic = core::database()->getRow($rt);
		
		if(!core::database()->update($fields['user'], core::database()->getTableName('users'), "id=" . $user_id)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}		
		
		$query = "SELECT * FROM " . core::database()->getTableName('occupations') . " WHERE kind=1 AND user_id=" . $user_id;
		$rt = core::database()->querySQL($query);
		
		$arr_tag = core::database()->getColumnArray($rt);
		
		foreach($arr_tag as $row){
			if(!core::database()->delete(core::database()->getTableName('geo_target'), "target_type='occupation' AND target_id=" . $row['id'])) {
				$result = FALSE;
				core::database()->querySQL('ROLLBACK');	
			}
		}	
			
		if(!core::database()->delete(core::database()->getTableName('occupations'), "kind=1 AND user_id=" . $user_id)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		else{
			for($i=0; $i < count($fields['education']['name']); $i++){
				$arr = array();
				$arr['id'] = 0;
				$arr['user_id'] = $user_id;
				$arr['name'] = htmlspecialchars(trim($fields['education']['name'][$i]));
				$arr['description'] = htmlspecialchars(trim($fields['education']['description'][$i]));
				$arr['month_start'] = $fields['education']['month_start'][$i];
				$arr['year_start'] = $fields['education']['year_start'][$i];
				$arr['month_finish'] = $fields['education']['month_finish'][$i];				
				$arr['year_finish'] = $fields['education']['year_finish'][$i];
				$arr['kind'] = $fields['education']['kind'][$i];						

				if(is_numeric($fields['education']['id_place'][$i])){
					$city = Places::getCityInfo($fields['education']['id_place'][$i]);
					if(!empty($city['name_ru'])) $arr['city'] = $city['name_ru'];
				}			

				$insert_id = core::database()->insert($arr, core::database()->getTableName('occupations'));
				
				if(!$insert_id) {
					$result = FALSE;
					core::database()->querySQL('ROLLBACK');
				}
				else{
					$country = Places::getCountryByCity($fields['education']['id_place'][$i]);
					$region = Places::getRegionByCity($fields['education']['id_place'][$i]);	
					
					$arr = array();
					$arr['id'] = 0;
					$arr['target_type'] = 'occupation';
					$arr['target_id'] = $insert_id;
					$arr['country_id'] = $country['country_id']; 	
					$arr['region_id'] = $region['region_id']; 
					$arr['city_id'] = $fields['education']['id_place'][$i];
					
					if(!core::database()->insert($arr, core::database()->getTableName('geo_target'))){
						$result = FALSE;
						core::database()->querySQL('ROLLBACK');
					}				
				}					
			}	
		}	
		
		$query = "SELECT * FROM " . core::database()->getTableName('occupations') . " WHERE kind=3 AND user_id=" . $user_id;
		$result = core::database()->querySQL($query);
		
		$arr_tag = core::database()->getColumnArray($result);
		
		foreach($arr_tag as $row){
			if(!core::database()->delete(core::database()->getTableName('geo_target'), "target_type='occupation' AND target_id=" . $row['id'])) {
				$result = FALSE;
				core::database()->querySQL('ROLLBACK');	
			}
		}	

		if(!core::database()->delete(core::database()->getTableName('occupations'), "kind=3 AND user_id=" . $user_id)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		else{
			for($i=0; $i < count($fields['job']['name']); $i++){
				$arr = array();
				$arr['id'] = 0;
				$arr['user_id'] = $user_id;
				$arr['name'] = htmlspecialchars(trim($fields['job']['name'][$i]));
				$arr['description'] = htmlspecialchars(trim($fields['job']['description'][$i]));
				$arr['month_start'] = $fields['job']['month_start'][$i];
				$arr['year_start'] = $fields['job']['year_start'][$i];
				$arr['month_finish'] = $fields['job']['month_finish'][$i];				
				$arr['year_finish'] = $fields['job']['year_finish'][$i];
				$arr['kind'] = $fields['job']['kind'][$i];			

				if(is_numeric($fields['job']['id_place'][$i])){
					$city = Places::getCityInfo($fields['job']['id_place'][$i]);
					if($city['name_ru']) $arr['city'] = $city['name_ru'];
				}			
				
				$insert_id = core::database()->insert($arr, core::database()->getTableName('occupations'));
				
				if(!$insert_id) {
					$result = FALSE;
					core::database()->querySQL('ROLLBACK');
				}
				else{
					$country = Places::getCountryByCity($fields['job']['id_place'][$i]);
					$region = Places::getRegionByCity($fields['job']['id_place'][$i]);	
					
					$arr = array();
					$arr['id'] = 0;
					$arr['target_type'] = 'occupation';
					$arr['target_id'] = $insert_id;
					$arr['country_id'] = $country['country_id']; 	
					$arr['region_id'] = $region['region_id']; 
					$arr['city_id'] = $fields['job']['id_place'][$i];
					
					if(!core::database()->insert($arr, core::database()->getTableName('geo_target'))){
						$result = FALSE;
						core::database()->querySQL('ROLLBACK');
					}				
				}				
			}	
		}		
		
		if(!core::database()->delete(core::database()->getTableName('users_sport_types'), "user_id=" . $user_id)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		else{
			for($i=0; $i < count($fields['sport']['id_sport_type']); $i++){
				$arr = array();
				$arr['id'] = 0;
				$arr['user_id'] = $user_id;
				
				if(is_numeric($fields['job']['id_sport_type'][$i])){
					$name = Sport::getSportType($fields['job']['id_sport_type'][$i]);
					if($name) $arr['sport_type'] = $name;
				}
				
				$arr['sport_level_id'] = $fields['sport']['sport_level_id'][$i];				
				$arr['sport_type'] = $fields['sport']['sport_type'][$i];			
				$arr['search_team'] = $fields['sport']['search_team'][$i] == 'on' ? 1 : 0;
					
				if(!core::database()->insert($arr, core::database()->getTableName('users_sport_types'))) {
					$result = FALSE;
					core::database()->querySQL('ROLLBACK');
				}				
			}		
		}
		
		if($fields['id_place']){
			$query = "SELECT * FROM " . core::database()->getTableName('geo_target') . " WHERE target_type='user' AND target_id=" . $user_id;
			$rt = core::database()->querySQL($query);
			
			$country = Places::getCountryByCity($fields['id_place']);
			$region = Places::getRegionByCity($fields['id_place']);			
			
			if(core::database()->getRecordCount($rt) == 0) {
				$arr = array();
				$arr['id'] = 0;				
				$arr['target_type'] = 'user';				
				$arr['target_id'] = $user_id;					
				$arr['country_id'] = $country['country_id']; 	
				$arr['region_id'] = $region['region_id']; 
				$arr['city_id'] = $fields['id_place'];
				
				if(!core::database()->insert($arr, core::database()->getTableName('geo_target'))){
					$result = FALSE;
					core::database()->querySQL('ROLLBACK');
				}				
			}else{
				$arr = array();
				$arr['country_id'] = $country['country_id']; 	
				$arr['region_id'] = $region['region_id'];
				$arr['city_id'] = $fields['id_place'];
				
				if(!core::database()->update($arr, core::database()->getTableName('geo_target'), "target_type='user' AND target_id=" . $user_id)){
					$result = FALSE;
					core::database()->querySQL('ROLLBACK');				
				}
			}
		}

		core::database()->querySQL('COMMIT');
		core::database()->querySQL('SET AUTOCOMMIT=1');		
		
		if($result) {
			if($fields['avatar'] && isset($fields['user']['avatar']) && file_exists($avatar_tmp)) {
				if($pic['avatar'] && file_exists($avatar_dir . $pic['avatar'])) unlink($avatar_dir . $pic['avatar']);				
				rename($avatar_tmp, $avatar_dir . $fields['user']['avatar']);	
			}	
			if($fields['cover_page'] && isset($fields['user']['cover_page']) && file_exists($cover_page_tmp)) {
				if($pic['cover_page'] && file_exists($cover_page_dir . $pic['cover_page'])) unlink($cover_page_dir . $pic['cover_page']);
				rename($cover_page_tmp, $cover_page_dir . $fields['user']['cover_page']);
			}				
		}			
		
		return $result; 
	}
	
	
	public function clearDialog($user_id, $receiver_id)
	{
		if(!core::database()->delete(core::database()->getTableName('comments'), "(sender_id=" . $user_id . " AND receiver_id=" . $receiver_id . ") OR (sender_id=" . $receiver_id . " AND receiver_id=" . $user_id . ")", '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
	}
	
	public function getLastMessage($receiver_id, $user_id)
	{
		$query = "SELECT *, a.id AS id, b.id AS user_id, DATE_FORMAT(a.created_at,'%d.%m.%y %H:%i') as created FROM " . core::database()->getTableName('messages') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.sender_id  
					WHERE (sender_id=" . $user_id . " AND receiver_id=" . $receiver_id . " AND a.status IN (0,1,3)) OR (sender_id=" . $receiver_id . " AND receiver_id=" . $user_id . " AND a.status IN (0,1,2)) 
					ORDER by a.created_at DESC 
					LIMIT 1";
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function blockUser($user_id, $friend_id)
	{
		core::database()->delete(core::database()->getTableName('friends'), "(status=0 OR status=1) AND (user_id=" . $friend_id . " AND friend_id=" . $user_id . ")", '');		
		
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE user_id=" . $user_id . " AND friend_id=" . $friend_id;
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['user_id'] = $user_id;
			$fields['friend_id'] = $friend_id;			
			$fields['status'] = 2;			
			
			$result = core::database()->insert($fields, core::database()->getTableName('friends'));
			
			if($result) 
				return TRUE;
			else
				return FALSE;	
		}
		else{

			$fields = array();
			$fields['status'] = 2;	

			if(core::database()->update($fields, core::database()->getTableName('friends'), "user_id=" . $user_id ." AND friend_id=" . $friend_id))
				return TRUE;
			else
				return FALSE;
		}		
	}
	
	public function unblockUser($user_id, $friend_id)
	{
		if(core::database()->delete(core::database()->getTableName('friends'), "user_id=" . $user_id ." AND friend_id=" . $friend_id))
			return TRUE;
		else
			return FALSE;
	}
	
	public function sendCommunityInvitation($community_id, $user_id)
	{
		$query = "SELECT *,u.id as user_id FROM " . core::database()->getTableName('users') . " u LEFT JOIN " . core::database()->getTableName('community_roles') . " c ON (c.user_id=u.id) AND (c.community_id=" . $community_id . "), 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.user_id='" . $user_id . "'
						THEN f.friend_id=u.id
						WHEN f.friend_id='" . $user_id . "'
						THEN f.user_id=u.id
					END
					AND	(f.status='1') AND (c.user_id IS NULL)
					";
				
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function sendEventInvitation($event_id, $type, $user_id)
	{
		$query = "SELECT *,u.id as user_id FROM " . core::database()->getTableName('users') . " u LEFT JOIN " . core::database()->getTableName('accepted_event_members') . " a ON (a.member_id=u.id) AND (a.eventable_type='".$type."') AND (event_id=" . $event_id . "), 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.user_id='" . $user_id . "' 
						THEN f.friend_id=u.id
						WHEN f.friend_id='" . $user_id . "' 
						THEN f.user_id=u.id
					END
					AND	(f.status='1') AND (a.member_id IS NULL)
					";
					
		$result = core::database()->querySQL($query);

		return core::database()->getColumnArray($result);
	}	
	
	public function getMailNotification($id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('mail_notification') . " WHERE id=" . $id;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);
	}
	
	public function removeShare($content_id, $shareable_type)
	{
		if(core::database()->delete(core::database()->getTableName('share'), "shareable_type='" . $shareable_type . "' AND content_id=" . $content_id, ''))
			return TRUE;
		else
			return FALSE;		
	}
	
	public function addFeedback($fields)
	{
		return core::database()->insert($fields, core::database()->getTableName('feedback'));
	}

	public function checkBlock($receiver_id, $user_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=2) AND (friend_id=" . $receiver_id . ") AND (user_id=" . $user_id . ")";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return TRUE;
		else
			return FALSE;	
	}
	
	public function removeMessage($id, $user_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('messages') . " WHERE id=" . $id;
		$result = core::database()->querySQL($query);
		$row = core::database()->getRow($result);
		
		switch($row['status']) {
			case 0: 
			
				if($row['sender_id'] == $user_id) 
					$status = 2;
				else if($row['receiver_id'] == $user_id) 
					$status = 3;	
			
			break;
			
			case 1: 
			
				if($row['sender_id'] == $user_id) 
					$status = 2;
				else if($row['receiver_id'] == $user_id) 
					$status = 3;
			
			break;
			
			case 2: 
	
				if($row['receiver_id'] == $user_id) $status = 4;
			
			break;
			
			case 3: 
			
				if($row['sender_id'] == $user_id) $status = 4;
			
			break;
		}	

		if($status){
			
			$update = "UPDATE " . core::database()->getTableName('messages') . " SET status=" . $status . " WHERE id=" . $id;
			
			if(core::database()->querySQL($update))
				return true;
			else
				return false;			
		}	
	}
	
	public function checkBanMsgReceiver($receiver_id)
	{
		$check = TRUE;
		
		if(is_numeric($receiver_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=" . $receiver_id  . " AND banned=1";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) > 0) $check = FALSE;
		}

		return $check;
	}
	
	public function checkDeletedMsgReceiver($receiver_id)
	{
		$check = TRUE;
		
		if(is_numeric($receiver_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=" . $receiver_id  . " AND deleted=1";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) > 0) $check = FALSE;
		}

		return $check;
	}
}
