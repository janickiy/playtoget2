<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_edit_profile extends Model
{
	public function getProfileEdit($user_id){
		if(preg_match("|^[\d]*$|",$user_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=".$user_id;
			$result = core::database()->querySQL($query);
			return core::database()->getRow($result);
		}	
	}
	
	public function getSportLevelList(){
		$query = "SELECT * FROM " . core::database()->getTableName('sport_level') . " ORDER by name";
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);		
	}
	
	public function getAchivmentsList($user_id){
		$user_id = core::database()->escape($user_id);
		
		$query = "SELECT * FROM " . core::database()->getTableName('users_sport_types') . " WHERE user_id=" . $user_id;
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
}