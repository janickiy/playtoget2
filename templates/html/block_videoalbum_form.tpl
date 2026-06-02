<h2>${TITLE_PAGE}</h2>
<form autocomplete="off" class="form-horizontal" method="post" action="${ACTION}" accept-charset="UTF-8">
  <input type="hidden" name="action" value="${QUERY}">
  <div class="education_form">
    <div class="form-group">
      <label class="col-lg-3 control-label" for="name">${STR_NAME}</label>
      <div class="col-lg-6">
        <input class="form-control" type="text" value="${NAME}" name="name">
      </div>
    </div>
  </div>
  <div class="control center_text">
    <input class="btn-form save-button margin0Auto" type="submit" value="${BUTTON}">
  </div>
</form>