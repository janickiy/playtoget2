<div class="relat">
  <div class="cover_page<!-- IF '${EDIT_COVER_TOP}' == 'yes' --> editable-cover-area<!-- END IF -->">

    <!--<p style='color:#fff;'>${COMMUNITY_MEMBER}</p>-->
	<!-- IF '${OPEN_PAGE}' == '' && '${BLOCK_PAGE}' == '' -->
	
    <!-- IF '${COMMUNITY_MEMBER}' == 'none' -->
    <div class="groups_button" data-num="${ID_COMMUNITY}" onclick='community(${ID_COMMUNITY},1,"");'> <span>${BUTTON_JOIN_TO_COMMUNITY}</span> </div>
    <div class="groups_button_leave hide" data-num="${ID_COMMUNITY}" onclick='community(${ID_COMMUNITY},0,"${COMMUNITY_MEMBER}");'></div>
    <!-- ELSE IF '${COMMUNITY_MEMBER}' == 'invited' -->
	   <div class="groups_button" data-num="${ID_COMMUNITY}" onclick='community(${ID_COMMUNITY},1,"${COMMUNITY_MEMBER}");'> <span>${BUTTON_ACCEPT INVITATION}</span> </div>
    <div class="groups_button_leave red" data-num="${ID_COMMUNITY}" onclick='community(${ID_COMMUNITY},0,"${COMMUNITY_MEMBER}");'></div>
	<!-- ELSE IF '${COMMUNITY_MEMBER}' == 'owner' -->
    <div class="groups_button leave_fr" data-num="${ID_COMMUNITY}" onclick='commun_fr(${ID_COMMUNITY})'>${TOP_BUTTON_INVITE_FRIENDS}</div>
    <div class="groups_button_leave" data-num="${ID_COMMUNITY}" onclick='community(${ID_COMMUNITY},0,"${COMMUNITY_MEMBER}");'></div>
    <!-- ELSE IF '${COMMUNITY_MEMBER}' == 'admin' -->
    <div class="groups_button leave_fr" data-num="${ID_COMMUNITY}" onclick='commun_fr(${ID_COMMUNITY})'>${TOP_BUTTON_INVITE_FRIENDS}</div>
    <div class="groups_button_leave" data-num="${ID_COMMUNITY}" onclick='community(${ID_COMMUNITY},0,"${COMMUNITY_MEMBER}");'></div>
    <!-- ELSE IF '${COMMUNITY_MEMBER}' == 'member' -->
    <div class="groups_button leave_fr" data-num="${ID_COMMUNITY}" onclick='commun_fr(${ID_COMMUNITY})'>${TOP_BUTTON_INVITE_FRIENDS}</div>
    <div class="groups_button_leave" data-num="${ID_COMMUNITY}" onclick='community(${ID_COMMUNITY},0,"${COMMUNITY_MEMBER}");'></div>
    <!-- ELSE IF '${COMMUNITY_MEMBER}' == 'applied' -->
    <div class="groups_button applied"> <span>${STR_YOU_HAVE_SENT_REQUEST}</span> </div>
    <div class="groups_button_leave" data-num="${ID_COMMUNITY}" onclick='community(${ID_COMMUNITY},0,"${COMMUNITY_MEMBER}");'></div>
    <!-- END IF -->
	<!-- END IF -->


	<!-- IF '${BLOCK_PAGE}' == '' && '${OPEN_PAGE}' == '' -->
    <!-- IF '${EVENT_MEMBER}' == 'none' -->
    <div class="groups_button" data-num="${ID_EVENT}" onclick='event_join(${ID_EVENT},1,"");'> <span>${BUTTON_JOIN_TO_EVENT}</span> </div>
    <div class="groups_button_leave hide" data-num="${ID_EVENT}" onclick='event_join(${ID_EVENT},0,"${EVENT_MEMBER}");'></div>
    <!-- ELSE IF '${EVENT_MEMBER}' == 'invited' -->
    <div class="groups_button" data-num="${ID_EVENT}" onclick='event_join(${ID_EVENT},1,"${EVENT_MEMBER}");'> <span>${BUTTON_ACCEPT INVITATION}</span> </div>
    <div class="groups_button_leave red" data-num="${ID_EVENT}" onclick='event_join(${ID_EVENT},0,"${EVENT_MEMBER}");'></div>
  <!-- ELSE IF '${EVENT_MEMBER}' == 'owner' -->
    <div class="groups_button leave_fr" data-num="${ID_EVENT}" onclick='event_fr(${ID_EVENT});'>${TOP_BUTTON_INVITE_FRIENDS}</div>
    <div class="groups_button_leave" data-num="${ID_EVENT}" onclick='event_join(${ID_EVENT},0,"${EVENT_MEMBER}");'></div>
    <!-- ELSE IF '${EVENT_MEMBER}' == 'admin' -->
    <div class="groups_button leave_fr" data-num="${ID_EVENT}" onclick='event_fr(${ID_EVENT});'>${TOP_BUTTON_INVITE_FRIENDS}</div>
    <div class="groups_button_leave" data-num="${ID_EVENT}" onclick='event_join(${ID_EVENT},0,"${EVENT_MEMBER}");'></div>
    <!-- ELSE IF '${EVENT_MEMBER}' == 'member' -->
    <div class="groups_button leave_fr" data-num="${ID_EVENT}" onclick='event_fr(${ID_EVENT});'>${TOP_BUTTON_INVITE_FRIENDS}</div>
    <div class="groups_button_leave" data-num="${ID_EVENT}" onclick='event_join(${ID_EVENT},0,"${EVENT_MEMBER}");'></div>
    <!-- ELSE IF '${EVENT_MEMBER}' == 'applied' -->
    <div class="groups_button applied"> <span>${STR_YOU_HAVE_SENT_REQUEST}</span> </div>
    <div class="groups_button_leave" data-num="${ID_EVENT}" onclick='event_join(${ID_EVENT},0,"${EVENT_MEMBER}");'></div>
	<!-- END IF -->
	<!-- END IF -->

    <!-- IF '${EVENT_NAME}' -->
    <div class="cover-container">
      <div class='cover_back'></div>
      <img class="cover-photo" src="${EVENT_COVER_PAGE}" alt=""/></div>
    <!-- ELSE -->
    <div class="cover-container">
      <div class='cover_back'></div>
      <img class="cover-photo<!-- IF '${EDIT_COVER_TOP}' == 'yes' --> editable-cover<!-- END IF -->" <!-- IF '${EDIT_COVER_TOP}' == 'yes' -->id="preview_cover" title="Изменить обложку"<!-- END IF --> src="${PROFILE_COVER_PAGE}" alt=""/></div>
    <!-- END IF -->

    <!-- IF '${EDIT_COVER}' == 'yes' -->
    <div class='upload_cover_img'>Изменить обложку</div>
    <!-- END IF -->


    <!-- IF '${SHOW_MESSAGES_LINK}' == 'show' -->
    <div class="cover-buttons"> <a class="cover-send-message" href="./?task=profile&user_id=${ID_USER}&q=messages&sel=${PROFILE_ID_USER}">
      <button class="btn btn-primary">${BUTTON_TOP_SEND_MESSAGE}</button>
      </a>
      <!-- END IF -->
      <!-- IF '${SHOW_FRIENDS_BUTTON}' == 'show' -->
      <div id="friends_button">
        <!-- IF '${FRIENDSSTATUS}' == 'invitation_sent' -->
        <button class="btn btn-primary">${STR_INVITATION_SENT}</button>
        <!-- ELSE IF '${FRIENDSSTATUS}' == 'friend' -->
        <button class="btn btn-danger" id="remove_friend" onclick='remove_friend(${PROFILE_ID_USER})'>${BUTTON_TOP_REMOVE_FRIEND}</button>
        <!-- ELSE IF '${FRIENDSSTATUS}' == 'nofriend' -->
        <button class="btn btn-success" id="add_as_friend" onclick='add_as_friend(${PROFILE_ID_USER})'>${BUTTON_TOP_ADD_AS_FRIEND}</button>
        <!-- ELSE IF '${FRIENDSSTATUS}' == 'invated' -->
        <button class="btn btn-success" id="accept_friendship" onclick='accept_friendship(${PROFILE_ID_USER})'>${BUTTON_TOP_ACCEPT_FRIENDSHIP}</button>
        <div class="clearfix"></div>
        <!-- END IF -->
      </div>
      <!-- IF '${FRIENDSSTATUS}' == 'block' -->
      <div id="block_user_button">
        <button class="btn btn-danger" id="unblock_user" data-item="${PROFILE_ID_USER}">${BUTTON_TOP_UNBLOCK_USER}</button>
      </div>
      <!-- ELSE -->
      <div id="block_user_button">
        <button class="btn btn-danger" id="block_user" data-item="${PROFILE_ID_USER}">${BUTTON_TOP_BLOCK_USER}</button>
      </div>
      <!-- END IF -->
      <div  class="clearfix"></div>
    </div>
    <!-- END IF -->
  </div>
  <div class="clearfix"></div>
  
  
  <!-- IF '${PROFILE_AVATAR}' != '' -->
  
  <div id="top-top" class="account top_thumb_avatar">
    <!-- IF '${EVENT_NAME}' -->
    <h3 class='name event_name'>${EVENT_NAME}<br>
      <p class="citation">
      <!-- IF '${CITY}' != '' -->
      ${EVENT_ADDRESS} 
      <!-- END IF -->
      </p>
      <!-- IF '${ALLOW_EDIT}' == 'yes' -->
      <a class='button_edit_groups' href="./?task=events&event_id=${ID_EVENT}&q=edit">${TOP_BUTTON_EDIT}</a>
      <!-- END IF -->
    </h3>
    <!-- ELSE -->
    <img border="0" <!-- IF '${EDIT_AVATAR}' == 'yes' -->id="preview_ava" class="editable-avatar" title="Изменить фото"<!-- END IF --> src="${PROFILE_AVATAR}" alt="">
    <h3 class="name">
      <!-- IF '${ID_COMMUNITY}' != '' -->${PROFILE_COMMUNITY_NAME}<br>
      <!-- ELSE IF '${ID_PLAYGROUND}' != '' -->${PROFILE_PLAYGROUND_NAME}<br>
      <!-- ELSE -->${PROFILE_FIRSTNAME}
      <!-- IF '${PROFILE_ID_USER}' -->
      <span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online<!-- END IF -->' data-num='${PROFILE_ID_USER}'></span><br>
      <!-- ELSE -->
      <span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online<!-- END IF -->' data-num='${ID_USER}'></span><br>
      <!-- END IF -->
      ${PROFILE_LASTNAME}
      <!-- IF '${PROFILE_SECONDNAME}' != '' -->
      <br>
      (${PROFILE_SECONDNAME})
      <!-- END IF -->
      <!-- END IF -->
    </h3>
    <p class="citation">${PROFILE_ABOUT}
      <!-- IF '${PROFILE_COMMUNITY_PLACE}' != '' -->
      ${PROFILE_COMMUNITY_PLACE}<br>
      <!-- END IF -->
      <!-- IF '${PROFILE_COMMUNITY_SPORT}' != '' -->
      ${PROFILE_COMMUNITY_SPORT}
      <!-- END IF -->
    </p>
    <!-- IF '${ALLOW_EDIT}' == 'yes' -->
	<!-- IF '${COMMUNITY_TYPE}' == 'team' --><a class='button_edit_groups' href="./?task=teams&community_id=${ID_COMMUNITY}&q=edit">${TOP_BUTTON_EDIT}</a>
	<!-- ELSE IF '${COMMUNITY_TYPE}' == 'group' -->
	<a class='button_edit_groups' href="./?task=groups&community_id=${ID_COMMUNITY}&q=edit">${TOP_BUTTON_EDIT}</a>
	<!-- END IF -->
    <!-- END IF -->
    <!-- END IF -->
  </div>
  <!-- END IF -->
