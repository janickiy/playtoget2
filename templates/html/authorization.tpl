<!-- INCLUDE defaultheader.tpl -->
  <script type="text/javascript" src='./templates/js/main_page.js'></script>

<body class="index-registration-body rel">
<div class="wrapper-backgrounded">
	<div class="wrapper-transparent">

		<section class="section-for-main-entrance main_top">
			<div class="container main-entrance">
				<div class="row">
					<div class="col-md-7 section-sport-inside margin_none">

						<div class="container header-entrance margin_none">
							<div class="row">
								<div class="col-xs-12">
									<a href="./" class="entrance-logo"><img borde="0" src="./templates/images/logo-main.png" alt="playtoget-logo"></a>

									<div class="col-md-6 col-md-offset-3">
										<hr>
									</div>
									<div class='col-md-8 col-md-offset-2'>
										<h1>Спортивный интернет-проект</h1>
										<p class='desc'>Мы первый спортивный интернет-ресурс, объединивший:<br>
										приверженцев здорового образа жизни, любителей спорта и профессиональных спортсменов</p>
									</div>
									<div class='col-md-12 cols'>
										<div class='col-md-3'>
											<img borde="0" src="./templates/images/teams.png" alt="">
											<h3>создавай и находи команды</h3>
											<p>Устраивай соревнования, ищи противников и приглашай болельщиков</p>
										</div>
										<div class='col-md-3'>
											<img borde="0" src="./templates/images/child.png" alt="">
											<h3>принимай участие</h3>
											<p>Следи за спортивными мероприятиями твоего города вместе с друзьями</p>
										</div>
										<div class='col-md-3'>
											<img borde="0" src="./templates/images/master.png" alt="">
											<h3>повышай мастерство</h3>
											<p>Находи наставников, получай советы и делись опытом </p>
										</div>
										<div class='col-md-3'>
											<img borde="0" src="./templates/images/kurs.png" alt="">
											<h3>будь в курсе</h3>
											<p>Общайся с единомышленниками, получай фото- и видеорепортажи</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--<p>${STR_WE_ARE_PEOPLE}</p>-->
					</div>
					<div class="col-md-5 form-frame">

						<div class="form-container">
							<p class="sport-inside">${STR_SPORT_INSIDE}</p>
							<p>Не ограничивай себя. Зарегистрируйся и получи полный доступ ко всем возможностям сайта.</p>
							<form autocomplete="off" name="enter-form" method="POST" id="entrance-form">
								<h3>${STR_ENTER_TO_SITE}</h3>
								<!-- IF '${ERROR_ALERT}' != '' --><div class="alert_msg"><p><strong>${STR_ERROR}! </strong>${ERROR_ALERT}</p></div><!-- END IF -->
								<input type="email" name="username" value="${USERNAME}" placeholder="email" id="input-login" autocomplete="off" readonly onfocus="this.removeAttribute('readonly')">
								<input type="password" name="password" placeholder="${STR_PASSWORD}" id="input-password" autocomplete="off"  readonly onfocus="this.removeAttribute('readonly')">
								<input type="checkbox" value="1" name="remember_me" <!-- IF '${REMEMBER_ME}' != '' -->checked="checked"<!-- END IF --> id="input-checkbox"  hidden><label for="input-checkbox">${STR_REMEMBER_ME}</label>
								<a href="./?task=restore" class="form-enter-link_pass">${STR_REMIND_PASSWORD}</a>
								<input type="submit" name="login" value="${BUTTON_LOGIN}" id="input-submit">
								<span>${STR_OR}</span>
								<a href="./?task=registration" class="form-enter-link_reg">${STR_SIGN_UP}</a>
							</form>
							<div class="social">
								<h4>${STR_SIGN_IN_VIA}</h4>
								<a href="${VK_AUT_LINK}" class="vk"></a>
								<a href="${FB_AUT_LINK}" class="fb"></a>
								<a href="${MR_AUT_LINK}" class="mail"></a>
								<a href="${YANDEX_AUT_LINK}" class="ya"></a>
								<a href="${OK_AUT_LINK}" class='ok'></a>
								<!--
								<a href="" class='gp'></a>
								

								<a href="" class='tw'></a>
								<a href="" class='ins'></a>
								<a href="" class='in'></a>-->
							</div>
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