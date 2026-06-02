<link rel="stylesheet" type="text/css" href="./templates/editor/jquery.cleditor.css">
<script type="text/javascript" src="./templates/editor/jquery.cleditor.min.js"></script>
<script>
        $(document).ready(function () { $("#editForminput").cleditor(
        		{
        		controls: // controls to add to the toolbar
                    "bold italic underline strikethrough subscript superscript | font size " +
                    "style | color highlight removeformat | bullets numbering | outdent " +
                    "indent | alignleft center alignright justify | undo redo | " +
                    "rule image link unlink | cut copy paste pastetext | print source",
                colors: // colors in the color popup
                    "FFF FCC FC9 FF9 FFC 9F9 9FF CFF CCF FCF " +
                    "CCC F66 F96 FF6 FF3 6F9 3FF 6FF 99F F9F " +
                    "BBB F00 F90 FC6 FF0 3F3 6CC 3CF 66C C6C " +
                    "999 C00 F60 FC3 FC0 3C0 0CC 36F 63F C3C " +
                    "666 900 C60 C93 990 090 399 33F 60C 939 " +
                    "333 600 930 963 660 060 366 009 339 636 " +
                    "000 300 630 633 330 030 033 006 309 303",
                fonts: // font names in the font popup
                    "Arial,Arial Black,Comic Sans MS,Courier New,Narrow,Garamond," +
                    "Georgia,Impact,Sans Serif,Serif,Tahoma,Trebuchet MS,Verdana",
                sizes: // sizes in the font size popup
                    "1,2,3,4,5,6,7",
                styles: // styles in the style popup
                    [["Paragraph", "<p>"], ["Header 1", "<h1>"], ["Header 2", "<h2>"],
                    ["Header 3", "<h3>"],  ["Header 4","<h4>"],  ["Header 5","<h5>"],
                    ["Header 6","<h6>"]],
                useCSS: false, // use CSS to style HTML when possible (not supported in ie)
                docType: // Document type contained within the editor
                    '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
                docCSSFile: // CSS file used to style the document contained within the editor
                    "",
                bodyStyle: // style to assign to document body contained within the editor
                    "margin:4px; font:10pt Arial,Verdana; cursor:text"
            })
    });
    </script>
