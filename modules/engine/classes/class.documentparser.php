<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class DocumentParser {

	public function error($title, $msg)
	{
		echo "<!DOCTYPE html>\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">\n";
		echo "<title>" . $title . "</title>\n";
		echo "</head>\n";
		echo "<body>\n";
		echo "<p>".$msg."</p>\n";
		echo "</body>\n";
		echo "</html>";
	
		exit();
	}	
	
	public function generateCode($length=6){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;
		while (strlen($code) < $length) {
			$code .= $chars[mt_rand(0,$clen)];
		}
		return $code;
	}
	
	public function root()
	{
		if(dirname($_SERVER['SCRIPT_NAME']) == '/' | dirname($_SERVER['SCRIPT_NAME']) == '\\') 
			return '/';
		else 
			return dirname($_SERVER['SCRIPT_NAME']) . '/';
	}
	
	public function check_email($email)
	{
		if(preg_match("/^([a-z0-9_\.\-]{1,70})@([a-z0-9\.\-]{1,70})\.([a-z]{2,6})$/i", $email))
			return false;
		else	
			return true;
	}
	
	public function getIP()
	{
		if (getenv("HTTP_CLIENT_IP") and strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
			$ip = getenv("HTTP_CLIENT_IP"); 
		elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
			$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		elseif (getenv("REMOTE_ADDR") and strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
			$ip = getenv("REMOTE_ADDR"); 
		elseif (!empty($_SERVER['REMOTE_ADDR']) and strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
			$ip = $_SERVER['REMOTE_ADDR'];
		else
			$ip = "unknown";
 
		return($ip);
	}
	
	public function convertToDbFormat($date){
		preg_match('/(\d{2})\.(\d{2})\.(\d{4})/', $date, $out);
	
		if($out) 
			return $out[3] . "-" . $out[2] . "-" . $out[1];	
		else
			return NULL;
	}
	
	public function convertToDateFormat($date){
		preg_match('/(\d{4})-(\d{2})-(\d{2})/', $date, $out);
		
		if($out) 
			return $out[3] . "." . $out[2] . "." . $out[1];
		else 
			return '';		
	}
	
	public function mysql_russian_date($datestr = ''){
		if ($datestr == '') return '';

		list($day) = explode(' ', $datestr);
	
		switch( $day )
		{
			case date('Y-m-d'):
				$result = 'Сегодня';
			break;
	
			case date( 'Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")) ):
				$result = 'Вчера';
			break;
		
			default:
			{
				list($y, $m, $d)  = explode('-', $day);
				$month_str = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня',  'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
				$month_int = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');			
				$m = str_replace($month_int, $month_str, $m);
				$result = $d.' '.$m.' '.$y;
			}
		}
	
		return $result;
	}
	
	public function mysql_english_date($datestr = ''){
		if ($datestr == '') return '';

		list($day) = explode(' ', $datestr);
	
		switch( $day )
		{
			case date('Y-m-d'):
				$result = 'today';
			break;
	
			case date( 'Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")) ):
				$result = 'yesterday';
			break;
		
			default:
			{
				list($y, $m, $d)  = explode('-', $day);
				$month_str = array('January', 'February', 'March', 'April', 'May', 'June',  'July', 'August', 'September', 'October', 'November', 'December');
				$month_int = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');			
				$m = str_replace($month_int, $month_str, $m);
				$result = $d.' '.$m.' '.$y;
			}
		}
	
		return $result;
	}

	public function mysql_russian_datetime($datestr = ''){
		if ($datestr == '') return '';

		$dt_elements = explode(' ',$datestr);

		$date_elements = explode('-',$dt_elements[0]);
		$time_elements =  explode(':',$dt_elements[1]);
		
		$result1 = mktime($time_elements[0],$time_elements[1],$time_elements[2], $date_elements[1],$date_elements[2], $date_elements[0]);
		$monthes = array(' ', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
		$days = array(' ', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота', 'воскресенье');
		$day = date("j",$result1);
		$month = $monthes[date("n",$result1)];
		$year = date("Y",$result1);
		$hour = date("G",$result1);
		$minute = date("i",$result1);
		$dayofweek = $days[date("N",$result1)];
		$result = $day.' '.$month.' '.$year;
		
		return $result;
	}
	
	public function mysql_english_datetime($datestr = ''){
		if ($datestr == '') return '';

		$dt_elements = explode(' ',$datestr);

		$date_elements = explode('-',$dt_elements[0]);
		$time_elements =  explode(':',$dt_elements[1]);
		
		$result1 = mktime($time_elements[0],$time_elements[1],$time_elements[2], $date_elements[1],$date_elements[2], $date_elements[0]);
		$monthes = array(' ', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$days = array(' ', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'saturday');
		$day = date("j",$result1);
		$month = $monthes[date("n",$result1)];
		$year = date("Y",$result1);
		$hour = date("G",$result1);
		$minute = date("i",$result1);
		$dayofweek = $days[date("N",$result1)];
		$result = $day.' '.$month.' '.$year;
		
		return $result;
	}
	
	public function getThumb($provider, $video){
		if($provider == 'youtube'){
			return 'http://img.youtube.com/vi/' . $video . '/hqdefault.jpg';
		}
		else{
			return 'templates/images/default_group.png';
		}		
	}
	
	public function detect_video_id($link)
	{
		$video = array();
		
		if(preg_match('/youtu\.be\/([^\?]*)/', $link, $out)){
			$video['provider'] = 'youtube';
			$video['video'] = $out[1];
		} 
		else if(preg_match('/^.*((v\/)|(embed\/)|(watch\?))\??v?=?([^\&\?]*).*/', $link, $out)){
			$video['provider'] = 'youtube';
			$video['video'] = $out[5];			
		}

		return $video;
	}
	
	public function getVideoPlayer($provider, $video)
	{
		$videoplayer = '';
		
		if($provider == 'youtube'){
			$videoplayer = '<iframe width="100%" height="100%" src="//www.youtube.com/embed/' . $video . '?frameborder="0" allowfullscreen></iframe>';
		}
		
		return $videoplayer;		
	}
	
	public function getVideoLink($provider, $video)
	{
		$link = '';
		
		if($provider == 'youtube'){
			$link = 'http://www.youtube.com/watch?v=' . $video;
		}
		
		return $link;		
	}
	
	public function showJSONContent($content)
	{
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Content-Type: application/json');
		echo $content;		
	}	
	
	public function showCSS($link)
	{
		return '<link rel="stylesheet" href="' . $link . '">';
	}
	
	public function showJS($link)
	{
		return '<script type="text/javascript" src="' . $link . '"></script>';
	}
	
	public function userAvatar($user)
	{
		if($user['avatar'] && file_exists(PATH_USER_AVATAR_IMAGES . $user['avatar']) && $user['banned'] != 1 && $user['deleted'] !=1)
			$avatar = PATH_USER_AVATAR_IMAGES . $user['avatar'];
		else{
			if($user['sex'] == 'male' && $user['banned'] != 1 && $user['deleted'] !=1)
				$avatar = 'templates/images/default_male.png';
			else if($user['sex'] == 'female' && $user['banned'] != 1 && $user['deleted'] !=1)
				$avatar = 'templates/images/default_female.png';
			else
				$avatar = 'templates/images/noimage.png';	
		}
		
		return $avatar;
	}
	
	public function attackPic($pic)
	{
		return ($pic && file_exists(PATH_ATTACHMENTS . $pic)) ? PATH_ATTACHMENTS . $pic : '';
	}
	
	public function communityAvatar($row)
	{
		if($row['type'] == 'group')
			$path = PATH_GROUPCONTENT_AVATAR_IMAGES;
		else if($row['type'] == 'team')
			$path = PATH_TEAMCONTENT_AVATAR_IMAGES;
		
		return ($row['avatar'] && file_exists($path . $row['avatar']) && $row['banned'] != 1) ? $path . $row['avatar'] : 'templates/images/noimage.png';
	}
	
	public function coverPage($user)
	{
		return ($user['cover_page'] && file_exists(PATH_USER_COVER_PAGE_IMAGES . $user['cover_page']) && $user['banned'] != 1) ? PATH_USER_COVER_PAGE_IMAGES . $user['cover_page'] : 'templates/images/content-bg.png';
	}
	
	public function communityCoverPage($row)
	{
		if($row['type'] == 'group')
			$path = PATH_GROUPCONTENT_COVER_PAGE_IMAGES;
		else if($row['type'] == 'team')
			$path = PATH_TEAMCONTENT_COVER_PAGE_IMAGES;
		
		return ($row['cover_page'] && file_exists($path . $row['cover_page']) && $row['banned'] != 1) ? $path . $row['cover_page'] : 'templates/images/content-bg.png';
	}

	public function photogalleryPic($pic, $photoalbumable_type = 'user')
	{
		$path = $this->getPhotogalleryPath($photoalbumable_type);
		
		return ($pic && file_exists($path . $pic)) ? $path . $pic : '';
	}
	
	public function eventAvatar($pic)
	{
		return ($pic && file_exists(PATH_EVENTS_COVER_PAGE_IMAGES . $pic)) ? PATH_EVENTS_COVER_PAGE_IMAGES . $pic : 'templates/images/content-bg.png';
	}
	
	public function sportblockAvatar($pic)
	{
		return ($pic && file_exists(PATH_SPORTBLOCKS_AVATAR_IMAGES . $pic)) ? PATH_SPORTBLOCKS_AVATAR_IMAGES . $pic : 'templates/images/noimage.png';	
	}	
	
	public function userNotification($email, $content)
	{
		if($email){
		
			core::requireEx('libs', "PHPMailer/class.phpmailer.php");
		
			$html = '<div style="font-family:\'Open Sans\'"><div style="border:2px solid #2A2D44; background-color: #2A2D44;width:90%;margin:0 5%;border-radius:20px 20px 0 0;padding:10px 0">
			<a target="_blank" href="http://playtoget.com"><img border="0" src="cid:logo" alt="logo" style="display: block;max-width: 10%;margin-left:30px;"></a>
			</div>
			<div id="background" style="width:90%;margin:0 5%;background:#fff;border-radius:0 0 20px 20px;min-height:100px;border:2px solid #2A2D44">
			<div style="width:20%;float:left;padding:2.5%; vertical-align:top;">
			<a target="_blank" href="' .  $content['link_to_profile'] . '"><img style="width:75px;border-radius:50%;float:left;margin-right:20px;" border="0" src="cid:avatar"></a>
			</div>
			<div style="width:70%;float:right;padding:2.5%; vertical-align:top;">
			<a href="' .  $content['link_to_profile'] . '" target="_blank" style="color: #2A2D44;text-decoration:none;font-size:16px;">' . $content['name'] . '</a><br>
			<span style="font-size:10px;color:#777;">' . $content['date'] . '</span><br>
			' . $content['msg'] . '
 			</div>
 			<div style="clear:both;"></div>
			</div>
			<p style="text-align:center;color: #2A2D44;font-size: 12px;font-weight: 700;">© ' . date("Y") . ' ' . $content['copyright'] . '</p>
			<p style="color: rgb(42, 45, 68); font-size: 12px;text-align:center">' . $content['restrict_or_cancel_notification'] . '</p>
			';			
		
			$m = new PHPMailer();
			$m->IsMail();
			//$m->Encoding = 'BASE64';
			$m->CharSet = 'utf-8';
			$m->From = "noreply@" . $_SERVER['SERVER_NAME'] . "";
			$m->FromName = 'PlayToget';
			$m->isHTML(true);
			$m->AddAddress($email);
			$m->Subject = $content['subject'];
			$m->AddEmbeddedImage("templates/images/logo-main.png", "logo", "logo-main.png", "base64", "image/png");			
			$m->AddEmbeddedImage($content['avatar'], "avatar", pathinfo($content['avatar'], PATHINFO_BASENAME), "base64", $this->get_mime_type(pathinfo($content['avatar'], PATHINFO_EXTENSION)));			
			
			
			if($content['video_thumb']) $m->AddEmbeddedImage($content['video_thumb'], "video_thumb", pathinfo($content['video_thumb'], PATHINFO_BASENAME), "base64", $this->get_mime_type(pathinfo($content['video_thumb'], PATHINFO_EXTENSION)));
			if($content['photo']) $m->AddEmbeddedImage($content['photo'], "photo", pathinfo($content['photo'], PATHINFO_BASENAME), "base64", $this->get_mime_type(pathinfo($content['photo'], PATHINFO_EXTENSION)));
			
			$m->Body = $html;
			
			if($m->Send())
				return TRUE;
			else
				return FALSE;				
		}
	}
	
	public function remove_html_tags($str)
	{
		$tags = array(
        "/<script[^>]*?>.*?<\/script>/si",
        "/<[\/\!]*?[^<>]*?>/si",
        "/&(quot|#34);/i",
        "/&(laquo|#171);/i",
        "/&(raquo|#187);/i",
        "/&(hellip|#8230);/i",
        "/&(amp|#38);/i",
        "/&(lt|#60);/i",
        "/&(gt|#62);/i",
        "/&(nbsp|#160);/i",
        "/&(iexcl|#161);/i",
        "/&(cent|#162);/i",
        "/&(pound|#163);/i",
        "/&(copy|#169);/i"
		);
    
		$replace = array(
        "",
        "",
        "\"",
        "\"",
        "\"",
        "...",
        "&",
        "<",
        ">",
        " ",
        chr(161),
        chr(162),
        chr(163),
        chr(169)
		);
    
		$str = preg_replace($tags, $replace, $str);
    
		return $str;
	}
	
	public function crop($image, $x_o, $y_o, $w_o, $h_o) {
		if (($x_o < 0) || ($y_o < 0) || ($w_o < 0) || ($h_o < 0)) {
			echo "Некорректные входные параметры";
			return false;
		}
		
		list($w_i, $h_i, $type) = getimagesize($image); 
		$types = array("", "gif", "jpeg", "png"); 
		$ext = $types[$type]; 
		
		if ($ext) {
			$func = 'imagecreatefrom'.$ext;
			$img_i = $func($image);
		} else {
			echo 'Некорректное изображение'; 
			return false;
		}
		
		if ($x_o + $w_o > $w_i) $w_o = $w_i - $x_o; 
		if ($y_o + $h_o > $h_i) $h_o = $h_i - $y_o;
		$img_o = imagecreatetruecolor($w_o, $h_o); 
		imagecopy($img_o, $img_i, 0, 0, $x_o, $y_o, $w_o, $h_o); 
		$func = 'image'.$ext; 
		
		return $func($img_o, $image); 
	}
	
    public function resize($image, $w_o = false, $h_o = false) {
		if (($w_o < 0) || ($h_o < 0)) {
			echo "Некорректные входные параметры";
			return false;
		}
		
		list($w_i, $h_i, $type) = getimagesize($image); 
		$types = array("", "gif", "jpeg", "png"); 
		$ext = $types[$type]; 
   
		if ($ext) {
			$func = 'imagecreatefrom'.$ext; 
			$img_i = $func($image); 
		} else {
			echo 'Некорректное изображение'; 
			return false;
		}
	
		if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
		if (!$w_o) $w_o = $h_o / ($h_i / $w_i);
		
		$img_o = imagecreatetruecolor($w_o, $h_o);
		imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
		$func = 'image'.$ext; 
		
		return $func($img_o, $image); 
	}
	
	public function checkImageSize($image, $size){
		$image_size = getimagesize($image);
	
		if($image_size[0] > $size)
			return TRUE;
		else
			return FALSE;
	}
	
	public function checkImage($file){
		$allowed = array('png', 'jpg', 'gif');
		
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		
		if(in_array(strtolower($extension), $allowed)){
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	
	public function getPhotogalleryPath($photoalbumable_type){
		
		switch($photoalbumable_type){
			case group:
			
				$path = PATH_PHOTOGALLERY_IMAGES . 'group/';
				
			break;
			
			case team:
			
				$path = PATH_PHOTOGALLERY_IMAGES . 'team/';
				
			break;
			
			case event:
			
				$path = PATH_PHOTOGALLERY_IMAGES . 'event/';
				
			break;
			
			case fitness:
			
				$path = PATH_PHOTOGALLERY_IMAGES . 'fitness/';
				
			break;
			
			case playground:
			
				$path = PATH_PHOTOGALLERY_IMAGES . 'playground/';
				
			break;
			
			case shop:
			
				$path = PATH_PHOTOGALLERY_IMAGES . 'shop/';
				
			break;
			
			case user:
			
				$path = PATH_PHOTOGALLERY_IMAGES . 'user/';
				
			break;
			
			case user_attach:
			
				$path = PATH_PHOTOGALLERY_IMAGES . 'user_attach/';
				
			break;
		}
		
		return $path;
	}
	
	public function getAttachPath($type)
	{
		if($type == 'comment')
			$path = PATH_COMMENT_ATTACHMENTS;
		else if($type == 'message')
			$path = PATH_MESSAGE_ATTACHMENTS;

		return $path;	
	}

	public function link_replace($str)
	{
		return preg_replace("~(http|https|ftp|ftps)://(.*?)(\s|\n|[,.?!](\s|\n)|$)~", '<a target="_blank" href="$1://$2">$1://$2</a>$3', $str); 
	}
	
	public function getOS($userAgent) {
		$oses = array (
        // Mircrosoft Windows Operating Systems
		'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
		'Windows 98' => '(Windows 98)|(Win98)',
		'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
		'Windows 2000 Service Pack 1' => '(Windows NT 5.01)',
		'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
		'Windows Server 2003' => '(Windows NT 5.2)',
		'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
		'Windows 7' => 'Windows 7',
		'Windows 8' => 'Windows 8',
		'Windows 8.1' => 'Windows NT 10.0',
		'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',		
		'Windows ME' => '(Windows ME)|(Windows 98; Win 9x 4.90 )',
		'Windows CE' => '(Windows CE)',
		// UNIX Like Operating Systems
		'Mac OS X Kodiak (beta)' => '(Mac OS X beta)',
		'Mac OS X Cheetah' => '(Mac OS X 10.0)',
		'Mac OS X Puma' => '(Mac OS X 10.1)',
		'Mac OS X Jaguar' => '(Mac OS X 10.2)',
		'Mac OS X Panther' => '(Mac OS X 10.3)',
		'Mac OS X Tiger' => '(Mac OS X 10.4)',
		'Mac OS X Leopard' => '(Mac OS X 10.5)',
		'Mac OS X Snow Leopard' => '(Mac OS X 10.6)',
		'Mac OS X Lion' => '(Mac OS X 10.7)',
		'Mac OS X' => '(Mac OS X)',
		'Mac OS' => '(Mac_PowerPC)|(PowerPC)|(Macintosh)',
		'Open BSD' => '(OpenBSD)',
		'SunOS' => '(SunOS)',
		'Solaris 11' => '(Solaris/11)|(Solaris11)',
		'Solaris 10' => '((Solaris/10)|(Solaris10))',
		'Solaris 9' => '((Solaris/9)|(Solaris9))',
		'CentOS' => '(CentOS)',
		'QNX' => '(QNX)',
		// Kernels
		'UNIX' => '(UNIX)',
		// Linux Operating Systems
		'Ubuntu 12.10' => '(Ubuntu/12.10)|(Ubuntu 12.10)',
		'Ubuntu 12.04 LTS' => '(Ubuntu/12.04)|(Ubuntu 12.04)',
		'Ubuntu 11.10' => '(Ubuntu/11.10)|(Ubuntu 11.10)',
		'Ubuntu 11.04' => '(Ubuntu/11.04)|(Ubuntu 11.04)',
		'Ubuntu 10.10' => '(Ubuntu/10.10)|(Ubuntu 10.10)',
		'Ubuntu 10.04 LTS' => '(Ubuntu/10.04)|(Ubuntu 10.04)',
		'Ubuntu 9.10' => '(Ubuntu/9.10)|(Ubuntu 9.10)',
		'Ubuntu 9.04' => '(Ubuntu/9.04)|(Ubuntu 9.04)',
		'Ubuntu 8.10' => '(Ubuntu/8.10)|(Ubuntu 8.10)',
		'Ubuntu 8.04 LTS' => '(Ubuntu/8.04)|(Ubuntu 8.04)',
		'Ubuntu 6.06 LTS' => '(Ubuntu/6.06)|(Ubuntu 6.06)',
		'Red Hat Linux' => '(Red Hat)',
		'Red Hat Enterprise Linux' => '(Red Hat Enterprise)',
		'Fedora 17' => '(Fedora/17)|(Fedora 17)',
		'Fedora 16' => '(Fedora/16)|(Fedora 16)',
		'Fedora 15' => '(Fedora/15)|(Fedora 15)',
		'Fedora 14' => '(Fedora/14)|(Fedora 14)',
		'Chromium OS' => '(ChromiumOS)',
		'Google Chrome OS' => '(ChromeOS)',
		// Kernel
		'Linux' => '(Linux)|(X11)',
		// BSD Operating Systems
		'OpenBSD' => '(OpenBSD)',
		'FreeBSD' => '(FreeBSD)',
		'NetBSD' => '(NetBSD)',
		// Mobile Devices
		'Android' => '(Android)',
		'iPod' => '(iPod)',
		'iPhone' => '(iPhone)',
		'iPad' => '(iPad)',
		//DEC Operating Systems
		'OS/8' => '(OS/8)|(OS8)',
		'Older DEC OS' => '(DEC)|(RSTS)|(RSTS/E)',
		'WPS-8' => '(WPS-8)|(WPS8)',
		// BeOS Like Operating Systems
		'BeOS' => '(BeOS)|(BeOS r5)',
		'BeIA' => '(BeIA)',
		// OS/2 Operating Systems
		'OS/2 2.0' => '(OS/220)|(OS/2 2.0)',
		'OS/2' => '(OS/2)|(OS2)',
		// Search engines
		'Search engine or robot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(msnbot)|(Ask Jeeves/Teoma)|(ia_archiver)'
		);
 
		foreach($oses as $os=>$pattern){
			if(preg_match("/$pattern/i", $userAgent)) { 
				return $os;
			}
		}
		return 'Unknown'; 
	}
	
	public function getUserBrowser($agent) {
		preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info); 
		list(,$browser,$version) = $browser_info; 
		
		if (preg_match("/Opera ([0-9.]+)/i", $agent, $opera)) return 'Opera '.$opera[1];
		
		if ($browser == 'MSIE') { 
			preg_match("/(Maxthon|Avant Browser|MyIE2)/i", $agent, $ie);
			if ($ie) return $ie[1]. ' IE '.$version;
				return 'IE '.$version;
		}
		
        if ($browser == 'Firefox') { 
			preg_match("/(Flock|Navigator|Epiphany)\/([0-9.]+)/", $agent, $ff);
			if ($ff) return $ff[1].' '.$ff[2];
		}
		
        if ($browser == 'Opera' && $version == '9.80') return 'Opera ' . substr($agent,-5);
        if ($browser == 'Version') return 'Safari ' . $version;
        if (!$browser && strpos($agent, 'Gecko')) return 'Gecko';
        return $browser . ' ' . $version;
	}
	
	public function customMultiSort($array, $field) {
		$sortArr = array();
		
		foreach($array as $key=>$val){
			$sortArr[$key] = $val[$field];
		}

		array_multisort($sortArr, SORT_DESC, $array);

		return $array;
	}	
	
	public function base64ImageEncoding($img_file)
	{
		if($img_file){
			$imgData = base64_encode(file_get_contents($img_file));
			$src = 'data:' . $this->get_mime_type(pathinfo($img_file, PATHINFO_EXTENSION)) . ';base64,' . $imgData;
			
			return $src;
		}		
	}	
	
	public function get_mime_type($ext) {
		$mimetypes = Array (
		"123" => "application/vnd.lotus-1-2-3",
		"3ds" => "image/x-3ds",
		"669" => "audio/x-mod",
		"a" => "application/x-archive",
		"abw" => "application/x-abiword",
		"ac3" => "audio/ac3",
		"adb" => "text/x-adasrc",
		"ads" => "text/x-adasrc",
		"afm" => "application/x-font-afm",
		"ag" => "image/x-applix-graphics",
		"ai" => "application/illustrator",
		"aif" => "audio/x-aiff",
		"aifc" => "audio/x-aiff",
		"aiff" => "audio/x-aiff",
		"al" => "application/x-perl",
		"arj" => "application/x-arj",
		"as" => "application/x-applix-spreadsheet",
		"asc" => "text/plain",
		"asf" => "video/x-ms-asf",
		"asp" => "application/x-asp",
		"asx" => "video/x-ms-asf",
		"au" => "audio/basic",
		"avi" => "video/x-msvideo",
		"aw" => "application/x-applix-word",
		"bak" => "application/x-trash",
		"bcpio" => "application/x-bcpio",
		"bdf" => "application/x-font-bdf",
		"bib" => "text/x-bibtex",
		"bin" => "application/octet-stream",
		"blend" => "application/x-blender",
		"blender" => "application/x-blender",
		"bmp" => "image/bmp",
		"bz" => "application/x-bzip",
		"bz2" => "application/x-bzip",
		"c" => "text/x-csrc",
		"c++" => "text/x-c++src",
		"cc" => "text/x-c++src",
		"cdf" => "application/x-netcdf",
		"cdr" => "application/vnd.corel-draw",
		"cer" => "application/x-x509-ca-cert",
		"cert" => "application/x-x509-ca-cert",
		"cgi" => "application/x-cgi",
		"cgm" => "image/cgm",
		"chrt" => "application/x-kchart",
		"class" => "application/x-java",
		"cls" => "text/x-tex",
		"cpio" => "application/x-cpio",
		"cpp" => "text/x-c++src",
		"crt" => "application/x-x509-ca-cert",
		"cs" => "text/x-csharp",
		"csh" => "application/x-shellscript",
		"css" => "text/css",
		"cssl" => "text/css",
		"csv" => "text/x-comma-separated-values",
		"cur" => "image/x-win-bitmap",
		"cxx" => "text/x-c++src",
		"dat" => "video/mpeg",
		"dbf" => "application/x-dbase",
		"dc" => "application/x-dc-rom",
		"dcl" => "text/x-dcl",
		"dcm" => "image/x-dcm",
		"deb" => "application/x-deb",
		"der" => "application/x-x509-ca-cert",
		"desktop" => "application/x-desktop",
		"dia" => "application/x-dia-diagram",
		"diff" => "text/x-patch",
		"djv" => "image/vnd.djvu",
		"djvu" => "image/vnd.djvu",
		"doc" => "application/vnd.ms-word",
		"dsl" => "text/x-dsl",
		"dtd" => "text/x-dtd",
		"dvi" => "application/x-dvi",
		"dwg" => "image/vnd.dwg",
		"dxf" => "image/vnd.dxf",
		"egon" => "application/x-egon",
		"el" => "text/x-emacs-lisp",
		"eps" => "image/x-eps",
		"epsf" => "image/x-eps",
		"epsi" => "image/x-eps",
		"etheme" => "application/x-e-theme",
		"etx" => "text/x-setext",
		"exe" => "application/x-ms-dos-executable",
		"ez" => "application/andrew-inset",
		"f" => "text/x-fortran",
		"fig" => "image/x-xfig",
		"fits" => "image/x-fits",
		"flac" => "audio/x-flac",
		"flc" => "video/x-flic",
		"fli" => "video/x-flic",
		"flw" => "application/x-kivio",
		"fo" => "text/x-xslfo",
		"g3" => "image/fax-g3",
		"gb" => "application/x-gameboy-rom",
		"gcrd" => "text/x-vcard",
		"gen" => "application/x-genesis-rom",
		"gg" => "application/x-sms-rom",
		"gif" => "image/gif",
		"glade" => "application/x-glade",
		"gmo" => "application/x-gettext-translation",
		"gnc" => "application/x-gnucash",
		"gnucash" => "application/x-gnucash",
		"gnumeric" => "application/x-gnumeric",
		"gra" => "application/x-graphite",
		"gsf" => "application/x-font-type1",
		"gtar" => "application/x-gtar",
		"gz" => "application/x-gzip",
		"h" => "text/x-chdr",
		"h++" => "text/x-chdr",
		"hdf" => "application/x-hdf",
		"hh" => "text/x-c++hdr",
		"hp" => "text/x-chdr",
		"hpgl" => "application/vnd.hp-hpgl",
		"hs" => "text/x-haskell",
		"htm" => "text/html",
		"html" => "text/html",
		"icb" => "image/x-icb",
		"ico" => "image/x-ico",
		"ics" => "text/calendar",
		"idl" => "text/x-idl",
		"ief" => "image/ief",
		"iff" => "image/x-iff",
		"ilbm" => "image/x-ilbm",
		"iso" => "application/x-cd-image",
		"it" => "audio/x-it",
		"jar" => "application/x-jar",
		"java" => "text/x-java",
		"jng" => "image/x-jng",
		"jp2" => "image/jpeg2000",
		"jpe" => "image/jpeg",
		"jpeg" => "image/jpeg",
		"jpg" => "image/jpeg",
		"jpr" => "application/x-jbuilder-project",
		"jpx" => "application/x-jbuilder-project",
		"js" => "application/x-javascript",
		"karbon" => "application/x-karbon",
		"kdelnk" => "application/x-desktop",
		"kfo" => "application/x-kformula",
		"kil" => "application/x-killustrator",
		"kon" => "application/x-kontour",
		"kpm" => "application/x-kpovmodeler",
		"kpr" => "application/x-kpresenter",
		"kpt" => "application/x-kpresenter",
		"kra" => "application/x-krita",
		"ksp" => "application/x-kspread",
		"kud" => "application/x-kugar",
		"kwd" => "application/x-kword",
		"kwt" => "application/x-kword",
		"la" => "application/x-shared-library-la",
		"lha" => "application/x-lha",
		"lhs" => "text/x-literate-haskell",
		"lhz" => "application/x-lhz",
		"log" => "text/x-log",
		"ltx" => "text/x-tex",
		"lwo" => "image/x-lwo",
		"lwob" => "image/x-lwo",
		"lws" => "image/x-lws",
		"lyx" => "application/x-lyx",
		"lzh" => "application/x-lha",
		"lzo" => "application/x-lzop",
		"m" => "text/x-objcsrc",
		"m15" => "audio/x-mod",
		"m3u" => "audio/x-mpegurl",
		"man" => "application/x-troff-man",
		"md" => "application/x-genesis-rom",
		"me" => "text/x-troff-me",
		"mgp" => "application/x-magicpoint",
		"mid" => "audio/midi",
		"midi" => "audio/midi",
		"mif" => "application/x-mif",
		"mkv" => "application/x-matroska",
		"mm" => "text/x-troff-mm",
		"mml" => "text/mathml",
		"mng" => "video/x-mng",
		"moc" => "text/x-moc",
		"mod" => "audio/x-mod",
		"moov" => "video/quicktime",
		"mov" => "video/quicktime",
		"movie" => "video/x-sgi-movie",
		"mp2" => "video/mpeg",
		"mp3" => "audio/x-mp3",
		"mpe" => "video/mpeg",
		"mpeg" => "video/mpeg",
		"mpg" => "video/mpeg",
		"ms" => "text/x-troff-ms",
		"msod" => "image/x-msod",
		"msx" => "application/x-msx-rom",
		"mtm" => "audio/x-mod",
		"n64" => "application/x-n64-rom",
		"nc" => "application/x-netcdf",
		"nes" => "application/x-nes-rom",
		"nsv" => "video/x-nsv",
		"o" => "application/x-object",
		"obj" => "application/x-tgif",
		"oda" => "application/oda",
		"ogg" => "application/ogg",
		"old" => "application/x-trash",
		"oleo" => "application/x-oleo",
		"p" => "text/x-pascal",
		"p12" => "application/x-pkcs12",
		"p7s" => "application/pkcs7-signature",
		"pas" => "text/x-pascal",
		"patch" => "text/x-patch",
		"pbm" => "image/x-portable-bitmap",
		"pcd" => "image/x-photo-cd",
		"pcf" => "application/x-font-pcf",
		"pcl" => "application/vnd.hp-pcl",
		"pdb" => "application/vnd.palm",
		"pdf" => "application/pdf",
		"pem" => "application/x-x509-ca-cert",
		"perl" => "application/x-perl",
		"pfa" => "application/x-font-type1",
		"pfb" => "application/x-font-type1",
		"pfx" => "application/x-pkcs12",
		"pgm" => "image/x-portable-graymap",
		"pgn" => "application/x-chess-pgn",
		"pgp" => "application/pgp",
		"php" => "application/x-php",
		"php3" => "application/x-php",
		"php4" => "application/x-php",
		"pict" => "image/x-pict",
		"pict1" => "image/x-pict",
		"pict2" => "image/x-pict",
		"pl" => "application/x-perl",
		"pls" => "audio/x-scpls",
		"pm" => "application/x-perl",
		"png" => "image/png",
		"pnm" => "image/x-portable-anymap",
		"po" => "text/x-gettext-translation",
		"pot" => "text/x-gettext-translation-template",
		"ppm" => "image/x-portable-pixmap",
		"pps" => "application/vnd.ms-powerpoint",
		"ppt" => "application/vnd.ms-powerpoint",
		"ppz" => "application/vnd.ms-powerpoint",
		"ps" => "application/postscript",
		"psd" => "image/x-psd",
		"psf" => "application/x-font-linux-psf",
		"psid" => "audio/prs.sid",
		"pw" => "application/x-pw",
		"py" => "application/x-python",
		"pyc" => "application/x-python-bytecode",
		"pyo" => "application/x-python-bytecode",
		"qif" => "application/x-qw",
		"qt" => "video/quicktime",
		"qtvr" => "video/quicktime",
		"ra" => "audio/x-pn-realaudio",
		"ram" => "audio/x-pn-realaudio",
		"rar" => "application/x-rar",
		"ras" => "image/x-cmu-raster",
		"rdf" => "text/rdf",
		"rej" => "application/x-reject",
		"rgb" => "image/x-rgb",
		"rle" => "image/rle",
		"rm" => "audio/x-pn-realaudio",
		"roff" => "application/x-troff",
		"rpm" => "application/x-rpm",
		"rss" => "text/rss",
		"rtf" => "application/rtf",
		"rtx" => "text/richtext",
		"s3m" => "audio/x-s3m",
		"sam" => "application/x-amipro",
		"scm" => "text/x-scheme",
		"sda" => "application/vnd.stardivision.draw",
		"sdc" => "application/vnd.stardivision.calc",
		"sdd" => "application/vnd.stardivision.impress",
		"sdp" => "application/vnd.stardivision.impress",
		"sds" => "application/vnd.stardivision.chart",
		"sdw" => "application/vnd.stardivision.writer",
		"sgi" => "image/x-sgi",
		"sgl" => "application/vnd.stardivision.writer",
		"sgm" => "text/sgml",
		"sgml" => "text/sgml",
		"sh" => "application/x-shellscript",
		"shar" => "application/x-shar",
		"siag" => "application/x-siag",
		"sid" => "audio/prs.sid",
		"sik" => "application/x-trash",
		"slk" => "text/spreadsheet",
		"smd" => "application/vnd.stardivision.mail",
		"smf" => "application/vnd.stardivision.math",
		"smi" => "application/smil",
		"smil" => "application/smil",
		"sml" => "application/smil",
		"sms" => "application/x-sms-rom",
		"snd" => "audio/basic",
		"so" => "application/x-sharedlib",
		"spd" => "application/x-font-speedo",
		"sql" => "text/x-sql",
		"src" => "application/x-wais-source",
		"stc" => "application/vnd.sun.xml.calc.template",
		"std" => "application/vnd.sun.xml.draw.template",
		"sti" => "application/vnd.sun.xml.impress.template",
		"stm" => "audio/x-stm",
		"stw" => "application/vnd.sun.xml.writer.template",
		"sty" => "text/x-tex",
		"sun" => "image/x-sun-raster",
		"sv4cpio" => "application/x-sv4cpio",
		"sv4crc" => "application/x-sv4crc",
		"svg" => "image/svg+xml",
		"swf" => "application/x-shockwave-flash",
		"sxc" => "application/vnd.sun.xml.calc",
		"sxd" => "application/vnd.sun.xml.draw",
		"sxg" => "application/vnd.sun.xml.writer.global",
		"sxi" => "application/vnd.sun.xml.impress",
		"sxm" => "application/vnd.sun.xml.math",
		"sxw" => "application/vnd.sun.xml.writer",
		"sylk" => "text/spreadsheet",
		"t" => "application/x-troff",
		"tar" => "application/x-tar",
		"tcl" => "text/x-tcl",
		"tcpalette" => "application/x-terminal-color-palette",
		"tex" => "text/x-tex",
		"texi" => "text/x-texinfo",
		"texinfo" => "text/x-texinfo",
		"tga" => "image/x-tga",
		"tgz" => "application/x-compressed-tar",
		"theme" => "application/x-theme",
		"tif" => "image/tiff",
		"tiff" => "image/tiff",
		"tk" => "text/x-tcl",
		"torrent" => "application/x-bittorrent",
		"tr" => "application/x-troff",
		"ts" => "application/x-linguist",
		"tsv" => "text/tab-separated-values",
		"ttf" => "application/x-font-ttf",
		"txt" => "text/plain",
		"tzo" => "application/x-tzo",
		"ui" => "application/x-designer",
		"uil" => "text/x-uil",
		"ult" => "audio/x-mod",
		"uni" => "audio/x-mod",
		"uri" => "text/x-uri",
		"url" => "text/x-uri",
		"ustar" => "application/x-ustar",
		"vcf" => "text/x-vcalendar",
		"vcs" => "text/x-vcalendar",
		"vct" => "text/x-vcard",
		"vob" => "video/mpeg",
		"voc" => "audio/x-voc",
		"vor" => "application/vnd.stardivision.writer",
		"vpp" => "application/x-extension-vpp",
		"wav" => "audio/x-wav",
		"wb1" => "application/x-quattropro",
		"wb2" => "application/x-quattropro",
		"wb3" => "application/x-quattropro",
		"wk1" => "application/vnd.lotus-1-2-3",
		"wk3" => "application/vnd.lotus-1-2-3",
		"wk4" => "application/vnd.lotus-1-2-3",
		"wks" => "application/vnd.lotus-1-2-3",
		"wmf" => "image/x-wmf",
		"wml" => "text/vnd.wap.wml",
		"wmv" => "video/x-ms-wmv",
		"wpd" => "application/vnd.wordperfect",
		"wpg" => "application/x-wpg",
		"wri" => "application/x-mswrite",
		"wrl" => "model/vrml",
		"xac" => "application/x-gnucash",
		"xbel" => "application/x-xbel",
		"xbm" => "image/x-xbitmap",
		"xcf" => "image/x-xcf",
		"xhtml" => "application/xhtml+xml",
		"xi" => "audio/x-xi",
		"xla" => "application/vnd.ms-excel",
		"xlc" => "application/vnd.ms-excel",
		"xld" => "application/vnd.ms-excel",
		"xll" => "application/vnd.ms-excel",
		"xlm" => "application/vnd.ms-excel",
		"xls" => "application/vnd.ms-excel",
		"xlt" => "application/vnd.ms-excel",
		"xlw" => "application/vnd.ms-excel",
		"xm" => "audio/x-xm",
		"xmi" => "text/x-xmi",
		"xml" => "text/xml",
		"xpm" => "image/x-xpixmap",
		"xsl" => "text/x-xslt",
		"xslfo" => "text/x-xslfo",
		"xslt" => "text/x-xslt",
		"xwd" => "image/x-xwindowdump",
		"z" => "application/x-compress",
		"zabw" => "application/x-abiword",
		"zip" => "application/zip",
		"zoo" => "application/x-zoo"
		);

		$ext=trim(strtolower($ext));
	
		if($ext!='' && isset($mimetypes[$ext])){
			return $mimetypes[$ext];
		}
		else {
			return "application/force-download";
		}
	}
	
	public function downloadfile($url, $path) {
		$readfile = fopen ($url, "rb");
		
		if ($readfile) {
			$writefile = fopen ($path, "wb");
			if ($writefile){
				while(!feof($readfile)) {
					fwrite($writefile, fread($readfile, 4096));
				}
				fclose($writefile);
			}
			fclose($readfile);
		}
		
		if(file_exists($path))
			return true;
		else
			return false;
	}	
	
	public function sitemap_url_gen($url, $lastmod = '', $changefreq = '', $priority = '')
	{
		$search = array('&', '\'', '"', '>', '<');
		$replace = array('&amp;', '&apos;', '&quot;', '&gt;', '&lt;');
		$url = str_replace($search, $replace, $url);
		$lastmod = (empty($lastmod)) ? '' : '
		<lastmod>' . $lastmod . '</lastmod>';
		$changefreq = (empty($changefreq)) ? '' : '
		<changefreq>' . $changefreq . '</changefreq>';
		$priority = (empty($priority)) ? '' : '
		<priority>' . $priority . '</priority>';
		$res = '
		<url>
			<loc>'.$url.'</loc>' . $lastmod . $changefreq . $priority.'
		</url>';		
		
		return $res;
	}

	public function getPageUrl($type, $id)
	{
		if(is_numeric($id) && $type){
			switch($type){
				
				case user:
				
					return 'http://' . $_SERVER['SERVER_NAME'] . '/?task=profile&id_user=' . $id;
					
				break;
				
				case group:
				
					return 'http://' . $_SERVER['SERVER_NAME'] . '/?task=groups&id_community=' . $id;
				
				break;
				
				case team:
				
					return 'http://' . $_SERVER['SERVER_NAME'] . '/?task=teams&id_community=' . $id;				
				
				break;
				
				case event:
				
					return 'http://' . $_SERVER['SERVER_NAME'] . '/?task=events&id_event=' . $id;	
				
				break;
				
				case playground:
				
					return 'http://' . $_SERVER['SERVER_NAME'] . '/?task=playgrounds&id_sport_block=' . $id;	
				
				break;
				
				case shop:
				
					return 'http://' . $_SERVER['SERVER_NAME'] . '/?task=shops&id_sport_block=' . $id;
				
				break;
				
				case fitness:
				
					return 'http://' . $_SERVER['SERVER_NAME'] . '/?task=fitness&id_sport_block=' . $id;
				
				break;
			}
		}
	}	
}

?>