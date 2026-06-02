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
	
	static function checkNameExists($name, $owner_id, $videoalbumable_type)
	{
		$name = core::database()->escape($name);
		$owner_id = core::database()->escape($owner_id);
		
		$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') . " WHERE (owner_id=" . $owner_id.") AND (videoalbumable_type='" . $videoalbumable_type . "') AND (name LIKE '".$name."')";	
		$result = core::database()->querySQL($query);
				
		if(core::database()->getRecordCount($result) == 0)
			return FALSE;
		else
			return TRUE;
	}
	
	static function NumberAlbums($owner_id, $videoalbumable_type)
	{
		$owner_id = core::database()->escape($owner_id);
		
		$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') ." WHERE videoalbumable_type='" . $videoalbumable_type . "' AND owner_id=" . $owner_id;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}
	
	static function getAlbumList($owner_id, $videoalbumable_type){
		
		if(is_numeric($owner_id) && $videoalbumable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') . " WHERE owner_id=" . $owner_id . " AND videoalbumable_type='" . $videoalbumable_type . "'";
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function getThumb($id_album)
	{
		if(is_numeric($id_album)){
			$query = "SELECT provider, video FROM " . core::database()->getTableName('videos') . " WHERE banned!=1 AND videoalbum_id = " . $id_album . " LIMIT 1";
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result);	
		
			return core::documentparser()->getThumb($row['provider'], $row['video']);
		}
	}	
	
	static function getVideoAlbumOption($owner_id, $videoalbumable_type)
	{
		if(is_numeric($owner_id) && $videoalbumable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') . " WHERE owner_id=" . $owner_id . " AND videoalbumable_type='" . $videoalbumable_type . "'";
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function addVideo($fields)
	{
		$insert_id = core::database()->insert($fields, core::database()->getTableName('videos'));
		
		return $insert_id;	
	}
	
	static function getVideosList($owner_id, $videoalbumable_type, $limit = 6, $offset = 0)
	{		
		if(is_numeric($owner_id) && $videoalbumable_type){
			$query = "SELECT *, a.id AS video_id, a.owner_id AS owner_id, b.owner_id AS id_albumowner  FROM " . core::database()->getTableName('videos') . " a 
						LEFT JOIN " . core::database()->getTableName('videoalbums') . " b ON b.id=a.videoalbum_id 
						WHERE (a.banned!=1) AND (b.owner_id=" . $owner_id . ") AND (b.videoalbumable_type='" . $videoalbumable_type . "')  
						LIMIT " . $limit . " OFFSET ".$offset."";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);		
		}
	}

	static function getVideosAlbumList($videoalbum_id, $limit = 4, $offset = 0){
		
		if(is_numeric($videoalbum_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('videos') . " WHERE videoalbum_id=" . $videoalbum_id . "	LIMIT " . $limit . " OFFSET ".$offset."";
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function NumberVideos($owner_id, $videoalbumable_type)
	{
		if(is_numeric($owner_id) && $videoalbumable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('videos') ." a 
						LEFT JOIN " . core::database()->getTableName('videoalbums') . " b ON b.id=a.videoalbum_id 
						WHERE (a.banned!=1) AND (b.owner_id=" . $owner_id . ") AND (b.videoalbumable_type='" . $videoalbumable_type . "')";
					
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
	
	static function editAlbum($fields, $id_album, $owner_id)
	{
		$table = core::database()->getTableName('videoalbums');
		$where = "owner_id=" . $owner_id . " AND id=" . $id_album;
		$result = core::database()->update($fields, $table, $where); 
		
		return $result;		
	}	
	
	static function removeAlbum($id_album, $owner_id){
		if(core::database()->delete(core::database()->getTableName('videos'), "videoalbum_id=" . $id_album. " AND owner_id=" . $owner_id, '') && core::database()->delete(core::database()->getTableName('videoalbums'), "id=" . $id_album . " AND owner_id=" . $owner_id,'')){
			return TRUE;
		}
		else
			return FALSE;
	}

	static function getPopularVideos($videoalbumable_type, $limit = 6, $offset = 0)
	{
		$query = "SELECT *, sum(l.user_id) pop, sum(w.user_id) pop2, v.id AS video_id FROM " . core::database()->getTableName('videos') . " v
					INNER JOIN  " . core::database()->getTableName('likes') . " l ON l.content_id=v.id
					LEFT JOIN " . core::database()->getTableName('videoalbums') . " b ON b.id=v.videoalbum_id
					LEFT JOIN " . core::database()->getTableName('video_views') . " w ON v.id=w.video_id
					WHERE (v.banned!=1) AND (b.videoalbumable_type='" . $videoalbumable_type . "') AND (l.likeable_type='video')
					GROUP by v.id
					ORDER by pop,pop2 DESC					
					LIMIT " . $limit . " OFFSET ".$offset."";
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}

	static function getNumberPopVideos($videoalbumable_type)
	{
		$query = "SELECT *, sum(l.user_id) pop, sum(w.user_id) pop2, v.id AS video_id FROM " . core::database()->getTableName('videos') . " v 
					INNER JOIN  " . core::database()->getTableName('likes') . " l ON l.content_id=v.id
					LEFT JOIN " . core::database()->getTableName('videoalbums') . " b ON b.id=v.videoalbum_id
					LEFT JOIN " . core::database()->getTableName('video_views') . " w ON v.id=w.video_id
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

	static function getNumberLiked($video_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('likes') . " WHERE content_id=" . $video_id  . " AND likeable_type='video' GROUP by user_id";
		$result = core::database()->querySQL($query);
			
		return core::database()->getRecordCount($result);
	}
	
	static function getNumberTell($video_id)
	{
		if(is_numeric($video_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('share') . " WHERE content_id=" . $video_id  . " AND shareable_type='video' GROUP by user_id";
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getNumberVideoViews($video_id)
	{
		if(is_numeric($video_id)){
			$query = "SELECT * FROM " . core::database()->getTableName('video_views') . " WHERE video_id=" . $video_id;
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getVideoInfo($video_id)
	{
		if(is_numeric($video_id)){
			$query = "SELECT *, DATE_FORMAT(a.created_at,'%Y-%m-%d') AS created, a.id AS video_id, a.owner_id AS owner_id, b.owner_id AS id_albumowner FROM " . core::database()->getTableName('videos') . " a
						LEFT JOIN " . core::database()->getTableName('videoalbums') . " b ON a.videoalbum_id=b.id
						LEFT JOIN " . core::database()->getTableName('users') . " c ON a.owner_id=c.id
						WHERE a.id=" . $video_id;
			
			$result = core::database()->querySQL($query);
		
			return core::database()->getRow($result);			
		}		
	}	
	
	static function countView($video_id, $user_id)
	{
		if(is_numeric($video_id) && is_numeric($user_id)){
			$fields = array();
			$fields['id'] = 0;
			$fields['user_id'] = $user_id;			
			$fields['video_id'] = $video_id;			
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
	
	static function checkOwner($id_album, $owner_id)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('videoalbums') . " WHERE id=" . $id_album . " AND owner_id=" . $owner_id;
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return FALSE;
		else
			return TRUE;
	}
	
	static function getUserPublishVideo($user_id, $limit = 5, $offset = 0)
	{
		$query = "SELECT *, u.id as user_id, f.friend_id AS friend_id, DATE_FORMAT(v.created_at, '%Y-%m-%d') AS added, DATE_FORMAT(v.created_at, '%Y%m%d%H%i%s') AS timeorder, v.id AS video_id FROM " . core::database()->getTableName('videos') . " v,  
					" . core::database()->getTableName('users') . " u, 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.user_id='" . $user_id . "'
						THEN f.friend_id=u.id
						WHEN f.friend_id='" . $user_id . "'
						THEN f.user_id=u.id
					END
					AND	((f.status='1') AND (v.owner_id!=" . $user_id . ") AND (v.owner_id=u.id))
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
		
		if(!core::database()->delete(core::database()->getTableName('comments'), "commentable_type='video' AND content_id=" . $id, '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		
		if(!core::database()->delete(core::database()->getTableName('likes'), "likeable_type='video' AND content_id=" . $id, '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}

		if(!core::database()->delete(core::database()->getTableName('share'), "shareable_type='video' AND content_id=" . $id, '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}		
		
		core::database()->querySQL('COMMIT');
		core::database()->querySQL('SET AUTOCOMMIT=1');
		
		return $result;
	}
}
