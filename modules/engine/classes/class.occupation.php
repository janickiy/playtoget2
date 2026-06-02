<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Occupation
{	
	static function getOccupationsList($id_user, $kind)
	{
		if(is_numeric($id_user)){
			$query = "SELECT * FROM " . core::database()->getTableName('occupations') . " WHERE kind=" . $kind . " AND id_user=" . $id_user;
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
}