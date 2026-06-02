<div class="left-sitebar">
          <ul>
            <li> <img src="./templates/images/left-sitebar.png" alt=""> <a href="#">${STR_RECOMMENDS}</a>
              <!-- INCLUDE s_recommend.tpl -->
            </li>
            
            <!-- IF '${ID_SPORT_BLOCK}' != '' -->
             <li class="ads"> <a href="#"> Местоположение<!--<img src="./templates/images/arrow.png" alt="">--></a>
              <!-- INCLUDE s_maps.tpl -->
            <!-- END IF -->
            <!-- IF '${ID_EVENT}' != '' -->
             <li class="ads"> <a href="#"> Местоположение<!--<img src="./templates/images/arrow.png" alt="">--></a>
              <!-- INCLUDE s_maps.tpl -->
            <!-- END IF -->
            <li class="ads">
              <a href="./?task=events">${STR_EVENTS}<span>${NUMBEREVENT}</span></a>
              <!-- INCLUDE s_events.tpl -->
            </li>
   <!--         <li> <a href="#">${STR_REASON_TO_CONGRATULATE} <img src="./templates/images/arrow.png" alt=""></a>
              INCLUDE s_reason_congratulate.tpl
            </li>-->
            <li class="ads"> <a href="#">${STR_ADS}<!--<img src="./templates/images/arrow.png" alt="">--></a>
              <!-- INCLUDE s_advertisement.tpl -->
            </li>


            

            <!-- <li> <a href="">${STR_RECOMMEND}<img src="./templates/images/arrow.png" alt=""></a>
              INCLUDE s_recommended.tpl 
            </li>-->
			 <!--<li class="ads"> <a href="#"> ${STR_SPORT_NEWS} Новости спорта</a>-->
              <!-- INCLUDE s_news.tpl -->
           <!-- </li>-->

          </ul>
        </div>