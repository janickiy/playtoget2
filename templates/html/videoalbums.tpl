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
<script type="text/javascript" src="./templates/js/script_all.js"></script>
<script type="text/javascript" src="./templates/js/videoalbum.js"></script>
<link rel="stylesheet" href="./templates/css/emotions.css">
<link rel="stylesheet" href="./templates/css/jquery.emotions.fb.css">
<!-- IF '${ERROR_ALERT}' != '' -->
<div class='save_window_ok hiden'>${ERROR_ALERT}</div>
<!-- END IF -->
<!-- IF '${MSG_ALERT}' != '' -->
<div class='save_window_fail hiden'>${MSG_ALERT}</div>
<!-- END IF -->
<!-- INCLUDE block_videowindow.tpl -->
<section class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-xs-12  bg">
        <!-- IF '${PROFILE_VIDEO_PERMIT}' != 'hide' -->
        <!-- INCLUDE left_sitebar.tpl -->
        <!--Central content-->
        <div class="content">
          <!-- INCLUDE top_user_profile.tpl -->
          <!-- IF '${QUERY}' == 'create_videoalbum' -->
          <!-- INCLUDE block_videoalbum_form.tpl -->
          <!-- ELSE IF '${QUERY}' == 'edit_videoalbum' -->
          <!-- INCLUDE block_videoalbum_form.tpl -->
          <!-- ELSE IF '${QUERY}' == 'add_video' -->
          <!-- INCLUDE block_add_video.tpl -->
          <!-- ELSE -->
          <!-- IF '${ID_ALBUMS}' != '' -->
          <!-- INCLUDE block_videos.tpl -->
          <!-- ELSE -->
          <!-- INCLUDE block_videoalbum.tpl -->
          <!-- END IF -->
          <!-- END IF -->
          <!-- ELSE -->
          <h4 class='blocking'>${STR_USER_HAS_RESTRICTED_ACCESS_TO_THIS_SECTION}</h4>
          <!-- END IF -->
          <!--End content-->
        </div>
        <!-- INCLUDE right_sitebar.tpl -->
      </div>
    </div>
  </div>
</section>
<!--END CONTENT-->
<!-- INCLUDE footer.tpl -->
