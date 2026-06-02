<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class User
{
	private $user_id; 
	private $friend_id;
	private $status;
	
	public function setUser_id($user_id){
		return $this->user_id = core::database()->escape($user_id);
	}	
	
	public function getUserInfo(){
		$query = "SELECT *, DATE_FORMAT(birthday,'%d.%m.%Y') AS user_birthday FROM " . core::database()->getTableName('users') . " WHERE id=" . $this->user_id;
		$result = core::database()->querySQL($query);
		$row = core::database()->getRow($result);
		
		return $row;
	}
	
	public function MessageNotification()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('messages') . " WHERE status=0 AND receiver_id=" . $this->user_id;
		$result = core::database()->querySQL($query);
	
		return core::database()->getRecordCount($result);
	}
	
	public function AddFriendsNotification()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE status=0 AND friend_id=" . $this->user_id;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}
	
	public function getUserSetting()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('usersettings') . " a LEFT JOIN " . core::database()->getTableName('users') . " b ON a.user_id=b.id WHERE user_id=" . $this->user_id;
		$result = core::database()->querySQL($query);
		return core::database()->getRow($result);
	}
	
	public function checkFriends($friend_id, $status)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE (status= " . $status . ") AND ((friend_id=" . $friend_id . " AND user_id=" . $this->user_id .") OR (friend_id=" . $this->user_id . " AND user_id=" . $friend_id . "))";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return FALSE;
		else
			return TRUE;
	}
	
	public function getFriendship($friend_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(friend_id=" . $friend_id ." AND user_id=" . $this->user_id . ") OR (friend_id=" . $this->user_id . " AND user_id=" . $friend_id . ")";
	
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);
	}	
	
	public function checkBlock($user_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=2) AND (friend_id=" . $this->user_id . ") AND (user_id=" . $user_id . ")";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return FALSE;
		else
			return TRUE;	
	}	
	
	public function checkInvited($user_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE (status=0) AND (friend_id=" . $user_id . ") AND (user_id=" . $this->user_id . ")";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return FALSE;
		else
			return TRUE;
	}
	
	public function getBlockUsersList()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('users') . " u 
					LEFT JOIN " . core::database()->getTableName('friends') . " f ON u.id=f.friend_id
					WHERE f.status=2 AND f.user_id=" . $this->user_id;
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function NumberUsers()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('users') . " u 
					LEFT JOIN " . core::database()->getTableName('friends') . " f ON u.id=f.friend_id
					WHERE f.status=2 AND f.user_id=" . $this->user_id;
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}
	
	public function getLastActivity($limit = 10)
	{
		$query = "SELECT *, DATE_FORMAT(l.last_sign_in_at,'%d.%m.%Y %H:%m') AS time FROM " . core::database()->getTableName('log') . " l 
					LEFT JOIN " . core::database()->getTableName('log') . " u ON u.id=l.user_id
					WHERE l.user_id=" . $this->user_id . "
					LIMIT " . $limit . "";
					
		$result = core::database()->querySQL($query);

		return core::database()->getColumnArray($result);
	}
	
	public function setUserActivity()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('user_activity') . " WHERE user_id=" . $this->user_id;
		$result = core::database()->querySQL($query);
			
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['user_id'] = $this->user_id;
			$fields['last_activity'] = date("Y-m-d H:i:s");			
			
			$result=core::database()->insert($fields, core::database()->getTableName('user_activity'));
			
			if($result) 
				return TRUE;
			else
				return FALSE;
		}
		else{
			$fields = array();
			$fields['last_activity'] = date("Y-m-d H:i:s");
		
			return core::database()->update($fields, core::database()->getTableName('user_activity'), "user_id=" . $this->user_id);
		}
	}
	
	public function getUserLastVisit()
	{
		$query = "SELECT *, DATE_FORMAT(last_activity,'%H:%m') AS time FROM " . core::database()->getTableName('user_activity') . " WHERE user_id=" . $this->user_id . "";
		$result = core::database()->querySQL($query);		
		
		return core::database()->getRow($result);
	}	
	
	public function checkUserOnline($user_id)
	{
		if($user_id){
			$query = "SELECT * FROM " . core::database()->getTableName('user_activity') . " WHERE user_id=" . $user_id . " AND last_activity > (NOW() - INTERVAL 2 MINUTE)";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0)
				return FALSE;
			else
				return TRUE;
		}
	}
	
	public function getUserShare($limit = 5, $offset = 0)
	{
		$query = "SELECT *,u.id as user_id, f.friend_id AS friend_id, DATE_FORMAT(s.time, '%Y-%m-%d') AS added, DATE_FORMAT(s.time, '%Y%m%d%H%i%s') AS timeorder, s.id AS id_shared FROM " . core::database()->getTableName('share') . " s,  
					" . core::database()->getTableName('users') . " u, 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.user_id='" . $this->user_id . "'
						THEN f.friend_id=u.id
						WHEN f.friend_id='" . $this->user_id . "'
						THEN f.user_id=u.id
					END
					AND	((f.status='1') AND (s.user_id!=" . $this->user_id . ") AND (s.user_id=u.id))				
					ORDER BY s.time DESC
					LIMIT " . $limit . " OFFSET " . $offset . "
					";				
		
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);		
	}
	
	public function getUserFriendsLiked($limit = 5, $offset = 0)
	{
		$query = "SELECT *,u.id as user_id, f.friend_id AS friend_id, DATE_FORMAT(l.time, '%Y-%m-%d') AS added, DATE_FORMAT(l.time, '%Y%m%d%H%i%s') AS timeorder, l.id AS id_liked FROM " . core::database()->getTableName('likes') . " l,  
					" . core::database()->getTableName('users') . " u, 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.user_id='" . $this->user_id . "'
						THEN f.friend_id=u.id
						WHEN f.friend_id='" . $this->user_id . "'
						THEN f.user_id=u.id
					END
					AND	((f.status='1') AND (l.user_id!=" . $this->user_id . ") AND (l.user_id=u.id))				
					ORDER BY l.time DESC
					LIMIT " . $limit . " OFFSET " . $offset . "
					";				
		
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function getMyFriendsLastFriend($limit = 5, $offset = 0)
	{
		$query = "SELECT *,u.id as user_id FROM " . core::database()->getTableName('users') . " u, " . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.user_id='" . $this->user_id . "'
						THEN f.friend_id=u.id
						WHEN f.friend_id='" . $this->user_id . "'
						THEN f.user_id=u.id
					END
					AND	(f.status='1')
					LIMIT " . $limit . " OFFSET " . $offset . "";
				
		$result = core::database()->querySQL($query);
		$friends = core::database()->getColumnArray($result);
		
		$arrs = array();
	
		foreach($friends as $row){		
			
			$query = "SELECT *,u.id as user_id, DATE_FORMAT(f.added, '%Y-%m-%d') AS added, DATE_FORMAT(f.added, '%Y%m%d%H%i%s') AS timeorder FROM " . core::database()->getTableName('users') . " u, " . core::database()->getTableName('friends') . " f
						WHERE
							CASE
								WHEN f.user_id='" . $row['user_id'] . "'
								THEN f.friend_id=u.id
								WHEN f.friend_id='" . $row['user_id'] . "'
								THEN f.user_id=u.id
							END
					AND	(f.status='1')
					ORDER BY u.id DESC 
					LIMIT 1";
					
			$result = core::database()->querySQL($query);		
			$user = core::database()->getRow($result);	

			$arrs[] = array("firstname" => $row['firstname'], "lastname" => $row['lastname'], "user_id" => $row['user_id'], "sex" => $row['sex'], "avatar" => $row['avatar'], "friend_firstname" => $user['firstname'], "friend_lastname" => $user['lastname'], "friend_secondname" => $user['secondname'], "friend_id" => $user['user_id'], "added" => $user['added'], "timeorder" => $user['timeorder']);
		}

		return $arrs;
	}	
	
	public function permissionUser($user_id, $permission)
	{
		if(is_numeric($user_id)){
			$permit = true;
		
			if($permission == 1){
				$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=1) AND ((friend_id=" . $user_id . ") AND (user_id=" . $this->user_id . ") OR (friend_id=" . $this->user_id . ") AND (user_id=" . $user_id . "))";
				$result = core::database()->querySQL($query);
		
				if(core::database()->getRecordCount($result) == 0) $permit = false;
			}
			else if($permission == 2){
				$permit = false;
			}
		
			$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=2) AND (friend_id=" . $user_id . ") AND (user_id=" . $this->user_id . ")";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0) {}
			else $permit = false;

			return $permit;
		}
	}
	
	public function checkExistence($id)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0)
				return TRUE;
			else
				return FALSE;
		}		
	}	
}