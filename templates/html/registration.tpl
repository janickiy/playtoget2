<!-- INCLUDE defaultheader.tpl -->
  <script type="text/javascript" src='./templates/js/main_page.js'></script>

<body class="index-registration-body rel" >

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
					<div class="col-xs-12 form-frame">
						<div class="form-container">
		
							<!-- IF '${MSG_ALERT}' == '' -->
						
							<!-- BEGIN show_errors -->
								<div class="error_msg">
									<h4 class="alert-heading">${STR_IDENTIFIED_FOLLOWING_ERRORS}:</h4>
									<ul>
										<!-- BEGIN row -->
										<li> ${ERROR}</li>
										<!-- END row -->
									</ul>
								</div>
							<!-- END show_errors -->							
							
							<!-- IF '${ERROR_ALERT}' != '' -->
								<div class="error_msg">
								<strong>${STR_ERROR}:</strong> ${ERROR_ALERT}
								</div>	
							<!-- END IF -->								
								
							<form autocomplete="off" name="enter-form" action="${ACTION}" method="POST" id="entrance-form">
								<h3>${TITLE_PAGE}</h3>				
								<input type="text" name="email" value="${EMAIL}" placeholder="${STR_EMAIL_REGISTRATION_FORM}*" id="input-mail" request>
								<input type="text" placeholder="${STR_FIRSTNAME}*" value="${FIRSTNAME}" name="firstname" id="input-name">
								<input type="text" placeholder="${STR_LASTNAME}*" value="${LASTNAME}" name="lastname" id="input-surname">
								<!-- <input type="text" name="nikname" placeholder="ник" id="input-nikname"> -->
								<input type="text" name="secondname" placeholder="${STR_SECONDNAME}" value="${SECONDNAME}" id="input-secondname"> 
								<input type="password" name="password" placeholder="${STR_PASSWORD}*" id="input-password" request>
								<input type="password" name="confirm_password" placeholder="${STR_CONFIRM_PASSWORD}*" id="input-s-password" request>
								<input type="checkbox" name="use_terms" <!-- IF '${USE_TERMS}' != '' -->checked="checked"<!-- END IF --> id="input-checkbox" hidden>
								<label for="input-checkbox"><div class="confirm-block"><a href="./?task=content&id_content=5" class="form-enter-link_pass">${STR_USE_TERMS}</a> ${STR_ACCEPT_AGREEMENT_FORM} *</div></label>
								<input type="submit" value="${BUTTON_SIGN_UP}" name="action" id="input-submit">
							</form>
							<div class="social">
								* ${STR_REQUIRED_FIELDS}
							</div>
							
							<!-- ELSE -->
								<p><span class="alert_msg">${MSG_ALERT}</span></p>
								<!--<p>Начните с приглашения друзей из адресной книги.
								Для этого проверьте Ваш E-mail и введите пароль от почтового ящика</p>
								<form autocomplete="off" class="form-horizontal" method="POST" action=""W>
									<div class="form-group">
									    <div class="col-lg-12">
									      <input class="form-control" type="email" value="${EMAIL}" name="email" placeholder="E-mail">
									    </div>
									</div><br>
									<div class="form-group">
									    <div class="col-lg-12">
									      <input class="form-control" type="password" name="email" placeholder="Пароль">
									    </div>
									</div>
								    <div class="form-group" style='text-align:center'>
								    	<input class="btn-form save-button" type="submit" value="Пригласить друзей">
								    </div>
							  </form>-->
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