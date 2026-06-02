<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Sport
{
	static function getSportTypeList()
	{
		$query = "SELECT * FROM " . core::database()->getTableName('sport_types') . " ORDER by name";
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);		
	}	
	
	static function getSportType($id)
	{
		if(is_numeric($id)){
			$query = "SELECT name FROM " . core::database()->getTableName('sport_types') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result);
		
			return $row['name'];
		}
	}
	
	static function searchSportTypes($str)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('sport_types') . " WHERE name LIKE '" . $str . "%'";
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}

	static function getUserSportTypeList($id_user)
	{
		if(is_numeric($id_user)){
			$query = "SELECT *, l.name AS sport_level FROM " . core::database()->getTableName('users_sport_types') . " u
						LEFT JOIN " . core::database()->getTableName('sport_level') . " l ON u.id_sport_level=l.id
						WHERE u.id_user=" . $id_user;
			
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	

}