</div>
<div class="clearfix"></div>
<!-- IF '${PROFILELINKS}' == 'show' -->
<div id="information">
  <ul>
    <!-- IF '${USER_LAST_VISIT}' != '' -->
    <li><span>${STR_WAS_ONLINE}</span>
      <div>${USER_LAST_VISIT}</div>
    </li>
    <!-- END IF -->
    <!-- BEGIN row_top_profile_sport -->
	
	<!-- IF '${SPORT_TYPE}' != '' -->
    <li> <span>${STR_PROFILE_TOP_SPORT_TYPE}</span>
      <div>${SPORT_TYPE}</div>
    </li>
	<!-- END IF -->
	
	<!-- IF '${SPORT_LEVEL}' != '' -->
    <li> <span>${STR_PROFILE_TOP_SPORT_LEVEL}</span>
      <div>${SPORT_LEVEL}</div>
    </li>
	<!-- END IF -->
	
	
	<!-- IF '${SEARCH_TEAM}' != '' -->
    <li> <span>${STR_PROFILE_TOP_SEARCH_TEAM}</span>
      <div>${SEARCH_TEAM}</div>
    </li>
	<!-- END IF -->
	
    <!-- END row_top_profile_sport -->
	
	<!-- IF '${SPORTS_ACHIVMENTS}' != '' -->
    <li> <span>${STR_PROFILE_TOP_SPORTS_ACHIVMENTS}</span>
      <div class="achivment-list"> ${SPORTS_ACHIVMENTS} </div>
    </li>
	<!-- END IF -->
	
  </ul>
  <ul class="more-info">
    <li>
      <hr>
    </li>
    <!-- IF '${USER_BIRTHDAY}' != '' -->
    <li> <span>${STR_PROFILE_TOP_BIRTHDAY}</span>
      <div>${USER_BIRTHDAY}</div>
    </li>
    <!-- END IF -->
    <!-- IF '${USER_PLACE}' != '' -->
    <li> <span>${STR_PROFILE_TOP_CITY}</span>
      <div>${USER_PLACE}</div>
    </li>
	<!-- END IF -->
    <!-- IF '${USER_PHONE}' != '' -->
    <li> <span>${STR_PROFILE_TOP_PHONE}</span>
      <div>${USER_PHONE}</div>
    </li>
    <!-- END IF -->
	
	<!-- IF '${USER_CONTACT_EMAIL}' != '' -->
    <li> <span>${STR_PROFILE_TOP_EMAIL}</span>
      <div>${USER_CONTACT_EMAIL}</div>
    </li>
	<!-- END IF -->	 
	
	<!-- IF '${USER_SKYPE}' != '' -->
    <li> <span>Skype</span>
      <div>${USER_SKYPE}</div>
    </li>
	<!-- END IF -->	
	
	<!-- IF '${USER_WEBSITE}' != '' -->
    <li> <span>${STR_PERSONAL_WEBSITE}</span>
      <div>${USER_WEBSITE}</div>
    </li>
	<!-- END IF -->		
	 
	<!-- IF '${PROFILE_TOP_EDUCATION}' != '' --> 
    <li> <span>${STR_PROFILE_TOP_EDUCATION}</span>
      <!-- BEGIN row_top_profile_education -->	  
	  
	  <!-- IF '${NAME}' != '' -->
      <div>${NAME}<br>
        ${MONTH_START} ${YEAR_START} - ${MONTH_FINISH} ${YEAR_FINISH}
	  </div>
	  <!-- END IF -->
		
      <!-- END row_top_profile_education -->
    </li>
	<!-- END IF -->
	
	<!-- IF '${PROFILE_TOP_WORK_PLACE}' != '' --> 
    <li> <span>${STR_PROFILE_TOP_WORK_PLACE}</span>
      <!-- BEGIN row_top_profile_job -->
	  
	  <!-- IF '${NAME}' != '' -->
      <div>${NAME}<br>
        ${DESCRIPTION}
	  </div>
      <!-- END IF -->
	   
      <!-- END row_top_profile_job -->
    </li>
	<!-- END IF -->
	
	
  </ul>
  <!-- IF '${SHOW_ROLLOUT}' != '' --><hr><a class="minimax" onclick='return false'><i>${STR_ROLLOUT}</i><i>${STR_ROLLIN}</i></a><!-- END IF -->
  </div>
