<!-- IF '${OPEN_PAGE}' == '' -->
<!-- INCLUDE header.tpl -->
<!--START CONTENT-->
<script>
window.avatar = '${TOP_AVATAR}';
window.user_id = '${ID_USER}';
window.content_id = '${ID_CONTENT}';
window.id_profile = <!-- IF '${PROFILE_ID_USER}'!='' --> '${PROFILE_ID_USER}' <!-- ELSE --> '${ID_USER}' <!-- END IF -->;
window.placeholder = '${STR_YOUR_COMMENT}';
window.error = '${STR_THERE_ARE_NO_MORE_ENTRIES}';
window.init = '${STR_CLICK}';
</script>
<!-- ELSE -->
<!-- INCLUDE unauthorizedheader.tpl -->
<!-- END IF -->
<!--START CONTENT-->
<script type="text/javascript" src="./templates/js/script_all.js"></script>
<!-- IF '${QUERY}' == 'messages' || '${QUERY}' == 'dialogues' -->
<script type="text/javascript" src="./templates/js/jquery.sticky-kit.min.js"></script>
<script>
	$(document).ready(function(){
		let window_h = $(window).height();
		let mess_h = window_h-310;
		$('.mess_list').css('height',mess_h);
		$('.section-for-footer').hide();
		$('.content').stick_in_parent({offset_top : 145});

		$(window).resize(function(){
			let window_h = $(window).height();
			let mess_h = window_h-310;
			$('.mess_list').css('height',mess_h);
		});
	})
