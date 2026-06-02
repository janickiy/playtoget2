<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Places
{
	static function getCityList($id_country)
	{
		if(is_numeric($id_country)){
			$query = "SELECT * FROM " . core::database()->getTableName('geo_city') . " WHERE id_country=" . $id_country ." ORDER by sort";
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	} 
	
	static function searchCity($str, $id_country=149, $lang='ru')
	{
		if($lang == 'ru') $query = "SELECT *, name_ru AS name FROM " . core::database()->getTableName('geo_city') . " WHERE id_country=" . $id_country ." AND name_ru LIKE '" . $str . "%'";
		else if($lang == 'en') $query = "SELECT *, name_en AS name FROM " . core::database()->getTableName('geo_city') . " WHERE id_country=" . $id_country ." AND name_en LIKE '" . $str . "%'";
		
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	static function getCountry($id)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('geo_country') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);		
			$row = core::database()->getRow($result);
		
			return $row;
		}
	}	
	
	static function getRegion($id)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('geo_region') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);		
			$row = core::database()->getRow($result);
		
			return $row;
		}		
	}	
	
	static function getCityInfo($id)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('geo_city') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);		
			$row = core::database()->getRow($result);
		
			return $row;
		}
	}

	static function getRegionByCity($id_city)
	{
		if(is_numeric($id_city)){
			$query = "SELECT *,r.name_ru AS name_ru,r.name_en AS name_en, r.id AS id_region FROM " . core::database()->getTableName('geo_region') . " r
						LEFT JOIN " . core::database()->getTableName('geo_city') . " c ON r.id=c.id_region
						WHERE c.id=" . $id_city;
						
			$result = core::database()->querySQL($query);		
			$row = core::database()->getRow($result);
		
			return $row;			
		}
	}	
	
	static function getCountryByCity($id_city)
	{
		if(is_numeric($id_city)){
			$query = "SELECT *,cr.name_ru AS name_ru, cr.name_en AS name_en, cr.id AS id_country FROM " . core::database()->getTableName('geo_country') . " cr
						LEFT JOIN " . core::database()->getTableName('geo_city') . " ct ON cr.id=ct.id_country
						WHERE ct.id=" . $id_city;
						
			$result = core::database()->querySQL($query);		
			$row = core::database()->getRow($result);
		
			return $row;			
		}
	}	
	
	static function getTargetPlaceId($id_target, $target_type)
	{
		if(is_numeric($id_target)){
			$query = "SELECT * FROM " . core::database()->getTableName('geo_target') . " WHERE target_type='" . $target_type . "' AND id_target=" . $id_target;
			$result = core::database()->querySQL($query);		
			$row = core::database()->getRow($result);
		
			return $row['id_city'];
		}
	}
	
	static function addGeoTarget($id_target, $target_type, $id_city)
	{
		if(is_numeric($id_target)){
			$country = Places::getCountryByCity($id_city);
			$region = Places::getRegionByCity($id_city);	
					
			$query = "SELECT * FROM " . core::database()->getTableName('geo_target') . " WHERE target_type='" . $target_type . "' AND id_target=" . $id_target;
			$result = core::database()->querySQL($query);	

			if(core::database()->getRecordCount($result) == 0){
				$fields = array();
				$fields['id'] = 0;
				$fields['target_type'] = $target_type;
				$fields['id_target'] = $id_target;
				$fields['id_country'] = $country['id_country']; 	
				$fields['id_region'] = $region['id_region']; 
				$fields['id_city'] = $id_city;
				
				if(core::database()->insert($fields, core::database()->getTableName('geo_target')))
					return TRUE;
				else
					return FALSE;
			}
			else{
				$fields = array();
				$fields['id_country'] = $country['id_country']; 	
				$fields['id_region'] = $region['id_region']; 
				$fields['id_city'] = $id_city;
				
				if(core::database()->update($fields, core::database()->getTableName('geo_target'), "id_target=" . $id_target ." AND target_type='" . $target_type . "'"))
					return TRUE;
				else
					return FALSE;
			}			
		}
	}	
}