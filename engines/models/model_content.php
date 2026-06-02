<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_content extends Model
{
    public function getContentInfo($id_content)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('content') . " WHERE hide='show' AND id=" . $id_content;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);
	}
	
	public function existContent($id_content)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('content') . " WHERE hide='show' AND id=" . $id_content;
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return TRUE;
		else
			return FALSE;
	}
}