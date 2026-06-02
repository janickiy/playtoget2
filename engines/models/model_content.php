<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_content extends Model
{
    public function getContentInfo($content_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('content') . " WHERE hide='show' AND id=" . $content_id;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);
	}
	
	public function existContent($content_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('content') . " WHERE hide='show' AND id=" . $content_id;
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return TRUE;
		else
			return FALSE;
	}
}