<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_edit_profile extends Model
{
	public function getProfileEdit($id_user){
		if(preg_match("|^[\d]*$|",$id_user)){
			$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=".$id_user;
			$result = core::database()->querySQL($query);
			return core::database()->getRow($result);
		}	
	}
	
	public function getSportLevelList(){
		$query = "SELECT * FROM " . core::database()->getTableName('sport_level') . " ORDER by name";
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);		
	}
	
	public function getAchivmentsList($id_user){
		$id_user = core::database()->escape($id_user);
		
		$query = "SELECT * FROM " . core::database()->getTableName('users_sport_types') . " WHERE id_user=" . $id_user;
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
}