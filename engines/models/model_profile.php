<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_profile extends Model
{
	public function getMessagesList($id_receiver, $id_user, $limit)
	{
		$id_receiver = core::database()->escape($id_receiver);
		
		$query = "SELECT *, a.id AS id, b.id AS id_user, DATE_FORMAT(a.created_at,'%d.%m.%y %H:%i') as created FROM " . core::database()->getTableName('messages') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.id_sender  
					WHERE (id_sender=" . $id_user . " AND id_receiver=" . $id_receiver . " AND a.status IN (0,1,3)) OR (id_sender=" . $id_receiver . " AND id_receiver=" . $id_user . " AND a.status IN (0,1,2)) 
					ORDER by a.created_at DESC
					LIMIT " . $limit . "";
		
		$result = core::database()->querySQL($query);
		
		return array_reverse(core::database()->getColumnArray($result));
	}
	
	public function getDialogues($id_user)
	{
		$query = "SELECT *, u.id as id_user FROM " . core::database()->getTableName('messages') . " m, " . core::database()->getTableName('users') . " u
				WHERE
				CASE
					WHEN m.id_sender='" . $id_user . "' AND m.status IN (0,1,3)
					THEN m.id_receiver=u.id
					WHEN m.id_receiver='" . $id_user . "' AND m.status IN (0,1,2)
					THEN m.id_sender=u.id
				END
				GROUP by u.id
				ORDER by m.created_at DESC
				";	

		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function getLastMessage($id_receiver, $id_user)
	{
		$query = "SELECT *, a.id AS id, b.id AS id_user, DATE_FORMAT(a.created_at,'%d.%m.%y %H:%i') as created FROM " . core::database()->getTableName('messages') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.id_sender  
					WHERE ((id_sender=" . $id_user . " AND id_receiver=" . $id_receiver . " AND a.status IN (0,1,3)) OR (id_sender=" . $id_receiver . " AND id_receiver=" . $id_user . " AND a.status IN (0,1,2))) 
					ORDER by a.created_at DESC 
					LIMIT 1";
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	public function markReadMsg($id_sender, $id_receiver)
	{
		$fields = array();
		$fields['status'] = 1;
		
		return core::database()->update($fields, core::database()->getTableName('messages'), "(status=0) AND (id_receiver=" . $id_receiver . ")  AND (id_sender=" . $id_sender. ")");
	}	
	
	public function permissionSendMessage($id_user, $friend, $permission)
	{
		if(is_numeric($id_user) && is_numeric($friend)){
			$permit = true;
		
			if($permission == 1){
				$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=1) AND ((id_friend=" . $friend . ") AND (id_user=" . $id_user . ") OR (id_friend=" . $id_user . ") AND (id_user=" . $friend . "))";
				$result = core::database()->querySQL($query);
		
				if(core::database()->getRecordCount($result) == 0) $permit = false;
			}
			else if($permission == 2){
				$permit = false;
			}
		
			$query = "SELECT * FROM " . core::database()->getTableName('friends') . " WHERE	(status=2) AND (id_friend=" . $friend . ") AND (id_user=" . $id_user . ")";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0) {}
			else $permit = false;

			return $permit;
		}
	}
}