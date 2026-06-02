<h2>${TITLE_PAGE}</h2>
<div class="job_form">
  <form autocomplete="off" class="form-horizontal" action="${ACTION}" method="POST" name="form_upload">
    <input type="hidden" name="action" value="add_video">
    <div class="form-group">
      <label class="col-lg-3 control-label" for="video">${STR_VIDEO}</label>
      <div class="col-lg-6">
        <input class="form-control" type="text" value="${VIDEO}" name="video">
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-3 control-label" for="description">${STR_DESCRIPTION}</label>
      <div class="col-lg-6">
        <textarea name="description" rows="3" class='input_hastags' data-num=1>${DESCRIPTION}</textarea>
        <div class='hashtags' data-num=1></div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-3 control-label" for="id_videoalbum">${STR_ALBUM}</label>
      <div class="col-lg-6">
        <div class="styled-select styled-select-4">
        <select name="id_videoalbum">
          <!-- BEGIN row_option_videoalbum -->
          <option value="${ID}" <!-- IF '${ID_PHOTOALBUM}' == '${ID}' -->selected="selected"<!-- END IF -->>${NAME}
          </option>
          <!-- END row_option_videoalbum -->
        </select>
        </div>
      </div>
    </div>
    <div class="control center_text">
      <input class="btn-form save-button" type="submit" value="${BUTTON_ADD}" >
    </div>
  </form>
</div>
<script>
selectAction();
</script>