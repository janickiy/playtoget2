<!-- IF '${OPEN_PAGE}' == '' -->
<!-- INCLUDE header.tpl -->
<!-- ELSE -->
<!-- INCLUDE unauthorizedheader.tpl -->
<!-- END IF -->
<section class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-xs-12  bg">
        <!-- INCLUDE left_sitebar.tpl -->
        <div class="content friends">

		  
		  <div class="photo-caption">
			<h3>Карта сайта</h3>
		  </div>
		  
          <ul id='sitemap'>
            <li><a href="./?task=friends">Друзья</a></li>
			      <li>
                <a href="./?task=groups">Группы<span id="cke_bm_150E" style="display: none;">&nbsp;</span></a>
			          <ul>          
      	           <!-- BEGIN row_groups_map -->
                   <li><a href="${URL}">${NAME}</a></li>
                   <!-- END row_groups_map -->
                </ul>
            </li>
            <li>
                <a href="./?task=teams">Команды</a>
                <ul>
                  <!-- BEGIN row_teams_map -->
                  <li><a href="${URL}">${NAME}</a></li>
                  <!-- END row_teams_map -->
                </ul>
            </li>
            <li>
                <a href="./?task=events">Мероприятия</a>
                <ul>
                  <!-- BEGIN row_events_map -->
                  <li><a href="${URL}">${NAME}</a></li>
                  <!-- END row_events_map -->
                </ul>
            </li>
            <li>
                <a href="./?task=playgrounds">Площадки</a>
                <ul>
                  <!-- BEGIN row_playgrounds_map -->
                  <li><a href="${URL}">${NAME}</a></li>
                  <!-- END row_playgrounds_map -->
                </ul>
            </li>
            <li>
                <a href="./?task=shops">Магазины</a>
                <ul>
                  <!-- BEGIN row_shops_map -->
                  <li><a href="${URL}">${NAME}</a></li>
                  <!-- END row_shops_map -->
                </ul>
            </li>
            <li>
                <a href="./?task=fitness">Фитнес</a>
                <ul>
                  <!-- BEGIN row_fitness_map -->
                  <li><a href="${URL}">${NAME}</a></li>
                  <!-- END row_fitness_map -->
                </ul>
            </li>
  		      <li><a href="./?task=calendar">Календарь</a></li>
          </ul>
        </div>
        <!--End content-->
        <!-- INCLUDE right_sitebar.tpl -->
      </div>
    </div>
  </div>
</section>
<!--END CONTENT-->
<!-- INCLUDE footer.tpl -->
