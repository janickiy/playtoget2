<!-- IF '${OPEN_PAGE}' == '' -->
<!-- INCLUDE header.tpl -->
<!--START CONTENT-->
<script>
window.avatar = '${TOP_AVATAR}';
window.user_id = '${ID_USER}';
window.placeholder = '${STR_YOUR_COMMENT}';
window.error = '${STR_THERE_ARE_NO_MORE_ENTRIES}';
window.init = '${STR_CLICK}';
</script>
<!-- ELSE -->
<!-- INCLUDE unauthorizedheader.tpl -->
<!-- END IF -->
<script type="text/javascript" src="./templates/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="./templates/js/jquery.ui.datepicker-ru.js"></script>
<script type="text/javascript" src="./templates/js/jquery.form.min.js"></script>
<script type="text/javascript" src="./templates/js/script_all.js"></script>
<script type="text/javascript">

$(function() {
	$.datepicker.setDefaults(
		$.extend($.datepicker.regional["ru"]) 
	);
  
	$("#datepicker").each(function(){
		$(this).datepicker({minDate: "0"}); 
	})

	$("#datepicker_end").each(function(){
		$(this).datepicker({minDate: "0"}); 
	})
});

</script>
<script type="text/javascript">

$(function() {

  // Tabs
  $("#tabs").tabs({
    activate: function (event, ui) {
      let active = $('#tabs').tabs('option', 'active');
      $("#tabid").html('the tab id is ' + $("#tabs ul>li a").eq(active).attr("href"));
    }
  });
});

</script>
<!-- INCLUDE uploadAvatar.tpl -->
<!-- IF '${MSG_ERROR_ALERT}' != '' -->

