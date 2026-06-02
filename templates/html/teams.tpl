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
	let wrapper = $( ".file_upload" ),
	inp = wrapper.find( "input" ),
	btn = wrapper.find( "button" ),
	lbl = wrapper.find( "div" );
	btn.focus(function(){
		inp.focus()
    });
	
    // Crutches for the :focus style:
    inp.focus(function(){
		wrapper.addClass( "focus" );
    }).blur(function(){
        wrapper.removeClass( "focus" );
    });
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
        <div class="content content-groups friends">
          <!-- INCLUDE top_user_profile.tpl -->
          <!-- IF '${ID_COMMUNITY}' != '' -->
          <!-- IF '${BLOCK_PAGE}' == '' -->
          <!-- IF '${COMMUNITY_DESCRIPTION}' != '' -->
          <div class="sport_group_title"> ${COMMUNITY_DESCRIPTION} </div>
          <!-- END IF -->
          <ul class="sport_group_list">
            <li><a href="./?task=teams&community_id=${ID_COMMUNITY}" <!-- IF '${QUERY}' == '' -->class="active-link"<!-- END IF -->><i class='icon_list icon-4'></i><span>${STR_COMMUNITY_FEED}</span></a></li>
            <li><a href="./?task=teams&community_id=${ID_COMMUNITY}&q=members" <!-- IF '${QUERY}' == 'members' -->class="active-link"<!-- END IF -->><i class='icon_list icon-5'></i><span>${STR_COMMUNITY_MEMBERS}</span></a></li>
            <!-- IF '${COMMUNITY_PHOTOALBUMS}' != 'hide' -->
			<li><a href="./?task=teams&community_id=${ID_COMMUNITY}&q=photoalbums" <!-- IF '${QUERY}' == 'photoalbums' -->class="active-link"<!-- END IF -->><i class='icon_list icon-2'></i><span>${STR_COMMUNITY_PHOTO}</span></a></li>
            <!-- END IF -->
            <!-- IF '${COMMUNITY_VIDEOALBUMS}' != 'hide' -->
            <li><a href="./?task=teams&community_id=${ID_COMMUNITY}&q=videoalbums" <!-- IF '${QUERY}' == 'videoalbums' -->class="active-link"<!-- END IF -->><i class='icon_list icon-3'></i><span>${STR_COMMUNITY_VIDEO}</span></a></li>
            <!-- END IF -->
            <li><a href="./?task=teams&community_id=${ID_COMMUNITY}&q=events" <!-- IF '${QUERY}' == 'events' -->class="active-link"<!-- END IF -->><i class='icon_list icon-1'></i><span>${STR_COMMUNITY_EVENTS}</span></a></li>
          </ul>
          <!-- IF '${QUERY}' == 'members' -->
          <!-- IF '${NO_MEMBERS}' == '' -->
          <div class="photo-caption">
            <h3>${STR_MEMBERS}<sup> ${NUMBERMEMBER}</sup></h3>
          </div>
          <div class="possible-friend">
            <!-- BEGIN row_members -->
            <div class="col-xs-6 possible-friend-cart"> <a class="possible-avatar" href="./?task=profile&user_id=${ID_USER}"> <img src="${AVATAR}" alt=""> </a> <a href="./?task=profile&user_id=${ID_USER}">
              <h5><strong>${FIRSTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online
                <!-- END IF -->
                ' data-num='${ID_USER}'></span> <br />
                ${LASTNAME}</strong></h5>
              </a>
              <p>${CITY}</p>
              <p>${STATUS}</p>
              <!-- IF '${SEL}' != '' -->
              <a href="./?task=profile&user_id=${ID_USER}&q=messages&sel=${SEL}"><b></b></a><br>
              <!-- END IF -->
              <div class="control">
                <!-- IF '${SHOW_ADD_TO_ADMIN_LINK}' == 'yes' -->
                <span> <a onclick='add_admin(${ID_USER},${ID_COMMUNITY})' data-tooltip="${STR_ADD_TO_ADMINISTRATORS}"><img src='./templates/images/icon-admin.png'/></a> </span>
                <!-- END IF -->
                <!-- IF '${SHOW_USER_BLOCK_LINK}' == 'yes' -->
                <span> <a onclick='add_black_community(${ID_USER}, ${ID_COMMUNITY})' data-tooltip="${STR_BLOCK_USER}"><img src='./templates/images/icon-krest.png'/></a> </span>
                <!-- END IF -->
              </div>
            </div>
            <!-- END row_members -->
          </div>
          <!-- END IF -->
          <!-- IF '${NO_APPLICATIONS}' == '' -->
          <div class="photo-caption">
            <h3>Заявки</h3>
          </div>
          <div class="possible-friend">
            <!-- BEGIN row_application_members -->
            <div class="col-xs-6 possible-friend-cart"> <a class="possible-avatar" href="./?task=profile&user_id=${ID_USER}"> <img src="${AVATAR}" alt=""> </a> <a href="./?task=profile&user_id=${ID_USER}">
              <h5><strong>${FIRSTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online
                <!-- END IF -->
                ' data-num='${ID_USER}'></span> <br />
                ${LASTNAME}</strong></h5>
              </a>
              <p>${CITY}</p>
              <a href="./?task=profile&user_id=${ID_USER}&q=messages&sel=${SEL}"><b></b></a><br>
              <div class='control'> <span> <a onclick='approve_community_user(${ID_USER},${ID_COMMUNITY})' data-tooltip="${STR_ACCEPT}"><img src='./templates/images/icon-ok.png'/></a> </span>
                <!-- <span> 
                  <a onclick='add_black_community(${ID_USER},${ID_COMMUNITY})' data-tooltip="Отклонить"><img src='./templates/images/icon-krest.png'/></a>
                </span> -->
              </div>
            </div>
            <!-- END row_application_members -->
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
          <!-- ELSE IF '${QUERY}' == 'events' -->
          <!-- IF '${SHOW_ADD_EVENTS_FORM}' == 'show' -->
          <div class="photo-caption">
            <h3>${STR_SEARCHING}</h3>
          </div>
          <form class="form-horizontal" enctype="multipart/form-data" method="post" action="">
            <div class="form-group">
              <div class="col-lg-12">
                <p class="select-container-text lupa width100">
                  <input class="form-control search_events" type="text" name="name" placeholder='Начните вводить'>
                  <span></span> </p>
              </div>
            </div>
          </form>
          <br>
          <div id='resultSearch'> </div>
          <script>
          $(document).on('click','.addEvent',function(){
              let community = '${ID_COMMUNITY}';
              let event = $(this).attr('data-item');
              let status = $(this).attr('data-status');
              change_event_community_status(community,event,status);
          })
          $(document).ready(function(){

                let settSearch = { 
                  number  : 10,
                  offset  : 0,
                }
                $('.search_events').keyup(function(){
                  let val = $(this).val();
                        $('#resultSearch').html('');
                  $.ajax({
                    type:'POST',
                    url:'?task=ajax_action&action=search_event',
                    data:{
                      number:settSearch.number,
                      offset:settSearch.offset,
                      member_id:'${ID_COMMUNITY}',
                      eventable_type:'team',
                      search:val,
                    },
                    success:function(data){
                      if (data.status==1)
                      {
                        $('#resultSearch').html('<div class="event-container">'+data.html+'</div>');
                      }
                    }
                  })
                })
          })
          </script>
          <!-- END IF -->
          <div class="photo-caption">
            <h3>${TITLE_PAGE}</h3>
          </div>
          <!-- IF '${NO_EVENTS}' == 'yes' -->
          <div class="photo-caption">
            <h5 class='center_text'>${STR_COMMUNITY_HASNT_EVENTS}</h5>
          </div>
          <!-- ELSE -->
          <div class="event-container">
            <!-- BEGIN row_community_events -->
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
                  ${DATE}</p>
                <p>${DESCRIPTION}</p>
                <p><i></i>${PARTICIPANTS_COMMUNITY}</p>
                <span>${STATUS}</span> </div>
            </div>
            <!-- END row_community_events -->
          </div>
          <!-- END IF -->
          <!-- ELSE IF '${QUERY}' == 'edit' -->
          <div class="photo-caption">
            <h3>${TITLE_PAGE}</h3>
          </div>
          <div class="job_form">
            <form class="form-horizontal" enctype="multipart/form-data" method="post" action="${ACTION}">
              <input type="hidden" name="action" value="edit">
              <input type="hidden" name="${ID_COMMUNITY}" value="community_id">
              <div id="tabs">
                <ul>
                  <li><a href="#info">${STR_COMMUNITY_INFO}</a></li>
                  <li><a href="#administrators">${STR_COMMUNITY_ADMINISTRATORS}</a></li>
                  <li><a href="#privacy">${STR_COMMUNITY_PRIVACY}</a></li>
                  <li><a href="#blacklist">${STR_COMMUNITY_BLACK_LIST}</a></li>
                </ul>
                <div id="info">
                  <center>
                    <h2>${STR_COMMUNITY_INFO}</h2>
                  </center>
                  <br>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="name">${STR_NAME}</label>
                    <div class="col-lg-6">
                      <input class="form-control" type="text" name="name" value="${COMMUNITY_NAME}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="about">${STR_DESCRIPTION}</label>
                    <div class="col-lg-6">
                      <textarea class="form-control form-dark" rows="4" name="about">${COMMUNITY_ABOUT}</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="id_place">${STR_PLACE}</label>
                    <div class="col-lg-6">
                      <input type="hidden" name="id_place" value="${COMMUNITY_ID_PLACE}" class="id_place" data-type="search_city"/>
                      <input autocomplete="off" class="form-control search_word text-place border-top-none" type="text" value="${COMMUNITY_PLACE}" name="place" data-type="search_city"'>
                      <div class="select-place" data-type="search_city"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="id_sport_type">${STR_SPORT_TYPE}</label>
                    <div class="col-lg-6">
                      <input type="hidden" name="id_sport" class="id_place" value="${COMMUNITY_ID_PLACE}" data-type="search_sport"/>
                      <input autocomplete="off" class="form-control search_word text-place border-top-none" type="text" value="${COMMUNITY_SPORT}" name="sport" data-type="search_sport" >
                      <div class="select-place" data-type="search_sport"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <!--<h3>${STR_PHOTO}</h3>-->
                      <img id="preview_ava" border="0" width="200" src="${COMMUNITY_AVATAR}">
                      <div class="file_upload">
                        <button type="button" id='avatar'>${BUTTON_EDIT_PHOTO}</button>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <!--<h3>${STR_COVER_IMAGE}</h3>-->
                      <img id="preview_cover" border="0" width="200" src="${COMMUNITY_COVER_PAGE}">
                      <div class="file_upload">
                        <button type="button" id='cover'>${BUTTON_CHANGE_COVER}</button>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="file_ava" id='file_ava_src' value="${COMMUNITY_AVATAR}">
                  <input type="hidden" name="file_cover" id='file_cover_src' value="${COMMUNITY_COVER_PAGE}">
                </div>
                <div id="administrators">
                  <center>
                    <h2>${STR_COMMUNITY_ADMINISTRATORS}</h2>
                  </center>
                  <div class="possible-friend">
                    <!-- BEGIN row_administrators -->
                    <div class="col-xs-6 possible-friend-cart"> <a class="possible-avatar" href="./?task=profile&user_id=${ID_USER}"><img src="${AVATAR}" alt=""> </a> <a href="./?task=profile&user_id=${ID_USER}">
                      <h5><strong>${FIRSTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online
                        <!-- END IF -->
                        ' data-num='${ID_USER}'></span> <br />
                        ${LASTNAME}</strong></h5>
                      </a>
                      <p>${CITY}</p>
                      <a href="./?task=profile&user_id=${ID_USER}&q=messages&sel=${SEL}"><b></b></a><br>
                      <div class='control'> <span> <a onclick='remove_admin(${ID_USER},${ID_COMMUNITY})' data-tooltip="${STR_REMOVE_FROM_ADMINISTRATORS}"><img src='./templates/images/icon-krest.png'/></a> </span> </div>
                    </div>
                    <!-- END row_administrators -->
                  </div>
                </div>
                <div id="privacy">
                  <center>
                    <h2>${STR_COMMUNITY_PRIVACY}</h2>
                  </center>
                  <br>
                  <div class="form-group">
                    <label class="col-lg-4 control-label" for="community[permission_wall]">${STR_COMMUNITY_FEED}</label>
                    <div class="col-lg-7">
                      <div class="styled-select styled-select-4">
                        <select class="form-control form-primary" name="community[permission_wall]">
                          <option value="0" <!-- IF '${PERMISSION_WALL}' == '' -->selected="selected"<!-- END IF -->>${STR_PERMISSION_WALL_OPEN}</option>
                          <option value="1" <!-- IF '${PERMISSION_WALL}' == '1' -->selected="selected"<!-- END IF -->>${STR_PERMISSION_WALL_DISABLE}</option>
                          <option value="2" <!-- IF '${PERMISSION_WALL}' == '2' -->selected="selected"<!-- END IF -->>${STR_PERMISSION_WALL_LIMETED}</option>
                          <option value="3" <!-- IF '${PERMISSION_WALL}' == '3' -->selected="selected"<!-- END IF -->>${STR_PERMISSION_WALL_CLOSED}</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-lg-1 paddingNone'> <img src='./templates/images/icon-what.png' class='what_icon' data-tooltip='${STR_PERMISSION_WALL_HINT}' /> </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-4 control-label" for="community[permission_photo]">${STR_PERMISSION_PHOTOS}</label>
                    <div class="col-lg-7">
                      <div class="styled-select styled-select-4">
                        <select class="form-control form-primary" name="community[permission_photo]">
                          <option value="0" <!-- IF '${PERMISSION_PHOTO}' == '' -->selected="selected"<!-- END IF -->>${STR_PERMISSION_PHOTO_OPEN}</option>
                          <option value="1" <!-- IF '${PERMISSION_PHOTO}' == '1' -->selected="selected"<!-- END IF -->>${STR_PERMISSION_PHOTO_DISABLE}</option>
                          <option value="2" <!-- IF '${PERMISSION_PHOTO}' == '2' -->selected="selected"<!-- END IF -->>${STR_PERMISSION_PHOTO_LIMITED}</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-lg-1 paddingNone'> <img src='./templates/images/icon-what.png' class='what_icon' data-tooltip='${STR_PERMISSION_PHOTO_HINT}' /> </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-4 control-label" for="community[permission_video]">${STR_PERMISSION_VIDEO}</label>
                    <div class="col-lg-7">
                      <div class="styled-select styled-select-4">
                        <select class="form-control form-primary" name="community[permission_video]">
                          <option value="0" <!-- IF '${PERMISSION_VIDEO}' == '' -->selected="selected"<!-- END IF -->>${STR_PERMISSION_VIDEO_OPEN}</option>
                          <option value="1" <!-- IF '${PERMISSION_VIDEO}' == '1' -->selected="selected"<!-- END IF -->>${STR_PERMISSION_VIDEO_DISABLE}</option>
                          <option value="2" <!-- IF '${PERMISSION_VIDEO}' == '2' -->selected="selected"<!-- END IF -->>${STR_PERMISSION_VIDEO_LIMITED}</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-lg-1 paddingNone'> <img src='./templates/images/icon-what.png' class='what_icon' data-tooltip='${STR_PERMISSION_VIDEO_HINT}' /> </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-4 control-label" for="community[type]">${STR_TYPE_COMMUNITY}</label>
                    <div class="col-lg-7">
                      <div class="styled-select styled-select-4">
                        <select class="form-control form-primary" name="community[type]">
                          <option value="0" <!-- IF '${TYPE}' == '' -->selected="selected" <!-- END IF -->>${STR_COMMUNITY_TYPE_OPEN}</option>
                          <option value="1" <!-- IF '${TYPE}' == '1' -->selected="selected" <!-- END IF -->>${STR_COMMUNITY_TYPE_CLOSED}</option>
                          <option value="2" <!-- IF '${TYPE}' == '2' -->selected="selected" <!-- END IF -->>${STR_COMMUNITY_TYPE_PRIVATE}</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-lg-1 paddingNone'> <img src='./templates/images/icon-what.png' class='what_icon' data-tooltip='${STR_TYPE_HINT}' /> </div>
                  </div>
                  <script>selectAction();</script>
                </div>
                <div id="blacklist">
                  <center>
                    <h2>${STR_COMMUNITY_BLACK_LIST}</h2>
                  </center>
                  <div class="possible-friend">
                    <!-- BEGIN row_blocked -->
                    <div class="col-xs-6 possible-friend-cart"> <a class="possible-avatar" href="./?task=profile&user_id=${ID_USER}"><img src="${AVATAR}" alt=""> </a> <a href="./?task=profile&user_id=${ID_USER}">
                      <h5><strong>${FIRSTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online
                        <!-- END IF -->
                        ' data-num='${ID_USER}'></span> <br />
                        ${LASTNAME}</strong></h5>
                      </a>
                      <p>${CITY}</p>
                      <a href="./?task=profile&user_id=${ID_USER}&q=messages&sel=${SEL}"><b></b></a><br>
                      <div class='control'> <span> <a onclick='remove_black_community(${ID_USER},${ID_COMMUNITY})' data-tooltip="${STR_UNBLOCK_USER}"><img src='./templates/images/icon-krest.png'/></a> </span> </div>
                    </div>
                    <!-- END row_blocked -->
                  </div>
                </div>
              </div>
              <div class="form-group center_text">
                <input class="btn-form save-button" type="submit" value="${BUTTON_SAVE_CHANGES}">
              </div>
            </form>
          </div>
          <!-- ELSE -->
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
              <input type="hidden" name="commentable_type" value="team">
              <input type="hidden" name="content_id" value="${ID_COMMUNITY}">
              <input type="hidden" name="user_id" value="${ID_USER}">
              <input type="hidden" name="parent_id" value="0">
              <input type="file"  class="file_name" name="file_name[]" data-num="${ID_COMMUNITY}" multiple/>
              <textarea id="comment" name="comment" data-num="${ID_COMMUNITY}" class='ahref_input' placeholder="${STR_WHATS_INTERESTING}"></textarea>
              <div class="smile-files"> <a id="smilesBtn" class="smile smilesBtn" data-num="${ID_COMMUNITY}"><img src="./templates/images/smile.png" alt=""></a> <a href="#" class="files" data-num="${ID_COMMUNITY}" data-tooltip="Прикрепить изображение"><img src="./templates/images/files.png" alt=""></a>
                <div class="smilesChoose" data-num="${ID_COMMUNITY}"></div>
              </div>
              <input id="submit" type="submit">
              <!-- IF '${ADMIN}' == 'yes' -->
              <div class="col-lg-6">
                  <div class="checkbox team_check">
                    <input id="team_check" type="checkbox" hidden="" checked="checked" name="author_community" value="1">
                    <label for="team_check"></label>
                  </div>
                  <label class="col-lg-3 control-label label_team_check" for="author_community">подпись</label>
                </div>
				<!-- END IF -->
              <div class='files_block' data-num="${ID_COMMUNITY}"></div>
            </form>
          </div>
          <!-- END IF -->
          <!-- INCLUDE block_photowindow.tpl -->
          <div id="addCommentContainers" data-type='team'></div>
          <!-- BEGIN row_comments -->
          <!-- IF '${ID_PARENT}' == '0' -->
          <div id="message-${ID}" data-item="${ID_PARENT}" class="message"> <img src="${COMMENT_AVATAR}" alt="" class="img-account">
            <!-- IF '${ID_USER_SESSION}' == '${ID_CONTENT}' -->
            <div class="del_mess" data-item="${ID}"></div>
            <!-- ELSE -->
            <!-- IF '${ID_USER}' == '${ID_USER_SESSION}' -->
            <div class="del_mess" data-item='${ID}'></div>
            <!-- END IF -->
            <!-- END IF -->
			
            <!-- IF '${BEHALFABLE_TYPE}' == 'group' -->
			<h5 class="name"><a href="./?task=teams&community_id=${ID_COMMUNITY}">${COMMENT_NAME}</a></h5>
            <!-- ELSE -->
			<h5 class="name"><a href="./?task=profile&user_id=${ID_USER}">${COMMENT_NAME}</a><span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online
            <!-- END IF -->' data-num='${ID_USER}'></span></h5>
            <!-- END IF -->		
			
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
            <!-- IF '${SHOW_REPLY_FORM}' == 'show' -->
            <a id="reply-${ID}" class="reply" data-item="${ID}"> ${STR_REPLY}</a>
            <!-- END IF -->
            <!-- IF '${LIKES_SHOW}' == '' -->
            <a id="tell-comment-${ID}" class="tell" data-item="${ID}" data-type="comment">${NUMBERTELL}</a> <a id="like-comment-${ID}" class="liked" data-item="${ID}" data-type="comment">${NUMBERLIKED}</a>
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
              <div class="message-account"> <img src="${COMMENT_AVATAR}" alt="" class="img-account">
			   <!-- IF '${BEHALFABLE_TYPE}' == 'team' -->
			    <h5 class="name"><a href="./?task=teams&community_id=${ID_COMMUNITY}">${COMMUNITY_NAME}</a></h5>
                
                <!-- ELSE -->
				<h5 class="name"><a href="./?task=profile&user_id=${ID_USER}">${COMMENT_NAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online
                  <!-- END IF -->
                  ' data-num='${ID_USER}'></span><br>
                 </a></h5>
               
                <!-- END IF -->
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
              <!-- <a id="reply-${ID}" class="reply" data-item="${ID}"> ${STR_REPLY}</a> 
			  <a id="tell-comment-${ID}" class="tell" data-item="${ID}" data-type='comment'>${NUMBERTELL}</a> 
			  <a id="like-comment-${ID}" class="liked" data-item="${ID}" data-type='comment'>${NUMBERLIKED}</a> -->
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
	ReplyForm += '<input type="hidden" name="commentable_type" value="team">';
	ReplyForm += '<input type="hidden" name="content_id" value="${ID_COMMUNITY}">';
	ReplyForm += '<input type="hidden" name="user_id" value="${ID_USER}">';
	ReplyForm += '<input type="hidden" name="parent_id" value="' + IdComment + '">';
    ReplyForm += '<input type="file" class="file_name" name="file_name[]" data-num="' + IdComment + '" multiple/>';
	ReplyForm += '<input id="comment" name="comment" type="text" data-num="' + IdComment + '" placeholder="${STR_YOUR_COMMENT}">';					
	ReplyForm += '<div class="smile-files">';					
	ReplyForm += '<a id="smilesBtn" class="smile smilesBtn" data-num="' + IdComment + '"><img src="./templates/images/smile.png" alt=""></a>';		
	ReplyForm += '<a href="#" class="files" data-num="' + IdComment + '" data-tooltip="Прикрепить изображение"><img src="./templates/images/files.png" alt=""></a>';
	ReplyForm += "<div class='smilesChoose add' data-num='" + IdComment + "'></div>";     						
	ReplyForm += '</div>';
	ReplyForm += '<input type="submit" id="send-reply" class="send" value="Отправить" data-item="' + IdComment + '">'; 	
	<!-- IF '${ADMIN}' == 'yes' -->
	ReplyForm += '<div class="col-lg-6 col-lg-offset-2">';
	ReplyForm += '<div class="checkbox team_check">';
	ReplyForm += '<input id="team_check" type="checkbox" hidden="" checked="checked"  name="author_community" value="1">';
	ReplyForm += '<label for="team_check"></label>';
	ReplyForm += '</div>';
	ReplyForm += '<label class="col-lg-6 control-label label_team_check" for="author_community">подпись</label>';
	ReplyForm += '</div>';
	<!-- END IF -->     
	ReplyForm += "<div class='files_block two' data-num='" + IdComment + "'></div>";				
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
					number:settComments.number,
					offset:settComments.offset,
					commentable_type:'team',
					id:'${ID_COMMUNITY}'
				},
				success:function(data){
					$('#comment-list').find('.loading-bar').remove();
					$('#comment-list').append(data.html);
					$('.message-text').each(function(){
						$(this).emotions();
					})
				
					$('.message-reply-text').each(function(){
						$(this).emotions();
					})
				
					settComments.offset+=settComments.number;
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
            <h3>${STR_COMMUNITY_SUSPENDED}</h3>
          </div>
          <!-- END IF -->
          <!-- ELSE -->
          <!-- IF '${QUERY}' == 'create' -->
          <div class="photo-caption">
            <h3>${TITLE_PAGE}</h3>
          </div>
          <div class="job_form">
            <form autocomplete="off" class="form-horizontal create_form" enctype="multipart/form-data" method="post" action="${ACTION}">
              <input type="hidden" name="action" value="create_team">
              <div class="form-group">
                <label class="col-lg-3 control-label" for="name">${STR_NAME}</label>
                <div class="col-lg-6">
                  <input class="form-control" type="text" name="name" value="${TEAM_NAME}">
                  <label class='error_label' name="name">${STR_INCORRECT_FIELD}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label" for="about">${STR_DESCRIPTION}</label>
                <div class="col-lg-6">
                  <textarea class="form-control form-dark" rows="4" name="about">${COMMUNITY_ABOUT}</textarea>
                  <label class='error_label' name="about">${STR_INCORRECT_FIELD}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label" for="id_place">${STR_PLACE}</label>
                <div class="col-lg-6">
                  <input type="hidden" name = "id_place" class="id_place" value="${COMMUNITY_ID_PLACE}" data-type="search_city"/>
                  <input autocomplete="off" class="form-control search_word text-place border-top-none" type="text" name="place" value="${COMMUNITY_PLACE}" data-type="search_city">
                  <div class="select-place" data-type="search_city"></div>
                  <label class='error_label' name="place">${STR_INCORRECT_FIELD}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label" for="id_sport_type">${STR_SPORT_TYPE}</label>
                <div class="col-lg-6">
                  <input type="hidden" name = "id_sport" class="id_place" data-type="search_sport"/>
                  <input autocomplete="off" class="form-control search_word text-place border-top-none" type="text" name="sport" data-type="search_sport">
                  <div class="select-place" data-type="search_sport"></div>
                  <label class='error_label' name="sport">${STR_INCORRECT_FIELD}</label>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <!--<h3>${STR_PHOTO}</h3>-->
                  <img border="0" id="preview_ava" src="${COMMUNITY_AVATAR}" width="200">
                  <div class="file_upload">
                    <button type="button" id='avatar'>${BUTTON_EDIT_PHOTO}</button>
                  </div>
                </div>
                <div class="col-sm-6">
                  <!--<h3>${STR_COVER_IMAGE}</h3>-->
                  <img border="0" id="preview_cover" width="200" src="${COMMUNITY_COVER_PAGE}">
                  <div class="file_upload">
                    <button type="button" id='cover'>${BUTTON_CHANGE_COVER}</button>
                  </div>
                </div>
              </div>
              <input type="hidden" name="file_ava" id='file_ava_src' value="${TEAM_AVATAR}">
              <input type="hidden" name="file_cover" id='file_cover_src' value="${TEAM_COVER_PAGE}">
              <div class="form-group center_text">
                <input class="btn-form save-button" type="submit" value="${BUTTON}">
              </div>
            </form>
          </div>
          <!-- ELSE -->
          <!-- IF '${SHOW_SEARCH_FORM}' != '' -->
          <!-- INCLUDE communities_search_form.tpl -->
          <!-- END IF -->
          <!-- IF '${NO_MY_PAGE}'=='' -->
          <div id="tabs">
            <ul id='main-menu' class='marginBottom-40'>
              <li data-type='popular'><a href="#popular">${STR_POPULAR_COMMUNITY}</a></li>
              <li data-type='mygroups'><a href="#mygroups">${STR_MY_COMMUNITIES}
                <!-- IF '${NUMBER_MY_COMMUNITIES}' -->
                <sup> ${NUMBER_MY_COMMUNITIES}</sup>
                <!-- END IF -->
                </a></li>
              <li data-type='invited'><a href="#invited">${STR_AM_INVITED}
                <!-- IF '${NUMBER_INVITED_ME_COMMUNITIES}' -->
                <sup class='active'> ${NUMBER_INVITED_ME_COMMUNITIES}</sup>
                <!-- END IF -->
                </a></li>
            </ul>
            <div id="popular" class='paddingTop20'>
              <!-- IF '${NO_POP_COMMUNITIES}' == '' -->
              <div class="event-container">
                <div id="pop_group_list">
                  <!-- BEGIN row_pop_communities_list -->
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
                      <!-- IF '${ALLOW_EDIT}' == 'yes' -->
                      <a href="./?task=teams&community_id=${ID}&q=edit">${STR_EDIT}</a>
                      <!-- END IF -->
                      <div class="transparent"></div>
                    </div>
                  </div>
                  <!-- END row_pop_communities_list -->
                  <!-- IF '${NUMBER_POPULAR_COMMUNITIES}' > '5' -->
                  <a class="show-more" id='my-event-pop' onclick="showPopMore('team')"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
                  <!-- END IF -->
                </div>
              </div>
              <!-- ELSE -->
              <center>
                <h5>${STR_THERE_ARE_NOT_POP_COMMUNITIES}</h5>
              </center>
              <!-- END IF -->
            </div>
            <!-- END IF -->
            <div id="mygroups" class='paddingTop20'>
              <!-- IF '${NO_MY_COMMUNITIES}' == '' -->
              <div class="event-container">
                <!-- BEGIN row_my_communities_list -->
                <div class="event-item" id="community_${ID}"> <a href="./?task=teams&community_id=${ID}" class="img"><img border="0" src="${AVATAR}" alt=""></a>
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
                    <!-- IF '${ALLOW_EDIT}' == 'yes' -->
                    <a href="./?task=teams&community_id=${ID}&q=edit">${STR_EDIT}</a>
                    <!-- END IF -->
                    <div class="transparent"></div>
                  </div>
                </div>
                <!-- END row_my_communities_list -->
                <!-- IF '${NUMBER_MY_COMMUNITIES}' > '5' -->
                <a class="show-more" id='my-event' onclick="showMore('${ID_USER}','team')"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
                <!-- END IF -->
              </div>
              <!-- ELSE -->
              <center>
                <h5>${STR_YOU_HAVENT_JOINED_COMMUNITIES}</h5>
              </center>
              <!-- END IF -->
            </div>
            <!-- IF '${NO_MY_PAGE}'=='' -->
            <div id='invited' class='paddingTop20'>
              <!-- IF '${NO_INVITED_ME_COMMUNITIES}' == '' -->
              <div class="event-container">
                <!-- BEGIN row_invited_me_community_list -->
                <div class="event-item" id="community_${ID}"> <a href="./?task=teams&community_id=${ID}" class="img"><img border="0" src="${AVATAR}" alt=""></a>
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
                <!-- END row_invited_me_community_list -->
                <!-- IF '${NUMBER_INVITED_ME_COMMUNITIES}' > '5' -->
                <a class="show-more" id='my-event'><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
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
          <!-- END IF -->
          <!--End content-->
        </div>
        <!-- INCLUDE right_sitebar.tpl -->
      </div>
    </div>
  </div>
</section>
<!--END CONTENT-->
<script src="./templates/js/search.js"></script>
<!-- INCLUDE footer.tpl -->
