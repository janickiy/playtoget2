<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_confirmation extends Model
{
	public function checkConfirm()
	{
		$_GET['id'] = core::database()->escape($_GET['id']);
		
		$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE confirmed=1 and id=".$_GET['id'];
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return FALSE;
		else
			return TRUE;
		
	}
	
	public function checkToken(){
		$_GET['id'] = core::database()->escape($_GET['id']);
		
		$query = "SELECT confirmation_token FROM " . core::database()->getTableName('users') . " WHERE id=".$_GET['id'];
		$result = core::database()->querySQL($query);
		$row = core::database()->getRow($result);
		
		if($row['confirmation_token'] == $_GET['confirmation_token'])
			return TRUE;
		else
			return FALSE;	
	}  
	
	public function doConfirm(){
		$_GET['id'] = core::database()->escape($_GET['id']);
		
		$query = "UPDATE " . core::database()->getTableName('users') . " SET confirmed=1, confirmed_at=NOW() WHERE id=".$_GET['id'];
		
		return core::database()->querySQL($query);		
	}	
}