<div class="profilelink"> 
	<!-- IF '${PROFILE_PHOTO_PERMIT}' == 'display' --><a <!-- IF '${TASK}' == 'photoalbums' -->class="active-link" <!-- END IF --> href="./?task=photoalbums&user_id=${PROFILE_ID_USER}"><span>${STR_PROFILE_PHOTO_LINK}</span></a><!-- END IF --> 
	<!-- IF '${PROFILE_VIDEO_PERMIT}' == 'display' --><a <!-- IF '${TASK}' == 'videoalbums' -->class="active-link" <!-- END IF --> href="./?task=videoalbums&user_id=${PROFILE_ID_USER}"><span>${STR_PROFILE_VIDEO_LINK}</span></a><!-- END IF --> 
	<!-- IF '${PROFILE_FRIENDS_PERMIT}' == 'display' --><a <!-- IF '${TASK}' == 'friends' -->class="active-link" <!-- END IF --> href="./?task=friends&user_id=${PROFILE_ID_USER}"><span>${STR_PROFILE_FRIENDS_LINK}</span></a><!-- END IF --> 
	<!-- IF '${PROFILE_GROUPS_PERMIT}' == 'display' --><a <!-- IF '${TASK}' == 'groups' -->class="active-link" <!-- END IF --> href="./?task=groups&user_id=${PROFILE_ID_USER}"><span>${STR_PROFILE_GROUPS_LINK}</span></a><!-- END IF --> 
	<!-- IF '${PROFILE_TEAMS_PERMIT}' == 'display' --><a <!-- IF '${TASK}' == 'teams' -->class="active-link" <!-- END IF --> href="./?task=teams&user_id=${PROFILE_ID_USER}"><span>${STR_PROFILE_TEAMS_LINKS}</span></a><!-- END IF --> 
