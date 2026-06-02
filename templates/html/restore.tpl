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
			<div class="container main-entrance main-registration main-password">
				<div class="row">
					<div class="col-xs-12">
						<div class="form-container">
							<!-- IF '${RESET_PASSWORD_TOKEN}' != '' -->
								<!-- IF '${MSG_ALERT}' == '' -->
								<form autocomplete="off" name="enter-form" action="${ACTION}" method="POST" id="entrance-form">
								<input type="hidden" name="action" value="reset_password">
								<h3>${STR_CHANGE_PASSWORD}</h3>
								<!-- IF '${ERROR_ALERT}' != '' --><div class="error_msg"><strong>${STR_ERROR}:</strong> ${ERROR_ALERT}</div><!-- END IF -->
								<!-- BEGIN show_errors -->
								<div class="error_msg">
								<h4 class="alert-heading">${STR_IDENTIFIED_FOLLOWING_ERRORS}:</h4>
									<ul>
										<!-- BEGIN row -->
										<li>${ERROR}</li>
										<!-- END row -->
									</ul>
								</div>	
								<!-- END show_errors --> 							
								<input class="form-control" placeholder="${STR_PASSWORD_FORM}" type="password" value="${PASSWORD}" name="password" id="input-password" request>
								<input class="form-control" placeholder="${STR_CONFIRM_PASSWORD_FORM}" type="password" value="${AGAIN_PASSWORD}" name="again_password" id="input-s-password" request>
								<input type="submit" value="${BUTTON_CHANGE}" id="input-submit">
								</form>							
							
								<!-- ELSE -->
								<span class="alert_msg">${MSG_ALERT}<p><br /></p><p><br /></p></span>
								<!-- END IF -->	
							
							<!-- ELSE -->
							
							<!-- IF '${MSG_ALERT}' == '' -->
							<form autocomplete="off" name="enter-form" action="${ACTION}" method="POST" id="entrance-form">
								<input type="hidden" name="action" value="send_restore_link">
								<h3>${TITLE_PAGE}</h3>
								<p>${STR_INSTRUCTION_RESTORE}</p>
								<input type="text" name="email" value="${EMAIL}" placeholder="${STR_EMAIL}*" id="input-mail" request>
								<input type="submit" value="${BUTTON_SEND}" id="input-submit">
												
							</form>	
							<div class="social">
								* ${STR_REQUIRED_FIELDS}
							</div>							
							
							<!-- ELSE -->
							<span class="alert_msg">${MSG_ALERT}<p><br /></p><p><br /></p></span>
							<!-- END IF -->	
							
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