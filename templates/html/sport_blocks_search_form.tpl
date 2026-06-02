<form autocomplete="off" action="./" method="GET" role="search">
  <input type="hidden" name="task" value="search">
  <input type="hidden" name="q" value="${SPORT_BLOCKS_TYPE}">
  <div class="add-photos-album selects-field-events">
  <p class="select-container-text lupa width100" >
      <input type="text" name="search" value="${SEARCH}" class="search_word border-top-none padding25" placeholder="${STR_KEYWORD}">
      <span class='padding2'></span> </p>
    <div class="select-container-text two_block">
      <input type="hidden" name="id_place" class="id_place" data-type="search_city"/>
      <input autocomplete="off" class="search_word text-place" type="text" name="place" data-type="search_city" placeholder="${STR_SEARCH_SPORT_BLOCKS_IN_CITY}" >
      <div class="select-place" data-type="search_city"></div>
    </div>
    
    
    <input type='submit' class="displayNone"/>
    <button type="button" onclick="location.href='${CREATE_SPORT_BLOCKS}'" class="btn btn-white">${BUTTON_CREATE_SPORT_BLOCKS}</button>
  </div>
</form>