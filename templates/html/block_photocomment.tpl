<link rel="stylesheet" href="./templates/css/emotions.css">
<link rel="stylesheet" href="./templates/css/jquery.emotions.fb.css">
<div class='overlay' id='photo_big'>
  <div class='overlay-back back_one'>Закрыть</div>
  <div class='prev'>Предыдущая</div>
  <div class='photo_big_wrap'>
    <input type='hidden' id='id_owner'/>
    <img src='#' class='photo_wrap next'/>
    <div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif"></div>
    <div class="text">
      <div class="message">
        <div class='text-block' id='name_foto'><a href="./?task=profile&id_user=${ID_USER}">${FIRSTNAME} ${LASTNAME}</a></div>
        <div class='text-block' id='date_foto'><span class="data">25.01.2016</span></div>
        <div class='text-block foto_like' id='likes_foto'> <a class="tell"  data-type="photo">0</a> <a class="liked"  data-type="photo">0</a> </div>
      </div>
    </div>
    <div class='text' id='mainComments'>
      <div id='addCommentContainer'></div>
    </div>
    <div class="text">
      <form autocomplete="off" id="addCommentForm" class="form-horizontal" method="POST" action="">
        <input type='hidden' name="commentable_type" value="photo"/>
        <input type='hidden' name="id_content" id='id_content' value="${ID_PHOTO}"/>
        <input type='hidden' name="id_user" value="${ID_USER}"/>
        <input type='hidden' name="id_parent" value="0"/>
        <div class="form-group">
          <label class="col-lg-3 control-label" for="comment">Оставить комментарий</label>
          <div class="col-lg-7">
            <input type="file"  class="file_name" name="file_name[]" data-num="0" multiple/>
            <textarea class="form-control form-dark" id="comment" rows="4" name="comment"></textarea>
          </div>
          <div class="smile-files"> <a id="smilesBtn" class="smile smilesBtn" data-num="0"><img src="./templates/images/smile.png" alt=""></a> <a href="#" class="files" data-num="0"><img src="./templates/images/files.png" alt=""></a> </div>
          <div class="smilesChoose" data-num="0"></div>
          <div class='files_block' data-num="0"></div>
        </div>
        <div class="control marginLeft">
          <input class="btn btn-success" id="submit" type="submit" value="Отправить">
        </div>
      </form>
    </div>
  </div>
</div>
<div class='overlay' id='overlay'>
  <div class='overlay-back back_two'>Закрыть</div>
  <div class='overlay-back prev'></div>
  <div class='photo_big_wrap' id='foto_wind'> <img src='#' class='photo_wrap next'/>
    <div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif"></div>
  </div>
</div>