</div>
<!-- END IF -->
<script>

/*BLOCK USER*/
$(document).on( "click", "#block_user", function() {
	let IdUser = $(this).attr('data-item');

	$.ajax({
		url: "./?task=ajax_action&action=block_user&user_id=" + IdUser,
		cache: false,
		dataType: "json",
		success: function(data){
			if(data.result != ''){
				$('#friends_button').html('');
				$('#block_user_button').html('<button class="btn btn-danger" id="unblock_user" data-item="' + IdUser +'">${BUTTON_TOP_UNBLOCK_USER}</button>');
			}	
		}
	});
});

/*UNBLOCK USER*/
$(document).on( "click", "#unblock_user", function() {
	let IdUser = $(this).attr('data-item');

	$.ajax({
		url: "./?task=ajax_action&action=unblock_user&user_id=" + IdUser,
		cache: false,
		dataType: "json",
		success: function(data){
			if(data.result != ''){
				$('#friends_button').html('<button class="btn btn-success" id="add_as_friend" data-item="' + IdUser +'">${BUTTON_TOP_ADD_AS_FRIEND}</button>');
				$('#block_user_button').html('<button class="btn btn-danger" id="block_user" data-item="' + IdUser +'">${BUTTON_TOP_BLOCK_USER}</button>');
			}	
		}
	});
});

</script>