<div class='save_window_ok hiden'>${MSG_ERROR_ALERT}</div>
<!-- END IF -->
<!-- IF '${MSG_SUCCESS}' != '' -->
<div class='save_window_fail hiden'>${MSG_SUCCESS}</div>
<!-- END IF -->
<section class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-xs-12  bg">
        <!-- INCLUDE left_sitebar.tpl -->
        <div class="content friends">
          <!-- INCLUDE top_user_profile.tpl -->
          <!-- IF '${ID_EVENT}' != '' -->
          <!-- IF '${BLOCK_PAGE}' == '' -->
          <!-- IF '${EVENT_DESCRIPTION}' != '' -->
          <div class="sport_group_title">${EVENT_DESCRIPTION} </div>
          <!-- END IF -->
          <ul class="sport_group_list">
            <li><a href="./?task=events&event_id=${ID_EVENT}" <!-- IF '${QUERY}' == '' -->class="active-link"<!-- END IF -->><i class='icon_list icon-4'></i><span>${STR_FEED}</span></a></li>
            <li><a href="./?task=events&event_id=${ID_EVENT}&q=members" <!-- IF '${QUERY}' == 'members' -->class="active-link"<!-- END IF -->><i class='icon_list icon-5'></i><span>${STR_MEMBERS}</span></a></li>
            <li><a href="./?task=events&event_id=${ID_EVENT}&q=photoalbums" <!-- IF '${QUERY}' == 'photoalbums' -->class="active-link"<!-- END IF -->><i class='icon_list icon-2'></i><span>${STR_PHOTO}</span></a></li>
            <li><a href="./?task=events&event_id=${ID_EVENT}&q=videoalbums" 
              <!-- IF '${QUERY}' == 'videoalbums' -->
              class="active-link"
              <!-- END IF -->
              ><i class='icon_list icon-3'></i><span>${STR_VIDEO}</span></a></li>
          </ul>
          <!-- IF '${QUERY}' == 'members' -->
          <!-- IF '${NO_MEMBERS}' == '' -->
          <div class="photo-caption">
            <h3>${STR_MEMBERS}<sup> ${NUMBERMEMBER}</sup></h3>
          </div>
          <div class="possible-friend">
            <!-- BEGIN row_members -->
            <div class="col-xs-6 possible-friend-cart"> <a class="possible-avatar" href="./?task=profile&user_id=${ID_USER}"> <img src="${AVATAR}" alt=""> </a> <a href="./?task=profile&user_id=${ID_USER}">
              <h5><strong>${FIRSTNAME} <span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online
                <!-- END IF -->
                ' data-num='${ID_USER}'></span><br />
                ${LASTNAME}</strong></h5>
              </a>
              <!-- IF '${CITY}' != '' -->
              <p>${CITY}</p>
              <!-- END IF -->
              <p>${STATUS}</p>
              <!-- IF '${SEL}' != '' -->
              <a href="./?task=profile&user_id=${ID_USER}&q=messages&sel=${SEL}"><b></b></a><br>
              <!-- END IF -->
            </div>
            <!-- END row_members -->
          </div>
          <!-- END IF -->
          <!-- IF '${NO_TEAMS}' == '' -->
          <div class="photo-caption">
            <h3>${STR_TEAMS}<sup> ${NUMBERTEAMS}</sup></h3>
          </div>
          <div class="event-container">
            <!-- BEGIN row_teams -->
            <div class="event-item"> <a href="./?task=teams&community_id=${ID}" class="img"><img border="0" src="${AVATAR}" alt=""></a>
              <div class="teg">
                <p><a href="./?task=teams&community_id=${ID}">${NAME}</a></p>
                <p>${TYPE}</p>
                <p>
                  <!-- IF '${SPORT_TYPE}' != '' -->
                  ${SPORT_TYPE}<br>
                  <!-- END IF -->
                  <!-- IF '${STATUS}' != '' -->
                  ${STATUS}<br>
                  <!-- END IF -->
                  <!-- IF '${CITY}' != '' -->
                  ${CITY}
                  <!-- END IF -->
                </p>
                <p><i></i>${STR_MEMBER}</p>
              </div>
            </div>
            <!-- END row_teams -->
          </div>
          <!-- END IF -->
          <!-- IF '${NO_GROUPS}' == '' -->
          <div class="photo-caption">
            <h3>${STR_GROUPS}<sup> ${NUMBERGROUPS}</sup></h3>
          </div>
          <div class="event-container">
            <!-- BEGIN row_groups -->
            <div class="event-item"> <a href="./?task=teams&community_id=${ID}" class="img"><img border="0" src="${AVATAR}" alt=""></a>
              <div class="teg">
                <p><a href="./?task=teams&community_id=${ID}">${NAME}</a></p>
                <p>${TYPE}</p>
                <p>
                  <!-- IF '${SPORT_TYPE}' != '' -->
                  ${SPORT_TYPE}<br>
                  <!-- END IF -->
                  <!-- IF '${STATUS}' != '' -->
                  ${STATUS}<br>
                  <!-- END IF -->
                  <!-- IF '${CITY}' != '' -->
                  ${CITY}
                  <!-- END IF -->
                </p>
                <p><i></i>${STR_MEMBER}</p>
              </div>
            </div>
            <!-- END row_groups -->
          </div>
          <!-- END IF -->
          <!-- ELSE IF '${QUERY}' == 'photoalbums' -->
          <!-- INCLUDE block_photowindow.tpl -->
          <!-- IF '${ID_ALBUM}' != '' -->
          <!-- INCLUDE block_photos.tpl -->
          <!-- ELSE -->
          <!-- INCLUDE block_photoalbum.tpl -->
          <!-- END IF -->
          <!-- ELSE IF '${QUERY}' == 'create_videoalbum' -->
          <!-- INCLUDE block_videoalbum_form.tpl -->
          <!-- ELSE IF '${QUERY}' == 'edit_videoalbum' -->
          <!-- INCLUDE block_videoalbum_form.tpl -->
          <!-- ELSE IF '${QUERY}' == 'add_photo' -->
          <!-- INCLUDE block_add_photos.tpl -->
          <!-- ELSE IF '${QUERY}' == 'videoalbums' -->
          <!-- INCLUDE block_videowindow.tpl -->
          <!-- IF '${ID_ALBUM}' != '' -->
          <!-- INCLUDE block_videos.tpl -->
          <!-- ELSE -->
          <!-- INCLUDE block_videoalbum.tpl -->
          <!-- END IF -->
          <!-- ELSE IF '${QUERY}' == 'add_video' -->
          <!-- INCLUDE block_add_video.tpl -->
          <!-- ELSE IF '${QUERY}' == 'edit_photoalbum' -->
          <!-- INCLUDE block_photoalbum_form.tpl -->
          <!-- ELSE IF '${QUERY}' == 'create_photoalbum' -->
          <!-- INCLUDE block_photoalbum_form.tpl -->
          <!-- ELSE IF '${QUERY}' == 'edit' -->
          <link rel="stylesheet" href="./templates/css/jquery-ui-1.8.16.custom.css">
          <div class="photo-caption">
            <h3>${TITLE_PAGE}</h3>
          </div>
          <div class="job_form">
            <!-- INCLUDE events_form.tpl -->
          </div>
          <!-- ELSE -->
          <!-- INCLUDE block_photowindow.tpl -->
          <!-- IF '${SHOW_COMMMENT_FORM}' == 'show' -->
          <script type="text/javascript" src="./templates/js/autoresize.js"></script>
          <script type="text/javascript">
      $(document).ready(function(){
              jQuery('textarea').autoResize({
                   extraSpace : 0
              });
      })
    </script>
          <div class="message-content">
            <form autocomplete="off" id="addCommentForm" method="POST" action="" enctype="multipart/form-data">
              <input type="hidden" name="commentable_type" value="event">
              <input type="hidden" name="content_id" value="${ID_EVENT}">
              <input type="hidden" name="user_id" value="${ID_USER}">
              <input type="hidden" name="parent_id" value="0">
			  <!-- IF '${ADMIN}' == 'yes' --><input type="hidden" name="author_community" value="1"><!-- END IF -->			  
              <input type="file"  class="file_name" name="file_name[]" data-num="${ID_EVENT}" multiple/>
              <textarea id="comment" name="comment" data-num="${ID_EVENT}" class='ahref_input' placeholder="${STR_WHATS_INTERESTING}"></textarea>
              <div class="smile-files"> <a id="smilesBtn" class="smile smilesBtn" data-num="${ID_EVENT}"><img src="./templates/images/smile.png" alt=""></a> <a href="#" class="files" data-num="${ID_EVENT}" data-tooltip="Прикрепить изображение"><img src="./templates/images/files.png" alt=""></a>
                <div class="smilesChoose" data-num="${ID_EVENT}"></div>
              </div>
              <input id="submit" type="submit">
              <div class='files_block' data-num="${ID_EVENT}"></div>
            </form>
          </div>
          <div id="addCommentContainers" data-type='event'></div>
          <!-- END IF -->
          <!-- BEGIN row_comments -->
          <!-- IF '${ID_PARENT}' == '0' -->
          <div id="message-${ID}" data-item="${ID_PARENT}" class="message">
            <!-- IF '${NAME}' -->
            <div class='img-account'> <img src="${AVATAR}" alt="" class='event'> </div>
            <!-- ELSE -->
            <img src="${AVATAR}" alt="" class='img-account'>
            <!-- END IF -->
            <!-- IF '${ID_USER_SESSION}' == '${ID_CONTENT}' -->
            <div class="del_mess" data-item="${ID}"></div>
            <!-- ELSE -->
            <!-- IF '${ID_USER}' == '${ID_USER_SESSION}' -->
            <div class="del_mess" data-item='${ID}'></div>
            <!-- END IF -->
            <!-- END IF -->
            <h5 class="name">
              <!-- IF '${NAME}' -->
              <a href="#">${NAME}</a>
              <!-- ELSE -->
              <a href="./?task=profile&user_id=${ID_USER}">${FIRSTNAME} ${LASTNAME}</a><span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online
              <!-- END IF -->
              ' data-num='${ID_USER}'></span>
              <!-- END IF -->
            </h5>
            <p class="data">${CREATED}</p>
            <p class="message-text">
              <!-- IF '${CONTENT}'!='' -->
              ${CONTENT} <br>
              <!-- END IF -->
            <ul class='attach_image'>
              <!-- BEGIN row_attach -->
              <li><img border="0" src="${SMALL_PHOTO}" class="photo_big" data-num="${ID_PHOTO}"></li>
              <!-- END row_attach -->
            </ul>
            </p>
            <!-- IF '${LIKES_SHOW}' == '' -->
            <a id="reply-${ID}" class="reply" data-item="${ID}"> ${STR_REPLY}</a> <a id="tell-comment-${ID}" class="tell" data-item="${ID}" data-type="comment">${NUMBERTELL}</a> <a id="like-comment-${ID}" class="liked" data-item="${ID}" data-type="comment">${NUMBERLIKED}</a>
            <!-- END IF -->
          </div>
          <!-- ELSE -->
          <div class="message-reply message" id="message-${ID}" data-item="${ID_PARENT}">
            <!-- IF '${ID_USER_SESSION}' == '${ID_CONTENT}' -->
            <div class='del_mess' data-item='${ID}'></div>
            <!-- ELSE -->
            <!-- IF '${ID_USER}' == '${ID_USER_SESSION}' -->
            <div class="del_mess" data-item="${ID}"></div>
            <!-- END IF -->
            <!-- END IF -->
            <div class="message" >
              <div class="message-account">
                <!-- IF '${NAME}' -->
                <div class='img-account'> <img src="${AVATAR}" alt="" class='event'> </div>
                <!-- ELSE -->
                <img src="${AVATAR}" alt="" class='img-account'>
                <!-- END IF -->
                <h5 class="name">
                  <!-- IF '${NAME}' -->
                  <a href="#">${NAME}</a>
                  <!-- ELSE -->
                  <a href="./?task=profile&user_id=${ID_USER}">${FIRSTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online
                  <!-- END IF -->
                  ' data-num='${ID_USER}'></span><br>
                  ${LASTNAME}</a>
                  <!-- END IF -->
                </h5>
                <p class="data">${CREATED}</p>
              </div>
              <p class="message-reply-text">
                <!-- IF '${CONTENT}'!='' -->
                ${CONTENT} <br>
                <!-- END IF -->
              <ul class='attach_image'>
                <!-- BEGIN row_reply_attach -->
                <li><img border="0" src="${SMALL_PHOTO}" class="photo_big" data-num="${ID_PHOTO}"></li>
                <!-- END row_reply_attach -->
              </ul>
              </p>
              <!-- <a id="reply-${ID}" class="reply" data-item="${ID}"> ${STR_REPLY}</a> <a id="tell-comment-${ID}" class="tell" data-item="${ID}" data-type='comment'>${NUMBERTELL}</a> <a id="like-comment-${ID}" class="liked" data-item="${ID}" data-type='comment'>${NUMBERLIKED}</a>-->
            </div>
          </div>
          <!-- END IF -->
          <!-- END row_comments -->
          <div id="comment-list"></div>
          <script type="text/javascript">

$(document).on( "click", ".reply", function() {
	let IdComment = $(this).attr('data-item');
	let IdParent = $('#message-' + IdComment).attr('data-item');

	$('.reply').show();	
	$(this).hide();	
	$('.my-comment').hide();	
	
	let ReplyForm = '<div id="my-comment-' + IdComment + '" class="my-comment">';
	ReplyForm += '<div class="message-account">';
	ReplyForm += '<img src="${TOP_AVATAR}" alt="" class="img-account">';
	ReplyForm += '</div>';				
	ReplyForm += '<form autocomplete="off" id="reply-form-' + IdComment + '" data-num = '+ IdComment +' action="" enctype="multipart/form-data">';
	ReplyForm += '<input type="hidden" name="commentable_type" value="event">';
	ReplyForm += '<input type="hidden" name="content_id" value="${ID_EVENT}">';
	ReplyForm += '<input type="hidden" name="user_id" value="${ID_USER}">';
	ReplyForm += '<input type="hidden" name="parent_id" value="' + IdComment + '">';
	<!-- IF '${ADMIN}' == 'yes' -->
	ReplyForm += '<input type="hidden" name="author_community" value="1">';
	<!-- END IF -->	
    ReplyForm += '<input type="file" class="file_name" name="file_name[]" data-num="' + IdComment + '" multiple/>';
	ReplyForm += '<input id="comment" name="comment" type="text" data-num="' + IdComment + '" placeholder="${STR_YOUR_COMMENT}">';					
	ReplyForm += '<div class="smile-files">';					
	ReplyForm += '<a id="smilesBtn" class="smile smilesBtn" data-num="' + IdComment + '"><img src="./templates/images/smile.png" alt=""></a>';		
	ReplyForm += '<a href="#" class="files" data-num="' + IdComment + '" data-tooltip="Прикрепить изображение"><img src="./templates/images/files.png" alt=""></a>';	
	ReplyForm += "<div class='smilesChoose add' data-num='" + IdComment + "'></div>"; 					
	ReplyForm += '</div>';	
	ReplyForm += "<div class='files_block two' data-num='" + IdComment + "'></div>";			
	ReplyForm += '<input type="submit" id="send-reply" class="send" value="Отправить" data-item="' + IdComment + '">';	
	ReplyForm += '</form>';						
	ReplyForm += '<div style="clear:both"></div>';
	ReplyForm += '</div>';						
							
	$(ReplyForm).hide().insertAfter('#message-' + IdComment).slideDown();
	
});

let evJob = true;
let settComments = { 
	number  : 10,
	offset  : 10,
}

$(document).scroll(function() {
  
	if($(window).scrollTop()+$(window).height()>=$(document).height()){
		if (evJob){
			evJob = false;
			$('#comment-list').append('<div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>')
			$.ajax({
				type:'POST',
				url:'/?task=ajax_action&action=getcomments',
				data:{
					number : settComments.number,
					offset : settComments.offset,
					commentable_type : 'event',
					id : '${ID_EVENT}'
				},
				success:function(data){
					$('#comment-list').find('.loading-bar').remove();
					$('#comment-list').append(data.html);
					settComments.offset+=settComments.number;
					$('.message-text').each(function(){
						$(this).emotions();
					})
					$('.message-reply-text').each(function(){
						$(this).emotions();
					})
					evJob = true;
				}
			})
		}
	}
});

</script>
          <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
          <!-- END IF -->
          <!-- ELSE -->
          <br>
          <br>
          <div class="photo-caption">
            <h3>${STR_EVENT_SUSPENDED}</h3>
          </div>
          <!-- END IF -->
          <!-- ELSE -->
          <!-- IF '${QUERY}' == 'create' -->
          <link rel="stylesheet" href="./templates/css/jquery-ui-1.8.16.custom.css">
          <div class="photo-caption">
            <h3>${TITLE_PAGE}</h3>
          </div>
          <div class="job_form">
            <!-- INCLUDE events_form.tpl -->
          </div>
          <!-- ELSE -->
          <!-- IF '${OPEN_PAGE}' == '' -->
          <!-- INCLUDE events_search_form.tpl -->
          <!-- END IF -->
          <div id="tabs">
            <ul id='main-menu' class='marginBottom-40'>
              <li data-type='popular'><a href="#popular">${STR_POPULAR_EVENT}</a></li>
              <li data-type='mygroups'><a href="#mygroups">${STR_MY_EVENTS}
                <!-- IF '${NUMBERMYEVENTS}' > '0' -->
                <sup> ${NUMBERMYEVENTS}</sup>
                <!-- END IF -->
                </a></li>
              <li data-type='invited'><a href="#invited">${STR_AM_INVITED}
                <!-- IF '${NUMBER_INVITED_ME}' > '0' -->
                <sup class='active'> ${NUMBER_INVITED_ME}</sup>
                <!-- END IF -->
                </a></li>
            </ul>
            <div id="popular">
              <!-- IF '${NO_POP_EVENTS}' == '' -->
              <div class="event-container">
                <!-- BEGIN pop_event_row -->
                <div class="event-item"> <a href="./?task=events&event_id=${ID}" class="img"><img src="${AVATAR}" alt="" class='marginLeft-100'></a>
                  <div class="teg">
                    <p><a href="./?task=events&event_id=${ID}">${NAME}</a></p>
                    <p>
                      <!-- IF '${SPORT_TYPE}' != '' -->
                      ${SPORT_TYPE}<br>
                      <!-- END IF -->
                      <!-- IF '${CITY}' != '' -->
                      ${CITY}<br>
                      <!-- END IF -->
                      ${DATE_INTERVAL_EVENT_BEGINNING}<br>
                      <!-- IF '${DATE_INTERVAL_EVENT_END}' != '' -->
                      ${DATE_INTERVAL_EVENT_END}<br>
                      <!-- END IF -->
                    </p>
                    <!-- IF '${ROLE}' != '' -->
                    <p>${ROLE}</p>
                    <!-- END IF -->
                    <p><i></i>${PARTICIPANTS_FRIENDS}</p>
                    <!-- IF '${ALLOW_EDIT}' == 'yes' -->
                    <a href="./?task=events&event_id=${ID}&q=edit">${STR_EDIT}</a>
                    <!-- END IF -->
                    <span>${STATUS}</span> </div>
                </div>
                <!-- END pop_event_row -->
                <!-- IF '${NUMBERPOPULAREVENTS}' > '5' -->
                <a id='my-event-pop' class="show-more" onclick="showMorePopEvent()"><i></i>${STR_SHOW_MORE}</a>
                <!-- END IF -->
              </div>
              <!-- ELSE -->
              <center>
                <h5>${STR_THERE_ARENT_POP_EVENTS}</h5>
              </center>
              <!-- END IF -->
            </div>
            <div id="mygroups">
              <!-- IF '${NO_MY_EVENTS}' == '' -->
              <div class="event-container">
                <!-- BEGIN my_event_row -->
                <div class="event-item"> <a href="./?task=events&event_id=${ID}" class="img"><img src="${AVATAR}" alt="" class='marginLeft-100'></a>
                  <div class="teg">
                    <p><a href="./?task=events&event_id=${ID}">${NAME}</a></p>
                    <p>
                      <!-- IF '${SPORT_TYPE}' != '' -->
                      ${SPORT_TYPE}<br>
                      <!-- END IF -->
                      <!-- IF '${CITY}' != '' -->
                      ${CITY}<br>
                      <!-- END IF -->
                      ${DATE_INTERVAL_EVENT_BEGINNING}<br>
                      <!-- IF '${DATE_INTERVAL_EVENT_END}' != '' -->
                      ${DATE_INTERVAL_EVENT_END}<br>
                      <!-- END IF -->
                    </p>
                    <!-- IF '${ROLE}' != '' -->
                    <p>${ROLE}</p>
                    <!-- END IF -->
                    <p><i></i>${PARTICIPANTS_FRIENDS}</p>
                    <!-- IF '${ALLOW_EDIT}' == 'yes' -->
                    <a href="./?task=events&event_id=${ID}&q=edit">${STR_EDIT}</a>
                    <!-- END IF -->
                    <span>${STATUS}</span> </div>
                </div>
                <!-- END my_event_row -->
                <!-- IF '${NUMBERMYEVENTS}' > '5' -->
                <a id='my-event' class="show-more" onclick="showMoreEvent('${ID_USER}','user')"><i></i>${STR_SHOW_MORE}</a>
                <!-- END IF -->
              </div>
              <!-- ELSE -->
              <center>
                <h5>${STR_YOU_DONT_TAKE_PART_IN_EVENTS}</h5>
              </center>
              <!-- END IF -->
            </div>
            <div id='invited'>
              <!-- IF '${NO_INVITED_ME}' == '' -->
              <div class="event-container">
                <!-- BEGIN invited_me_events_row -->
                <div class="event-item"> <a href="./?task=events&event_id=${ID}" class="img"><img src="${AVATAR}" alt="" class='marginLeft-100'></a>
                  <div class="teg">
                    <p><a href="./?task=events&event_id=${ID}">${NAME}</a></p>
                    <p>
                      <!-- IF '${SPORT_TYPE}' != '' -->
                      ${SPORT_TYPE}<br>
                      <!-- END IF -->
                      <!-- IF '${CITY}' != '' -->
                      ${CITY}<br>
                      <!-- END IF -->
                      ${DATE_INTERVAL_EVENT_BEGINNING}<br>
                      <!-- IF '${DATE_INTERVAL_EVENT_END}' != '' -->
                      ${DATE_INTERVAL_EVENT_END}<br>
                      <!-- END IF -->
                    </p>
                    <p><i></i>${PARTICIPANTS_FRIENDS}</p>
                  </div>
                </div>
                <!-- END invited_me_events_row -->
                <!-- IF '${NUMBER_INVITED_ME}' > '5' -->
                <a id='my-event' class="show-more"><i></i>${STR_SHOW_MORE}</a>
                <!-- END IF -->
              </div>
              <!-- ELSE -->
              <center>
                <h5>${STR_YOU_DONT_HAVE_INVITATIONS}</h5>
              </center>
              <!-- END IF -->
            </div>
          </div>
          <!-- END IF -->
          <!-- END IF -->
          <!--End content-->
        </div>
        <!-- INCLUDE right_sitebar.tpl -->
      </div>
    </div>
  </div>
</section>
<script src="./templates/js/search.js"></script>
<!--END CONTENT-->
<!-- INCLUDE footer.tpl -->