<form autocomplete="off" class="form-horizontal create_form" method="POST" action="${ACTION}" enctype="multipart/form-data">
  <input type="hidden" value="${ACTION_EVENT}" name="action">
  <div class="form-group">
    <label class="col-lg-3 control-label" for="name">${STR_NAME}</label>
    <div class="col-lg-6">
      <input class="form-control" type="text" value="${NAME}" name="name">
      <label class='error_label' name="name">Некорректное поле</label>
    </div>
  </div>
  <br>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="sport_type">${STR_SPORT_TYPE}</label>
    <div class="col-lg-6">
      <input type="hidden" name="id_sport" value="${ID_PLACE}" class="id_place" data-type="search_sport"/>
      <input autocomplete="off" class="form-control search_word text-place border-top-none" value="${SPORT_TYPE}" type="text" name="sport_type" data-type="search_sport">
      <div class="select-place" data-type="search_sport"></div>
      <label class='error_label' name="sport_type">Некорректное поле</label>
    </div>
  </div>
  <br>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="date_from">${STR_START_TIME}</label>
    <div class="col-lg-6">
      <input class="form-control datetime" type="text" placeholder="${STR_DATE_FORMAT}" id="datepicker" value="${EVENT_DATE_FROM}" name="event_date_from" class='width32'>
       <select name="event_hour_from" class="form-control datetime">
		<option <!-- IF '${EVENT_HOUR_FROM}' == '00' -->selected="selected"<!-- END IF --> value="00">00</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '01' -->selected="selected"<!-- END IF --> value="01">01</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '02' -->selected="selected"<!-- END IF --> value="02">02</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '03' -->selected="selected"<!-- END IF --> value="03">03</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '04' -->selected="selected"<!-- END IF --> value="04">04</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '05' -->selected="selected"<!-- END IF --> value="05">05</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '06' -->selected="selected"<!-- END IF --> value="06">06</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '07' -->selected="selected"<!-- END IF --> value="07">07</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '08' -->selected="selected"<!-- END IF --> value="08">08</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '09' -->selected="selected"<!-- END IF --> value="09">09</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '10' -->selected="selected"<!-- END IF --> value="10">10</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '11' -->selected="selected"<!-- END IF --> value="11">11</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '12' -->selected="selected"<!-- END IF --> value="12">12</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '13' -->selected="selected"<!-- END IF --> value="13">13</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '14' -->selected="selected"<!-- END IF --> value="14">14</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '15' -->selected="selected"<!-- END IF --> value="15">15</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '16' -->selected="selected"<!-- END IF --> value="16">16</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '' -->selected="selected"<!-- END IF --> value="17">17</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '18' -->selected="selected"<!-- END IF --> value="18">18</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '19' -->selected="selected"<!-- END IF --> value="19">19</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '20' -->selected="selected"<!-- END IF --> value="20">20</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '21' -->selected="selected"<!-- END IF --> value="21">21</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '22' -->selected="selected"<!-- END IF --> value="22">22</option>
		<option <!-- IF '${EVENT_HOUR_FROM}' == '23' -->selected="selected"<!-- END IF --> value="23">23</option>
	  </select>
	  <select name="event_minute_from" class="form-control datetime">
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '00' -->selected="selected"<!-- END IF --> value="00">00</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '' -->selected="selected"<!-- END IF --> value="01">01</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '02' -->selected="selected"<!-- END IF --> value="02">02</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '03' -->selected="selected"<!-- END IF --> value="03">03</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '04' -->selected="selected"<!-- END IF --> value="04">04</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '05' -->selected="selected"<!-- END IF --> value="05">05</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '06' -->selected="selected"<!-- END IF --> value="06">06</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '07' -->selected="selected"<!-- END IF --> value="07">07</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '08' -->selected="selected"<!-- END IF --> value="08">08</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '09' -->selected="selected"<!-- END IF --> value="09">09</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '10' -->selected="selected"<!-- END IF --> value="10">10</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '11' -->selected="selected"<!-- END IF --> value="11">11</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '12' -->selected="selected"<!-- END IF --> value="12">12</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '13' -->selected="selected"<!-- END IF --> value="13">13</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '14' -->selected="selected"<!-- END IF --> value="14">14</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '15' -->selected="selected"<!-- END IF --> value="15">15</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '16' -->selected="selected"<!-- END IF --> value="16">16</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '17' -->selected="selected"<!-- END IF --> value="17">17</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '18' -->selected="selected"<!-- END IF --> value="18">18</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '19' -->selected="selected"<!-- END IF --> value="19">19</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '20' -->selected="selected"<!-- END IF --> value="20">20</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '21' -->selected="selected"<!-- END IF --> value="21">21</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '22' -->selected="selected"<!-- END IF --> value="22">22</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '23' -->selected="selected"<!-- END IF --> value="23">23</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '24' -->selected="selected"<!-- END IF --> value="24">24</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '25' -->selected="selected"<!-- END IF --> value="25">25</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '26' -->selected="selected"<!-- END IF --> value="26">26</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '27' -->selected="selected"<!-- END IF --> value="27">27</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '28' -->selected="selected"<!-- END IF --> value="28">28</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '29' -->selected="selected"<!-- END IF --> value="29">29</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '30' -->selected="selected"<!-- END IF --> value="30">30</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '31' -->selected="selected"<!-- END IF --> value="31">31</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '32' -->selected="selected"<!-- END IF --> value="32">32</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '33' -->selected="selected"<!-- END IF --> value="33">33</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '34' -->selected="selected"<!-- END IF --> value="34">34</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '35' -->selected="selected"<!-- END IF --> value="35">35</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '36' -->selected="selected"<!-- END IF --> value="36">36</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '37' -->selected="selected"<!-- END IF --> value="37">37</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '38' -->selected="selected"<!-- END IF --> value="38">38</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '39' -->selected="selected"<!-- END IF --> value="39">39</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '40' -->selected="selected"<!-- END IF --> value="40">40</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '41' -->selected="selected"<!-- END IF --> value="41">41</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '42' -->selected="selected"<!-- END IF --> value="42">42</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '43' -->selected="selected"<!-- END IF --> value="43">43</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '44' -->selected="selected"<!-- END IF --> value="44">44</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '45' -->selected="selected"<!-- END IF --> value="45">45</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '46' -->selected="selected"<!-- END IF --> value="46">46</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '47' -->selected="selected"<!-- END IF --> value="47">47</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '48' -->selected="selected"<!-- END IF --> value="48">48</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '49' -->selected="selected"<!-- END IF --> value="49">49</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '50' -->selected="selected"<!-- END IF --> value="50">50</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '51' -->selected="selected"<!-- END IF --> value="51">51</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '52' -->selected="selected"<!-- END IF --> value="52">52</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '53' -->selected="selected"<!-- END IF --> value="53">53</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '54' -->selected="selected"<!-- END IF --> value="54">54</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '55' -->selected="selected"<!-- END IF --> value="55">55</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '56' -->selected="selected"<!-- END IF --> value="56">56</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '57' -->selected="selected"<!-- END IF --> value="57">57</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '58' -->selected="selected"<!-- END IF --> value="58">58</option>
		<option <!-- IF '${EVENT_MINUTE_FROM}' == '59' -->selected="selected"<!-- END IF --> value="59">59</option>
	 </select>
    </div>
  </div>
  <!-- IF '${SHOW_TIME_END}' == '' -->
  <div class="form-group button_time">
    <div class="col-lg-6"><a onclick='show_time_end()'>${STR_SPECIFY_TIME_END}</a></div>
  </div>
  <!-- END IF -->
  <div id='time_end' class="form-group <!-- IF '${SHOW_TIME_END}' == '' -->hiden<!-- END IF -->">
      <label class='error_label' name="event_date_to">Некорректное поле</label>
    <div class="col-lg-6">
      <input class="form-control datetime" type="text" placeholder="${STR_DATE_FORMAT}" id="datepicker_end" value="${EVENT_DATE_TO}" name="event_date_to" class='width32'>
      <select name="event_hour_to" class="form-control datetime">
		<option <!-- IF '${EVENT_HOUR_TO}' == '00' -->selected="selected"<!-- END IF --> value="00">00</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '01' -->selected="selected"<!-- END IF --> value="01">01</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '02' -->selected="selected"<!-- END IF --> value="02">02</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '03' -->selected="selected"<!-- END IF --> value="03">03</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '04' -->selected="selected"<!-- END IF --> value="04">04</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '05' -->selected="selected"<!-- END IF --> value="05">05</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '06' -->selected="selected"<!-- END IF --> value="06">06</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '07' -->selected="selected"<!-- END IF --> value="07">07</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '08' -->selected="selected"<!-- END IF --> value="08">08</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '09' -->selected="selected"<!-- END IF --> value="09">09</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '10' -->selected="selected"<!-- END IF --> value="10">10</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '11' -->selected="selected"<!-- END IF --> value="11">11</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '12' -->selected="selected"<!-- END IF --> value="12">12</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '13' -->selected="selected"<!-- END IF --> value="13">13</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '14' -->selected="selected"<!-- END IF --> value="14">14</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '15' -->selected="selected"<!-- END IF --> value="15">15</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '16' -->selected="selected"<!-- END IF --> value="16">16</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '' -->selected="selected"<!-- END IF --> value="17">17</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '18' -->selected="selected"<!-- END IF --> value="18">18</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '19' -->selected="selected"<!-- END IF --> value="19">19</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '20' -->selected="selected"<!-- END IF --> value="20">20</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '21' -->selected="selected"<!-- END IF --> value="21">21</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '22' -->selected="selected"<!-- END IF --> value="22">22</option>
		<option <!-- IF '${EVENT_HOUR_TO}' == '23' -->selected="selected"<!-- END IF --> value="23">23</option>
	  </select>
	  <select name="event_minute_to" class="form-control datetime">
		<option <!-- IF '${EVENT_MINUTE_TO}' == '00' -->selected="selected"<!-- END IF --> value="00">00</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '' -->selected="selected"<!-- END IF --> value="01">01</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '02' -->selected="selected"<!-- END IF --> value="02">02</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '03' -->selected="selected"<!-- END IF --> value="03">03</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '04' -->selected="selected"<!-- END IF --> value="04">04</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '05' -->selected="selected"<!-- END IF --> value="05">05</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '06' -->selected="selected"<!-- END IF --> value="06">06</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '07' -->selected="selected"<!-- END IF --> value="07">07</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '08' -->selected="selected"<!-- END IF --> value="08">08</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '09' -->selected="selected"<!-- END IF --> value="09">09</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '10' -->selected="selected"<!-- END IF --> value="10">10</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '11' -->selected="selected"<!-- END IF --> value="11">11</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '12' -->selected="selected"<!-- END IF --> value="12">12</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '13' -->selected="selected"<!-- END IF --> value="13">13</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '14' -->selected="selected"<!-- END IF --> value="14">14</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '15' -->selected="selected"<!-- END IF --> value="15">15</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '16' -->selected="selected"<!-- END IF --> value="16">16</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '17' -->selected="selected"<!-- END IF --> value="17">17</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '18' -->selected="selected"<!-- END IF --> value="18">18</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '19' -->selected="selected"<!-- END IF --> value="19">19</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '20' -->selected="selected"<!-- END IF --> value="20">20</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '21' -->selected="selected"<!-- END IF --> value="21">21</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '22' -->selected="selected"<!-- END IF --> value="22">22</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '23' -->selected="selected"<!-- END IF --> value="23">23</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '24' -->selected="selected"<!-- END IF --> value="24">24</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '25' -->selected="selected"<!-- END IF --> value="25">25</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '26' -->selected="selected"<!-- END IF --> value="26">26</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '27' -->selected="selected"<!-- END IF --> value="27">27</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '28' -->selected="selected"<!-- END IF --> value="28">28</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '29' -->selected="selected"<!-- END IF --> value="29">29</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '30' -->selected="selected"<!-- END IF --> value="30">30</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '31' -->selected="selected"<!-- END IF --> value="31">31</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '32' -->selected="selected"<!-- END IF --> value="32">32</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '33' -->selected="selected"<!-- END IF --> value="33">33</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '34' -->selected="selected"<!-- END IF --> value="34">34</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '35' -->selected="selected"<!-- END IF --> value="35">35</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '36' -->selected="selected"<!-- END IF --> value="36">36</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '37' -->selected="selected"<!-- END IF --> value="37">37</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '38' -->selected="selected"<!-- END IF --> value="38">38</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '39' -->selected="selected"<!-- END IF --> value="39">39</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '40' -->selected="selected"<!-- END IF --> value="40">40</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '41' -->selected="selected"<!-- END IF --> value="41">41</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '42' -->selected="selected"<!-- END IF --> value="42">42</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '43' -->selected="selected"<!-- END IF --> value="43">43</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '44' -->selected="selected"<!-- END IF --> value="44">44</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '45' -->selected="selected"<!-- END IF --> value="45">45</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '46' -->selected="selected"<!-- END IF --> value="46">46</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '47' -->selected="selected"<!-- END IF --> value="47">47</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '48' -->selected="selected"<!-- END IF --> value="48">48</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '49' -->selected="selected"<!-- END IF --> value="49">49</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '50' -->selected="selected"<!-- END IF --> value="50">50</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '51' -->selected="selected"<!-- END IF --> value="51">51</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '52' -->selected="selected"<!-- END IF --> value="52">52</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '53' -->selected="selected"<!-- END IF --> value="53">53</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '54' -->selected="selected"<!-- END IF --> value="54">54</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '55' -->selected="selected"<!-- END IF --> value="55">55</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '56' -->selected="selected"<!-- END IF --> value="56">56</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '57' -->selected="selected"<!-- END IF --> value="57">57</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '58' -->selected="selected"<!-- END IF --> value="58">58</option>
		<option <!-- IF '${EVENT_MINUTE_TO}' == '59' -->selected="selected"<!-- END IF --> value="59">59</option>
	 </select>
    </div>
  </div>
  <br>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="description">${STR_DESCRIPTION}</label>
    <div class="col-lg-6">
      <textarea class="form-control" name="description" id='editForminput'>${DESCRIPTION}</textarea>
      <label class='error_label' name="description">Некорректное поле</label>
    </div>
  </div>
  <br>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="place">${STR_LOCATION}</label>
    <div class="col-lg-6">
      <input type="hidden" name="id_place" value="${ID_PLACE}" class="id_place" data-type="search_city"/>
      <input autocomplete="off" class="form-control search_word text-place border-top-none" type="text" name="place" value="${PLACE}" data-type="search_city">
      <div class="select-place" data-type="search_city"></div>
      <label class='error_label' name="place">Некорректное поле</label>
    </div>
  </div>
  <br>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="address">${STR_ADDRESS}</label>
    <div class="col-lg-6">
      <textarea class="form-control height100" name="address">${ADDRESS}</textarea>
      <label class='error_label' name="address">Некорректное поле</label>
    </div>
  </div>
  <br>
  <div class="form-group">
    <div class="col-sm-6">
      <!--<h3>${STR_PHOTO}</h3>-->
      <img id='preview_cover' border="0" src="${EVENT_COVER_PAGE}" width="200">
      <div class="file_upload">
        <button type="button" id='cover'>${BUTTON_CHANGE_COVER}</button>
      </div>
    </div>
  </div>
  <input type="hidden" name="file_cover" id='file_cover_src' value="${EVENT_COVER_PAGE}">
  <br>
  <div class="form-group center_text">
    <input class="btn-form save-button" type="submit" value="${BUTTON}">
  </div>
</form>
