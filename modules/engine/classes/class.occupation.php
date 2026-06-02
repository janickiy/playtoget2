<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Occupation
{	
	static function getOccupationsList($user_id, $kind)
	{
		if(is_numeric($user_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('occupations') . " WHERE kind=" . $kind . " AND user_id=" . $user_id;
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
}