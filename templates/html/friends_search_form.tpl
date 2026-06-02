<link rel="stylesheet" type="text/css" href="./templates/css/select2.css">
<form autocomplete="off" action="./" method="GET" role="search" class='search_friends'>
  <input type="hidden" name="task" value="search">
  <input type="hidden" value="user" name="q">
  <div class="add-photos-album selects-field-events">
      <div class='select-container-text border-top-none'>
        <input type='text' placeholder="Имя" name="search" value="${SEARCH}" class='search_word text-place border-top-none border-right-none'/>
      </div>
      <div class='select-container-text two_block borderLeft'>
        <div class="styled-select styled-select-4">
          <select name="sex">
            <option value="">Пол</option>
            <option value="male"<!-- IF '${SEX}' == 'mail' --> selected="selected"<!-- END IF -->>Мужской</option>
            <option value="female"<!-- IF '${SEX}' == 'female' --> selected="selected"<!-- END IF -->>Женский</option>
          </select>
        </div>
      </div>
      <div class='select-container-text'>
        <input type="hidden" name = "id_place" class="id_place" data-type="search_city"/>
        <input autocomplete="off" class="search_word text-place" type="text" name="place" value="${PLACE}" data-type="search_city" placeholder="Город">
        <div class="select-place" data-type="search_city"></div>
      </div>
      <div class="select-container-text two_block borderLeft" >
        <input type="hidden" name = "id_sport" class="id_place" data-type="search_sport"/>
        <input autocomplete="off" class="search_word text-place" type="text" name="sport" value="${SPORT}" data-type="search_sport" placeholder="Вид спорта">
        <div class="select-place" data-type="search_sport"></div>
      </div>
      <div class='select-container-text'>
        <input type='text' placeholder="Возраст от" maxlength="2" name="min_age" value="${MIN_AGE}" class='search_word text-place age border-right-none'/>
        <input type='text' placeholder="Возраст до" maxlength="2" name="max_age" value="${MAX_AGE}" class='search_word text-place age border-right-none borderLeft'/>
      </div>
      <div class='select-container-text borderLeft borderTop'>
        <div class="checkbox">
          <input id="checkbox-find-comand" type="checkbox" hidden="" <!-- IF '${PHOTO}' == '' || '${PHOTO}' == '1' -->checked="checked"<!-- END IF --> name="photo" value="1">
          <label for="checkbox-find-comand">фото</label>
        </div>
        <input type='submit' class="displayNone"/>
        <button type="button" onclick="$('input[type=submit]').click()" class="btn btn-white">Поиск</button>
      </div>
      <div class='clearfix'></div>
  </div>
</form>
<script>selectAction();</script>
<script type="text/javascript" src="./templates/js/search.js"></script>