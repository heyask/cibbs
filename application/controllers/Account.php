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
	
	}
	
	public function forgot_password()
	{
	
		$this->load->view('include/header');
		$this->load->view('account/forgot_password');
		$this->load->view('include/footer');
	}
	
	public function auth()
	{
	
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
		
		$member_email_check = $this->membermodel->get_member_data(array(array('column' => 'email', 'value' => $email)))->row();
		$member_data = $this->membermodel->get_member_data(array(array('column' => 'email', 'value' => $email), array('column' => 'password', 'value' => $password)))->row();
		
		//유효성검사
		if(count($member_email_check) == 0)
			$this->generalclass->print_json(lang('msg_error_not_exist_member'), false);
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
		$email_confirm = $this->generalclass->sanitize_string($this->input->get('email'));
		$member_id = $this->generalclass->sanitize_string($this->input->get('email'));
		$nickname  = $this->generalclass->sanitize_string($this->input->get('email'));
		$password      = $this->input->get('password');
		$password_confirm = $this->input->get('password');
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
		
		//유효성검사
		if(count($member_email_check) != 0)
			$this->generalclass->print_json(lang('msg_error_exist_email'), false);
		if(count($member_data) == 0)
			$this->generalclass->print_json(lang('msg_error_email_password_diff'), false);
		
		
		$member_data = $this->membermodel->get_member_data(array(array('column' => 'email', 'value' => $email), array('column' => 'password', 'value' => $password)))->row();
		
		$this->session->set_userdata('member_num', $member_data->member_num);
		$this->session->set_userdata('member_nickname', $member_data->nickname);
		
		$this->generalclass->print_json(lang('msg_success_login'), true);
	}
}
