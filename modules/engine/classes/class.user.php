<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class User
{
	private $id_user; 
	private $id_friend;
	private $status;
	
	public function setUser_id($id_user){
		return $this->id_user = core::database()->escape($id_user);
	}	
	
	public function getUserInfo(){
		$query = "SELECT *, DATE_FORMAT(birthday,'%d.%m.%Y') AS user_birthday FROM " . core::database()->getTableName('users') . " WHERE id=" . $this->id_user;
		$result = core::database()->querySQL($query);
		$row = core::database()->getRow($result);
		
		return $row;
	}
	
	public function MessageNotification()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('messages') . " WHERE status=0 AND id_receiver=" . $this->id_user;
		$result = core::database()->querySQL($query);
	
		return core::database()->getRecordCount($result);
	}
	
	public function AddFriendsNotification()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE status=0 AND id_friend=" . $this->id_user;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}
	
	public function getUserSetting()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('usersettings') . " a LEFT JOIN " . core::database()->getTableName('users') . " b ON a.id_user=b.id WHERE id_user=" . $this->id_user;
		$result = core::database()->querySQL($query);
		return core::database()->getRow($result);
	}
	
	public function checkFriends($id_friend, $status)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE (status= " . $status . ") AND ((id_friend=" . $id_friend . " AND id_user=" . $this->id_user .") OR (id_friend=" . $this->id_user . " AND id_user=" . $id_friend . "))";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return FALSE;
		else
			return TRUE;
	}
	
	public function getFriendship($id_friend)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(id_friend=" . $id_friend ." AND id_user=" . $this->id_user . ") OR (id_friend=" . $this->id_user . " AND id_user=" . $id_friend . ")";
	
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);
	}	
	
	public function checkBlock($id_user)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=2) AND (id_friend=" . $this->id_user . ") AND (id_user=" . $id_user . ")";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return FALSE;
		else
			return TRUE;	
	}	
	
	public function checkInvited($id_user)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE (status=0) AND (id_friend=" . $id_user . ") AND (id_user=" . $this->id_user . ")";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return FALSE;
		else
			return TRUE;
	}
	
	public function getBlockUsersList()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('users') . " u 
					LEFT JOIN " . core::database()->getTableName('friends') . " f ON u.id=f.id_friend
					WHERE f.status=2 AND f.id_user=" . $this->id_user;
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function NumberUsers()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('users') . " u 
					LEFT JOIN " . core::database()->getTableName('friends') . " f ON u.id=f.id_friend
					WHERE f.status=2 AND f.id_user=" . $this->id_user;
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}
	
	public function getLastActivity($limit = 10)
	{
		$query = "SELECT *, DATE_FORMAT(l.last_sign_in_at,'%d.%m.%Y %H:%m') AS time FROM " . core::database()->getTableName('log') . " l 
					LEFT JOIN " . core::database()->getTableName('log') . " u ON u.id=l.id_user
					WHERE l.id_user=" . $this->id_user . "
					LIMIT " . $limit . "";
					
		$result = core::database()->querySQL($query);

		return core::database()->getColumnArray($result);
	}
	
	public function setUserActivity()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('user_activity') . " WHERE id_user=" . $this->id_user;
		$result = core::database()->querySQL($query);
			
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['id_user'] = $this->id_user;
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
		
			return core::database()->update($fields, core::database()->getTableName('user_activity'), "id_user=" . $this->id_user);
		}
	}
	
	public function getUserLastVisit()
	{
		$query = "SELECT *, DATE_FORMAT(last_activity,'%H:%m') AS time FROM " . core::database()->getTableName('user_activity') . " WHERE id_user=" . $this->id_user . "";
		$result = core::database()->querySQL($query);		
		
		return core::database()->getRow($result);
	}	
	
	public function checkUserOnline($id_user)
	{
		if($id_user){
			$query = "SELECT * FROM " . core::database()->getTableName('user_activity') . " WHERE id_user=" . $id_user . " AND last_activity > (NOW() - INTERVAL 2 MINUTE)";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0)
				return FALSE;
			else
				return TRUE;
		}
	}
	
	public function getUserShare($limit = 5, $offset = 0)
	{
		$query = "SELECT *,u.id as id_user, f.id_friend AS id_friend, DATE_FORMAT(s.time, '%Y-%m-%d') AS added, DATE_FORMAT(s.time, '%Y%m%d%H%i%s') AS timeorder, s.id AS id_shared FROM " . core::database()->getTableName('share') . " s,  
					" . core::database()->getTableName('users') . " u, 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.id_user='" . $this->id_user . "'
						THEN f.id_friend=u.id
						WHEN f.id_friend='" . $this->id_user . "'
						THEN f.id_user=u.id
					END
					AND	((f.status='1') AND (s.id_user!=" . $this->id_user . ") AND (s.id_user=u.id))				
					ORDER BY s.time DESC
					LIMIT " . $limit . " OFFSET " . $offset . "
					";				
		
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);		
	}
	
	public function getUserFriendsLiked($limit = 5, $offset = 0)
	{
		$query = "SELECT *,u.id as id_user, f.id_friend AS id_friend, DATE_FORMAT(l.time, '%Y-%m-%d') AS added, DATE_FORMAT(l.time, '%Y%m%d%H%i%s') AS timeorder, l.id AS id_liked FROM " . core::database()->getTableName('likes') . " l,  
					" . core::database()->getTableName('users') . " u, 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.id_user='" . $this->id_user . "'
						THEN f.id_friend=u.id
						WHEN f.id_friend='" . $this->id_user . "'
						THEN f.id_user=u.id
					END
					AND	((f.status='1') AND (l.id_user!=" . $this->id_user . ") AND (l.id_user=u.id))				
					ORDER BY l.time DESC
					LIMIT " . $limit . " OFFSET " . $offset . "
					";				
		
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function getMyFriendsLastFriend($limit = 5, $offset = 0)
	{
		$query = "SELECT *,u.id as id_user FROM " . core::database()->getTableName('users') . " u, " . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.id_user='" . $this->id_user . "'
						THEN f.id_friend=u.id
						WHEN f.id_friend='" . $this->id_user . "'
						THEN f.id_user=u.id
					END
					AND	(f.status='1')
					LIMIT " . $limit . " OFFSET " . $offset . "";
				
		$result = core::database()->querySQL($query);
		$friends = core::database()->getColumnArray($result);
		
		$arrs = array();
	
		foreach($friends as $row){		
			
			$query = "SELECT *,u.id as id_user, DATE_FORMAT(f.added, '%Y-%m-%d') AS added, DATE_FORMAT(f.added, '%Y%m%d%H%i%s') AS timeorder FROM " . core::database()->getTableName('users') . " u, " . core::database()->getTableName('friends') . " f
						WHERE
							CASE
								WHEN f.id_user='" . $row['id_user'] . "'
								THEN f.id_friend=u.id
								WHEN f.id_friend='" . $row['id_user'] . "'
								THEN f.id_user=u.id
							END
					AND	(f.status='1')
					ORDER BY u.id DESC 
					LIMIT 1";
					
			$result = core::database()->querySQL($query);		
			$user = core::database()->getRow($result);	

			$arrs[] = array("firstname" => $row['firstname'], "lastname" => $row['lastname'], "id_user" => $row['id_user'], "sex" => $row['sex'], "avatar" => $row['avatar'], "friend_firstname" => $user['firstname'], "friend_lastname" => $user['lastname'], "friend_secondname" => $user['secondname'], "id_friend" => $user['id_user'], "added" => $user['added'], "timeorder" => $user['timeorder']);
		}

		return $arrs;
	}	
	
	public function permissionUser($id_user, $permission)
	{
		if(is_numeric($id_user)){
			$permit = true;
		
			if($permission == 1){
				$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=1) AND ((id_friend=" . $id_user . ") AND (id_user=" . $this->id_user . ") OR (id_friend=" . $this->id_user . ") AND (id_user=" . $id_user . "))";
				$result = core::database()->querySQL($query);
		
				if(core::database()->getRecordCount($result) == 0) $permit = false;
			}
			else if($permission == 2){
				$permit = false;
			}
		
			$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=2) AND (id_friend=" . $id_user . ") AND (id_user=" . $this->id_user . ")";
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