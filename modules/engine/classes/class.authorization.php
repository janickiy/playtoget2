<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Auth
{
	static function authorization()
	{
		if($_SESSION['user_authorization'] != "ok" && empty($_SESSION['id_user'])){
			if(self::LoginUser($_COOKIE['id_user'], $_COOKIE['token'], $_COOKIE['id_session'])){
				$_SESSION['user_authorization'] = "ok";
				$_SESSION['id_user'] = $_COOKIE['id_user'];
				
				$fields = array();
				$fields['id_user'] = $_COOKIE['id_user'];
				$fields['ip'] = core::documentparser()->getIP();
				$fields['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
				$fields['last_sign_in_at'] = date("Y-m-d H:i:s");
				
				self::setLog($fields);
			}
			else{
				self::Logout();
			}
		}
		else{
			if(!self::LoginUser($_COOKIE['id_user'], $_COOKIE['token'], $_COOKIE['id_session'])) self::Logout();
		}		
		
		if($_POST['login'] && ($_SESSION['user_authorization'] != "ok" and empty($_SESSION['id_user']))){
			
			$username = trim(core::database()->escape($_POST['username']));
			$password = trim($_POST['password']);
			
			if(!empty($username) && !empty($password)){
				
				$query = "SELECT `id`, `password`, `email`, `confirmed`, `banned` FROM " . core::database()->getTableName('users') . " WHERE email LIKE '" . $username . "'";
				$result = core::database()->querySQL($query);
			
				if(core::database()->getRecordCount($result) > 0){
					$row = core::database()->getRow($result);

					if($row['confirmed'] == 0){
						$alert_error = core::getLanguage('msg', 'unconfirmed');
					}
					else if($row['banned'] == 1){
						$alert_error = core::getLanguage('msg', 'locked_user');				
					}
					else if($row['password'] == md5(trim($_POST['password']))){
						self::Login($row['id'], $_POST['remember_me']);
					}
					else{
						$alert_error = core::getLanguage('msg', 'not_found_in_database');
					}	
				}
				else{
					$alert_error = core::getLanguage('msg', 'not_found_in_database');
				}
			}
		}		
			
		if($_SESSION['user_authorization'] != "ok"){

			core::requireEx('libs', "html_template/SeparateTemplate.php");
			$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . "authorization.tpl");				
			
			$tpl->assign('TITLE', core::getLanguage('title', 'authorization'));
			$tpl->assign('TITLE_PAGE', core::getLanguage('title', 'authorization'));
			
			if(!empty($alert_error)) {
				$tpl->assign('STR_ERROR', core::getLanguage('str', 'error'));
				$tpl->assign('ERROR_ALERT', $alert_error);				
			}
			
			$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE confirmed=1";
			$result = core::database()->querySQL($query);
			$total_user = core::database()->getRecordCount($result);

			if(isset($_POST['remember_me'])) 
				$remember_me = $_POST['remember_me'];
			else 
				$remember_me = 1;
			
			$adapterConfigs = array(
				'vk' => array(
					'client_id'     => CLIENT_VK_ID,
					'redirect_uri'  => REDIRECT_VK_URI,
					'response_type' => 'code',
					'client_secret' => CLIENT_VK_SECRET,
					'scope'			=> 'email'
				),
				'ok' => array(
					'client_id'    => CLIENT_OK_ID,
					'response_type' => 'code',
					'scope'         => 'GET_EMAIL',
					'redirect_uri'  => REDIRECT_OK_URI					
				),
				'mailru' => array(
					'client_id'     => CLIENT_MR_ID,
					'response_type' => 'code',
					'redirect_uri'  => REDIRECT_MR_URI
				),
				'yandex' => array(
					'response_type' => 'code',
					'client_id'     => CLIENT_YANDEX_ID,
					'display'       => 'popup'
				),
				'google' => array(
					'client_id'     => '',
					'client_secret' => '',
					'redirect_uri'  => 'http://localhost/auth?provider=google'
				),
				'facebook' => array(
					'client_id'     => CLIENT_FB_ID,
					'client_secret' => CLIENT_FB_SECRET,
					'redirect_uri'  => REDIRECT_FB_URI,
					'scope'			=> 'email,user_birthday'
				)
			);
	
			$tpl->assign('VK_AUT_LINK', 'http://oauth.vk.com/authorize?' . urldecode(http_build_query($adapterConfigs['vk'])));
			$tpl->assign('FB_AUT_LINK', 'https://www.facebook.com/dialog/oauth?' . urldecode(http_build_query($adapterConfigs['facebook'])));
			$tpl->assign('MR_AUT_LINK', 'https://connect.mail.ru/oauth/authorize?' . urldecode(http_build_query($adapterConfigs['mailru'])));	
			$tpl->assign('YANDEX_AUT_LINK', 'https://oauth.yandex.ru/authorize?' . urldecode(http_build_query($adapterConfigs['yandex'])));	
			$tpl->assign('OK_AUT_LINK', 'http://www.odnoklassniki.ru/oauth/authorize?' . urldecode(http_build_query($adapterConfigs['ok'])));	
			
			$tpl->assign('STR_LOGIN_FORM_TITLE', core::getLanguage('str', 'login_form_title'));
			$tpl->assign('STR_USERNAME', core::getLanguage('str', 'username'));
			$tpl->assign('STR_PASSWORD', core::getLanguage('str', 'password'));
			$tpl->assign('STR_LOGIN', core::getLanguage('str', 'login'));
			$tpl->assign('STR_ENTER_YOUR_LOGIN', core::getLanguage('str', 'enter_your_login'));
			$tpl->assign('STR_ENTER_PASSWORD', core::getLanguage('str', 'enter_password'));
			$tpl->assign('STR_FORGOT_PASSWORD', core::getLanguage('str', 'forgot_password'));
			$tpl->assign('STR_CREATE_ACCOUNT', core::getLanguage('str', 'create_account'));
			$tpl->assign('STR_REMEMBER_ME', core::getLanguage('str', 'remember_me'));	
			$tpl->assign('USERNAME', $_POST['username']);
			$tpl->assign('REMEMBER_ME', $remember_me);				
			$tpl->assign('TOTAL_USER', $total_user);	
			$tpl->assign('MSG_REQUIRED_THIS_FIELD', core::getLanguage('msg', 'required_this_field'));
			$tpl->assign('STR_SPORT_INSIDE', core::getLanguage('str', 'sport_inside'));			
			$tpl->assign('STR_WE_ARE_PEOPLE',  str_replace('%TOTAL_USER%', $total_user, core::getLanguage('str', 'we_are_people')));			
			$tpl->assign('STR_REMIND_PASSWORD', core::getLanguage('str', 'remind_password'));			

			$tpl->assign('STR_OR', core::getLanguage('str', 'or'));			
			$tpl->assign('STR_SIGN_IN_VIA', core::getLanguage('str', 'sign_in_via'));			
			$tpl->assign('STR_ENTER_TO_SITE', core::getLanguage('str', 'enter_to_site'));			
			$tpl->assign('STR_SIGN_UP', core::getLanguage('str', 'sign_up'));			
			$tpl->assign('BUTTON_LOGIN', core::getLanguage('button', 'login'));
			
			$tpl->assign('CURRENT_YEAR', date("Y"));
			$tpl->assign('STR_COPYRIGHT', core::getLanguage('str', 'copyright'));

			$tpl->assign('MENU_ABOUT_SERVICE', core::getLanguage('menu', 'about_service'));
			$tpl->assign('MENU_POSSIBILITY', core::getLanguage('menu', 'possibility'));
			$tpl->assign('MENU_ADVERTISING', core::getLanguage('menu', 'advertising'));
			$tpl->assign('MENU_TERMS_OF_USE', core::getLanguage('menu', 'terms_of_use'));
			$tpl->assign('MENU_RULES', core::getLanguage('menu', 'rules'));
			$tpl->assign('MENU_FEEDBACK', core::getLanguage('menu', 'feedback'));
			
			$tpl->display();
				
			exit();			
		}					
	}
	
	static function LoginUser($id_user, $token, $id_session){
		$id_user = core::database()->escape($id_user);
		$id_session = core::database()->escape($id_session);
	
		$result = FALSE;
		
		if($id_user && $token && $id_session){
			$query = "SELECT * FROM " . core::database()->getTableName('sessions') . " WHERE (id_user=" . $id_user . ") AND (id_session=" . $id_session . ") AND (expiration_date > NOW())";
			$rt = core::database()->querySQL($query);
			$row = core::database()->getRow($rt);

			$result = $row['token'] == $token ? TRUE : FALSE;
		}
		
		return $result;
	}
	
	static function setLog($fields)
	{
		return core::database()->insert($fields, core::database()->getTableName("log"));
	}
	
	static function Login($id_user, $remember_me)
	{
		$expiration_date = $remember_me ? time()+(3600 * 24 * 365) : time() + (60 * 30);
		$token = core::documentparser()->generateCode(25);
					
		$fields = array();
		$fields['id_session'] = 0;
		$fields['token'] = $token;
		$fields['id_user'] = $id_user;
		$fields['expiration_date'] = $remember_me ? date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")+1)) : date("Y-m-d H:i:s", mktime(date("H"), date("i")+30, date("s"), date("m"), date("d"), date("Y")));
					
		$id_session = core::database()->insert($fields, core::database()->getTableName("sessions"));
				
		if($id_session){
			$fields = array();
			$fields['id_user'] = $id_user;
			$fields['ip'] = core::documentparser()->getIP();
			$fields['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$fields['last_sign_in_at'] = date("Y-m-d H:i:s");
			
			self::setLog($fields);
					
			$domain = $_SERVER['SERVER_NAME'];		
					
			if((substr($domain, 0, 4)) == "www.") $domain = str_replace('www.','',$domain);		
					
			setcookie ("id_user", $id_user, $expiration_date, '/', "." . $domain, 0);
			setcookie ("id_session", $id_session, $expiration_date, '/', "." . $domain, 0);
			setcookie ("token", $token, $expiration_date, '/', "." . $domain, 0);				
				
			$_SESSION['user_authorization'] = "ok";
			$_SESSION['id_user'] = $id_user;
		}			
	}
	
	static function Logout()
	{
		unset($_SESSION['user_authorization']);
		unset($_SESSION['id_user']);
		
		$id_user = core::database()->escape($_COOKIE['id_user']);
		$id_session = core::database()->escape($_COOKIE['id_session']);
		
		if($id_user && $id_session){
			$result = core::database()->delete(core::database()->getTableName('sessions'), "id_session=" . $id_session . " and id_user=" . $id_user."");			
		}		
		
		$domain = $_SERVER['SERVER_NAME'];		
					
		if((substr($domain, 0, 4)) == "www.") $domain = str_replace('www.','',$domain);	
		
		setcookie ("id_user", "", time() - 3600, '/', "." .$domain, 0);
		setcookie ("id_session", "", time() - 3600, '/', "." . $domain, 0);
		setcookie ("token", "", time() - 3600, '/', "." . $domain, 0);	
	}
}

?>