<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_sitemap extends Model
{
	public function sitemap()
	{
		$out = array();
		
		$query = "SELECT *, DATE_FORMAT(created_at,'%d.%m.%Y') AS created, DATE_FORMAT(updated_at,'%d.%m.%Y') AS updated FROM " . core::database()->getTableName('users') . " WHERE (confirmed=1) AND (banned!=1) AND (deleted!=1)";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) > 0)	{
			while($row = core::database()->getRow($result)){
				$out[] = array('id' => $row['id'], 'type' => 'user', 'created' => $row['created'], 'updated' => $row['updated'] ? $row['updated'] : $row['created'], 'priority' => '0.9');
			}
		}
		
		$query = "SELECT *, DATE_FORMAT(created_at,'%d.%m.%Y') AS created, DATE_FORMAT(updated_at,'%d.%m.%Y') AS updated FROM " . core::database()->getTableName('communities') . " WHERE banned!=1";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) > 0)	{
			while($row = core::database()->getRow($result)){
				$out[] = array('id' => $row['id'], 'type' => $row['type'], 'created' => $row['created'], 'updated' => $row['updated'] ? $row['updated'] : $row['created'], 'priority' => '0.7');
			}
		}
		
		$query = "SELECT *, DATE_FORMAT(created_at,'%d.%m.%Y') AS created, DATE_FORMAT(updated_at,'%d.%m.%Y') AS updated FROM " . core::database()->getTableName('events') . " WHERE banned!=1";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) > 0)	{
			while($row = core::database()->getRow($result)){
				$out[] = array('id' => $row['id'], 'type' => 'event', 'created' => $row['created'], 'updated' => $row['updated'] ? $row['updated'] : $row['created'], 'priority' => '0.9');
			}
		}
		
		$query = "SELECT *, DATE_FORMAT(created_at,'%d.%m.%Y') AS created, DATE_FORMAT(updated_at,'%d.%m.%Y') AS updated FROM " . core::database()->getTableName('sport_blocks') . " WHERE banned!=1";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) > 0)	{
			while($row = core::database()->getRow($result)){
				$out[] = array('id' => $row['id'], 'type' => $row['type'], 'created' => $row['created'], 'updated' => $row['updated'] ? $row['updated'] : $row['created'], 'priority' => '0.7');
			}
		}
		
		return $out;		
	}	
}