<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Friends
{
	static function getPossibleFriendsList($user_id, $limit = 10, $offset = 0)
	{
		if(is_numeric($user_id)){
			$query = "SELECT *, id AS user_id FROM " . core::database()->getTableName('users') . " WHERE id != " . $user_id . " AND id NOT IN (SELECT u.id as user_id FROM " . core::database()->getTableName('users') . " u, " . core::database()->getTableName('friends') . " f
						WHERE
						CASE
							WHEN f.user_id='" . $user_id . "'
							THEN f.friend_id=u.id
							WHEN f.friend_id='" . $user_id . "'
							THEN f.user_id=u.id
						END)
						ORDER by RAND()
						LIMIT " . $limit . " OFFSET " . $offset . "
					";

			$result = core::database()->querySQL($query);

			return core::database()->getColumnArray($result);
		}
	}
	
	static function NumberFriends($user_id)
	{
		if(is_numeric($user_id)){
			$query = "SELECT *,u.id as user_id FROM " . core::database()->getTableName('users') . " u, " . core::database()->getTableName('friends') . " f
						WHERE
						CASE
							WHEN f.user_id='" . $user_id . "'
							THEN f.friend_id=u.id
							WHEN f.friend_id='" . $user_id . "'
							THEN f.user_id=u.id
						END
						AND	(f.status='1')";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getFriendsList($user_id, $limit = 0, $offset = 0)
	{
		if(is_numeric($user_id)){
			$query = "SELECT *,u.id as user_id FROM " . core::database()->getTableName('users') . " u, " . core::database()->getTableName('friends') . " f
						WHERE
							CASE
								WHEN f.user_id='" . $user_id . "'
								THEN f.friend_id=u.id
								WHEN f.friend_id='" . $user_id . "'
								THEN f.user_id=u.id
						END
						AND	(f.status='1')
						LIMIT " . $limit . " OFFSET " . $offset . "
					";

				
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}		
	}
	
	static function NumberFriendsRequest($friend_id)
	{
		if(is_numeric($friend_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('friends') . " f 
						LEFT JOIN " . core::database()->getTableName('users') . " u ON f.user_id=u.id
						WHERE status=0 AND f.friend_id=" . $friend_id;
						
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getFriendsRequestList($friend_id)
	{
		if(is_numeric($friend_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('friends') . " f 
						LEFT JOIN " . core::database()->getTableName('users') . " u ON f.user_id=u.id
						WHERE status=0 AND f.friend_id=" . $friend_id;
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function removeFriend($user_id, $friend_id)
	{
		if(core::database()->delete(core::database()->getTableName('friends'), "((user_id=" . $friend_id . " AND friend_id=" . $user_id.") OR (user_id=" . $user_id . " AND friend_id=" . $friend_id. "))",'')) 
			return TRUE;
		else 
			return FALSE;
		//if(core::database()->delete(core::database()->getTableName('friends'), "(user_id=" . $friend_id . " AND friend_id=" . $user_id.")",'')) return true;
	}
	
	static function changeFriendsStatus($friend_id, $user_id, $status)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE (user_id=" . $user_id . " AND friend_id=" . $friend_id . ") OR (user_id=" . $user_id . " AND friend_id=" . $friend_id . ")";
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
			$update = "UPDATE " . core::database()->getTableName('friends') . " SET status=" . $status . ", added=NOW() WHERE user_id=" . $user_id . " AND friend_id=" . $friend_id;

			if(core::database()->querySQL($update)){
				return $status;
			}
		}
	}
	
	static function getOutgoingRequestList($user_id, $limit = 10, $offset = 0)
	{
		if(is_numeric($user_id)){
			$query = "SELECT *, f.friend_id as user_id FROM " . core::database()->getTableName('friends') . " f 
						LEFT JOIN " . core::database()->getTableName('users') . " u ON f.friend_id=u.id
						WHERE status=0 AND f.user_id=" . $user_id . "
						LIMIT " . $limit . " OFFSET " . $offset . "
						";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function NumberOutgoingRequest($user_id)
	{
		if(is_numeric($user_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('friends') . " f 
						LEFT JOIN " . core::database()->getTableName('users') . " u ON f.friend_id=u.id
						WHERE status=0 AND f.user_id=" . $user_id;
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function checkBlock($receiver_id, $user_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=2) AND (friend_id=" . $receiver_id . ") AND (user_id=" . $user_id . ")";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return TRUE;
		else
			return FALSE;	
	}
}