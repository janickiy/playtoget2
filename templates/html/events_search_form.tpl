<form autocomplete="off" role="search" method="GET" action="">
  <input type="hidden" value="search" name="task">
  <input type="hidden" value="event" name="q">
  <div class="add-photos-album selects-field-events">
    <div class="select-container-text two_block">
      <input type="hidden" name = "id_place" class="id_place" data-type="search_city"/>
      <input autocomplete="off" class="search_word text-place border-top-none" type="text" name="place" value="${PLACE}" data-type="search_city" placeholder='${STR_LOOKING_FOR_EVENT_IN_CITY}'>
      <div class="select-place" data-type="search_city"></div>
    </div>
    <div class="select-container-text two_block borderLeft" >
      <input type="hidden" name = "id_sport" class="id_place" data-type="search_sport"/>
      <input autocomplete="off" class="search_word text-place border-top-none" type="text" name="sport" value="${SPORT}" data-type="search_sport" placeholder='${STR_LOOKING_FOR_SPORT_TYPE}'>
      <div class="select-place" data-type="search_sport"></div>
    </div>
    <p class="select-container-text lupa">
      <input type="text" name="search" value="${SEARCH}" class="search_word" placeholder="${STR_KEYWORD}">
      <span></span> </p>
    <input type='submit' class="displayNone"/>
    <button type="button" onclick="location.href='./?task=events&q=create'" class="btn btn-white">${BUTTON_CREATE_EVENT}</button>
  </div>
</form>