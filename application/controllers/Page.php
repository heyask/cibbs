<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

	public function main_page()
	{
		$this->load->database();
		
		
		$this->load->view('header');
		$this->load->view('page/main_page');
		$this->load->view('footer');
	}
}
