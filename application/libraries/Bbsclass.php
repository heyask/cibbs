<?php
class Bbsclass {
	//private $_CI;
	public function __construct()
	{
		
	}
	public function getPagination($total_rows, $per_page, $request_uri, $params_http_query)
	{
		$ci =& get_instance();
		$ci->load->library('pagination');
	
		$config['base_url'] = $request_uri . '?' . $params_http_query;
		
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['num_links'] = 4;
		$config['uri_segment'] = 1;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['full_tag_open'] = '<div><ul class="pagination">';
		$config['full_tag_close'] = '</ul></div>';
		$config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '<i class="fa fa-angle-right"></i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fa fa-angle-left"></i>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['use_page_numbers'] = TRUE;
		
		$ci->pagination->initialize($config);
		
		return $ci->pagination->create_links();
	}
	
	public function convert_content($content, $html, $linkify = false)
	{
		require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/libraries/htmlpurifier-4.7.0/library/HTMLPurifier.auto.php';
		
		$content = substr(trim($content),0,65536);
    	$content = preg_replace("#[\\\]+$#", "", $content);
		/*
		$tmp_html = 0;
		if (strstr($html, 'html1'))
			$tmp_html = 1;
		else if (strstr($html, 'html2'))
			$tmp_html = 2;
		*/
		//$content = $this->conv_unescape_nl($content);
		
		if ($html === true)
		{
			$config = HTMLPurifier_Config::createDefault();
			$config->set('HTML.Allowed', "p,span,hr,img,iframe");
			$config->set('HTML.AllowedAttributes', array('a.href','a.target', 'img.src', 'img.alt', 'style', 'iframe.src', 'iframe.width', 'iframe.height', 'iframe.frameborder'));
			$config->set('HTML.SafeIframe', true);
			$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
			$config->set('CSS.Trusted', true);
			$config->set('AutoFormat.RemoveEmpty', true);
			if($linkify === true)
			{
				$config->set('AutoFormat.Linkify', true);
				$config->set('HTML.TargetBlank', true);
			}
			$purifier = new HTMLPurifier($config);
			$content = $purifier->purify($content);
		}
		else // text 이면
		{
			htmlspecialchars($content);
			$config = HTMLPurifier_Config::createDefault();
			$config->set('HTML.Allowed', "a");
			$config->set('HTML.AllowedAttributes', array('a.href','a.target'));
			if($linkify === true)
			{
				$config->set('AutoFormat.Linkify', true);
				$config->set('HTML.TargetBlank', true);
			}
			//$config->set('HTML.AllowedAttributes', array('a.href', 'img.src', 'img.alt', 'style'));
			//$config->set('CSS.Trusted', true);
			$purifier = new HTMLPurifier($config);
			$content = $purifier->purify($content);
			$content = nl2br($content);
		}
		
		$content = trim($content);
		
		return $content;
	}
	/*
	function url_auto_link($str, $attributes = array())
	{
		$attrs = '';

		foreach ($attributes as $attribute => $value) {
			$attrs .= " {$attribute}=\"{$value}\"";
		}

		$str = ' ' . $str;
		$str = preg_replace(
			'`([^"=\'>])((http|https|ftp)://[^\s<]+[^\s<\.)])`i',
			'$1<a href="$2"'.$attrs.'>$2</a>',
			$str
		);
		$str = substr($str, 1);

		return $str;

	}	
	*/
	// unescape nl 얻기
	function conv_unescape_nl($str)
	{
         $search = array('\r', 'r', '\n', 'n');
         $replace = array('', '', "n", "n");
 
         return str_replace($search, $replace, $str);
	}
}
?>