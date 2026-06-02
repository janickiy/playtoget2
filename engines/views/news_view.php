<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();



	Auth::authorization();

	core::requireEx('libs', "html_template/SeparateTemplate.php");
	$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

	core::user()->setUser_id($_SESSION['user_id']);
	$user = core::user()->getUserInfo();
	core::user()->setUserActivity();

	$tpl->assign('NUMBERMESSAGE', core::user()->MessageNotification());
	$tpl->assign('NUMBERINVITATION', core::user()->AddFriendsNotification());

	$css = array();
	$css[] = './templates/css/bootstrap-theme.min.css';
	$css[] = './templates/css/bootstrap.min.css';
	$css[] = './templates/css/style.css';
	$css[] = './templates/css/owl.carousel.css';
	$css[] = './templates/css/owl.theme.css';
	$css[] = './templates/css/owl.transitions.css';
	$css[] = './templates/css/lightbox.css';

	foreach($css as $row){
		$rowBlock = $tpl->fetch('row_css_list');
		$rowBlock->assign('CSS', core::documentparser()->showCSS($row));
		$tpl->assign('row_css_list', $rowBlock);
	}

	$js = array();
	$js[] = './templates/js/jquery-3.7.1.min.js';

	foreach($js as $row){
		$rowBlock = $tpl->fetch('row_js_list');
		$rowBlock->assign('JS', core::documentparser()->showJS($row));
		$tpl->assign('row_js_list', $rowBlock);
	}

	include_once "top.inc";
	include_once "left_block.inc";
	include_once "right_block.inc";

	$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'news'));
	$tpl->assign('NUMBERNEWS', 0);

	$arrs = array();

	foreach(Comments::getCommentEvent($user['id'], 5, 0) as $row){
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

	foreach(Comments::getCommentCommunity($user['id'], 5, 0) as $row){
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
			$arrs[] = array('name' => $publication_name,'type' => $row['commentable_type'],'id_author' => $community_id, 'msg' => $publication_msg, 'avatar' => core::documentparser()->communityAvatar($row), 'content_id' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
		}
	}

	foreach(Comments::getUserComment($user['id'], 5, 0) as $row){
		$publication_name = $row['firstname'] . " " . $row['lastname'];
		$publication_id_author = $row['user_id'];
		$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_left_comment'));  
		$publication_msg .= '<ul class="attach_image">';
		
			foreach(Attach::getAttachList($row['id_comment'], 'comment') as $row2){
				$photo = Photoalbum::getPhotoInfo($row2['photo_id']);
				$photo_src = Photoalbum::getPhotoSmallSrc($photo);
				if($photo_src) $publication_msg .= '<li><img border="0" src="' . $photo_src . '" class="photo_big" data-num="'.$photo['photo_id'].'" /></li>';
			}	
		
		$publication_msg .= '</ul>'	;
		$arrs[] = array('name' => $publication_name,'type' => 'user', 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $row['id_comment'], 'likeable_type' => 'comment', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
	}

	foreach(Videoalbum::getUserPublishVideo($user['id'], 5, 0) as $row){
		$publication_name = $row['firstname'] . " " . $row['lastname'];
		$publication_id_author = $row['user_id'];
		$publication_msg = str_replace('%VIDEO%', core::documentparser()->getThumb($row['provider'], $row['video']), core::getLanguage('str', 'useraction_published_video')); 
		$publication_msg = str_replace('%ID%', $row['video_id'], $publication_msg); 	
	
		$arrs[] = array('name' => $publication_name,'type' => 'user', 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $row['video_id'], 'likeable_type' => 'video', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
	}

	foreach(Photoalbum::getUserPublishPhoto($user['id'], 5, 0) as $row){
		$publication_name = $row['firstname'] . " " . $row['lastname'];
		$publication_id_author = $row['user_id'];
		$publication_msg = str_replace('%PHOTO%', core::documentparser()->photogalleryPic($row['small_photo'], $row['photoalbumable_type']), core::getLanguage('str', 'useraction_added_photo')); 
		$publication_msg = str_replace('%ID%', $row['photo_id'], $publication_msg); 
	
		$arrs[] = array('name' => $publication_name,'type' => 'user', 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $row['photo_id'], 'likeable_type' => 'photo', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
	}

	foreach(core::user()->getMyFriendsLastFriend(5, 0) as $row){
		$publication_name = $row['lastname'] . " " . $row['firstname'];
		$publication_id_author = $row['user_id'];
	
		$userfriend = $row['friend_lastname'] . " " . $row['friend_firstname'];
	
		$publication_msg = str_replace('%USERFRIEND%', $userfriend, core::getLanguage('str', 'useraction_make_friends')); 
		$publication_msg = str_replace('%ID_FRIEND%', $row['friend_id'], $publication_msg); 
	
		$arrs[] = array('name' => $publication_name,'type' => 'user', 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => '', 'likeable_type' => '', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
	}

	foreach(Comments::getUserGetVideoComment($user['id'], 5, 0) as $row){
		$publication_name = $row['firstname'] . " " . $row['lastname'];	
		$publication_id_author = $row['user_id'];
		$publication_msg = str_replace('%MSG%', $row['content'], core::getLanguage('str', 'useraction_commented_video'));  
	
		$video = Videoalbum::getVideoInfo($row['content_id']);
		
		if($video['owner_id']){
			$avatar = core::documentparser()->userAvatar($video);
			$date = core::documentparser()->mysql_russian_date($row['added']);
			$author_name = $video['lastname'] . " " . $video['firstname'];	
			$publication_msg = str_replace('%VIDEO%', core::documentparser()->getThumb($video['provider'], $video['video']), $publication_msg);
			$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);
			$publication_msg = str_replace('%ID_AUTHOR%', $video['owner_id'], $publication_msg);
			$publication_msg = str_replace('%ID%', $video['video_id'], $publication_msg); 
			$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
			$publication_msg = str_replace('%DATE%', $date , $publication_msg);
		}

		$arrs[] = array('name' => $publication_name,'type' => 'user', 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $row['id_comment'], 'likeable_type' => 'video', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
	} 

	foreach(Comments::getUserGetPhotoComment($user['id'], 5, 0) as $row){
		$publication_name = $row['firstname'] . " " . $row['lastname'];
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
	
		$arrs[] = array('name' => $publication_name,'type' => 'user', 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $row['id_comment'], 'likeable_type' => 'photo', 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);	
	}

	foreach(core::user()->getUserFriendsLiked(5, 0) as $row){
		$publication_name = $row['firstname'] . " " . $row['lastname'];
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
				
				if ($comment['commentable_type']=='group' || $comment['commentable_type']=='team'){

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
				
				$publication_msg .= '</ul>'	;
				$publication_msg .= '</div></div>';
			}	
		}	
		else if($row['likeable_type'] == 'video'){
			$video = Videoalbum::getVideoInfo($row['content_id']);
			$avatar = core::documentparser()->userAvatar($video);
			$date = core::documentparser()->mysql_russian_date($row['added']);
		
			if($video['owner_id']){
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
			$avatar = core::documentparser()->userAvatar($photo);
			$date = core::documentparser()->mysql_russian_date($row['added']);
		
			if($photo['owner_id']){
				$author_name = $photo['firstname'] . " " . $photo['lastname'];	
			
				$publication_msg = str_replace('%PHOTO%', core::documentparser()->photogalleryPic($photo['small_photo'], $photo['photoalbumable_type']), core::getLanguage('str', 'useraction_liked_photo'));
				$publication_msg = str_replace('%ID_AUTHOR%',$photo['owner_id'], $publication_msg); 
				$publication_msg = str_replace('%AUTHOR%', $author_name, $publication_msg);	
				$publication_msg = str_replace('%ID%', $content_id, $publication_msg);  
				$publication_msg = str_replace('%AVATAR%', $avatar, $publication_msg);	
				$publication_msg = str_replace('%DATE%', $date , $publication_msg);
			}		
		}	
	
		$arrs[] = array('name' => $publication_name,'type' => 'user', 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $content_id, 'likeable_type' => $row['likeable_type'], 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
	}

	foreach(core::user()->getUserShare(5, 0) as $row){
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
				
				if ($comment['commentable_type']=='event'){
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
	
		$arrs[] = array('name' => $publication_name,'type' => 'user', 'id_author' => $publication_id_author, 'msg' => $publication_msg, 'avatar' => core::documentparser()->userAvatar($row), 'content_id' => $content_id, 'likeable_type' => $row['shareable_type'], 'publication_date' => core::documentparser()->mysql_russian_date($row['added']), 'timeorder' => $row['timeorder']);
	}

	foreach(core::documentparser()->customMultiSort($arrs, 'timeorder') as $row){
		$rowBlock = $tpl->fetch('row_news');
		$rowBlock->assign('PUBLICATION_AVATAR', $row['avatar']);		
		$rowBlock->assign('PUBLICATION_AUTHOR_ID', $row['id_author']);	
		$rowBlock->assign('PUBLICATION_NAME', $row['name']);	
		$rowBlock->assign('PUBLICATION_MSG', $row['msg']);
		$rowBlock->assign('PUBLICATION_TYPE', $row['type']);
		$rowBlock->assign('ID', $row['content_id']);	
		$rowBlock->assign('PUBLICATION_DATE', $row['publication_date']);
		$rowBlock->assign('STATUS_USER', core::user()->checkUserOnline($row['id_author']) ? 'online' : 'offline');
	
		if($row['likeable_type']){
			$rowBlock->assign('NUMBERLIKED', Comments::getNumberLiked($row['content_id'], $row['likeable_type']));
			$rowBlock->assign('NUMBERTELL', Comments::getNumberTell($row['content_id'], $row['likeable_type']));	
			$rowBlock->assign('LIKEABLE_TYPE', $row['likeable_type']);
		}
	
		$rowBlock->assign('STR_REPLY', core::getLanguage('str', 'reply'));
	
		$tpl->assign('row_news', $rowBlock);
	}

	$tpl->assign('STR_RECOMMENDS', core::getLanguage('str', 'recommends'));
	$tpl->assign('STR_REASON_TO_CONGRATULATE', core::getLanguage('str', 'reason_to_congratulate'));
	$tpl->assign('STR_ADS', core::getLanguage('str', 'ads'));
	$tpl->assign('STR_RECOMMEND', core::getLanguage('str', 'recommend'));
	$tpl->assign('STR_PLAYGROUNDS', core::getLanguage('str', 'playgrounds'));
	$tpl->assign('STR_SHOPS', core::getLanguage('str', 'shops'));
	$tpl->assign('STR_FITNESS', core::getLanguage('str', 'fitness'));

	include_once "footer.inc";
		
	$tpl->display();
	
