<!-- IF '${SHOW_ADD_VIDEO_MENU}' == 'show' -->
<div class="add-photos-album"> <span><i class="videoicon"></i><a href="${PATH_VIDEO}&q=add_video">${STR_ADD_NEW_VIDEO}</a></span> <span>${STR_OR}</span> <span><i></i> 
<a href="${PATH_VIDEO}&q=create_videoalbum">${STR_CREATE_NEW_ALBUM}</a></span> 
</div>
<!-- END IF -->

<!-- IF '${NO_POP_VIDEOS}' == '' -->
  
<div class="photo-caption">
  <h3>${STR_POPULAR_VIDEOS}</h3>

<a id="button-hid" class="button-hid show-pop-video-block">${STR_HIDE}</a>
</div>

<div id="popular-videos">
  <div class="photo-container video-container">
    <!-- BEGIN row_pop_videos_list -->
  
  <div class="hov"> <div class="video-box"><img src="${THUMB}" alt="" class="video_prev" data-num="${ID_VIDEO}"></div>
     <span class="video-capt"><i></i>${NUMBERVIEWS}</span>
  </div>
  <!-- END row_pop_videos_list -->
  </div>
</div>

<!-- END IF -->

<!-- IF '${NO_ALBUMS}' == '' -->

<div class="photo-caption">
  <h3>${STR_MY_ALBUMS}
    <!-- IF '${NUMBER_ALBUMS}' > '0' -->
    <sup> ${NUMBER_ALBUMS}</sup>
    <!-- END IF -->
  </h3>
</div>

<div class="my-albums">
  <!-- BEGIN row_my_videoalbum_list -->
  <div class="album"> <a href="${PATH_VIDEO}&id_album=${ID}<!-- IF '${PROFILE_USER_ID}' != '' -->&id_user=${PROFILE_USER_ID}<!-- END IF -->">
    <div class="img-container"><img src="${THUMB}" alt=""></div>
    </a>
    <p>${NAME}</p>
    <!-- IF '${SHOW_EDIT_LINKS}' == 'show' --><p><a href="${PATH_EDIT_VIDEO}&id_album=${ID}">${STR_EDIT}</a> <a href="${PATH_REMOVE_VIDEO}&id_album=${ID}" class='remove_album'>${STR_REMOVE}</a></p><!-- END IF -->
  </div>
  <!-- END row_my_videoalbum_list -->
</div>
<!-- END IF -->

<!-- IF '${NO_MY_VIDEOS}' == '' -->

<div class="photo-caption">
  <h3>${STR_MY_VIDEOS}
    <!-- IF '${NUMBER_MY_VIDEOS}' > '0' -->
    <sup> ${NUMBER_MY_VIDEOS}</sup>
    <!-- END IF -->
  </h3>
</div>

<div class="photo-container video-container vid-no-border">
  <!-- BEGIN row_my_videos_list -->
  <div id="video-block-${ID_VIDEO}" class="hov">
    <div class="video-box"><img src="${THUMB}" alt="" class='video_prev' data-num='${ID_VIDEO}'>
  </div>
  
  <!-- IF '${ALLOW_EDIT}' != '' --><span class="icons-hid"><i id="my-video-${ID_VIDEO}" class="remove_video" data-item="${ID_VIDEO}" data-tooltip="${STR_REMOVE_VIDEO}"> <img  src="./templates/images/icon-krest.png" alt=""></i></span><!-- END IF --> 
  
  <span class="video-capt"><i></i>${NUMBERVIEWS}</span> 
  </div>
  <!-- END row_my_videos_list -->

  <!-- IF '${NUMBER_MY_VIDEOS}' > '6' -->
  <a class="show-more" id='my-event' onclick="showMoreVideos('${ID_OWNER}','${VIDEOALBUMABLE_TYPE}')"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a>
  <!-- END IF -->
</div>
<!-- END IF -->