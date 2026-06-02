<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Events
{
	static function getEventsList($limit = 5, $offset = 0)
	{
		$from = "" . core::database()->getTableName('events') . " e LEFT JOIN " . core::database()->getTableName('accepted_event_members') . " a ON a.id_event=e.id";

		$place = core::database()->escape(Core_Array::getRequest('place'));
		$sport = core::database()->escape(Core_Array::getRequest('sport'));

		if (Core_Array::getRequest('search') or !empty($place) or !empty($sport)) {
			$_search = core::database()->escape(Core_Array::getRequest('search'));			
         
            $temp = strtok($_search, " ");
            $temp = "%" . $temp . "%";
            
            while ($temp) {
                if ($is_query)
                    $tmp .= " OR (e.name LIKE '" . $temp . "' OR e.description LIKE '" . $temp . "') ";
                else
                    $tmp .= "(e.name LIKE '" . $temp . "' OR e.description LIKE '" . $temp . "') ";
                
                $is_query = true;
                $temp = strtok(" ");
            }
			
			$additional_pars = '';
			if(!empty($place)) $additional_pars .= " AND (place LIKE '" . $place . "')";
			if(!empty($sport)) $additional_pars .= " AND (sport_type LIKE '" . $sport . "')";
			
			$parameters = "*,DATE_FORMAT(e.created_at,'%d.%m.%y') AS putdate_created, e.id AS id_event";
			$where = "WHERE e.banned!=1 " . ((!empty($tmp)) ? 'AND' : '') . " " . $tmp . "" . $additional_pars . "";
			$group = "GROUP BY e.id";
			$order = "ORDER BY e.name";
			$limit = "LIMIT " . $limit . " OFFSET " . $offset . "";
         
        } else {
			$parameters = "*,DATE_FORMAT(e.created_at,'%d.%m.%y') AS putdate_created, e.id AS id_event";
			$where = "WHERE e.banned!=1";
			$group = "GROUP BY e.id";
			$order = "ORDER BY e.name";
			$limit = "LIMIT " . $limit . " OFFSET " . $offset . "";
        }
        
        $result = core::database()->select($parameters, $from, $where, $group, $order, $limit);
		
        return core::database()->getColumnArray($result);
	}		
	
	static function getSearchEventsList($id_member, $eventable_type, $limit = 5, $offset = 0)
	{
		if(is_numeric($id_member)){
			$from = "" . core::database()->getTableName('events') . " e LEFT JOIN " . core::database()->getTableName('accepted_event_members') . " a ON a.id_event=e.id";

			$place = core::database()->escape(Core_Array::getRequest('place'));
			$sport = core::database()->escape(Core_Array::getRequest('sport'));

			if (Core_Array::getRequest('search') or !empty($place) or !empty($sport)) {
				$_search = core::database()->escape(Core_Array::getRequest('search'));			
         
				$temp = strtok($_search, " ");
				$temp = "%" . $temp . "%";
            
				while ($temp) {
					if ($is_query)
						$tmp .= " OR (e.name LIKE '" . $temp . "' OR e.description LIKE '" . $temp . "') ";
					else
						$tmp .= "(e.name LIKE '" . $temp . "' OR e.description LIKE '" . $temp . "') ";
                
					$is_query = true;
					$temp = strtok(" ");
				}
			
				$additional_pars = '';
				if(!empty($place)) $additional_pars .= " AND (place LIKE '" . $place . "')";
				if(!empty($sport)) $additional_pars .= " AND (sport LIKE '" . $sport . "')";
			
				$parameters = "*,DATE_FORMAT(e.created_at,'%d.%m.%y') AS putdate_created, e.id AS id_event";
				$where = "WHERE (e.banned!=1) AND " . ((!empty($tmp)) ? 'AND' : '') . " " . $tmp . "" . $additional_pars . "";
				$group = "GROUP BY e.id";
				$order = "ORDER BY e.name";
				$limit = "LIMIT " . $limit . " OFFSET " . $offset . "";
         
			} else {
				$parameters = "*,DATE_FORMAT(e.created_at,'%d.%m.%y') AS putdate_created, e.id AS id_event";
				$where = "WHERE e.banned!=1";
				$group = "GROUP BY e.id";
				$order = "ORDER BY e.name";
				$limit = "LIMIT " . $limit . " OFFSET " . $offset . "";
			}
        
			$result = core::database()->select($parameters, $from, $where, $group, $order, $limit);
		
			return core::database()->getColumnArray($result);
		}		
	}	
	
	static function getPopularEventList($limit = 5, $offset = 0)
	{
		$query = "SELECT *, sum(a.id_member) pop, e.id AS id, a.id AS id_member, DATE_FORMAT(e.date_from,'%d.%m.%y %H:%i') AS date_beginning, DATE_FORMAT(e.date_from,'%H:%i') AS time_from, DATE_FORMAT(e.date_to,'%d.%m.%y %H:%i') AS date_end, DATE_FORMAT(e.date_to,'%H:%i') AS time_to FROM " . core::database()->getTableName('events') . " e
					INNER JOIN " . core::database()->getTableName('accepted_event_members') . " a ON a.id_event=e.id					
					WHERE (e.banned!=1) AND ((e.date_to IS NULL AND e.date_from > NOW()) OR e.date_to > NOW())				
					GROUP by e.id
					ORDER by pop DESC
					LIMIT " . $limit . " OFFSET " . $offset . "";

		$result = core::database()->querySQL($query);

		return core::database()->getColumnArray($result);	
	}  

 	static function getNumberPopularEvents()
	{
		$query = "SELECT *, sum(a.id_member) pop FROM " . core::database()->getTableName('events') . " e
					INNER JOIN " . core::database()->getTableName('accepted_event_members') . " a ON a.id_event=e.id					
					WHERE (e.banned!=1) AND ((e.date_to IS NULL AND e.date_from > NOW()) OR e.date_to > NOW())	
					GROUP by e.id";
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}
	
	static function changememberstatus($id_event, $id_member, $eventable_type, $status)
	{
		if(is_numeric($id_event) && is_numeric($id_member) && $eventable_type && Events::checkBlocked($id_event)){
			if($status == 1){
				$query = "SELECT * FROM " . core::database()->getTableName('accepted_event_members') . " WHERE (id_event=" . $id_event . ") AND (eventable_type='" . $eventable_type . "') AND (id_member=" . $id_member . ")";
				$result = core::database()->querySQL($query);
			
				if(core::database()->getRecordCount($result) == 0){
					$fields = array();
					$fields['id'] = 0;				
					$fields['eventable_type'] = $eventable_type;				
					$fields['id_member'] = $id_member;				
					$fields['role'] = 3;				
					$fields['id_event'] = $id_event;				
				
					$result = core::database()->insert($fields, core::database()->getTableName('accepted_event_members'));
				
					if($result) 
						return TRUE;
					else
						return FALSE;
				}
				else{
					$query = "SELECT * FROM " . core::database()->getTableName('accepted_event_members') . " WHERE (id_event=" . $id_event . ") AND (eventable_type='" . $eventable_type . "') AND (id_member=" . $id_member . ")";
					$result = core::database()->querySQL($query);
					$row = core::database()->getRow($result);
				
					if($row['role'] == 4){
						$fields = array();
						$fields['role'] = 3;
			
						$update = core::database()->update($fields, core::database()->getTableName('accepted_event_members'), "(id_event=" . $id_event . ") AND (eventable_type='" . $eventable_type . "') AND (id_member=" . $id_member . ")"); 
			
						if($update) 
							return TRUE;
						else
							return FALSE;
					
					}
					else
						return TRUE;								
				}
			}
			else if($status == 0){
				if(core::database()->delete(core::database()->getTableName('accepted_event_members'), "(id_event=" . $id_event . ") AND (eventable_type='" . $eventable_type . "') AND (id_member=" . $id_member . ")", '')){
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
		}
	}

	static function change_member_role($id_event, $id_member, $eventable_type, $role)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('accepted_event_members') . " WHERE (id_event=" . $id_event . ") AND (eventable_type='" . $eventable_type . "') AND (id_member=" . $id_member . ")";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0){
			$fields = array();
			$fields['id'] = 0;				
			$fields['eventable_type'] = $eventable_type;				
			$fields['id_member'] = $id_member;				
			$fields['role'] = $role;				
			$fields['id_event'] = $id_event;
			
			$insert = core::database()->insert($fields, core::database()->getTableName('accepted_event_members'));
				
			if($insert) 
				return TRUE;
			else
				return FALSE;
		}
		else{
			$fields = array();
			$fields['role'] = $role;
			
			$update = core::database()->update($fields, core::database()->getTableName('accepted_event_members'), "id=" . $id_event); 
			
			if($update) 
				return TRUE;
			else
				return FALSE;
		}	
	}	
	
	static function getMyEvents($id_member, $eventable_type, $limit = 5, $offset = 0)
	{
		if(is_numeric($id_member) && $eventable_type){
			$query = "SELECT *, DATE_FORMAT(date_from,'%d.%m.%y %H:%i') AS date_beginning, DATE_FORMAT(date_from,'%H:%i') AS time_from, DATE_FORMAT(date_to,'%d.%m.%y %H:%i') AS date_end, DATE_FORMAT(date_to,'%H:%i') AS time_to, e.id AS id_event FROM " . core::database()->getTableName('events') . " e 
						LEFT JOIN " . core::database()->getTableName('accepted_event_members') . " a ON a.id_event=e.id
						WHERE (e.banned!=1) AND (a.role IN (1,2,3)) AND (a.eventable_type='" . $eventable_type . "') AND (a.id_member=" . $id_member . ")
						GROUP BY e.id
						ORDER by a.role 					
						LIMIT " . $limit . " OFFSET " . $offset ."";			
				
			$result = core::database()->querySQL($query);		
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function countMembers($id_event, $eventable_type)
	{
		if(is_numeric($id_event) && $eventable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('accepted_event_members') . " WHERE (role IN (1,2,3)) AND (eventable_type='" . $eventable_type . "') AND (id_event=" . $id_event . ")";
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getEventStatus($id)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('events') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result);
		
			$time = strtotime(date("Y-m-d H:i:s"));
		
			if($time < strtotime($row['date_from']) && !$row['date_to'])
				return 'none';
			else if($time > strtotime($row['date_from']) && !$row['date_to'])
				return 'continues';
			else if($time > strtotime($row['date_from']) && $time < strtotime($row['date_to']))
				return 'continues';
			else if($row['date_to'] && $time > strtotime($row['date_to']))
				return 'end';
		}
	}
	
	static function checkOwnerEvent($id, $id_member, $eventable_type)
	{
		if(is_numeric($id) && is_numeric($id_member)){
			$query = "SELECT * FROM " . core::database()->getTableName('accepted_event_members') . " WHERE (role=1) AND (eventable_type='" . $eventable_type . "') AND (id_member=" . $id_member . ") AND (id_event=" . $id . ")";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0)
				return FALSE;
			else
				return TRUE;	
		}		
	}
	
	static function checkEventMember($id, $id_member, $eventable_type)
	{
		if(is_numeric($id) && is_numeric($id_member)){
			$query = "SELECT * FROM " . core::database()->getTableName('accepted_event_members') . " WHERE (eventable_type='" . $eventable_type . "') AND (id_member=" . $id_member . ") AND (id_event=" . $id . ")";
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result);
		
			return $row['role'];
		}		
	}

	static function getEventInfo($id_event)
	{
		if(is_numeric($id_event)){
			$query = "SELECT * FROM " . core::database()->getTableName('events') . " WHERE id=" . $id_event;
			$result = core::database()->querySQL($query);
		
			return core::database()->getRow($result);
		}
	}	

	static function getNumberMyEvents($id_member, $eventable_type)
	{
		if(is_numeric($id_member) && $eventable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('events') . " e
						LEFT JOIN " . core::database()->getTableName('accepted_event_members') . " m ON m.id_event=e.id
						WHERE (e.banned!=1) AND (m.role IN (1,2,3)) AND (m.eventable_type='" . $eventable_type . "') AND (m.id_member=" . $id_member . ")";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}		
	}
	
	static function addEvent($fields){
		
		if($fields['cover_page'] && file_exists('tmp/' . $fields['cover_page'])){
			rename('tmp/' . $fields['cover_page'], PATH_EVENTS_COVER_PAGE_IMAGES . $fields['cover_page']);
		}

		return core::database()->insert($fields, core::database()->getTableName('events'));
	}
	
	static function addMember($id_event, $id_member, $eventable_type, $status)
	{
		$fields = array();
		$fields['id'] = 0;
		$fields['eventable_type'] = $eventable_type;
		$fields['id_member'] = $id_member;
		$fields['role'] = $status;
		$fields['id_event'] = $id_event;
		
		return core::database()->insert($fields, core::database()->getTableName('accepted_event_members'));		
	}
	
	static function editEvent($fields, $id_event)
	{
		if($fields['cover_page'] && file_exists('tmp/' . $fields['cover_page'])){
			$query = "SELECT cover_page FROM " . core::database()->getTableName('events') . " WHERE id=" . $id_event;
			$result = core::database()->querySQL($query);		
			$row = core::database()->getRow($result);
			
			if(file_exists(PATH_EVENTS_COVER_PAGE_IMAGES . $row['cover_page'])) unlink(PATH_EVENTS_COVER_PAGE_IMAGES . $row['cover_page']);
			
			rename('tmp/' . $fields['cover_page'], PATH_EVENTS_COVER_PAGE_IMAGES . $fields['cover_page']);
		}
		
		$result = core::database()->update($fields, core::database()->getTableName('events'), "id=" . $id_event); 
		
		return $result;
	}
	
	static function getEventsMemberList($id_event, $eventable_type)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('events') . " e
					LEFT JOIN " . core::database()->getTableName('accepted_event_members') . " a ON e.id=a.id_event
					WHERE (a.eventable_type='" . $eventable_type . "') AND (a.role IN(1,2,3)) AND (a.id_event=" . $id_event . ")";
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}
	
	static function getEventsOfMember($id_member, $eventable_type)
	{
		if(is_numeric($id_member) && $eventable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('events') . " e
						LEFT JOIN " . core::database()->getTableName('accepted_event_members') . " a ON e.id=a.id_event
						WHERE a.eventable_type='" . $eventable_type . "' AND a.id_member=" . $id_member;
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function getEventRole($role)
	{
		if($role == 1)
			return core::getLanguage('str', 'owner');
		else if($role == 2)
			return core::getLanguage('str', 'admin');
		else if($role == 3)
			return core::getLanguage('str', 'member');
	}
	
	static function getMemberShipStatus($id_event, $id_member, $eventable_type)
	{
		if(is_numeric($id_event) && is_numeric($id_member) && $eventable_type){
			$query = "SELECT role FROM " . core::database()->getTableName('accepted_event_members') . " WHERE (id_event=" . $id_event . ") AND (eventable_type='" . $eventable_type . "') AND (id_member=" . $id_member .")";
			$result = core::database()->querySQL($query);
		
			$row = core::database()->getRow($result);
		
			return $row['role'];
		}
	}
	
	static function checkExistence($id)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('events') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0)
				return TRUE;
			else
				return FALSE;	
		}		
	}
	
	static function getNumberInvitedMeEvents($id_member, $eventable_type)
	{
		if(is_numeric($id_member) && $eventable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('events') . " e
						LEFT JOIN " . core::database()->getTableName('accepted_event_members') . " m ON m.id_event=e.id
						WHERE (e.banned!=1) AND (m.role=4) AND (m.eventable_type='" . $eventable_type . "') AND (m.id_member=" . $id_member . ")";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getInvitedMeEvents($id_member, $eventable_type, $limit = 5, $offset = 0)
	{
		if(is_numeric($id_member) && $eventable_type){
			$query = "SELECT *, DATE_FORMAT(date_from,'%d.%m.%y %H:%i') AS date_beginning, DATE_FORMAT(date_from,'%H:%i') AS time_from, DATE_FORMAT(date_to,'%d.%m.%y %H:%i') AS date_end, DATE_FORMAT(date_to,'%H:%i') AS time_to, e.id AS id_event FROM " . core::database()->getTableName('events') . " e 
						LEFT JOIN " . core::database()->getTableName('accepted_event_members') . " a ON a.id_event=e.id
						WHERE (e.banned!=1) AND (a.role=4) AND (a.eventable_type='" . $eventable_type . "') AND (a.id_member=" . $id_member . ")
						GROUP BY e.id
						ORDER by a.role 					
						LIMIT " . $limit . " OFFSET " . $offset ."";			
				
			$result = core::database()->querySQL($query);		
		
			return core::database()->getColumnArray($result);
		}
	}	
	
	static function checkBlocked($id){
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('events') . " WHERE banned=1 AND id=" . $id;
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0)
				return TRUE;
			else
				return FALSE;	
		}	
	}
	
	static function getEventName($id)
	{
		if(is_numeric($id)){
			$query = "SELECT name FROM " . core::database()->getTableName('events') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
			
			$row = core::database()->getRow($result);
		
			return $row['name'];
		}
	}
	
	
}