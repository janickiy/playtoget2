
  <script>
  window.avatar = '${TOP_AVATAR}';
  window.user_id = '${ID_USER}';
  window.placeholder = '${STR_YOUR_COMMENT}';
  window.error = '${STR_THERE_ARE_NO_MORE_ENTRIES}';
  window.init = '${STR_CLICK}';
</script>
<script src="./templates/js/photoalbum.js"></script>
<div class='overlay' id='photo_big'>
  <div class='overlay-back back_one'>Закрыть</div>
  <div class='prev'>Предыдущая</div>
  <div class='photo_big_wrap'>
    <input type='hidden' id='owner_id'/>
	    <img src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==' class='photo_wrap next'/>
    <div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>
    <div class="text">
      <div class="message">
        <div class='text-block' id='name_foto'><a href="./?task=profile&user_id=${ID_USER}">${FIRSTNAME} ${LASTNAME}</a></div>
        <div class='text-block' id='date_foto'><span class="data">25.01.2016</span></div>
        <!-- IF '${OPEN_PAGE}' == '' -->
        <div class='text-block foto_like'> <a class="tell"  data-type="photo">0</a> <a class="liked"  data-type="photo">0</a> </div>
        <!-- END IF -->
        <p class='info_photo'></p>
      </div>
    </div>
    <div class='text' id='mainComments'>
      <div id='addCommentContainers' data-type='photo'></div>
    </div>
    <div class="text">
      <form autocomplete="off" id="addCommentForm" class="form-horizontal" method="POST" action="">
        <input type='hidden' name="commentable_type" value="photo"/>
        <input type='hidden' name="content_id" id='content_id' value="${ID_PHOTO}"/>
        <input type='hidden' name="user_id" value="${ID_USER}"/>
        <input type='hidden' name="parent_id" value="0"/>
        <div class="form-group">
        <!-- IF '${OPEN_PAGE}' == '' -->
          <label class="col-lg-3 control-label" for="comment">Оставить комментарий</label>
          <div class="col-lg-7">
            <input type="file"  class="file_name" name="file_name[]" data-num="0" multiple/>
            <textarea class="form-control form-dark" id="comment" rows="4" name="comment" data-num="0"></textarea>
          </div>
          <div class="smile-files"> 
              <a id="smilesBtn" class="smile smilesBtn" data-num="0"><img src="./templates/images/smile.png" alt=""></a> 
              <a href="#" class="files" data-num="0" data-tooltip="Прикрепить изображение"><img src="./templates/images/files.png" alt=""></a>
              <div class="smilesChoose" data-num="0"></div> 
          </div>
          <div class='files_block' data-num="0"></div>
        <!-- ELSE -->
          <label class="col-lg-12 control-label center_text margin0Auto marginTop20" for="comment">Чтобы оставить комментарий авторизуйтесь</label>
        <!-- END IF -->
        </div>
        <!-- IF '${OPEN_PAGE}' == '' -->
        <div class="control marginLeft">
          <input class="btn btn-success" type="submit" value="Отправить">
        </div>
        <!-- END IF -->
      </form>
    </div>
  </div>
</div>
