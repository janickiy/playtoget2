<!-- IF '${OPEN_PAGE}' == '' -->
<!-- INCLUDE header.tpl -->
<!-- ELSE -->
<!-- INCLUDE unauthorizedheader.tpl -->
<!-- END IF -->
<!--START CONTENT-->
<script type="text/javascript" src="./templates/js/script_all.js"></script>
<!-- IF '${MSG_ERROR_ALERT}' != '' --><div class='save_window_ok hiden'>${MSG_ERROR_ALERT}</div><!-- END IF -->
<!-- IF '${MSG_SUCCESS}' != '' --><div class='save_window_fail hiden'>${MSG_SUCCESS}</div><!-- END IF -->
<section class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 bg">
        <!-- INCLUDE left_sitebar.tpl -->
        <div class="content">
          <!-- INCLUDE top_user_profile.tpl -->		  
		  <!-- IF '${PROFILE_PHOTO_PERMIT}' != 'hide' -->		  
          <!-- INCLUDE block_photowindow.tpl -->
          <!-- IF '${QUERY}' == 'create_photoalbum' -->
          <!-- INCLUDE block_photoalbum_form.tpl -->
          <!-- ELSE IF '${QUERY}' == 'edit_photoalbum' -->
          <!-- INCLUDE block_photoalbum_form.tpl -->
          <!-- ELSE IF '${QUERY}' == 'add_photo' -->
          <!-- INCLUDE block_add_photos.tpl -->
          <!-- ELSE -->
          <!-- IF '${ID_ALBUM}' != '' -->
          <!-- INCLUDE block_photos.tpl -->
          <!-- ELSE -->
          <!-- INCLUDE block_photoalbum.tpl -->
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