<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Photoalbum
{
	static function createAlbum($fields){
		$insert_id = core::database()->insert($fields, core::database()->getTableName('photoalbums'));
		
		return $insert_id;		
	}
	
	static function editAlbum($fields, $id_album)
	{
		return core::database()->update($fields, core::database()->getTableName('photoalbums'), "id=" . $id_album); 		
	}
	
	static function removeAlbum($id_album)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('photos') . " p
					LEFT JOIN " . core::database()->getTableName('photoalbums') . " a ON p.id_photoalbum=a.id
					WHERE a.id=" . $id_album;
		
		$result = core::database()->querySQL($query);
		
		while($row = core::database()->getRow($result)){
			$path = core::documentparser()->getPhotogalleryPath($row['photoalbumable_type']);	
			
			if(file_exists($path . $row['small_photo'])) unlink($path . $row['small_photo']);
			if(file_exists($path . $row['photo'])) unlink($path . $row['photo']);
		}
		
		if(core::database()->delete(core::database()->getTableName('photos'), "id_photoalbum=" . $id_album,'') && core::database()->delete(core::database()->getTableName('photoalbums'), "id=" . $id_album,'')){
			return TRUE;
		}
		else
			return FALSE;		
	}

	static function getAlbumList($id_owner, $photoalbumable_type)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('photoalbums') . " WHERE id_owner=" . $id_owner . " AND photoalbumable_type='" . $photoalbumable_type . "'";
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}

	static function getPhotosList($id_owner, $photoalbumable_type, $limit, $offset=0)
	{
		$id_owner = core::database()->escape($id_owner);
		
		$query = "SELECT *, a.id AS id_photo FROM " . core::database()->getTableName('photos') . " a 
					LEFT JOIN " . core::database()->getTableName('photoalbums') . " b ON a.id_photoalbum=b.id 
					WHERE (a.banned!=1) AND (b.photoalbumable_type='" . $photoalbumable_type . "') AND (b.id_owner=" . $id_owner . ")
					ORDER BY a.id DESC					
					LIMIT " . $limit ." OFFSET " . $offset . "";
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}

	static function uploadHandle($max_file_size = 102400, $upload_dir = '.', $i = 0, $id_photoalbum, $description, $id_user)  
    {  
		$id_user = core::database()->escape($id_user);
		$id_photoalbum = core::database()->escape($id_photoalbum);
	
		$valid_extensions = array('jpg', 'jpeg', 'png', 'gif');  
	   
		$error = null;  
		$info  = null;  
		$max_file_size *= 1048576;
		
        if ($_FILES['photo']['error'][$i] === UPLOAD_ERR_OK){  

			$file_extension = pathinfo($_FILES['photo']['name'][$i], PATHINFO_EXTENSION);
			
			if (in_array(strtolower($file_extension), $valid_extensions)){  
                if ($_FILES['photo']['size'][$i] < $max_file_size){  
					$photo = md5(time() . $_FILES['photo']['name'][$i]). '.' . $file_extension;
					$small_photo = 's_' . $photo;
                    $destination = $upload_dir . '/' . $photo; 					
					
					$size_img = getimagesize($_FILES['photo']['tmp_name'][$i]); 

					//if ($size_img[0] > 200){
						$image = new SimpleImage();
						$image->load($_FILES['photo']['tmp_name'][$i]);
						//$image->resize(203, 120);							
						
						if($size_img[0] > $size_img[1]){
							if($size_img[0] > 203) $image->resizeToWidth(203);
						}
						else{
							if($size_img[1] > 120) $image->resizeToHeight(120);
						}
						
						$image->save($upload_dir . '/' . $small_photo);					
					//}					
       
                    if (move_uploaded_file($_FILES['photo']['tmp_name'][$i], $destination)){
						$fields = array();
						$fields['id'] = 0;						
						$fields['id_photoalbum'] = $id_photoalbum;						 	
						$fields['small_photo'] = $small_photo;
						$fields['photo'] = $photo;
						$fields['description'] = $description;
						$fields['id_owner'] = $id_user;
						$fields['created_at'] = date("Y-m-d H:i:s");
						$fields['moderate'] = 0;						
						
						$insert_id = core::database()->insert($fields, core::database()->getTableName('photos'));
						$info = 'FILE_SUCCESSFULLY_DOWNLOADED';
					}  
                    else 
                        $error = 'COULDNT_LOAD_FILE';  
				}   
				else 
					$error = 'LARGER_THAN_ALLOWED';  
			}   
			else 
				$error = 'FILE_EXTENSION_ISNT_VALID';  
		}   
        else{  
		
		$error_values = array( 
			'UPLOAD_ERR_INI_SIZE',  
			'UPLOAD_ERR_FORM_SIZE',                            
			'UPLOAD_ERR_PARTIAL',   
			'UPLOAD_ERR_NO_FILE',   
			'UPLOAD_ERR_NO_TMP_DIR',   
			'UPLOAD_ERR_CANT_WRITE'
		);  
			
		$error_code = $_FILES['file']['error'][$i]; 
		
		if (!empty($error_values[$error_code]))  
			$error = $error_values[$error_code];   
		else 
			$error = 'HAPPENED_SOMETHING_STRANGE';  
        }  
       
        return array('info' => $info, 'error' => $error);  
    }
	
	static function uploadHandlePup($max_file_size = 102400, $upload_dir = '.', $i = 0, $id_photoalbum, $description, $id_owner)  
    {  
		$valid_extensions = array('jpg', 'jpeg', 'png', 'gif');  
	   
		$error = null;  
		$info  = null;  
		$insert_id = null;
		$max_file_size *= 1048576;	

		if (empty($_FILES['file'])) {
			return array('info' => null, 'id' => null, 'error' => 'UPLOAD_ERR_NO_FILE');
		}

		$file_error = is_array($_FILES['file']['error']) ? $_FILES['file']['error'][$i] : $_FILES['file']['error'];
		$file_name = is_array($_FILES['file']['name']) ? $_FILES['file']['name'][$i] : $_FILES['file']['name'];
		$file_size = is_array($_FILES['file']['size']) ? $_FILES['file']['size'][$i] : $_FILES['file']['size'];
		$file_tmp_name = is_array($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'][$i] : $_FILES['file']['tmp_name'];
		
        if ($file_error === UPLOAD_ERR_OK){  
		
			$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
			
			if (in_array(strtolower($file_extension), $valid_extensions)){  
                if ($file_size < $max_file_size){  
					$photo = md5(microtime(TRUE) . $file_name). '.' . $file_extension;
					$small_photo = 's_' . $photo;
                    $destination = $upload_dir . '/' . $photo; 					
					$size_img = getimagesize($file_tmp_name); 

					if (!$size_img) {
						return array('info' => null, 'error' => 'FILE_EXTENSION_ISNT_VALID');
					}

					$img = new abeautifulsite\SimpleImage();
					$img->load($file_tmp_name)->fit_to_height(300)->auto_orient()->save($upload_dir . '/' . $small_photo);
					$img->load($file_tmp_name)->fit_to_width(800)->auto_orient()->save($destination);						
       
                    if (file_exists($destination)){
						$fields = array();
						$fields['id'] = 0;						
						$fields['id_photoalbum'] = $id_photoalbum;						 	
						$fields['small_photo'] = $small_photo;
						$fields['photo'] = $photo;
						$fields['description'] = $description;
						$fields['id_owner'] = $id_owner;
						$fields['created_at'] = date("Y-m-d H:i:s");
						$fields['moderate'] = 0;						
						
						$insert_id = core::database()->insert($fields, core::database()->getTableName('photos'));
						$info = 'FILE_SUCCESSFULLY_DOWNLOADED';
					}  
                    else 
                       $error = 'COULDNT_LOAD_FILE';  
				}   
				else 
					$error = 'LARGER_THAN_ALLOWED';  
			}   
			else 
				$error = 'FILE_EXTENSION_ISNT_VALID';  
		}   
        else{  
		
		$error_values = array( 
			'UPLOAD_ERR_INI_SIZE',  
			'UPLOAD_ERR_FORM_SIZE',                            
			'UPLOAD_ERR_PARTIAL',   
			'UPLOAD_ERR_NO_FILE',   
			'UPLOAD_ERR_NO_TMP_DIR',   
			'UPLOAD_ERR_CANT_WRITE'
		);  
			
		$error_code = $file_error; 
		
		if (!empty($error_values[$error_code]))  
			$error = $error_values[$error_code];   
		else 
			$error = 'HAPPENED_SOMETHING_STRANGE';  
        }  
       
        return array('info' => $info,'id' => $insert_id, 'error' => $error);  
    }
	
	static function getAlbumsOptionList($id_owner, $photoalbumable_type)
	{
		if(is_numeric($id_owner) && $photoalbumable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('photoalbums') . " WHERE photoalbumable_type='" . $photoalbumable_type . "' AND id_owner=" . $id_owner . " ORDER by name";
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}

	static function getPhotoAlbumInfo($id_photoalbum)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('photoalbums') . " WHERE id=" . $id_photoalbum;
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);	
	}
	
	static function getPhotoInfo($id_photo)
	{
		if(is_numeric($id_photo)){
			$query = "SELECT *, DATE_FORMAT(a.created_at,'%Y-%m-%d') AS created, a.id AS id_photo, a.id_owner AS id_owner, b.id_owner AS photoalbum_owner FROM " . core::database()->getTableName('photos') . " a
						LEFT JOIN " . core::database()->getTableName('photoalbums') . " b ON a.id_photoalbum=b.id
						LEFT JOIN " . core::database()->getTableName('users') . " c ON a.id_owner=c.id
						WHERE a.id=" . $id_photo;
			
			$result = core::database()->querySQL($query);
		
			return core::database()->getRow($result);			
		}		
	}
	
	static function getPicList($id_photoalbum, $limit = 5, $offset = 0)
	{
		if(is_numeric($id_photoalbum)){
			$query = "SELECT * FROM " . core::database()->getTableName('photos') . " WHERE banned!=1 AND id_photoalbum=" . $id_photoalbum . " ORDER BY id DESC LIMIT " . $limit . " OFFSET " . $offset . "";
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}		
	}	
	
	static function checkNameExists($name, $id_owner, $photoalbumable_type)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('photoalbums') . " WHERE (id_owner='" . $id_owner . "') AND (photoalbumable_type='" . $photoalbumable_type . "') AND (name LIKE '".$name."')";	
		$result = core::database()->querySQL($query);
				
		if(core::database()->getRecordCount($result) == 0)
			return FALSE;
		else
			return TRUE;
	}
	
	static function getMainImage($id_photoalbum)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('photos') . " p 
					LEFT JOIN " . core::database()->getTableName('photoalbums') . " a ON p.id_photoalbum=a.id
					WHERE p.banned!=1 AND p.id_photoalbum=" . $id_photoalbum . " ORDER by p.id DESC LIMIT 1";
		$result = core::database()->querySQL($query);
		
		return core::database()->getRow($result);		
	}
	
	static function getNumberAlbums($id_owner, $photoalbumable_type)
	{
		if(is_numeric($id_owner) && $photoalbumable_type){
			$query = "SELECT * FROM " . core::database()->getTableName('photoalbums') ." WHERE photoalbumable_type='" . $photoalbumable_type . "' AND id_owner=" . $id_owner;
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function NumberPhotos($id_owner, $photoalbumable_type)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('photos') . " a 
					LEFT JOIN  " . core::database()->getTableName('photoalbums') . " b ON b.id=a.id_photoalbum 
					WHERE (a.banned!=1) AND (b.photoalbumable_type='" . $photoalbumable_type . "') AND (b.id_owner=" . $id_owner . ")";
					
		$result = core::database()->querySQL($query);
		
		return core::database()->getRecordCount($result);
	}
	
	static function getNumberLiked($id_photo)
	{
		if(is_numeric($id_photo)){
			$query = "SELECT * FROM " . core::database()->getTableName('likes') . " WHERE id_content=" . $id_photo  . " AND likeable_type='photo' GROUP by id_user";
			$result = core::database()->querySQL($query);
				
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getNumberTell($id_photo)
	{
		if(is_numeric($id_photo)){
			$query = "SELECT * FROM " . core::database()->getTableName('share') . " WHERE id_content=" . $id_photo  . " AND shareable_type='photo' GROUP by id_user";
			$result = core::database()->querySQL($query);
		
			return core::database()->getRecordCount($result);
		}
	}
	
	static function getPopularPhotos($photoalbumable_type, $limit = 5, $offset=0)
	{
		$query = "SELECT *, sum(l.id_user) pop, p.id AS id_photo FROM " . core::database()->getTableName('photos') . " p 
					LEFT JOIN " . core::database()->getTableName('photoalbums') . " a ON a.id=p.id_photoalbum
					INNER JOIN  " . core::database()->getTableName('likes') . " l ON l.id_content=p.id
					WHERE (p.banned!=1) AND (a.photoalbumable_type='" . $photoalbumable_type . "') AND (l.likeable_type='photo')
					GROUP by p.id
					ORDER by pop DESC
					LIMIT " . $limit . " OFFSET " . $offset . "";
		
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);		
	}		
	
	static function NumberTotalPopPhotos($photoalbumable_type)
	{
		$query = "SELECT *, sum(l.id_user) pop FROM " . core::database()->getTableName('photos') . " p 
					LEFT JOIN " . core::database()->getTableName('photoalbums') . " a ON a.id=p.id_photoalbum
					INNER JOIN  " . core::database()->getTableName('likes') . " l ON l.id_content=p.id
					WHERE (p.banned!=1) AND (a.photoalbumable_type='" . $photoalbumable_type . "') AND (l.likeable_type='photo')
					GROUP by p.id";
					
		$result = core::database()->querySQL($query);

		return core::database()->getRecordCount($result);		
	}
	
	static function removePhoto($id)
	{
		$row = Photoalbum::getPhotoInfo($id);		
			
		$path = core::documentparser()->getPhotogalleryPath($row['photoalbumable_type']);

		core::database()->querySQL('SET AUTOCOMMIT=0');
		core::database()->querySQL('START TRANSACTION');
		
		$result = TRUE;
		
		if(!core::database()->delete(core::database()->getTableName('photos'), "id=" . $row['id_photo'], '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		
		if(!core::database()->delete(core::database()->getTableName('comments'), "commentable_type='photo' AND id_content=" . $row['id_photo'], '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		
		if(!core::database()->delete(core::database()->getTableName('likes'), "likeable_type='photo' AND id_content=" . $row['id_photo'], '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}

		if(!core::database()->delete(core::database()->getTableName('share'), "shareable_type='photo' AND id_content=" . $row['id_photo'], '')){
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}
		
		core::database()->querySQL('COMMIT');
		core::database()->querySQL('SET AUTOCOMMIT=1');
		
		if($result){
			if(file_exists($path . $row['small_photo'])) unlink($path . $row['small_photo']);
			if(file_exists($path . $row['photo'])) unlink($path . $row['photo']);
		}
		
		return $result;
	}
	
	static function checkExistence($id)
	{
		if(is_numeric($id)){
			$query = "SELECT * FROM " . core::database()->getTableName('photoalbums') . " WHERE id=" . $id;
			$result = core::database()->querySQL($query);
		
			if(core::database()->getRecordCount($result) == 0)
				return TRUE;
			else
				return FALSE;
		}		
	}
	
	static function checkOwner($id_album, $id_owner)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('photoalbums') . " WHERE id=" . $id_album . " AND id_owner=" . $id_owner;
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) == 0) 
			return FALSE;
		else
			return TRUE;
	}
	
	static function getUserPublishPhoto($id_user, $limit = 5, $offset = 0)
	{
		if(is_numeric($id_user)){
			$query = "SELECT *,u.id as id_user, f.id_friend AS id_friend, DATE_FORMAT(p.created_at, '%Y-%m-%d') AS added, DATE_FORMAT(p.created_at, '%Y%m%d%H%i%s') AS timeorder, p.id AS id_photo FROM " . core::database()->getTableName('photos') . " p,
					" . core::database()->getTableName('photoalbums') . " a,
					" . core::database()->getTableName('users') . " u, 
					" . core::database()->getTableName('friends') . " f
					WHERE
					CASE
						WHEN f.id_user='" . $id_user . "'
						THEN f.id_friend=u.id
						WHEN f.id_friend='" . $id_user . "'
						THEN f.id_user=u.id
					END					
					AND	((f.status='1') AND (p.id_owner!=" . $id_user . ") AND (a.photoalbumable_type='user') AND (a.id=p.id_photoalbum) AND (p.id_owner=u.id))
					ORDER BY p.created_at DESC
					LIMIT " . $limit . " OFFSET " . $offset . "
					";					
		
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
	
	static function getAllPicList($id_photoalbum)
	{
		if(is_numeric($id_photoalbum)){
			$query = "SELECT *, p.id AS id_photo FROM " . core::database()->getTableName('photos') . " p 
						LEFT JOIN " . core::database()->getTableName('photoalbums') . " a ON p.id_photoalbum=a.id
						WHERE p.banned!=1 AND p.id_photoalbum=" . $id_photoalbum . " ORDER BY p.id DESC";
					
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}		
	}
}
