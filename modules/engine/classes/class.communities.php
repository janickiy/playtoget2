<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Communities
{
	static function getAllCommunitiesList($type, $limit, $offset = 0)
	{
		$from = "" . core::database()->getTableName('communities') . " a LEFT JOIN " . core::database()->getTableName('community_roles') . " b ON b.community_id=a.id";
		
		$place = core::database()->escape(Core_Array::getRequest('place'));
		$sport = core::database()->escape(Core_Array::getRequest('sport'));
		
        if (Core_Array::getRequest('search') or !empty($place) or !empty($sport)) {
            $_search = core::database()->escape(Core_Array::getRequest('search'));			
         
            $temp = strtok($_search, " ");
            $temp = "%" . $temp . "%";
            
            while ($temp) {
                if ($is_query)
                    $tmp .= " OR (name LIKE '" . $temp . "' OR about LIKE '" . $temp . "') ";
                else
                    $tmp .= "(name LIKE '" . $temp . "' OR about LIKE '" . $temp . "') ";
                
                $is_query = true;
                $temp = strtok(" ");
            }
			
			$additional_pars = '';
			
			if(!empty($place)) $additional_pars .= " AND (a.place LIKE '" . $place . "')";
			if(!empty($sport)) $additional_pars .= " AND (a.sport_type LIKE '" . $sport . "')";
			
			$parameters = "*,DATE_FORMAT(created_at,'%d.%m.%y') as putdate_created, a.id as community_id";
			$where = "WHERE (a.type='" . $type . "') AND (a.banned!=1) ".((!empty($tmp)) ? 'AND' : '')." " . $tmp . "" . $additional_pars . "";
			$group = "GROUP BY a.id";
			$order = "ORDER BY a.name";
			$limit = "LIMIT ".$limit." OFFSET ".$offset."";
         
        } else {
			$parameters = "*,DATE_FORMAT(created_at,'%d.%m.%y') as putdate_created, a.id as community_id";
			$where = "WHERE (a.type='" . $type . "') AND (a.banned!=1)";
			$group = "GROUP BY a.id";
			$order = "ORDER BY a.name";
			$limit = "LIMIT ".$limit." OFFSET ".$offset."";
        }
        
        $result = core::database()->select($parameters, $from, $where, $group, $order, $limit);
		
        return core::database()->getColumnArray($result);	
	}
	
	static function getTotalCommunities($type)
	{
		$place = core::database()->escape(Core_Array::getRequest('place'));
		$sport = core::database()->escape(Core_Array::getRequest('sport'));		
		
		if (Core_Array::getRequest('search')) {
            $_search = core::database()->escape(Core_Array::getRequest('search'));
            
			$temp = strtok($_search, " ");
            $temp = "%" . $temp . "%";
            
            while ($temp) {
                if ($is_query)
                    $tmp .= " OR (name LIKE '" . $temp . "' OR about LIKE '" . $temp . "') ";
                else
                    $tmp .= "(name LIKE '" . $temp . "' OR about LIKE '" . $temp . "') ";
                
                $is_query = true;
                $temp = strtok(" ");
            }
			
			$additional_pars = "";
			
			if(!empty($place)) $additional_pars .= " AND (place LIKE '" . $place . "')";
			if(!empty($sport)) $additional_pars .= " AND (sport_type LIKE '" . $sport . "')";
            
            $query = "SELECT *,DATE_FORMAT(created_at,'%d.%m.%y') as putdate_created FROM " . core::database()->getTableName('communities') . " WHERE (type='" . $type . "') AND (banned!=1) ".((!empty($tmp)) ? 'AND' : '')." " . $tmp . "" . $additional_pars . " GROUP BY id";
        
        } else {
			$query = "SELECT *,DATE_FORMAT(created_at,'%d.%m.%y') as putdate_created FROM " . core::database()->getTableName('communities') . " WHERE (type='" . $type . "') AND (banned!=1) ".((!empty($tmp)) ? 'AND' : '')." " . $tmp . "" . $additional_pars . "";
        }
		
        $result = core::database()->querySQL($query);
        return core::database()->getRecordCount($result);		
	}
	
	static function getMyCommunitiesList($user_id, $type, $limit=5, $offset=0)
	{
		if($user_id && $type){
		
			$query = "SELECT *, c.id as community_id FROM " . core::database()->getTableName('communities') . " c 
					LEFT JOIN " . core::database()->getTableName('community_roles') . " r ON r.community_id=c.id 
					WHERE (r.user_id=" . $user_id . ") AND (c.type='" . $type . "') AND (c.banned!=1) AND (r.role IN (1,2,3))
					GROUP BY c.id
					ORDER by r.role 					
					LIMIT " . $limit . " OFFSET " . $offset ."";
				
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);	
		}		
	}

	static function countMemberCommunity($community_id, $role){
		
		if($community_id && $role){
			$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.user_id 
					WHERE role=" . $role . " AND a.community_id=" . $community_id . "
					GROUP by a.user_id";
					
			$result = core::database()->querySQL($query);			
		
			return core::database()->getRecordCount($result);
		}		
	}
	
	static function getMemberList($community_id, $role)
	{
		if($community_id && $role){
			$query = "SELECT *, b.id AS user_id FROM " . core::database()->getTableName('community_roles') . " a 
						LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.user_id 
						WHERE role=" . $role . " AND a.community_id=" . $community_id . "
						GROUP by a.user_id";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}		
	}
	
	static function getMemberAllList($community_id)
	{
		if($community_id){
			$query = "SELECT *, b.id AS user_id FROM " . core::database()->getTableName('community_roles') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.user_id 
					WHERE (role IN (1,2,3)) AND (a.community_id=" . $community_id . ")
					GROUP by a.user_id
					ORDER by a.role
					";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}		
	}
	
	static function countAllMemberCommunity($community_id){
		
		if($community_id){
			$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.user_id 
					WHERE (role IN (1,2,3)) AND (a.community_id=" . $community_id . ")
					GROUP by a.user_id";
					
			$result = core::database()->querySQL($query);			
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getNumberMyCommunities($user_id, $type)
	{
		if($user_id && $type){		
			$query = "SELECT * FROM " . core::database()->getTableName('communities') . " c 
					LEFT JOIN " . core::database()->getTableName('community_roles') . " r ON r.community_id=c.id
					WHERE (r.user_id=" . $user_id . ") AND (c.type='" . $type . "') AND (c.banned!=1) AND (role IN (1,2,3)) 
					GROUP BY c.id";
	
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);	
		}
	}	

	static function getUserStatus($community_id, $user_id)
	{
		if($user_id && $community_id) {
			$query = "SELECT role FROM " . core::database()->getTableName('community_roles') . " WHERE community_id=" . $community_id . " AND user_id=" . $user_id;
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result);
		
			return $row['role'];			
		}		
	}
	
	static function getCommunityInfo($id)
	{
		if($id){
			$query = "SELECT * FROM " . core::database()->getTableName('communities') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
		
			return core::database()->getRow($result);
		}		
	}
	
	static function getCommunitySettings($id)
	{
		if($id){
			$query = "SELECT * FROM " . core::database()->getTableName('communities_settings') . " WHERE community_id=" . $id;
			$result = core::database()->querySQL($query);
		
			return core::database()->getRow($result);
		}
	}
	
	static function getPopularCommunitiesList($type, $limit = 5, $offset = 0)
	{
		if($type){
			$query = "SELECT *, sum(b.user_id) pop, a.id AS community_id FROM " . core::database()->getTableName('communities') . " a 
					INNER JOIN  " . core::database()->getTableName('community_roles') . " b ON b.community_id = a.id
					WHERE (a.type='" . $type. "') AND (a.banned!=1)
					GROUP by a.id
					ORDER by pop DESC
					LIMIT " . $limit . " OFFSET " . $offset . "";

			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}		
	}
	
	static function getNumberPopularCommunities($type)
	{
		if($type){
			$query = "SELECT sum(b.user_id) pop FROM " . core::database()->getTableName('communities') . " a 
					INNER JOIN  " . core::database()->getTableName('community_roles') . " b ON b.community_id = a.id
					WHERE (a.type='" . $type . "') AND (a.banned!=1)
					GROUP by a.id";
					
			$result = core::database()->querySQL($query);

			return core::database()->getRecordCount($result);	
		}		
	}
	
	static function checkOwnerCommunity($community_id, $user_id)
	{
		if($community_id && $user_id){
			$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " WHERE (community_id=" . $community_id . ") and (user_id=" . $user_id . ") and (role=1)";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0) 
				return FALSE;
			else
				return TRUE;
		}
	}
	
	static function checkAdminCommunity($community_id, $user_id)
	{
		if($community_id && $user_id){
			$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " WHERE (community_id=" . $community_id . ") and (user_id=" . $user_id . ") and (role=2)";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0) 
				return FALSE;
			else
				return TRUE;
		}
	}	
	
	static function getCommunityRole($role)
	{
		if($role == 1)
			return core::getLanguage('str', 'owner');
		else if($role == 3)
			return core::getLanguage('str', 'member');
		else if($role == 2)
			return core::getLanguage('str', 'admin');		
	}
	
	static function addNewCommunity($fields, $user_id)
	{
		$insert_id = core::database()->insert($fields, core::database()->getTableName('communities'));
		
		if($insert_id){
			
			if($fields['avatar'] && file_exists('tmp/' . $fields['avatar'])){
				if($fields['type'] == 'group')
					$path = PATH_GROUPCONTENT_AVATAR_IMAGES;
				else if($fields['type'] == 'team')
					$path = PATH_TEAMCONTENT_AVATAR_IMAGES;
				
				rename('tmp/' . $fields['avatar'], $path . $fields['avatar']);
			}
			
			if($fields['cover_page'] && file_exists('tmp/' . $fields['cover_page'])){
				if($fields['type'] == 'group')
					$path = PATH_GROUPCONTENT_COVER_PAGE_IMAGES;
				else if($fields['type'] == 'team')
					$path = PATH_TEAMCONTENT_COVER_PAGE_IMAGES;
				
				rename('tmp/' . $fields['cover_page'], $path . $fields['cover_page']);
			}
			
			$fields = array();
			$fields['id'] = 0;
			$fields['user_id'] = $user_id;
			$fields['community_id'] = $insert_id;
			$fields['role'] = 1;		
			
			core::database()->insert($fields, core::database()->getTableName('community_roles'));
			
			return $insert_id;	
		}			
	}	
	
	static function editCommunity($fields, $settings, $type, $community_id)
	{
		if($fields['avatar'] && file_exists('tmp/' . $fields['avatar'])){
			if($type == 'group')
				$path = PATH_GROUPCONTENT_AVATAR_IMAGES;
			else if($type == 'team')
				$path = PATH_TEAMCONTENT_AVATAR_IMAGES;
			
			$query = "SELECT avatar FROM " . core::database()->getTableName('communities') . " WHERE id=" . $community_id;
			$result =  core::database()->querySQL($query);
			$row = core::database()->getRow($result);
			
			if(file_exists($path . $row['avatar'])) unlink($path . $row['avatar']);
				
			rename('tmp/' . $fields['avatar'], $path . $fields['avatar']);
		}
			
		if($fields['cover_page'] && file_exists('tmp/' . $fields['cover_page'])){
			if($type == 'group')
				$path = PATH_GROUPCONTENT_COVER_PAGE_IMAGES;
			else if($type == 'team')
				$path = PATH_TEAMCONTENT_COVER_PAGE_IMAGES;
			
			$query = "SELECT cover_page FROM " . core::database()->getTableName('communities') . " WHERE id=" . $community_id;
			$result =  core::database()->querySQL($query);
			$row = core::database()->getRow($result);
			
			if(file_exists($path . $row['cover_page'])) unlink($path . $row['cover_page']);
				
			rename('tmp/' . $fields['cover_page'], $path . $fields['cover_page']);
		}		
		
		$result = TRUE;		
			
		core::database()->querySQL('SET AUTOCOMMIT=0');
		core::database()->querySQL('START TRANSACTION');
		
		if(!core::database()->update($fields, core::database()->getTableName('communities'), "id=" . $community_id)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}		
			
		$query = "SELECT * FROM " . core::database()->getTableName('communities_settings') . " WHERE community_id=" . $community_id;
		$result = core::database()->querySQL($query);		
		
		if(core::database()->getRecordCount($result) == 0){
			$set_community = array();
			$set_community['id'] = 0;
			$set_community['permission_wall'] = $settings['permission_wall'];
			$set_community['permission_photo'] = $settings['permission_photo'];			
			$set_community['permission_video'] = $settings['permission_video'];			
			$set_community['type'] = $settings['type'];			
			$set_community['community_id'] = $community_id;
			
			if(!core::database()->insert($set_community, core::database()->getTableName('communities_settings'))) {
				$result = FALSE;
				core::database()->querySQL('ROLLBACK');
			}			
		}
		else{
			$set_community = array();
			$set_community['permission_wall'] = $settings['permission_wall'];
			$set_community['permission_photo'] = $settings['permission_photo'];			
			$set_community['permission_video'] = $settings['permission_video'];			
			$set_community['type'] = $settings['type'];					

			if(!core::database()->update($set_community, core::database()->getTableName('communities_settings'), "community_id=" . $community_id)) {
				$result = FALSE;			
				core::database()->querySQL('ROLLBACK');
			}
		}

		core::database()->querySQL('COMMIT');
		core::database()->querySQL('SET AUTOCOMMIT=1');		
		
		return $result;
	}	
	
	static function getMemberShipStatus($community_id, $user_id)
	{
		if($community_id && $user_id){
			$query = "SELECT role FROM " . core::database()->getTableName('community_roles') . " WHERE community_id=" . $community_id . " AND user_id=" . $user_id;
			$result = core::database()->querySQL($query);
		
			$row = core::database()->getRow($result);
		
			return $row['role'];
		}
	}
	
	static function checkExistence($community_id, $type)
	{
		if($community_id && $type){
			$query = "SELECT * FROM " . core::database()->getTableName('communities') . " WHERE type='" . $type . "' AND id=" . $community_id;
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0)
				return TRUE;
			else
				return FALSE;
		}		
	}
	
	static function getPermissionWall($permission, $community_id, $user_id)
	{
		if($permission == 1)
			return FALSE;
		else if($permission == 2){
			if(Communities::checkOwnerCommunity($community_id, $user_id) or Communities::checkAdminCommunity($community_id, $user_id))
				return TRUE;
			else if(Communities::getMemberShipStatus($community_id, $user_id) == 2)
				return TRUE;
			else
				return FALSE;
		}		
		else if($permission == 3){
			if(Communities::checkOwnerCommunity($community_id, $user_id) or Communities::checkAdminCommunity($community_id, $user_id))
				return TRUE;
			else
				return FALSE;
		}
		else if(Communities::getMemberShipStatus($community_id, $user_id) == 4)
			return FALSE;
		else 
			return TRUE;
	}
	
	static function getPermissionPhoto($permission, $community_id, $user_id)
	{
		if($permission == 1)
			return FALSE;
		else if($permission == 2)
			if(Communities::checkOwnerCommunity($community_id, $user_id) or Communities::checkAdminCommunity($community_id, $user_id))
				return TRUE;
			else if(Communities::getMemberShipStatus($community_id, $user_id) == 2)
				return TRUE;
			else
				return FALSE;
		else if(Communities::getMemberShipStatus($community_id, $user_id) == 4)
			return FALSE;
		else 
			return TRUE;		
	}		

	static function getPermissionVideo($permission, $community_id, $user_id)
	{
		if($permission == 1)
			return FALSE;
		else if($permission == 2)
			if(Communities::checkOwnerCommunity($community_id, $user_id) or Communities::checkAdminCommunity($community_id, $user_id))
				return TRUE;
			else if(Communities::getMemberShipStatus($community_id, $user_id) == 2)
				return TRUE;
			else
				return FALSE;
		else if(Communities::getMemberShipStatus($community_id, $user_id) == 4)
			return FALSE;
		else 
			return TRUE;
		
	}	
	
	static function getCommunityType($community_id)
	{
		if($community_id){
			$query = "SELECT type FROM " . core::database()->getTableName('communities_settings') . " WHERE community_id=" . $community_id;
			$result = core::database()->querySQL($query);
		
			$row = core::database()->getRow($result);
		
			return $row['type'];
		}
	}
	
	static function changememberstatus($community_id, $user_id, $status)
	{
		if($status == 1){
			$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " WHERE community_id=" . $community_id . " AND user_id=" . $user_id;
			$result = core::database()->querySQL($query);
			
			if(core::database()->getRecordCount($result) == 0){			
				
				$communitysettings = Communities::getCommunitySettings($community_id);
				
				$fields = array();				
				
				if($communitysettings['type'] == 0 or !$communitysettings['type']){
					$fields['id'] = 0;				
					$fields['user_id'] = $user_id;
					$fields['community_id'] = $community_id;
					$fields['role'] = 2;
					
					$result = core::database()->insert($fields, core::database()->getTableName('community_roles'));
				} 
				else if($communitysettings['type'] == 1){
					$fields['id'] = 0;				
					$fields['user_id'] = $user_id;
					$fields['community_id'] = $community_id;
					$fields['role'] = 0;
					
					$result = core::database()->insert($fields, core::database()->getTableName('community_roles'));
				}
			
				if($result) 
					return TRUE;
				else
					return FALSE;
			}
			else{
				$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " WHERE community_id=" . $community_id . " AND user_id=" . $user_id;
				$result = core::database()->querySQL($query);
				$row = core::database()->getRow($result);
				
				if($row['role'] == 5){
					$fields = array();
					$fields['role'] = 2;
			
					$update = core::database()->update($fields, core::database()->getTableName('community_roles'), "community_id=" . $community_id . " AND user_id=" . $user_id); 
			
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
			if(core::database()->delete(core::database()->getTableName('community_roles'), "community_id=" . $community_id . " AND user_id=" . $user_id, '')){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}

	static function change_community_role($community_id, $user_id, $role)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " WHERE community_id=" . $community_id . " AND user_id=" . $user_id;
		$result = core::database()->querySQL($query);
			
		if(core::database()->getRecordCount($result) == 0){			
			$fields = array();
			$fields['id'] = 0;				
			$fields['user_id'] = $user_id;
			$fields['community_id'] = $community_id;
			$fields['role'] = $role;
				
			$result = core::database()->insert($fields, core::database()->getTableName('community_roles'));
				
			if($result) 
				return TRUE;
			else
				return FALSE;
		}
		else{
			$fields = array();
			$fields['role'] = $role;
			
			if(core::database()->update($fields, core::database()->getTableName('community_roles'), "community_id=" . $community_id . " AND user_id=" . $user_id))
				return TRUE;
			else
				return FALSE;	
		}
	}
	
	static function remove_community_role($community_id, $user_id)
	{
		if(core::database()->delete(core::database()->getTableName('community_roles'), "community_id=" . $community_id . " AND user_id=" . $user_id, '')){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}	
	
	static function getInvitedMeCommunity($user_id, $type, $limit, $offset = 0)
	{
		$query = "SELECT *, c.id as community_id FROM " . core::database()->getTableName('communities') . " c 
					LEFT JOIN " . core::database()->getTableName('community_roles') . " r ON r.community_id=c.id 
					WHERE (r.user_id=" . $user_id . ") AND (c.type='" . $type . "') AND (r.role=5) AND (c.banned!=1)
					GROUP BY c.id
					ORDER by r.role 					
					LIMIT " . $limit . " OFFSET " . $offset ."";
				
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);	
	}
	
	static function getNumberInvitedMeCommunities($user_id, $type)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('communities') . " c 
					LEFT JOIN " . core::database()->getTableName('community_roles') . " r ON r.community_id=c.id
					WHERE (r.user_id=" . $user_id . ") AND (c.type='" . $type . "') AND (r.role=5) AND (c.banned!=1)
					GROUP BY c.id";
	
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);	
	}
	
	static function getCommunityName($id)
	{
		if(is_numeric($id)){
			$query = "SELECT name FROM " . core::database()->getTableName('communities') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
		
			$row = core::database()->getRow($result);
			
			return $row['name'];
		}
	}
}