<h2>${TITLE_PAGE}</h2>
<p><a href="${PATH_VIDEO}<!-- IF '${PROFILE_USER_ID}' != '' -->&user_id=${PROFILE_USER_ID}<!-- END IF -->">${STR_ALL_VIDEOS}</a></p>
<div class="photo-container video-container vid-no-border">
  <!-- BEGIN row_videos_list -->
  <div id="video-block-${ID}" class="hov"><div class="video-box"><img src="${THUMB}" alt="" class="video_prev" data-num="${ID}"></div>
  
  <!-- IF '${ALLOW_EDIT}' != '' --><span class="icons-hid"><i id="my-video-${ID}" class="remove_video" data-item="${ID}" data-tooltip="${STR_REMOVE_VIDEO}"><img src="./templates/images/icon-krest.png" alt=""></i></span><!-- END IF --> 
  
  <span class="video-capt"><i></i>${NUMBERVIEWS}</span> 
  </div>
  <!-- END row_videos_list -->
</div>

<script>

let evJob = true;
let settvideos = { 
	number : 6,
	offset : 6,
}

$(document).scroll(function() {
	if($(window).scrollTop()+$(window).height()>=$(document).height()){
		if (evJob){
			evJob = false;
			$('.photo-container').append('<div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>')
			$.ajax({
				type:'POST',
				url:'/?task=ajax_action&action=get_album_videos',
				data:{
					number:settvideos.number,
					offset:settvideos.offset,
					id_album:'${ID_ALBUM}',
				},
				success:function(data){
					$('.photo-container').find('.loading-bar').remove();
					$('.photo-container').append(data.html);
					settvideos.offset+=settvideos.number;
					evJob = true;
				}
			})
		}
	}
});

</script>
