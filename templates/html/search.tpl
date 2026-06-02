<!-- INCLUDE header.tpl -->
<!--START CONTENT-->
<script type="text/javascript" src="./templates/js/script_all.js"></script>

<section class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-xs-12  bg">
        <!-- INCLUDE left_sitebar.tpl -->
        <div class="content friends">
          <!-- INCLUDE top_user_profile.tpl -->

          <!-- IF '${QUERY}' == 'all_search' -->
          <div class="photo-caption">
            <h3>${TITLE_PAGE}</h3>
          </div>

            <h4 class='center_text'>${SEARCH_NOTHING_RESULT}</h4>

          <!-- IF '${SHOW_USER_SEARCH_RESULT}' != '' -->
          <h4>${STR_USERS}</h4>
          <div id="possible-friend" class="possible-friend">
            <!-- BEGIN users_search_result_row -->
            <div class="col-xs-6 possible-friend-cart"> <a class="possible-avatar" href="./?task=profile&id_user=${ID}"> <img src="${AVATAR}" alt=""> </a> <a href="./?task=profile&id_user=${ID}">
              <h5><strong>${FIRSTNAME} <br />
                ${LASTNAME}</strong></h5>
              </a>
              <!-- IF '${CITY}' != '' -->
              <p>${CITY}</p>
              <!-- END IF -->
              <br>
              <a href="./?task=profile&id_user=${ID_USER}&q=messages&sel=${SEL}"><b></b></a>
            </div>
            <!-- END users_search_result_row -->
          </div>
          <a class="show-more" href="./?task=search&q=user&search=${SEARCH}"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
        
          <!-- END IF -->
          <!-- IF '${SHOW_GROUP_SEARCH_RESULT}' != '' -->
          <h4>${STR_GROUPS}</h4>
          <div class="event-container">
            <!-- BEGIN group_search_result_row -->
            <div class="event-item" id="community_${ID}"> <a href="./?task=groups&id_community=${ID}" class="img"><img border="0" src="${AVATAR}" alt=""></a>
              <div class="teg">
                <p><a href="./?task=groups&id_community=${ID}">${NAME}</a></p>
                <p>${SPORT_TYPE}<br>
                  ${CITY}</p>
                <p>${ABOUT}</p>
                <p><i></i>${STR_MEMBER}</p>
                <!-- IF '${ALLOW_EDIT}' == 'yes' -->
                <a href="./?task=groups&id_community=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                <div class="transparent"> </div>
              </div>
            </div>
            <!-- END group_search_result_row -->
          </div>
          <a class="show-more" href="./?task=search&q=group&search=${SEARCH}"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
          <!-- END IF -->
          <!-- IF '${SHOW_TEAM_SEARCH_RESULT}' != '' -->
          <h4>${STR_TEAMS}</h4>
          <div class="event-container">
            <!-- BEGIN team_search_result_row -->
            <div class="event-item" id="community_${ID}"> <a href="./?task=teams&id_community=${ID}" class="img"><img border="0" src="${AVATAR}" alt=""></a>
              <div class="teg">
                <p><a href="./?task=teams&id_community=${ID}">${NAME}</a></p>
                <p>${SPORT_TYPE}<br>
                  ${CITY}</p>
                <p>${ABOUT}</p>
                <p><i></i>${STR_MEMBER}</p>
                <!-- IF '${ALLOW_EDIT}' == 'yes' -->
                <a href="./?task=teams&id_community=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                <div class="transparent"> </div>
              </div>
            </div>
            <!-- END team_search_result_row -->
          </div>
          <a class="show-more" href="./?task=search&q=teams&search=${SEARCH}"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
          <!-- END IF -->
          <!-- IF '${SHOW_PLAYGROUND_SEARCH_RESULT}' != '' -->
          <h4>${STR_PLAYGROUNDS}</h4>
          <div class="event-container">
          <!-- BEGIN playground_search_result_row -->
            <div class="event-item"> 
            <a href="./?task=playgrounds&id_sport_block=${ID}" class="img">
        <!-- IF '${AVATAR}' !='' --><img src="${AVATAR}"></a><!-- END IF -->
              <div class="teg">
                <p><a href="./?task=playgrounds&id_sport_block=${ID}">${NAME}</a></p>
                <p>${PLACE}</p>
                <p>${ABOUT}</p>
        
                <!-- IF '${SHOW_EDIT_FORM}' == 'show' --> 
                <a href="./?task=playgrounds&id_sport_block=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                </div>
            </div>
		        
          <!-- END playground_search_result_row -->
          </div>
          <a class="show-more" href="./?task=search&q=playground&search=${SEARCH}"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
          <!-- END IF -->
          <!-- IF '${SHOW_SHOPS_SEARCH_RESULT}' != '' -->
          <h4>${STR_SHOPS}</h4>
          <div class="event-container">
          <!-- BEGIN shop_search_result_row -->
          <div class="event-item"> 
            <a href="./?task=shops&id_sport_block=${ID}" class="img">
        <!-- IF '${AVATAR}' !='' --><img src="${AVATAR}"></a><!-- END IF -->
              <div class="teg">
                <p><a href="./?task=shops&id_sport_block=${ID}">${NAME}</a></p>
                <p>${PLACE}</p>
                <p>${ABOUT}</p>
        
                <!-- IF '${SHOW_EDIT_FORM}' == 'show' --> 
                <a href="./?task=shops&id_sport_block=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                </div>
            </div>
          <!-- END shop_search_result_row -->
          </div>
          <a class="show-more" href="./?task=search&q=shop&search=${SEARCH}"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
          <!-- END IF -->
          <!-- IF '${SHOW_FITNES_SEARCH_RESULT}' != '' -->
          <h4>${STR_FITNESS}</h4>
          <div class="event-container">
          <!-- BEGIN fitnes_search_result_row -->
          <div class="event-item"> 
            <a href="./?task=fitness&id_sport_block=${ID}" class="img">
        <!-- IF '${AVATAR}' !='' --><img src="${AVATAR}"></a><!-- END IF -->
              <div class="teg">
                <p><a href="./?task=fitness&id_sport_block=${ID}">${NAME}</a></p>
                <p>${PLACE}</p>
                <p>${ABOUT}</p>
        
                <!-- IF '${SHOW_EDIT_FORM}' == 'show' --> 
                <a href="./?task=fitness&id_sport_block=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                </div>
            </div>
          <!-- END fitnes_search_result_row -->
          </div>
          <a class="show-more" href="./?task=search&q=fitnes&search=${SEARCH}"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
        <!-- END IF -->
		
		<!-- IF '${SHOW_EVENT_SEARCH_RESULT}' != '' -->
          <h4>${STR_EVENTS}</h4>
          <div class="event-container">
          <!-- BEGIN events_search_result_row -->
          <div class="event-item"> <a href="./?task=events&id_event=${ID}" class="img"><img src="${AVATAR}" alt="" class='marginLeft-100'></a>
              <div class="teg">
                <p><a href="./?task=events&id_event=${ID}">${NAME}</a></p>
                <p>
                  <!-- IF '${SPORT_TYPE}' != '' -->
                  ${SPORT_TYPE}<br>
                  <!-- END IF -->
                  <!-- IF '${CITY}' != '' -->
                  ${CITY}<br>
                  <!-- END IF -->
                  ${DATE}</p>
                <p>${DESCRIPTION}</p>
                <p><i></i>${PARTICIPANTS_FRIENDS}</p>

                <!-- IF '${ALLOW_EDIT}' == 'yes' -->
                <a href="./?task=events&id_event=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                <span>${STATUS}</span> </div>
            </div>
          <!-- END events_search_result_row -->
          </div>
          <a class="show-more" href="./?task=search&q=event&search=${SEARCH}"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
           <!-- END IF -->
        
          <!-- ELSE IF '${QUERY}' == 'event' -->
          <!-- INCLUDE events_search_form.tpl -->		  
		  
		  <!-- IF '${NOTHING_FOUND}' == '' -->
		  
          <div class="photo-caption">
            <h3>${STR_SEARCHING_RESULTS}</h3>
          </div>		  
		  
          <div class="event-container">
            <!-- BEGIN row_events_list -->
            <div class="event-item"> <a href="./?task=events&id_event=${ID}" class="img"><img src="${AVATAR}" alt="" class='marginLeft-100'></a>
              <div class="teg">
                <p><a href="./?task=events&id_event=${ID}">${NAME}</a></p>
                <p>
                  <!-- IF '${SPORT_TYPE}' != '' -->
                  ${SPORT_TYPE}<br>
                  <!-- END IF -->
                  <!-- IF '${CITY}' != '' -->
                  ${CITY}<br>
                  <!-- END IF -->
                  ${DATE}</p>
                <p>${DESCRIPTION}</p>
                <p><i></i>${PARTICIPANTS_FRIENDS}</p> 

                <!-- IF '${ALLOW_EDIT}' == 'yes' -->
                <a href="./?task=events&id_event=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                <span>${STATUS}</span> </div>
            </div>
            <!-- END row_events_list -->
          </div>
		  <!-- ELSE -->
        <h4 class='center_text'>${NOTHING_FOUND}</h4>
		  <!-- END IF -->
		  
          <!-- ELSE IF '${QUERY}' == 'group' -->
          <!-- INCLUDE communities_search_form.tpl -->
		  
			<!-- IF '${NOTHING_FOUND}' == '' -->

          <div class="photo-caption">
            <h3>${STR_SEARCHING_RESULTS}</h3>
          </div>  		  
		  
          <div class="event-container">
            <!-- BEGIN row_group_list -->
            <div class="event-item" id="community_${ID}"> <a href="./?task=groups&id_community=${ID}" class="img"><img border="0" src="${AVATAR}" alt=""></a>
              <div class="teg">
                <p><a href="./?task=groups&id_community=${ID}">${NAME}</a></p>
                <p>${SPORT_TYPE}<br>
                  ${CITY}</p>
                <p>${ABOUT}</p>
                <p><i></i>${STR_MEMBER}</p>
                <!-- IF '${ALLOW_EDIT}' == 'yes' -->
                <a href="./?task=groups&id_community=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                <div class="transparent"> </div>
              </div>
            </div>
            <!-- END row_group_list -->
          </div>
      <!-- ELSE -->
        <h4 class='center_text'>${NOTHING_FOUND}</h4>
		  
		  
		   <!-- END IF -->
          <!-- ELSE IF '${QUERY}' == 'team' -->
          <!-- INCLUDE communities_search_form.tpl -->		  
		  
		  <!-- IF '${NOTHING_FOUND}' == '' -->
		  
          <div class="photo-caption">
            <h3>${STR_SEARCHING_RESULTS}</h3>
          </div>	  
		  
		  
          <div class="event-container">
            <!-- BEGIN row_team_list -->
            <div class="event-item" id="community_${ID}"> <a href="./?task=teams&id_community=${ID}" class="img"><img border="0" src="${AVATAR}" alt=""></a>
              <div class="teg">
                <p><a href="./?task=teams&id_community=${ID}">${NAME}</a></p>
                <p>${SPORT_TYPE}<br>
                  ${CITY}</p>
                <p>${ABOUT}</p>
                <p><i></i>${STR_MEMBER}</p>
                <!-- IF '${ALLOW_EDIT}' == 'yes' -->
                <a href="./?task=teams&id_community=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                <div class="transparent"></div>
              </div>
            </div>
            <!-- END row_team_list -->
          </div>	
      <!-- ELSE -->
        <h4 class='center_text'>${NOTHING_FOUND}</h4>

		  <!-- END IF -->
		  
          <!-- ELSE IF '${QUERY}' == 'shop' -->

		  <!-- INCLUDE sport_blocks_search_form.tpl -->
		  
		  <!-- IF '${NOTHING_FOUND}' == '' -->
          <div class="photo-caption">
            <h3>${STR_SEARCHING_RESULTS}</h3>
          </div>
		  
          <div class="event-container">
          <!-- BEGIN row_shop_block -->
          <div class="event-item"> 
            
        <!-- IF '${AVATAR}' !='' --><a href="./?task=shops&id_sport_block=${ID}" class="img"><img src="${AVATAR}"></a><!-- END IF -->
              <div class="teg">
                <p><a href="./?task=shops&id_sport_block=${ID}">${NAME}</a></p>
                <p>${PLACE}</p>
                <p>${ABOUT}</p>
        
                <!-- IF '${SHOW_EDIT_FORM}' == 'show' --> 
                <a href="./?task=shops&id_sport_block=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                </div>
            </div>
          <!-- END row_shop_block -->
          </div>

      <!-- ELSE -->
        <h4 class='center_text'>${NOTHING_FOUND}</h4>

      <!-- END IF -->

          <!-- ELSE IF '${QUERY}' == 'playground' -->
		  
          <!-- INCLUDE sport_blocks_search_form.tpl -->
		   
		   
		   
		    <!-- IF '${NOTHING_FOUND}' == '' -->
		   
		    <div class="photo-caption">
            <h3>${STR_SEARCHING_RESULTS}</h3>
          </div>	
          
          <div class="event-container">
          <!-- BEGIN row_playground_block -->
         <div class="event-item"> 
            
        <!-- IF '${AVATAR}' !='' --><a href="./?task=playgrounds&id_sport_block=${ID}" class="img"><img src="${AVATAR}"></a><!-- END IF -->
              <div class="teg">
                <p><a href="./?task=playgrounds&id_sport_block=${ID}">${NAME}</a></p>
                <p>${PLACE}</p>
                <p>${ABOUT}</p>
        
                <!-- IF '${SHOW_EDIT_FORM}' == 'show' --> 
                <a href="./?task=playgrounds&id_sport_block=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                </div>
            </div>
          <!-- END row_playground_block -->
          </div>

        <!-- ELSE -->
        <h4 class='center_text'>${NOTHING_FOUND}</h4>

      <!-- END IF -->

          <!-- ELSE IF '${QUERY}' == 'fitness' -->
          <!-- INCLUDE sport_blocks_search_form.tpl -->

        <!-- IF '${NOTHING_FOUND}' == '' -->
		  <div class="photo-caption">
            <h3>${STR_SEARCHING_RESULTS}</h3>
          </div>
		  
		  
          <div class="event-container">
          <!-- BEGIN row_fitnes_block -->
          <div class="event-item"> 
            
        <!-- IF '${AVATAR}' !='' --><a href="./?task=fitness&id_sport_block=${ID}" class="img"><img src="${AVATAR}"></a><!-- END IF -->
              <div class="teg">
                <p><a href="./?task=fitness&id_sport_block=${ID}">${NAME}</a></p>
                <p>${PLACE}</p>
                <p>${ABOUT}</p>
        
                <!-- IF '${SHOW_EDIT_FORM}' == 'show' --> 
                <a href="./?task=fitness&id_sport_block=${ID}&q=edit">${STR_EDIT}</a>
                <!-- END IF -->
                </div>
            </div>
          <!-- END row_fitnes_block -->
          </div>


        <!-- ELSE -->
        <h4 class='center_text'>${NOTHING_FOUND}</h4>

      <!-- END IF -->

          <!-- ELSE IF '${QUERY}' == 'user' -->
