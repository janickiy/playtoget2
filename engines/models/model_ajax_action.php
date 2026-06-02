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
		
		$query = "SELECT *, a.id AS id_comment, DATE_FORMAT(a.created_at,'%d.%m.%Y %H:%i') AS created, a.id_user AS id_user FROM " . core::database()->getTableName('comments') . " a LEFT JOIN  " . core::database()->getTableName('users') . " b ON a.id_user=b.id WHERE a.id=" . $id_comment;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);
	}
	
	public function liked($id_content, $likeable_type, $id_user)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('likes') . " WHERE (id_content=" . $id_content  . ") AND (id_user=" . $id_user . ") AND (likeable_type='" . $likeable_type . "')";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['id_user'] = $id_user;
			$fields['likeable_type'] = $likeable_type;
			$fields['id_content'] = $id_content;
			$fields['time'] = date("Y-m-d H:i:s");
		
			core::database()->insert($fields, core::database()->getTableName('likes'));
		}
		else{
			core::database()->delete(core::database()->getTableName('likes'), "(id_content=" . $id_content  . ") AND (id_user=" . $id_user . ") AND (likeable_type='" . $likeable_type . "')",'');
		}

		$query = "SELECT * FROM " . core::database()->getTableName('likes') . " WHERE id_content=" . $id_content  . " AND likeable_type='" . $likeable_type . "'";
		$result = core::database()->querySQL($query);
			
		return core::database()->getRecordCount($result);
	}
	
	public function shared($id_content, $shareable_type, $id_user)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('share') . " WHERE (id_content=" . $id_content  . ") AND (id_user=" . $id_user . ") AND (shareable_type='" . $shareable_type . "')";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['id_user'] = $id_user;
			$fields['shareable_type'] = $shareable_type;			
			$fields['time'] = date("Y-m-d H:i:s");			
			$fields['id_content'] = $id_content;
		
			core::database()->insert($fields, core::database()->getTableName('share'));
		}
		
		$query = "SELECT * FROM " . core::database()->getTableName('share') . " WHERE id_content=" . $id_content  . " AND shareable_type='" . $shareable_type . "'";
		$result = core::database()->querySQL($query);
			
		return core::database()->getRecordCount($result);
	}	
	
	
	public function getPopularPhotos($id_owner, $offset, $postnumbers)
	{
		$from = core::database()->getTableName('photos');
		$parameters = '*';
		$where = "WHERE id_owner=" . $id_owner ."";
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
		
		$query = "SELECT *, a.id AS id_message, DATE_FORMAT(a.created_at,'%d.%m.%Y %H:%i') AS created, a.id_sender AS id_sender FROM " . core::database()->getTableName('messages') . " a LEFT JOIN  " . core::database()->getTableName('users') . " b ON a.id_sender=b.id WHERE a.id=" . $id_message;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);
	}

	public function getMessagesListAjax($offset, $number, $id_sender, $id_receiver)
	{
		
		$query = "SELECT *, a.id AS id, b.id AS id_user, DATE_FORMAT(a.created_at,'%d.%m.%y %H:%i') as created FROM " . core::database()->getTableName('messages') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.id_sender 
					WHERE (id_sender=" . $id_sender . " AND id_receiver=" . $id_receiver . " AND status IN (0,1,3)) OR (id_sender=" . $id_receiver . " AND id_receiver=" . $id_sender . " AND status IN (0,1,2)) 
					ORDER BY a.created_at DESC
					LIMIT " . $number . " OFFSET ".$offset." 
					
					";
					
		$result = core::database()->querySQL($query);
		
		return  array_reverse(core::database()->getColumnArray($result));		
	}
	
	public function changeFriendsStatus($id_friend, $id_user, $status)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE (id_user=" . $id_user . " AND id_friend=" . $id_friend . ") OR (id_user=" . $id_friend . " AND id_friend=" . $id_user . ")";
		$result = core::database()->querySQL($query);
			
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['id_user'] = $id_user;
			$fields['id_friend'] = $id_friend;			
			$fields['status'] = $status;			
			
			$result = core::database()->insert($fields, core::database()->getTableName('friends'));
			
			if($result) return $status;			
		}
		else{
			$row = core::database()->getRow($result);
			
			if($row['status'] == 0){
				$update = "UPDATE " . core::database()->getTableName('friends') . " SET status=" . $status . ", added=NOW() WHERE id_user=" . $id_user . " AND id_friend=" . $id_friend;

				if(core::database()->querySQL($update)){
					return $status;
				}
			}
		}
	}
	
	public function removeFriend($id_user, $id_friend)
	{
		if(core::database()->delete(core::database()->getTableName('friends'), "((id_user=" . $id_friend . " AND id_friend=" . $id_user.") OR (id_user=" . $id_user . " AND id_friend=" . $id_friend. "))",'')) return TRUE;
		else return FALSE;
	}
	
	public function editUserProfile($fields, $id_user){

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
		
		if($fields['avatar'] && file_exists($avatar_tmp)) $fields['user']['avatar'] = $id_user . '_' . $fields['avatar'];
		if($fields['cover_page'] && file_exists($cover_page_tmp)) $fields['user']['cover_page'] = $id_user . '_' . $fields['cover_page'];	

		$query = "SELECT avatar, cover_page FROM " . core::database()->getTableName('users') . " WHERE id=" . $id_user;
		$rt =  core::database()->querySQL($query);
		$pic = core::database()->getRow($rt);
		
		if(!core::database()->update($fields['user'], core::database()->getTableName('users'), "id=" . $id_user)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}		
		
		$query = "SELECT * FROM " . core::database()->getTableName('occupations') . " WHERE kind=1 AND id_user=" . $id_user;
		$rt = core::database()->querySQL($query);
		
		$arr_tag = core::database()->getColumnArray($rt);
		
		foreach($arr_tag as $row){
			if(!core::database()->delete(core::database()->getTableName('geo_target'), "target_type='occupation' AND id_target=" . $row['id'])) {
				$result = FALSE;
				core::database()->querySQL('ROLLBACK');	
			}
		}	
			
		if(!core::database()->delete(core::database()->getTableName('occupations'), "kind=1 AND id_user=" . $id_user)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		else{
			for($i=0; $i < count($fields['education']['name']); $i++){
				$arr = array();
				$arr['id'] = 0;
				$arr['id_user'] = $id_user;
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
					$arr['id_target'] = $insert_id;
					$arr['id_country'] = $country['id_country']; 	
					$arr['id_region'] = $region['id_region']; 
					$arr['id_city'] = $fields['education']['id_place'][$i];
					
					if(!core::database()->insert($arr, core::database()->getTableName('geo_target'))){
						$result = FALSE;
						core::database()->querySQL('ROLLBACK');
					}				
				}					
			}	
		}	
		
		$query = "SELECT * FROM " . core::database()->getTableName('occupations') . " WHERE kind=3 AND id_user=" . $id_user;
		$result = core::database()->querySQL($query);
		
		$arr_tag = core::database()->getColumnArray($result);
		
		foreach($arr_tag as $row){
			if(!core::database()->delete(core::database()->getTableName('geo_target'), "target_type='occupation' AND id_target=" . $row['id'])) {
				$result = FALSE;
				core::database()->querySQL('ROLLBACK');	
			}
		}	

		if(!core::database()->delete(core::database()->getTableName('occupations'), "kind=3 AND id_user=" . $id_user)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		else{
			for($i=0; $i < count($fields['job']['name']); $i++){
				$arr = array();
				$arr['id'] = 0;
				$arr['id_user'] = $id_user;
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
					$arr['id_target'] = $insert_id;
					$arr['id_country'] = $country['id_country']; 	
					$arr['id_region'] = $region['id_region']; 
					$arr['id_city'] = $fields['job']['id_place'][$i];
					
					if(!core::database()->insert($arr, core::database()->getTableName('geo_target'))){
						$result = FALSE;
						core::database()->querySQL('ROLLBACK');
					}				
				}				
			}	
		}		
		
		if(!core::database()->delete(core::database()->getTableName('users_sport_types'), "id_user=" . $id_user)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		else{
			for($i=0; $i < count($fields['sport']['id_sport_type']); $i++){
				$arr = array();
				$arr['id'] = 0;
				$arr['id_user'] = $id_user;
				
				if(is_numeric($fields['job']['id_sport_type'][$i])){
					$name = Sport::getSportType($fields['job']['id_sport_type'][$i]);
					if($name) $arr['sport_type'] = $name;
				}
				
				$arr['id_sport_level'] = $fields['sport']['id_sport_level'][$i];				
				$arr['sport_type'] = $fields['sport']['sport_type'][$i];			
				$arr['search_team'] = $fields['sport']['search_team'][$i] == 'on' ? 1 : 0;
					
				if(!core::database()->insert($arr, core::database()->getTableName('users_sport_types'))) {
					$result = FALSE;
					core::database()->querySQL('ROLLBACK');
				}				
			}		
		}
		
		if($fields['id_place']){
			$query = "SELECT * FROM " . core::database()->getTableName('geo_target') . " WHERE target_type='user' AND id_target=" . $id_user;
			$rt = core::database()->querySQL($query);
			
			$country = Places::getCountryByCity($fields['id_place']);
			$region = Places::getRegionByCity($fields['id_place']);			
			
			if(core::database()->getRecordCount($rt) == 0) {
				$arr = array();
				$arr['id'] = 0;				
				$arr['target_type'] = 'user';				
				$arr['id_target'] = $id_user;					
				$arr['id_country'] = $country['id_country']; 	
				$arr['id_region'] = $region['id_region']; 
				$arr['id_city'] = $fields['id_place'];
				
				if(!core::database()->insert($arr, core::database()->getTableName('geo_target'))){
					$result = FALSE;
					core::database()->querySQL('ROLLBACK');
				}				
			}else{
				$arr = array();
				$arr['id_country'] = $country['id_country']; 	
				$arr['id_region'] = $region['id_region'];
				$arr['id_city'] = $fields['id_place'];
				
				if(!core::database()->update($arr, core::database()->getTableName('geo_target'), "target_type='user' AND id_target=" . $id_user)){
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
	
	
	public function clearDialog($id_user, $id_receiver)
	{
		if(!core::database()->delete(core::database()->getTableName('comments'), "(id_sender=" . $id_user . " AND id_receiver=" . $id_receiver . ") OR (id_sender=" . $id_receiver . " AND id_receiver=" . $id_user . ")", '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
	}
	
	public function getLastMessage($id_receiver, $id_user)
	{
		$query = "SELECT *, a.id AS id, b.id AS id_user, DATE_FORMAT(a.created_at,'%d.%m.%y %H:%i') as created FROM " . core::database()->getTableName('messages') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.id_sender  
					WHERE (id_sender=" . $id_user . " AND id_receiver=" . $id_receiver . " AND a.status IN (0,1,3)) OR (id_sender=" . $id_receiver . " AND id_receiver=" . $id_user . " AND a.status IN (0,1,2)) 
					ORDER by a.created_at DESC 
					LIMIT 1";
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function blockUser($id_user, $id_friend)
	{
		core::database()->delete(core::database()->getTableName('friends'), "(status=0 OR status=1) AND (id_user=" . $id_friend . " AND id_friend=" . $id_user . ")", '');		
		
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE id_user=" . $id_user . " AND id_friend=" . $id_friend;
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['id_user'] = $id_user;
			$fields['id_friend'] = $id_friend;			
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

			if(core::database()->update($fields, core::database()->getTableName('friends'), "id_user=" . $id_user ." AND id_friend=" . $id_friend))
				return TRUE;
			else
				return FALSE;
		}		
	}
	
	public function unblockUser($id_user, $id_friend)
	{
		if(core::database()->delete(core::database()->getTableName('friends'), "id_user=" . $id_user ." AND id_friend=" . $id_friend))
			return TRUE;
		else
			return FALSE;
	}
	
	public function sendCommunityInvitation($id_community, $id_user)
	{
		$query = "SELECT *,u.id as id_user FROM " . core::database()->getTableName('users') . " u LEFT JOIN " . core::database()->getTableName('community_roles') . " c ON (c.id_user=u.id) AND (c.id_community=" . $id_community . "), 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.id_user='" . $id_user . "'
						THEN f.id_friend=u.id
						WHEN f.id_friend='" . $id_user . "'
						THEN f.id_user=u.id
					END
					AND	(f.status='1') AND (c.id_user IS NULL)
					";
				
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function sendEventInvitation($id_event, $type, $id_user)
	{
		$query = "SELECT *,u.id as id_user FROM " . core::database()->getTableName('users') . " u LEFT JOIN " . core::database()->getTableName('accepted_event_members') . " a ON (a.id_member=u.id) AND (a.eventable_type='".$type."') AND (id_event=" . $id_event . "), 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.id_user='" . $id_user . "' 
						THEN f.id_friend=u.id
						WHEN f.id_friend='" . $id_user . "' 
						THEN f.id_user=u.id
					END
					AND	(f.status='1') AND (a.id_member IS NULL)
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
	
	public function removeShare($id_content, $shareable_type)
	{
		if(core::database()->delete(core::database()->getTableName('share'), "shareable_type='" . $shareable_type . "' AND id_content=" . $id_content, ''))
			return TRUE;
		else
			return FALSE;		
	}
	
	public function addFeedback($fields)
	{
		return core::database()->insert($fields, core::database()->getTableName('feedback'));
	}

	public function checkBlock($id_receiver, $id_user)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=2) AND (id_friend=" . $id_receiver . ") AND (id_user=" . $id_user . ")";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return TRUE;
		else
			return FALSE;	
	}
	
	public function removeMessage($id, $id_user)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('messages') . " WHERE id=" . $id;
		$result = core::database()->querySQL($query);
		$row = core::database()->getRow($result);
		
		switch($row['status']) {
			case 0: 
			
				if($row['id_sender'] == $id_user) 
					$status = 2;
				else if($row['id_receiver'] == $id_user) 
					$status = 3;	
			
			break;
			
			case 1: 
			
				if($row['id_sender'] == $id_user) 
					$status = 2;
				else if($row['id_receiver'] == $id_user) 
					$status = 3;
			
			break;
			
			case 2: 
	
				if($row['id_receiver'] == $id_user) $status = 4;
			
			break;
			
			case 3: 
			
				if($row['id_sender'] == $id_user) $status = 4;
			
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
	
	public function checkBanMsgReceiver($id_receiver)
	{
		$check = TRUE;
		
		if(is_numeric($id_receiver)){
			$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=" . $id_receiver  . " AND banned=1";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) > 0) $check = FALSE;
		}

		return $check;
	}
	
	public function checkDeletedMsgReceiver($id_receiver)
	{
		$check = TRUE;
		
		if(is_numeric($id_receiver)){
			$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=" . $id_receiver  . " AND deleted=1";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) > 0) $check = FALSE;
		}

		return $check;
	}
}
