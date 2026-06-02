<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

if($_SESSION['user_authorization'] == "ok"){
	core::user()->setUser_id($_SESSION['id_user']);
	$user = core::user()->getUserInfo();
	
	core::user()->setUserActivity();	
}

switch ($_GET['action'])
{	
	case removepic:
	
		Auth::authorization();
	
		if($_REQUEST['id']){
			
			$id_photo = core::database()->escape((int)Core_Array::getRequest('id'));	

			$row = Photoalbum::getPhotoInfo($id_photo);
			
			$allow = FALSE;
			
			switch($row['photoalbumable_type']) {
		
				case user:
					if($row['id_owner'] == $user['id']) $allow = TRUE;
				break;

				case user_attach:
					if($row['id_owner'] == $user['id']) $allow = TRUE;
				break;

				case team: 		
					if(Communities::checkOwnerCommunity($row['photoalbum_owner'], $user['id']) or Communities::checkAdminCommunity($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;	
				
				case group: 		
					if(Communities::checkOwnerCommunity($row['photoalbum_owner'], $user['id']) or Communities::checkAdminCommunity($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;		
		
				case event: 		
					if($row['id_owner'] == $user['id'] or Events::checkOwnerEvent($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;
		
				case playground:		
					if($row['id_owner'] == $user['id'] or Playgrounds::checkOwnerPlayground($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;
		
				case fitness:		
					if($row['id_owner'] == $user['id'] or SportBlocks::checkOwner($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;
		
				case shop: 		
					if($row['id_owner'] == $user['id'] or SportBlocks::checkOwner($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;
			}			
			
			if($allow){
				if(Photoalbum::removePhoto($id_photo))
					$content = array("result" => 'success');
				else
					$content = array("result" => 'error');
			}	
			else $content = array("result" => 'error');	
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case removevideo:
	
		Auth::authorization();
	
		if($_REQUEST['id']){
			
			$id = core::database()->escape((int)Core_Array::getRequest('id'));
			$row = Videoalbum::getVideoInfo($id);

			$allow = FALSE;
			
			switch($row['videoalbumable_type']) {
		
				case user:
					if($row['id_owner'] == $user['id']) $allow = TRUE;
				break;			
		
				case team: 		
					if(Communities::checkOwnerCommunity($row['id_albumowner'], $user['id']) or Communities::checkAdminCommunity($row['id_albumowner'], $user['id'])) $allow = TRUE;
				
				break;	
				
				case group: 		
					if(Communities::checkOwnerCommunity($row['id_albumowner'], $user['id']) or Communities::checkAdminCommunity($row['id_albumowner'], $user['id'])) $allow = TRUE;
				break;		
		
				case event: 		
					if($row['id_owner'] == $user['id'] or Events::checkOwnerEvent($row['id_albumowner'], $user['id'])) $allow = TRUE;
				break;
		
				case playground:		
					if($row['id_owner'] == $user['id'] or Playgrounds::checkOwnerPlayground($row['id_albumowner'], $user['id'])) $allow = TRUE;
				break;
		
				case fitness:		
					if($row['id_owner'] == $user['id'] or SportBlocks::checkOwner($row['id_albumowner'], $user['id'])) $allow = TRUE;
				break;
		
				case shop: 		
					if($row['id_owner'] == $user['id'] or SportBlocks::checkOwner($row['id_albumowner'], $user['id'])) $allow = TRUE;
				break;
			}
			
			if($allow){
				if(Videoalbum::removevideo($id))
					$content = array("result" => 'success');
				else
					$content = array("result" => 'error');
			}
			else 
				$content = array("result" => 'error');						

			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case addcomment:
	
		Auth::authorization();
	
		$errors = array();

		$id_user = is_numeric($_REQUEST['id_user']) ? $_REQUEST['id_user'] : exit();		
		$id_content = is_numeric($_REQUEST['id_content']) ? $_REQUEST['id_content'] : exit();		
		$id_parent = is_numeric($_REQUEST['id_parent']) ? $_REQUEST['id_parent'] : exit();
		$attach = $_REQUEST['attach'] ? explode(",", $_REQUEST['attach']) : '';
		
		$author_community = $_REQUEST['author_community'] ? $_REQUEST['author_community'] : ''; 

		$commentable_type = Core_Array::getRequest('commentable_type');
		$comment = htmlspecialchars(trim(Core_Array::getRequest('comment')));
		
		if($commentable_type == 'user'){
			core::user()->setUser_id($id_content);
			$owner = core::user()->getUserInfo();
			$owner_settings = core::user()->getUserSetting();
			
			if(core::user()->permissionUser($user['id'], $owner_settings['permission_view_wall']) && $data->checkBlock($user['id'], $id_content))
				$permit = 'yes';
			else
				$permit = 'no';
		}		
		
		if(empty($errors) && $permit != 'no' && Comments::checkBanReceiver($commentable_type, $id_content)){
			$fields = array();
			$fields['id'] = 0;
			$fields['commentable_type'] = $commentable_type;
			$fields['id_content'] = $id_content;
			$fields['id_user'] = $user['id'];
			$fields['content'] = $comment;
			$fields['created_at'] = date("Y-m-d H:i:s");
			$fields['id_parent'] = $id_parent;			
			
			if($commentable_type == 'group' or $commentable_type == 'team'){
				if($author_community == 1 && Communities::checkOwnerCommunity($id_content, $user['id']) or Communities::checkAdminCommunity($id_content, $user['id'])) {
					$fields['behalfable_type'] = $commentable_type;
					$fields['id_behalf'] = $fields['id_content'];
					$head = TRUE;
				}
			}
			else if($commentable_type == 'event'){
				if($author_community == 1 && Events::checkOwnerEvent($id_content, $user['id'], 'user')) {
					$fields['behalfable_type'] = $commentable_type;
					$fields['id_behalf'] = $fields['id_content'];
					$head = TRUE;
				}
			}
			else $head = FALSE;
			
			$insert_id = $data->addComment($fields);
			
			if($insert_id){
				$row = $data->makeUpComment($insert_id);
						
				if($head){
					$avatar = Comments::getCommentAvatar($row['id_comment']);
					$name = Comments::getCommentAuthorName($row['id_comment']);
					
					switch($commentable_type){
						
						case team:
						
							$page_link = './?task=teams&id_user=' . $id_content;
						
						break;
						
						case group:
						
							$page_link = './?task=groups&id_user=' . $id_content;
							
						break;
						
						case event:
						
							$page_link = './?task=events&id_user=' . $id_content;
							
						break;
					}
				}else{
					$avatar = core::documentparser()->userAvatar($row);
					$name = $row['firstname'] . ' '  . $row['lastname'].'<span class="status_user' . (core::user()->checkUserOnline($row['id_user']) ? ' online' : '') . '" data-num="' . $row['id_user'] . '"></span>';
					$page_link = './?task=profile&id_user=' . $row['id_user'];
				}	

				if($id_parent == 0){
					$html = '<div id="message-' . $insert_id . '" class="message">';
					$html .= '<div class="del_mess" data-item="' . $insert_id . '"></div>';
					
					if ($commentable_type == 'event' && $head){
						$html .= '<div class="img-account">';
	                  	$html .= '<img src="' . $avatar . '" alt="" class="event">';
	                	$html .= '</div>';
					}	
					else
						$html .= '<img src="' . $avatar . '" alt="" class="img-account">';

					$html .= '<h5 class="name"><a href="'. $page_link .'">' . $name . '</a></h5>';
					$html .= '<p class="data">' . $row['created'] . '</p>';	
					$html .= '<p class="message-text">' . core::documentparser()->link_replace($row['content']) . '<br>';
					
					if ($attach > ''){
						$html .= '<ul class="attach_image">';
					
						for( $i = 0, $length = count($attach); $i < $length; $i++){  
							$html .= '<li>';
							$message = Attach::uploadAttach($attach[$i], $insert_id,'comment');
							
							if($message['small_photo'] && file_exists(PATH_COMMENT_ATTACHMENTS . $message['small_photo'])) $html .=  '<img border="0" src="'  . PATH_COMMENT_ATTACHMENTS . $message['small_photo']. '" class="photo_big"  data-num='.$message['id_photo'].'>';
						
							$html .= '</li>';
						}
						
						$html .= '</ul>';
					}	
					
					$html .= '</p>';
					$html .= '<a id="reply-' . $insert_id . '" class="reply" data-item="' . $insert_id . '">' . core::getLanguage('str', 'reply') . '</a>';
					$html .= '<a id="like-comment-' . $insert_id . '" class="liked" data-item="' . $insert_id . '" data-type="comment">0</span></a>';
					$html .= '</div>';
				}
				else{
					$html = '<div class="message-reply message" id="message-' . $insert_id . '" data-item="' . $insert_id . '">';
					$html .= '<div class="del_mess" data-item="' . $insert_id . '"></div>';
					$html .= '<div class="message" >';
					$html .= '<div class="message-account" >';
					
					if ($commentable_type == 'event' && $head){
						$html .= '<div class="img-account">';
	                  	$html .= '<img src="' . $avatar . '" alt="" class="event">';
	                	$html .= '</div>';
					}	
					else
						$html .= '<img src="' . $avatar . '" alt="" class="img-account">';
					
					$html .= '<h5 class="name"><a href="'. $page_link .'">' . $name . '</a></h5>';
					$html .= '<p class="data">' . $row['created'] . '</p>';					
					$html .= '</div>';					
					$html .= '<p class="message-reply-text">' . core::documentparser()->link_replace($row['content']) . '<br>';
					
					if ($attach > ''){
						$html .= '<ul class="attach_image">';
					
						for( $i = 0, $length = count($attach); $i < $length; $i++){  
							$html .= '<li>';
							$message = Attach::uploadAttach($attach[$i], $insert_id, 'comment');
							
							if($message['small_photo'] && file_exists(PATH_COMMENT_ATTACHMENTS . $message['small_photo'])) $html .=  '<img border="0" src="'  . PATH_COMMENT_ATTACHMENTS . $message['small_photo']. '" class="photo_big" data-num='.$message['id_photo'].'>';
						
							$html .= '</li>';
						}
						
						$html .= '</ul>';
					}

					$html .= '</div>';
					
					/*$html .= '<a id="reply-' . $insert_id . '" class="reply" data-item="' . $insert_id . '">' . core::getLanguage('str', 'reply') . '</a>';			
					$html .= '<a id="tell-comment-' . $insert_id . '" class="tell" data-item="' . $insert_id . '" data-type="comment">0</a>';				
					$html .= '<a id="like-comment-' . $insert_id . '" class="liked" data-item="' . $insert_id . '" data-type="comment">0</a>';				
					*/
					
					$html .= '</div>';		
					$html .= '</div>';			
				}				
				
				$content = array();
				$content['status'] = 1;				
				$content['html'] = $html;
				
				$content = json_encode($content);	
			
				$settings = core::user()->getUserSetting();			
			
				if($id_parent == 0){			
					if($commentable_type == 'user')	{
						if($settings['notification_wall_comments'] != 'no' && $user['id'] != $id_content){						
							core::user()->setUser_id($id_content);
							$receiver = core::user()->getUserInfo();
						
							$mail = $data->getMailNotification(3);	
			
							$subject = $mail['subject_ru'];
							$subject = str_replace('%USER_FIRSTNAME%', $user['firstname'], $subject);
							$subject = str_replace('%USER_LASTNAME%', $user['lastname'], $subject);
			
							$message = array();
							$message['subject'] = $subject;
							$message['name'] = $user['lastname'] . ' ' . $user['firstname'];
							$message['copyright'] = core::getLanguage('str', 'copyright');							
							$message['avatar'] = core::documentparser()->userAvatar($user);						
						
							$published = core::getLanguage('str', 'was_published');   						
							$published = str_replace('%DATE%', date("Y-m-d"), $published); 
							$published = str_replace('%TIME%', date("H:i"), $published);						
							$message['date'] = $published;						
							$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $user['id'];

							$msg = $mail['content_ru'];
							
							$pos = strpos(substr($msg, 800), " ");			

							if(strlen($msg) > 800) 
								$srttmpend = "...";
							else 
								$strtmpend = "";
			
							$msg = substr($msg, 0, 800 + $pos) . $srttmpend; 								
							
							$msg = str_replace('%COMMENT%', htmlspecialchars(trim(Core_Array::getRequest('comment'))), $msg);
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $user['id'], $msg);
							$message['msg'] = $msg;
							
							$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
							$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
							$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;							

							core::documentparser()->userNotification($receiver['email'], $message);						
						}
					}
					else if($commentable_type == 'photo'){						
						if($settings['notification_picture_comments'] != 'no' && $user['id'] != $id_content){
							$mail = $data->getMailNotification(4);	
							$photo = Photoalbum::getPhotoInfo($id_content);

							$small_photo = core::documentparser()->photogalleryPic($photo['small_photo']);
						
							core::user()->setUser_id($photo['id_owner']);
							$receiver = core::user()->getUserInfo();	
			
							$subject = $mail['subject_ru'];
							$subject = str_replace('%USER_FIRSTNAME%', $user['firstname'], $subject);
							$subject = str_replace('%USER_LASTNAME%', $user['lastname'], $subject);						
					
							$msg = $mail['content_ru'];
							
							$pos = strpos(substr($msg, 800), " ");			

							if(strlen($msg) > 800) 
								$srttmpend = "...";
							else 
								$strtmpend = "";
			
							$msg = substr($msg, 0, 800 + $pos) . $srttmpend;							
							$msg = str_replace('%FIRSTNAME%', $receiver['firstname'], $msg);							
							$msg = str_replace('%PHOTO%', 'cid:photo', $msg);							
							$msg = str_replace('%COMMENT%', htmlspecialchars(trim(Core_Array::getRequest('comment'))), $msg);
							if($photo['photoalbumable_type'] == 'user') 
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=photoalbums&id_album=' . $photo['id_photoalbum'] . '&id_user=' . $photo['id_owner'], $msg);
							else if($photo['photoalbumable_type'] == 'team')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&id_community=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
							else if($photo['photoalbumable_type'] == 'group')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&id_community=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
							else if($photo['photoalbumable_type'] == 'event')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&id_event=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
							else if($photo['photoalbumable_type'] == 'playground')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_playground=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
							else if($photo['photoalbumable_type'] == 'fitness')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
							else if($photo['photoalbumable_type'] == 'shop')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
				
							$message = array();
							
							$message['photo'] = core::documentparser()->photogalleryPic($photo['small_photo']);						
							$message['subject'] = $subject;
							$message['name'] = $user['lastname'] . ' ' . $user['firstname'];
							$message['copyright'] = core::getLanguage('str', 'copyright');
							$message['avatar'] = core::documentparser()->userAvatar($user);
						
							$published = core::getLanguage('str', 'was_published');   						
							$published = str_replace('%DATE%', date("Y-m-d"), $published); 
							$published = str_replace('%TIME%', date("H:i"), $published);						
							$message['date'] = $published;						
							$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $user['id'];
							$message['msg'] = $msg;								
							$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
							$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
							$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;	
							
							core::documentparser()->userNotification($receiver['email'], $message);				
						}					
					}
					else if($commentable_type == 'video'){
						if($settings['notification_video_comments'] != 'no' && $user['id'] != $id_content){
							$mail = $data->getMailNotification(5);	
			
							$video = Videoalbum::getVideoInfo($id_content);
						
							core::user()->setUser_id($video['id_owner']);
							$receiver = core::user()->getUserInfo();	
			
							$subject = $mail['subject_ru'];
							$subject = str_replace('%USER_FIRSTNAME%', $user['firstname'], $subject);
							$subject = str_replace('%USER_LASTNAME%', $user['lastname'], $subject);
			
							$msg = $mail['content_ru'];
							
							$pos = strpos(substr($msg, 800), " ");			

							if(strlen($msg) > 800) 
								$srttmpend = "...";
							else 
								$strtmpend = "";
			
							$msg = substr($msg, 0, 800 + $pos) . $srttmpend;							
							$msg = str_replace('%FIRSTNAME%', $receiver['firstname'], $msg);
							$msg = str_replace('%ID_USER%', $user['id'], $msg);										
							$msg = str_replace('%VIDEO_LINK%', core::documentparser()->getVideoLink($video['provider'], $video['video']), $msg);						
							$msg = str_replace('%VIDEO_THUMB%', 'cid:video', $msg);						
							$msg = str_replace('%COMMENT%', htmlspecialchars(trim(Core_Array::getRequest('comment'))), $msg);							
							
							if($video['videoalbumable_type'] == 'user')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=videoalbums&id_album=' . $video['videoalbum'] . '&id_user=' . $video['id_owner'], $msg);
							else if($video['videoalbumable_type'] == 'team')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&id_community=' . $video['id_owner'] . '&q=videoalbums&id_album=' . $video['id_videoalbum'], $msg);
							else if($video['videoalbumable_type'] == 'group')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&id_community=' . $video['id_owner'] . '&q=videoalbums&id_album=' . $video['id_videoalbum'], $msg);
							else if($video['videoalbumable_type'] == 'event')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&id_event=' . $video['id_owner'] . '&q=videoalbums&id_album=' . $video['id_videoalbum'], $msg);				
							else if($video['videoalbumable_type'] == 'playground')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_playground=' . $video['id_owner'] . '&q=videoalbums&id_album=' . $video['id_videoalbum'], $msg);
							else if($video['videoalbumable_type'] == 'fitness')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $video['id_owner'] . '&q=photoalbums&id_album=' . $video['id_videoalbum'], $msg);
							else if($video['videoalbumable_type'] == 'shop')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $video['id_owner'] . '&q=photoalbums&id_album=' . $video['id_videoalbum'], $msg);
					
							$message = array();
							$message['subject'] = $subject;
							$message['name'] = $user['firstname'] . ' ' . $user['firstname'];
							$message['copyright'] = core::getLanguage('str', 'copyright');
							$message['avatar'] = core::documentparser()->userAvatar($user);
							$message['video_thumb'] = core::documentparser()->getThumb($video['provider'], $video['video']);	
						
							$published = core::getLanguage('str', 'was_published');   						
							$published = str_replace('%DATE%', date("Y-m-d"), $published); 
							$published = str_replace('%TIME%', date("H:i"), $published);						
							$message['date'] = $published;						
							$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $user['id'];
							$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
							$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
							$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;	
							$message['msg'] = $msg;						

							core::documentparser()->userNotification($receiver['email'], $message);
						}							
					}	
				}
				else{
					if($settings['notification_answers_in_comments'] != 'no' && $user['id'] != $id_content){
						$mail = $data->getMailNotification(7);
						
						core::user()->setUser_id($id_content);
						$receiver = core::user()->getUserInfo();
	
						$subject = $mail['subject_ru'];
						$subject = str_replace('%USER_FIRSTNAME%', $user['firstname'], $subject);
						$subject = str_replace('%USER_LASTNAME%', $user['lastname'], $subject);
			
						$message = array();
						$message['subject'] = $subject;
						$message['name'] = $user['firstname'] . ' ' . $user['lastname'];
						$message['copyright'] = core::getLanguage('str', 'copyright');
						$message['avatar'] = core::documentparser()->userAvatar($user);
						
						$published = core::getLanguage('str', 'was_published');   						
						$published = str_replace('%DATE%', date("Y-m-d"), $published); 
						$published = str_replace('%TIME%', date("H:i"), $published);						
						$message['date'] = $published;						
						$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $user['id'];
						
						$msg = $mail['content_ru'];
						
						$pos = strpos(substr($msg, 800), " ");			

						if(strlen($msg) > 800) 
							$srttmpend = "...";
						else 
							$strtmpend = "";
			
						$msg = substr($msg, 0, 800 + $pos) . $srttmpend;						
						$msg = str_replace('%COMMENT%', htmlspecialchars(trim(Core_Array::getRequest('comment'))), $msg);
						
						if($row['commentable_type'] == 'user')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $row['id_content'], $msg);
						else if($row['commentable_type'] == 'team')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&id_community=' . $row['id_content'], $msg);
						else if($row['commentable_type'] == 'group')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&id_community=' . $row['id_content'], $msg);
						else if($row['commentable_type'] == 'event')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&id_event=' . $row['id_content'], $msg);
						else if($row['commentable_type'] == 'playground')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_playground=' . $row['id_content'], $msg);						
						else if($row['commentable_type'] == 'fitness')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $row['id_content'], $msg);
						else if($row['commentable_type'] == 'shop')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $row['id_content'], $msg);
						else if($row['commentable_type'] == 'photo'){
							$photo = Photoalbum::getPhotoInfo($row['id_content']);
							
							if($photo['photoalbumable_type'] == 'user') 
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=photoalbums&id_album=' . $photo['id_photoalbum'] . '&id_user=' . $photo['id_owner'], $msg);
							else if($photo['photoalbumable_type'] == 'team')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&id_community=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
							else if($photo['photoalbumable_type'] == 'group')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&id_community=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
							else if($photo['photoalbumable_type'] == 'event')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&id_event=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
							else if($photo['photoalbumable_type'] == 'playground')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_playground=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
							else if($photo['photoalbumable_type'] == 'fitness')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);
							else if($photo['photoalbumable_type'] == 'shop')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $photo['id_owner'] . '&q=photoalbums&id_album=' . $photo['id_photoalbum'], $msg);							
						}
						else if($row['commentable_type'] == 'video'){
							
							$video = Videoalbum::getVideoInfo($row['id_content']);
							
							if($video['videoalbumable_type'] == 'user')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=videoalbums&id_album=' . $video['videoalbum'] . '&id_user=' . $video['id_owner'], $msg);
							else if($video['videoalbumable_type'] == 'team')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&id_community=' . $video['id_owner'] . '&q=videoalbums&id_album=' . $video['id_videoalbum'], $msg);
							else if($video['videoalbumable_type'] == 'group')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&id_community=' . $video['id_owner'] . '&q=videoalbums&id_album=' . $video['id_videoalbum'], $msg);
							else if($video['videoalbumable_type'] == 'event')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&id_event=' . $video['id_owner'] . '&q=videoalbums&id_album=' . $video['id_videoalbum'], $msg);				
							else if($video['videoalbumable_type'] == 'playground')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_playground=' . $video['id_owner'] . '&q=videoalbums&id_album=' . $video['id_videoalbum'], $msg);
							else if($video['videoalbumable_type'] == 'fitness')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $video['id_owner'] . '&q=photoalbums&id_album=' . $video['id_videoalbum'], $msg);
							else if($video['videoalbumable_type'] == 'shop')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $video['id_owner'] . '&q=photoalbums&id_album=' . $video['id_videoalbum'], $msg);
						}						
						
						$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
						$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
						$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;	
							
						$message['msg'] = $msg;

						core::documentparser()->userNotification($receiver['email'], $message);
					}	
				}
			}	
			else
				$content = '{"status":0,"errors":'.core::getLanguage('error', 'web_apps_error').'}';
		}
		else{
			$content = '{"status":0,"errors":'.json_encode($errors).'}';
		}

		core::documentparser()->showJSONContent($content);
	
	break;	
	
	case liked:
	
		Auth::authorization();
	
		if($_REQUEST['id'] && $_REQUEST['likeable_type']){
			
			$result = $data->liked($_REQUEST['id'], $_REQUEST['likeable_type'], $user['id']);			
			$content = array("result" => "".$result."");
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case shared:	
	
		Auth::authorization();
	
		if($_REQUEST['id'] && $_REQUEST['shareable_type']){
			
			$result = $data->shared($_REQUEST['id'], $_REQUEST['shareable_type'], $user['id']);			
			$content = array("result" => "".$result."");
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
		
	break;
	
	case getcomments:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$commentable_type = $_REQUEST['commentable_type'] ? $_REQUEST['commentable_type'] : exit();
		$id = $_REQUEST['id'] ? $_REQUEST['id'] : exit();	
		
		if($commentable_type == 'user' && $_SESSION['user_authorization'] == "ok"){
			if($id != $user['id']){
				core::user()->setUser_id($id);
				$owner = core::user()->getUserInfo();
				$owner_settings = core::user()->getUserSetting();
	
				if(core::user()->permissionUser($user['id'], $owner_settings['permission_view_wall']) && $data->checkBlock($user['id'], $id)) $permit = 'yes';
			}
			else{
				if($settings['permission_view_wall'] != 2) $permit = 'yes';
			}
		}
		else if($commentable_type == 'photo'){
			
			$photoinfo = Photoalbum::getPhotoInfo($id);			
			
			core::user()->setUser_id($photoinfo['id_owner']);
			$owner = core::user()->getUserInfo();
			$owner_settings = core::user()->getUserSetting();
			
			if($photoinfo['id_owner'] != $user['id']){
				if(core::user()->permissionUser($user['id'], $owner_settings['permission_view_photo']) && $data->checkBlock($user['id'], $photoinfo['id_owner'])) $permit = 'yes';
			}
			else{
				if($settings['permission_view_photo'] != 2) $permit = 'yes';
			}			
		}
		else if($commentable_type == 'video'){
			$videoinfo = Videoalbum::getVideoInfo($id);
			
			core::user()->setUser_id($videoinfo['id_owner']);
			$owner = core::user()->getUserInfo();
			$owner_settings = core::user()->getUserSetting();
			
			if($videoinfo['id_owner'] != $user['id']){
				if(core::user()->permissionUser($user['id'], $owner_settings['permission_view_video']) && $data->checkBlock($user['id'], $videoinfo['id_owner'])) $permit = 'yes';
			}
			else{
				if($settings['permission_view_photo'] != 2) $permit = 'yes';
			}
		}
		else if($commentable_type == 'group'){
			if(Communities::getPermissionWall($communities_settings['permission_wall'], $id, $user['id'])) $permit = 'yes';			
		}
		else if($commentable_type == 'team'){
			if(Communities::getPermissionWall($communities_settings['permission_wall'], $id, $user['id'])) $permit = 'yes';	
		}
		else if($commentable_type == 'event'){
			if($data->checkBlock($user['id'], $id)) $permit = 'yes';
		}		
		
		$html = '';
		
		if($permit == 'yes'){

			$html = '';
			$arr_comments = Comments::treeComments(0, Comments::getCommentList($id, $commentable_type, $number, $offset));
	
			foreach($arr_comments as $row){	
				if($commentable_type == 'group' or $commentable_type == 'team'){
					if($author_community == 1 && Communities::checkOwnerCommunity($id_content, $user['id']) or Communities::checkAdminCommunity($id_content, $user['id'])) {
						$head = TRUE;
					}
				}
				else if($commentable_type == 'event'){
					if($author_community == 1 && Events::checkOwnerEvent($id_content, $user['id'], 'user')) {
						$head = TRUE;
					}
				}
				else $head = FALSE;			
				
				if($head){
					$avatar = Comments::getCommentAvatar($row['id_comment']);
					$name = Comments::getCommentAuthorName($row['id_comment']);
					
					switch($commentable_type){
						
						case team:
						
							$page_link = './?task=teams&id_user=' . $id_content;
						
						break;
						
						case group:
						
							$page_link = './?task=groups&id_user=' . $id_content;
							
						break;
						
						case event:
						
							$page_link = './?task=events&id_user=' . $id_content;
							
						break;
					}
				}else{
					$avatar = core::documentparser()->userAvatar($row);
					$name = $row['firstname'] . ' '  . $row['lastname'].'<span class="status_user' . (core::user()->checkUserOnline($row['id_user']) ? ' online' : '') . '" data-num="' . $row['id_user'] . '"></span>';
					$page_link = './?task=profile&id_user=' . $row['id_user'];
				}
				
				if($row['id_parent'] == 0){
					$html .= '<div id="message-' . $row['id_comment'] . '" data-item="' . $row['id_parent'] . '" class="message">';
					
					if ($commentable_type == 'event' && $head){
						$html .= '<div class="img-account">';
						$html .= '<img src="' . $avatar . '" alt="" class="event">';
						$html .= '</div>';
					}	
					else
						$html .= '<img src="' . $avatar . '" alt="" class="img-account">';
				
						if($row['id_content'] == $user['id']) {
						$html .= '<div class="del_mess" data-item="' . $row['id_comment'] . '"></div>';
					
						if($row['id_user'] == $user['id']){
							$html .= '<div class="del_mess" data-item="' . $row['id_parent'] . '"></div>';						
						}
					}						
				
					$html .= ' <h5 class="name"><a href="'.$page_link.'">' . $name . '</a></h5>';				
					$html .=  '<p class="data">' . $row['created'] . '</p>';
					$html .= '<p class="message-text">' . $row['content'] . '<br>';
					$html .= '<ul class="attach_image">';
				
					foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
						
						$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
						$html .= '<li>';
						$html .= '<img border="0" src="' . PATH_COMMENT_ATTACHMENTS . $photo['small_photo'] . '" class="photo_big" data-num="' . $photo['id_photo'] . '">';
						$html .= '</li>';
					}		
				
					$html .= '</ul>';
					$html .= '</p>';			
					$html .= '<a id="reply-' . $row['id_comment'] . '" class="reply" data-item="' . $row['id_comment'] . '"> ' . core::getLanguage('str', 'reply'). '</a>';
					if ($user['id'] != $row['id_user']) $html .=  '<a id="tell-comment-' . $row['id_comment'] . '" class="tell" data-item="' . $row['id_comment'] . '" data-type="comment">' . Comments::getNumberTell($row['id_comment'], 'comment') . '</a>';
					$html .=  '<a id="like-comment-' . $row['id_comment'] . '" class="liked" data-item="' . $row['id_comment'] . '" data-type="comment">' . Comments::getNumberLiked($row['id_comment'], 'comment') . '</a>';
					$html .= '</div>';
				}
				else{
					$html .= '<div class="message-reply message" id="message-' . $row['id_comment'] . '" data-item="' . $row['id_parent'] . '">';
					
					if($row['id_content'] == $user['id']) {
						$html .= '<div class="del_mess" data-item="' . $row['id_comment'] . '"></div>';
					
						if($row['id_user'] == $user['id']){
							$html .= '<div class="del_mess" data-item="' . $row['id_parent'] . '"></div>';						
						}
					}
				
					$html .= '<div class="message" >';					
					$html .= '<div class="message-account"> <img src="' . $avatar . '" alt="" class="img-account">';            	
					$html .= '<h5 class="name"><a href="./?task=profile&id_user=' . $row['id_user'] . '">' . $name . '</a></h5>';
					$html .= '<p class="data">' . $row['created'] . '</p>';
					$html .= '</div>';              
					$html .= '<p class="message-reply-text">' . $row['content'] . '<br>'; 
					$html .= '<ul class="attach_image">';
				
					foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
						$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
						$html .= '<li>';
						$html .= '<img border="0" src="' . PATH_COMMENT_ATTACHMENTS . $photo['small_photo'] . '" class="photo_big" data-num="' . $photo['id_photo'] . '">';
						$html .= '</li>';
					}
				
					$html .= '</ul>';				
					$html .= '</p>';			  
					$html .= '</div>';			  
					$html .= '</div>';  
					$html .= '</div>';		
				}
			}	
		}
		
		$content = array();
		$content['status'] = 1;				
		$content['html'] = $html;	
		$content = json_encode($content);
			
		core::documentparser()->showJSONContent($content);
	
	break;
	
	case get_communities_list:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$id_user = is_numeric($_REQUEST['id_user']) ? $_REQUEST['id_user'] : exit();
		$type = $_REQUEST['type'] ? $_REQUEST['type'] : exit();
	
		$html = '';
		$task = ($type == 'team') ? 'teams' : 'groups' ;
	
		foreach(Communities::getMyCommunitiesList($id_user, $type, $number, $offset) as $row){
			
			$html .= '<div id="community_' .$row['id_community'] . '" class="event-item">';
			$html .= '<a class="img" href="./?task=' . $task . '&id_community=' . $row['id_community'] . '">';
			$html .= '<img border="0" alt="" src="' . core::documentparser()->communityAvatar($row) . '">';
			$html .= '</a>';
			$html .= '<div class="teg">';
			$html .= '<p><a href="./?task=' . $task . '&id_community=' . $row['id_community'] . '"> ' . $row['name'] . '</a></p>';

			 $status = Communities::getCommunityRole(Communities::getUserStatus($row['id_community'], $user['id']));			
			
			$html .= '<p>' . $row['sport_type'] . '<br> ' . $status . '<br>' . $row['place'] . '</p>';
			$html .= '<p> ' .$row['about'] . '</p>';
			$html .= '<p><i></i>'. str_replace('%MEMBERS%', Communities::countMemberCommunity($row['id_community'], 2), core::getLanguage('str', 'participants_friends')) . '</p>';
			
			if(Communities::checkOwnerCommunity($row['id_community'], $user['id'])) {
				$html .= '<a href="./?task=' . $type . '&id_community=' .$row['id_community'] . '&q=edit">' . core::getLanguage('str', 'edit') . '</a>';
			}				
			
			$html .= '<div class="transparent"> </div>';
			$html .= '</div>';
			$html .= '</div>';			
		}

		$content = array();
		$content['status'] = 1;				
		$content['html'] = $html;	
		$content = json_encode($content);
			
		core::documentparser()->showJSONContent($content);	
	
	break;	
	
	case get_events_list:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$id_member = is_numeric($_REQUEST['id_member']) ? $_REQUEST['id_member'] : exit();
		$eventable_type = $_REQUEST['eventable_type'] ? $_REQUEST['eventable_type'] : exit();
		
		$html = '';
		
		foreach(Events::getMyEvents($id_member, $eventable_type, $number, $offset) as $row){
			
			$html .= '<div class="event-item"> <a href="./?task=events&id_event=' . $row['id_event'] . '" class="img"><img src="' . core::documentparser()->eventAvatar($row['cover_page']) . '" alt="" style="margin-left: -100%;"></a>';
			$html .= '<div class="teg">';
			$html .= '<p><a href="./?task=events&id_event=' . $row['id_event'] . '">' . $row['name'] . '</a></p>'; 
            $html .= ' <p>';  
			
			if($row['sport_type']) $html .= $row['sport_type'] . '<br>'; 
            if($row['place']) $html .= $row['place'] . '<br>';
			
            $html .= ' Начало: ' .core::documentparser()->mysql_russian_datetime($row['date_from']). ' в ' . $row['time_from'] . '<br>';   
               
			if($row['date_to'])	$html .= 'Окончание: ' .core::documentparser()->mysql_russian_datetime($row['date_to']). ' в ' . $row['time_to'] . '<br>'; 
                
			$html .= '</p>';	  
				
			if(Events::getEventRole(Events::getMemberShipStatus($row['id_event'], $user['id'], 'user'))) $html .= '<p>' . Events::getEventRole(Events::getMemberShipStatus($row['id_event'], $user['id'], 'user')) . '</p>';
			
			$html .= '<p><i></i>' . str_replace('%MEMBERS%', Events::countMembers($row['id_event'], 'user'), core::getLanguage('str', 'participants_friends')) . '</p>';
			
			if(Events::checkOwnerEvent($row['id_event'], $user['id'], 'user'))  
				$html .= '<a href="./?task=events&id_event='.$row['id_event'].'&q=edit">Редактировать</a>';
			
			if(Events::getEventStatus($row['id_event']) == 'continues') 
				$html .='<span>'.core::getLanguage('str', 'event_continues').'</span>';
			else if(Events::getEventStatus($row['id_event']) == 'end')	
				$html .='<span>'.core::getLanguage('str', 'event_completed').'</span>'; 
			
            $html .= '</div>';   
			$html .= '</div>';          
		}

		$content = array();
		$content['status'] = 1;				
		$content['html'] = $html;	
		$content = json_encode($content);
			
		core::documentparser()->showJSONContent($content);	
		
	break;

	case getpopphotos:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$postnumbers = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		
		$arr_photo = $data->getPopularPhotos($user['id'], $offset, $postnumbers);
		
		foreach($arr_photo as $row){
			
			$rows[] = array(
			"id" => $row['id'],
			"description" => $row['description'],
			"small_photo" => core::documentparser()->photogalleryPic($row['small_photo']),
			"photo" => core::documentparser()->photogalleryPic($row['photo'])			
			);
		}		
		
		$content = '{"item":'.json_encode($rows).'}';		
	
		core::documentparser()->showJSONContent($content);
	
	break;
	
	case addmessage:
	
		Auth::authorization();
	
		$id_receiver = Core_Array::getRequest('id_receiver');
		$content = htmlspecialchars(trim(Core_Array::getRequest('message')));
		$attach = Core_Array::getRequest('attach') ? explode(",", Core_Array::getRequest('attach')) : '';
		
		core::user()->setUser_id($id_receiver);
		$receiver = core::user()->getUserInfo();
		$receiver_settings = core::user()->getUserSetting();
		
		if(empty($content)) $error = core::getLanguage('error', 'empty_coomment');	
		if($data->checkBanMsgReceiver($id_receiver) === false or $data->checkDeletedMsgReceiver($id_receiver) === false)
		if(core::user()->permissionUser($id_receiver, $receiver_settings['permission_send_message']) && $data->checkBlock($user['id'], $id_receiver)) $error = core::getLanguage('error', 'message_hasnt_been_sent');
		
		if(empty($error)){
			$fields = array();
			$fields['id'] = 0;			
			$fields['id_sender'] = $user['id'];			
			$fields['id_receiver'] = $id_receiver;			
			$fields['content'] = $content;		
			$fields['created_at'] = date("Y-m-d H:i:s");			
			$fields['status'] = 0;			
			
			$insert_id = $data->addMessage($fields);
			
			if($insert_id){
				$row = $data->makeUpMessage($insert_id);				
				
				$content = array();
				$content['status'] = 1;
				$content['id'] = $insert_id;
				$content['id_receiver'] = $row['id_receiver'];				
				$content['id_sender'] = $row['id_sender'];
				$content['avatar'] = core::documentparser()->userAvatar($row);
				$content['firstname'] = $row['firstname'];
				$content['lastname'] = $row['lastname'];
				$content['created'] = $row['created'];				
				$image='';
				
				if ($attach > ''){
					$image = '<ul class="attach_image">';
					
					for( $i = 0, $length = count($attach); $i < $length; $i++){  
						$image .= '<li>';
						$message = Attach::uploadAttach($attach[$i], $insert_id,'message');
						
						if($message['small_photo'] && file_exists(PATH_COMMENT_ATTACHMENTS . $message['small_photo'])) $image .=  '<img border="0" src="'  . PATH_COMMENT_ATTACHMENTS . $message['small_photo']. '" class="photo_big"  data-num='.$message['id_photo'].'>';
						
						$image .= '</li>';
					}
					$image .= '</ul>';
				}	

				$content['image'] = $image;				
				$content['content'] = core::documentparser()->link_replace($row['content']);
				$content = json_encode($content);
				
				core::user()->setUser_id($id_receiver);
				$receiver = core::user()->getUserInfo();	
				$settings = core::user()->getUserSetting();
			
				if($settings['notification_private_messages'] != 'no' && !core::user()->checkUserOnline($id_receiver)){
					$mail = $data->getMailNotification(2);	
			
					$subject = $mail['subject_ru'];
					$subject = str_replace('%USER_FIRSTNAME%', $user['firstname'], $subject);
					$subject = str_replace('%USER_LASTNAME%', $user['lastname'], $subject);
			
					$msg = $mail['content_ru'];
					$msg = str_replace('%USER_FIRSTNAME%', $user['firstname'], $msg);
					$msg = str_replace('%USER_LASTNAME%', $user['lastname'], $msg);
					$msg = str_replace('%FIRSTNAME%', $receiver['firstname'], $msg);
					$msg = str_replace('%ID_USER%', $user['id'], $msg);
					$msg = str_replace('%MESSAGE%', htmlspecialchars(trim(Core_Array::getRequest('message'))), $msg);
					$msg = str_replace('%TIME%', date("Y-m-d H:i"), $msg);	
					
					$message = array();
					$message['subject'] = $subject;
					$message['name'] = $user['firstname'] . ' ' . $user['lastname'];
					$message['copyright'] = core::getLanguage('str', 'copyright');
					$message['avatar'] = core::documentparser()->userAvatar($user);
					$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $id_receiver . '&q=messages&sel=' . $user['id'], $msg);

					$message['msg'] = $msg;
					
					$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
					$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
					$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;	
				
					core::documentparser()->userNotification($receiver['email'], $message);				
				}				
			}	
			else
				$content = '{"status":0,"errors":' . core::getLanguage('error', 'web_apps_error') . '}';
		}
		else{
			$content = '{"status":0,"errors":' . $error . '}';
		}

		core::documentparser()->showJSONContent($content);		
	
	break;
	
	case getmessages:	
	
		Auth::authorization();

		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();		
		$id_receiver = is_numeric($_REQUEST['id_receiver']) ? $_REQUEST['id_receiver'] : exit();
		
		$arr_messages = $data->getMessagesListAjax($offset, $number, $user['id'], $id_receiver);		
		
		foreach($arr_messages as $row){
			
			$image = '<ul class="attach_image">';
				
			foreach(Attach::getAttachList($row['id'], 'message') as $row2){
				$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
						$image .= '<li>';
						$image .= '<img border="0" src="' . PATH_COMMENT_ATTACHMENTS . $photo['small_photo'] . '" class="photo_big" data-num="' . $photo['id_photo'] . '">';
						$image .= '</li>';
			}
			$image.='</ul>';
			$rows[] = array(
			"id_message" => $row['id'],
			"avatar" => core::documentparser()->userAvatar($row),
			"id_sender" => $row['id_sender'],
			"firstname" => $row['firstname'],			
			"lastname" => $row['lastname'],			
			"created" => $row['created'],
			"status" => $row['status'],	
			"image" => $image,	
			"content" => core::documentparser()->link_replace($row['content']));
		}
		
		$content = '{"item":'.json_encode($rows).'}';		
	
		core::documentparser()->showJSONContent($content);		
	
	break;
	
	case get_last_message:
	
		Auth::authorization();
	
		$id_receiver = is_numeric($_REQUEST['id_receiver']) ? $_REQUEST['id_receiver'] : exit();
		$last_message = $data->getLastMessage($id_receiver, $user['id']);
			
		foreach($last_message as $row){
			
			$image = '<ul class="attach_image">';
				
			foreach(Attach::getAttachList($row['id'], 'message') as $row2){
				$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
						$image .= '<li>';
						$image .= '<img border="0" src="' . PATH_COMMENT_ATTACHMENTS . $photo['small_photo'] . '" class="photo_big" data-num="' . $photo['id_photo'] . '">';
						$image .= '</li>';
			}
			$image.='</ul>';
			
			$rows[] = array(
			"id_message" => $row['id'],
			"avatar" => core::documentparser()->userAvatar($row),
			"id_sender" => $row['id_sender'],
			"firstname" => $row['firstname'],			
			"lastname" => $row['lastname'],	
			"image" => $image,	
			"created" => $row['created'],
			"status" => $row['status'],			
			"content" => core::documentparser()->link_replace($row['content']));
		}
			
		$content = '{"item":'.json_encode($rows).'}';		
		
		core::documentparser()->showJSONContent($content);	
		
	break;
	
	case add_as_friend:
	
		Auth::authorization();
	
		if($_REQUEST['id_user'] && $_REQUEST['id_user'] != $user['id']){
			$id_friend = core::database()->escape((int)Core_Array::getRequest('id_user'));			
			$result = $data->changeFriendsStatus($id_friend, $user['id'], 0);

			core::user()->setUser_id($id_friend);
			$friend = core::user()->getUserInfo();
	
			$settings = core::user()->getUserSetting();
			
			if($settings['notification_friends_request'] != 'no' ){
				$row = $data->getMailNotification(1);	
			
				$subject = $row['subject_ru'];
				$subject = str_replace('%USER_FIRSTNAME%', $user['firstname'], $subject);
				$subject = str_replace('%USER_LASTNAME%', $user['lastname'], $subject);
				
				$message = array();
				$message['subject'] = $subject;
				$message['name'] = $user['firstname'] . ' ' . $user['lastname'];
				$message['copyright'] = core::getLanguage('str', 'copyright');							
				$message['avatar'] = core::documentparser()->userAvatar($user);				
			
				$published = core::getLanguage('str', 'was_published');   						
				$published = str_replace('%DATE%', date("Y-m-d"), $published); 
				$published = str_replace('%TIME%', date("H:i"), $published);						
				$message['date'] = $published;
				$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $user['id'];
			
				$msg = $row['content_ru'];
				$msg = str_replace('%USER_FIRSTNAME%', $user['firstname'], $msg);
				$msg = str_replace('%USER_LASTNAME%', $user['lastname'], $msg);
				$msg = str_replace('%FIRSTNAME%', $friend['firstname'], $msg);
				$msg = str_replace('%PAGE%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=friends', $msg);

				$message['msg'] = $msg;
					
				$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
				$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
				$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;					
				
				core::documentparser()->userNotification($friend['email'], $message);				
			}
			
			$content = array("status" => $result);	

			core::documentparser()->showJSONContent(json_encode($content));			
		}	
		
	break;
	
	case accept_friendship:
	
		Auth::authorization();
	
		if($_REQUEST['id_user'] && $_REQUEST['id_user'] != $user['id']){
			$id_friend = core::database()->escape((int)Core_Array::getRequest('id_user'));

			core::user()->setUser_id($id_friend);
			$friend = core::user()->getUserInfo();	
			$settings = core::user()->getUserSetting();				
			
			$result = $data->changeFriendsStatus($user['id'], $id_friend, 1);
			
			if($result){
				if($settings['notification_friends_request'] != 'no' && !core::user()->checkUserOnline($id_friend)){
					$row = $data->getMailNotification(12);

					$subject = $row['subject_ru'];
					$subject = str_replace('%USER_FIRSTNAME%', $user['firstname'], $subject);
					$subject = str_replace('%USER_LASTNAME%', $user['lastname'], $subject);
					
					$message = array();
					$message['subject'] = $subject;
					$message['name'] = $user['firstname'] . ' ' . $user['lastname'];
					$message['copyright'] = core::getLanguage('str', 'copyright');							
					$message['avatar'] = core::documentparser()->userAvatar($user);

					$published = core::getLanguage('str', 'was_published');   						
					$published = str_replace('%DATE%', date("Y-m-d"), $published); 
					$published = str_replace('%TIME%', date("H:i"), $published);						
					$message['date'] = $published;
					$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $user['id'];
			
					$msg = $row['content_ru'];
					$msg = str_replace('%USER_FIRSTNAME%', $user['firstname'], $msg);
					$msg = str_replace('%USER_LASTNAME%', $user['lastname'], $msg);
					$msg = str_replace('%FIRSTNAME%', $friend['firstname'], $msg);
					$msg = str_replace('%PAGE%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=friends', $msg);

					$message['msg'] = $msg;
					
					$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
					$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
					$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;					
				
					core::documentparser()->userNotification($friend['email'], $message);					
				}
			}
			
			$content = array("status" => $result);
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;	
	
	case block_user:
	
		Auth::authorization();

		if($_REQUEST['id_user'] && $_REQUEST['id_user'] != $user['id']){
			$id_user = core::database()->escape((int)Core_Array::getRequest('id_user'));
			
			$result = $data->blockUser($user['id'], $id_user);

			$content = array("status" => 'success');		
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case unblock_user:
	
		Auth::authorization();

		if($_REQUEST['id_user'] && $_REQUEST['id_user'] != $user['id']){
			$id_user = core::database()->escape((int)Core_Array::getRequest('id_user'));
			
			$result = $data->unblockUser($user['id'], $id_user);		

			$content = array("status" => 'success');		
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case remove_friend:
	
		Auth::authorization();
	
		if($_REQUEST['id_user']){
			$id_friend = core::database()->escape((int)Core_Array::getRequest('id_user'));
			
			$result = $data->removeFriend($user['id'], $id_friend);
			
			$content = array("status" => 'success');
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case search_city:
	
		$city = trim(Core_Array::getRequest('city'));
		
		foreach( Places::searchCity($city) as $row){			
			$rows[] = array(
			"id" => $row['id'],
			'name' => $row['name']);		
		}
				
		$content = '{"item":'.json_encode($rows).'}';
			
		core::documentparser()->showJSONContent($content);
	
	break;
	
	case search_sport_types:
	
		$sport_types = trim(Core_Array::getRequest('sport_types'));
	
		foreach( Sport::searchSportTypes($sport_types) as $row){			
			$rows[] = array(
			"id" => $row['id'],
			'name' => $row['name']);		
		}
				
		$content = '{"item":'.json_encode($rows).'}';
			
		core::documentparser()->showJSONContent($content);
	
	break;	
	
	case getpossiblefriends:
	
		Auth::authorization();
	
		foreach(Friends::getPossibleFriendsList($user['id'], 6) as $row){
			$rows[] = array(
			"id_user" => $row['id_user'],			
			"avatar" => core::documentparser()->userAvatar($row),
			"firstname" => $row['firstname'],			
			"lastname" => $row['lastname'],
			"city" => $row['city'],
			"status_user" => core::user()->checkUserOnline($row['id_user']) ? 'online' : 'offline',
			"id_sender" => $user['id']);
		}		
		
		$content = '{"item":'.json_encode($rows).'}';		
	
		core::documentparser()->showJSONContent($content);
	
	break;
	
	case getphotoinfo:
	
		$id_photo = is_numeric(Core_Array::getRequest('id_photo')) ? Core_Array::getRequest('id_photo') : exit();
		$row = Photoalbum::getPhotoInfo($id_photo);		
		$content = array();
		
		if($row){
			$content['status'] = 1;
			$content['id_photoalbum'] = $row['id_photoalbum'];		
			$content['small_photo'] = core::documentparser()->photogalleryPic($row['small_photo'], $row['photoalbumable_type']);
			$content['photo'] = core::documentparser()->photogalleryPic($row['photo'], $row['photoalbumable_type']);		
			$content['description'] = $row['description'];		
			$content['id_owner'] = $row['id_owner'];
			$content['firstname'] = $row['firstname'];
			$content['lastname'] = $row['lastname'];
			$content['liked'] = Photoalbum::getNumberLiked($id_photo);		
			$content['tell'] = Photoalbum::getNumberTell($id_photo);		
			$content['created'] = core::documentparser()->mysql_russian_date($row['created']);
		}
		else $content['status'] = 0;
		
		core::documentparser()->showJSONContent(json_encode($content));
		
	break;
	
	case getvideoinfo:
	
		$id_video = is_numeric(Core_Array::getRequest('id_video')) ? Core_Array::getRequest('id_video') : exit();
		$row = Videoalbum::getVideoInfo($id_video);
		$content = array();
		
		if($row){
			$content['status'] = 1;
			$content['id_video'] = $row['id_video'];		
			$content['description'] = $row['description'];
			$content['id_owner'] = $row['id_owner'];
			$content['firstname'] = $row['firstname'];
			$content['lastname'] = $row['lastname'];
			$content['liked'] = Videoalbum::getNumberLiked($id_video);		
			$content['tell'] = Videoalbum::getNumberTell($id_video);
			$content['views'] = Videoalbum::getNumberVideoViews($id_video);			
			$content['thumb'] = Videoalbum::getThumb($id_video);			
			$content['video'] = core::documentparser()->getVideoPlayer($row['provider'], $row['video']);		
			$content['created'] = core::documentparser()->mysql_russian_date($row['created']);
			
			Videoalbum::countView(Core_Array::getRequest('id_video'), $user['id']);
		}
		else $content['status'] = 0;
		
		core::documentparser()->showJSONContent(json_encode($content));		
	
	break;
	
	case edit_profile:
	
		Auth::authorization();
	
		$firstname = htmlspecialchars(trim(Core_Array::getRequest('firstname')));
		$lastname  = htmlspecialchars(trim(Core_Array::getRequest('lastname')));	
		$secondname = htmlspecialchars(trim(Core_Array::getRequest('secondname')));	
		$birthday = core::documentparser()->convertToDbFormat(trim($_POST['birthday']));
		$sex = Core_Array::getRequest('sex');
		$about_sport = htmlspecialchars(trim(Core_Array::getRequest('about_sport')));
		$about = htmlspecialchars(trim(Core_Array::getRequest('about')));		
		$id_place = Core_Array::getRequest('id_place');
		$file_ava = Core_Array::getRequest('file_ava');
		$cover_page = Core_Array::getRequest('file_cover');				

		$fields = array();
	
		if($firstname) $fields['user']['firstname'] = $firstname;
		if($lastname) $fields['user']['lastname']  = $lastname;
		if($secondname) $fields['user']['secondname'] = $secondname;
		if($birthday) $fields['user']['birthday']  = $birthday;
		if($sex) $fields['user']['sex']       = $sex;
		if($about_sport) $fields['user']['about_sport'] = $about_sport;		
		if($about) $fields['user']['about'] = $about;
		
		$fields['user']['updated_at'] = date("Y-m-d H:i:s");
		
		if(!empty($file_ava)) $fields['avatar'] = basename($file_ava);
		if(!empty($cover_page)) $fields['cover_page'] = basename($cover_page);			
		
		if($id_place){
			$country = Places::getCountryByCity($id_place);
			$region = Places::getRegionByCity($id_place);
			$city = Places::getCityInfo($id_place);
		
			if($country['name_ru']) $fields['user']['country'] = $country['name_ru'];
			if($region['name_ru']) $fields['user']['region'] = $region['name_ru']; 
			if($city['name_ru']) $fields['user']['city'] = $city['name_ru'];
			$fields['id_place'] = $id_place;			
		} 	
	
		if($_REQUEST['id_job_block']) $fields['job']['id_block'] = Core_Array::getRequest('id_job_block');
		if($_REQUEST['job_kind']) $fields['job']['kind'] = Core_Array::getRequest('job_kind');
		if($_REQUEST['job_name']) $fields['job']['name'] = Core_Array::getRequest('job_name');
		if($_REQUEST['job_description']) $fields['job']['description'] = Core_Array::getRequest('job_description');
		if($_REQUEST['job_month_start']) $fields['job']['month_start'] = Core_Array::getRequest('job_month_start');
		if($_REQUEST['job_year_start']) $fields['job']['year_start'] = Core_Array::getRequest('job_year_start');
		if($_REQUEST['job_month_finish']) $fields['job']['month_finish'] = Core_Array::getRequest('job_month_finish');
		if($_REQUEST['job_year_finish']) $fields['job']['year_finish'] = Core_Array::getRequest('job_year_finish');
		if($_REQUEST['id_job_place']) $fields['job']['id_place']= Core_Array::getRequest('id_job_place');		
		if($_REQUEST['sport_type']) $fields['sport']['sport_type'] = Core_Array::getRequest('sport_type');
		if($_REQUEST['spoort_level']) $fields['sport']['id_sport_level'] = Core_Array::getRequest('spoort_level');		
		if($_REQUEST['search_team']) $fields['sport']['search_team'] = Core_Array::getRequest('search_team');	
		if($_REQUEST['id_sport_type']) $fields['sport']['id_sport_type'] = Core_Array::getRequest('id_sport_type');		
		if($_REQUEST['id_education_block']) $fields['education']['id_block'] = Core_Array::getRequest('id_education_block');	
		if($_REQUEST['education_kind']) $fields['education']['kind'] = Core_Array::getRequest('education_kind');
		if($_REQUEST['id_education_place']) $fields['education']['id_place'] = Core_Array::getRequest('id_education_place');		
		if($_REQUEST['education_name']) $fields['education']['name'] = Core_Array::getRequest('education_name');
		if($_REQUEST['education_description']) $fields['education']['description'] = Core_Array::getRequest('education_description');
		if($_REQUEST['education_month_start']) $fields['education']['month_start'] = Core_Array::getRequest('education_month_start');
		if($_REQUEST['education_year_start']) $fields['education']['year_start'] = Core_Array::getRequest('education_year_start');
		if($_REQUEST['education_month_finish']) $fields['education']['month_finish'] = Core_Array::getRequest('education_month_finish');
		if($_REQUEST['education_year_finish']) $fields['education']['year_finish'] = Core_Array::getRequest('education_year_finish');		
	
		if($fields){
			$result = $data->editUserProfile($fields, $user['id']);
		
			if($result){
				$content = array("result" => 'success');
			}
			else{
				$content = array("result" => 'error');
			}		
			
			core::documentparser()->showJSONContent(json_encode($content));
		}					
	
	break;
	
	case adduseravatar:
	
		Auth::authorization();
	
		if(!empty($_FILES['avatar']['name'])){
			if(core::documentparser()->checkImageSize($_FILES['avatar']['tmp_name'], 200)){
				if($data->addAvatar(200, $user['id'])) 
					$content = array("result" => 'success', "error" => '');
				else
					$content = array("result" => 'error', "error" => core::getLanguage('error', 'error_loading_photos'));
			}
			else{
				$content = array("result" => 'error', "error" => str_replace('%SIZE%', 200, core::getLanguage('error', 'avatar_size')));
			}

			core::documentparser()->showJSONContent(json_encode($content));			
		}
	
	break;	
	
	case crop:
	
		Auth::authorization();
	
		$src = $_SERVER['DOCUMENT_ROOT'] . '/' . $_POST['file'];	
		
		$img = new abeautifulsite\SimpleImage();
		$img->load($src)->crop($_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'])->auto_orient()->save($src);

		$content = array("result" => 'success', "path" => $src);
		core::documentparser()->showJSONContent(json_encode($content));	
	
	break;
	
	case cropcover:
	
		Auth::authorization();
	
		$src = $_SERVER['DOCUMENT_ROOT'] . $_POST['file'];		
		$img = new abeautifulsite\SimpleImage();	 
		$img->load($src)->crop($_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'])->auto_orient()->save($src);
		$content = array("result" => 'success', "path" => $src);
		
		core::documentparser()->showJSONContent(json_encode($content));	
	
	break;	

	case add_photo_ajax:
	
		Auth::authorization();
		
		$id_photoalbum = $_REQUEST['categorie'];
		$description = trim($_REQUEST['description']);
		$photoalbumable_type = $_REQUEST['photoalbumable_type'];		
		$path = core::documentparser()->getPhotogalleryPath($photoalbumable_type);			
		$message = Photoalbum::uploadHandlePup(32, $path, 0, $id_photoalbum, $description, $user['id']);
		
		core::documentparser()->showJSONContent(json_encode($message));	
		
	break;	
	
	case add_photo_ajax_attach:
	
		Auth::authorization();
	
		$description = trim($_REQUEST['description']);
		$photoalbumable_type = 'user_attach';
		$num = $_REQUEST['num'];
		
		if(Photoalbum::getNumberAlbums($user['id'], $photoalbumable_type) == 0){
			$fields = array();
			$fields['id'] = 0;
			$fields['name'] = core::getLanguage('str', 'my_album_attach');
			$fields['created_at'] = date("Y-m-d H:i:s");
			$fields['photoalbumable_type'] = 'user_attach';
			$fields['id_owner'] = $user['id'];		
				
			Photoalbum::createAlbum($fields);	
		}
		
		$arr_option_list = Photoalbum::getAlbumList($user['id'], $photoalbumable_type);
		$path = core::documentparser()->getPhotogalleryPath($photoalbumable_type);			
		$message = Photoalbum::uploadHandlePup(32, $path, 0,  $arr_option_list[0]['id'], $description, $user['id']);		
		$content = array("num" => $num, "message" => $message);
		
		core::documentparser()->showJSONContent(json_encode($content));
		
		exit;
		
	break;


	case uploadcover:
	
		Auth::authorization();
	
		if (isset ($_FILES['cover'])){
			$dir = $_SERVER['DOCUMENT_ROOT'] . '/tmp/'; 
		
			$upfile = $_FILES['cover']['tmp_name'];
			$ext = strrchr($_FILES['cover']['name'], "."); 
	
			$upfile_name = md5(date("YmdHis", time())).$ext;
			$upfile_size = $_FILES['cover']['size'];
			$upfile_type = $_FILES['cover']['type'];
			$error_code = $_FILES['cover']['error'];
		
			if ($error_code == 0){
				$img = new abeautifulsite\SimpleImage();
				$img->load($upfile)->auto_orient()->save($dir.$upfile_name);
			}   
		}
		
		list($w_i, $h_i, $type) = getimagesize($_SERVER['DOCUMENT_ROOT'].'/tmp/'.$upfile_name);
	
		if (($w_i > 600) || ($h_i > 600)){
		
			$widthcrop = (floor($w_i/6) )* 6;
			$ratio = floor($widthcrop / 6);
			$heightcrop = floor($h_i/$ratio) * $ratio;
		
			core::documentparser()->crop($_SERVER['DOCUMENT_ROOT'].'/tmp/'.$upfile_name, 0, 0, $widthcrop, $heightcrop);
			core::documentparser()->resize($_SERVER['DOCUMENT_ROOT'].'/tmp/'.$upfile_name, 600);
		}
	
		echo '/tmp/'. $upfile_name;
		exit;	 

	break;
	
	case uploadavatar:
	
		Auth::authorization();
	
		if (isset ($_FILES['avatar'])){
			$dir = $_SERVER['DOCUMENT_ROOT'] . '/tmp/'; 
		
			$upfile = $_FILES['avatar']['tmp_name'];
			$ext = strrchr($_FILES['avatar']['name'], "."); 		
		
			$upfile = $_FILES['avatar']['tmp_name'];
			$upfile_name = md5(date("YmdHis", time())).$ext;
			$upfile_size = $_FILES['avatar']['size'];
			$upfile_type = $_FILES['avatar']['type'];
			$error_code = $_FILES['avatar']['error'];
		
			if ($error_code == 0){
				$img = new abeautifulsite\SimpleImage();
				$img->load($upfile)->auto_orient()->save($dir.$upfile_name);
			}   
		}
		
		list($w_i, $h_i, $type) = getimagesize($_SERVER['DOCUMENT_ROOT'].'/tmp/' . $upfile_name);
	
		if (($w_i > 600) || ($h_i > 600)){
		
			$widthcrop = (floor($w_i/6) )* 6;
			$ratio = floor($widthcrop / 6);
			$heightcrop = floor($h_i/$ratio) * $ratio;
		
			core::documentparser()->crop($_SERVER['DOCUMENT_ROOT'].'/tmp/' . $upfile_name, 0, 0, $widthcrop, $heightcrop);
			core::documentparser()->resize($_SERVER['DOCUMENT_ROOT'].'/tmp/' . $upfile_name, 600);
		}
	
		echo '/tmp/' . $upfile_name;
		exit;
	
	break;	
	
	case removecomment:
	
		Auth::authorization();
		
		$id = is_numeric($_REQUEST['id_comment']) ? $_REQUEST['id_comment'] : exit();
		
		if(Comments::removeComment($id) && $data->removeShare($id, 'comment'))
			$content = array("result" => 'success');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;	
	
	case cleardialog:
	
		Auth::authorization();
	
		$id = is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : exit();
		
		$result = $data->clearDialog($user['id'], $id);
		
		if($result)
			$content = array("result" => 'success');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
		
	break;	
	
	case change_event_memberstatus:
	
		Auth::authorization();
	
		$id_event = is_numeric($_REQUEST['id_event']) ? $_REQUEST['id_event'] : exit();
		$status = is_numeric($_REQUEST['status']) ? $_REQUEST['status'] : exit();
	
		$result = Events::changeMemberStatus($id_event, $user['id'], 'user', $status);		
	
		if($result)
			$content = array("result" => 'success');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));

	break;
	
	case changememberstatus:
	
		Auth::authorization();
	
		$id = is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : exit();
		$status = is_numeric($_REQUEST['status']) ? $_REQUEST['status'] : exit();
		
		if(Communities::getMemberShipStatus($id, $user['id']) != 0 or Communities::getMemberShipStatus($id, $user['id']) != 3 or Communities::getMemberShipStatus($id, $user['id']) != 4){
			if(Communities::changememberstatus($id, $user['id'], $status))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		}
		else $content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
	
	case get_photos_list:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$id_owner = is_numeric($_REQUEST['id_owner']) ? $_REQUEST['id_owner'] : exit();
		$type = $_REQUEST['type'] ? $_REQUEST['type'] : exit();	
	
		$arr_photos = Photoalbum::getPhotosList($id_owner, $type, $number, $offset);		
		
		if($arr_photos){
			
			$html = ''; 		
			
			foreach($arr_photos as $row){
				$small_photo = core::documentparser()->photogalleryPic($row['small_photo'], $type);
				$big_image = core::documentparser()->photogalleryPic($row['photo'], $type);
				
				if($small_photo){
					$html .= '<div class="hov" id="photo-block-' . $row['id_photo'] . '">'; 
					$html .= '<a class="photo_big" title="' . $row['description'] . '" href="' . $big_image . '" data-lightbox="roadtrip" data-num=' . $row['id_photo'] . '> <img src="' . $small_photo . '" alt="">';
					$html .= '<div class="transparent"></div>';
					$html .= '</a>';

					if($row['id_owner'] == $user['id']) {
						$html .= '<span class="icons-hid"><i id="my-video-' . $row['id_photo'] . '" class="remove_pic" id="' . $row['id_photo'] . '" data-item="' . $row['id_photo'] . '"><img src="templates/images/icon-krest.png" alt=""></i></span>';
					}
					
					$html .= '</div>';
				}
			}

			$content = array();
			$content['status'] = 1;				
			$content['html'] = $html;	
			$content = json_encode($content);
			
			core::documentparser()->showJSONContent($content);			
		}
		
	break;

	case get_videos_list:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$id_owner = is_numeric($_REQUEST['id_owner']) ? $_REQUEST['id_owner'] : exit();
		$type = $_REQUEST['type'] ? $_REQUEST['type'] : exit();
		
		$arr_videos = Videoalbum::getVideosList($id_owner, $type, $number, $offset);
		
		if($arr_videos){
			
			$html = '';
			
			foreach($arr_videos as $row){
				$thumb = core::documentparser()->getThumb($row['provider'], $row['video']);
				
				$html .= '<div id="video-block-' . $row['id_video'] . '" class="hov">';
				$html .= '<div class="video-box"><img src="' . $thumb . '" alt="" class="video_prev" data-num="' . $row['id_video'] . '">';
				$html .= '</div>'; 
				$html .= '<span class="icons-hid"><i id="my-video-' . $row['id_video'] . '" class="remove_video" data-item="' . $row['id_video'] . '"> <img  src="templates/images/icon-krest.png" alt=""></i></span> ';
				$html .= '<span class="video-capt"><i></i>' . Videoalbum::getNumberVideoViews($row['id_video']) . '</span>';
				$html .= '</div>';
			}			
			
			$content = array();
			$content['status'] = 1;				
			$content['html'] = $html;	
			$content = json_encode($content);
			
			core::documentparser()->showJSONContent($content);
		}	
	
	break;
	
	case get_album_photos:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$id_album = is_numeric($_REQUEST['id_album']) ? $_REQUEST['id_album'] : exit();
		
		$info = Photoalbum::getPhotoAlbumInfo($id_album);
		
		$arr_photos = Photoalbum::getPicList($id_album, $number, $offset);
		
		if($arr_photos){
			$html = '';
			
			foreach($arr_photos as $row){
				$small_photo = core::documentparser()->photogalleryPic($row['small_photo'], $info['photoalbumable_type']);
				$big_image = core::documentparser()->photogalleryPic($row['photo'], $info['photoalbumable_type']);
				
				if($small_photo){
					$html .= '<div class="hov" id="photo-block-' . $row['id'] . '"> <a class="photo_big" title="' . $row['description'] . '" href="' . $big_image . '" data-lightbox="roadtrip" data-num="' . $row['id'] . '"> <img src="' . $small_photo . '" alt="">';
					$html .= '<div class="transparent"></div>';
					$html .= '</a>';	

					if($row['id_owner'] == $user['id']) {
						$html .= '<span class="icons-hid"><i id="my-video-' . $row['id'] . '" class="remove_pic" id="' . $row['id'] . '" data-item="' . $row['id'] . '"><img src="templates/images/icon-krest.png" alt=""></i></span> ';
					}
					
					$html .='</div>';
				}
			}
			
			$content = array();
			$content['status'] = 1;				
			$content['html'] = $html;	
			$content = json_encode($content);
			
			core::documentparser()->showJSONContent($content);
		}		
	
	break;	
	
	case get_album_videos:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$id_album = is_numeric($_REQUEST['id_album']) ? $_REQUEST['id_album'] : exit();
		
		$arr_videos = Videoalbum::getVideosAlbumList($id_album, $number, $offset);
		
		if($arr_videos){
			
			$html = '';
		
			foreach($arr_videos as $row){
				$html .= '<div id="video-block-' . $row['id_video'] . '" class="hov">';
				$html .= '<div class="video-box"><img src="' . core::documentparser()->getThumb($row['provider'], $row['video']) . '" alt="" class="video_prev" data-num="' . $row['id_video'] . '">';
				$html .= '</div>';
				$html .= '<span class="icons-hid"><i id="my-video-' . $row['id_video'] . '" class="remove_video" data-item="' . $row['id_video'] . '" data-tooltip="Удалить видео"> <img  src="templates/images/icon-krest.png" alt=""></i></span> ';
				$html .= '<span class="video-capt"><i></i>' . Videoalbum::getNumberVideoViews($row['id_video']) . '</span>';
				$html .= '</div>';
			}
			
			$content = array();
			$content['status'] = 1;				
			$content['html'] = $html;	
			$content = json_encode($content);
			
			core::documentparser()->showJSONContent($content);
		}

	break;
	
	case send_community_invitation:
	
		Auth::authorization();
	
		$id_community = is_numeric($_REQUEST['id_community']) ? $_REQUEST['id_community'] : exit();		
		$community = Communities::getCommunityInfo($id_community);
		
		if($community['type'] == 'group') {
			$mailnotification = $data->getMailNotification(9);
		}
		else if($community['type'] == 'team'){
			$mailnotification = $data->getMailNotification(10);
		}		
		
		foreach($data->sendCommunityInvitation($id_community, $user['id']) as $row){
		
			if(Communities::change_community_role($id_community, $row['id_user'], 5)){
				$message = array();
			
				$subject = str_replace('%USER_FIRSTNAME%', $user['firstname'], $mailnotification['subject_ru']);
				$subject = str_replace('%USER_LASTNAME%', $user['lastname'], $subject);	
				$subject = str_replace('%NAME%', $community['name'], $subject);
			
				$message['subject'] = $subject;
				$message['name'] = $user['lastname'] . ' ' . $user['firstname'];
				$message['copyright'] = core::getLanguage('str', 'copyright');
				$message['avatar'] = core::documentparser()->userAvatar($user);
				
				$published = core::getLanguage('str', 'was_published');   						
				$published = str_replace('%DATE%', date("Y-m-d"), $published); 
				$published = str_replace('%TIME%', date("H:i"), $published);						
				$message['date'] = $published;
						
				$msg = $mailnotification['content_ru'];			
				$msg = str_replace('%PAGE%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $user['id'], $msg);			
				$msg = str_replace('%NAME%', $community['name'], $msg);				
			
				if($community['type'] == 'team')  
					$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&id_community=' . $id_community, $msg);
				else if($community['type'] == 'group')
					$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&id_community=' . $id_community, $msg);			
				
				//$msg = str_replace('%AVATAR%',  core::documentparser()->eventAvatar('http://' . $_SERVER['SERVER_NAME'] . '/' . core::documentparser()->communityAvatar($community))->resize(100, 100)->output_base64(), $msg);
				
				$msg = str_replace('%AVATAR%', 'cid:photo', $msg);
				
				$message['photo'] = core::documentparser()->communityAvatar($community);	
				
				$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
				$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
				$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;	
				$message['msg'] = $msg;						

				core::documentparser()->userNotification($row['email'], $message);
			}	
		}		
		
		$content = array("result" => 'success');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
	
	case send_event_invitation:
	
		Auth::authorization();
	
		$id_event = is_numeric($_REQUEST['id_event']) ? $_REQUEST['id_event'] : exit();
	
		$event = Events::getEventInfo($id_event);
		
		$mailnotification = $data->getMailNotification(11);
		
		foreach($data->sendEventInvitation($id_event, 'user', $user['id']) AS $row){
			$result = Events::change_member_role($id_event, $row['id_user'], 'user', 4);
			
			if($result){
				$message = array();
				
				$event = Events::getEventInfo($id_event);
				
				$subject = str_replace('%USER_FIRSTNAME%', $user['firstname'], $mailnotification['subject_ru']);
				$subject = str_replace('%USER_LASTNAME%', $user['lastname'], $subject);
				$subject = str_replace('%NAME%', $event['name'], $subject);				
				$message['subject'] = $subject;
				$message['name'] = $user['lastname'] . ' ' . $user['firstname'];
				$message['copyright'] = core::getLanguage('str', 'copyright');
				$message['avatar'] = core::documentparser()->userAvatar($user);
				
				$published = core::getLanguage('str', 'was_published');   						
				$published = str_replace('%DATE%', date("Y-m-d"), $published); 
				$published = str_replace('%TIME%', date("H:i"), $published);						
				$message['date'] = $published;			
				
				$msg = $mailnotification['content_ru'];			
				$msg = str_replace('%PAGE%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $user['id'], $msg);			
				$msg = str_replace('%NAME%', $event['name'], $msg);				
				$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&id_event=' . $id_event, $msg);
				$msg = str_replace('%AVATAR%', 'cid:photo', $msg);				
		
				$message['photo'] = core::documentparser()->eventAvatar($event['cover_page']);	
			
				$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
				$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
				$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;	
				$message['msg'] = $msg;		
				
				core::documentparser()->userNotification($row['email'], $message);				
			}
		}			
		
		$content = array("result" => 'success');
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;	
	
	case check_user_online:
	
		$id_user = is_numeric($_REQUEST['id_user']) ? $_REQUEST['id_user'] : exit();
		
		if(core::user()->checkUserOnline($id_user))
			$content = array("status" => 'online');
		
		else
			$content = array("status" => 'offline');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
	
	case news_parse:
	
		$url = "http://www.sport-express.ru/services/materials/news/se/";
		$xml = simplexml_load_file($url);

		$news = array();

		for($i = 0; $i < count($xml->channel->item); $i++){
			$title = $xml->channel->item[$i]->title;
			$link = $xml->channel->item[$i]->link;
			$description = $xml->channel->item[$i]->description;
			$pubDate = $xml->channel->item[$i]->pubDate;
	
			if($title && $link && $description && $pubDate){
				$news['title'][] = $title;
				$news['link'][] = $link;		
				$news['description'][] = $description;
				$news['pubdate'][] = $pubDate;
			}
		}
		
		$result = Rss::addNews($news);
		
		if($result)
			$content = array("result" => 'success');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
		
	break;
	
	case get_usernews_list:
	
		Auth::authorization();
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		
		$arrs = array();
		foreach(Comments::getCommentEvent($user['id'], $number, $offset) as $row){
			$id_event = $row['id_event'];
			$id_author = $row['id_author'];
			
			if (Events::checkOwnerEvent($id_event, $id_author, 'user'))	{
				$publication_name = $row['name'];
				$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_left_comment'));  
				$publication_msg .= '<ul class="attach_image">';
				
				foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
					$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
					$publication_msg .= '<li><img border="0" src='.PATH_COMMENT_ATTACHMENTS . $photo['small_photo'].' class="photo_big" data-num="'.$photo['id_photo'].'" /></li>';
				}
				
				$publication_msg .= '</ul>'	;
				$arrs[] = array('name' => $publication_name,'type' => 'event','id_author' => $id_event, 'msg' => $publication_msg, 'avatar' => core::documentparser()->eventAvatar($row['cover_page']), 'id_content' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
			}
		}
		
		foreach(Comments::getCommentCommunity($user['id'], $number, $offset) as $row){
			$id_community = $row['id_community'];
			$id_author = $row['id_author'];
			
			if(Communities::checkOwnerCommunity($id_community, $id_author) or Communities::checkAdminCommunity($id_community, $id_author)){
				$publication_name = $row['name'];
				$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_left_comment'));  
				$publication_msg .= '<ul class="attach_image">';
				
				foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
					$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
					$publication_msg .= '<li><img border="0" src='.PATH_COMMENT_ATTACHMENTS . $photo['small_photo'].' class="photo_big" data-num="'.$photo['id_photo'].'" /></li>';
				}
				
				$publication_msg .= '</ul>'	;
				$arrs[] = array('name' => $publication_name,'type' => $row['commentable_type'], 'id_author' => $id_community, 'msg' => $publication_msg, 'avatar' => core::documentparser()->communityAvatar($row), 'id_content' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
			}
		}
		
		foreach(Comments::getUserComment($user['id'], $number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['id_user'];
			$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_left_comment'));  
			$publication_msg .= '<ul class="attach_image">';
			
			foreach(Attach::getAttachList($row['id_content'], 'comment') as $row2){
				$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
				$publication_msg .= '<li><img border="0" src='.PATH_COMMENT_ATTACHMENTS . $photo['small_photo'].' class="photo_big" data-num="'.$photo['id_photo'].'" /></li>';
			}	
			
			$publication_msg .= '</ul></div></div>'	;
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'id_content' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}

		foreach(Videoalbum::getUserPublishVideo($user['id'], $number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['id_user'];
			$publication_msg = str_replace('%VIDEO%', core::documentparser()->getThumb($row['provider'], $row['video']), core::getLanguage('str', 'useraction_published_video')); 
			$publication_msg = str_replace('%ID%', $row['id_video'], $publication_msg); 	
	
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'id_content' => $row['id_video'], 'likeable_type' => 'video', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}

		foreach(Photoalbum::getUserPublishPhoto($user['id'], $number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['id_user'];
			$publication_msg = str_replace('%PHOTO%', core::documentparser()->photogalleryPic($row['small_photo'], $row['photoalbumable_type']), core::getLanguage('str', 'useraction_added_photo')); 
			$publication_msg = str_replace('%ID%', $row['id_photo'], $publication_msg); 
	
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'id_content' => $row['id_photo'], 'likeable_type' => 'photo', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}		
		
		foreach(core::user()->getMyFriendsLastFriend($number, $offset) as $row){
			$publication_name = $row['lastname'] . " " . $row['firstname'];
			$publication_id_author = $row['id_user'];
	
			$userfriend = $row['friend_lastname'] . " " . $row['friend_firstname'];
	
			$publication_msg = str_replace('%USERFRIEND%', $userfriend, core::getLanguage('str', 'useraction_make_friends')); 
			$publication_msg = str_replace('%ID_FRIEND%', $row['id_friend'], $publication_msg); 
	
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'id_content' => '', 'likeable_type' => '', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}		

		foreach(Comments::getUserGetVideoComment($user['id'], $number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['id_user'];
			$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_commented_video'));  
			$video = Videoalbum::getVideoInfo($row['id_content']);
		
			if($video['id_owner']){

				$avatar = core::documentparser()->userAvatar($video);
				$date = core::documentparser()->mysql_russian_date($row['added']);
				$author_name = $video['firstname'] . " " . $video['lastname'];	
				$publication_msg = str_replace('%VIDEO%', core::documentparser()->getThumb($video['provider'], $video['video']), $publication_msg);
				$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);
				$publication_msg = str_replace('%ID_AUTHOR%', $video['id_owner'], $publication_msg);
				$publication_msg = str_replace('%ID%', $video['id_video'], $publication_msg);
				$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
				$publication_msg = str_replace('%DATE%', $date , $publication_msg); 
			}

			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'id_content' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		} 

		foreach(Comments::getUserGetPhotoComment($user['id'], $number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['id_user'];
			$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_commented_photo'));
	
			$photo = Photoalbum::getPhotoInfo($row['id_content']);
		
			if($photo['id_owner']){
				$avatar = core::documentparser()->userAvatar($photo);
				$date = core::documentparser()->mysql_russian_date($row['added']);
				$author_name = $photo['firstname'] . " " . $photo['lastname'];	
			
				$publication_msg = str_replace('%PHOTO%', core::documentparser()->photogalleryPic($photo['small_photo'], $photo['photoalbumable_type']), $publication_msg);
				$publication_msg = str_replace('%ID_AUTHOR%',$photo['id_owner'], $publication_msg); 
				$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	
				$publication_msg = str_replace('%ID%', $row['id_photo'], $publication_msg); 
				$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
				$publication_msg = str_replace('%DATE%', $date , $publication_msg); 
			}
	
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'id_content' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);	
		}

		foreach(core::user()->getUserFriendsLiked($number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['id_user'];	
			$id_content = $row['id_content'];
			$pubmsg['publication_date'][] = core::documentparser()->mysql_russian_date($row['added']);
			$pubmsg['timeorder'][] = $row['timeorder'];
	
			if($row['likeable_type'] == 'comment'){
				$comment = Comments::getCommentInfo($row['id_content']);
		
				if($comment['id_user']){
					$author_name = $comment['firstname'] . " " . $comment['lastname'];
					$avatar = core::documentparser()->userAvatar($comment);
					$link = './?task=profile&id_user='.$comment['id_user'];
					
					if ($comment['commentable_type']=='group'||$comment['commentable_type']=='team'){

						$community = Communities::getCommunityInfo($comment['id_content']);
						
						if(Communities::checkOwnerCommunity($comment['id_content'], $comment['id_user']) or Communities::checkAdminCommunity($comment['id_content'], $comment['id_user'])){
							$author_name = $community['name'];	
							$avatar = core::documentparser()->communityAvatar($community);
							$link = './?task='.$community['type'].'s&id_community='.$comment['id_content'];
						}
					}
					
					if ($comment['commentable_type']=='event'){
						$event = Events::getEventInfo($comment['id_content']);
						
						if (Events::checkOwnerEvent($comment['id_content'], $comment['id_user'], 'user')){
							$author_name = $event['name'];	
							$avatar = core::documentparser()->eventAvatar($event['cover_page']);
							$link = './?task=events&id_event='.$comment['id_content'];
						}
					}
					$date = core::documentparser()->mysql_russian_date($row['added']);
		
					$publication_msg = str_replace('%MSG%', $comment['content'], core::getLanguage('str', 'useraction_liked_post'));
					$publication_msg = str_replace('%LINK%', $link, $publication_msg); 
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg);
					$publication_msg .= '<ul class="attach_image">';
					
					foreach(Attach::getAttachList($row['id_content'], 'comment') as $row2){
						$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
						$publication_msg .= '<li><img border="0" src='.PATH_COMMENT_ATTACHMENTS . $photo['small_photo'].' class="photo_big" data-num="'.$photo['id_photo'].'" /></li>';
					}
					
					$publication_msg .= '</ul></div></div>'	; 
				}	
			}	
			else if($row['likeable_type'] == 'video'){
				$video = Videoalbum::getVideoInfo($row['id_content']);
		
				if($video['id_owner']){
					$avatar = core::documentparser()->userAvatar($video);
					$date = core::documentparser()->mysql_russian_date($row['added']);
					$author_name = $video['firstname'] . " " . $video['lastname'];	
					$publication_msg = str_replace('%VIDEO%', core::documentparser()->getThumb($video['provider'], $video['video']), core::getLanguage('str', 'useraction_liked_video'));
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);
					$publication_msg = str_replace('%ID_AUTHOR%', $video['id_owner'], $publication_msg);
					$publication_msg = str_replace('%ID%', $video['id_video'], $publication_msg);
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg);  
				}	
			}	
			else if($row['likeable_type'] == 'photo'){	
				$photo = Photoalbum::getPhotoInfo($row['id_content']);
		
				if($photo['id_owner']){
					$avatar = core::documentparser()->userAvatar($photo);
					$date = core::documentparser()->mysql_russian_date($row['added']);
					$author_name = $photo['firstname'] . " " . $photo['lastname'];	
			
					$publication_msg = str_replace('%PHOTO%', core::documentparser()->photogalleryPic($photo['small_photo'], $photo['photoalbumable_type']), core::getLanguage('str', 'useraction_liked_photo'));
					$publication_msg = str_replace('%ID_AUTHOR%',$photo['id_owner'], $publication_msg); 
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	
					$publication_msg = str_replace('%ID%', $id_content, $publication_msg); 
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg); 
				}		
			}	
	
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'id_content' => $id_content, 'likeable_type' => $row['likeable_type'], 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}
		foreach(core::user()->getUserShare($number, $offset) as $row){
			$publication_name = $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['id_user'];	
			$id_content = $row['id_content'];
			$pubmsg['publication_date'][] = core::documentparser()->mysql_russian_date($row['added']);
			$pubmsg['timeorder'][] = $row['timeorder'];	
			
			if($row['shareable_type'] == 'comment'){
				$comment = Comments::getCommentInfo($row['id_content']);
				
				if($comment['id_user']){
					$author_name = $comment['firstname'] . " " . $comment['lastname'];
					$avatar = core::documentparser()->userAvatar($comment);
					$link = './?task=profile&id_user='.$comment['id_user'];
					
					if ($comment['commentable_type']=='group'||$comment['commentable_type']=='team'){
						$community = Communities::getCommunityInfo($comment['id_content']);
						
						if(Communities::checkOwnerCommunity($comment['id_content'], $comment['id_user']) or Communities::checkAdminCommunity($comment['id_content'], $comment['id_user'])){
							$author_name = $community['name'];	
							$avatar = core::documentparser()->communityAvatar($community);
							$link = './?task='.$community['type'].'s&id_community='.$comment['id_content'];
						}
					}
					if ($comment['commentable_type'] == 'event'){
						$event = Events::getEventInfo($comment['id_content']);
						
						if (Events::checkOwnerEvent($comment['id_content'], $comment['id_user'], 'user')){
							$author_name = $event['name'];	
							$avatar = core::documentparser()->eventAvatar($event['cover_page']);
							$link = './?task=events&id_event='.$comment['id_content'];
						}
					}
					$date = core::documentparser()->mysql_russian_date($row['added']);		
				
					$publication_msg = str_replace('%MSG%', $comment['content'], core::getLanguage('str', 'useraction_shared_post'));
					$publication_msg = str_replace('%LINK%', $link, $publication_msg); 
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	 
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg);
					$publication_msg .= '<ul class="attach_image">';
					
					foreach(Attach::getAttachList($row['id_content'], 'comment') as $row2){
						$photo = Photoalbum::getPhotoInfo($row2['id_photo']);
						$publication_msg .= '<li><img border="0" src='.PATH_COMMENT_ATTACHMENTS . $photo['small_photo'].' class="photo_big" data-num="'.$photo['id_photo'].'" /></li>';
					}	
					
					$publication_msg .= '</ul>'	;
					$publication_msg .= '</div></div>';
				}	
			}	
			else if($row['shareable_type'] == 'video'){
				$video = Videoalbum::getVideoInfo($row['id_content']);
				$avatar = core::documentparser()->userAvatar($video);
				$date = core::documentparser()->mysql_russian_date($row['added']);	
				
				if($video['id_owner']){
					$author_name = $video['firstname'] . " " . $video['lastname'];	
					$publication_msg = str_replace('%VIDEO%', core::documentparser()->getThumb($video['provider'], $video['video']), core::getLanguage('str', 'useraction_shared_video'));
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);
					$publication_msg = str_replace('%ID_AUTHOR%', $video['id_owner'], $publication_msg);
					$publication_msg = str_replace('%ID%', $video['id_video'], $publication_msg); 	 
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg);		
				}	
			}	
			else if($row['shareable_type'] == 'photo'){	
				$photo = Photoalbum::getPhotoInfo($row['id_content']);
				$avatar = core::documentparser()->userAvatar($photo);
				$date = core::documentparser()->mysql_russian_date($row['added']);	
				
				if($photo['id_owner']){
					$author_name = $photo['firstname'] . " " . $photo['lastname'];	
					$publication_msg = str_replace('%PHOTO%', core::documentparser()->photogalleryPic($photo['small_photo'], $photo['photoalbumable_type']), core::getLanguage('str', 'useraction_shared_photo'));
					$publication_msg = str_replace('%ID_AUTHOR%',$photo['id_owner'], $publication_msg); 
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	
					$publication_msg = str_replace('%ID%', $id_content, $publication_msg);  	 
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg);
				}		
			}	
			
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'id_content' => $id_content, 'likeable_type' => $row['shareable_type'], 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}
		
		if($arrs){
		
			$html = '';

			foreach(core::documentparser()->customMultiSort($arrs, 'timeorder') as $row){
				$html .= '<div class="news-block-item" data-toggle="modal" data-target="#second-post">';
				$html .= '<div class="news-block-head">';
				if ($row['type']=='user'){
					$html .= '<a href="./?task=profile&id_user=' . $row['id_author'] . '"><div class="head-img"><img src="' . $row['avatar'] . '" alt=""></div></a>';
					$html .= '<a href="./?task=profile&id_user=' . $row['id_author'] . '"><p class="head-topic">' . $row['name'] . '<span class="status_user" data-num="' . $row['id_author'] . '"></span></p></a>';	
				}
				else if ($row['type']=='event'){
					$html .= '<a href="./?task=events&id_event=' . $row['id_author'] . '"><div class="head-img"><img src="' . $row['avatar'] . '" alt=""></div></a>';
					$html .= '<a href="./?task=events&id_event=' . $row['id_author'] . '"><p class="head-topic">' . $row['name'] . '</p></a>';
				}
				else{
					$html .= '<a href="./?task='.$row['type'].'s&id_community=' . $row['id_author'] . '"><div class="head-img"><img src="' . $row['avatar'] . '" alt=""></div></a>';
					$html .= '<a href="./?task='.$row['type'].'s&id_community=' . $row['id_author'] . '"><p class="head-topic">' . $row['name'] . '</p></a>';
				}

				$html .= '<p class="data">' . $row['publication_date'] . '</p>';			
				$html .= '<div class="clearfix"></div>';				
				$html .= '</div>';				
				$html .= '<div class="news-block-content">';				
				$html .= '<div class="article nov">';				
				$html .= $row['msg'];			
				$html .= '</div>';	
				
				if($row['likeable_type']) $html .= ' <a id="like-' . $row['likeable_type'] . '-' . $row['id_content'] . '" class="liked" data-item="' . $row['id_content'] . '" data-type="' . $row['likeable_type'] . '">' . Comments::getNumberLiked($row['id_content'], $row['likeable_type']) . '</a>';				
				
				$html .= '</div>';					
				$html .= '</div>';					
			}
		
			$content = array();
			$content['status'] = 1;				
			$content['html'] = $html;	
			$content = json_encode($content);
			
			core::documentparser()->showJSONContent($content);
		}		
	
	break;	
	
	case send_message:
	
		Auth::authorization();
	
		$subject = htmlspecialchars(trim(Core_Array::getRequest('subject')));		
		$name = htmlspecialchars(trim(Core_Array::getRequest('name')));		
		$email = htmlspecialchars(trim(Core_Array::getRequest('email')));		
		$message = htmlspecialchars(trim(Core_Array::getRequest('message')));
		$captcha = trim(htmlspecialchars(Core_Array::getRequest('captcha')));
		
		if(empty($subject) or empty($name) or empty($email) or empty($message) or empty($captcha)) $error = core::getLanguage('error', 'not_all_fields_are_filled');
	
		if(!empty($captcha)) {
			if(empty($_SESSION['captcha']) || strtolower($captcha) != $_SESSION['captcha']) {
				$error = core::getLanguage('error', 'invalid_captcha'); 	
			} 

			unset($_SESSION['captcha']);
		}		
		
		$content = array();
		
		if(empty($error)){
			
			$fields = array();
			$fields['id'] = 0;
			$fields['subject'] = $subject;		
			$fields['name'] = $name;			
			$fields['email'] = $email;			
			$fields['message'] = $message;			
			$fields['time'] = date("Y-m-d H:i:s");			
			
			$result = $data->addFeedback($fields);
			
			if($result){
				$content['status'] = 1;				
				$content['msg'] = core::getLanguage('msg', 'add_feedback');
			}
			else{
				$content['status'] = 0;	
				$content['msg'] = core::getLanguage('error', 'web_apps_error');;
			}
		}
		else{
			$content['status'] = 0;	
			$content['msg'] = $error;
		}
		
		$content = json_encode($content);
			
		core::documentparser()->showJSONContent($content);
	
	break;
	
	case block_community_user:
	
		Auth::authorization();
	
		$id_community = is_numeric($_REQUEST['id_community']) ? $_REQUEST['id_community'] : exit();
		$id_user = is_numeric($_REQUEST['id_user']) ? $_REQUEST['id_user'] : exit();
		
		if(Communities::checkOwnerCommunity($id_community, $user['id']) or Communities::checkAdminCommunity($id_community, $user['id']))
			if(Communities::change_community_role($id_community, $id_user, 4))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));			
	
	break;
	
	case unblock_community_user:
	
		Auth::authorization();
	
		$id_community = is_numeric($_REQUEST['id_community']) ? $_REQUEST['id_community'] : exit();
		$id_user = is_numeric($_REQUEST['id_user']) ? $_REQUEST['id_user'] : exit();
		
		if(Communities::checkOwnerCommunity($id_community, $user['id']) or Communities::checkAdminCommunity($id_community, $user['id']))
			if(Communities::remove_community_role($id_community, $id_user))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));		
		
	break;

	case add_community_administrator:
	
		Auth::authorization();
	
		$id_community = is_numeric($_REQUEST['id_community']) ? $_REQUEST['id_community'] : exit();
		$id_user = is_numeric($_REQUEST['id_user']) ? $_REQUEST['id_user'] : exit();
		
		if(Communities::checkOwnerCommunity($id_community, $user['id']) or Communities::checkAdminCommunity($id_community, $user['id']))
			if(Communities::change_community_role($id_community, $id_user, 3))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
	
	case approve_community_user:
	
		Auth::authorization();
	
		$id_community = is_numeric($_REQUEST['id_community']) ? $_REQUEST['id_community'] : exit();
		$id_user = is_numeric($_REQUEST['id_user']) ? $_REQUEST['id_user'] : exit();
		
		if(Communities::checkOwnerCommunity($id_community, $user['id']) or Communities::checkAdminCommunity($id_community, $user['id']))
			if(Communities::change_community_role($id_community, $id_user, 2))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
	
	case remove_community_administrator:
	
		Auth::authorization();
	
		$id_community = is_numeric($_REQUEST['id_community']) ? $_REQUEST['id_community'] : exit();
		$id_user = is_numeric($_REQUEST['id_user']) ? $_REQUEST['id_user'] : exit();
		
		if(Communities::checkOwnerCommunity($id_community, $user['id']) or Communities::checkAdminCommunity($id_community, $user['id']))
			if(Communities::remove_community_role($id_community, $id_user))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));		
		
	break;

	case search_event:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$id_member = is_numeric($_REQUEST['id_member']) ? $_REQUEST['id_member'] : exit();
		$eventable_type = !empty($_REQUEST['eventable_type']) ? $_REQUEST['eventable_type'] : exit();
		
		$html = '';
		
		foreach(Events::getSearchEventsList($id_member, $eventable_type, $number, $offset) as $row){
			$html .= '<div class="event-item"> <a href="./?task=events&id_event=' . $row['id_event'] . '" class="img"><img src="' . core::documentparser()->eventAvatar($row['cover_page']) . '" alt="" style="margin-left: -100%;"></a>';
            $html .= '<a class="addEvent" data-tooltip="Присоединиться" data-item="'.$row['id_event'].'" data-status="1"><img src="./templates/images/icon-ok.png"/></a>';
			$html .= '<div class="teg">';
			$html .= '<p><a href="./?task=events&id_event=' . $row['id_event'] . '">' . $row['name'] . '</a></p>'; 
            $html .= ' <p>';  
			
			if($row['sport_type']) $html .= $row['sport_type'] . '<br>'; 
            if($row['place']) $html .= $row['place'] . '<br>';
			
			$date_interval_event_beginning = str_replace('%DATE_FROM%', core::documentparser()->mysql_russian_datetime($row['date_from']), core::getLanguage('str', 'date_interval_event_beginning'));
			$date_interval_event_beginning = str_replace('%TIME_FROM%', $row['time_from'], $date_interval_event_beginning);
		
			$html .= $date_interval_event_beginning . '<br>'; 
				
			if(!empty($row['date_to'])){
				$date_interval_event_end = str_replace('%DATE_TO%', core::documentparser()->mysql_russian_datetime($row['date_to']), core::getLanguage('str', 'date_interval_event_end'));
				$date_interval_event_end = str_replace('%TIME_TO%', $row['time_to'], $date_interval_event_end);
					
				$html .= $date_interval_event_end . '<br>'; 	
			}
                
			$html .= '</p>';	  
				
			if(Events::getEventRole(Events::getMemberShipStatus($row['id_event'], $user['id'], 'user'))) $html .= '<p>' . Events::getEventRole(Events::getMemberShipStatus($row['id_event'], $user['id'], 'user')) . '</p>';
			
			if(Events::getEventStatus($row['id_event']) == 'continues') 
				$status = core::getLanguage('str', 'event_continues');			
			else if(Events::getEventStatus($row['id_event']) == 'end')	
				$status = core::getLanguage('str', 'event_completed');		
			
			$html .= '<p><i></i>' . str_replace('%MEMBERS%', Events::countMembers($row['id_event'], 'user'), core::getLanguage('str', 'participants_friends')) . '</p>';
			$html .= '<span>' . $status . '</span>';
            $html .= '</div>';   	
			$html .= '</div>';			
		}
		
		$content = array();
		$content['status'] = 1;				
		$content['html'] = $html;	
		$content = json_encode($content);
			
		core::documentparser()->showJSONContent($content);	
	
	break;	
	
	case change_event_community_status:

		Auth::authorization();
	
		$id_event = is_numeric($_REQUEST['id_event']) ? $_REQUEST['id_event'] : exit();
		$status = is_numeric($_REQUEST['status']) ? $_REQUEST['status'] : exit();
		$id_community = is_numeric($_REQUEST['id_community']) ? $_REQUEST['id_community'] : exit();
		
		$community = Communities::getCommunityInfo($id_community);	
		
		if(Communities::checkOwnerCommunity($id_community, $user['id']) or Communities::checkAdminCommunity($id_community, $user['id'])){
			if(Events::changeMemberStatus($id_event, $id_community, $community['type'], $status))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		}
		else $content = array("result" => 'error');	
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
	
	case get_sport_block_list:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$type = !empty($_REQUEST['type']) ? $_REQUEST['type'] : exit();
		
		$html = '';
		
		foreach(SportBlocks::getSportBlocks($type, $number, $offset) as $row){
			$html .= '<div class="content-fitness">';
			
			if($row['avatar']){
				if($row['type'] == 'shop') $html .= '<a href="./?task=shops&id_sport_block=' . $row['id'] . '" class="img"><img src="' . core::documentparser()->sportblockAvatar($row['avatar']) . '"></a>';
				else if($row['type'] == 'fitness') $html .= '<a href="./?task=fitness&id_sport_block=' . $row['id'] . '" class="img"><img src="' . core::documentparser()->sportblockAvatar($row['avatar']) . '"></a>';
				else if($row['type'] == 'playground') $html .= '<a href="./?task=playgrounds&id_sport_block=' . $row['id'] . '" class="img"><img src="' . core::documentparser()->sportblockAvatar($row['avatar']) . '"></a>';
			}
			
			$html .= ' <div class="teg">';
			
			if($row['type'] == 'shop') $html .= '<p><a href="./?task=shops&id_sport_block=' . $row['id'] . '">' . $row['name'] . '</a></p>';
			else if($row['type'] == 'fitness') $html .= '<p><a href="./?task=fitness&id_sport_block=' . $row['id'] . '">' . $row['name'] . '</a></p>';
			else if($row['type'] == 'playground') $html .= '<p><a href="./?task=playgrounds&id_sport_block=' . $row['id'] . '">' . $row['name'] . '</a></p>';
			
			if($row['place']) $html .= '<p>' . $row['place'] . '</p>';
			
            $html .= '<p>' . $row['about'] . '</p>';
			  
            if($row['id_owner'] == $user['id']) {
				if($row['type'] == 'shop') $html .=  '<a href="./?task=shops&id_sport_block=' . $row['id'] . '&q=edit">' . core::getLanguage('str', 'edit') . '</a> </div>';
				else if($row['type'] == 'fitness') $html .=  '<a href="./?task=fitness&id_sport_block=' . $row['id'] . '&q=edit">' . core::getLanguage('str', 'edit') . '</a> </div>';
				else if($row['type'] == 'playgrounds') $html .=  '<a href="./?task=playgrounds&id_sport_block=' . $row['id'] . '&q=edit">' . core::getLanguage('str', 'edit') . '</a> </div>';
			} 
              
            $html .= '</div>';           
		}
		
		$content = array();
		$content['status'] = 1;				
		$content['html'] = $html;	
		$content = json_encode($content);
			
		core::documentparser()->showJSONContent($content);	
	
	break;	
	
	case get_pop_events_list:
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		
		$html = '';
		
		foreach(Events::getPopularEventList($number, $offset) as $row){
			$html .= '<div class="event-item"> <a href="./?task=events&id_event=' . $row['id'] . '" class="img"><img src="' . core::documentparser()->eventAvatar($row['cover_page']) . '" alt="" style="margin-left: -100%;"></a>';      
			$html .= '<div class="teg">';
			$html .= '<p><a href="./?task=events&id_event=' . $row['id'] . '">' . $row['name'] . '</a></p>';
			$html .= '<p>';
              
            if(!empty($row['sport_type'])) $html .= $row['sport_type'] . "<br>";
			if(!empty($row['city'])) $html .= $row['city'] . "<br>";
                
			$date_interval_event_beginning = str_replace('%DATE_FROM%', core::documentparser()->mysql_russian_datetime($row['date_from']), core::getLanguage('str', 'date_interval_event_beginning'));
			$date_interval_event_beginning = str_replace('%TIME_FROM%', $row['time_from'], $date_interval_event_beginning);	
                
			if($date_interval_event_beginning) $html .= $date_interval_event_beginning . "<br>";	
				
			if(!empty($row['date_to'])){
				$date_interval_event_end = str_replace('%DATE_TO%', core::documentparser()->mysql_russian_datetime($row['date_to']), core::getLanguage('str', 'date_interval_event_end'));
				$date_interval_event_end = str_replace('%TIME_TO%', $row['time_to'], $date_interval_event_end);
					
				$html .= $date_interval_event_end . "<br>";	
			} 
			  
			$html .= '</p>';  		  
	
			if(Events::getEventRole(Events::getMemberShipStatus($row['id'], $user['id'], 'user')))	
				$html .= '<p>' . Events::getEventRole(Events::getMemberShipStatus($row['id'], $user['id'], 'user')) . '</p>';
				
			$html .= '<p><i></i>' . str_replace('%MEMBERS%', Events::countMembers($row['id'], 'user'), core::getLanguage('str', 'participants_friends')) . '</p>';
                
			if(Events::checkOwnerEvent($row['id_event'], $user['id'], 'user')) $html .=	'<a href="./?task=events&id_event=' . $row['id'] . '&q=edit">' . core::getLanguage('str', 'edit') . '</a>';
				
			if(Events::getEventStatus($row['id']) == 'continues') 
				$html .= '<span>' . core::getLanguage('str', 'event_continues') . '</span>';
			else if(Events::getEventStatus($row['id']) == 'end')	
				$html .= '<span>' . core::getLanguage('str', 'event_completed'). '</span>';
			
			$html .= '</div>';
			$html .= '</div>';			  
		}
		
		$content = array();
		$content['status'] = 1;				
		$content['html'] = $html;	
		$content = json_encode($content);
			
		core::documentparser()->showJSONContent($content);		
	
	break;
	
	case get_pop_communities_list:
	
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();		
		$type = !empty($_REQUEST['type']) ? $_REQUEST['type'] : exit();
		
		$html = '';
		$task = $type == 'group' ? 'groups' : 'teams';	
	
		foreach(Communities::getPopularCommunitiesList($type, $number, $offset) as $row){
			
			$html .= '<div id="community_' .$row['id_community'] . '" class="event-item">';
			$html .= '<a class="img" href="./?task=' . $task . '&id_community=' . $row['id_community'] . '">';
			$html .= '<img border="0" alt="" src="' . core::documentparser()->communityAvatar($row) . '">';
			$html .= '</a>';
			$html .= '<div class="teg">';
			$html .= '<p><a href="./?task=' . $type . '&id_community=' . $row['id_community'] . '"> ' . $row['name'] . '</a></p>';

			$status = Communities::getCommunityRole(Communities::getUserStatus($row['id_community'], $user['id']));			
			
			$html .= '<p>' . $row['sport_type'] . '<br> ' . $status . '<br>' . $row['place'] . '</p>';
			$html .= '<p> ' .$row['about'] . '</p>';
			$html .= '<p><i></i>'. str_replace('%MEMBERS%', Communities::countMemberCommunity($row['id_community'], 2), core::getLanguage('str', 'participants_friends')) . '</p>';
			
			if(Communities::checkOwnerCommunity($row['id_community'], $user['id'])) {
				$html .= '<a href="./?task=' . $type . '&id_community=' .$row['id_community'] . '&q=edit">' . core::getLanguage('str', 'edit') . '</a>';
			}				
			
			$html .= '<div class="transparent"> </div>';
			$html .= '</div>';
			$html .= '</div>';			
		}

		$content = array();
		$content['status'] = 1;				
		$content['html'] = $html;	
		$content = json_encode($content);
			
		core::documentparser()->showJSONContent($content);		
	
	break;
	
	case get_parsing:

		$str = !empty($_REQUEST['str']) ? $_REQUEST['str'] : exit();
		
		core::requireEx('libs', "simple_html_dom.php");
		
		$html = file_get_html($str);
		$content = array ();
		$title = $html->find('title', 0)->innertext;
		$description = $html->find('meta[name=description]', 0)->content;
		$img = $html->find('img');
		$src = $img[0]->src;
		$html->clear();
		$content['status'] = 1;
		$content['title'] = $title!=null ? $title : '';
		$content['description'] = $description!=null ? $description : '';
		$content['img'] = !@fopen($src,'r') ? '' : $src;
		if ($content['img']=='')
			$content['img'] = !@fopen($str.$src,'r') ? '' : $str.$src;
		if ($content['img']=='')
			$content['img'] = !@fopen($str.'/'.$src,'r') ? '' : $str.'/'.$src;

		$content = json_encode($content);
		core::documentparser()->showJSONContent($content);	
		
	break;
	
	case get_friends_list:
	
		Auth::authorization();
	
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$id_user = is_numeric($_REQUEST['id_user']) ? $_REQUEST['id_user'] : exit();			
		
		if($id_user != $user['id']){
			core::user()->setUser_id($id_user);
			$profile_usersetting = core::user()->getUserSetting();	
		
			if(core::user()->permissionUser($user['id'], $profile_usersetting['permission_view_friends'])) 
				$permit = true;
			else
				$permit = false;
		}	
		
		if($permit !== false){
			foreach(Friends::getFriendsList($id_user, $number, $offset) as $row){
				$rows[] = array(
				"id_user" => $row['id_user'],			
				"avatar" => core::documentparser()->userAvatar($row),
				"firstname" => $row['firstname'],			
				"lastname" => $row['lastname'],
				"city" => $row['city'],
				"status_user" => core::user()->checkUserOnline($row['id_user']) ? 'online' : 'offline',
				"id_sender" => $user['id']);
			}	
			
			$content = '{"item":'.json_encode($rows).'}';		
	
			core::documentparser()->showJSONContent($content);	
		}
		
	break;
	
	case remove_message:
	
		Auth::authorization();		
		
		$id = is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : exit();
		
		if($data->removeMessage($id, $user['id']))
			$content = array("result" => 'success');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
}