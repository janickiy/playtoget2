<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class SportBlocks
{
	static function getSportBlocksList($type, $limit = 5, $offset = 0)
	{
		$from = "" . core::database()->getTableName('sport_blocks') . "";
		
		$place = core::database()->escape(Core_Array::getRequest('place'));
		
		if (Core_Array::getRequest('search') or !empty($place)) {
			$_search = core::database()->escape(Core_Array::getRequest('search'));			
         
			$temp = strtok($_search, " ");
			$temp = "%" . $temp . "%";
            
			while ($temp) {
				if ($is_query)
					$tmp .= " OR (name LIKE '" . $temp . "' OR address LIKE '" . $temp . "') ";
				else
					$tmp .= "(name LIKE '" . $temp . "' OR address LIKE '" . $temp . "') ";
                
				$is_query = true;
				$temp = strtok(" ");
			}
			
			$additional_pars = '';
			if(!empty($place)) $additional_pars .= " AND (place LIKE '" . $place . "')";
			//$additional_pars .= " AND (active=1)";
			
			$parameters = "*,DATE_FORMAT(created_at,'%d.%m.%y') AS created";
			$where = "WHERE (banned!=1) AND (type='" . $type . "') ".((!empty($tmp)) ? 'AND' : '')." " . $tmp . "" . $additional_pars . "";
			$group = "GROUP BY id";
			$order = "ORDER BY id";
			$limit = "LIMIT " . $limit . " OFFSET " . $offset . "";
         
		} else {
			$parameters = "*,DATE_FORMAT(created_at,'%d.%m.%y') AS created";
			$where = "WHERE banned!=1 AND type='" . $type . "'";
			$group = "GROUP BY id";
			$order = "ORDER BY id";
			$limit = "LIMIT " . $limit . " OFFSET " . $offset . "";
		}
        
		$result = core::database()->select($parameters, $from, $where, $group, $order, $limit);
		
		return core::database()->getColumnArray($result);		
	}
	
	static function createSportBlock($fields)
	{
		if($fields['avatar'] && file_exists('tmp/' . $fields['avatar'])){
			rename('tmp/' . $fields['avatar'], PATH_SPORTBLOCKS_AVATAR_IMAGES . $fields['avatar']);
		}		
		
		return core::database()->insert($fields, core::database()->getTableName('sport_blocks'));
	}	
	
	static function getSportBlocksInfo($id)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('sport_blocks') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
		
			return core::database()->getRow($result);
		}		
	}
	
	static function checkOwner($id_sport_block, $id_owner)
	{		
		$query = "SELECT * FROM " . core::database()->getTableName('sport_blocks') . " WHERE id=" . $id_sport_block . " AND id_owner=" . $id_owner;
		$result =  core::database()->querySQL($query);
				
		if(core::database()->getRecordCount($result) == 0)
			return FALSE;
		else
			return TRUE;
	}
	
	static function getMySportBlocks($type, $id_owner, $limit = 5, $offset = 0)
	{
		if(is_numeric($id_owner) && $type){
			$query = "SELECT * FROM " . core::database()->getTableName('sport_blocks') . " WHERE (banned!=1) AND (type='" . $type . "') AND (id_owner=" . $id_owner . ")";
			$result =  core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function getSportBlocks($type, $limit, $offset = 0)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('sport_blocks') . " WHERE banned!=1 AND type='" . $type . "'";
		$result =  core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	static function getNumberSportBlocks($sport_block_type)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('sport_blocks') . " WHERE banned!=1 AND type='" . $sport_block_type . "'";
		$result =  core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}
	
	static function editSportBlock($fields, $id_sport_block)
	{
		if($fields['avatar'] && file_exists('tmp/' . $fields['avatar'])){
			$query = "SELECT avatar FROM " . core::database()->getTableName('sport_blocks') . " WHERE id=" . $id_sport_block;
			$result =  core::database()->querySQL($query);
			$row = core::database()->getRow($result);
			
			if(file_exists(PATH_SPORTBLOCKS_AVATAR_IMAGES . $row['avatar'])) unlink(PATH_SPORTBLOCKS_AVATAR_IMAGES . $row['avatar']);
			
			rename('tmp/' . $fields['avatar'], PATH_SPORTBLOCKS_AVATAR_IMAGES . $fields['avatar']);
		}		
		
		return core::database()->update($fields, core::database()->getTableName('sport_blocks'), "id=" . $id_sport_block); 
	}

	static function lastSportBlock($type, $limit = 3)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('sport_blocks') . " WHERE banned!=1 AND type='" . $type . "' ORDER BY id DESC LIMIT " . $limit . "";
		$result =  core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	static function getSportBlockIdPhotoAlbum($id_sport_block, $photoalbumable_type)
	{
		if(is_numeric($id_sport_block) && $photoalbumable_type){
			$query = "SELECT id FROM " . core::database()->getTableName('photoalbums') . " WHERE photoalbumable_type='" . $photoalbumable_type . "' AND id_owner=" . $id_sport_block;
			$result =  core::database()->querySQL($query);
			$row = core::database()->getRow($result);
		
			return $row['id'];
		}
	}

	static function checkExistence($id, $type)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('sport_blocks') . " WHERE type='" . $type . "' AND id=" . $id;
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0)
				return TRUE;
			else
				return FALSE;	
		}		
	}
	
	static function getSportBlockName($id)
	{
		if(is_numeric($id)){
			$query = "SELECT name FROM " . core::database()->getTableName('sport_blocks') . " WHERE id=" . $id;
			$result =  core::database()->querySQL($query);
			$row = core::database()->getRow($result);
			
			return $row['name'];
		}
	}
}