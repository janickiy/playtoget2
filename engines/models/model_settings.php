<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_settings extends Model
{
	public function changeUserSettings($fields, $user_id)
	{
		$arr_user = array();
		$arr_user['contact_email'] = $fields['contact_email'];		
		$arr_user['skype'] = $fields['skype'];		
		$arr_user['website'] = $fields['website'];		
		$arr_user['phone'] = $fields['phone'];
		
		$document_root = !empty($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/') : rtrim(dirname(dirname(__DIR__)), '/');
		$tmp_dir = $document_root . '/tmp/';
		$avatar_dir = $document_root . '/' . PATH_USER_AVATAR_IMAGES;
		$cover_page_dir = $document_root . '/' . PATH_USER_COVER_PAGE_IMAGES;
		$avatar = !empty($fields['avatar']) ? basename($fields['avatar']) : '';
		$cover_page = !empty($fields['cover_page']) ? basename($fields['cover_page']) : '';
		$avatar_tmp = $avatar ? $tmp_dir . $avatar : '';
		$cover_page_tmp = $cover_page ? $tmp_dir . $cover_page : '';
		if($avatar && file_exists($avatar_tmp)) $arr_user['avatar'] = $user_id . '_' . $avatar;
		if($cover_page && file_exists($cover_page_tmp)) $arr_user['cover_page'] = $user_id . '_' . $cover_page;
		
		$result = TRUE;
		
		core::database()->querySQL('SET AUTOCOMMIT=0');
		core::database()->querySQL('START TRANSACTION');
		
		$query = "SELECT avatar, cover_page FROM " . core::database()->getTableName('users') . " WHERE id=" . $user_id;
		$current_user_result = core::database()->querySQL($query);
		$current_user = core::database()->getRow($current_user_result);
		
		if(!core::database()->update($arr_user, core::database()->getTableName('users'), "id=" . $user_id)) {
			$result = FALSE;
			core::database()->querySQL('ROLLBACK');
		}		
		
		$query = "SELECT * FROM " . core::database()->getTableName('usersettings') . " WHERE user_id=" . $user_id;
		$settings_result = core::database()->querySQL($query);		
		
		if(core::database()->getRecordCount($settings_result) == 0){
			$arr_usersettings = array();
			$arr_usersettings['id'] = 0;
			$arr_usersettings['permission_send_message'] = $fields['permission_send_message'];			
			$arr_usersettings['permission_view_profile'] = $fields['permission_view_profile'];			
			$arr_usersettings['permission_view_friends'] = $fields['permission_view_friends'];			
			$arr_usersettings['permission_view_photo'] = $fields['permission_view_photo']; 					
			$arr_usersettings['permission_view_video'] = $fields['permission_view_video'];			
			$arr_usersettings['permission_view_wall'] = $fields['permission_view_wall'];			
			$arr_usersettings['permission_comment_photo'] = $fields['permission_comment_photo'];			
			$arr_usersettings['permission_comment_video'] = $fields['permission_comment_video'];			
			$arr_usersettings['permission_comment_wall'] = $fields['permission_comment_wall'];
			$arr_usersettings['notification_friends_request'] = $fields['notification_friends_request'];
			$arr_usersettings['notification_private_messages'] = $fields['notification_private_messages'];
			$arr_usersettings['notification_wall_comments'] = $fields['notification_wall_comments'];
			$arr_usersettings['notification_picture_comments'] = $fields['notification_picture_comments'];
			$arr_usersettings['notification_video_comments'] = $fields['notification_video_comments'];
			$arr_usersettings['notification_events'] = $fields['notification_events'];
			$arr_usersettings['notification_birthdays'] = $fields['notification_birthdays'];
			$arr_usersettings['notification_answers_in_comments'] = $fields['notification_answers_in_comments'];			
			$arr_usersettings['user_id'] = $user_id;
			
			if(!core::database()->insert($arr_usersettings, core::database()->getTableName('usersettings'))) {
				$result = FALSE;
				core::database()->querySQL('ROLLBACK');
			}			
		}
		else{
			$arr_usersettings = array();
			$arr_usersettings['permission_send_message'] = $fields['permission_send_message'];			
			$arr_usersettings['permission_view_profile'] = $fields['permission_view_profile'];			
			$arr_usersettings['permission_view_friends'] = $fields['permission_view_friends'];			
			$arr_usersettings['permission_view_photo'] = $fields['permission_view_photo']; 					
			$arr_usersettings['permission_view_video'] = $fields['permission_view_video'];			
			$arr_usersettings['permission_view_wall'] = $fields['permission_view_wall'];			
			$arr_usersettings['permission_comment_photo'] = $fields['permission_comment_photo'];			
			$arr_usersettings['permission_comment_video'] = $fields['permission_comment_video'];			
			$arr_usersettings['permission_comment_wall'] = $fields['permission_comment_wall'];
			$arr_usersettings['notification_friends_request'] = $fields['notification_friends_request'];
			$arr_usersettings['notification_private_messages'] = $fields['notification_private_messages'];
			$arr_usersettings['notification_wall_comments'] = $fields['notification_wall_comments'];
			$arr_usersettings['notification_picture_comments'] = $fields['notification_picture_comments'];
			$arr_usersettings['notification_video_comments'] = $fields['notification_video_comments'];
			$arr_usersettings['notification_events'] = $fields['notification_events'];
			$arr_usersettings['notification_birthdays'] = $fields['notification_birthdays'];
			$arr_usersettings['notification_answers_in_comments'] = $fields['notification_answers_in_comments'];

			if(!core::database()->update($arr_usersettings, core::database()->getTableName('usersettings'), "user_id=" . $user_id)) {
				$result = FALSE;
				
			
				core::database()->querySQL('ROLLBACK');
			}
		}
	
		core::database()->querySQL('COMMIT');
		core::database()->querySQL('SET AUTOCOMMIT=1');
		
			if($result) {
				if($avatar && isset($arr_user['avatar']) && file_exists($avatar_tmp)) {
					if($current_user['avatar'] && file_exists($avatar_dir . $current_user['avatar'])) unlink($avatar_dir . $current_user['avatar']);
					rename($avatar_tmp, $avatar_dir . $arr_user['avatar']);
				}
				if($cover_page && isset($arr_user['cover_page']) && file_exists($cover_page_tmp)) {
					if($current_user['cover_page'] && file_exists($cover_page_dir . $current_user['cover_page'])) unlink($cover_page_dir . $current_user['cover_page']);
					rename($cover_page_tmp, $cover_page_dir . $arr_user['cover_page']);
				}
			}
		
		return $result; 		
	}    
}
