<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Attach
{
	static function removeAttach($id_content, $type)
	{
		if(is_numeric($id_content) && !empty($type)){
			$query = "SELECT * FROM " . core::database()->getTableName('attachment') . " WHERE type='" . $type . "' AND id_content = " . $id_content;
			$result = core::database()->querySQL($query);
		
			$path = core::documentparser()->getAttachPath($type);
		
			while($row = core::database()->getRow($result))
			{
				if(file_exists($path . $row['small_photo'])) unlink($path . $row['small_photo']);
				if(file_exists($path . $row['photo'])) unlink($path . $row['photo']);		
			}

			core::database()->delete(core::database()->getTableName('attachment'), "type='" . $type . "' AND id_content = " . $id_content, '');
		}		
	}	
	
	static function uploadAttach($id_photo,$id_content,$type)  
    {  

    	$photo = Photoalbum::getPhotoInfo($id_photo);

		if (file_exists(PATH_COMMENT_ATTACHMENTS.$photo['photo'])){
						$fields = array();
						$fields['id'] = 0;			
						$fields['type'] = $type;
						$fields['id_content'] = $id_content;
						$fields['id_photo'] = $id_photo;									
						
						$insert_id = core::database()->insert($fields, core::database()->getTableName('attachment'));
						$info = 'FILE_SUCCESSFULLY_DOWNLOADED';
						$path_small_photo = $photo['photo'];
						$path_photo = $photo['small_photo'];
						$id_photo = $photo['id_photo'];
					}  
                    else 
                        $error = 'COULDNT_LOAD_FILE';

    	return array('info' => $info, 'id_photo' => $id_photo, 'small_photo' => $path_small_photo, 'photo' => $path_photo, 'error' => $error);
    }		
	
	static function getAttachList($id_content, $type)
	{		
		if(is_numeric($id_content) && $type){
			$query = "SELECT * FROM " . core::database()->getTableName('attachment') . " WHERE type='" . $type . "' AND id_content=" . $id_content;
			$result = core::database()->querySQL($query);
		
			return core::database()->getColumnArray($result);
		}
	}
}	