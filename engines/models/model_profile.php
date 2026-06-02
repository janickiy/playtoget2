<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_profile extends Model
{
	public function getMessagesList($receiver_id, $user_id, $limit)
	{
		$receiver_id = core::database()->escape($receiver_id);
		
		$query = "SELECT *, a.id AS id, b.id AS user_id, DATE_FORMAT(a.created_at,'%d.%m.%y %H:%i') as created FROM " . core::database()->getTableName('messages') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.sender_id  
					WHERE (sender_id=" . $user_id . " AND receiver_id=" . $receiver_id . " AND a.status IN (0,1,3)) OR (sender_id=" . $receiver_id . " AND receiver_id=" . $user_id . " AND a.status IN (0,1,2)) 
					ORDER by a.created_at DESC
					LIMIT " . $limit . "";
		
		$result = core::database()->querySQL($query);
		
		return array_reverse(core::database()->getColumnArray($result));
	}
	
	public function getDialogues($user_id)
	{
		$query = "SELECT *, u.id as user_id FROM " . core::database()->getTableName('messages') . " m, " . core::database()->getTableName('users') . " u
				WHERE
				CASE
					WHEN m.sender_id='" . $user_id . "' AND m.status IN (0,1,3)
					THEN m.receiver_id=u.id
					WHEN m.receiver_id='" . $user_id . "' AND m.status IN (0,1,2)
					THEN m.sender_id=u.id
				END
				GROUP by u.id
				ORDER by m.created_at DESC
				";	

		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function getLastMessage($receiver_id, $user_id)
	{
		$query = "SELECT *, a.id AS id, b.id AS user_id, DATE_FORMAT(a.created_at,'%d.%m.%y %H:%i') as created FROM " . core::database()->getTableName('messages') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.sender_id  
					WHERE ((sender_id=" . $user_id . " AND receiver_id=" . $receiver_id . " AND a.status IN (0,1,3)) OR (sender_id=" . $receiver_id . " AND receiver_id=" . $user_id . " AND a.status IN (0,1,2))) 
					ORDER by a.created_at DESC 
					LIMIT 1";
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function markReadMsg($sender_id, $receiver_id)
	{
		$fields = array();
		$fields['status'] = 1;
		
		return core::database()->update($fields, core::database()->getTableName('messages'), "(status=0) AND (receiver_id=" . $receiver_id . ")  AND (sender_id=" . $sender_id. ")");
	}	
	
	public function permissionSendMessage($user_id, $friend, $permission)
	{
		if(is_numeric($user_id) && is_numeric($friend)){
			$permit = true;
		
			if($permission == 1){
				$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=1) AND ((friend_id=" . $friend . ") AND (user_id=" . $user_id . ") OR (friend_id=" . $user_id . ") AND (user_id=" . $friend . "))";
				$result = core::database()->querySQL($query);
		
				if(core::database()->getRecordCount($result) == 0) $permit = false;
			}
			else if($permission == 2){
				$permit = false;
			}
		
			$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=2) AND (friend_id=" . $friend . ") AND (user_id=" . $user_id . ")";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0) {}
			else $permit = false;

			return $permit;
		}
	}
}