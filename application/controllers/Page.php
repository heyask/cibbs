<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

	public function main_page()
	{
		$this->load->database();
		
		
		$this->load->view('include/header');
		$this->load->view('page/main_page');
		$this->load->view('include/footer');
	}
	
	public function custom()
	{
		$this->load->view('include/header');
		$this->load->view('page/custom');
		$this->load->view('include/footer');
	}
}
