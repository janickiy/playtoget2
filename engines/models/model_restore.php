<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_restore extends Model
{
	public function sendRestoreLink($email, $subject, $msg){
		$result = FALSE;
		
		if($email) {
			core::requireEx('libs', "PHPMailer/class.phpmailer.php");
		
			$m = new PHPMailer();
			$m->IsMail();
			$m->CharSet = 'utf-8';
			$m->From = "noreply@" . $_SERVER['SERVER_NAME'] . "";
			$m->FromName = $_SERVER['SERVER_NAME'];
			$m->isHTML(true);
			$m->AddAddress($email);
			$m->Subject = $subject;
			$m->Body = $msg;
			
			if($m->Send()) $result = TRUE;			
		}
		
		return $result;	
	} 

	public function setReset($reset_password_token, $email){
		$email = core::database()->escape($email);
		
		$query = "SELECT id FROM " . core::database()->getTableName('users') . " WHERE email LIKE '".$email."'";
		$result = core::database()->querySQL($query);
		
		if(core::database()->getRecordCount($result) > 0){
			$row = core::database()->getRow($result);
			
			$update = "UPDATE " . core::database()->getTableName("users") . " SET reset_password_sent_at=NOW(), reset_password_token='".$reset_password_token."' WHERE id=".$row['id'];
			
			if(core::database()->querySQL($update))
				return TRUE;
			else
				return FALSE;			
		}	
		else
			return FALSE;			
	}
	
	public function checkExistEmail()
    {
		$_POST['email'] = core::database()->escape($_POST['email']);
		
        $query = "SELECT email FROM " . core::database()->getTableName('users') . " WHERE email LIKE '" . $_POST['email'] . "'";
        $result = core::database()->querySQL($query);
		
        return (core::database()->getRecordCount($result) == 0) ? FALSE : TRUE;
    }
	
	public function checkExistResetToken($reset_password_token)
    {
        $query = "SELECT reset_password_token FROM " . core::database()->getTableName('users') . " WHERE reset_password_token='" . $reset_password_token . "'";
        $result = core::database()->querySQL($query);
		
        return (core::database()->getRecordCount($result) == 0) ? FALSE : TRUE;
    }
	
	public function changePassword($password, $reset_password_token)
	{
		$reset_password_token = core::database()->escape($reset_password_token);
	
		$fields = array();
		$fields['password'] = md5($password);
		$fields['reset_password_token'] = '';
		$fields['updated_at'] = NULL;
		
		$result = core::database()->update($fields, core::database()->getTableName("users"), "reset_password_token LIKE '".$reset_password_token."'");
		
		return $result;		
	}	
}