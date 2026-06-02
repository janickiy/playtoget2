<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_search extends Model
{
	public function getUsersList($limit, $offset = 0)
	{
		$from = "" . core::database()->getTableName('users') . " u LEFT JOIN " . core::database()->getTableName('users_sport_types') . " t ON u.id=id_user";
		$parameters = "*, u.id AS id";
		
		$place = core::database()->escape(Core_Array::getRequest('place'));
		$sport = core::database()->escape(Core_Array::getRequest('sport'));
		$sex = core::database()->escape(Core_Array::getRequest('sex'));
		$photo = core::database()->escape(Core_Array::getRequest('photo'));
		
		if (Core_Array::getRequest('search')) {
            $_search = core::database()->escape(Core_Array::getRequest('search'));			
         
            $temp = strtok($_search, " ");
            $temp = "%" . $temp . "%";
            
            while ($temp) {
                if ($is_query)
                    $tmp .= " OR (u.firstname LIKE '" . $temp . "' OR u.lastname LIKE '" . $temp . "' OR u.secondname LIKE '" . $temp . "') ";
                else
                    $tmp .= "(u.firstname LIKE '" . $temp . "' OR u.lastname LIKE '" . $temp . "' OR u.secondname LIKE '" . $temp . "') ";
                
                $is_query = true;
                $temp = strtok(" ");
            }
			
			$additional_pars = '';
			$additional_pars .= " " . ((!empty($tmp)) ? 'AND' : '') . " (u.confirmed='1') AND (u.banned='0') AND (u.deleted='0')";
			
			if(!empty($place)) $additional_pars .= " AND (u.city LIKE '" . $place . "')";
			if(!empty($sport)) $additional_pars .= " AND (t.sport_type LIKE '" . $sport . "')";
			if(!empty($sex)) $additional_pars .= " AND (u.sex LIKE '" . $sex . "')";
			if($photo == 1) $additional_pars .= " AND (u.avatar != '')";
			
			$where = "WHERE " . $tmp . "" . $additional_pars . ""; 
			$group = "GROUP BY u.id";
			$order = "ORDER BY u.lastname";
			$limit = "LIMIT " . $limit . " OFFSET " . $offset . "";
         
        } else {
			$group = "GROUP BY u.id";
			$order = "ORDER BY u.id";
			$limit = "LIMIT " . $limit . " OFFSET " . $offset . "";
        }
        
        $result = core::database()->select($parameters, $from, $where, $group, $order, $limit);
		
		return core::database()->getColumnArray($result);		
	}    
}