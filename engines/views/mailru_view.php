<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();


if (isset($_GET['code'])) {
    $result = false;

     $content = array(
        'client_id'     => CLIENT_MR_ID,
        'client_secret' => CLIENT_MR_SECRET,
        'grant_type'    => 'authorization_code',
        'code'          => $_GET['code'],
        'redirect_uri'  => REDIRECT_MR_URI
    );

    $url_token = 'https://connect.mail.ru/oauth/token';	
	
	$opts = array('http' =>
				array(
                'method' => 'POST',
                'header' =>"Content-Type: application/x-www-form-urlencodedrn".
				"Accept: */*rn",
				'content' => http_build_query($content)
				)
			);
		
	$response = @file_get_contents('https://connect.mail.ru/oauth/token', false, stream_context_create($opts));
	$tokenInfo = @json_decode($response); 
	
		
	
		
	if (isset($tokenInfo->access_token)) {
		$request_params = array(
							'app_id' => CLIENT_MR_ID,
							'method' => 'users.getInfo',
							'secure' => 1,
							'session_key' => $tokenInfo->access_token,
							'uids' => $tokenInfo->x_mailru_vid
						);
	
		$params = '';
		
        foreach ($request_params as $key => $value) {
			$params .= "$key=$value";
        }

		$url = 'http://www.appsmail.ru/platform/api?' . http_build_query($request_params).'&sig=' . md5($params . CLIENT_MR_SECRET);
	
		$userInfo = json_decode(file_get_contents($url), true);

		if (isset($userInfo[0]['uid'])) {
            $userInfo = $userInfo;
            $result = true;
        }		
	}
	
	if ($result) {

		if($data->checkExistUser($userInfo[0]['email'])){
			
			$id_user = $data->getUserId($userInfo[0]['email']);
				
			Auth::Login($id_user, 1);
				
			header("Location: http://playtoget.com");
			exit;	
		}		
		else{
			$fields = array();
			$fields['id'] = 0;
			$fields['email'] = $userInfo[0]['email'];
			$fields['password'] = '';
			$fields['confirmation_token'] = $token = core::documentparser()->generateCode(20);
			$fields['confirmed_at'] = date("Y-m-d H:i:s");
			$fields['confirmation_sent_at'] = date("Y-m-d H:i:s");
			$fields['firstname'] = $userInfo[0]['first_name'];
			$fields['lastname'] = $userInfo[0]['last_name'];
			$fields['created_at'] = date("Y-m-d H:i:s");

			$fields['sex'] = $userInfo[0]['sex'];

			if( $userInfo[0]['birthday']) {
				$fields['birthday'] = core::documentparser()->convertToDbFormat($userInfo[0]['birthday']);
			}					
				
			$fields['confirmed'] = 1;		

			if($userInfo[0]['pic_big']){
				
				$upfile_name = md5(date("YmdHis", time())) . 'jpg';					

				if(core::documentparser()->downloadfile($userInfo[0]['pic_big'], PATH_USER_AVATAR_IMAGES . $upfile_name)) $fields['avatar'] = $upfile_name;			
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