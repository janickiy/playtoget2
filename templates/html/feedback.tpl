<!-- INCLUDE defaultheader.tpl -->
<script type="text/javascript" src='./templates/js/content.js'></script>
<body class="content_bg">

<div class='save_window_ok hiden'></div>
<div class='save_window_fail hiden'></div>
		<section class="section-for-header content_margin">
			<div class="container header-entrance">
				<div class="row">
					<div class="col-xs-12">
						<a href="./" class="entrance-logo"><img borde="0" src="./templates/images/logo-main.png" alt="logo"></a>
					</div>
				</div>
			</div>
		</section>

		<div class="photo-caption"></div>
<div class='bg bg_feed'>
<div class="content content-groups friends content_left">	
				
<div class="job_form">
	<div class="photo-caption">					
	<h3>${TITLE_PAGE}</h3>
	</div>			
<form method="POST" class="form-horizontal" id='feedback-form'>
<div class="form-group">
    <label class="control-label col-lg-3" for="subject">Тема:</label>
    <div class="col-lg-6">
      <input type="text" class="form-control" name="subject" id="subject" placeholder="Введите тему сообщения">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-lg-3" for="name">Ваше имя:</label>
    <div class="col-lg-6">
      <input type="text" class="form-control" name="name" id="name" placeholder="Укажите ваше имя">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3" for="email">Адрес электронной почты:</label>
    <div class="col-lg-6">
      <input type="text" class="form-control" id="email" name="email" placeholder="Введите Ваш адрес электронной почты">
    </div>
  </div>


  <div class="form-group">
    <label class="control-label col-lg-3" for="message">Ваше сообщение:</label>
    <div class="col-lg-6">
      <textarea rows="3" class="form-control" id="message" name="message" placeholder="Введите сообщение"></textarea>
    </div>
  </div>
  
  <div class="form-group">
  
  
  
  
    <label class="control-label col-lg-3" for="securitycode">Проверочный код:</label>
    <label class="control-label col-lg-6 left-text" for="securitycode">
    	<a href="#" onclick="document.getElementById('captcha').src='captcha.php?'+Math.random();
    						document.getElementById('captcha-form').focus();"
    		id="change-image">Не можете прочитать? Изменить текст.</a><br/><br/>
    <img src="captcha.php" alt="защитный код" id="captcha" /></label>
    <div class="col-lg-6">
      <input type="text" class="form-control" name="captcha" id="securitycode" placeholder="Введите защитный код">
    </div>
  </div>
  
  
  
  
  
  
  <br />
  <div class="form-group">
    <div class="col-xs-offset-3 col-lg-6">
      <input type="submit" class="btn-form save-button" value="Отправить">
    </div>
  </div>
</form>
						
						
						
					</div>
</div>
</div>


<!-- INCLUDE footer.tpl -->