<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

if($_SESSION['user_authorization'] == "ok"){
	core::user()->setUser_id($_SESSION['user_id']);
	$user = core::user()->getUserInfo();
	
	core::user()->setUserActivity();	
}

switch ($_GET['action'])
{	
	case 'removepic':
	
		Auth::authorization();
	
		if($_REQUEST['id']){
			
			$photo_id = core::database()->escape((int)Core_Array::getRequest('id'));	

			$row = Photoalbum::getPhotoInfo($photo_id);
			
			$allow = FALSE;
			
			switch($row['photoalbumable_type']) {
		
				case 'user':
					if($row['owner_id'] == $user['id']) $allow = TRUE;
				break;

				case 'user_attach':
					if($row['owner_id'] == $user['id']) $allow = TRUE;
				break;

				case 'team':
					if(Communities::checkOwnerCommunity($row['photoalbum_owner'], $user['id']) or Communities::checkAdminCommunity($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;	
				
				case 'group':
					if(Communities::checkOwnerCommunity($row['photoalbum_owner'], $user['id']) or Communities::checkAdminCommunity($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;		
		
				case 'event':
					if($row['owner_id'] == $user['id'] or Events::checkOwnerEvent($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;
		
				case 'playground':
					if($row['owner_id'] == $user['id'] or Playgrounds::checkOwnerPlayground($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;
		
				case 'fitness':
					if($row['owner_id'] == $user['id'] or SportBlocks::checkOwner($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;
		
				case 'shop':
					if($row['owner_id'] == $user['id'] or SportBlocks::checkOwner($row['photoalbum_owner'], $user['id'])) $allow = TRUE;
				break;
			}			
			
			if($allow){
				if(Photoalbum::removePhoto($photo_id))
					$content = array("result" => 'success');
				else
					$content = array("result" => 'error');
			}	
			else $content = array("result" => 'error');	
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case 'removevideo':
	
		Auth::authorization();
	
		if($_REQUEST['id']){
			
			$id = core::database()->escape((int)Core_Array::getRequest('id'));
			$row = Videoalbum::getVideoInfo($id);

			$allow = FALSE;
			
			switch($row['videoalbumable_type']) {
		
				case 'user':
					if($row['owner_id'] == $user['id']) $allow = TRUE;
				break;			
		
				case 'team': 		
					if(Communities::checkOwnerCommunity($row['id_albumowner'], $user['id']) or Communities::checkAdminCommunity($row['id_albumowner'], $user['id'])) $allow = TRUE;
				
				break;	
				
				case 'group': 		
					if(Communities::checkOwnerCommunity($row['id_albumowner'], $user['id']) or Communities::checkAdminCommunity($row['id_albumowner'], $user['id'])) $allow = TRUE;
				break;		
		
				case 'event': 		
					if($row['owner_id'] == $user['id'] or Events::checkOwnerEvent($row['id_albumowner'], $user['id'])) $allow = TRUE;
				break;
		
				case 'playground':		
					if($row['owner_id'] == $user['id'] or Playgrounds::checkOwnerPlayground($row['id_albumowner'], $user['id'])) $allow = TRUE;
				break;
		
				case 'fitness':		
					if($row['owner_id'] == $user['id'] or SportBlocks::checkOwner($row['id_albumowner'], $user['id'])) $allow = TRUE;
				break;
		
				case 'shop': 		
					if($row['owner_id'] == $user['id'] or SportBlocks::checkOwner($row['id_albumowner'], $user['id'])) $allow = TRUE;
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
	
	case 'addcomment':
	
		Auth::authorization();
	
		$errors = array();

		$user_id = is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : exit();		
		$content_id = is_numeric($_REQUEST['content_id']) ? $_REQUEST['content_id'] : exit();		
		$parent_id = is_numeric($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] : exit();
		$attach = $_REQUEST['attach'] ? explode(",", $_REQUEST['attach']) : '';
		
		$author_community = $_REQUEST['author_community'] ? $_REQUEST['author_community'] : ''; 

		$commentable_type = Core_Array::getRequest('commentable_type');
		$comment = htmlspecialchars(trim(Core_Array::getRequest('comment')));
		
		if($commentable_type == 'user'){
			core::user()->setUser_id($content_id);
			$owner = core::user()->getUserInfo();
			$owner_settings = core::user()->getUserSetting();
			
			if(core::user()->permissionUser($user['id'], $owner_settings['permission_view_wall']) && $data->checkBlock($user['id'], $content_id))
				$permit = 'yes';
			else
				$permit = 'no';
		}		
		
		if(empty($errors) && $permit != 'no' && Comments::checkBanReceiver($commentable_type, $content_id)){
			$fields = array();
			$fields['id'] = 0;
			$fields['commentable_type'] = $commentable_type;
			$fields['content_id'] = $content_id;
			$fields['user_id'] = $user['id'];
			$fields['content'] = $comment;
			$fields['created_at'] = date("Y-m-d H:i:s");
			$fields['parent_id'] = $parent_id;			
			
			if($commentable_type == 'group' or $commentable_type == 'team'){
				if($author_community == 1 && Communities::checkOwnerCommunity($content_id, $user['id']) or Communities::checkAdminCommunity($content_id, $user['id'])) {
					$fields['behalfable_type'] = $commentable_type;
					$fields['behalf_id'] = $fields['content_id'];
					$head = TRUE;
				}
			}
			else if($commentable_type == 'event'){
				if($author_community == 1 && Events::checkOwnerEvent($content_id, $user['id'], 'user')) {
					$fields['behalfable_type'] = $commentable_type;
					$fields['behalf_id'] = $fields['content_id'];
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
						
						case 'team':
						
							$page_link = './?task=teams&user_id=' . $content_id;
						
						break;
						
						case 'group':
						
							$page_link = './?task=groups&user_id=' . $content_id;
							
						break;
						
						case 'event':
						
							$page_link = './?task=events&user_id=' . $content_id;
							
						break;
					}
				}else{
					$avatar = core::documentparser()->userAvatar($row);
					$name = $row['firstname'] . ' '  . $row['lastname'].'<span class="status_user' . (core::user()->checkUserOnline($row['user_id']) ? ' online' : '') . '" data-num="' . $row['user_id'] . '"></span>';
					$page_link = './?task=profile&user_id=' . $row['user_id'];
				}	

				if($parent_id == 0){
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
								
								$message_src = Photoalbum::getPhotoSmallSrc($message);
								if($message_src) $html .=  '<img border="0" src="'  . $message_src . '" class="photo_big"  data-num='.$message['photo_id'].'>';
							
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
								
								$message_src = Photoalbum::getPhotoSmallSrc($message);
								if($message_src) $html .=  '<img border="0" src="'  . $message_src . '" class="photo_big" data-num='.$message['photo_id'].'>';
							
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
			
				if($parent_id == 0){			
					if($commentable_type == 'user')	{
						if($settings['notification_wall_comments'] != 'no' && $user['id'] != $content_id){						
							core::user()->setUser_id($content_id);
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
							$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $user['id'];

							$msg = $mail['content_ru'];
							
							$pos = strpos(substr($msg, 800), " ");			

							if(strlen($msg) > 800) 
								$srttmpend = "...";
							else 
								$strtmpend = "";
			
							$msg = substr($msg, 0, 800 + $pos) . $srttmpend; 								
							
							$msg = str_replace('%COMMENT%', htmlspecialchars(trim(Core_Array::getRequest('comment'))), $msg);
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $user['id'], $msg);
							$message['msg'] = $msg;
							
							$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
							$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
							$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;							

							core::documentparser()->userNotification($receiver['email'], $message);						
						}
					}
					else if($commentable_type == 'photo'){						
						if($settings['notification_picture_comments'] != 'no' && $user['id'] != $content_id){
							$mail = $data->getMailNotification(4);	
							$photo = Photoalbum::getPhotoInfo($content_id);

							$small_photo = core::documentparser()->photogalleryPic($photo['small_photo']);
						
							core::user()->setUser_id($photo['owner_id']);
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
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=photoalbums&id_album=' . $photo['photoalbum_id'] . '&user_id=' . $photo['owner_id'], $msg);
							else if($photo['photoalbumable_type'] == 'team')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&community_id=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
							else if($photo['photoalbumable_type'] == 'group')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&community_id=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
							else if($photo['photoalbumable_type'] == 'event')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&event_id=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
							else if($photo['photoalbumable_type'] == 'playground')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_playground=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
							else if($photo['photoalbumable_type'] == 'fitness')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
							else if($photo['photoalbumable_type'] == 'shop')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
				
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
							$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $user['id'];
							$message['msg'] = $msg;								
							$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
							$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
							$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;	
							
							core::documentparser()->userNotification($receiver['email'], $message);				
						}					
					}
					else if($commentable_type == 'video'){
						if($settings['notification_video_comments'] != 'no' && $user['id'] != $content_id){
							$mail = $data->getMailNotification(5);	
			
							$video = Videoalbum::getVideoInfo($content_id);
						
							core::user()->setUser_id($video['owner_id']);
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
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=videoalbums&id_album=' . $video['videoalbum'] . '&user_id=' . $video['owner_id'], $msg);
							else if($video['videoalbumable_type'] == 'team')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&community_id=' . $video['owner_id'] . '&q=videoalbums&id_album=' . $video['videoalbum_id'], $msg);
							else if($video['videoalbumable_type'] == 'group')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&community_id=' . $video['owner_id'] . '&q=videoalbums&id_album=' . $video['videoalbum_id'], $msg);
							else if($video['videoalbumable_type'] == 'event')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&event_id=' . $video['owner_id'] . '&q=videoalbums&id_album=' . $video['videoalbum_id'], $msg);				
							else if($video['videoalbumable_type'] == 'playground')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_playground=' . $video['owner_id'] . '&q=videoalbums&id_album=' . $video['videoalbum_id'], $msg);
							else if($video['videoalbumable_type'] == 'fitness')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $video['owner_id'] . '&q=photoalbums&id_album=' . $video['videoalbum_id'], $msg);
							else if($video['videoalbumable_type'] == 'shop')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $video['owner_id'] . '&q=photoalbums&id_album=' . $video['videoalbum_id'], $msg);
					
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
							$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $user['id'];
							$restrict_or_cancel_notification = core::getLanguage('str', 'restrict_or_cancel_notification');
							$restrict_or_cancel_notification = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=settings', $restrict_or_cancel_notification);
							$message['restrict_or_cancel_notification'] = $restrict_or_cancel_notification;	
							$message['msg'] = $msg;						

							core::documentparser()->userNotification($receiver['email'], $message);
						}							
					}	
				}
				else{
					if($settings['notification_answers_in_comments'] != 'no' && $user['id'] != $content_id){
						$mail = $data->getMailNotification(7);
						
						core::user()->setUser_id($content_id);
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
						$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $user['id'];
						
						$msg = $mail['content_ru'];
						
						$pos = strpos(substr($msg, 800), " ");			

						if(strlen($msg) > 800) 
							$srttmpend = "...";
						else 
							$strtmpend = "";
			
						$msg = substr($msg, 0, 800 + $pos) . $srttmpend;						
						$msg = str_replace('%COMMENT%', htmlspecialchars(trim(Core_Array::getRequest('comment'))), $msg);
						
						if($row['commentable_type'] == 'user')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $row['content_id'], $msg);
						else if($row['commentable_type'] == 'team')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&community_id=' . $row['content_id'], $msg);
						else if($row['commentable_type'] == 'group')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&community_id=' . $row['content_id'], $msg);
						else if($row['commentable_type'] == 'event')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&event_id=' . $row['content_id'], $msg);
						else if($row['commentable_type'] == 'playground')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_playground=' . $row['content_id'], $msg);						
						else if($row['commentable_type'] == 'fitness')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $row['content_id'], $msg);
						else if($row['commentable_type'] == 'shop')
							$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $row['content_id'], $msg);
						else if($row['commentable_type'] == 'photo'){
							$photo = Photoalbum::getPhotoInfo($row['content_id']);
							
							if($photo['photoalbumable_type'] == 'user') 
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=photoalbums&id_album=' . $photo['photoalbum_id'] . '&user_id=' . $photo['owner_id'], $msg);
							else if($photo['photoalbumable_type'] == 'team')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&community_id=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
							else if($photo['photoalbumable_type'] == 'group')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&community_id=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
							else if($photo['photoalbumable_type'] == 'event')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&event_id=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
							else if($photo['photoalbumable_type'] == 'playground')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_playground=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
							else if($photo['photoalbumable_type'] == 'fitness')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);
							else if($photo['photoalbumable_type'] == 'shop')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $photo['owner_id'] . '&q=photoalbums&id_album=' . $photo['photoalbum_id'], $msg);							
						}
						else if($row['commentable_type'] == 'video'){
							
							$video = Videoalbum::getVideoInfo($row['content_id']);
							
							if($video['videoalbumable_type'] == 'user')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=videoalbums&id_album=' . $video['videoalbum'] . '&user_id=' . $video['owner_id'], $msg);
							else if($video['videoalbumable_type'] == 'team')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&community_id=' . $video['owner_id'] . '&q=videoalbums&id_album=' . $video['videoalbum_id'], $msg);
							else if($video['videoalbumable_type'] == 'group')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&community_id=' . $video['owner_id'] . '&q=videoalbums&id_album=' . $video['videoalbum_id'], $msg);
							else if($video['videoalbumable_type'] == 'event')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&event_id=' . $video['owner_id'] . '&q=videoalbums&id_album=' . $video['videoalbum_id'], $msg);				
							else if($video['videoalbumable_type'] == 'playground')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_playground=' . $video['owner_id'] . '&q=videoalbums&id_album=' . $video['videoalbum_id'], $msg);
							else if($video['videoalbumable_type'] == 'fitness')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $video['owner_id'] . '&q=photoalbums&id_album=' . $video['videoalbum_id'], $msg);
							else if($video['videoalbumable_type'] == 'shop')
								$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $video['owner_id'] . '&q=photoalbums&id_album=' . $video['videoalbum_id'], $msg);
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
	
	case 'liked':
	
		Auth::authorization();
	
		if($_REQUEST['id'] && $_REQUEST['likeable_type']){
			
			$result = $data->liked($_REQUEST['id'], $_REQUEST['likeable_type'], $user['id']);			
			$content = array("result" => "".$result."");
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case 'shared':	
	
		Auth::authorization();
	
		if($_REQUEST['id'] && $_REQUEST['shareable_type']){
			
			$result = $data->shared($_REQUEST['id'], $_REQUEST['shareable_type'], $user['id']);			
			$content = array("result" => "".$result."");
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
		
	break;
	
	case 'getcomments':
	
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
			
			core::user()->setUser_id($photoinfo['owner_id']);
			$owner = core::user()->getUserInfo();
			$owner_settings = core::user()->getUserSetting();
			
			if($photoinfo['owner_id'] != $user['id']){
				if(core::user()->permissionUser($user['id'], $owner_settings['permission_view_photo']) && $data->checkBlock($user['id'], $photoinfo['owner_id'])) $permit = 'yes';
			}
			else{
				if($settings['permission_view_photo'] != 2) $permit = 'yes';
			}			
		}
		else if($commentable_type == 'video'){
			$videoinfo = Videoalbum::getVideoInfo($id);
			
			core::user()->setUser_id($videoinfo['owner_id']);
			$owner = core::user()->getUserInfo();
			$owner_settings = core::user()->getUserSetting();
			
			if($videoinfo['owner_id'] != $user['id']){
				if(core::user()->permissionUser($user['id'], $owner_settings['permission_view_video']) && $data->checkBlock($user['id'], $videoinfo['owner_id'])) $permit = 'yes';
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
					if($author_community == 1 && Communities::checkOwnerCommunity($content_id, $user['id']) or Communities::checkAdminCommunity($content_id, $user['id'])) {
						$head = TRUE;
					}
				}
				else if($commentable_type == 'event'){
					if($author_community == 1 && Events::checkOwnerEvent($content_id, $user['id'], 'user')) {
						$head = TRUE;
					}
				}
				else $head = FALSE;			
				
				if($head){
					$avatar = Comments::getCommentAvatar($row['id_comment']);
					$name = Comments::getCommentAuthorName($row['id_comment']);
					
					switch($commentable_type){
						
						case 'team':
						
							$page_link = './?task=teams&user_id=' . $content_id;
						
						break;
						
						case 'group':
						
							$page_link = './?task=groups&user_id=' . $content_id;
							
						break;
						
						case 'event':
						
							$page_link = './?task=events&user_id=' . $content_id;
							
						break;
					}
				}else{
					$avatar = core::documentparser()->userAvatar($row);
					$name = $row['firstname'] . ' '  . $row['lastname'].'<span class="status_user' . (core::user()->checkUserOnline($row['user_id']) ? ' online' : '') . '" data-num="' . $row['user_id'] . '"></span>';
					$page_link = './?task=profile&user_id=' . $row['user_id'];
				}
				
				if($row['parent_id'] == 0){
					$html .= '<div id="message-' . $row['id_comment'] . '" data-item="' . $row['parent_id'] . '" class="message">';
					
					if ($commentable_type == 'event' && $head){
						$html .= '<div class="img-account">';
						$html .= '<img src="' . $avatar . '" alt="" class="event">';
						$html .= '</div>';
					}	
					else
						$html .= '<img src="' . $avatar . '" alt="" class="img-account">';
				
						if($row['content_id'] == $user['id']) {
						$html .= '<div class="del_mess" data-item="' . $row['id_comment'] . '"></div>';
					
						if($row['user_id'] == $user['id']){
							$html .= '<div class="del_mess" data-item="' . $row['parent_id'] . '"></div>';						
						}
					}						
				
					$html .= ' <h5 class="name"><a href="'.$page_link.'">' . $name . '</a></h5>';				
					$html .=  '<p class="data">' . $row['created'] . '</p>';
					$html .= '<p class="message-text">' . $row['content'] . '<br>';
					$html .= '<ul class="attach_image">';
				
						foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
							
							$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
							$photo_src = Photoalbum::getPhotoSmallSrc($photo);
							if(!$photo_src) continue;
							$html .= '<li>';
							$html .= '<img border="0" src="' . $photo_src . '" class="photo_big" data-num="' . $photo['photo_id'] . '">';
							$html .= '</li>';
						}		
				
					$html .= '</ul>';
					$html .= '</p>';			
					$html .= '<a id="reply-' . $row['id_comment'] . '" class="reply" data-item="' . $row['id_comment'] . '"> ' . core::getLanguage('str', 'reply'). '</a>';
					if ($user['id'] != $row['user_id']) $html .=  '<a id="tell-comment-' . $row['id_comment'] . '" class="tell" data-item="' . $row['id_comment'] . '" data-type="comment">' . Comments::getNumberTell($row['id_comment'], 'comment') . '</a>';
					$html .=  '<a id="like-comment-' . $row['id_comment'] . '" class="liked" data-item="' . $row['id_comment'] . '" data-type="comment">' . Comments::getNumberLiked($row['id_comment'], 'comment') . '</a>';
					$html .= '</div>';
				}
				else{
					$html .= '<div class="message-reply message" id="message-' . $row['id_comment'] . '" data-item="' . $row['parent_id'] . '">';
					
					if($row['content_id'] == $user['id']) {
						$html .= '<div class="del_mess" data-item="' . $row['id_comment'] . '"></div>';
					
						if($row['user_id'] == $user['id']){
							$html .= '<div class="del_mess" data-item="' . $row['parent_id'] . '"></div>';						
						}
					}
				
					$html .= '<div class="message" >';					
					$html .= '<div class="message-account"> <img src="' . $avatar . '" alt="" class="img-account">';            	
					$html .= '<h5 class="name"><a href="./?task=profile&user_id=' . $row['user_id'] . '">' . $name . '</a></h5>';
					$html .= '<p class="data">' . $row['created'] . '</p>';
					$html .= '</div>';              
					$html .= '<p class="message-reply-text">' . $row['content'] . '<br>'; 
					$html .= '<ul class="attach_image">';
				
						foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
							$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
							$photo_src = Photoalbum::getPhotoSmallSrc($photo);
							if(!$photo_src) continue;
							$html .= '<li>';
							$html .= '<img border="0" src="' . $photo_src . '" class="photo_big" data-num="' . $photo['photo_id'] . '">';
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
	
	case 'get_communities_list':
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$user_id = is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : exit();
		$type = $_REQUEST['type'] ? $_REQUEST['type'] : exit();
	
		$html = '';
		$task = ($type == 'team') ? 'teams' : 'groups' ;
	
		foreach(Communities::getMyCommunitiesList($user_id, $type, $number, $offset) as $row){
			
			$html .= '<div id="community_' .$row['community_id'] . '" class="event-item">';
			$html .= '<a class="img" href="./?task=' . $task . '&community_id=' . $row['community_id'] . '">';
			$html .= '<img border="0" alt="" src="' . core::documentparser()->communityAvatar($row) . '">';
			$html .= '</a>';
			$html .= '<div class="teg">';
			$html .= '<p><a href="./?task=' . $task . '&community_id=' . $row['community_id'] . '"> ' . $row['name'] . '</a></p>';

			 $status = Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $user['id']));			
			
			$html .= '<p>' . $row['sport_type'] . '<br> ' . $status . '<br>' . $row['place'] . '</p>';
			$html .= '<p> ' .$row['about'] . '</p>';
			$html .= '<p><i></i>'. str_replace('%MEMBERS%', Communities::countMemberCommunity($row['community_id'], 2), core::getLanguage('str', 'participants_friends')) . '</p>';
			
			if(Communities::checkOwnerCommunity($row['community_id'], $user['id'])) {
				$html .= '<a href="./?task=' . $type . '&community_id=' .$row['community_id'] . '&q=edit">' . core::getLanguage('str', 'edit') . '</a>';
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
	
	case 'get_events_list':
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$member_id = is_numeric($_REQUEST['member_id']) ? $_REQUEST['member_id'] : exit();
		$eventable_type = $_REQUEST['eventable_type'] ? $_REQUEST['eventable_type'] : exit();
		
		$html = '';
		
		foreach(Events::getMyEvents($member_id, $eventable_type, $number, $offset) as $row){
			
			$html .= '<div class="event-item"> <a href="./?task=events&event_id=' . $row['event_id'] . '" class="img"><img src="' . core::documentparser()->eventAvatar($row['cover_page']) . '" alt="" style="margin-left: -100%;"></a>';
			$html .= '<div class="teg">';
			$html .= '<p><a href="./?task=events&event_id=' . $row['event_id'] . '">' . $row['name'] . '</a></p>'; 
            $html .= ' <p>';  
			
			if($row['sport_type']) $html .= $row['sport_type'] . '<br>'; 
            if($row['place']) $html .= $row['place'] . '<br>';
			
            $html .= ' Начало: ' .core::documentparser()->mysql_russian_datetime($row['date_from']). ' в ' . $row['time_from'] . '<br>';   
               
			if($row['date_to'])	$html .= 'Окончание: ' .core::documentparser()->mysql_russian_datetime($row['date_to']). ' в ' . $row['time_to'] . '<br>'; 
                
			$html .= '</p>';	  
				
			if(Events::getEventRole(Events::getMemberShipStatus($row['event_id'], $user['id'], 'user'))) $html .= '<p>' . Events::getEventRole(Events::getMemberShipStatus($row['event_id'], $user['id'], 'user')) . '</p>';
			
			$html .= '<p><i></i>' . str_replace('%MEMBERS%', Events::countMembers($row['event_id'], 'user'), core::getLanguage('str', 'participants_friends')) . '</p>';
			
			if(Events::checkOwnerEvent($row['event_id'], $user['id'], 'user'))  
				$html .= '<a href="./?task=events&event_id='.$row['event_id'].'&q=edit">Редактировать</a>';
			
			if(Events::getEventStatus($row['event_id']) == 'continues') 
				$html .='<span>'.core::getLanguage('str', 'event_continues').'</span>';
			else if(Events::getEventStatus($row['event_id']) == 'end')	
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

	case 'getpopphotos':
	
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
	
	case 'addmessage':
	
		Auth::authorization();
	
		$receiver_id = Core_Array::getRequest('receiver_id');
		$content = htmlspecialchars(trim(Core_Array::getRequest('message')));
		$attach = Core_Array::getRequest('attach') ? explode(",", Core_Array::getRequest('attach')) : '';
		
		core::user()->setUser_id($receiver_id);
		$receiver = core::user()->getUserInfo();
		$receiver_settings = core::user()->getUserSetting();
		
		if(empty($content)) $error = core::getLanguage('error', 'empty_coomment');	
		if($data->checkBanMsgReceiver($receiver_id) === false or $data->checkDeletedMsgReceiver($receiver_id) === false)
		if(core::user()->permissionUser($receiver_id, $receiver_settings['permission_send_message']) && $data->checkBlock($user['id'], $receiver_id)) $error = core::getLanguage('error', 'message_hasnt_been_sent');
		
		if(empty($error)){
			$fields = array();
			$fields['id'] = 0;			
			$fields['sender_id'] = $user['id'];			
			$fields['receiver_id'] = $receiver_id;			
			$fields['content'] = $content;		
			$fields['created_at'] = date("Y-m-d H:i:s");			
			$fields['status'] = 0;			
			
			$insert_id = $data->addMessage($fields);
			
			if($insert_id){
				$row = $data->makeUpMessage($insert_id);				
				
				$content = array();
				$content['status'] = 1;
				$content['id'] = $insert_id;
				$content['receiver_id'] = $row['receiver_id'];				
				$content['sender_id'] = $row['sender_id'];
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
							
							$message_src = Photoalbum::getPhotoSmallSrc($message);
							if($message_src) $image .=  '<img border="0" src="'  . $message_src . '" class="photo_big"  data-num='.$message['photo_id'].'>';
							
							$image .= '</li>';
					}
					$image .= '</ul>';
				}	

				$content['image'] = $image;				
				$content['content'] = core::documentparser()->link_replace($row['content']);
				$content = json_encode($content);
				
				core::user()->setUser_id($receiver_id);
				$receiver = core::user()->getUserInfo();	
				$settings = core::user()->getUserSetting();
			
				if($settings['notification_private_messages'] != 'no' && !core::user()->checkUserOnline($receiver_id)){
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
					$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $receiver_id . '&q=messages&sel=' . $user['id'], $msg);

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
	
	case 'getmessages':	
	
		Auth::authorization();

		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();		
		$receiver_id = is_numeric($_REQUEST['receiver_id']) ? $_REQUEST['receiver_id'] : exit();
		
		$arr_messages = $data->getMessagesListAjax($offset, $number, $user['id'], $receiver_id);		
		
		foreach($arr_messages as $row){
			
			$image = '<ul class="attach_image">';
				
				foreach(Attach::getAttachList($row['id'], 'message') as $row2){
					$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
					$photo_src = Photoalbum::getPhotoSmallSrc($photo);
					if(!$photo_src) continue;
					$image .= '<li>';
					$image .= '<img border="0" src="' . $photo_src . '" class="photo_big" data-num="' . $photo['photo_id'] . '">';
					$image .= '</li>';
				}
			$image.='</ul>';
			$rows[] = array(
			"id_message" => $row['id'],
			"avatar" => core::documentparser()->userAvatar($row),
			"sender_id" => $row['sender_id'],
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

	case 'get_new_messages':

		Auth::authorization();

		$last_id = is_numeric(Core_Array::getRequest('last_id')) ? (int)Core_Array::getRequest('last_id') : 0;
		$receiver_id = is_numeric(Core_Array::getRequest('receiver_id')) ? (int)Core_Array::getRequest('receiver_id') : 0;
		$arr_messages = $data->getNewMessagesAfter($last_id, $user['id'], $receiver_id);
		$rows = array();

		foreach($arr_messages as $row){

			$image = '<ul class="attach_image">';

				foreach(Attach::getAttachList($row['id'], 'message') as $row2){
					$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
					$photo_src = Photoalbum::getPhotoSmallSrc($photo);
					if(!$photo_src) continue;
					$image .= '<li>';
					$image .= '<img border="0" src="' . $photo_src . '" class="photo_big" data-num="' . $photo['photo_id'] . '">';
					$image .= '</li>';
				}
			$image .= '</ul>';

			$rows[] = array(
				"id_message" => $row['id'],
				"avatar" => core::documentparser()->userAvatar($row),
				"sender_id" => $row['sender_id'],
				"receiver_id" => $row['receiver_id'],
				"firstname" => $row['firstname'],
				"lastname" => $row['lastname'],
				"created" => $row['created'],
				"status" => $row['status'],
				"image" => $image,
				"content" => core::documentparser()->link_replace($row['content'])
			);

			if($receiver_id > 0 && $row['receiver_id'] == $user['id'] && $row['status'] == 0) {
				core::database()->update(array('status' => 1), core::database()->getTableName('messages'), "id=" . $row['id']);
			}
		}

		$content = array(
			"item" => $rows,
			"count" => core::user()->MessageNotification()
		);

		core::documentparser()->showJSONContent(json_encode($content));

	break;
	
	case 'get_last_message':
	
		Auth::authorization();
	
		$receiver_id = is_numeric($_REQUEST['receiver_id']) ? $_REQUEST['receiver_id'] : exit();
		$last_message = $data->getLastMessage($receiver_id, $user['id']);
			
		foreach($last_message as $row){
			
			$image = '<ul class="attach_image">';
				
				foreach(Attach::getAttachList($row['id'], 'message') as $row2){
					$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
					$photo_src = Photoalbum::getPhotoSmallSrc($photo);
					if(!$photo_src) continue;
					$image .= '<li>';
					$image .= '<img border="0" src="' . $photo_src . '" class="photo_big" data-num="' . $photo['photo_id'] . '">';
					$image .= '</li>';
				}
			$image.='</ul>';
			
			$rows[] = array(
			"id_message" => $row['id'],
			"avatar" => core::documentparser()->userAvatar($row),
			"sender_id" => $row['sender_id'],
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
	
	case 'add_as_friend':
	
		Auth::authorization();
	
		if($_REQUEST['user_id'] && $_REQUEST['user_id'] != $user['id']){
			$friend_id = core::database()->escape((int)Core_Array::getRequest('user_id'));			
			$result = $data->changeFriendsStatus($friend_id, $user['id'], 0);

			core::user()->setUser_id($friend_id);
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
				$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $user['id'];
			
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
	
	case 'accept_friendship':
	
		Auth::authorization();
	
		if($_REQUEST['user_id'] && $_REQUEST['user_id'] != $user['id']){
			$friend_id = core::database()->escape((int)Core_Array::getRequest('user_id'));

			core::user()->setUser_id($friend_id);
			$friend = core::user()->getUserInfo();	
			$settings = core::user()->getUserSetting();				
			
			$result = $data->changeFriendsStatus($user['id'], $friend_id, 1);
			
			if($result){
				if($settings['notification_friends_request'] != 'no' && !core::user()->checkUserOnline($friend_id)){
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
					$message['link_to_profile'] = 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $user['id'];
			
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
	
	case 'block_user':
	
		Auth::authorization();

		if($_REQUEST['user_id'] && $_REQUEST['user_id'] != $user['id']){
			$user_id = core::database()->escape((int)Core_Array::getRequest('user_id'));
			
			$result = $data->blockUser($user['id'], $user_id);

			$content = array("status" => 'success');		
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case 'unblock_user':
	
		Auth::authorization();

		if($_REQUEST['user_id'] && $_REQUEST['user_id'] != $user['id']){
			$user_id = core::database()->escape((int)Core_Array::getRequest('user_id'));
			
			$result = $data->unblockUser($user['id'], $user_id);		

			$content = array("status" => 'success');		
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case 'remove_friend':
	
		Auth::authorization();
	
		if($_REQUEST['user_id']){
			$friend_id = core::database()->escape((int)Core_Array::getRequest('user_id'));
			
			$result = $data->removeFriend($user['id'], $friend_id);
			
			$content = array("status" => 'success');
			
			core::documentparser()->showJSONContent(json_encode($content));
		}
	
	break;
	
	case 'search_city':
	
		$city = trim(Core_Array::getRequest('city'));
		
		foreach( Places::searchCity($city) as $row){			
			$rows[] = array(
			"id" => $row['id'],
			'name' => $row['name']);		
		}
				
		$content = '{"item":'.json_encode($rows).'}';
			
		core::documentparser()->showJSONContent($content);
	
	break;
	
	case 'search_sport_types':
	
		$sport_types = trim(Core_Array::getRequest('sport_types'));
	
		foreach( Sport::searchSportTypes($sport_types) as $row){			
			$rows[] = array(
			"id" => $row['id'],
			'name' => $row['name']);		
		}
				
		$content = '{"item":'.json_encode($rows).'}';
			
		core::documentparser()->showJSONContent($content);
	
	break;	
	
	case 'getpossiblefriends':
	
		Auth::authorization();
	
		foreach(Friends::getPossibleFriendsList($user['id'], 6) as $row){
			$rows[] = array(
			"user_id" => $row['user_id'],			
			"avatar" => core::documentparser()->userAvatar($row),
			"firstname" => $row['firstname'],			
			"lastname" => $row['lastname'],
			"city" => $row['city'],
			"status_user" => core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline',
			"sender_id" => $user['id']);
		}		
		
		$content = '{"item":'.json_encode($rows).'}';		
	
		core::documentparser()->showJSONContent($content);
	
	break;
	
	case 'getphotoinfo':
	
		$photo_id = is_numeric(Core_Array::getRequest('photo_id')) ? Core_Array::getRequest('photo_id') : exit();
		$row = Photoalbum::getPhotoInfo($photo_id);		
		$content = array();
		
		if($row){
			$content['status'] = 1;
			$content['photoalbum_id'] = $row['photoalbum_id'];		
			$content['small_photo'] = core::documentparser()->photogalleryPic($row['small_photo'], $row['photoalbumable_type']);
			$content['photo'] = core::documentparser()->photogalleryPic($row['photo'], $row['photoalbumable_type']);		
			$content['description'] = $row['description'];		
			$content['owner_id'] = $row['owner_id'];
			$content['firstname'] = $row['firstname'];
			$content['lastname'] = $row['lastname'];
			$content['liked'] = Photoalbum::getNumberLiked($photo_id);		
			$content['tell'] = Photoalbum::getNumberTell($photo_id);		
			$content['created'] = core::documentparser()->mysql_russian_date($row['created']);
		}
		else $content['status'] = 0;
		
		core::documentparser()->showJSONContent(json_encode($content));
		
	break;
	
	case 'getvideoinfo':
	
		$video_id = is_numeric(Core_Array::getRequest('video_id')) ? Core_Array::getRequest('video_id') : exit();
		$row = Videoalbum::getVideoInfo($video_id);
		$content = array();
		
		if($row){
			$content['status'] = 1;
			$content['video_id'] = $row['video_id'];		
			$content['description'] = $row['description'];
			$content['owner_id'] = $row['owner_id'];
			$content['firstname'] = $row['firstname'];
			$content['lastname'] = $row['lastname'];
			$content['liked'] = Videoalbum::getNumberLiked($video_id);		
			$content['tell'] = Videoalbum::getNumberTell($video_id);
			$content['views'] = Videoalbum::getNumberVideoViews($video_id);			
			$content['thumb'] = Videoalbum::getThumb($video_id);			
			$content['video'] = core::documentparser()->getVideoPlayer($row['provider'], $row['video']);		
			$content['created'] = core::documentparser()->mysql_russian_date($row['created']);
			
			Videoalbum::countView(Core_Array::getRequest('video_id'), $user['id']);
		}
		else $content['status'] = 0;
		
		core::documentparser()->showJSONContent(json_encode($content));		
	
	break;
	
	case 'edit_profile':
	
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
		if($_REQUEST['spoort_level']) $fields['sport']['sport_level_id'] = Core_Array::getRequest('spoort_level');		
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
	
	case 'adduseravatar':
	
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
	
	case 'crop':
	
		Auth::authorization();
	
		$src = $_SERVER['DOCUMENT_ROOT'] . '/' . $_POST['file'];	
		
		$img = new abeautifulsite\SimpleImage();
		$img->load($src)->crop($_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'])->auto_orient()->save($src);

		$content = array("result" => 'success', "path" => $src);
		core::documentparser()->showJSONContent(json_encode($content));	
	
	break;
	
	case 'cropcover':
	
		Auth::authorization();
	
		$src = $_SERVER['DOCUMENT_ROOT'] . $_POST['file'];		
		$img = new abeautifulsite\SimpleImage();	 
		$img->load($src)->crop($_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'])->auto_orient()->save($src);
		$content = array("result" => 'success', "path" => $src);
		
		core::documentparser()->showJSONContent(json_encode($content));	
	
	break;	

	case 'add_photo_ajax':
	
		Auth::authorization();
		
		$photoalbum_id = $_REQUEST['categorie'];
		$description = trim($_REQUEST['description']);
		$photoalbumable_type = $_REQUEST['photoalbumable_type'];		
		$path = core::documentparser()->getPhotogalleryPath($photoalbumable_type);			
		$message = Photoalbum::uploadHandlePup(32, $path, 0, $photoalbum_id, $description, $user['id']);
		
		core::documentparser()->showJSONContent(json_encode($message));	
		
	break;	
	
	case 'add_photo_ajax_attach':
	
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
			$fields['owner_id'] = $user['id'];		
				
			Photoalbum::createAlbum($fields);	
		}
		
		$arr_option_list = Photoalbum::getAlbumList($user['id'], $photoalbumable_type);
		$path = core::documentparser()->getPhotogalleryPath($photoalbumable_type);			
		$message = Photoalbum::uploadHandlePup(32, $path, 0,  $arr_option_list[0]['id'], $description, $user['id']);		
		$content = array("num" => $num, "message" => $message);
		
		core::documentparser()->showJSONContent(json_encode($content));
		
		exit;
		
	break;


	case 'uploadcover':
	
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
	
	case 'uploadavatar':
	
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
	
	case 'removecomment':
	
		Auth::authorization();
		
		$id = is_numeric($_REQUEST['id_comment']) ? $_REQUEST['id_comment'] : exit();
		
		if(Comments::removeComment($id) && $data->removeShare($id, 'comment'))
			$content = array("result" => 'success');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;	
	
	case 'cleardialog':
	
		Auth::authorization();
	
		$id = is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : exit();
		
		$result = $data->clearDialog($user['id'], $id);
		
		if($result)
			$content = array("result" => 'success');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
		
	break;	
	
	case 'change_event_memberstatus':
	
		Auth::authorization();
	
		$event_id = is_numeric($_REQUEST['event_id']) ? $_REQUEST['event_id'] : exit();
		$status = is_numeric($_REQUEST['status']) ? $_REQUEST['status'] : exit();
	
		$result = Events::changeMemberStatus($event_id, $user['id'], 'user', $status);		
	
		if($result)
			$content = array("result" => 'success');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));

	break;
	
	case 'changememberstatus':
	
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
	
	case 'get_photos_list':
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$owner_id = is_numeric($_REQUEST['owner_id']) ? $_REQUEST['owner_id'] : exit();
		$type = $_REQUEST['type'] ? $_REQUEST['type'] : exit();	
	
		$arr_photos = Photoalbum::getPhotosList($owner_id, $type, $number, $offset);		
		
		if($arr_photos){
			
			$html = ''; 		
			
			foreach($arr_photos as $row){
				$small_photo = core::documentparser()->photogalleryPic($row['small_photo'], $type);
				$big_image = core::documentparser()->photogalleryPic($row['photo'], $type);
				
				if($small_photo){
					$html .= '<div class="hov" id="photo-block-' . $row['photo_id'] . '">'; 
					$html .= '<a class="photo_big" title="' . $row['description'] . '" href="' . $big_image . '" data-lightbox="roadtrip" data-num=' . $row['photo_id'] . '> <img src="' . $small_photo . '" alt="">';
					$html .= '<div class="transparent"></div>';
					$html .= '</a>';

					if($row['owner_id'] == $user['id']) {
						$html .= '<span class="icons-hid"><i id="my-video-' . $row['photo_id'] . '" class="remove_pic" id="' . $row['photo_id'] . '" data-item="' . $row['photo_id'] . '"><img src="templates/images/icon-krest.png" alt=""></i></span>';
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

	case 'get_videos_list':
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$owner_id = is_numeric($_REQUEST['owner_id']) ? $_REQUEST['owner_id'] : exit();
		$type = $_REQUEST['type'] ? $_REQUEST['type'] : exit();
		
		$arr_videos = Videoalbum::getVideosList($owner_id, $type, $number, $offset);
		
		if($arr_videos){
			
			$html = '';
			
			foreach($arr_videos as $row){
				$thumb = core::documentparser()->getThumb($row['provider'], $row['video']);
				
				$html .= '<div id="video-block-' . $row['video_id'] . '" class="hov">';
				$html .= '<div class="video-box"><img src="' . $thumb . '" alt="" class="video_prev" data-num="' . $row['video_id'] . '">';
				$html .= '</div>'; 
				$html .= '<span class="icons-hid"><i id="my-video-' . $row['video_id'] . '" class="remove_video" data-item="' . $row['video_id'] . '"> <img  src="templates/images/icon-krest.png" alt=""></i></span> ';
				$html .= '<span class="video-capt"><i></i>' . Videoalbum::getNumberVideoViews($row['video_id']) . '</span>';
				$html .= '</div>';
			}			
			
			$content = array();
			$content['status'] = 1;				
			$content['html'] = $html;	
			$content = json_encode($content);
			
			core::documentparser()->showJSONContent($content);
		}	
	
	break;
	
	case 'get_album_photos':
	
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

					if($row['owner_id'] == $user['id']) {
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
	
	case 'get_album_videos':
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$id_album = is_numeric($_REQUEST['id_album']) ? $_REQUEST['id_album'] : exit();
		
		$arr_videos = Videoalbum::getVideosAlbumList($id_album, $number, $offset);
		
		if($arr_videos){
			
			$html = '';
		
			foreach($arr_videos as $row){
				$html .= '<div id="video-block-' . $row['video_id'] . '" class="hov">';
				$html .= '<div class="video-box"><img src="' . core::documentparser()->getThumb($row['provider'], $row['video']) . '" alt="" class="video_prev" data-num="' . $row['video_id'] . '">';
				$html .= '</div>';
				$html .= '<span class="icons-hid"><i id="my-video-' . $row['video_id'] . '" class="remove_video" data-item="' . $row['video_id'] . '" data-tooltip="Удалить видео"> <img  src="templates/images/icon-krest.png" alt=""></i></span> ';
				$html .= '<span class="video-capt"><i></i>' . Videoalbum::getNumberVideoViews($row['video_id']) . '</span>';
				$html .= '</div>';
			}
			
			$content = array();
			$content['status'] = 1;				
			$content['html'] = $html;	
			$content = json_encode($content);
			
			core::documentparser()->showJSONContent($content);
		}

	break;
	
	case 'send_community_invitation':
	
		Auth::authorization();
	
		$community_id = is_numeric($_REQUEST['community_id']) ? $_REQUEST['community_id'] : exit();		
		$community = Communities::getCommunityInfo($community_id);
		
		if($community['type'] == 'group') {
			$mailnotification = $data->getMailNotification(9);
		}
		else if($community['type'] == 'team'){
			$mailnotification = $data->getMailNotification(10);
		}		
		
		foreach($data->sendCommunityInvitation($community_id, $user['id']) as $row){
		
			if(Communities::change_community_role($community_id, $row['user_id'], 5)){
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
				$msg = str_replace('%PAGE%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $user['id'], $msg);			
				$msg = str_replace('%NAME%', $community['name'], $msg);				
			
				if($community['type'] == 'team')  
					$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&community_id=' . $community_id, $msg);
				else if($community['type'] == 'group')
					$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&community_id=' . $community_id, $msg);			
				
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
	
	case 'send_event_invitation':
	
		Auth::authorization();
	
		$event_id = is_numeric($_REQUEST['event_id']) ? $_REQUEST['event_id'] : exit();
	
		$event = Events::getEventInfo($event_id);
		
		$mailnotification = $data->getMailNotification(11);
		
		foreach($data->sendEventInvitation($event_id, 'user', $user['id']) AS $row){
			$result = Events::change_member_role($event_id, $row['user_id'], 'user', 4);
			
			if($result){
				$message = array();
				
				$event = Events::getEventInfo($event_id);
				
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
				$msg = str_replace('%PAGE%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&user_id=' . $user['id'], $msg);			
				$msg = str_replace('%NAME%', $event['name'], $msg);				
				$msg = str_replace('%LINK%', 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&event_id=' . $event_id, $msg);
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
	
	case 'check_user_online':
	
		$user_id = is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : exit();
		
		if(core::user()->checkUserOnline($user_id))
			$content = array("status" => 'online');
		
		else
			$content = array("status" => 'offline');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
	
	case 'news_parse':
	
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
				$news['created_at'][] = $pubDate;
			}
		}
		
		$result = Rss::addNews($news);
		
		if($result)
			$content = array("result" => 'success');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
		
	break;
	
	case 'get_usernews_list':
	
		Auth::authorization();
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		
		$arrs = array();
		foreach(Comments::getCommentEvent($user['id'], $number, $offset) as $row){
			$event_id = $row['event_id'];
			$id_author = $row['id_author'];
			
			if (Events::checkOwnerEvent($event_id, $id_author, 'user'))	{
				$publication_name = $row['name'];
				$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_left_comment'));  
				$publication_msg .= '<ul class="attach_image">';
				
					foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
						$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
						$photo_src = Photoalbum::getPhotoSmallSrc($photo);
						if($photo_src) $publication_msg .= '<li><img border="0" src="' . $photo_src . '" class="photo_big" data-num="'.$photo['photo_id'].'" /></li>';
					}
				
				$publication_msg .= '</ul>'	;
				$arrs[] = array('name' => $publication_name,'type' => 'event','id_author' => $event_id, 'msg' => $publication_msg, 'avatar' => core::documentparser()->eventAvatar($row['cover_page']), 'content_id' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
			}
		}
		
		foreach(Comments::getCommentCommunity($user['id'], $number, $offset) as $row){
			$community_id = $row['community_id'];
			$id_author = $row['id_author'];
			
			if(Communities::checkOwnerCommunity($community_id, $id_author) or Communities::checkAdminCommunity($community_id, $id_author)){
				$publication_name = $row['name'];
				$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_left_comment'));  
				$publication_msg .= '<ul class="attach_image">';
				
					foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
						$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
						$photo_src = Photoalbum::getPhotoSmallSrc($photo);
						if($photo_src) $publication_msg .= '<li><img border="0" src="' . $photo_src . '" class="photo_big" data-num="'.$photo['photo_id'].'" /></li>';
					}
				
				$publication_msg .= '</ul>'	;
				$arrs[] = array('name' => $publication_name,'type' => $row['commentable_type'], 'id_author' => $community_id, 'msg' => $publication_msg, 'avatar' => core::documentparser()->communityAvatar($row), 'content_id' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
			}
		}
		
		foreach(Comments::getUserComment($user['id'], $number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['user_id'];
			$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_left_comment'));  
			$publication_msg .= '<ul class="attach_image">';
			
				foreach(Attach::getAttachList($row['content_id'], 'comment') as $row2){
					$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
					$photo_src = Photoalbum::getPhotoSmallSrc($photo);
					if($photo_src) $publication_msg .= '<li><img border="0" src="' . $photo_src . '" class="photo_big" data-num="'.$photo['photo_id'].'" /></li>';
				}
			
			$publication_msg .= '</ul></div></div>'	;
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}

		foreach(Videoalbum::getUserPublishVideo($user['id'], $number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['user_id'];
			$publication_msg = str_replace('%VIDEO%', core::documentparser()->getThumb($row['provider'], $row['video']), core::getLanguage('str', 'useraction_published_video')); 
			$publication_msg = str_replace('%ID%', $row['video_id'], $publication_msg); 	
	
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $row['video_id'], 'likeable_type' => 'video', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}

		foreach(Photoalbum::getUserPublishPhoto($user['id'], $number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['user_id'];
			$publication_msg = str_replace('%PHOTO%', core::documentparser()->photogalleryPic($row['small_photo'], $row['photoalbumable_type']), core::getLanguage('str', 'useraction_added_photo')); 
			$publication_msg = str_replace('%ID%', $row['photo_id'], $publication_msg); 
	
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $row['photo_id'], 'likeable_type' => 'photo', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}		
		
		foreach(core::user()->getMyFriendsLastFriend($number, $offset) as $row){
			$publication_name = $row['lastname'] . " " . $row['firstname'];
			$publication_id_author = $row['user_id'];
	
			$userfriend = $row['friend_lastname'] . " " . $row['friend_firstname'];
	
			$publication_msg = str_replace('%USERFRIEND%', $userfriend, core::getLanguage('str', 'useraction_make_friends')); 
			$publication_msg = str_replace('%ID_FRIEND%', $row['friend_id'], $publication_msg); 
	
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => '', 'likeable_type' => '', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}		

		foreach(Comments::getUserGetVideoComment($user['id'], $number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['user_id'];
			$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_commented_video'));  
			$video = Videoalbum::getVideoInfo($row['content_id']);
		
			if($video['owner_id']){

				$avatar = core::documentparser()->userAvatar($video);
				$date = core::documentparser()->mysql_russian_date($row['added']);
				$author_name = $video['firstname'] . " " . $video['lastname'];	
				$publication_msg = str_replace('%VIDEO%', core::documentparser()->getThumb($video['provider'], $video['video']), $publication_msg);
				$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);
				$publication_msg = str_replace('%ID_AUTHOR%', $video['owner_id'], $publication_msg);
				$publication_msg = str_replace('%ID%', $video['video_id'], $publication_msg);
				$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
				$publication_msg = str_replace('%DATE%', $date , $publication_msg); 
			}

			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		} 

		foreach(Comments::getUserGetPhotoComment($user['id'], $number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['user_id'];
			$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_commented_photo'));
	
			$photo = Photoalbum::getPhotoInfo($row['content_id']);
		
			if($photo['owner_id']){
				$avatar = core::documentparser()->userAvatar($photo);
				$date = core::documentparser()->mysql_russian_date($row['added']);
				$author_name = $photo['firstname'] . " " . $photo['lastname'];	
			
				$publication_msg = str_replace('%PHOTO%', core::documentparser()->photogalleryPic($photo['small_photo'], $photo['photoalbumable_type']), $publication_msg);
				$publication_msg = str_replace('%ID_AUTHOR%',$photo['owner_id'], $publication_msg); 
				$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	
				$publication_msg = str_replace('%ID%', $row['photo_id'], $publication_msg); 
				$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
				$publication_msg = str_replace('%DATE%', $date , $publication_msg); 
			}
	
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);	
		}

		foreach(core::user()->getUserFriendsLiked($number, $offset) as $row){
			$publication_name =  $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['user_id'];	
			$content_id = $row['content_id'];
			$pubmsg['publication_date'][] = core::documentparser()->mysql_russian_date($row['added']);
			$pubmsg['timeorder'][] = $row['timeorder'];
	
			if($row['likeable_type'] == 'comment'){
				$comment = Comments::getCommentInfo($row['content_id']);
		
				if($comment['user_id']){
					$author_name = $comment['firstname'] . " " . $comment['lastname'];
					$avatar = core::documentparser()->userAvatar($comment);
					$link = './?task=profile&user_id='.$comment['user_id'];
					
					if ($comment['commentable_type']=='group'||$comment['commentable_type']=='team'){

						$community = Communities::getCommunityInfo($comment['content_id']);
						
						if(Communities::checkOwnerCommunity($comment['content_id'], $comment['user_id']) or Communities::checkAdminCommunity($comment['content_id'], $comment['user_id'])){
							$author_name = $community['name'];	
							$avatar = core::documentparser()->communityAvatar($community);
							$link = './?task='.$community['type'].'s&community_id='.$comment['content_id'];
						}
					}
					
					if ($comment['commentable_type']=='event'){
						$event = Events::getEventInfo($comment['content_id']);
						
						if (Events::checkOwnerEvent($comment['content_id'], $comment['user_id'], 'user')){
							$author_name = $event['name'];	
							$avatar = core::documentparser()->eventAvatar($event['cover_page']);
							$link = './?task=events&event_id='.$comment['content_id'];
						}
					}
					$date = core::documentparser()->mysql_russian_date($row['added']);
		
					$publication_msg = str_replace('%MSG%', $comment['content'], core::getLanguage('str', 'useraction_liked_post'));
					$publication_msg = str_replace('%LINK%', $link, $publication_msg); 
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg);
					$publication_msg .= '<ul class="attach_image">';
					
						foreach(Attach::getAttachList($row['content_id'], 'comment') as $row2){
							$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
							$photo_src = Photoalbum::getPhotoSmallSrc($photo);
							if($photo_src) $publication_msg .= '<li><img border="0" src="' . $photo_src . '" class="photo_big" data-num="'.$photo['photo_id'].'" /></li>';
						}
					
					$publication_msg .= '</ul></div></div>'	; 
				}	
			}	
			else if($row['likeable_type'] == 'video'){
				$video = Videoalbum::getVideoInfo($row['content_id']);
		
				if($video['owner_id']){
					$avatar = core::documentparser()->userAvatar($video);
					$date = core::documentparser()->mysql_russian_date($row['added']);
					$author_name = $video['firstname'] . " " . $video['lastname'];	
					$publication_msg = str_replace('%VIDEO%', core::documentparser()->getThumb($video['provider'], $video['video']), core::getLanguage('str', 'useraction_liked_video'));
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);
					$publication_msg = str_replace('%ID_AUTHOR%', $video['owner_id'], $publication_msg);
					$publication_msg = str_replace('%ID%', $video['video_id'], $publication_msg);
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg);  
				}	
			}	
			else if($row['likeable_type'] == 'photo'){	
				$photo = Photoalbum::getPhotoInfo($row['content_id']);
		
				if($photo['owner_id']){
					$avatar = core::documentparser()->userAvatar($photo);
					$date = core::documentparser()->mysql_russian_date($row['added']);
					$author_name = $photo['firstname'] . " " . $photo['lastname'];	
			
					$publication_msg = str_replace('%PHOTO%', core::documentparser()->photogalleryPic($photo['small_photo'], $photo['photoalbumable_type']), core::getLanguage('str', 'useraction_liked_photo'));
					$publication_msg = str_replace('%ID_AUTHOR%',$photo['owner_id'], $publication_msg); 
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	
					$publication_msg = str_replace('%ID%', $content_id, $publication_msg); 
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg); 
				}		
			}	
	
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $content_id, 'likeable_type' => $row['likeable_type'], 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}
		foreach(core::user()->getUserShare($number, $offset) as $row){
			$publication_name = $row['firstname'] . " " . $row['lastname'];
			$publication_id_author = $row['user_id'];	
			$content_id = $row['content_id'];
			$pubmsg['publication_date'][] = core::documentparser()->mysql_russian_date($row['added']);
			$pubmsg['timeorder'][] = $row['timeorder'];	
			
			if($row['shareable_type'] == 'comment'){
				$comment = Comments::getCommentInfo($row['content_id']);
				
				if($comment['user_id']){
					$author_name = $comment['firstname'] . " " . $comment['lastname'];
					$avatar = core::documentparser()->userAvatar($comment);
					$link = './?task=profile&user_id='.$comment['user_id'];
					
					if ($comment['commentable_type']=='group'||$comment['commentable_type']=='team'){
						$community = Communities::getCommunityInfo($comment['content_id']);
						
						if(Communities::checkOwnerCommunity($comment['content_id'], $comment['user_id']) or Communities::checkAdminCommunity($comment['content_id'], $comment['user_id'])){
							$author_name = $community['name'];	
							$avatar = core::documentparser()->communityAvatar($community);
							$link = './?task='.$community['type'].'s&community_id='.$comment['content_id'];
						}
					}
					if ($comment['commentable_type'] == 'event'){
						$event = Events::getEventInfo($comment['content_id']);
						
						if (Events::checkOwnerEvent($comment['content_id'], $comment['user_id'], 'user')){
							$author_name = $event['name'];	
							$avatar = core::documentparser()->eventAvatar($event['cover_page']);
							$link = './?task=events&event_id='.$comment['content_id'];
						}
					}
					$date = core::documentparser()->mysql_russian_date($row['added']);		
				
					$publication_msg = str_replace('%MSG%', $comment['content'], core::getLanguage('str', 'useraction_shared_post'));
					$publication_msg = str_replace('%LINK%', $link, $publication_msg); 
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	 
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg);
					$publication_msg .= '<ul class="attach_image">';
					
						foreach(Attach::getAttachList($row['content_id'], 'comment') as $row2){
							$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
							$photo_src = Photoalbum::getPhotoSmallSrc($photo);
							if($photo_src) $publication_msg .= '<li><img border="0" src="' . $photo_src . '" class="photo_big" data-num="'.$photo['photo_id'].'" /></li>';
						}
					
					$publication_msg .= '</ul>'	;
					$publication_msg .= '</div></div>';
				}	
			}	
			else if($row['shareable_type'] == 'video'){
				$video = Videoalbum::getVideoInfo($row['content_id']);
				$avatar = core::documentparser()->userAvatar($video);
				$date = core::documentparser()->mysql_russian_date($row['added']);	
				
				if($video['owner_id']){
					$author_name = $video['firstname'] . " " . $video['lastname'];	
					$publication_msg = str_replace('%VIDEO%', core::documentparser()->getThumb($video['provider'], $video['video']), core::getLanguage('str', 'useraction_shared_video'));
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);
					$publication_msg = str_replace('%ID_AUTHOR%', $video['owner_id'], $publication_msg);
					$publication_msg = str_replace('%ID%', $video['video_id'], $publication_msg); 	 
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg);		
				}	
			}	
			else if($row['shareable_type'] == 'photo'){	
				$photo = Photoalbum::getPhotoInfo($row['content_id']);
				$avatar = core::documentparser()->userAvatar($photo);
				$date = core::documentparser()->mysql_russian_date($row['added']);	
				
				if($photo['owner_id']){
					$author_name = $photo['firstname'] . " " . $photo['lastname'];	
					$publication_msg = str_replace('%PHOTO%', core::documentparser()->photogalleryPic($photo['small_photo'], $photo['photoalbumable_type']), core::getLanguage('str', 'useraction_shared_photo'));
					$publication_msg = str_replace('%ID_AUTHOR%',$photo['owner_id'], $publication_msg); 
					$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	
					$publication_msg = str_replace('%ID%', $content_id, $publication_msg);  	 
					$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
					$publication_msg = str_replace('%DATE%', $date , $publication_msg);
				}		
			}	
			
			$arrs[] = array('name' => $publication_name, 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $content_id, 'likeable_type' => $row['shareable_type'], 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}
		
		if($arrs){
		
			$html = '';

			foreach(core::documentparser()->customMultiSort($arrs, 'timeorder') as $row){
				$html .= '<div class="news-block-item" data-toggle="modal" data-target="#second-post">';
				$html .= '<div class="news-block-head">';
				if ($row['type']=='user'){
					$html .= '<a href="./?task=profile&user_id=' . $row['id_author'] . '"><div class="head-img"><img src="' . $row['avatar'] . '" alt=""></div></a>';
					$html .= '<a href="./?task=profile&user_id=' . $row['id_author'] . '"><p class="head-topic">' . $row['name'] . '<span class="status_user" data-num="' . $row['id_author'] . '"></span></p></a>';	
				}
				else if ($row['type']=='event'){
					$html .= '<a href="./?task=events&event_id=' . $row['id_author'] . '"><div class="head-img"><img src="' . $row['avatar'] . '" alt=""></div></a>';
					$html .= '<a href="./?task=events&event_id=' . $row['id_author'] . '"><p class="head-topic">' . $row['name'] . '</p></a>';
				}
				else{
					$html .= '<a href="./?task='.$row['type'].'s&community_id=' . $row['id_author'] . '"><div class="head-img"><img src="' . $row['avatar'] . '" alt=""></div></a>';
					$html .= '<a href="./?task='.$row['type'].'s&community_id=' . $row['id_author'] . '"><p class="head-topic">' . $row['name'] . '</p></a>';
				}

				$html .= '<p class="data">' . $row['publication_date'] . '</p>';			
				$html .= '<div class="clearfix"></div>';				
				$html .= '</div>';				
				$html .= '<div class="news-block-content">';				
				$html .= '<div class="article nov">';				
				$html .= $row['msg'];			
				$html .= '</div>';	
				
				if($row['likeable_type']) $html .= ' <a id="like-' . $row['likeable_type'] . '-' . $row['content_id'] . '" class="liked" data-item="' . $row['content_id'] . '" data-type="' . $row['likeable_type'] . '">' . Comments::getNumberLiked($row['content_id'], $row['likeable_type']) . '</a>';				
				
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
	
	case 'send_message':
	
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
	
	case 'block_community_user':
	
		Auth::authorization();
	
		$community_id = is_numeric($_REQUEST['community_id']) ? $_REQUEST['community_id'] : exit();
		$user_id = is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : exit();
		
		if(Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id']))
			if(Communities::change_community_role($community_id, $user_id, 4))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));			
	
	break;
	
	case 'unblock_community_user':
	
		Auth::authorization();
	
		$community_id = is_numeric($_REQUEST['community_id']) ? $_REQUEST['community_id'] : exit();
		$user_id = is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : exit();
		
		if(Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id']))
			if(Communities::remove_community_role($community_id, $user_id))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));		
		
	break;

	case 'add_community_administrator':
	
		Auth::authorization();
	
		$community_id = is_numeric($_REQUEST['community_id']) ? $_REQUEST['community_id'] : exit();
		$user_id = is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : exit();
		
		if(Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id']))
			if(Communities::change_community_role($community_id, $user_id, 3))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
	
	case 'approve_community_user':
	
		Auth::authorization();
	
		$community_id = is_numeric($_REQUEST['community_id']) ? $_REQUEST['community_id'] : exit();
		$user_id = is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : exit();
		
		if(Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id']))
			if(Communities::change_community_role($community_id, $user_id, 2))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
	
	case 'remove_community_administrator':
	
		Auth::authorization();
	
		$community_id = is_numeric($_REQUEST['community_id']) ? $_REQUEST['community_id'] : exit();
		$user_id = is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : exit();
		
		if(Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id']))
			if(Communities::remove_community_role($community_id, $user_id))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));		
		
	break;

	case 'search_event':
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$member_id = is_numeric($_REQUEST['member_id']) ? $_REQUEST['member_id'] : exit();
		$eventable_type = !empty($_REQUEST['eventable_type']) ? $_REQUEST['eventable_type'] : exit();
		
		$html = '';
		
		foreach(Events::getSearchEventsList($member_id, $eventable_type, $number, $offset) as $row){
			$html .= '<div class="event-item"> <a href="./?task=events&event_id=' . $row['event_id'] . '" class="img"><img src="' . core::documentparser()->eventAvatar($row['cover_page']) . '" alt="" style="margin-left: -100%;"></a>';
            $html .= '<a class="addEvent" data-tooltip="Присоединиться" data-item="'.$row['event_id'].'" data-status="1"><img src="./templates/images/icon-ok.png"/></a>';
			$html .= '<div class="teg">';
			$html .= '<p><a href="./?task=events&event_id=' . $row['event_id'] . '">' . $row['name'] . '</a></p>'; 
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
				
			if(Events::getEventRole(Events::getMemberShipStatus($row['event_id'], $user['id'], 'user'))) $html .= '<p>' . Events::getEventRole(Events::getMemberShipStatus($row['event_id'], $user['id'], 'user')) . '</p>';
			
			if(Events::getEventStatus($row['event_id']) == 'continues') 
				$status = core::getLanguage('str', 'event_continues');			
			else if(Events::getEventStatus($row['event_id']) == 'end')	
				$status = core::getLanguage('str', 'event_completed');		
			
			$html .= '<p><i></i>' . str_replace('%MEMBERS%', Events::countMembers($row['event_id'], 'user'), core::getLanguage('str', 'participants_friends')) . '</p>';
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
	
	case 'change_event_community_status':

		Auth::authorization();
	
		$event_id = is_numeric($_REQUEST['event_id']) ? $_REQUEST['event_id'] : exit();
		$status = is_numeric($_REQUEST['status']) ? $_REQUEST['status'] : exit();
		$community_id = is_numeric($_REQUEST['community_id']) ? $_REQUEST['community_id'] : exit();
		
		$community = Communities::getCommunityInfo($community_id);	
		
		if(Communities::checkOwnerCommunity($community_id, $user['id']) or Communities::checkAdminCommunity($community_id, $user['id'])){
			if(Events::changeMemberStatus($event_id, $community_id, $community['type'], $status))
				$content = array("result" => 'success');
			else
				$content = array("result" => 'error');
		}
		else $content = array("result" => 'error');	
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
	
	case 'get_sport_block_list':
	
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
			  
            if($row['owner_id'] == $user['id']) {
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
	
	case 'get_pop_events_list':
	
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		
		$html = '';
		
		foreach(Events::getPopularEventList($number, $offset) as $row){
			$html .= '<div class="event-item"> <a href="./?task=events&event_id=' . $row['id'] . '" class="img"><img src="' . core::documentparser()->eventAvatar($row['cover_page']) . '" alt="" style="margin-left: -100%;"></a>';      
			$html .= '<div class="teg">';
			$html .= '<p><a href="./?task=events&event_id=' . $row['id'] . '">' . $row['name'] . '</a></p>';
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
                
			if(Events::checkOwnerEvent($row['event_id'], $user['id'], 'user')) $html .=	'<a href="./?task=events&event_id=' . $row['id'] . '&q=edit">' . core::getLanguage('str', 'edit') . '</a>';
				
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
	
	case 'get_pop_communities_list':
	
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();		
		$type = !empty($_REQUEST['type']) ? $_REQUEST['type'] : exit();
		
		$html = '';
		$task = $type == 'group' ? 'groups' : 'teams';	
	
		foreach(Communities::getPopularCommunitiesList($type, $number, $offset) as $row){
			
			$html .= '<div id="community_' .$row['community_id'] . '" class="event-item">';
			$html .= '<a class="img" href="./?task=' . $task . '&community_id=' . $row['community_id'] . '">';
			$html .= '<img border="0" alt="" src="' . core::documentparser()->communityAvatar($row) . '">';
			$html .= '</a>';
			$html .= '<div class="teg">';
			$html .= '<p><a href="./?task=' . $type . '&community_id=' . $row['community_id'] . '"> ' . $row['name'] . '</a></p>';

			$status = Communities::getCommunityRole(Communities::getUserStatus($row['community_id'], $user['id']));			
			
			$html .= '<p>' . $row['sport_type'] . '<br> ' . $status . '<br>' . $row['place'] . '</p>';
			$html .= '<p> ' .$row['about'] . '</p>';
			$html .= '<p><i></i>'. str_replace('%MEMBERS%', Communities::countMemberCommunity($row['community_id'], 2), core::getLanguage('str', 'participants_friends')) . '</p>';
			
			if(Communities::checkOwnerCommunity($row['community_id'], $user['id'])) {
				$html .= '<a href="./?task=' . $type . '&community_id=' .$row['community_id'] . '&q=edit">' . core::getLanguage('str', 'edit') . '</a>';
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
	
	case 'get_parsing':

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
	
	case 'get_friends_list':
	
		Auth::authorization();
	
		$number = is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$offset = is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$user_id = is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : exit();			
		
		if($user_id != $user['id']){
			core::user()->setUser_id($user_id);
			$profile_usersetting = core::user()->getUserSetting();	
		
			if(core::user()->permissionUser($user['id'], $profile_usersetting['permission_view_friends'])) 
				$permit = true;
			else
				$permit = false;
		}	
		
		if($permit !== false){
			foreach(Friends::getFriendsList($user_id, $number, $offset) as $row){
				$rows[] = array(
				"user_id" => $row['user_id'],			
				"avatar" => core::documentparser()->userAvatar($row),
				"firstname" => $row['firstname'],			
				"lastname" => $row['lastname'],
				"city" => $row['city'],
				"status_user" => core::user()->checkUserOnline($row['user_id']) ? 'online' : 'offline',
				"sender_id" => $user['id']);
			}	
			
			$content = '{"item":'.json_encode($rows).'}';		
	
			core::documentparser()->showJSONContent($content);	
		}
		
	break;
	
	case 'remove_message':
	
		Auth::authorization();		
		
		$id = is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : exit();
		
		if($data->removeMessage($id, $user['id']))
			$content = array("result" => 'success');
		else
			$content = array("result" => 'error');
		
		core::documentparser()->showJSONContent(json_encode($content));
	
	break;
}
