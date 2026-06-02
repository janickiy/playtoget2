<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

session_start();

if (isset($_GET['code'])) {
    $result = false;
    $params = array(
        'client_id' => CLIENT_VK_ID,
        'client_secret' => CLIENT_VK_SECRET,
        'code' => $_GET['code'],
        'redirect_uri' => REDIRECT_VK_URI
    );

    $token=json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

    if (isset($token['access_token'])) {
        $params=array(
            'uids'=>$token['user_id'],
            'fields'=>'uid,emeil,first_name,last_name,screen_name,sex,bdate,photo_big,photo_200,city,home_town',
            'access_token'=>$token['access_token']
        );

        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
        if (isset($userInfo['response'][0]['uid'])) {
            $userInfo = $userInfo['response'][0];
            $result = true;
        }
    }

    if ($result) {
		
		if($token['email']){
		
			if($data->checkExistUser($token['email'])){
				$id_user = $data->getUserId($token['email']);
				
				Auth::Login($id_user, 1);
				
				header("Location: http://playtoget.com");
				exit;	
			}
			else{
				$fields = array();
				$fields['id'] = 0;
				$fields['email'] = $token['email'];
				$fields['password'] = '';
				$fields['confirmation_token'] = $token = core::documentparser()->generateCode(20);;
				$fields['confirmed_at'] = date("Y-m-d H:i:s");
				$fields['confirmation_sent_at'] = date("Y-m-d H:i:s");
				$fields['firstname'] = $userInfo['first_name'];
				$fields['lastname'] = $userInfo['last_name'];
				$fields['created_at'] = date("Y-m-d H:i:s");
				
				if($userInfo['sex'] == 1) 
					$fields['sex'] = 'female';
				else if($userInfo['sex'] == 2)
					$fields['sex'] = 'male';

				if($userInfo['bdate']) $fields['birthday'] = core::documentparser()->convertToDbFormat($userInfo['bdate']);
				
				$fields['confirmed'] = 1;
				
				if($userInfo['photo_200']){
					$upfile = basename($userInfo['photo_200']);;
					$ext = strrchr($upfile, "."); 
	
					$upfile_name = md5(date("YmdHis", time())) . $ext;					

					if(core::documentparser()->downloadfile($userInfo['photo_200'], PATH_USER_AVATAR_IMAGES . $upfile_name)) $fields['avatar'] = $upfile_name;			
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
}

?>