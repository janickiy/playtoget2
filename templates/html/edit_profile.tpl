<!-- INCLUDE header.tpl -->
<!--START CONTENT-->

<script type="text/javascript" src="./templates/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="./templates/js/jquery.ui.datepicker-ru.js"></script>
<script type="text/javascript" src="./templates/js/jquery.form.min.js"></script>
<script type="text/javascript" src="./templates/js/script_all.js"></script>

<script type="text/javascript">



   
 

$(document).on('click', '.plus', function(){
	let r = getRandomInt(1,99999);
	$(this).before(
	'<div class="education_form_new">' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="education_place[]">${STR_EDUCATION_PLACE}</label><div class="col-lg-6"><input type="hidden" name="id_education_place[]" class="id_place" data-type="education_'+r+'"/><input autocomplete="off" class="form-control text-place" type="text" name="education_place[]" data-type="education_'+r+'" value=""><div class="select-place" data-type="education_'+r+'"></div></div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="education_name[]">${STR_EDUCATION_NAME}</label><div class="col-lg-6"><input class="form-control" autocomplete="off" type="text" name="education_name[]" value=""></div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="education_description[]">${STR_EDUCATION_DESCRIPTION}</label><div class="col-lg-6"><input autocomplete="off" class="form-control" type="text" name="education_description[]" value=""></div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="education_month_start[]">${STR_EDUCATION_MONTH_START}</label><div class="col-lg-6">' +
	'<div class="styled-select styled-select-4"><select class="form-control form-primary" name="education_month_start[]">' +
	'<option value="">${STR_NO}</option>' +
	'<option value="1">${STR_MONTH_JAN}</option>' +
	'<option value="2">${STR_MONTH_FEB}</option>' +
	'<option value="3">${STR_MONTH_MARCH}</option>' +
	'<option value="4">${STR_MONTH_APR}</option>' +
	'<option value="5">${STR_MONTH_MAY}</option>' +
	'<option value="6">${STR_MONTH_JUN}</option>' +
	'<option value="7">${STR_MONTH_JUL}</option>' +
	'<option value="8">${STR_MONTH_AUG}</option>' +
	'<option value="9">${STR_MONTH_SEP}</option>' +
	'<option value="10">${STR_MONTH_OCT}</option>' +
	'<option value="11">${STR_MONTH_NOV}</option>' +
	'<option value="12">${STR_MONTH_DEC}</option>' +
	'</select></div>' +		
	'</div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="education_year_start[]">${STR_EDUCATION_YEAR_START}</label><div class="col-lg-6">' +
	'<div class="styled-select styled-select-4"><select class="form-control form-primary" name="education_year_start[]">' +
	'<option value="">${STR_NO}</option>' +	
	<!-- BEGIN OPTION_YEARS_LIST_START -->	
	'<option value="${YEAR}">${YEAR}</option>' +
	<!-- END OPTION_YEARS_LIST_START -->	
	'</select></div>' +	
	'</div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="education_month_finish[]">${STR_EDUCATION_MONTH_FINISH}</label><div class="col-lg-6">' +
	'<div class="styled-select styled-select-4"><select class="form-control form-primary" name="education_month_finish[]">' +
	'<option value="">${STR_NO}</option>' +
	'<option value="1">${STR_MONTH_JAN}</option>' +
	'<option value="2">${STR_MONTH_FEB}</option>' +
	'<option value="3">${STR_MONTH_MARCH}</option>' +
	'<option value="4">${STR_MONTH_APR}</option>' +
	'<option value="5">${STR_MONTH_MAY}</option>' +
	'<option value="6">${STR_MONTH_JUN}</option>' +
	'<option value="7">${STR_MONTH_JUL}</option>' +
	'<option value="8">${STR_MONTH_AUG}</option>' +
	'<option value="9">${STR_MONTH_SEP}</option>' +
	'<option value="10">${STR_MONTH_OCT}</option>' +
	'<option value="11">${STR_MONTH_NOV}</option>' +
	'<option value="12">${STR_MONTH_DEC}</option>' +
	'</select></div>' +	
	'</div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="education_year_finish[]">${STR_EDUCATION_YEAR_FINISH}</label><div class="col-lg-6">' +
	'<div class="styled-select styled-select-4"><select class="form-control form-primary" name="education_year_finish[]">' +
	'<option value="">${STR_NO}</option>' +	
	<!-- BEGIN OPTION_YEARS_LIST_END -->	
	'<option value="${YEAR}">${YEAR}</option>' +
	<!-- END OPTION_YEARS_LIST_END -->	
	'</select></div>' +
	'<input type="hidden" name="education_kind[]" value="1"><span class="btn-form button-del minus pull-right">${BUTTON_REMOVE_EDUCATION}</span>' +
	'</div></div></div>'
	);
});