</script>
<!-- END IF -->
<section class="wrapper">
<div class="container">
<div class="row">
  <div class="col-xs-12 bg">
    <!-- INCLUDE left_sitebar.tpl -->
    <div class="content padding_none" id="content">
      <!-- IF '${QUERY}' == 'messages' -->
	  <!-- INCLUDE block_photowindow.tpl -->      
      <a href="./?task=profile&user_id=${SEL}">${RECEIVER_FIRSNAME} ${RECEIVER_LASTNAME}</a>
      <a href="./?task=profile&user_id=${ID_SENDER}&q=dialogues " class='float_right'>${TO_DIALOGUES_LIST}</a>
      <div class='mess_list'>
      <!-- IF '${NO_MESSAGES}'=='' -->
        <!-- BEGIN row_messages -->
        <!-- IF '${ID_USER}' == '${ID_SENDER}' -->
        <div id="message-${ID}" class="message" >
          <div class="message-account"> <img src="${AVATAR}" alt="" class="img-account">
            <h5 class="name"><a href="./?task=profile&user_id=${ID_USER}">${FIRSTNAME} ${LASTNAME}</a></h5>
            <p class="data">${CREATED}</p>
          </div>
          <p class="message-text">
            
              <!-- IF '${CONTENT}'!='' -->
              ${CONTENT} <br>
              <!-- END IF -->
              <ul class='attach_image'>
            <!-- BEGIN row_attach_message -->
              <li><img border="0" src="${SMALL_PHOTO}" class="photo_big" data-num="${ID_PHOTO}"></li>
            <!-- END row_attach_message -->
            </ul>
          </p>
          <div class='del-message' data-item='${ID}' data-tooltip='Удалить сообщение'></div>
        </div>
        <!-- ELSE -->
        <div class="message-reply" id="message-${ID}">
          <div class="message">
            <div class="message-account"> <img src="${AVATAR}" alt="" class="img-account">
              <h5 class="name"><a href="./?task=profile&user_id=${ID_USER}">${FIRSTNAME} ${LASTNAME}</a></h5>
              <p class="data">${CREATED}</p>
            </div>
            <p class="message-reply-text"> 
              <!-- IF '${CONTENT}'!='' -->
                ${CONTENT} <br>
              <!-- END IF -->
              <ul class='attach_image'>
      			  <!-- BEGIN row_attach_reply_message -->
                <li><img border="0" src="${SMALL_PHOTO}" class="photo_big" data-num="${ID_PHOTO}"></li>
              <!-- END row_attach_reply_message -->
              </ul>
      			</p>
          </div>
        </div>
        <!-- END IF -->
        <!-- END row_messages -->
      <!-- ELSE -->
        <h5 class='no_message'>Здесь будет история переписки</h5>
      <!-- END IF -->
        <div id="message-list" data-num='${SEL}'></div>
        <div  id="addMessageContainer"></div>
        
        <div class='typing'>
          <div class='animate'>
            <img src='./templates/images/icon-news-pen-active.png'/>
          </div>
          <span>Набирает сообщение</span>
          <span class='dotten'></span>
        </div>

      </div>
	  
	  <!-- IF '${PERMISSION_MESSAGE}' == 'yes' -->
	  <div class='wrap_text_dialog'>
      <div class='ava_dialog'>
        <a href="./?task=profile&user_id=${ID_USER}"><img src='${MY_AVATAR}'/></a>
      </div>
      <div class='text_form'>
      <form id="addMessageForm" class="form-horizontal" method="POST" action="">
        <input type="hidden" name="sender_id" value="${ID_USER}">
        <input type="hidden" name="receiver_id" value="${SEL}">
        <div class="form-group">
          <div class="col-lg-7 message_textarea">
            <input type="file" class="file_name" name="file_name[]" data-num="0" multiple/>
            <textarea class="form-control form-dark padding-right" id="message" rows="4" name="message"></textarea>
          </div>
          <div class="smile-files"> 
            <a id="smilesBtn" class="smile smilesBtn" data-num="0"><img src="./templates/images/smile.png" alt=""></a> 
            <a href="#" class="files" data-num="0" data-tooltip='Прикрепить изображение'><img src="./templates/images/files.png" alt=""></a> 
            <div class="smilesChoose" data-num="0"></div>
          </div>
        </div>
        <div class="control static_control">
          <div class="smilesChoose static_smile block_smile"></div><input class="btn btn-success" id="submit" type="submit" value="${STR_SEND}">
        </div>

          <div class='files_block' data-num="0"></div>
      </form>
      </div>
      <div class='ava_dialog'>
        <a href="./?task=profile&user_id=${SEL}"><img src='${RECEIVER_AVATAR}'/></a>
      </div>
    </div>
    <!-- ELSE -->
    <center><h4>Вы не можете написать сообщение пользователю</h4></center>
	  <!-- END IF -->
	  
      </div>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
      <!-- ELSE IF '${QUERY}' == 'dialogues' -->
      <div class="photo-caption">
        <h3>${TITLE_PAGE}</h3>
      </div>
      <div class="row dialogues new_dialog" data-status='new'>
        <h5><img src='./templates/images/message-sitebar.png'/> Начать новый диалог</h5>
      </div>
      <div class='mess_list'>
      <div class='container_dialog hide' id='new_dialogue'> 
      <!-- IF '${NO_FRIENDS}' == '' -->
        <!-- BEGIN row_my_friends -->
            <div class="row dialogues " data-num='${ID_FRIEND}' onclick='window.location.href = "./?task=profile&user_id=${ID_USER}&q=messages&sel=${ID_FRIEND}"'>
          <div class="col-md-12"> 
            <a href="./?task=profile&user_id=${ID_FRIEND}"> <img src="${AVATAR}" width="50" alt="" class="img-account float_left">
              <div class="fromwho"> ${FIRSTNAME} ${LASTNAME}
              </div>
            </a> 
          </div>
          </div>
        <!-- END row_my_friends -->
      <!-- ELSE -->
      <center><h4>У Вас пока нет друзей</h4></center>
        <center><h5><a href='./?task=friends'>Посмотреть возможных друзей</a></h5></center>
      <!-- END IF -->
      </div>
      <div class='container_dialog' id='old_dialogue'>
      <!-- IF '${NO_DIALOGUES}' == '' -->
        <!-- BEGIN row_dialogues -->
         
        <div class="row dialogues " data-num='${ID_RECEIVER}' onclick='window.location.href = "./?task=profile&user_id=${ID_USER}&q=messages&sel=${ID_RECEIVER}"'>
          <div class="col-md-4"> 
            <a href="./?task=profile&user_id=${ID_RECEIVER}"> <img src="${AVATAR}" width="50" alt="" class="img-account float_left">
              <div class="fromwho name_dialog"> ${FIRSTNAME}<br>
                ${LASTNAME}<br>
                <span>${CREATED_AT}</span> 
              </div>
            </a> 
          </div>
          <div class="col-md-8 "> 
                <div class='block-dialog'>
                  <span class='ahref <!-- IF '${STATUS}' == '0' --> status_red<!-- END IF -->'> 
                      <img src="${LAST_MSG_AVATAR}" alt="" class="img-mess-dialog"> 
                      ${CONTENT}  
                  </span>
                </div>
          </div>
          <div class='del-dialog' data-item='${ID_RECEIVER}' data-tooltip='Очистить диалог'></div>
        </div>
        
        <!-- END row_dialogues -->
      <!-- ELSE -->
      <center><h4 class='no_dialogues'>У Вас пока нет диалогов</h4></center>
      <!-- END IF -->
      </div>


      </div>
      </div>
      <!-- ELSE -->
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<!-- INCLUDE block_photowindow.tpl -->
      <!-- INCLUDE top_user_profile.tpl -->
	  
	 <!-- IF '${BLOCK_PAGE}' != '' --><br><br>
      <div class="photo-caption">
		    <h3>Действие аккаунта приостановлено!</h3>
      </div>
	 <!-- ELSE IF '${CLOSED_PAGE}' != '' --><br><br>
      <div class="photo-caption">
		    <h3>Страница пользователя удалена.<br>Информация недоступна.</h3>
      </div> 
	 <!-- ELSE -->
	 
      <div class='overlay' id='overlay'>
        <div class='overlay-back back_two'>Закрыть</div>
        <div class='overlay-back prev'></div>
	        <div class='photo_big_wrap' id='foto_wind'> <img src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==' class='photo_wrap next'/>
          <div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>
        </div>
      </div>
	  
	  
	  <!-- IF '${PERMISSION_WALL}' == 'yes' -->
	  <script type="text/javascript" src="./templates/js/autoresize.js"></script>
	   <script>
      jQuery('textarea').autoResize({
                   extraSpace : 0
              });
 
     </script>
	<!-- IF '${OPEN_PAGE}' == '' -->
	
      <div class="message-content">
        <form autocomplete="off" id="addCommentForm" method="POST" action="" enctype="multipart/form-data">
          <input type="hidden" name="commentable_type" value="user">
          <input type="hidden" name="content_id" value="${ID_CONTENT}">
          <input type="hidden" name="user_id" value="${ID_USER}">
          <input type="hidden" name="parent_id" value="0">
          <input type="file"  class="file_name" name="file_name[]" data-num="${ID_CONTENT}" multiple/>
          <textarea id="comment" name="comment" data-num="${ID_CONTENT}" class='ahref_input' placeholder="${STR_WHATS_INTERESTING}"></textarea>
          <div class="smile-files"> 
              <a id="smilesBtn" class="smile smilesBtn" data-num="${ID_CONTENT}"><img src="./templates/images/smile.png" alt=""></a> 
              <a href="#" class="files" data-num="${ID_CONTENT}" data-tooltip="Прикрепить изображение"><img src="./templates/images/files.png" alt=""></a> 
              <div class="smilesChoose" data-num="${ID_CONTENT}"></div></div>
          <input id="submit" type="submit">
          <div class='link_attach' data-num="${ID_CONTENT}">
          </div>
          <div class='files_block' data-num="${ID_CONTENT}"></div>

        </form>
      </div>
	  
      <div id="addCommentContainers" data-type='user'></div>
	   <!-- END IF -->
	  
	  
      <!-- BEGIN row_comments -->
      <!-- IF '${ID_PARENT}' == '0' -->
      <div id="message-${ID}" data-item="${ID_PARENT}" class="message">
        <div class="message-account"><img src="${AVATAR}" alt="" class="img-account">
          <!-- IF '${ID_USER_SESSION}' == '${ID_CONTENT}' && '${OPEN_PAGE}' == '' -->
          <div class="del_mess" data-item="${ID}"></div>
          <!-- ELSE -->
          <!-- IF '${ID_USER}' == '${ID_USER_SESSION}' && '${OPEN_PAGE}' == '' -->
          <div class="del_mess" data-item='${ID}'></div>
          <!-- END IF -->
          <!-- END IF -->
          <h5 class="name"><a href="./?task=profile&user_id=${ID_USER}">${FIRSTNAME} ${LASTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online<!-- END IF -->' data-num='${ID_USER}'></span></a></h5>
          <p class="data">${CREATED}</p>
        </div>
        <!-- IF '${CONTENT}'!='' -->
        <p class="message-text">
              ${CONTENT} <br>
        </p>
              <!-- END IF -->
            <ul class='attach_image'>
          <!-- BEGIN row_attach -->
              <li><img border="0" src="${SMALL_PHOTO}" class="photo_big" data-num="${ID_PHOTO}"></li>
          <!-- END row_attach -->
            </ul>
			
			<!-- IF '${OPEN_PAGE}' == '' -->
			
        <a id="reply-${ID}" class="reply" data-item="${ID}"> ${STR_REPLY}</a>
        <!-- IF '${BUTTON_SHARE}' == 'show' -->
        <a id="tell-comment-${ID}" class="tell" data-item="${ID}" data-type='comment'>${NUMBERTELL}</a>
        <!-- END IF -->
        <a id="like-comment-${ID}" class="liked" data-item="${ID}" data-type='comment'>${NUMBERLIKED}</a> 
		 <!-- END IF -->
	
		</div>
      <!-- ELSE -->
      <div class="message-reply message" id="message-${ID}" data-item="${ID_PARENT}">
        <!-- IF '${ID_USER_SESSION}' == '${ID_CONTENT}' && '${OPEN_PAGE}' == '' -->
        <div class='del_mess' data-item='${ID}'></div>
        <!-- ELSE -->
        <!-- IF '${ID_USER}' == '${ID_USER_SESSION}' && '${OPEN_PAGE}' == '' -->
        <div class='del_mess' data-item='${ID}'></div>
        <!-- END IF -->
        <!-- END IF -->
        <div class="message">
          <div class="message-account"> <img src="${AVATAR}" alt="" class="img-account">
            <h5 class="name"><a href="./?task=profile&user_id=${ID_USER}">${FIRSTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online<!-- END IF -->' data-num='${ID_USER}'></span><br>${LASTNAME}</a></h5>
            <p class="data">${CREATED}</p>
          </div>
          <!-- IF '${CONTENT}'!='' -->
          <p class="message-reply-text">
              ${CONTENT} <br>
            </p>
              <!-- END IF -->
            <ul class='attach_image'>
            <!-- BEGIN row_reply_attach -->
              <li><img border="0" src="${SMALL_PHOTO}" class="photo_big" data-num="${ID_PHOTO}"></li>
            <!-- END row_reply_attach -->
            </ul>
          
        </div>
      </div>
      <!-- END IF -->
      <!-- END row_comments -->
      <div id="comment-list"></div>
	   <!-- END IF -->
	  <!-- END IF -->
    </div>
	
    <!-- END IF -->
    <!-- INCLUDE right_sitebar.tpl -->
  </div>
</div>
</section>
<script type="text/javascript" src='./templates/js/profile.js'></script>
<!--END CONTENT-->
<!-- INCLUDE footer.tpl -->
