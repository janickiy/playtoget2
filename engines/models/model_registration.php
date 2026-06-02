<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Model_registration extends Model
{
	public function checkExistEmail()
    {
		$_POST['email'] = core::database()->escape($_POST['email']);
		
        $query = "SELECT email FROM " . core::database()->getTableName('users') . " WHERE email LIKE '" . $_POST['email'] . "'";
        $result = core::database()->querySQL($query);
		
        return (core::database()->getRecordCount($result) == 0) ? false : true;
    }	
	
	public function addUser($fields)
    {
        return core::database()->insert($fields, core::database()->getTableName('users'));
    }
	
	public function sendNotification($email, $subject, $msg){
		
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
}