$(document).on('click', '.plus_sport_type', function(){
	let r = getRandomInt(1,99999);
	$(this).before(
		'<div class="sport_type_form_new">' +
		'<div class="form-group"><label class="col-lg-3 control-label" for="sport_type[]">${STR_SPORT_TYPE}</label><div class="col-lg-6">' +
		' <div class="styled-select styled-select-4">'+

		'<input type="hidden" name = "id_sport_type[]" class="id_place" data-type="search_sport_'+r+'"/>'+
        '<input autocomplete="off" class="form-control text-place" type="text" name="sport_type[]" data-type="search_sport_'+r+'">'+
        '<div class="select-place" data-type="search_sport_'+r+'"></div>'+

		'</div></div></div>' +
		'<div class="form-group"><label class="col-lg-3 control-label" for="sport_level[]">${STR_LEVEL}</label><div class="col-lg-6">' +
		' <div class="styled-select styled-select-4"><select class="form-control form-primary" name="spoort_level[]">' +
		'<option value="">${STR_NO}</option>' +		
		<!-- BEGIN OPTION_SPORT_LEVEL -->
		'<option value="${ID_LEVEL}">${LEVEL_NAME}</option>' +
		<!-- END OPTION_SPORT_LEVEL -->		
		'</select></div></div></div>' +		
		'<div class="form-group">' +
		'<div class="col-lg-6">' +
		'<div class="checkbox">' +
		'<input id="checkbox-find-comand" type="checkbox" hidden=""  name="search_team[]" value="1">' +
        '<label for="checkbox-find-comand"></label>' +
		'</div><label class="col-lg-3 control-label" for="search_team[]">${STR_SEARCH_TEAM}</label>'+
		'<span class="btn-form button-del minus_sport_type pull-right">${BUTTON_REMOVE_SPORT_TYPE}</span></div>' +
		'</div></div>'
	);
});



