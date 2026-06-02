<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Communities
{
	static function getAllCommunitiesList($type, $limit, $offset = 0)
	{
		$from = "" . core::database()->getTableName('communities') . " a LEFT JOIN " . core::database()->getTableName('community_roles') . " b ON b.id_community=a.id";
		
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
			
			$parameters = "*,DATE_FORMAT(created_at,'%d.%m.%y') as putdate_created, a.id as id_community";
			$where = "WHERE (a.type='" . $type . "') AND (a.banned!=1) ".((!empty($tmp)) ? 'AND' : '')." " . $tmp . "" . $additional_pars . "";
			$group = "GROUP BY a.id";
			$order = "ORDER BY a.name";
			$limit = "LIMIT ".$limit." OFFSET ".$offset."";
         
        } else {
			$parameters = "*,DATE_FORMAT(created_at,'%d.%m.%y') as putdate_created, a.id as id_community";
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
	
	static function getMyCommunitiesList($id_user, $type, $limit=5, $offset=0)
	{
		if($id_user && $type){
		
			$query = "SELECT *, c.id as id_community FROM " . core::database()->getTableName('communities') . " c 
					LEFT JOIN " . core::database()->getTableName('community_roles') . " r ON r.id_community=c.id 
					WHERE (r.id_user=" . $id_user . ") AND (c.type='" . $type . "') AND (c.banned!=1) AND (r.role IN (1,2,3))
					GROUP BY c.id
					ORDER by r.role 					
					LIMIT " . $limit . " OFFSET " . $offset ."";
				
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);	
		}		
	}

	static function countMemberCommunity($id_community, $role){
		
		if($id_community && $role){
			$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.id_user 
					WHERE role=" . $role . " AND a.id_community=" . $id_community . "
					GROUP by a.id_user";
					
			$result = core::database()->querySQL($query);			
		
			return core::database()->getRecordCount($result);
		}		
	}
	
	static function getMemberList($id_community, $role)
	{
		if($id_community && $role){
			$query = "SELECT *, b.id AS id_user FROM " . core::database()->getTableName('community_roles') . " a 
						LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.id_user 
						WHERE role=" . $role . " AND a.id_community=" . $id_community . "
						GROUP by a.id_user";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}		
	}
	
	static function getMemberAllList($id_community)
	{
		if($id_community){
			$query = "SELECT *, b.id AS id_user FROM " . core::database()->getTableName('community_roles') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.id_user 
					WHERE (role IN (1,2,3)) AND (a.id_community=" . $id_community . ")
					GROUP by a.id_user
					ORDER by a.role
					";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}		
	}
	
	static function countAllMemberCommunity($id_community){
		
		if($id_community){
			$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.id_user 
					WHERE (role IN (1,2,3)) AND (a.id_community=" . $id_community . ")
					GROUP by a.id_user";
					
			$result = core::database()->querySQL($query);			
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getNumberMyCommunities($id_user, $type)
	{
		if($id_user && $type){		
			$query = "SELECT * FROM " . core::database()->getTableName('communities') . " c 
					LEFT JOIN " . core::database()->getTableName('community_roles') . " r ON r.id_community=c.id
					WHERE (r.id_user=" . $id_user . ") AND (c.type='" . $type . "') AND (c.banned!=1) AND (role IN (1,2,3)) 
					GROUP BY c.id";
	
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);	
		}
	}	

	static function getUserStatus($id_community, $id_user)
	{
		if($id_user && $id_community) {
			$query = "SELECT role FROM " . core::database()->getTableName('community_roles') . " WHERE id_community=" . $id_community . " AND id_user=" . $id_user;
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
			$query = "SELECT * FROM " . core::database()->getTableName('communities_settings') . " WHERE id_community=" . $id;
			$result = core::database()->querySQL($query);
		
			return core::database()->getRow($result);
		}
	}
	
	static function getPopularCommunitiesList($type, $limit = 5, $offset = 0)
	{
		if($type){
			$query = "SELECT *, sum(b.id_user) pop, a.id AS id_community FROM " . core::database()->getTableName('communities') . " a 
					INNER JOIN  " . core::database()->getTableName('community_roles') . " b ON b.id_community = a.id
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
			$query = "SELECT sum(b.id_user) pop FROM " . core::database()->getTableName('communities') . " a 
					INNER JOIN  " . core::database()->getTableName('community_roles') . " b ON b.id_community = a.id
					WHERE (a.type='" . $type . "') AND (a.banned!=1)
					GROUP by a.id";
					
			$result = core::database()->querySQL($query);

			return core::database()->getRecordCount($result);	
		}		
	}
	
	static function checkOwnerCommunity($id_community, $id_user)
	{
		if($id_community && $id_user){
			$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " WHERE (id_community=" . $id_community . ") and (id_user=" . $id_user . ") and (role=1)";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0) 
				return FALSE;
			else
				return TRUE;
		}
	}
	
	static function checkAdminCommunity($id_community, $id_user)
	{
		if($id_community && $id_user){
			$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " WHERE (id_community=" . $id_community . ") and (id_user=" . $id_user . ") and (role=2)";
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
	
	static function addNewCommunity($fields, $id_user)
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
			$fields['id_user'] = $id_user;
			$fields['id_community'] = $insert_id;
			$fields['role'] = 1;		
			
			core::database()->insert($fields, core::database()->getTableName('community_roles'));
			
			return $insert_id;	
		}			
	}	
	
	static function editCommunity($fields, $settings, $type, $id_community)
	{
		if($fields['avatar'] && file_exists('tmp/' . $fields['avatar'])){
			if($type == 'group')
				$path = PATH_GROUPCONTENT_AVATAR_IMAGES;
			else if($type == 'team')
				$path = PATH_TEAMCONTENT_AVATAR_IMAGES;
			
			$query = "SELECT avatar FROM " . core::database()->getTableName('communities') . " WHERE id=" . $id_community;
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
			
			$query = "SELECT cover_page FROM " . core::database()->getTableName('communities') . " WHERE id=" . $id_community;
			$result =  core::database()->querySQL($query);
			$row = core::database()->getRow($result);
			
			if(file_exists($path . $row['cover_page'])) unlink($path . $row['cover_page']);
				
			rename('tmp/' . $fields['cover_page'], $path . $fields['cover_page']);
		}		
		
		$result = TRUE;		
			
		core::database()->querySQL('SET AUTOCOMMIT=0');
		core::database()->querySQL('START TRANSACTION');
		
		if(!core::database()->update($fields, core::database()->getTableName('communities'), "id=" . $id_community)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}		
			
		$query = "SELECT * FROM " . core::database()->getTableName('communities_settings') . " WHERE id_community=" . $id_community;
		$result = core::database()->querySQL($query);		
		
		if(core::database()->getRecordCount($result) == 0){
			$set_community = array();
			$set_community['id'] = 0;
			$set_community['permission_wall'] = $settings['permission_wall'];
			$set_community['permission_photo'] = $settings['permission_photo'];			
			$set_community['permission_video'] = $settings['permission_video'];			
			$set_community['type'] = $settings['type'];			
			$set_community['id_community'] = $id_community;
			
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

			if(!core::database()->update($set_community, core::database()->getTableName('communities_settings'), "id_community=" . $id_community)) {
				$result = FALSE;			
				core::database()->querySQL('ROLLBACK');
			}
		}

		core::database()->querySQL('COMMIT');
		core::database()->querySQL('SET AUTOCOMMIT=1');		
		
		return $result;
	}	
	
	static function getMemberShipStatus($id_community, $id_user)
	{
		if($id_community && $id_user){
			$query = "SELECT role FROM " . core::database()->getTableName('community_roles') . " WHERE id_community=" . $id_community . " AND id_user=" . $id_user;
			$result = core::database()->querySQL($query);
		
			$row = core::database()->getRow($result);
		
			return $row['role'];
		}
	}
	
	static function checkExistence($id_community, $type)
	{
		if($id_community && $type){
			$query = "SELECT * FROM " . core::database()->getTableName('communities') . " WHERE type='" . $type . "' AND id=" . $id_community;
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0)
				return TRUE;
			else
				return FALSE;
		}		
	}
	
	static function getPermissionWall($permission, $id_community, $id_user)
	{
		if($permission == 1)
			return FALSE;
		else if($permission == 2){
			if(Communities::checkOwnerCommunity($id_community, $id_user) or Communities::checkAdminCommunity($id_community, $id_user))
				return TRUE;
			else if(Communities::getMemberShipStatus($id_community, $id_user) == 2)
				return TRUE;
			else
				return FALSE;
		}		
		else if($permission == 3){
			if(Communities::checkOwnerCommunity($id_community, $id_user) or Communities::checkAdminCommunity($id_community, $id_user))
				return TRUE;
			else
				return FALSE;
		}
		else if(Communities::getMemberShipStatus($id_community, $id_user) == 4)
			return FALSE;
		else 
			return TRUE;
	}
	
	static function getPermissionPhoto($permission, $id_community, $id_user)
	{
		if($permission == 1)
			return FALSE;
		else if($permission == 2)
			if(Communities::checkOwnerCommunity($id_community, $id_user) or Communities::checkAdminCommunity($id_community, $id_user))
				return TRUE;
			else if(Communities::getMemberShipStatus($id_community, $id_user) == 2)
				return TRUE;
			else
				return FALSE;
		else if(Communities::getMemberShipStatus($id_community, $id_user) == 4)
			return FALSE;
		else 
			return TRUE;		
	}		

	static function getPermissionVideo($permission, $id_community, $id_user)
	{
		if($permission == 1)
			return FALSE;
		else if($permission == 2)
			if(Communities::checkOwnerCommunity($id_community, $id_user) or Communities::checkAdminCommunity($id_community, $id_user))
				return TRUE;
			else if(Communities::getMemberShipStatus($id_community, $id_user) == 2)
				return TRUE;
			else
				return FALSE;
		else if(Communities::getMemberShipStatus($id_community, $id_user) == 4)
			return FALSE;
		else 
			return TRUE;
		
	}	
	
	static function getCommunityType($id_community)
	{
		if($id_community){
			$query = "SELECT type FROM " . core::database()->getTableName('communities_settings') . " WHERE id_community=" . $id_community;
			$result = core::database()->querySQL($query);
		
			$row = core::database()->getRow($result);
		
			return $row['type'];
		}
	}
	
	static function changememberstatus($id_community, $id_user, $status)
	{
		if($status == 1){
			$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " WHERE id_community=" . $id_community . " AND id_user=" . $id_user;
			$result = core::database()->querySQL($query);
			
			if(core::database()->getRecordCount($result) == 0){			
				
				$communitysettings = Communities::getCommunitySettings($id_community);
				
				$fields = array();				
				
				if($communitysettings['type'] == 0 or !$communitysettings['type']){
					$fields['id'] = 0;				
					$fields['id_user'] = $id_user;
					$fields['id_community'] = $id_community;
					$fields['role'] = 2;
					
					$result = core::database()->insert($fields, core::database()->getTableName('community_roles'));
				} 
				else if($communitysettings['type'] == 1){
					$fields['id'] = 0;				
					$fields['id_user'] = $id_user;
					$fields['id_community'] = $id_community;
					$fields['role'] = 0;
					
					$result = core::database()->insert($fields, core::database()->getTableName('community_roles'));
				}
			
				if($result) 
					return TRUE;
				else
					return FALSE;
			}
			else{
				$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " WHERE id_community=" . $id_community . " AND id_user=" . $id_user;
				$result = core::database()->querySQL($query);
				$row = core::database()->getRow($result);
				
				if($row['role'] == 5){
					$fields = array();
					$fields['role'] = 2;
			
					$update = core::database()->update($fields, core::database()->getTableName('community_roles'), "id_community=" . $id_community . " AND id_user=" . $id_user); 
			
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
			if(core::database()->delete(core::database()->getTableName('community_roles'), "id_community=" . $id_community . " AND id_user=" . $id_user, '')){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}

	static function change_community_role($id_community, $id_user, $role)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('community_roles') . " WHERE id_community=" . $id_community . " AND id_user=" . $id_user;
		$result = core::database()->querySQL($query);
			
		if(core::database()->getRecordCount($result) == 0){			
			$fields = array();
			$fields['id'] = 0;				
			$fields['id_user'] = $id_user;
			$fields['id_community'] = $id_community;
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
			
			if(core::database()->update($fields, core::database()->getTableName('community_roles'), "id_community=" . $id_community . " AND id_user=" . $id_user))
				return TRUE;
			else
				return FALSE;	
		}
	}
	
	static function remove_community_role($id_community, $id_user)
	{
		if(core::database()->delete(core::database()->getTableName('community_roles'), "id_community=" . $id_community . " AND id_user=" . $id_user, '')){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}	
	
	static function getInvitedMeCommunity($id_user, $type, $limit, $offset = 0)
	{
		$query = "SELECT *, c.id as id_community FROM " . core::database()->getTableName('communities') . " c 
					LEFT JOIN " . core::database()->getTableName('community_roles') . " r ON r.id_community=c.id 
					WHERE (r.id_user=" . $id_user . ") AND (c.type='" . $type . "') AND (r.role=5) AND (c.banned!=1)
					GROUP BY c.id
					ORDER by r.role 					
					LIMIT " . $limit . " OFFSET " . $offset ."";
				
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);	
	}
	
	static function getNumberInvitedMeCommunities($id_user, $type)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('communities') . " c 
					LEFT JOIN " . core::database()->getTableName('community_roles') . " r ON r.id_community=c.id
					WHERE (r.id_user=" . $id_user . ") AND (c.type='" . $type . "') AND (r.role=5) AND (c.banned!=1)
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