<!-- INCLUDE friends_search_form.tpl -->
        <!-- IF '${NOTHING_FOUND}' == '' -->
          <div class="photo-caption">
            <h3>${STR_SEARCHING_RESULTS}</h3>
          </div>
          <div id="possible-friend" class="possible-friend">
            <!-- BEGIN row_users -->
            <div class="col-xs-6 possible-friend-cart"> <a class="possible-avatar" href="./?task=profile&id_user=${ID}"> <img src="${AVATAR}" alt=""> </a> <a href="./?task=profile&id_user=${ID}">
              <h5><strong>${FIRSTNAME} <br />
                ${LASTNAME}</strong></h5>
              </a>
              <!-- IF '${CITY}' != '' -->
              <p>${CITY}</p>
              <!-- END IF -->
              <br>
              <a href="./?task=profile&id_user=${ID_USER}&q=messages&sel=${SEL}"><b></b></a><br>
            </div>
            <!-- END row_users -->
          </div>
           <!-- ELSE -->
        <h4 class='center_text'>${NOTHING_FOUND}</h4>

      <!-- END IF -->
          <!-- END IF -->
        </div>
        <!--End content-->
        <!-- INCLUDE right_sitebar.tpl -->
      </div>
    </div>
  </div>
</section>
<script src="./templates/js/search.js"></script>
<!--END CONTENT-->
<!-- INCLUDE footer.tpl -->