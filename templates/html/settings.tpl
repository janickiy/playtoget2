<!-- INCLUDE header.tpl -->
<!--START CONTENT-->
<script type="text/javascript" src="./templates/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="./templates/js/jquery.form.min.js"></script>
<script type="text/javascript" src="./templates/js/script_all.js"></script>
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

<!-- IF '${ERROR_ALERT}' != '' --><div class='save_window_ok hiden'>${ERROR_ALERT}</div><!-- END IF -->
<!-- IF '${MSG_ALERT}' != '' --><div class='save_window_fail hiden'>${MSG_ALERT}</div><!-- END IF -->

<!-- INCLUDE uploadAvatar.tpl -->

<section class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-xs-12  bg">
 <!-- INCLUDE left_sitebar.tpl -->
        <div class="content friends">
          <!-- INCLUDE top_user_profile.tpl -->
          <div class="photo-caption">
              <h3>${TITLE_PAGE}</h3>
          </div>
          <div class='job_form'>
          <form autocomplete="off" class="form-horizontal" enctype="multipart/form-data" method="post" action="${ACTION}">
            <div id="tabs" class='five'>
              <ul>
                <li><a href="#contacts">${STR_CONTACTS}</a></li>
                <li><a href="#privacy">${STR_PRIVACY}</a></li>
                <li><a href="#notifications">${STR_NOTIFICATIONS}</a></li>
                <li><a href="#security">${STR_SECURITY}</a></li>
                <li><a href="#blacklist">${STR_BLACK_LIST}</a></li>
              </ul>
              <div id="contacts" class='marginTopNone'>
                
                <div class="photo-caption">
                    <center><h2>${STR_CONTACTS}</h2></center><br>
                </div>
                <p>Оставьте свои контактные данные, чтобы в дальнейшем получать е-мэйл уведомления от своей команды.</p>
                
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[contact_email]">${STR_EMAIL}</label>
                  <div class="col-lg-7">
                    <input class="form-control" type="text" value="${CONTACT_EMAIL}" name="user[contact_email]">
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[phone]">${STR_CELLPHONE}</label>
                  <div class="col-lg-7">
                    <input class="form-control" type="text" value="${PHONE}" name="user[phone]">
                  </div>
                </div>
				
				<div class="form-group">
                  <label class="col-lg-4 control-label" for="user[skype]">Skype</label>
                  <div class="col-lg-7">
                    <input class="form-control" type="text" value="${SKYPE}" name="user[skype]">
                  </div>
                </div>
				
				<div class="form-group">
                  <label class="col-lg-4 control-label" for="user[website]">${STR_PERSONAL_WEBSITE}</label>
                  <div class="col-lg-7">
                    <input class="form-control" type="text" value="${WEBSITE}" name="user[website]">
                  </div>
                </div>
				
				
				
              </div>
              <div id="privacy" class='marginTopNone'>
                <div class="photo-caption">
                    <center><h2>${STR_PRIVACY}</h2></center><br>
                </div>
                
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[permission_send_message]">${STR_PERMISSION_SEND_MESSAGE}</label>
                  <div class="col-lg-7">
                    <div class="styled-select styled-select-4">
                      <select id="user_permission_send_message" class="form-control form-primary" name="user[permission_send_message]">
                        <option value="0" <!-- IF '${PERMISSION_SEND_MESSAGE}' == '' -->selected="selected"<!-- END IF -->>${STR_ALL}</option>
                        <option value="1" <!-- IF '${PERMISSION_SEND_MESSAGE}' == '1' -->selected="selected"<!-- END IF -->>${STR_FRIENDS}</option>
                        <option value="2" <!-- IF '${PERMISSION_SEND_MESSAGE}' == '2' -->selected="selected"<!-- END IF -->>${STR_NOBODY}</option>  
                      </select>
                    </div>
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[permission_view_profile]">${STR_PERMISSION_VIEW_PROFILE}</label>
                  <div class="col-lg-7">
                    <div class="styled-select styled-select-4">
                      <select id="user_permission_send_message" class="form-control form-primary" name="user[permission_view_profile]">
                        <option value="0" <!-- IF '${PERMISSION_VIEW_PROFILE}' == '' -->selected="selected"<!-- END IF -->>${STR_ALL}</option>
                        <option value="1" <!-- IF '${PERMISSION_VIEW_PROFILE}' == '1' -->selected="selected"<!-- END IF -->>${STR_FRIENDS}</option>
                        <option value="2" <!-- IF '${PERMISSION_VIEW_PROFILE}' == '2' -->selected="selected"<!-- END IF -->>${STR_NOBODY}</option>
                      </select>
                    </div>
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[permission_view_friends]">${STR_PERMISSION_VIEW_FRIENDS}</label>
                  <div class="col-lg-7">
                    <div class="styled-select styled-select-4">
                      <select id="user_permission_view_wall" class="form-control form-primary" name="user[permission_view_friends]">
                        <option value="0" <!-- IF '${PERMISSION_VIEW_FRIENDS}' == '0' -->selected="selected"<!-- END IF -->>${STR_ALL}</option>
                        <option value="1" <!-- IF '${PERMISSION_VIEW_FRIENDS}' == '1' -->selected="selected"<!-- END IF -->>${STR_FRIENDS}</option>
                        <option value="2" <!-- IF '${PERMISSION_VIEW_FRIENDS}' == '2' -->selected="selected"<!-- END IF -->>${STR_NOBODY}</option>
                      </select>
                    </div>
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[permission_view_photo]">${STR_PERMISSION_VIEW_PHOTO}</label>
                  <div class="col-lg-7">
                    <div class="styled-select styled-select-4">
                      <select id="user_permission_view_photo" class="form-control form-primary" name="user[permission_view_photo]">
                        <option value="0" <!-- IF '${PERMISSION_VIEW_PHOTO}' == '0' -->selected="selected"<!-- END IF -->>${STR_ALL}</option>
                        <option value="1" <!-- IF '${PERMISSION_VIEW_PHOTO}' == '1' -->selected="selected"<!-- END IF -->>${STR_FRIENDS}</option>
                        <option value="2" <!-- IF '${PERMISSION_VIEW_PHOTO}' == '2' -->selected="selected"<!-- END IF --> >${STR_NOBODY}</option>
                      </select>
                    </div>
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[permission_view_video]">${STR_PERMISSION_VIEW_VIDEO}</label>
                  <div class="col-lg-7">
                    <div class="styled-select styled-select-4">
                      <select id="user_permission_view_photo" class="form-control form-primary" name="user[permission_view_video]">
                        <option value="0" <!-- IF '${PERMISSION_VIEW_VIDEO}' == '0' -->selected="selected"<!-- END IF -->>${STR_ALL}</option>
                        <option value="1" <!-- IF '${PERMISSION_VIEW_VIDEO}' == '1' -->selected="selected"<!-- END IF -->>${STR_FRIENDS}</option>
                        <option value="2" <!-- IF '${PERMISSION_VIEW_VIDEO}' == '2' -->selected="selected"<!-- END IF -->>${STR_NOBODY}</option>
                      </select>
                    </div>
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[permission_view_wall]">${STR_PERMISSION_VIEW_WALL}</label>
                  <div class="col-lg-7">
                    <div class="styled-select styled-select-4">
                      <select id="user_permission_view_wall" class="form-control form-primary" name="user[permission_view_wall]">
                        <option value="0" <!-- IF '${PERMISSION_VIEW_WALL}' == '0' -->selected="selected"<!-- END IF -->>${STR_ALL}</option>
                        <option value="1" <!-- IF '${PERMISSION_VIEW_WALL}' == '1' -->selected="selected"<!-- END IF -->>${STR_FRIENDS}</option>
                        <option value="2" <!-- IF '${PERMISSION_VIEW_WALL}' == '2' -->selected="selected"<!-- END IF -->>${STR_NOBODY}</option>
                      </select>
                    </div>
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[permission_comment_photo]">${STR_PERMISSION_COMMENT_PHOTO}</label>
                  <div class="col-lg-7">
                    <div class="styled-select styled-select-4">
                      <select id="user_permission_comment_photo" class="form-control form-primary" name="user[permission_comment_photo]">
                        <option value="0" <!-- IF '${PERMISSION_COMMENT_PHOTO}' == '0' -->selected="selected"<!-- END IF -->>${STR_ALL}</option>
                        <option value="1" <!-- IF '${PERMISSION_COMMENT_PHOTO}' == '1' -->selected="selected"<!-- END IF -->>${STR_FRIENDS}</option>
                        <option value="2" <!-- IF '${PERMISSION_COMMENT_PHOTO}' == '2' -->selected="selected"<!-- END IF -->>${STR_NOBODY}</option>
                      </select>
                    </div>
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[permission_comment_video]">${STR_PERMISSION_COMMENT_VIDEO}</label>
                  <div class="col-lg-7">
                    <div class="styled-select styled-select-4">
                      <select id="user_permission_comment_video" class="form-control form-primary" name="user[permission_comment_video]">
                        <option value="0" <!-- IF '${PERMISSION_COMMENT_VIDEO}' == '0' -->selected="selected"<!-- END IF -->>${STR_ALL}</option>
                        <option value="1" <!-- IF '${PERMISSION_COMMENT_VIDEO}' == '1' -->selected="selected"<!-- END IF -->>${STR_FRIENDS}</option>
                        <option value="2" <!-- IF '${PERMISSION_COMMENT_VIDEO}' == '2' -->selected="selected"<!-- END IF -->>${STR_NOBODY}</option>
                      </select>
                    </div>
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-lg-4 control-label" for="user[permission_comment_wall]">${STR_PERMISSION_COMMENT_WALL}</label>
                  <div class="col-lg-7">
                    <div class="styled-select styled-select-4">
                      <select id="user_permission_comment_wall" class="form-control form-primary" name="user[permission_comment_wall]">
                        <option value="0" <!-- IF '${PERMISSION_COMMENT_WALL}' == '0' -->selected="selected"<!-- END IF -->>${STR_ALL}</option>
                        <option value="1" <!-- IF '${PERMISSION_COMMENT_WALL}' == '1' -->selected="selected"<!-- END IF -->>${STR_FRIENDS}</option>
                        <option value="2" <!-- IF '${PERMISSION_COMMENT_WALL}' == '2' -->selected="selected"<!-- END IF -->>${STR_NOBODY}</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
	
              <div id="notifications" class='marginTopNone'>
                
                <div class="photo-caption">
                    <center><h2>${STR_NOTIFICATIONS}</h2></center><br>
                </div>
                <div class="form-group">
                  <div class='col-lg-3'></div>
                  <label class="col-lg-4 control-label" for="user[notification_friends_request]">${STR_FRIENDS_REQUEST}</label>
                  <div class="col-lg-5">
                      <div class="checkbox">
                          <input id="checkbox-find-comand" type="checkbox" hidden="" name="user[notification_friends_request]" <!-- IF '${NOTIFICATION_FRIENDS_REQUEST}' == 'yes' -->checked="checked"<!-- END IF -->>
                          <label for="checkbox-find-comand"></label>
                      </div>

                  </div>
                </div>
                <div class="form-group">
                  <div class='col-lg-3'></div>
                  <label class="col-lg-4 control-label" for="user[notification_private_messages]">${STR_PRIVATE_MESSAGES}</label>
                  <div class="col-lg-5">
                    <div class="checkbox">
                          <input id="checkbox-find-comand" type="checkbox" hidden=""  name="user[notification_private_messages]" <!-- IF '${NOTIFICATION_PRIVATE_MESSAGES}' == 'yes' -->checked="checked"<!-- END IF -->>
                          <label for="checkbox-find-comand"></label>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class='col-lg-3'></div>
                  <label class="col-lg-4 control-label" for="user[notification_wall_comments]">${STR_WALL_COMMENTS}</label>
                  <div class="col-lg-5">
                    <div class="checkbox">
                          <input id="checkbox-find-comand" type="checkbox" hidden=""  name="user[notification_wall_comments]" <!-- IF '${NOTIFICATION_WALL_COMMENTS}' == 'yes' -->checked="checked"<!-- END IF -->>
                          <label for="checkbox-find-comand"></label>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class='col-lg-3'></div>
                  <label class="col-lg-4 control-label" for="user[notification_picture_comments]">${STR_PICTURE_COMMENTS}</label>
                  <div class="col-lg-5">
                    <div class="checkbox">
                          <input id="checkbox-find-comand" type="checkbox" hidden=""  name="user[notification_picture_comments]" <!-- IF '${NOTIFICATION_PICTURE_COMMENTS}' == 'yes' -->checked="checked"<!-- END IF -->>
                          <label for="checkbox-find-comand"></label>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class='col-lg-3'></div>
                  <label class="col-lg-4 control-label" for="user[notification_video_comments]">${STR_VIDEO_COMMENTS}</label>
                  <div class="col-lg-5">
                    <div class="checkbox">
                          <input id="checkbox-find-comand" type="checkbox" hidden=""  name="user[notification_video_comments]" <!-- IF '${NOTIFICATION_VIDEO_COMMENTS}' == 'yes' -->checked="checked"<!-- END IF -->>
                          <label for="checkbox-find-comand"></label>
                      </div>
                  </div>
                </div>				
				
				<div class="form-group">
                  <div class='col-lg-3'></div>
                  <label class="col-lg-4 control-label" for="user[notification_answers_in_comments]">${STR_NOTIFICATION_ANSWERS_IN_COMMENTS}</label>
                  <div class="col-lg-5">
                    <div class="checkbox">
                          <input id="checkbox-find-comand" type="checkbox" hidden=""  name="user[notification_answers_in_comments]" <!-- IF '${NOTIFICATION_ANSWERS_IN_COMMENTS}' == 'yes' -->checked="checked"<!-- END IF -->>
                          <label for="checkbox-find-comand"></label>
                      </div>
                  </div>
                </div>				
				
                <div class="form-group">
                  <div class='col-lg-3'></div>
                  <label class="col-lg-4 control-label" for="user[notification_events]">${STR_EVENTS}</label>
                  <div class="col-lg-5">
                    <div class="checkbox">
                          <input id="checkbox-find-comand" type="checkbox" hidden=""  name="user[notification_events]" <!-- IF '${NOTIFICATION_EVENTS}' == 'yes' -->checked="checked"<!-- END IF -->>
                          <label for="checkbox-find-comand"></label>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class='col-lg-3'></div>
                  <label class="col-lg-4 control-label" for="user[notification_birthdays]">${STR_BIRTHDAYS}</label>
                  <div class="col-lg-5">
                    <div class="checkbox">
                        <input id="checkbox-find-comand" type="checkbox" hidden=""  name="user[notification_birthdays]" <!-- IF '${NOTIFICATION_BIRTHDAYS}' == 'yes' -->checked="checked"<!-- END IF -->>
                        <label for="checkbox-find-comand"></label>
                      </div>
                  </div>
                </div>
              </div>			  
			 
              <div id="blacklist" class='marginTopNone'>
                
                <div class="photo-caption">
                    <center><h2>${STR_BLACK_LIST}</h2></center><br>
	  
                      <div class="possible-friend my-friend">			
            		  <!-- BEGIN row_block_users -->
                      <div class="col-xs-6 possible-friend-cart" data-num='${ID_USER}'> 
                        <a class="possible-avatar" href="./?task=profile&user_id=${ID_USER}"> <img src="${AVATAR}" alt=""> </a> 
                        <a href="./?task=profile&user_id=${ID_USER}">
                          <h5><strong>${FIRSTNAME} <br />
                            ${LASTNAME}</strong></h5>
                          </a>
                        <div class='control'>
                          <span>
                            <img src='./templates/images/icon-krest.png' alt="" onclick='remove_black_list(${ID_USER})'/>
                          </span>
                        </div>
                      </div>
                      <!-- END row_block_users -->	
				    </div>
                </div>
              </div>
              <div id="security" class='marginTopNone'>
                
                <div class="photo-caption">
                    <center><h2>${STR_SECURITY}</h2></center><br>
					<p>Журнал учета посещений. Ваш текущий IP - адрес: ${MY_IP}  </p>
					<!-- BEGIN row_logs -->
                    <p>IP: ${IP} ОС: ${OS} Браузер: ${BROWSER} Время: ${TIME}</p>
                    <!-- END row_logs -->
                </div>
              </div>
              <div class="profile-settings button">
                <input type="hidden" name="file_ava" id='file_ava_src' value=''>
                <input type="hidden" name="file_cover" id='file_cover_src' value=''>
                <button class="save-button" name="action" value="${BUTTON_APPLY}">${BUTTON_APPLY}</button>
              </div>
            </div>
          </form>

              </div>
          <!--End content-->
        </div>

<!-- INCLUDE right_sitebar.tpl -->

      </div>
    </div>
  </div>
</section>
<script>selectAction();</script>
<!--END CONTENT-->
<!-- INCLUDE footer.tpl -->
