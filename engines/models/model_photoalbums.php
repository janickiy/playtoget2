<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_photoalbums extends Model
{
	public function NumberTotalPopPhotos($id_user)
	{
		$id_user = core::database()->escape($id_user);
		
		$query = "SELECT * FROM " . core::database()->getTableName('photos') ." WHERE id_owner=" . $id_user;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}	
}