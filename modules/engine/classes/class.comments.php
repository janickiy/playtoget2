<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Comments
{
	static function getCommentList($content_id, $commentable_type, $limit = 10, $offset=0)
	{
		if(is_numeric($content_id) && $commentable_type){
			$query = "SELECT *, a.id AS id_comment, DATE_FORMAT(a.created_at,'%d.%m.%Y %H:%i') AS created, b.id AS user_id FROM " . core::database()->getTableName('comments') . " a 
						LEFT JOIN " . core::database()->getTableName('users') . " b ON a.user_id=b.id WHERE commentable_type='" .$commentable_type . "' AND content_id=" . $content_id . " 
						ORDER by a.id DESC 
						LIMIT " . $limit . " OFFSET ".$offset."";
					
			$result = core::database()->querySQL($query);
		
			$arr = array();
		
			while ($row = core::database()->getRow($result)) {
				$arr[$row['parent_id']][] = array('id_comment' => $row['id_comment'],
												'user_id' => $row['user_id'],
												'parent_id' => $row['parent_id'],
												'content_id' => $row['content_id'],
												'behalfable_type' => $row['behalfable_type'],
												'behalf_id' => $row['behalf_id'],
												'sex' => $row['sex'],
												'avatar' => $row['avatar'],
												'firstname' => $row['firstname'],
												'lastname' => $row['lastname'],
												'created' => $row['created'],
												'content' => $row['content']); 
			}	
		
			return $arr;
		}
	}
	
	static function getCommentsListAjax($content_id, $commentable_type, $offset = 0, $number = 10)
	{
		if(is_numeric($content_id) && $commentable_type){
		
			$query = "SELECT *, a.id AS id_comment, DATE_FORMAT(a.created_at,'%d.%m.%Y %H:%i') AS created FROM " . core::database()->getTableName('comments') . " a 
						LEFT JOIN " . core::database()->getTableName('users') . " b ON a.user_id=b.id 
						WHERE commentable_type='" .$commentable_type . "' AND content_id=" . $content_id . "
						ORDER by a.id DESC		
						LIMIT ".$number." 
						OFFSET ".$offset."";
		
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);	
		}
	}	

	static function getNumberLiked($content_id, $likeable_type)
	{
		if(is_numeric($content_id) && $likeable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('likes') . " WHERE content_id=" . $content_id  . " AND likeable_type='" . $likeable_type . "'";
			$result = core::database()->querySQL($query);
			
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getNumberTell($content_id, $shareable_type)
	{
		if(is_numeric($content_id) && $shareable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('share') . " WHERE content_id=" . $content_id  . " AND shareable_type='" . $shareable_type . "'";
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}

	static function removeComment($id_comment)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('comments') . " WHERE parent_id = " . $id_comment;
		$result = core::database()->querySQL($query);

		while($row = core::database()->getRow($result))
		{
			core::database()->delete(core::database()->getTableName('comments'), "id=" . $row['id'], '');
			Attach::removeAttach($row['id'], 'comment');
			self::removeComment($row['id']);
		}
		
		Attach::removeAttach($id_comment, 'comment');
		core::database()->delete(core::database()->getTableName('comments'), "id=" . $id_comment, '');
		
		return true;
	}
	
	static function treeComments($parent=0, &$tags) {
		global $new_arr;
	
		for ($i=0;$i<=count($tags[$parent])-1;$i++) {
			$new_arr[] = $tags[$parent][$i];
			unset($new_arr[count($arr) - 1]);
		
			if (isset($tags[ $tags[$parent][$i]['id_comment'] ])) self::treeComments($tags[$parent][$i]['id_comment'], $tags, $arr);
			unset($tags[$parent][$i]['id_comment']);
		}
	
		return $new_arr;	
	}

	static function getUserComment($user_id, $limit = 20, $offset = 0)
	{
		if(is_numeric($user_id)){
			$query = "SELECT *,u.id as user_id, f.friend_id AS friend_id, DATE_FORMAT(c.created_at, '%Y-%m-%d') AS added, DATE_FORMAT(c.created_at, '%Y%m%d%H%i%s') AS timeorder, c.id AS id_comment FROM " . core::database()->getTableName('comments') . " c,  
						" . core::database()->getTableName('users') . " u, 
						" . core::database()->getTableName('friends') . " f
						WHERE
						CASE
							WHEN f.user_id='" . $user_id . "'
							THEN f.friend_id=u.id
							WHEN f.friend_id='" . $user_id . "'
							THEN f.user_id=u.id
						END
						AND (c.commentable_type='user') AND (c.content_id=u.id) 
						AND (c.user_id!=" . $user_id . ")
						AND	(f.status='1')  AND (c.user_id=u.id)
						ORDER BY c.id DESC
						LIMIT " . $limit . " OFFSET " . $offset . "
						";					
		
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	} 
	
	static function getCommentCommunity($user_id, $limit = 20, $offset = 0)
	{
		if(is_numeric($user_id)){
			$query="SELECT *,c.id AS id_comment, c.user_id AS id_author, DATE_FORMAT(c.created_at, '%Y-%m-%d') AS added, DATE_FORMAT(c.created_at, '%Y%m%d%H%i%s') AS timeorder FROM " . 
						core::database()->getTableName('comments') . " c," . 
						core::database()->getTableName('communities') . " g,".
						core::database()->getTableName('community_roles') . " r
						WHERE (c.commentable_type='group' OR c.commentable_type='team') 
						AND	(c.content_id=g.id AND c.commentable_type=g.type)
						AND (r.community_id=g.id AND r.user_id=".$user_id.") AND (role IN (1,2,3))
						ORDER BY c.id DESC
						LIMIT " . $limit . " OFFSET " . $offset . "
						";
			$result = core::database()->querySQL($query);
			
			return core::database()->getColumnArray($result);
		}
	}

	static function getCommentEvent($user_id, $limit = 20, $offset = 0)
	{
		if(is_numeric($user_id)){
			$query="SELECT *,c.id AS id_comment, c.user_id AS id_author, DATE_FORMAT(c.created_at, '%Y-%m-%d') AS added, DATE_FORMAT(c.created_at, '%Y%m%d%H%i%s') AS timeorder FROM " . 
						core::database()->getTableName('comments') . " c," . 
						core::database()->getTableName('events') . " g,".
						core::database()->getTableName('accepted_event_members') . " r
						WHERE (c.commentable_type='event') 
						AND	(c.content_id=g.id AND c.commentable_type='event')
						AND (r.event_id=g.id AND r.member_id=".$user_id.") AND (role IN (1,2,3))
						ORDER BY c.id DESC
						LIMIT " . $limit . " OFFSET " . $offset . "
						";
			$result = core::database()->querySQL($query);
			
			return core::database()->getColumnArray($result);
		}
	}
	static function getUserGetVideoComment($user_id, $limit = 5, $offset = 0)
	{
		if(is_numeric($user_id)){
			$query = "SELECT *,u.id as user_id, f.friend_id AS friend_id, DATE_FORMAT(c.created_at, '%Y-%m-%d') AS added, DATE_FORMAT(c.created_at, '%Y%m%d%H%i%s') AS timeorder, c.id AS id_comment FROM " . core::database()->getTableName('comments') . " c,  
						" . core::database()->getTableName('videos') . " v,
						" . core::database()->getTableName('users') . " u, 
						" . core::database()->getTableName('friends') . " f
						WHERE
						CASE
							WHEN f.user_id='" . $user_id . "'
							THEN f.friend_id=u.id
							WHEN f.friend_id='" . $user_id . "'
							THEN f.user_id=u.id
						END
						AND	((f.status='1') AND (c.user_id!=" . $user_id . ") AND (v.id=c.content_id) AND (c.commentable_type='video') AND (c.user_id=u.id))
						LIMIT " . $limit . " OFFSET " . $offset . "
						";					
		
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	} 
	
	static function getUserGetPhotoComment($user_id, $limit = 5, $offset = 0)
	{
		if(is_numeric($user_id)){
			$query = "SELECT *,u.id as user_id, f.friend_id AS friend_id, DATE_FORMAT(c.created_at, '%Y-%m-%d') AS added, DATE_FORMAT(c.created_at, '%Y%m%d%H%i%s') AS timeorder, c.id AS id_comment, p.id AS photo_id FROM " . core::database()->getTableName('comments') . " c,  
						" . core::database()->getTableName('photos') . " p,
						" . core::database()->getTableName('users') . " u, 
						" . core::database()->getTableName('friends') . " f
						WHERE
						CASE
							WHEN f.user_id='" . $user_id . "'
							THEN f.friend_id=u.id
							WHEN f.friend_id='" . $user_id . "'
							THEN f.user_id=u.id
						END
						AND	((f.status='1') AND (c.user_id!=" . $user_id . ") AND (p.id=c.content_id) AND (c.commentable_type='photo') AND (c.user_id=u.id))
						ORDER BY c.id DESC LIMIT " . $limit . " OFFSET " . $offset . "
						";					
		
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	} 
	
	static function getCommentInfo($id_comment)
	{
		if(is_numeric($id_comment)){
			$query = "SELECT * FROM " . core::database()->getTableName('comments') . " c 
						LEFT JOIN " . core::database()->getTableName('users') . " u ON c.user_id=u.id
						WHERE c.id=" . $id_comment;
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getRow($result);
		}		
	}	

	static function checkBanReceiver($commentable_type, $content_id)
	{
		$check = TRUE;
		
		if(is_numeric($content_id) && $commentable_type){
			switch($commentable_type){
				case user:
				
					$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=" . $content_id  . " AND banned=1";
					$result = core::database()->querySQL($query);
		
					if(core::database()->getRecordCount($result) > 0) $check = FALSE;
	
				break;
				
				case photo:
				
					$query = "SELECT * FROM " . core::database()->getTableName('photos') . " WHERE id=" . $content_id  . " AND banned=1";
					$result = core::database()->querySQL($query);
					
					if(core::database()->getRecordCount($result) > 0) $check = FALSE;
					
				break;	
				
				case video:
				
					$query = "SELECT * FROM " . core::database()->getTableName('videos') . " WHERE id=" . $content_id  . " AND banned=1";
					$result = core::database()->querySQL($query);
					
					if(core::database()->getRecordCount($result) > 0) $check = FALSE;				
					
				break;
				
				case group:
				
					$query = "SELECT * FROM " . core::database()->getTableName('communities') . " WHERE id=" . $content_id  . " AND banned=1";
					$result = core::database()->querySQL($query);
					
					if(core::database()->getRecordCount($result) > 0) $check = FALSE;
				
				break;				
				
				case team:
				
					$query = "SELECT * FROM " . core::database()->getTableName('communities') . " WHERE id=" . $content_id  . " AND banned=1";
					$result = core::database()->querySQL($query);
					
					if(core::database()->getRecordCount($result) > 0) $check = FALSE;
					
				break;
				
				case event:
				
					$query = "SELECT * FROM " . core::database()->getTableName('events') . " WHERE id=" . $content_id  . " AND banned=1";
					$result = core::database()->querySQL($query);
					
					if(core::database()->getRecordCount($result) > 0) $check = FALSE;
					
				break;
			}
		}	
		
		return $check;
	}
	
	static function checkRemoveReceiver($commentable_type, $content_id)
	{
		$check = TRUE;
		
		if(is_numeric($content_id) && $commentable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=" . $content_id  . " AND banned=1";
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) > 0) $check = FALSE;
		}

		return $check;
	}
	
	static function getCommentAvatar($id)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('comments') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result);
			
			if($row['behalfable_type'] && $row['behalf_id']){
				switch($row['behalfable_type']){
				
					case group:
				
						$query = "SELECT * FROM " . core::database()->getTableName('communities') . " WHERE id=" . $row['behalf_id'];
						$result = core::database()->querySQL($query);					
						$row = core::database()->getRow($result);	
					
						return ($row['avatar'] && file_exists(PATH_GROUPCONTENT_AVATAR_IMAGES . $row['avatar']) && $row['banned'] != 1) ? PATH_GROUPCONTENT_AVATAR_IMAGES . $row['avatar'] : 'templates/images/noimage.png';					
					
					break;
				
					case team:
				
						$query = "SELECT * FROM " . core::database()->getTableName('communities') . " WHERE id=" . $row['behalf_id'];
						$result = core::database()->querySQL($query);					
						$row = core::database()->getRow($result);	
					
						return ($row['avatar'] && file_exists(PATH_TEAMCONTENT_AVATAR_IMAGES . $row['avatar']) && $row['banned'] != 1) ? PATH_TEAMCONTENT_AVATAR_IMAGES . $row['avatar'] : 'templates/images/noimage.png';
				
					break;
				
					case event:
				
						$query = "SELECT * FROM " . core::database()->getTableName('events') . " WHERE id=" . $row['behalf_id'];
						$result = core::database()->querySQL($query);					
						$row = core::database()->getRow($result);	
					
						return ($row['cover_page'] && file_exists(PATH_EVENTS_COVER_PAGE_IMAGES . $row['cover_page']) && $row['banned'] != 1) ? PATH_EVENTS_COVER_PAGE_IMAGES . $row['cover_page'] : 'templates/images/content-bg.png';
				
					break;
				}	
			}
			else{
				$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=" . $row['user_id'];
				$result = core::database()->querySQL($query);
				$row = core::database()->getRow($result);				
				
				if($row['avatar'] && file_exists(PATH_USER_AVATAR_IMAGES . $row['avatar']) && $row['banned'] != 1 && $row['deleted'] !=1)
					return PATH_USER_AVATAR_IMAGES . $row['avatar'];
				else{
					if($row['sex'] == 'male' && $row['banned'] != 1 && $row['deleted'] !=1)
						return 'templates/images/default_male.png';
					else if($row['sex'] == 'female' && $row['banned'] != 1 && $row['deleted'] !=1)
						return 'templates/images/default_female.png';
					else
						return 'templates/images/noimage.png';	
				}
			}
		}
	}
	
	static function getCommentAuthorName($id)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('comments') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result);
			
			if($row['behalfable_type'] && $row['behalf_id']){
				switch($row['behalfable_type']){
				
					case group:
				
						$query = "SELECT * FROM " . core::database()->getTableName('communities') . " WHERE id=" . $row['behalf_id'];
						$result = core::database()->querySQL($query);					
						$row = core::database()->getRow($result);						
					
						return $row['name'];					
					
					break;
				
					case team:
				
						$query = "SELECT * FROM " . core::database()->getTableName('communities') . " WHERE id=" . $row['behalf_id'];
						$result = core::database()->querySQL($query);					
						$row = core::database()->getRow($result);	
					
						return $row['name'];
				
					break;
				
					case event:
				
						$query = "SELECT * FROM " . core::database()->getTableName('events') . " WHERE id=" . $row['behalf_id'];
						$result = core::database()->querySQL($query);					
						$row = core::database()->getRow($result);	
					
						return $row['name'];
				
					break;
				}	
			}
			else{
				$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id=" . $row['user_id'];
				$result = core::database()->querySQL($query);
				$row = core::database()->getRow($result);
				
				return $row['firstname'] . " " . $row['lastname'];				
				 
			}			
		}
	}
}