<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function mypage()
	{
		
		$this->load->view('header');
		$this->load->view('user/mypage');
		$this->load->view('footer');
	}
	
	public function profile()
	{
	
		$this->load->view('header');
		$this->load->view('user/profile');
		$this->load->view('footer');
	}
}
