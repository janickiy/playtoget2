<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Places
{
	static function getCityList($country_id)
	{
		if(is_numeric($country_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('geo_city') . " WHERE country_id=" . $country_id ." ORDER by sort";
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	} 
	
	static function searchCity($str, $country_id=149, $lang='ru')
	{
		if($lang == 'ru') $query = "SELECT *, name_ru AS name FROM " . core::database()->getTableName('geo_city') . " WHERE country_id=" . $country_id ." AND name_ru LIKE '" . $str . "%'";
		else if($lang == 'en') $query = "SELECT *, name_en AS name FROM " . core::database()->getTableName('geo_city') . " WHERE country_id=" . $country_id ." AND name_en LIKE '" . $str . "%'";
		
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

	static function getRegionByCity($city_id)
	{
		if(is_numeric($city_id)){
			$query = "SELECT *,r.name_ru AS name_ru,r.name_en AS name_en, r.id AS region_id FROM " . core::database()->getTableName('geo_region') . " r
						LEFT JOIN " . core::database()->getTableName('geo_city') . " c ON r.id=c.region_id
						WHERE c.id=" . $city_id;
						
			$result = core::database()->querySQL($query);		
			$row = core::database()->getRow($result);
		
			return $row;			
		}
	}	
	
	static function getCountryByCity($city_id)
	{
		if(is_numeric($city_id)){
			$query = "SELECT *,cr.name_ru AS name_ru, cr.name_en AS name_en, cr.id AS country_id FROM " . core::database()->getTableName('geo_country') . " cr
						LEFT JOIN " . core::database()->getTableName('geo_city') . " ct ON cr.id=ct.country_id
						WHERE ct.id=" . $city_id;
						
			$result = core::database()->querySQL($query);		
			$row = core::database()->getRow($result);
		
			return $row;			
		}
	}	
	
	static function getTargetPlaceId($target_id, $target_type)
	{
		if(is_numeric($target_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('geo_target') . " WHERE target_type='" . $target_type . "' AND target_id=" . $target_id;
			$result = core::database()->querySQL($query);		
			$row = core::database()->getRow($result);
		
			return $row['city_id'];
		}
	}
	
	static function addGeoTarget($target_id, $target_type, $city_id)
	{
		if(is_numeric($target_id)){
			$country = Places::getCountryByCity($city_id);
			$region = Places::getRegionByCity($city_id);	
					
			$query = "SELECT * FROM " . core::database()->getTableName('geo_target') . " WHERE target_type='" . $target_type . "' AND target_id=" . $target_id;
			$result = core::database()->querySQL($query);	

			if(core::database()->getRecordCount($result) == 0){
				$fields = array();
				$fields['id'] = 0;
				$fields['target_type'] = $target_type;
				$fields['target_id'] = $target_id;
				$fields['country_id'] = $country['country_id']; 	
				$fields['region_id'] = $region['region_id']; 
				$fields['city_id'] = $city_id;
				
				if(core::database()->insert($fields, core::database()->getTableName('geo_target')))
					return TRUE;
				else
					return FALSE;
			}
			else{
				$fields = array();
				$fields['country_id'] = $country['country_id']; 	
				$fields['region_id'] = $region['region_id']; 
				$fields['city_id'] = $city_id;
				
				if(core::database()->update($fields, core::database()->getTableName('geo_target'), "target_id=" . $target_id ." AND target_type='" . $target_type . "'"))
					return TRUE;
				else
					return FALSE;
			}			
		}
	}	
}