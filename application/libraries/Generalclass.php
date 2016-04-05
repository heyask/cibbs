<?php
class Generalclass {
	//private $_CI;
	public function __construct()
	{
		$CI =& get_instance();
	}
	
	public function valid_en($str)
	{
		if (!empty($str) && preg_match('/^[A-Za-z]/i', $str)) // '/[^a-z\d]/i' should also work.
			return true;
		else
			return false;
	}
	
	public function valid_en_($str)
	{
		if (!empty($str) && preg_match('/^[A-Za-z_]/i', $str)) // '/[^a-z\d]/i' should also work.
			return true;
		else
			return false;
	}
	
	public function valid_en_num($str)
	{
		if (!empty($str) && !preg_match('/[^a-zA-Z0-9]/i', $str)) // '/[^a-z\d]/i' should also work.
			return true;
		else
			return false;
	}
	
	public function valid_en_num_($str)
	{
		if (!empty($str) && !preg_match('/[^a-zA-Z0-9_]/i', $str)) // '/[^a-z\d]/i' should also work.
			return true;
		else
			return false;
	}
	
	public function valid_ko_en_special($str)
	{
		if(!empty($str) && !preg_match("/^[a-zA-Z0-9가-힣\!\@\#\$\%\^\&\*\(\)\-\_\+\=\.\,\?\~]+$/i", $str))
			return false;
		else
			return true;
	}
	
	public function valid_ko_en_num($str)
	{
		if(!empty($str) && !preg_match("/^[a-zA-Z0-9가-힣]+$/i", $str))
			return false;
		else
			return true;
	}
	
	public function valid_strlen_between($min, $max, $str)
	{
		if(!empty($str) && mb_strlen($str) >= $min && mb_strlen($str) <= $max)
			return true;
		else
			return false;
	}
	
	public function valid_num($str)
	{
		if(!empty($str) && !preg_match('/[^0-9]/', $str)) // '/[^a-z\d]/i' should also work.
			return true;
		else
			return false;
	}
	
	public function valid_email($str)
	{
		if(!empty($str) && !filter_var($str, FILTER_VALIDATE_EMAIL)) 
			return false;
		else 
			return true;
	}
	
	public function valid_empty($str)
	{
		$str = trim($str);
		
		if ($str == '' || $str == 'false' || $str === false) 
			return true;
		else
			return false;
	}
	
	public function sanitize_string($str, $allow_html = false)
	{
		if($allow_html === true)
			return filter_var(trim($str), FILTER_SANITIZE_STRING);
		else 
			return filter_var(strip_tags(trim($str)), FILTER_SANITIZE_STRING);
	}
	
	
	public function is_logged()
	{
		$CI =& get_instance();
		
		if($CI->session->userdata('member_num'))
			return true;
		else
			return false;
	}
	
	// send_email(보내는이름, 보내는메일, 받는이름, 받는메일, 제목, 내용)
	public function send_email($from_name, $from_email, $to_name, $to_email, $subject, $content){
		$toName = $to_name; //받는이 이름
		$toName = "=?utf-8?b?".base64_encode($toName)."?=";
		$toMail = $to_email; //받는이 메일
		$mail_content = $content;   //메일 내용
		$subject = $subject;  //메일 제목
		$subject  = "=?utf-8?b?".base64_encode($subject)."?=";
		$fromName = $from_name;  //보내는이 이름
		$fromMail = $from_email;  //보내는이 메일
		
		$headers = "Return-Path: <".$fromMail.">\n";
		$headers .= "From: =?utf-8?b?".base64_encode($fromName)."?= <".$fromMail.">\n";
		$headers .= "X-Sender: <".$fromMail.">\n";
		$headers .= "X-Mailer: PHP\n";
		$headers .= "Reply-To: =?utf-8?b?".base64_encode($fromName)."?= <".$fromMail.">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html;charset=utf-8\n";
		$headers .= "mailed-by: inoost.com <".$fromMail.">\n";
		$rs = mail("$toName <$toMail>",$subject,$mail_content,$headers);
		if($rs){
			return true;
		} else
			return false;
	}
	
