<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

if (isset($_GET['code'])) {
	
	$result = false;

    $params = array(
        'client_id'     => CLIENT_FB_ID,
        'redirect_uri'  => REDIRECT_FB_URI,
        'client_secret' => CLIENT_FB_SECRET,
        'code'          => $_GET['code'],
		'scope'			=> 'email,user_birthday'
    );

    $url = 'https://graph.facebook.com/oauth/access_token';

    $tokenInfo = null;
    parse_str(file_get_contents($url . '?' . http_build_query($params)), $tokenInfo);

    if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {
        $params = array('fields'=>'id,name,email,first_name,last_name,hometown,birthday,gender','access_token' => $tokenInfo['access_token']);

        $userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);

        if (isset($userInfo['id'])) {
            $userInfo = $userInfo;
            $result = true;
        }
    }
	
	if ($result) {
		 
		if($userInfo['email']) 
			$email = $userInfo['email'];
		else 
			$email = $userInfo['id'] . '@facebook.com';			
		 
			if($data->checkExistUser($email)){
				$id_user = $data->getUserId($email);
				
				Auth::Login($id_user, 1);
				
				header("Location: http://playtoget.com");
				exit;	
			}		
			else{
				$fields = array();
				$fields['id'] = 0;
				$fields['email'] = $email;
				$fields['password'] = '';
				$fields['confirmation_token'] = $token = core::documentparser()->generateCode(20);
				$fields['confirmed_at'] = date("Y-m-d H:i:s");
				$fields['confirmation_sent_at'] = date("Y-m-d H:i:s");
				$fields['firstname'] = $userInfo['first_name'];
				$fields['lastname'] = $userInfo['last_name'];
				$fields['created_at'] = date("Y-m-d H:i:s");

				$fields['sex'] = $userInfo['gender'];

				if( $userInfo['birthday']) {
					$userInfo['birthday'] = str_replace("/", ".", $userInfo['birthday']);
					$fields['birthday'] = core::documentparser()->convertToDbFormat($userInfo['birthday']);
				}					
				
				$fields['confirmed'] = 1;		

				$upfile_name = md5(date("YmdHis", time())) . 'jpg';					

				if(core::documentparser()->downloadfile('http://graph.facebook.com/'.$userInfo['id'].'/picture?type=large', PATH_USER_AVATAR_IMAGES . $upfile_name)) $fields['avatar'] = $upfile_name;			

				$insert_id = $data->addUser($fields);
				
				if($insert_id){
					Auth::Login($insert_id, 1);
					
					header("Location: http://playtoget.com");
					exit;
				}
			}  
		}
		else{
			header("Location: http://playtoget.com");
			exit;	
		} 
	
}