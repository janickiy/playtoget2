<!-- INCLUDE header.tpl -->

<!--START CONTENT-->

<!-- IF '${ERROR_ALERT}' != '' --><div class='save_window_ok hiden'>${ERROR_ALERT}</div><!-- END IF -->
<!-- IF '${MSG_ALERT}' != '' --><div class='save_window_fail hiden'>${MSG_ALERT}</div><!-- END IF -->


	<section class="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-xs-12  bg">
					
 <!-- INCLUDE left_sitebar.tpl -->

					<div class="content friends">
						<!-- INCLUDE top_user_profile.tpl -->

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
					
					<!-- IF '${MSG_ALERT}' != '' -->
					<div class="alert alert-success">
					<button class="close" data-dismiss="alert">×</button>
					${MSG_ALERT}</div>
					<!-- END IF -->
						


						<div id='calendar'></div>		

						
					</div><!--End content-->

						<!-- INCLUDE right_sitebar.tpl -->

				</div>
			</div>
		</div>
	</section>

	<!--END CONTENT-->

<!-- INCLUDE footer.tpl -->