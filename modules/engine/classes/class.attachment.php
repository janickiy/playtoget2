<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Attach
{
	static function removeAttach($content_id, $type)
	{
		if(is_numeric($content_id) && !empty($type)){
			$query = "SELECT * FROM " . core::database()->getTableName('attachment') . " WHERE type='" . $type . "' AND content_id = " . $content_id;
			$result = core::database()->querySQL($query);
		
			$path = core::documentparser()->getAttachPath($type);
		
			while($row = core::database()->getRow($result))
			{
				if(file_exists($path . $row['small_photo'])) unlink($path . $row['small_photo']);
				if(file_exists($path . $row['photo'])) unlink($path . $row['photo']);		
			}

			core::database()->delete(core::database()->getTableName('attachment'), "type='" . $type . "' AND content_id = " . $content_id, '');
		}		
	}	
	
	static function uploadAttach($photo_id,$content_id,$type)  
    {  

    	$photo = Photoalbum::getPhotoInfo($photo_id);

		if (file_exists(PATH_COMMENT_ATTACHMENTS.$photo['photo'])){
						$fields = array();
						$fields['id'] = 0;			
						$fields['type'] = $type;
						$fields['content_id'] = $content_id;
						$fields['photo_id'] = $photo_id;									
						
						$insert_id = core::database()->insert($fields, core::database()->getTableName('attachment'));
						$info = 'FILE_SUCCESSFULLY_DOWNLOADED';
						$path_small_photo = $photo['photo'];
						$path_photo = $photo['small_photo'];
						$photo_id = $photo['photo_id'];
					}  
                    else 
                        $error = 'COULDNT_LOAD_FILE';

    	return array('info' => $info, 'photo_id' => $photo_id, 'small_photo' => $path_small_photo, 'photo' => $path_photo, 'error' => $error);
    }		
	
	static function getAttachList($content_id, $type)
	{		
		if(is_numeric($content_id) && $type){
			$query = "SELECT * FROM " . core::database()->getTableName('attachment') . " WHERE type='" . $type . "' AND content_id=" . $content_id;
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
}	