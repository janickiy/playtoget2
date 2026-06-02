<!-- INCLUDE defaultheader.tpl -->
  <script type="text/javascript" src='./templates/js/main_page.js'></script>

<body class="index-registration-body rel">

<div class="wrapper-backgrounded">
	<div class="wrapper-transparent">
		<section class="section-for-header">
			<div class="container header-entrance">
				<div class="row">
					<div class="col-xs-12">
						<a href="./" class="entrance-logo"><img border="0" src="./templates/images/logo-main.png" alt="logo"></a>
					</div>
				</div>
			</div>
		</section>

		<section class="section-for-main-entrance main_top">
			<div class="container main-entrance main-registration">
				<div class="row">
					<div class="col-xs-12">
						<div class="form-container">
						
							<h3>${STR_CONFIRMATION_OF_REGISTRATION}</h3>	
								
							<!-- IF '${MSG_ALERT}' != '' -->
							<span class="alert_msg">${MSG_ALERT}<br><a class='button_success' href="./?task=edit_profile">${STR_GO_TO_SITE}</a></span>
							
							<!-- END IF -->		
	
							<!-- IF '${ERROR_ALERT}' != '' -->
							<div class="error_msg">
							<strong>${STR_ERROR}:</strong> ${ERROR_ALERT} 
							</div>
							<!-- END IF -->	
							
						</div>
					</div>
				</div>
			</div>
		</section>

		
	</div>
</div>

<div class='footer'>
<!-- INCLUDE footer.tpl -->
</div>