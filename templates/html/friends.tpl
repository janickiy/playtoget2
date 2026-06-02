<!-- IF '${OPEN_PAGE}' == '' -->
<!-- INCLUDE header.tpl -->
<!-- ELSE -->
<!-- INCLUDE unauthorizedheader.tpl -->
<!-- END IF -->
<!--START CONTENT-->
<script type="text/javascript" src="./templates/js/script_all.js"></script>
<section class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-xs-12  bg">
        <!-- INCLUDE left_sitebar.tpl -->
        <div class="content friends content-groups">
          <!-- INCLUDE top_user_profile.tpl -->
		  <!-- IF '${PROFILE_FRIENDS_PERMIT}' != 'hide' -->
          <!-- IF '${NO_POSSIBLE_FRIENDS}' == '' -->
          <!-- INCLUDE friends_search_form.tpl -->
          <div class="photo-caption">
            <h3>${STR_POSSIBLE_FRIEND}
            </h3>
          </div>
          <div id="possible-friend" class="possible-friend">
            <!-- BEGIN row_possible_friends -->
            <div class="col-xs-6 possible-friend-cart" data-num='${ID_FRIEND}'> <a class="possible-avatar" href="./?task=profile&id_user=${ID_FRIEND}" > <img src="${AVATAR}" alt="" > </a> <a href="./?task=profile&id_user=${ID_FRIEND}">
              <h5><strong>${FIRSTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online<!-- END IF -->' data-num='${ID_FRIEND}'></span> <br />
                ${LASTNAME}</strong></h5>
              </a>
              <p>${CITY}</p>
              <div class='control'> <span> <a onclick='add_as_friend(${ID_FRIEND});' data-tooltip="Добавить в друзья"><img src='./templates/images/icon-ok.png' alt=""/></a> </span> <span> <img src='./templates/images/icon-krest.png' alt="" id='remove_this' data-num='${ID_FRIEND}' data-tooltip="Больше не показывать"/> </span> </div>
            </div>
            <!-- END row_possible_friends -->
          </div>
          <a id="show-possible_friends" class="show-more"><i></i>${STR_SHOW_MORE}</a>
          <!-- END IF --> 
		  
          <!-- IF '${NO_FRIENDS}' == '' -->
		  
          <div class="photo-caption">
            <h3>${STR_MY_FRIENDS}
              <!-- IF '${NUMBER_FRIENDS}' > '0' -->
              <sup>${NUMBER_FRIENDS}</sup>
              <!-- END IF -->
            </h3>
          </div>
		  
          <div id='friends' class="possible-friend my-friend">
            <!-- BEGIN row_my_friends -->
            <div class="col-xs-6 possible-friend-cart"> 
              <a class="possible-avatar" href="./?task=profile&id_user=${ID_FRIEND}"> 
                <img src="${AVATAR}" alt=""> </a> <a href="./?task=profile&id_user=${ID_FRIEND}">
              <h5><strong>${FIRSTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online<!-- END IF -->' data-num='${ID_FRIEND}'></span><br />
                ${LASTNAME}</strong></h5>
              </a>
              <p>${CITY}</p>
              <a href="./?task=profile&id_user=${SEL}&q=messages&sel=${ID_FRIEND}" data-tooltip='Написать сообщение'><b></b></a>
			 <!-- IF '${REMOVE_FRIEND}' == 'show' --><div class='control'> <span> </span> <span> <a onclick='remove_friend(${ID_FRIEND});' data-tooltip="Удалить из друзей"><img src='./templates/images/icon-krest.png' alt=""/></a> </span> </div> <!-- END IF -->
                </div>
            <!-- END row_my_friends -->
          </div>
            <!-- IF '${SHOW_MORE_MY_FRIENDS}'!='' -->
              <!-- IF '${PROFILE_ID_USER}'!='' -->
                <a id='show_more_friends' class="show-more" onclick="showMoreFriend(${PROFILE_ID_USER})"><i></i>${STR_SHOW_MORE}</a>
              <!-- ELSE -->
                <a id='show_more_friends' class="show-more" onclick="showMoreFriend(${ID_USER})"><i></i>${STR_SHOW_MORE}</a>
              <!-- END IF -->

            <!-- END IF -->

          <!-- END IF -->
          <!-- IF '${NO_FRIENDS_REQUEST}' == '' -->
          <div class="photo-caption">
            <h3>${STR_FRIENDS_REQUEST}
              <!-- IF '${NUMBER_FRIENDS_REQUEST}' > '0' -->
              <sup>${NUMBER_FRIENDS_REQUEST}</sup>
              <!-- END IF -->
            </h3>
          </div>
          <div class="possible-friend my-friend">
            <!-- BEGIN row_request_friends -->
            <div class="col-xs-6 possible-friend-cart"> <a class="possible-avatar" href="./?task=profile&id_user=${ID_FRIEND}"> <img src="${AVATAR}" alt=""> </a> <a href="./?task=profile&id_user=${ID_FRIEND}">
              <h5><strong>${FIRSTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online<!-- END IF -->' data-num='${ID_FRIEND}'></span><br />
                ${LASTNAME}</strong></h5>
              </a>
              <p>${CITY}</p>
			  <a href="./?task=profile&id_user=${SEL}&q=messages&sel=${ID_FRIEND}" data-tooltip='Написать сообщение'><b></b></a>
              <div class='control'> <span> <a onclick='accept_friendship(${ID_FRIEND});' data-tooltip="Принять заявку"><img src='./templates/images/icon-ok.png' alt=""/></a> </span> <span> </span> </div>
            </div>
            <!-- END row_request_friends -->
          </div>
          <!-- END IF -->
		   <!-- IF '${NO_OUTGOING_REQUEST}' == '' -->
          <div class="photo-caption">
            <h3>${STR_OUTGOING_REQUEST}
              <!-- IF '${OUTGOING_REQUEST}' > '0' -->
              <sup>${OUTGOING_REQUEST}</sup>
              <!-- END IF -->
            </h3>
          </div>
          <div class="possible-friend my-friend">
            <!-- BEGIN row_outgoing_request -->
            <div class="col-xs-6 possible-friend-cart"> <a class="possible-avatar" href="./?task=profile&id_user=${ID_FRIEND}"> <img src="${AVATAR}" alt=""> </a> <a href="./?task=profile&id_user=${ID_FRIEND}">
              <h5><strong>${FIRSTNAME}<span class='status_user<!-- IF '${STATUS_USER}' == 'online' --> online<!-- END IF -->' data-num='${ID_FRIEND}'></span><br />
                ${LASTNAME}</strong></h5>
              </a>
              <p>${CITY}</p>
			  <!-- IF '${SEL}' != '' --><a href="./?task=profile&id_user=${SEL}&q=messages&sel=${ID_FRIEND}" data-tooltip='Написать сообщение'><b></b></a><!-- END IF -->
            </div>
            <!-- END row_outgoing_request -->
          </div>
          <!-- END IF -->
		  
<script type="text/javascript" src='./templates/js/friends.js'></script>

		 <!-- ELSE -->
         <h4 class='blocking'>${STR_USER_HAS_RESTRICTED_ACCESS_TO_THIS_SECTION}</h4>
		  <!-- END IF --> 

        </div>
        <!--End content-->
        <!-- INCLUDE right_sitebar.tpl -->
      </div>
    </div>
  </div>
</section>
<!--END CONTENT-->
<!-- INCLUDE footer.tpl -->