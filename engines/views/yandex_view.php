<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

if(isset($_GET['code'])) {
    $result = false;

    $content = array(
        'grant_type'    => 'authorization_code',
        'code'          => $_GET['code'],
        'client_id'     => CLIENT_YANDEX_ID,
        'client_secret' => CLIENT_YANDEX_SECRET
    );

	$opts = array('http' =>
				array(
                'method' => 'POST',
                'header' =>"Content-Type: application/x-www-form-urlencodedrn".
				"Accept: */*rn",
				'content' => http_build_query($content)
				)
			);
		
	$response = @file_get_contents('https://oauth.yandex.ru/token', false, stream_context_create($opts));
	$tokenInfo = @json_decode($response);    

    if (isset($tokenInfo->access_token)) {
        $request_params = array(
            'format'       => 'json',
            'oauth_token'  => $tokenInfo->access_token
        );

        $userInfo = json_decode(file_get_contents('https://login.yandex.ru/info' . '?' . urldecode(http_build_query($request_params))), true);
		
        if (isset($userInfo['id'])) {
            $userInfo = $userInfo;
            $result = true;
        }
    }	
	
	if($result){
		echo $userInfo['default_email'];
		
		
		if($data->checkExistUser($userInfo['default_email'])){
			
			$id_user = $data->getUserId($userInfo['default_email']);
				
			Auth::Login($id_user, 1);
				
			header("Location: http://playtoget.com");
			exit;	
		}		
		else{
			$fields = array();
			$fields['id'] = 0;
			$fields['email'] = $userInfo['default_email'];
			$fields['password'] = '';
			$fields['confirmation_token'] = $token = core::documentparser()->generateCode(20);
			$fields['confirmed_at'] = date("Y-m-d H:i:s");
			$fields['confirmation_sent_at'] = date("Y-m-d H:i:s");
			$fields['firstname'] = $userInfo[0]['first_name'];
			$fields['lastname'] = $userInfo[0]['last_name'];	
			$fields['created_at'] = date("Y-m-d H:i:s");

			if($userInfo['sex']) $fields['sex'] = $userInfo['sex'];
			if($userInfo['birthday']) $fields['birthday'] = $userInfo['birthday'];
				
			$fields['confirmed'] = 1;		

			if($userInfo['is_avatar_empty'] === false){
				
				$upfile_name = md5(date("YmdHis", time())) . 'jpg';					

				if(core::documentparser()->downloadfile('http://upics.yandex.net/'. $userInfo['id'] .'/normal', PATH_USER_AVATAR_IMAGES . $upfile_name)) $fields['avatar'] = $upfile_name;			
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