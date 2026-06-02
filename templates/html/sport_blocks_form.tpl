<form autocomplete="off" class="form-horizontal create_form" method="post" action="${ACTION}">
  <input type="hidden" name="action" value="${FORM_ACTION}">
  <div class="form-group">
    <label class="col-lg-3 control-label" for="name">${STR_NAME}</label>
    <div class="col-lg-6">
      <input class="form-control" type="text" name="name" value="${SPORT_BLOCK_NAME}">
      <label class='error_label' name="name">Некорректное поле</label>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="text">${STR_DESCRIPTION}</label>
    <div class="col-lg-6">
      <textarea class="form-control form-dark" name="about" rows="4">${SPORT_BLOCK_ABOUT}</textarea>
      <label class='error_label' name="about">Некорректное поле</label>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="name">${STR_CITY}</label>
    <div class="col-lg-6">
      <input type="hidden" name="id_place" value="${SPORT_BLOCK_ID_PLACE}" class="id_place" data-type="search_city"/>
      <input autocomplete="off" class="form-control search_word text-place border-top-none" type="text" value="${SPORT_BLOCK_PLACE}" name="place" data-type="search_city">
      <div class="select-place" data-type="search_city"></div>
      <label class='error_label' name="place">Некорректное поле</label>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="text">${STR_ADDRESS}</label>
    <div class="col-lg-6">
      <textarea class="form-control form-dark" name="address" rows="4">${SPORT_BLOCK_ADDRESS}</textarea>
      <label class='error_label' name="address">Некорректное поле</label>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="text">${STR_PHONE}</label>
    <div class="col-lg-6">
      <input class="form-control" type="text" name="phone" value="${SPORT_BLOCK_PHONE}">
      <label class='error_label' name="phone">Некорректное поле</label>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="text">${STR_EMAIL}</label>
    <div class="col-lg-6">
      <input class="form-control" type="text" name="email" value="${SPORT_BLOCK_EMAIL}">
      <label class='error_label' name="email">Некорректное поле</label>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="text">${STR_WEBSITE}</label>
    <div class="col-lg-6">
      <input class="form-control" type="text" name="website" value="${SPORT_BLOCK_WEBSITE}">
      <label class='error_label' name="website">Некорректное поле</label>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="text"></label>
    <div class="col-lg-6"> <img id="preview_ava" border="0" width="200" src="${SPORT_BLOCK_AVATAR}">
      <div class="file_upload">
        <button type="button" id='avatar'>${BUTTON_EDIT_PHOTO}</button>
      </div>
    </div>
  </div>
  <div class="form-group center_text">
    <input type="hidden" name="file_ava" id='file_ava_src' value="${SPORT_BLOCK_AVATAR}">
    <input class="btn-form save-button" type="submit" value="${BUTTON}">
  </div>
</form>