$(document).on('click', '.plus_job', function(){
	let r = getRandomInt(1,99999);
	$(this).before(
	'<div class="job_form_new">' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="job_place[]">${STR_JOB_PLACE}</label><div class="col-lg-6"><input type="hidden" name = "id_job_place[]" class="id_place" data-type="job_'+r+'"/><input autocomplete="off" class="form-control text-place" type="text" name="job_place[]" value="" data-type="job_'+r+'"><div class="select-place" data-type="job_'+r+'"></div></div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="job_name[]">${STR_JOB_NAME}</label><div class="col-lg-6"><input autocomplete="off" class="form-control" type="text" name="job_name[]" value=""></div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="job_description[]">${STR_JOB_DESCRIPTION}</label><div class="col-lg-6"><input autocomplete="off" class="form-control" type="text" name="job_description[]" value=""></div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="job_month_start[]">${STR_JOB_MONTH_START}</label><div class="col-lg-6">' +
	'<div class="styled-select styled-select-4"><select class="form-control form-primary" name="job_month_start[]">' +
	'<option value="">${STR_NO}</option>' +
	'<option value="1">${STR_MONTH_JAN}</option>' +
	'<option value="2">${STR_MONTH_FEB}</option>' +
	'<option value="3">${STR_MONTH_MARCH}</option>' +
	'<option value="4">${STR_MONTH_APR}</option>' +
	'<option value="5">${STR_MONTH_MAY}</option>' +
	'<option value="6">${STR_MONTH_JUN}</option>' +
	'<option value="7">${STR_MONTH_JUL}</option>' +
	'<option value="8">${STR_MONTH_AUG}</option>' +
	'<option value="9">${STR_MONTH_SEP}</option>' +
	'<option value="10">${STR_MONTH_OCT}</option>' +
	'<option value="11">${STR_MONTH_NOV}</option>' +
	'<option value="12">${STR_MONTH_DEC}</option>' +
	'</select></div>' +		
	'</div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="job_year_start[]">${STR_JOB_YEAR_START}</label><div class="col-lg-6">' +
	'<div class="styled-select styled-select-4"><select class="form-control form-primary" name="job_year_start[]">' +
	'<option value="">${STR_NO}</option>' +	
	<!-- BEGIN OPTION_JOB_YEARS_LIST_START -->	
	'<option value="${YEAR}">${YEAR}</option>' +
	<!-- END OPTION_JOB_YEARS_LIST_START -->	
	'</select></div>' +	
	'</div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="job_month_finish[]">${STR_JOB_MONTH_FINISH}</label><div class="col-lg-6">' +
	'<div class="styled-select styled-select-4"><select class="form-control form-primary" name="job_month_finish[]">' +
	'<option value="">${STR_NO}</option>' +
	'<option value="1">${STR_MONTH_JAN}</option>' +
	'<option value="2">${STR_MONTH_FEB}</option>' +
	'<option value="3">${STR_MONTH_MARCH}</option>' +
	'<option value="4">${STR_MONTH_APR}</option>' +
	'<option value="5">${STR_MONTH_MAY}</option>' +
	'<option value="6">${STR_MONTH_JUN}</option>' +
	'<option value="7">${STR_MONTH_JUL}</option>' +
	'<option value="8">${STR_MONTH_AUG}</option>' +
	'<option value="9">${STR_MONTH_SEP}</option>' +
	'<option value="10">${STR_MONTH_OCT}</option>' +
	'<option value="11">${STR_MONTH_NOV}</option>' +
	'<option value="12">${STR_MONTH_DEC}</option>' +
	'</select></div>' +	
	'</div></div>' +
	'<div class="form-group"><label class="col-lg-3 control-label" for="education_year_finish[]">${STR_JOB_YEAR_FINISH}</label><div class="col-lg-6">' +
	'<div class="styled-select styled-select-4"><select class="form-control form-primary" name="job_year_finish[]">' +
	'<option value="">${STR_NO}</option>' +	
	<!-- BEGIN OPTION_JOB_YEARS_LIST_END -->	
	'<option value="${YEAR}">${YEAR}</option>' +
	<!-- END OPTION_JOB_YEARS_LIST_END -->	
	'</select></div>' +
	'<input type="hidden" name="job_kind[]" value="3"><span class="btn-form button-del minus_job pull-right">${BUTTON_REMOVE_JOB}</span>' +
	'</div></div></div>'	
	);
});




</script>

<!-- INCLUDE uploadAvatar.tpl -->


