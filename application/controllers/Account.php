<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

	public function login()
	{
		
		$this->load->view('include/header');
		$this->load->view('account/login');
		$this->load->view('include/footer');
	}
	
	public function signup()
	{
	
		$this->load->view('include/header');
		$this->load->view('account/signup');
		$this->load->view('include/footer');
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		$http_referer = $this->input->server('HTTP_REFERER');
		
		if(!empty($http_referer))
		{
			$url = parse_url($http_referer);
			$return_url = $url['path'] . $url['query'];
			$this->generalclass->go_to_url($return_url);
		} else {
			$this->generalclass->go_to_url("/");
		}
	}
	
	public function forgot_password()
	{
	
		$this->load->view('include/header');
		$this->load->view('account/forgot_password');
		$this->load->view('include/footer');
	}
	
	public function reset_password()
	{
		$this->load->model('membermodel');
		
		$auth_key = $this->generalclass->sanitize_string($this->input->get('key'));
		if(!$this->generalclass->valid_en_num($auth_key))
			show_error(lang('msg_error_abnormal_val'));
		
		$member_data = $this->membermodel->get_member_data(array(array('column' => 'auth_key', 'value' => $auth_key)))->row();
		
		if(count($member_data) != 1)
			show_error(lang('msg_error_not_exist_email'));
		
		
		
		$this->load->view('include/header');
		$this->load->view('account/reset_password');
		$this->load->view('include/footer');
	}
	
	public function auth()
	{
		$this->load->model('membermodel');
		
		$auth_key = $this->generalclass->sanitize_string($this->input->get('key'));
		if(!$this->generalclass->valid_en_num($auth_key))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		
		$member_data = $this->membermodel->get_member_data(array(array('column' => 'auth_key', 'value' => $auth_key)))->row();
		
		if(count($member_data) == 1)
			true;
		else
			false;
		
		$this->load->view('include/header');
		$this->load->view('account/auth');
		$this->load->view('include/footer');
	}
	
	public function settings()
	{
	
		$this->load->view('include/header');
		$this->load->view('account/signup');
		$this->load->view('include/footer');
	}
	
	public function settings_profile()
	{
	
		$this->load->view('include/header');
		$this->load->view('account/settings_profile');
		$this->load->view('include/footer');
	}
	
	
	
	
	/*
	 * Servlet 섹션
	 */
	public function login_check()
	{	
		//라이브러리
		$this->load->model('membermodel');
		
		$data     = array();
		$email    = $this->generalclass->sanitize_string($this->input->get('email'));
		$password = sha1($this->input->get('password'));
		$remember = $this->generalclass->sanitize_string($this->input->get('remember'));
		
		//유효성검사
		if(!$this->generalclass->valid_email($email)) 
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if(!$this->generalclass->valid_en($remember, true)) 
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		
		$member_data = $this->membermodel->get_member_data(array(array('column' => 'email', 'value' => $email), array('column' => 'password', 'value' => $password)))->row();
	
		//유효성검사
		if(count($member_data) == 0)
			$this->generalclass->print_json(lang('msg_error_email_password_diff'), false);
		
		$this->session->set_userdata('member_num', $member_data->member_num);
		$this->session->set_userdata('member_nickname', $member_data->nickname);
		
		$this->generalclass->print_json(lang('msg_success_login'), true);
	}
	
	public function signup_check()
	{

		//라이브러리
		$this->load->model('membermodel');
		
		$data          = array();
		$email         = $this->generalclass->sanitize_string($this->input->get('email'));
		$email_confirm = $this->generalclass->sanitize_string($this->input->get('email_confirm'));
		$member_id = $this->generalclass->sanitize_string($this->input->get('member_id'));
		$nickname  = $this->generalclass->sanitize_string($this->input->get('nickname'));
		$password      = $this->input->get('password');
		$password_confirm = $this->input->get('password_confirm');
		$remember      = $this->generalclass->sanitize_string($this->input->get('remember'));
		
		//유효성검사
		if(!$this->generalclass->valid_email($email))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if(!$this->generalclass->valid_email($email_confirm))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if($email != $email_confirm)
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if(!$this->generalclass->valid_strlen_between($password, 8, 100))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if(!$this->generalclass->valid_strlen_between($password_confirm, 8, 100))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if($password != $password_confirm)
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if(!$this->generalclass->valid_en_num_($member_id))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if(!$this->generalclass->valid_ko_en_special($nickname))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if(!$this->generalclass->valid_en($remember, true))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		
		$password = sha1($password);
		$member_email_check = $this->membermodel->get_member_data(array(array('column' => 'email', 'value' => $email)))->row();
		$member_id_check = $this->membermodel->get_member_data(array(array('column' => 'member_id', 'value' => $member_id)))->row();
		$member_nickname_check = $this->membermodel->get_member_data(array(array('column' => 'nickname', 'value' => $member_id)))->row();
		
		//유효성검사
		if(count($member_email_check) != 0)
			$this->generalclass->print_json(lang('msg_error_exist_email'), false);
		if(count($member_id_check) != 0)
			$this->generalclass->print_json(lang('msg_error_exist_member_id'), false);
		if(count($member_nickname_check) != 0)
			$this->generalclass->print_json(lang('msg_error_exist_nickname'), false);
		
		$auth_key = $this->generalclass->get_auth_key();
		
		$ip_addr = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
		$this->db->set('email', $email);
		$this->db->set('password', $password);
		$this->db->set('member_id', $member_id);
		$this->db->set('nickname', $nickname);
		$this->db->set('has_profile_icon', 'N');
		$this->db->set('auth_key', $auth_key);
		$this->db->set('regdate', 'now()', false);
		$this->db->set('ip_addr', $ip_addr);
		$this->db->insert('member');
		$insert_id = $this->db->insert_id();
		
		if($this->db->affected_rows() != 1)
			$this->generalclass->print_json(lang('msg_success_unknown'), false);
		
		
		$this->session->set_userdata('member_num', $insert_id);
		$this->session->set_userdata('member_nickname', $nickname);
		
		$this->generalclass->print_json(lang('msg_success_signup'), true);
	}
	
	public function dup_id_check()
	{
		$this->load->model('membermodel');
		
		$member_id = $this->generalclass->sanitize_string($this->input->get('member_id'));
		
		//유효성 검사
		if(!$this->generalclass->valid_en_num_($member_id))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if(!$this->generalclass->valid_strlen_between($member_id, 6, 20))
			$this->generalclass->print_json(sprintf(lang('msg_error_member_id_strlen_min_max'), 6, 20), false);
		
		
		$member_id_check = $this->membermodel->get_member_data(array(array('column' => 'member_id', 'value' => $member_id)))->row();
		
		if(count($member_id_check) != 0)
			$this->generalclass->print_json(lang('msg_error_exist_member_id'), false);
		else
			$this->generalclass->print_json(lang('msg_success_available_member_id'), true);
	}
	
	public function dup_nickname_check()
	{
		$this->load->model('membermodel');
		
		$nickname  = $this->generalclass->sanitize_string($this->input->get('nickname'));
		
		//유효성검사
		if(!$this->generalclass->valid_ko_en_special($nickname))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if(!$this->generalclass->valid_strlen_between($nickname, 2, 20))
			$this->generalclass->print_json(sprintf(lang('msg_error_nickname_strlen_min_max'), 2, 20), false);
		
		$member_nickname_check = $this->membermodel->get_member_data(array(array('column' => 'nickname', 'value' => $nickname)))->row();
		
		if(count($member_nickname_check) != 0)
			$this->generalclass->print_json(lang('msg_error_exist_nickname'), false);
		else
			$this->generalclass->print_json(lang('msg_success_available_nickname'), true);
	}
	
	public function send_reset_password_email()
	{
		$this->load->model('membermodel');
		
		$email = $this->generalclass->sanitize_string($this->input->get('email'));
		
		if(!$this->generalclass->valid_email($email))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		
		$member_data = $this->membermodel->get_member_data(array(array('column' => 'email', 'value' => $email)))->row();
		
		if(count($member_data) == 1)
		{
			$this->db->where('setting_id', 'reset_password_email');
			$email_setting_data = $this->db->get('reset_password_email');
			
			$this->generalclass->send_email("aa", "asdasd", "zzz", "sunrivs@gmail.com", $email_setting_data->title, $email_setting_data->content);
			$this->generalclass->print_json(lang('msg_success_send_reset_password_email'), true);
		}
		else
			$this->generalclass->print_json(lang('msg_error_not_exist_email'), true);
	}
	
	public function servlet_reset_password()
	{
		$this->load->model('membermodel');
		
		$password = $this->input->get('password');
		$password_confirm = $this->input->get('password_confirm');
		$auth_key = $this->input->get('auth_key');
		
		//유효성 검사
		if(!$this->generalclass->valid_en_num($auth_key))
			$this->generalclass->print_json(lang('msg_error_abnormal_val'), false);
		if(!$this->generalclass->valid_strlen_between($password, 8, 100))
			$this->generalclass->print_json(sprintf(lang('msg_error_password_strlen_min_max'), 8, 100), false);
		if(!$this->generalclass->valid_strlen_between($password_confirm, 8, 100))
			$this->generalclass->print_json(sprintf(lang('msg_error_password_strlen_min_max'), 8, 100), false);
		if($password != $password_confirm)
			$this->generalclass->print_json(lang('msg_error_password_password_confirm_diff'), false);
		
		$member_id_check = $this->membermodel->get_member_data(array(array('column' => 'auth_key', 'value' => $auth_key)))->row();
		
		if(count($member_id_check) != 1)
			$this->generalclass->print_json(lang('msg_error_not_exist_email'), false);
		
		$this->db->set('password', sha1($password));
		$this->db->where('auth_key', $auth_key);
		$this->db->update('member');
		
		$this->generalclass->print_json(lang('msg_success_reset_password'), true);
	}
}
