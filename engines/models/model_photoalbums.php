<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_photoalbums extends Model
{
	public function NumberTotalPopPhotos($user_id)
	{
		$user_id = core::database()->escape($user_id);
		
		$query = "SELECT * FROM " . core::database()->getTableName('photos') ." WHERE owner_id=" . $user_id;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}	
}