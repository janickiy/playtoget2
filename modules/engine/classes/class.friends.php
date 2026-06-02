<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Friends
{
	static function getPossibleFriendsList($id_user, $limit = 10, $offset = 0)
	{
		if(is_numeric($id_user)){
			$query = "SELECT *, id AS id_user FROM " . core::database()->getTableName('users') . " WHERE id != " . $id_user . " AND id NOT IN (SELECT u.id as id_user FROM " . core::database()->getTableName('users') . " u, " . core::database()->getTableName('friends') . " f
						WHERE
						CASE
							WHEN f.id_user='" . $id_user . "'
							THEN f.id_friend=u.id
							WHEN f.id_friend='" . $id_user . "'
							THEN f.id_user=u.id
						END)
						ORDER by RAND()
						LIMIT " . $limit . " OFFSET " . $offset . "
					";

			$result = core::database()->querySQL($query);

			return core::database()->getColumnArray($result);
		}
	}
	
	static function NumberFriends($id_user)
	{
		if(is_numeric($id_user)){
			$query = "SELECT *,u.id as id_user FROM " . core::database()->getTableName('users') . " u, " . core::database()->getTableName('friends') . " f
						WHERE
						CASE
							WHEN f.id_user='" . $id_user . "'
							THEN f.id_friend=u.id
							WHEN f.id_friend='" . $id_user . "'
							THEN f.id_user=u.id
						END
						AND	(f.status='1')";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getFriendsList($id_user, $limit = 0, $offset = 0)
	{
		if(is_numeric($id_user)){
			$query = "SELECT *,u.id as id_user FROM " . core::database()->getTableName('users') . " u, " . core::database()->getTableName('friends') . " f
						WHERE
							CASE
								WHEN f.id_user='" . $id_user . "'
								THEN f.id_friend=u.id
								WHEN f.id_friend='" . $id_user . "'
								THEN f.id_user=u.id
						END
						AND	(f.status='1')
						LIMIT " . $limit . " OFFSET " . $offset . "
					";

				
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}		
	}
	
	static function NumberFriendsRequest($id_friend)
	{
		if(is_numeric($id_friend)){
			$query = "SELECT * FROM " . core::database()->getTableName('friends') . " f 
						LEFT JOIN " . core::database()->getTableName('users') . " u ON f.id_user=u.id
						WHERE status=0 AND f.id_friend=" . $id_friend;
						
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getFriendsRequestList($id_friend)
	{
		if(is_numeric($id_friend)){
			$query = "SELECT * FROM " . core::database()->getTableName('friends') . " f 
						LEFT JOIN " . core::database()->getTableName('users') . " u ON f.id_user=u.id
						WHERE status=0 AND f.id_friend=" . $id_friend;
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function removeFriend($id_user, $id_friend)
	{
		if(core::database()->delete(core::database()->getTableName('friends'), "((id_user=" . $id_friend . " AND id_friend=" . $id_user.") OR (id_user=" . $id_user . " AND id_friend=" . $id_friend. "))",'')) 
			return TRUE;
		else 
			return FALSE;
		//if(core::database()->delete(core::database()->getTableName('friends'), "(id_user=" . $id_friend . " AND id_friend=" . $id_user.")",'')) return true;
	}
	
	static function changeFriendsStatus($id_friend, $id_user, $status)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE (id_user=" . $id_user . " AND id_friend=" . $id_friend . ") OR (id_user=" . $id_user . " AND id_friend=" . $id_friend . ")";
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
			$update = "UPDATE " . core::database()->getTableName('friends') . " SET status=" . $status . ", added=NOW() WHERE id_user=" . $id_user . " AND id_friend=" . $id_friend;

			if(core::database()->querySQL($update)){
				return $status;
			}
		}
	}
	
	static function getOutgoingRequestList($id_user, $limit = 10, $offset = 0)
	{
		if(is_numeric($id_user)){
			$query = "SELECT *, f.id_friend as id_user FROM " . core::database()->getTableName('friends') . " f 
						LEFT JOIN " . core::database()->getTableName('users') . " u ON f.id_friend=u.id
						WHERE status=0 AND f.id_user=" . $id_user . "
						LIMIT " . $limit . " OFFSET " . $offset . "
						";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function NumberOutgoingRequest($id_user)
	{
		if(is_numeric($id_user)){
			$query = "SELECT * FROM " . core::database()->getTableName('friends') . " f 
						LEFT JOIN " . core::database()->getTableName('users') . " u ON f.id_friend=u.id
						WHERE status=0 AND f.id_user=" . $id_user;
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function checkBlock($id_receiver, $id_user)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=2) AND (id_friend=" . $id_receiver . ") AND (id_user=" . $id_user . ")";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return TRUE;
		else
			return FALSE;	
	}
}