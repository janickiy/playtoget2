<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Videoalbum
{
	static function createAlbum($fields)
	{
		if(empty($fields['created_at'])) $fields['created_at'] = date("Y-m-d H:i:s");
		if(empty($fields['updated_at'])) $fields['updated_at'] = $fields['created_at'];

		$insert_id = core::database()->insert($fields, core::database()->getTableName('videoalbums'));
		
		return $insert_id;	
	}	
	
	static function checkNameExists($name, $id_owner, $videoalbumable_type)
	{
		$name = core::database()->escape($name);
		$id_owner = core::database()->escape($id_owner);
		
		$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') . " WHERE (id_owner=" . $id_owner.") AND (videoalbumable_type='" . $videoalbumable_type . "') AND (name LIKE '".$name."')";	
		$result = core::database()->querySQL($query);
				
		if(core::database()->getRecordCount($result) == 0)
			return FALSE;
		else
			return TRUE;
	}
	
	static function NumberAlbums($id_owner, $videoalbumable_type)
	{
		$id_owner = core::database()->escape($id_owner);
		
		$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') ." WHERE videoalbumable_type='" . $videoalbumable_type . "' AND id_owner=" . $id_owner;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}
	
	static function getAlbumList($id_owner, $videoalbumable_type){
		
		if(is_numeric($id_owner) && $videoalbumable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') . " WHERE id_owner=" . $id_owner . " AND videoalbumable_type='" . $videoalbumable_type . "'";
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function getThumb($id_album)
	{
		if(is_numeric($id_album)){
			$query = "SELECT provider, video FROM " . core::database()->getTableName('videos') . " WHERE banned!=1 AND id_videoalbum = " . $id_album . " LIMIT 1";
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result);	
		
			return core::documentparser()->getThumb($row['provider'], $row['video']);
		}
	}	
	
	static function getVideoAlbumOption($id_owner, $videoalbumable_type)
	{
		if(is_numeric($id_owner) && $videoalbumable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') . " WHERE id_owner=" . $id_owner . " AND videoalbumable_type='" . $videoalbumable_type . "'";
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function addVideo($fields)
	{
		$insert_id = core::database()->insert($fields, core::database()->getTableName('videos'));
		
		return $insert_id;	
	}
	
	static function getVideosList($id_owner, $videoalbumable_type, $limit = 6, $offset = 0)
	{		
		if(is_numeric($id_owner) && $videoalbumable_type){
			$query = "SELECT *, a.id AS id_video, a.id_owner AS id_owner, b.id_owner AS id_albumowner  FROM " . core::database()->getTableName('videos') . " a 
						LEFT JOIN " . core::database()->getTableName('videoalbums') . " b ON b.id=a.id_videoalbum 
						WHERE (a.banned!=1) AND (b.id_owner=" . $id_owner . ") AND (b.videoalbumable_type='" . $videoalbumable_type . "')  
						LIMIT " . $limit . " OFFSET ".$offset."";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);		
		}
	}

	static function getVideosAlbumList($id_videoalbum, $limit = 4, $offset = 0){
		
		if(is_numeric($id_videoalbum)){
			$query = "SELECT * FROM " . core::database()->getTableName('videos') . " WHERE id_videoalbum=" . $id_videoalbum . "	LIMIT " . $limit . " OFFSET ".$offset."";
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function NumberVideos($id_owner, $videoalbumable_type)
	{
		if(is_numeric($id_owner) && $videoalbumable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('videos') ." a 
						LEFT JOIN " . core::database()->getTableName('videoalbums') . " b ON b.id=a.id_videoalbum 
						WHERE (a.banned!=1) AND (b.id_owner=" . $id_owner . ") AND (b.videoalbumable_type='" . $videoalbumable_type . "')";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getVideoAlbumInfo($id_album)
	{
		if(is_numeric($id_album)){
			$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') ." WHERE id=" . $id_album;
			$result = core::database()->querySQL($query);
		
			return core::database()->getRow($result);
		}
	}	
	
	static function editAlbum($fields, $id_album, $id_owner)
	{
		$table = core::database()->getTableName('videoalbums');
		$where = "id_owner=" . $id_owner . " AND id=" . $id_album;
		$result = core::database()->update($fields, $table, $where); 
		
		return $result;		
	}	
	
	static function removeAlbum($id_album, $id_owner){
		if(core::database()->delete(core::database()->getTableName('videos'), "id_videoalbum=" . $id_album. " AND id_owner=" . $id_owner, '') && core::database()->delete(core::database()->getTableName('videoalbums'), "id=" . $id_album . " AND id_owner=" . $id_owner,'')){
			return TRUE;
		}
		else
			return FALSE;
	}

	static function getPopularVideos($videoalbumable_type, $limit = 6, $offset = 0)
	{
		$query = "SELECT *, sum(l.id_user) pop, sum(w.id_user) pop2, v.id AS id_video FROM " . core::database()->getTableName('videos') . " v
					INNER JOIN  " . core::database()->getTableName('likes') . " l ON l.id_content=v.id
					LEFT JOIN " . core::database()->getTableName('videoalbums') . " b ON b.id=v.id_videoalbum
					LEFT JOIN " . core::database()->getTableName('video_views') . " w ON v.id=w.id_video
					WHERE (v.banned!=1) AND (b.videoalbumable_type='" . $videoalbumable_type . "') AND (l.likeable_type='video')
					GROUP by v.id
					ORDER by pop,pop2 DESC					
					LIMIT " . $limit . " OFFSET ".$offset."";
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}

	static function getNumberPopVideos($videoalbumable_type)
	{
		$query = "SELECT *, sum(l.id_user) pop, sum(w.id_user) pop2, v.id AS id_video FROM " . core::database()->getTableName('videos') . " v 
					INNER JOIN  " . core::database()->getTableName('likes') . " l ON l.id_content=v.id
					LEFT JOIN " . core::database()->getTableName('videoalbums') . " b ON b.id=v.id_videoalbum
					LEFT JOIN " . core::database()->getTableName('video_views') . " w ON v.id=w.id_video
					WHERE (v.banned!=1) AND (b.videoalbumable_type='" . $videoalbumable_type . "') AND (l.likeable_type='video')
					GROUP by v.id";
					
		$result = core::database()->querySQL($query);

		return core::database()->getRecordCount($result);
	}
	
	static function getAlbumInfo($id_album)
	{
		if(is_numeric($id_album)){
			$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') . " WHERE id=" . $id_album;
			$result = core::database()->querySQL($query);

			return core::database()->getRow($result);
		}
	}

	static function getNumberLiked($id_video)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('likes') . " WHERE id_content=" . $id_video  . " AND likeable_type='video' GROUP by id_user";
		$result = core::database()->querySQL($query);
			
		return core::database()->getRecordCount($result);
	}
	
	static function getNumberTell($id_video)
	{
		if(is_numeric($id_video)){
			$query = "SELECT * FROM " . core::database()->getTableName('share') . " WHERE id_content=" . $id_video  . " AND shareable_type='video' GROUP by id_user";
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getNumberVideoViews($id_video)
	{
		if(is_numeric($id_video)){
			$query = "SELECT * FROM " . core::database()->getTableName('video_views') . " WHERE id_video=" . $id_video;
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getVideoInfo($id_video)
	{
		if(is_numeric($id_video)){
			$query = "SELECT *, DATE_FORMAT(a.created_at,'%Y-%m-%d') AS created, a.id AS id_video, a.id_owner AS id_owner, b.id_owner AS id_albumowner FROM " . core::database()->getTableName('videos') . " a
						LEFT JOIN " . core::database()->getTableName('videoalbums') . " b ON a.id_videoalbum=b.id
						LEFT JOIN " . core::database()->getTableName('users') . " c ON a.id_owner=c.id
						WHERE a.id=" . $id_video;
			
			$result = core::database()->querySQL($query);
		
			return core::database()->getRow($result);			
		}		
	}	
	
	static function countView($id_video, $id_user)
	{
		if(is_numeric($id_video) && is_numeric($id_user)){
			$fields = array();
			$fields['id'] = 0;
			$fields['id_user'] = $id_user;			
			$fields['id_video'] = $id_video;			
			$fields['time'] = date("Y-m-d H:i:s");			
			
			$insert_id = core::database()->insert($fields, core::database()->getTableName('video_views'));
		
			return $insert_id;			
		}
	}
	
	static function checkExistence($id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') . " WHERE id=" . $id;
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0)
			return TRUE;
		else
			return FALSE;			
	}
	
	static function checkOwner($id_album, $id_owner)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') . " WHERE id=" . $id_album . " AND id_owner=" . $id_owner;
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return FALSE;
		else
			return TRUE;
	}
	
	static function getUserPublishVideo($id_user, $limit = 5, $offset = 0)
	{
		$query = "SELECT *, u.id as id_user, f.id_friend AS id_friend, DATE_FORMAT(v.created_at, '%Y-%m-%d') AS added, DATE_FORMAT(v.created_at, '%Y%m%d%H%i%s') AS timeorder, v.id AS id_video FROM " . core::database()->getTableName('videos') . " v,  
					" . core::database()->getTableName('users') . " u, 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.id_user='" . $id_user . "'
						THEN f.id_friend=u.id
						WHEN f.id_friend='" . $id_user . "'
						THEN f.id_user=u.id
					END
					AND	((f.status='1') AND (v.id_owner!=" . $id_user . ") AND (v.id_owner=u.id))
					ORDER BY v.created_at DESC
					LIMIT " . $limit . " OFFSET " . $offset . "
					";					
		
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);		
	}
	
	static function removevideo($id)
	{
		core::database()->querySQL('SET AUTOCOMMIT=0');
		core::database()->querySQL('START TRANSACTION');
		
		$result = TRUE;
		
		if(!core::database()->delete(core::database()->getTableName('videos'), "id=" . $id, '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		
		if(!core::database()->delete(core::database()->getTableName('comments'), "commentable_type='video' AND id_content=" . $id, '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		
		if(!core::database()->delete(core::database()->getTableName('likes'), "likeable_type='video' AND id_content=" . $id, '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}

		if(!core::database()->delete(core::database()->getTableName('share'), "shareable_type='video' AND id_content=" . $id, '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}		
		
		core::database()->querySQL('COMMIT');
		core::database()->querySQL('SET AUTOCOMMIT=1');
		
		return $result;
	}
}
