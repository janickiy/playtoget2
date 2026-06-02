

<h2>${PHOTOALBUM_NAME}  ${WERR2}</h2>
<p><a href="${PHOTOALBUM_PATH}<!-- IF '${PROFILE_USER_ID}' != '' -->&user_id=${PROFILE_USER_ID}<!-- END IF -->">${STR_ALL_PHOTOS}</a></p>
<!-- IF '${NO_IMAGES}' == '' -->
<div  class="photo-container pop-photos">
  <!-- BEGIN row_photos_list -->
  <!-- IF '${SMALL_IMAGE}' != '' -->
  <div class="hov" id="photo-block-${ID_PHOTO}"> <a class='photo_big' title="${DESCRIPTION}" href="${BIG_IMAGE}" data-lightbox="roadtrip" data-num='${ID_PHOTO}'> <img src="${SMALL_IMAGE}" alt="">
    <div class="transparent"></div>
    </a> 
	<span class="icons-hid">
	<!-- IF '${ALLOW_EDIT}' != '' --><span class="icons-hid"><i id="my-video-${ID_PHOTO}" class="remove_pic" id="${ID_PHOTO}" data-item="${ID_PHOTO}" data-tooltip="${STR_REMOVE_PHOTO}"><img src="./templates/images/icon-krest.png" alt=""></i></span><!-- END IF --> 
	</span> 
	</div>
  <!-- END IF -->
  <!-- END row_photos_list -->
</div>
<!-- END IF -->

<script>

let evJob = true;
let settPhotos = { 
	number  : 9,
	offset  : 9,
}
$(document).scroll(function() {
  
	if($(window).scrollTop()+$(window).height()>=$(document).height()){
		if (evJob) {
			evJob = false;
			$('.photo-container').append('<div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>')
			$.ajax({
				type:'POST',
				url:'/?task=ajax_action&action=get_album_photos',
				data:{
					number:settPhotos.number,
					offset:settPhotos.offset,
					id_album:'${ID_ALBUM}',
				},
				success:function(data){
					$('.photo-container').find('.loading-bar').remove();
					$('.photo-container').append(data.html);
					settPhotos.offset+=settPhotos.number;
					evJob = true;
					$('.photo_big').each(function(){
						let id = $(this).attr('data-num');
						if (parseInt($.inArray(id, mass_photo)) == -1)
						{
							mass_photo.push(id);
							k++;
						}
					});
				}
			})
		}
	}
});

</script>
