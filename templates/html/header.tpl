<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<meta http-equiv="Cache-Control" content="no-cache">
	<title>${TITLE_PAGE}</title>	
	<!-- BEGIN row_css_list -->
	${CSS}
	<!-- END row_css_list -->	
	<!-- BEGIN row_js_list -->
	${JS}
	<!-- END row_js_list -->	
	<script src="./templates/js/jquery-migrate-3.5.2.min.js?v=20260602-2"></script>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="./templates/css/jquery.confirm.css" />
<link href="./favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, , user-scalable=no">
	<meta name="google-site-verification" content="5tjvO2p9ZsLKTKxE2YMfTbyDbImEK9y2M1o-vb_a92Y" />
	<meta property="og:image" content="./templates/images/left-sitebar-img-2.png">
	<!-- IF '${META_DESCRIPTION}' != '' --><meta name="description" content="${META_DESCRIPTION}" /><!-- ELSE -->Мы первый спортивный интернет-ресурс, объединивший: приверженцев здорового образа жизни, любителей спорта и профессиональных спортсменов.<!-- END IF -->
	<!-- IF '${META_KEYWORDS}' != '' --><meta name="keywords" content="${META_KEYWORDS}" /><!-- END IF -->
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
	<script>
		window.user = '${ID_USER}';
	</script>
	<script type="text/javascript" src='./templates/js/header.js?v=20260602-3'></script>
<!-- show/hidden -->
<script src='./templates/js/show-hidden.js'></script>

