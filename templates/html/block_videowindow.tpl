<script src="./templates/js/videoalbum.js"></script>
<div class='overlay' id='video_big'>
  <div class='overlay-back back_one'>Закрыть</div>
  <div class='prev'></div>
  <div class='photo_big_wrap'>
    <input type='hidden' id='id_owner'/>
    <div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>
    <img id='prev_video' src='./templates/images/prev.png'/> <img id='next_video' src='./templates/images/next.png'/>
    <div class='video_wrap'></div>
    <div class="text">
      <div class="message">
        <div class='text-block' id='name_video'><a href="./?task=profile&id_user=${ID_USER}">${FIRSTNAME} ${LASTNAME}</a></div>
        <div class='text-block' id='date_video'><span class="data">25.01.2016</span></div>
        <!-- IF '${OPEN_PAGE}' == '' -->
        <div class='text-block foto_like'> <a class="tell"  data-type="video">0</a> <a class="liked"  data-type="video">0</a> </div>
        <!-- END IF -->
        <p class='info'></p>
      </div>
    </div>
    <div class='text' id='mainComments'>
      <div id='addCommentContainers' data-type='video'></div>
    </div>
    <div class="text">
      <form autocomplete="off" id="addCommentForm" class="form-horizontal" method="POST" action="">
        <input type='hidden' name="commentable_type" value="video"/>
        <input type='hidden' name="id_content" id='id_content' value="${ID}"/>
        <input type='hidden' name="id_user" value="${ID_USER}"/>
        <input type='hidden' name="id_parent" value="0"/>
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
          <label class="col-lg-12 control-label center_text marginTop20" for="comment">Чтобы оставить комментарий авторизуйтесь</label>
        <!-- END IF -->
        </div>
        <!-- IF '${OPEN_PAGE}' == '' -->
        <div class="control marginLeft">
          <input class="btn btn-success" id="submit" type="submit" value="Отправить">
        </div>
        <!-- END IF -->
      </form>
    </div>
  </div>
</div>
<div class='overlay' id='overlay'>
  <div class='overlay-back back_two'>Закрыть</div>
  <div class='overlay-back prev'></div>
  <div class='photo_big_wrap' id='foto_wind'> <img src='#' class='photo_wrap next'/>
    <div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>
  </div>
</div>