<section class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-xs-12  bg">
        
        <!-- INCLUDE left_sitebar.tpl -->
        <div class="content friends">
        <!-- INCLUDE top_user_profile.tpl -->
        <br>
		
        <form  id='main_form' class="form-horizontal" action="${ACTION}" method="post" enctype="multipart/form-data">
          <div id="tabs" class='marginTop20'>
            <ul id='main-menu'>
              <li data-type='main'><a href="#main">${STR_MAIN}</a></li>
              <li data-type='education'><a href="#education">${STR_EDUCATION}</a></li>
              <li data-type='job'><a href="#job">${STR_JOB}</a></li>
              <!--<li data-type='achivments'><a href="#achivments">${STR_SPORT_ACHIVMENTS}</a></li>-->
            <div class='clearfix'></div>
            </ul>

            <div id="main">
              <!--<h3>${STR_PERSONAL_INFORMATION}</h3>-->
			  
			  <!-- IF '${MSG}' != '' -->
					<div class="mutations-both">
						<p>${MSG}</p>
						<a class="delete">x</a>
					</div>
					<!-- END IF -->	

					<!-- IF '${MSG_ERROR}' != '' -->
					<div class="alert alert-error">
					<button class="close" data-dismiss="alert">×</button>
					<strong>${STR_ERROR}:</strong>
					${MSG_ERROR}
					</div>
					<!-- END IF -->	

					<!-- BEGIN show_errors -->
					<div class="alert alert-danger">
					<a class="close" href="#" data-dismiss="alert">×</a>
					<span class="icon icon-remove-sign"></span>
					<h4 class="alert-heading">${STR_IDENTIFIED_FOLLOWING_ERRORS}:</h4>
					<ul>
					<!-- BEGIN row -->
					<li> ${ERROR}</li>
					<!-- END row -->
					</ul>
					</div>
					<!-- END show_errors -->
			
					<!-- IF '${MSG_ALERT}' != '' -->
					<div class="alert alert-success">
					<button class="close" data-dismiss="alert">×</button>
					${MSG_ALERT}</div>
					<!-- END IF -->
			  
              <div class="col-xs-4">
				  
                <!--<h3>${STR_PHOTO}</h3>-->
                <img border="0" src="${AVATAR}" width="200" id='preview_ava' >
				<div class="file_upload">
					<button type="button" id='avatar'>Изменить фото</button>
				</div>
				<br />
                <!--<h3>${STR_COVER_IMAGE}</h3>-->
                <!-- IF '${PROFILE_COVER_PAGE}' != '' -->
                <img border="0" width="200" src="${PROFILE_COVER_PAGE}" id='preview_cover'>
                <!-- ELSE -->
                ${STR_NO_PICTURE}
                <!-- END IF -->
				<div class="file_upload">
					<button type="button" id='cover'>Изменить обложку</button>
				</div>
			
              </div>
              <div class="col-xs-8">
                <div class="control-group">
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="firstname">${STR_FIRSTNAME}</label>
                    <div class="col-lg-9">
                      <input autocomplete="off" class="form-control" type="text" value="${FIRSTNAME}" name="firstname">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="lastname">${STR_LASTNAME}</label>
                    <div class="col-lg-9">
                      <input autocomplete="off" class="form-control" type="text" value="${LASTNAME}" name="lastname">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="secondname">${STR_SECONDNAME}</label>
                    <div class="col-lg-9">
                      <input autocomplete="off" class="form-control" type="text" value="${SECONDNAME}" name="secondname">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="sex">${STR_SEX}</label>
                    <div class="col-lg-9">
						<div class="styled-select styled-select-4">
							<select class="form-control form-primary" name="sex">
								<option value="male" <!-- IF '${OPTION_SEX}' == 'male' -->selected="selected"<!-- END IF -->>${STR_MALE}
								</option>
								<option value="female" <!-- IF '${OPTION_SEX}' == 'female' -->selected="selected"<!-- END IF -->>${STR_FEMALE}
								</option>
							</select>
						</div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="birthday">${STR_BIRTHDAY}</label>
                    <div class="col-lg-9">
                      <input class="form-control" type="text" placeholder="${STR_BIRTHDAY_DATE_FORMAT}" id="datepicker" value="${BIRTHDAY}" name="birthday">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="id_place">${STR_CITY}</label>
                    <div class="col-lg-9">
                      <div class="styled-select styled-select-4">
				        <input type='hidden' name = 'id_place' class='id_place' data-type='osn' value="${ID_PLACE}"/>
                      	<input autocomplete="off" type='text' name = 'name_place' value="${CITY_NAME}" class='text-place' data-type='osn'/>
                      	<div class='select-place' data-type='osn'>
  						</div>
						  <!--<select class="form-control form-primary" name="id_place">
	                        <option></option>
	                      </select>-->
					  </div>
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-lg-3 control-label" for="about">${STR_ABOUT}</label>
                    <div class="col-lg-9">
                      <input autocomplete="off" class="form-control" type="text" value="${ABOUT}" name="about">
                    </div>
                  </div>
				  
				  
                </div>
              </div>
              <div class="col-xs-12">
              	<hr class='line_hr'>
              </div>
            </div>

            <div id="education">
              <!--<h3>${STR_EDUCATION}</h3>-->
              <div class="addform_plus">
                <!-- BEGIN EDUCATION_ROW -->
                <div class="education_form">
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="education_place[]">${STR_EDUCATION_PLACE}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">
					  	<input type="hidden" name="id_education_place[]" class="id_place" data-type="education_${ID}" value="${ID_PLACE}"/>
					  	<input autocomplete="off" class="form-control text-place" type="text" name="education_place[]" data-type="education_${ID}" value="${PLACE}">
					  	<div class="select-place" data-type="education_${ID}"></div>
						
                      </select>
					  </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="education_name[]">${STR_EDUCATION_NAME}</label>
                    <div class="col-lg-6">
                      <input autocomplete="off" class="form-control" type="text" name="education_name[]" value="${NAME}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="education_description[]">${STR_EDUCATION_DESCRIPTION}</label>
                    <div class="col-lg-6">
                      <input autocomplete="off" class="form-control" type="text" name="education_description[]" value="${DESCRIPTION}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="education_month_start[]">${STR_EDUCATION_MONTH_START}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">
                      <select class="form-control form-primary" name="education_month_start[]">
                        <option value="">${STR_NO}</option>
                        <option value="1" <!-- IF '${MONTH_START}' == '1' -->selected="selected"<!-- END IF -->>${STR_MONTH_JAN}</option>
                        <option value="2" <!-- IF '${MONTH_START}' == '2' -->selected="selected"<!-- END IF -->>${STR_MONTH_FEB}</option>
                        <option value="3" <!-- IF '${MONTH_START}' == '3' -->selected="selected"<!-- END IF -->>${STR_MONTH_MARCH}</option>
                        <option value="4" class="select-styled" <!-- IF '${MONTH_START}' == '4' -->selected="selected"<!-- END IF -->>${STR_MONTH_APR}</option>
                        <option value="5" <!-- IF '${MONTH_START}' == '5' -->selected="selected"<!-- END IF -->>${STR_MONTH_MAY}</option>
                        <option value="6" <!-- IF '${MONTH_START}' == '6' -->selected="selected"<!-- END IF -->>${STR_MONTH_JUN}</option>
                        <option value="7" <!-- IF '${MONTH_START}' == '7' -->selected="selected"<!-- END IF -->>${STR_MONTH_JUL}</option>
                        <option value="8" <!-- IF '${MONTH_START}' == '8' -->selected="selected"<!-- END IF -->>${STR_MONTH_AUG}</option>
                        <option value="9" <!-- IF '${MONTH_START}' == '9' -->selected="selected"<!-- END IF -->>${STR_MONTH_SEP}</option>
                        <option value="10" <!-- IF '${MONTH_START}' == '10' -->selected="selected"<!-- END IF -->>${STR_MONTH_OCT}</option>
                        <option value="11" <!-- IF '${MONTH_START}' == '11' -->selected="selected"<!-- END IF -->>${STR_MONTH_NOV}</option>
                        <option value="12" <!-- IF '${MONTH_START}' == '12' -->selected="selected"<!-- END IF -->>${STR_MONTH_DEC}</option>
                      </select>
					  </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="education_year_start[]">${STR_EDUCATION_YEAR_START}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">
                      <select class="form-control form-primary" name="education_year_start[]">
                        <option value="">${STR_NO}</option>
                        <!-- BEGIN EDUCATION_ROW_OPTION_YEARS_LIST_START -->
                        <option <!-- IF '${YEAR}' == '${EDUCATION_YEAR_START}' -->selected="selected"<!-- END IF --> value="${YEAR}">${YEAR}</option>
                        <!-- END EDUCATION_ROW_OPTION_YEARS_LIST_START -->
                      </select>
					  </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="education_month_finish[]">${STR_EDUCATION_MONTH_FINISH}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">
                      <select class="form-control form-primary" name="education_month_finish[]">
                        <option value="">${STR_NO}</option>
                        <option value="1" <!-- IF '${MONTH_FINISH}' == '1' -->selected="selected"<!-- END IF -->>${STR_MONTH_JAN}</option>
                        <option value="2" <!-- IF '${MONTH_FINISH}' == '2' -->selected="selected"<!-- END IF -->>${STR_MONTH_FEB}</option>
                        <option value="3" <!-- IF '${MONTH_FINISH}' == '3' -->selected="selected"<!-- END IF -->>${STR_MONTH_MARCH}</option>
                        <option value="4" <!-- IF '${MONTH_FINISH}' == '4' -->selected="selected"<!-- END IF -->>${STR_MONTH_APR}</option>
                        <option value="5" <!-- IF '${MONTH_FINISH}' == '5' -->selected="selected"<!-- END IF -->>${STR_MONTH_MAY}</option>
                        <option value="6" <!-- IF '${MONTH_FINISH}' == '6' -->selected="selected"<!-- END IF -->>${STR_MONTH_JUN}</option>
                        <option value="7" <!-- IF '${MONTH_FINISH}' == '7' -->selected="selected"<!-- END IF -->>${STR_MONTH_JUL}</option>
                        <option value="8" <!-- IF '${MONTH_FINISH}' == '8' -->selected="selected"<!-- END IF -->>${STR_MONTH_AUG}</option>
                        <option value="9" <!-- IF '${MONTH_FINISH}' == '9' -->selected="selected"<!-- END IF -->>${STR_MONTH_SEP}</option>
                        <option value="10" <!-- IF '${MONTH_FINISH}' == '10' -->selected="selected"<!-- END IF -->>${STR_MONTH_OCT}</option>
                        <option value="11" <!-- IF '${MONTH_FINISH}' == '11' -->selected="selected"<!-- END IF -->>${STR_MONTH_NOV}</option>
                        <option value="12" <!-- IF '${MONTH_FINISH}' == '12' -->selected="selected"<!-- END IF -->>${STR_MONTH_DEC}</option>
                      </select>
					  </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="education_year_finish[]">${STR_EDUCATION_YEAR_FINISH}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">
                      <select class="form-control form-primary" name="education_year_finish[]">
                        <option value="">${STR_NO}</option>
                        <!-- BEGIN EDUCATION_ROW_OPTION_YEARS_LIST_FINISH -->
                        <option <!-- IF '${YEAR}' == '${EDUCATION_YEAR_FINISH}' -->selected="selected"<!-- END IF --> value="${YEAR}">${YEAR}</option>
                        <!-- END EDUCATION_ROW_OPTION_YEARS_LIST_FINISH -->
                      </select>
					  </div>
                    <input type="hidden" name="education_kind[]" value="1">
                    <span class="btn-form button-del minus pull-right">${BUTTON_REMOVE_EDUCATION}</span>
					</div>
                    </div>
                </div>
                <!-- END EDUCATION_ROW -->
                <span class="btn-form btn-primary plus pull-right">${BUTTON_ADD_NEW_EDUCATION}</span> </div>
            </div>




            <div id="job">
             <!-- <h3>${STR_JOB}</h3> -->
              <div class="addform_plus_job">
                <!-- BEGIN JOB_ROW -->
                <div class="job_form">
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="job_place[]">${STR_JOB_PLACE}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">

                      <input type="hidden" name = "id_job_place[]" class="id_place" data-type="job_${ID}" value="${ID_PLACE}"/>
					  <input type="hidden" name="id_job_block[]" value="${ID}"/>
                      <input autocomplete="off" class="form-control text-place" type="text" name="job_place[]" value="${PLACE}" data-type="job_${ID}">
                      <div class="select-place" data-type="job_${ID}"></div>

					  </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="job_name[]">${STR_JOB_NAME}</label>
                    <div class="col-lg-6">
                      <input autocomplete="off" class="form-control" type="text" name="job_name[]" value="${NAME}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="job_description[]">${STR_JOB_DESCRIPTION}</label>
                    <div class="col-lg-6">
                      <input autocomplete="off" class="form-control" type="text" name="job_description[]" value="${DESCRIPTION}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="job_month_start[]">${STR_JOB_MONTH_START}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">
                      <select class="form-control form-primary" name="job_month_start[]">
                        <option value="">${STR_NO}</option>
                        <option value="1" <!-- IF '${MONTH_START}' == '1' -->selected="selected"<!-- END IF -->>${STR_MONTH_JAN}</option>
                        <option value="2" <!-- IF '${MONTH_START}' == '2' -->selected="selected"<!-- END IF -->>${STR_MONTH_FEB}</option>
                        <option value="3" <!-- IF '${MONTH_START}' == '3' -->selected="selected"<!-- END IF -->>${STR_MONTH_MARCH}</option>
                        <option value="4" <!-- IF '${MONTH_START}' == '4' -->selected="selected"<!-- END IF -->>${STR_MONTH_APR}</option>
                        <option value="5" <!-- IF '${MONTH_START}' == '5' -->selected="selected"<!-- END IF -->>${STR_MONTH_MAY}</option>
                        <option value="6" <!-- IF '${MONTH_START}' == '6' -->selected="selected"<!-- END IF -->>${STR_MONTH_JUN}</option>
                        <option value="7" <!-- IF '${MONTH_START}' == '7' -->selected="selected"<!-- END IF -->>${STR_MONTH_JUL}</option>
                        <option value="8" <!-- IF '${MONTH_START}' == '8' -->selected="selected"<!-- END IF -->>${STR_MONTH_AUG}</option>
                        <option value="9" <!-- IF '${MONTH_START}' == '9' -->selected="selected"<!-- END IF -->>${STR_MONTH_SEP}</option>
                        <option value="10" <!-- IF '${MONTH_START}' == '10' -->selected="selected"<!-- END IF -->>${STR_MONTH_OCT}</option>
                        <option value="11" <!-- IF '${MONTH_START}' == '11' -->selected="selected"<!-- END IF -->>${STR_MONTH_NOV}</option>
                        <option value="12" <!-- IF '${MONTH_START}' == '12' -->selected="selected"<!-- END IF -->>${STR_MONTH_DEC}</option>
                      </select>
					  </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="job_year_start[]">${STR_JOB_YEAR_START}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">
                      <select class="form-control form-primsary" name="job_year_start[]">
                        <option value="">${STR_NO}</option>
                        <!-- BEGIN JOB_ROW_OPTION_YEARS_LIST_START -->
                        <option <!-- IF '${YEAR}' == '${JOB_YEAR_START}' -->selected="selected"<!-- END IF -->value="${YEAR}">${YEAR}</option>
                        <!-- END JOB_ROW_OPTION_YEARS_LIST_START -->
                      </select>
					  </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="job_month_finish[]">${STR_JOB_MONTH_FINISH}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">
                      <select class="form-control form-primary" name="job_month_finish[]">
                        <option value="">${STR_NO}</option>
                        <option value="1" <!-- IF '${MONTH_FINISH}' == '1' -->selected="selected"<!-- END IF -->>${STR_MONTH_JAN}</option>
                        <option value="2" <!-- IF '${MONTH_FINISH}' == '2' -->selected="selected"<!-- END IF -->>${STR_MONTH_FEB}</option>
                        <option value="3" <!-- IF '${MONTH_FINISH}' == '3' -->selected="selected"<!-- END IF -->>${STR_MONTH_MARCH}</option>
                        <option value="4" <!-- IF '${MONTH_FINISH}' == '4' -->selected="selected"<!-- END IF -->>${STR_MONTH_APR}</option>
                        <option value="5" <!-- IF '${MONTH_FINISH}' == '5' -->selected="selected"<!-- END IF -->>${STR_MONTH_MAY}</option>
                        <option value="6" <!-- IF '${MONTH_FINISH}' == '6' -->selected="selected"<!-- END IF -->>${STR_MONTH_JUN}</option>
                        <option value="7" <!-- IF '${MONTH_FINISH}' == '7' -->selected="selected"<!-- END IF -->>${STR_MONTH_JUL}</option>
                        <option value="8" <!-- IF '${MONTH_FINISH}' == '8' -->selected="selected"<!-- END IF -->>${STR_MONTH_AUG}</option>
                        <option value="9" <!-- IF '${MONTH_FINISH}' == '9' -->selected="selected"<!-- END IF -->>${STR_MONTH_SEP}</option>
                        <option value="10" <!-- IF '${MONTH_FINISH}' == '10' -->selected="selected"<!-- END IF -->>${STR_MONTH_OCT}</option>
                        <option value="11" <!-- IF '${MONTH_FINISH}' == '11' -->selected="selected"<!-- END IF -->>${STR_MONTH_NOV}</option>
                        <option value="12" <!-- IF '${MONTH_FINISH}' == '12' -->selected="selected"<!-- END IF -->>${STR_MONTH_DEC}</option>
                      </select>
					  </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="job_year_finish[]">${STR_JOB_YEAR_FINISH}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">
                      <select class="form-control form-primary" name="job_year_finish[]">
                        <option value="">${STR_NO}</option>
                        <!-- BEGIN JOB_ROW_OPTION_YEARS_LIST_FINISH -->
                        <option <!-- IF '${YEAR}' == '${JOB_YEAR_FINISH}' -->selected="selected"<!-- END IF --> value="${YEAR}">${YEAR}</option>
                        <!-- END JOB_ROW_OPTION_YEARS_LIST_FINISH -->
                      </select>


					  </div>
					  <input type="hidden" name="job_kind[]" value="3">
                    <span class="btn-form button-del minus_job pull-right">${BUTTON_REMOVE_JOB}</span> 
                    </div>
                    </div>
                </div>
                <!-- END JOB_ROW -->
                <span class="btn-form btn-primary plus_job pull-right">${BUTTON_ADD_NEW_JOB}</span> </div>
            </div>



            <div id="achivments">
              <!--<h3>${STR_MY_SPORTS}</h3>-->
              <div class="addform_achivments_plus">
                <!-- BEGIN ACHIVMENTS_ROW -->
                <div class="sport_type_form">
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="sport_type[]">${STR_SPORT_TYPE}</label>
                    <div class="col-lg-6">
					  <div class="styled-select styled-select-4">
                      <input type="hidden" name = "id_sport_type[]" class="id_place" data-type="search_sport_${ID}"/>
					   <input type="hidden" name="id_sport_block[]" value="${ID}"/>
        				<input autocomplete="off" class="form-control text-place" type="text" value="${SPORT_TYPE}" name="sport_type[]" data-type="search_sport_${ID}">
        				<div class="select-place" data-type="search_sport_${ID}"></div>
					  </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label" for="sport_level[]">${STR_LEVEL}</label>
                    <div class="col-lg-6">
					 <div class="styled-select styled-select-4">
                      <select class="form-control form-primary" name="spoort_level[]">
                        <option value="">${STR_NO}</option>
                        <!-- BEGIN ACHIVMENTS_OPTION_SPORT_LEVEL -->
                        <option value="${ID_LEVEL}" <!-- IF '${ID_LEVEL}' == '${ACHIVMENTS_ID_LEVEL}' -->selected="selected"<!-- END IF -->>${LEVEL_NAME}</option>
                        <!-- END ACHIVMENTS_OPTION_SPORT_LEVEL -->
                      </select>
					  </div>
                    </div>
                  </div>
                  <div class="form-group">
                    
                    <div class="col-lg-6">
                      <div class="checkbox">
					  <input id="checkbox-find-comand" type="checkbox" hidden="" <!-- IF '${SEARCH_TEAM}' == '1' -->checked="checked"<!-- END IF --> name="search_team[]">
                        <label for="checkbox-find-comand">                   
                          </label>
                      </div>
					<label class="col-lg-3 control-label" for="search_team[]">${STR_SEARCH_TEAM}</label>

                    <span class="btn-form button-del minus_sport_type pull-right">${BUTTON_REMOVE_SPORT_TYPE}</span>
					</div>
					</div>
                </div>
                <!-- END ACHIVMENTS_ROW -->
				
              </div>
			   <span class="btn-form btn-primary plus_sport_type pull-right">${BUTTON_ADD_SPORT_TYPE}</span>
            	<div class='sport_block'>
	            	<div class="sport_type_form">
		              <div class="form-group">
		                <label class="col-lg-3 control-label" for="about_sport">${STR_ABOUT_SPORT}</label>
		                <div class="col-lg-6">
		                  <textarea autocomplete="off" class="form-control form-dark" name="about_sport" rows="4">${ABOUT_SPORT}</textarea>
		                </div>
		              </div>
					 </div>
				</div>
            </div>
          </div>
          <div class="profile-settings button">
          	<input type="hidden" name="file_ava" id='file_ava_src' value=''>
              <input type="hidden" name="file_cover" id='file_cover_src' value=''>
            <button class="save-button" id = 'save_profile' value="${SAVE_CHANGES}" name="action">Сохранить<span> изменения</span></button>
            <button class="next-button" id = 'next-step' value="${SAVE_CHANGES}" name="action">Далее</button>
          </div>
          </div>
        </form>
        <!--End content-->


        <!-- INCLUDE right_sitebar.tpl -->

      </div>
    </div>
  </div>
</section>
<script src="./templates/js/select2.min.js"></script>
<script src="./templates/js/edit_profile.js"></script>
<script src="./templates/js/search.js"></script>
<!--END CONTENT-->
<!-- INCLUDE footer.tpl -->