	public function checkUserAgent ( $type = NULL ) {
    	$user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
    	if ( $type == 'bot' ) {
    		// matches popular bots
    		if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
    			return true;
    			// watchmouse|pingdom\.com are "uptime services"
    		}
    	} else if ( $type == 'browser' ) {
    		// matches core browser types
    		if ( preg_match ( "/mozilla\/|opera\//", $user_agent ) ) {
    			return true;
    		}
    	} else if ( $type == 'mobile' ) {
    		// matches popular mobile devices that have small screens and/or touch inputs
    		// mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
    		// detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
    		if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
    			// these are the most common
    			return true;
    		} else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
    			// these are less common, and might not be worth checking
    			return true;
    		}
    	}
    	return false;
    }
	
	public function convertFormatSize($bytes)
    {
    	if ($bytes >= 1073741824)
    		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
    	elseif ($bytes >= 1048576)
    		$bytes = number_format($bytes / 1048576, 2) . ' MB';
    	elseif ($bytes >= 1024)
    		$bytes = number_format($bytes / 1024, 2) . ' KB';
    	elseif ($bytes > 1)
    		$bytes = $bytes . ' bytes';
    	elseif ($bytes == 1)
    		$bytes = $bytes . ' byte';
    	else
    		$bytes = '0 bytes';
    	
        return $bytes;
    }
	
	public function getTimeDiff($rtime , $option = null)
    {
    	$ctime = date('Y-m-d H:i:s');
    	if ($ctime) $cur_time = strtotime($ctime);
    	else $cur_time = time();
    	$ref_time = strtotime($rtime);
    	
    	$cur_date = floor($cur_time / 86400);
    	$ref_date = floor($ref_time / 86400);
    	
    	$datetimediff = $cur_time - $ref_time;
    	$datedist = $cur_date - $ref_date;
    	$datediff = floor($datetimediff / 86400);
    	$weekdiff = floor($datediff / 7);
    	$timediff = $datetimediff % 86400;
    	
    	$hour = floor($timediff / 3600);
    	$min = floor($timediff % 3600 / 60);
    	$sec = floor($timediff % 3600 % 60);
    	
    	$result = "";
    	if ($datedist>34) {
    		$result = date("Y년 n월 j일", $ref_time);
    	} else if ($weekdiff>0) {
    		$result = $weekdiff . "주 전";
    	} else {
    		if ($datediff>0) {
    			$result = $datedist . "일 전";
    		} else if ($timediff<=0) {
    			$result = "1초 전";
    		} else {
    			if ($hour) $result = $hour . "시간";
    			else if ($min) $result = $min . "분";
    			else $result = $sec . "초";
    			if ($result) $result .= " 전";
    		}
    	}
    	if ($option=='ALL') {
    		$result = "";
    		if ($datediff) $result .= ($result?" ":"") . $datediff."일";
    		if ($hour) $result .= ($result?" ":"") . $hour."시간";
    		if ($min) $result .= ($result?" ":"") . $min ."분";
    		if ($sec) $result .= ($result?" ":"") . $sec . "초";
    		$result .= " 전";
    	}
    	return $result;
    }
	
	public function getAuthKey()
	{
		$auth_key = "";
		
		for($i = 1; $i <= 100; $i++)
			$auth_key .= substr('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQKSTUVWXYZ', rand(0, 61), 1);
		
		return $auth_key;
	}
	
	public function getTmpPwd()
	{
		$tmp_pwd = "";
		
		for($i = 0; $i <= 9; $i++)
			$tmp_pwd .= substr('0123456789abcdefghijklmnopqrstuvwxyz', rand(0, 35), 1);
		
		return $tmp_pwd;
	}
	
	public function printJsonResult($msg, $success, $exit = false)
	{
		echo json_encode(array('msg' => $msg, 'success' => $success));
		
		if($exit === true)
		{
			exit;
		}
	}
	
	/**
	 * simple method to encrypt or decrypt a plain text string
	 * initialization vector(IV) has to be the same when encrypting and decrypting
	 * PHP 5.4.9
	 *
	 * this is a beginners template for simple encryption decryption
	 * before using this in production environments, please read about encryption
	 *
	 * @param string $action: can be 'encrypt' or 'decrypt'
	 * @param string $string: string to encrypt or decrypt
	 *
	 * @return string
	 */
	function encrypt_decrypt($action, $string) {
		$output = false;

		$encrypt_method = "AES-256-CBC";
		$secret_key = 'weSaivhAsd34d2hG19a8ha';
		$secret_iv  = 'uAdfaQ98b234jkfDvgghBt';

		// hash
		$key = hash('sha256', $secret_key);

		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		if( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}

		return $output;
	}
	
	public function history_back()
	{
		header("Location:" . $_SERVER['HTTP_REFERER']);
		exit;
	}
	
	public function go_to_login()
	{
		header("Location:" . $this->get_login_link());
		exit;
	}
	
	public function get_login_link()
	{
		return "/account/login?return_url=" . urlencode($_SERVER['REQUEST_URI']);
	}
	
	public function get_bbs_id_music()
	{
		//return array('composed_music', 'general_music');
		return array('composed_music', 'halloffame');
	}
	
	public function is_bbs_id_music($bbs_id)
	{
		if(in_array($bbs_id, $this->get_bbs_id_music()))
			return true;
		else
			return false;
	}
	
	public function is_valid_url($str)
	{
		$str = rawurldecode(trim($str));
    	$str = filter_var($str, FILTER_SANITIZE_STRING);
    	return filter_var($str, FILTER_VALIDATE_URL);
	}
	
	public function get_breadcrumb_info($page_id)
	{
		$data['free']['page_icon'] = "comments";
		$data['free']['page_title'] = "자유게시판";
		$data['free']['page_title_sub'] = "자유롭게 이야기하는 공간입니다.";
		$data['free']['page_desc'] = "";
		
		$data['qna']['page_icon'] = "question-circle";
		$data['qna']['page_title'] = "질문 & 답변";
		$data['qna']['page_title_sub'] = "여러가지 질문과 답변을 하는 공간입니다.";
		$data['qna']['page_desc'] = "";
		
		$data['suggestion']['page_icon'] = "gavel";
		$data['suggestion']['page_title'] = "건의사항";
		$data['suggestion']['page_title_sub'] = "시스템 오류 또는 개선 건의사항이 있다면 여기에 적어주세요.";
		$data['suggestion']['page_desc'] = "";
		
		$data['halloffame']['page_icon'] = "star";
		$data['halloffame']['page_title'] = "명예의 전당";
		$data['halloffame']['page_title_sub'] = "회원님들의 추천을 가장 많이 받은 자작곡들을 모아놓은 곳 입니다.";
		$data['halloffame']['page_desc'] = "회원님들의 추천을 가장 많이 받은 자작곡들을 모아놓은 곳 입니다.";
		
		$data['composed_music']['page_icon'] = "music";
		$data['composed_music']['page_title'] = "자작곡 공간";
		$data['composed_music']['page_title_sub'] = "자신이 직접 작곡한 자작곡을 업로드하는 공간입니다.";
		$data['composed_music']['page_desc'] = "";
		
		$data['general_music']['page_icon'] = "music";
		$data['general_music']['page_title'] = "일반곡 공간";
		$data['general_music']['page_title_sub'] = "이미 출시된 곡들, 자신이 좋아하는 곡들을 서로 올리며 소개, 공유하는 공간입니다.";
		$data['general_music']['page_desc'] = "";
		
		$data['video']['page_icon'] = "video-camera";
		$data['video']['page_title'] = "동영상";
		$data['video']['page_title_sub'] = "음악과 관련된 유튜브 등의 동영상을 올리는 공간입니다.";
		$data['video']['page_desc'] = "";
		
		$data['user_review']['page_icon'] = "pencil";
		$data['user_review']['page_title'] = "리뷰 & 사용기";
		$data['user_review']['page_title_sub'] = "유저들이 올리는 리뷰와 사용기입니다.";
		$data['user_review']['page_desc'] = "";
		
		$data['user_lecture']['page_icon'] = "book";
		$data['user_lecture']['page_title'] = "유저 강좌";
		$data['user_lecture']['page_title_sub'] = "유저들이 올리는 각종 강좌들입니다.";
		$data['user_lecture']['page_desc'] = "";
		
		$data['user_pds']['page_icon'] = "download";
		$data['user_pds']['page_title'] = "자료실";
		$data['user_pds']['page_title_sub'] = "각종 자료들을 올리고 받을 수 있습니다.";
		$data['user_pds']['page_desc'] = "";
		
		$data['info_concert']['page_icon'] = "star";
		$data['info_concert']['page_title'] = "공연정보";
		$data['info_concert']['page_title_sub'] = "스뮤디오에서 제공하는 공연정보입니다.";
		$data['info_concert']['page_desc'] = "";
		
		$data['info_lecture']['page_icon'] = "book";
		$data['info_lecture']['page_title'] = "강좌";
		$data['info_lecture']['page_title_sub'] = "스뮤디오에서 제공하는 강좌입니다.";
		$data['info_lecture']['page_desc'] = "";
		
		$data['info_review']['page_icon'] = "pencil";
		$data['info_review']['page_title'] = "리뷰";
		$data['info_review']['page_title_sub'] = "스뮤디오에서 제공하는 리뷰입니다.";
		$data['info_review']['page_desc'] = "";
		
		$data['notice']['page_icon'] = "exclamation-circle";
		$data['notice']['page_title'] = "공지 & 업데이트";
		$data['notice']['page_title_sub'] = "공지사항과 업데이트 사항들을 알려주는 곳 입니다.";
		$data['notice']['page_desc'] = "";
		
		return $data[$page_id];
	}
	
	public function is_same($a, $b)
	{
		if($a == $b)
			return true;
		else
			return false;
	}
	
	public function br2nl($str)
	{
		$str = preg_replace("/<br\\s*?\/??>/i", "", $str);
		
		return $str;
	}
	
	public function is_admin($member_num)
	{
		if(in_array($member_num, array(1)))
			return true;
		else 
			return false;
	}
	
	public function getFilename($insert_id, $file_type, $file_category)
	{
		$tmp_id = ceil($insert_id / 5000);
		
		if($file_type == 'image' && $file_category == 'albumart')
			$filename = "/files/images/albumart/" . $tmp_id . "/" . $insert_id . "/0.jpg";
		else if($file_type == 'image' && $file_category == 'profile')
			$filename = "/files/images/profile/" . $tmp_id . "/" . $insert_id . "/0.jpg";
		else if($file_type == 'mp3')
			$filename = '';
			
		return $filename;
	}
	
	public function isMobile()
	{
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>