</head>
<body>
<div id="tooltip"></div>

	
<div class='window-message'>
</div>
<div class="main">
<section class="wrapper header">
	<div class="container-fluid">
		<div class="row">
			<div class="top-header">
				<div class="col-xs-12">
					<div class="left-top-header">
						<div class="logo">
							<a href="./"><img src="./templates/images/top-logo.png" alt=""></a>
						</div>
						<div class="search">
							<form autocomplete="off" action="" method="GET">
							<input type="hidden" name="task" value="search">
							<input type="hidden" name="q" value="all_search">
							<input  type="text" name="search" id = 'main_search' value="${SEARCH}" placeholder="${STR_SEARCH}" <!-- IF '${SEARCH}' != '' --> class='white'<!-- END IF -->>
							</form>
						</div>
					</div>
					<div class="profile-user">
						<a href=""><div class="mini_thumb_avatar "><img width="50px" height="50px" border="0" src="${TOP_AVATAR}" alt=""></div></a>
						<a href="./?task=profile&user_id=${ID_USER}">${TOP_FIRSTNAME}<br>${TOP_LASTNAME}</a>
					</div>
					<a class="menu-icon" href="#go-nav"></a>
					<div class="top-header-menu ">
						<ul> 
							<li><a href=""><div class="mini_thumb_avatar"><img width="50px" height="50px" border="0" src="${TOP_AVATAR}" alt=""></div></a><a href="./?task=profile&user_id=${ID_USER}">${TOP_FIRSTNAME}<span></span>${TOP_LASTNAME}<span></span></a></li>
							<li><a href="./"><img src="./templates/images/menu-home.png" alt=""></a></li>
							<li><a href="./?task=profile&user_id=${ID_USER}&q=dialogues"><img src="./templates/images/message.png" alt=""></a> 
								<!-- IF '${NUMBERMESSAGE}' != '0' -->
									<span id='message_count'>${NUMBERMESSAGE}</span>
								<!-- ELSE -->
									<span id='message_count' class='displayNone'>${NUMBERMESSAGE}</span>
								<!-- END IF -->
							</li>
							<li><a href="./?task=friends"><img src="./templates/images/man.png" alt=""></a><!-- IF '${NUMBERINVITATION}' != '0' --><span>${NUMBERINVITATION}</span><!-- END IF --></li>
							<li><a href="./?task=settings"><img src="./templates/images/settings.png" alt=""></a></li>
							<li><a href="./?task=logout"><b>X</b> ${STR_LOGOUT}</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="wrapper mnu">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<ul class="menu">
					<li>
						<a><img src="./templates/images/Profile.png" alt=""></a>
							<span class="for-submenu">
								<a>${MENU_PROFILE}</a>
								<ul class="top-mnu-submenu">
									<li><a href="./?task=profile&user_id=${ID_USER}">${MENU_PROFILE}</a></li>
									<li><a href="./?task=edit_profile">${MENU_EDITPROFILE}</a></li>
								</ul>
							</span>
					</li>
					<li><a href="./"><img src="./templates/images/news.png" alt=""></a><span class="for-submenu"><a title="${MENU_NEWS}" href="./?task=news">${MENU_NEWS}</a></span></li>
					<li><a href="./?task=friends"><img src="./templates/images/friends.png" alt=""></a><span class="for-submenu"><a title="${MENU_FRIENDS}" href="./?task=friends">${MENU_FRIENDS}</a></a></li>
					<li>
						<a><img src="./templates/images/Share.png" alt=""></a>
						<span class="for-submenu">
							<a>${MENU_SHARE}</a>
							<ul class="top-mnu-submenu">
								<li><a href="./?task=photoalbums">${MENU_PHOTOALBUMS}</a></li>
								<li><a href="./?task=videoalbums">${MENU_VIDEOALBUMS}</a></li>
							</ul>
						</span>
					</li>
					<li><a href="./?task=teams"><img src="./templates/images/command.png" alt=""></a><span class="for-submenu"><a title="${MENU_TEAMS}" href="./?task=teams">${MENU_TEAMS}</a></span><!-- IF '${NUMBER_INVITED_ME_TEAM}' > '0' --><span class="i-sup">${NUMBER_INVITED_ME_TEAM}</span><!-- END IF --></li>
					<li class='menu_groups_hide'><a href="./?task=groups"><img src="./templates/images/Group.png" alt=""></a><span class="for-submenu"><a title="${MENU_GROUPS}" href="./?task=groups">${MENU_GROUPS}</a></span><!-- IF '${NUMBER_INVITED_ME_GROUP}' > '0' --><span class="i-sup">${NUMBER_INVITED_ME_GROUP}</span><!-- END IF --></li>
					<li class='menu_groups'>
						<a><img src="./templates/images/Group.png" alt=""></a>
						<span class="for-submenu">
							<a title="${MENU_GROUPS}" href="./?task=groups">${MENU_GROUPS}</a>
							<ul class="top-mnu-submenu">
								<li><a href="./?task=groups">${MENU_GROUPS}</a></li>
								<li><a href="./?task=playgrounds">${MENU_PLAYGROUNDS}</a></li>
								<li><a href="./?task=shops">${MENU_SHOPS}</a></li>
								<li><a href="./?task=fitness">${MENU_FITNESS}</a></li>
							</ul>

						</span>
						<!-- IF '${NUMBER_INVITED_ME_GROUP}' > '0' --><span class="i-sup">${NUMBER_INVITED_ME_GROUP}</span><!-- END IF -->
					</li>
					<li><a href="./?task=events"><img src="./templates/images/Events.png" alt=""></a><span class="for-submenu"><a title="${MENU_EVENTS}" href="./?task=events">${MENU_EVENTS}</a></span><!-- IF '${NUMBER_INVITED_ME}' > '0' --><span class="i-sup">${NUMBER_INVITED_ME}</span><!-- END IF --></li>
					<li><a href="./?task=calendar"><img src="./templates/images/Calendar.png" alt=""></a><span class="for-submenu"><a title="${MENU_CALENDAR}" href="./?task=calendar">${MENU_CALENDAR}</a></span></li>
				</ul>
			</div>
		</div>
	</div>
</section>

<section class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="baner">
					<!-- <img src="./templates/images/baner.png" alt=""> -->
				</div>
			</div>
		</div>
	</div>
</section>
