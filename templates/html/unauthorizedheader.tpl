<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Cache-Control" content="no-cache">
	<title>${TITLE_PAGE}</title>	
	<!-- BEGIN row_css_list2 -->
	${CSS}
	<!-- END row_css_list2 -->	
	<!-- BEGIN row_js_list2 -->
	${JS}
	<!-- END row_js_list2 -->	
	<script src="./templates/js/jquery-migrate-3.5.2.min.js?v=20260602-2"></script>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="./templates/css/jquery.confirm.css" />
<link href="./favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, , user-scalable=no">
	<meta name="google-site-verification" content="5tjvO2p9ZsLKTKxE2YMfTbyDbImEK9y2M1o-vb_a92Y" />
  	<meta name="description" content="${DESCRIPTION}" >
	<meta property="og:image" content="./templates/images/left-sitebar-img-2.png">
	<!--Responsive-->
	<link href="./templates/css/responsive.css" rel="stylesheet" type="text/css">
	<link href="./templates/css/max-width-1440.css" rel="stylesheet" media="(max-width: 1440px)">
	<link href="./templates/css/max-width-1190.css" rel="stylesheet" media="(max-width: 1190px)">
	<link href="./templates/css/max-width-960.css" rel="stylesheet" media="(max-width: 960px)">
	<link href="./templates/css/max-width-768.css" rel="stylesheet" media="(max-width: 768px)">
	<link href="./templates/css/max-width-640.css" rel="stylesheet" media="(max-width: 640px)">
	<link href="./templates/css/max-width-480.css" rel="stylesheet" media="(max-width: 480px)">
	<link href="./templates/css/max-width-390.css" rel="stylesheet" media="(max-width: 390px)">
	<link rel="stylesheet" href="./templates/css/emotions.css">
	<link rel="stylesheet" href="./templates/css/jquery.emotions.fb.css">


<!-- show/hidden -->
<script src='./templates/js/show-hidden.js'></script>
</head>
<body>
<div id="tooltip"></div>

	
<div class='window-message'>
</div>
<div class="main paddingTop70">
<section class="wrapper header">
	<div class="container-fluid">
		<div class="row">
			<div class="top-header">
				<div class="col-xs-12">
					<div class="left-top-header">
						<div class="logo">
							<a href="./"><img src="./templates/images/top-logo.png" alt=""></a>
						</div>
					</div>
					<a class="menu-icon" href="#go-nav"></a>
					<form action='./' autocomplete="off" name="enter-form" method="POST" id='entrance'>
					<div class="top-header-menu ">
						<ul> 
							<li></li>
							<li>
								<input type='text' name="username" placeholder='E-mail'/>
								<input type='password' name="password" placeholder='Пароль'/>
							</li>
							<li><input type='submit' name='login' value='Войти'/></li>
							<li><a href="./?task=registration">Регистрация</a></li>
						</ul>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
