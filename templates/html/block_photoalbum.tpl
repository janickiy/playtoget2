<!-- IF '${SHOW_ADD_PHOTOS_MENU}' == 'show' -->
<div class="add-photos-album"> 
    <span><i></i><a href="${PHOTOALBUM_PATH}&q=add_photo">${STR_ADD_PHOTOS}</a> </span> 
    <span>${STR_OR}</span> 
    <span><i></i><a href="${PHOTOALBUM_PATH}&q=create_photoalbum">${STR_CREATE_PHOTOALBUM}</a></span> 
</div>
<!-- END IF -->

<!-- IF '${NO_POP_PHOTOS}' == '' -->
<div class="photo-caption">
  <h3>${STR_POPULAR_PHOTOS}</h3>
  <a id="button-hid" class="button-hid show-pop-photo-block">Скрыть</a>
</div>

<div id="popular-photos">
  <div class="photo-container pop-photos">
    <!-- BEGIN row_pop_photos_list -->
    <div class="hov"> 
        <a class='photo_big' title="${DESCRIPTION}" href="${BIG_IMAGE}" data-lightbox="roadtrip" data-num='${ID_PHOTO}'> <img src="${SMALL_IMAGE}" alt=""> </a> 
    </div>
    <!-- END row_pop_photos_list -->
  </div>
</div>
<!-- END IF -->

<!-- IF '${NO_ALBUMS}' == '' -->

<div class="photo-caption">
  <h3>${STR_MY_ALBUMS}
    <!-- IF '${NUMBER_MY_ALBUMS}' != '0' -->
    <sup> ${NUMBER_MY_ALBUMS}
    <!-- END IF -->
    </sup>
  </h3>
</div>

<div class="my-albums">
  <!-- BEGIN row_my_album_list -->
  <div class="album"> <a href="${PHOTOALBUM_PATH}&id_album=${ID}<!-- IF '${PROFILE_USER_ID}' != '' -->&id_user=${PROFILE_USER_ID}<!-- END IF -->">
    <div class="img-container"><img border="0" src="${IMAGE}" alt=""></div>
    <p>${NAME}</p>
    </a>
    <!-- IF '${SHOW_EDIT_LINKS}' == 'show' --><p><a href="${PHOTOALBUM_EDIT_PATH}&id_album=${ID}">${STR_EDIT}</a> <a href="${PHOTOALBUM_REMOVE_PATH}&id_album=${ID}" class='remove_album'>${STR_REMOVE}</a></p><!-- END IF -->
  </div>
  <!-- END row_my_album_list -->
</div>

<!-- END IF -->

<!-- IF '${NO_PHOTOS}' == '' -->

<div class="photo-caption">
  <h3>${STR_MY_PHOTOS}
    <!-- IF '${NUMBER_MY_PHOTOS}' > '0' -->
    <sup> ${NUMBER_MY_PHOTOS}</sup>
    <!-- END IF -->
  </h3>
</div>

<div class="photo-container my-photos">
  <!-- BEGIN row_my_photos_list -->
  <!-- IF '${SMALL_IMAGE}' != '' -->
  <div class="hov" id="photo-block-${ID_PHOTO}"> 
  	<a class='photo_big' title="${DESCRIPTION}" href="${BIG_IMAGE}" data-lightbox="roadtrip" data-num='${ID_PHOTO}'> <img src="${SMALL_IMAGE}" alt="">
  		<div class="transparent"></div>
    </a> 
  	<!-- IF '${ALLOW_EDIT}' == 'show' --><span class="icons-hid"><i id="my-video-${ID_PHOTO}" class="remove_pic" id="${ID_PHOTO}" data-item="${ID_PHOTO}" data-tooltip="${STR_REMOVE_PHOTO}"><img src="templates/images/icon-krest.png" alt=""></i></span><!-- END IF --> 
  </div>
  <!-- END IF -->
  <!-- END row_my_photos_list -->
  <!-- IF '${SHOW_MORE}=="show"' -->
  <!-- IF '${NUMBER_MY_PHOTOS}' > '9' --><a class="show-more" id='my-event' onclick="showMorePhotos('${ID_OWNER}','${PHOTOALBUMABLE_TYPE}')"><i></i><span id="show-more">${STR_SHOW_MORE}</span></a> <!-- END IF -->
  <!-- END IF -->
</div>

<!-- END IF -->