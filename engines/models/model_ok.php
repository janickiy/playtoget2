<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_ok extends Model
{
	public function checkExistUser($email){
		$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE email LIKE '" . $email . "'";
        $result = core::database()->querySQL($query);
		
		return (core::database()->getRecordCount($result) == 0) ? false : true;		
	}
	
	public function getUserId($email){
		$query = "SELECT id FROM " . core::database()->getTableName('users') . " WHERE email LIKE '" . $email . "'";
        $result = core::database()->querySQL($query);
		
		$row = core::database()->getRow($result);
		
		return $row['id'];
	}
	
	public function addUser($fields)
    {
        return core::database()->insert($fields, core::database()->getTableName('users'));
    }
}
   