<form autocomplete="off" action="./" method="GET" role="search">
  <input type="hidden" name="task" value="search">
  <input type="hidden" name="q" value="${COMMUNITY_TYPE}">
  <div class="add-photos-album selects-field-events">
    <div class="select-container-text two_block">
      <input type="hidden" name="id_place" class="id_place" data-type="search_city"/>
      <input autocomplete="off" class="search_word text-place border-top-none" type="text" value="${PLACE}" name="place" data-type="search_city" placeholder="${STR_SEARCH_COMMUNITY_IN_CITY}">
      <div class="select-place" data-type="search_city"></div>
    </div>
    <div class="select-container-text two_block borderLeft">
      <input type="hidden" name="id_sport" class="id_place" data-type="search_sport"/>
      <input autocomplete="off" class="search_word text-place border-top-none" type="text" value="${SPORT}" name="sport" data-type="search_sport" placeholder="${STR_SEARCH_SPORT_TYPE}">
      <div class="select-place" data-type="search_sport"></div>
    </div>
    <p class="select-container-text lupa">
      <input type="text" name="search" value="${SEARCH}" class="search_word" placeholder="${STR_KEYWORD}">
      <span></span> </p>
    <input type='submit' class='displayNone'/>
    <button type="button" onclick="location.href='${CREATE_COMMUNITY}'" class="btn btn-white">${BUTTON_CREATE_COMMUNITY}</button>
  </div>
</form>