<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();


if (isset($_GET['code'])) {
    $result = false;
	
	$content = array(
		'code' => trim($_GET['code']),
		'redirect_uri' => REDIRECT_OK_URI,
		'client_id' => CLIENT_OK_ID,
		'fields' => 'email',
		'client_secret' => CLIENT_OK_SECRET,
		'grant_type' => 'authorization_code'
	);

    $opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  =>"Content-type: application/x-www-form-urlencoded\r\n".
			"Accept: */*\r\n",
			'content' => http_build_query($content)
		)
	);
		
	$response = @file_get_contents('http://api.odnoklassniki.ru/oauth/token.do', false, stream_context_create($opts));
	$tokenInfo = @json_decode($response); 
	
	if (isset($tokenInfo->access_token)) {
		
		$request_params = array(
							'application_key' => CLIENT_OK_PUBLIC,
							'method' => 'users.getCurrentUser',
							'access_token' => $tokenInfo->access_token
						);		
		
		$url = 'http://api.odnoklassniki.ru/fb.do?access_token=' . $tokenInfo->access_token . '&method=users.getCurrentUser&application_key=' . CLIENT_OK_PUBLIC . '&sig=' . md5('application_key=' . CLIENT_OK_PUBLIC . 'method=users.getCurrentUser' . md5($tokenInfo->access_token . CLIENT_OK_SECRET));
		
		
		
		$userInfo = json_decode(file_get_contents($url), true);

		if (isset($userInfo['email'])) {
            $userInfo = $userInfo;
            $result = true;
        }		
	}	
	
	if ($result) {
		if($data->checkExistUser($userInfo['email'])){
			
			$id_user = $data->getUserId($userInfo['email']);
				
			Auth::Login($id_user, 1);
				
			header("Location: http://playtoget.com");
			exit;	
		}		
		else{
			$fields = array();
			$fields['id'] = 0;
			$fields['email'] = $userInfo['email'];
			$fields['password'] = '';
			$fields['confirmation_token'] = $token = core::documentparser()->generateCode(20);
			$fields['confirmed_at'] = date("Y-m-d H:i:s");
			$fields['confirmation_sent_at'] = date("Y-m-d H:i:s");
			$fields['firstname'] = $userInfo['first_name'];
			$fields['lastname'] = $userInfo['last_name'];
			$fields['created_at'] = date("Y-m-d H:i:s");

			$fields['sex'] = $userInfo['gender'];

			if( $userInfo['birthday']) {
				$fields['birthday'] = $userInfo['birthday'];
			}					
				
			$fields['confirmed'] = 1;		

			if($userInfo['pic_3']){
				
				$upfile_name = md5(date("YmdHis", time())) . 'jpg';					

				if(core::documentparser()->downloadfile($userInfo['pic_3'], PATH_USER_AVATAR_IMAGES . $upfile_name)) $fields['avatar'] = $upfile_name;			
			}
		
			$insert_id = $data->addUser($fields);
				
			if($insert_id){
				Auth::Login($insert_id, 1);
					
				header("Location: http://playtoget.com");
				exit;
			}
		}
		
	}	
}