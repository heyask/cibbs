<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {

	public function index()
	{
		$data = array();
		$data['page_info']['page_title'] = "설치";
		
		$this->load->view('admin/include/header', $data);
		$this->load->view('admin/install', $data);
		$this->load->view('admin/include/footer', $data);
	}
}
