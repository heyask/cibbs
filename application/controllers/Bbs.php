<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bbs extends CI_Controller {

	public function listing($bbs_id)
	{
		//라이브러리
		$this->load->model('bbsmodel');
		$this->load->library('bbsclass');

		$data = array();
		$bbs_id               = $this->generalclass->sanitize_string($bbs_id);
		$params['q_type']     = $this->generalclass->sanitize_string($this->input->get('q_type'));
		$params['q']          = $this->generalclass->sanitize_string($this->input->get('q'));
		$data['current_page'] = $this->generalclass->sanitize_string($this->input->get('page'));
		
		//유효성검사
		if(!$this->generalclass->valid_en_num_($bbs_id)) //게시판 아이디 유효성
			show_error(lang('msg_error_abnormal_val'));
		if(!$this->generalclass->valid_en_num_($this->input->get('q_type'))) //검색 타입 유효성
			show_error(lang('msg_error_abnormal_val'));
		if(!$this->generalclass->valid_num($this->input->get('page'))) //페이지 숫자만 유효성
			show_error(lang('msg_error_abnormal_val'));
		
		//변수
		$data['bbs_info']     = $this->bbsmodel->get_bbs_info($bbs_id);
		$data['current_page'] = ($data['current_page'] > 0) ? intval($data['current_page']) : 1;
		
		//유효성검사
		if(count($data['bbs_info']) == 0) //게시판 존재여부
			show_error(lang('msg_error_not_exsist_bbs'));
		
		//변수
		$start_limit       = ($data['current_page'] - 1) * $data['bbs_info']->listing_cnt;
		$total_rows        = $data['bbs_info']->documents_cnt;
		$params_http_query = http_build_query($params, '', '&amp;');
		
		
		$data['documents_result'] = $this->bbsmodel->get_documents_result($bbs_id, $start_limit, $data['bbs_info']->listing_cnt, $params['q_type'], $params['q']); 
		$data['pagination']       = $this->bbsclass->getPagination($total_rows, $data['bbs_info']->listing_cnt, "/bbs/listing/" . $bbs_id, $params_http_query);
		
		$this->load->view('include/header', $data);
		$this->load->view('bbs/listing', $data);
		$this->load->view('include/footer', $data);
	}
	
	public function view($bbs_id, $document_num)
	{
		//라이브러리
		$this->load->model('bbsmodel');
		$this->load->library('bbsclass');

		$data = array();
		$bbs_id               = $this->generalclass->sanitize_string($bbs_id);
		$params['q_type']     = $this->generalclass->sanitize_string($this->input->get('q_type'));
		$params['q']          = $this->generalclass->sanitize_string($this->input->get('q'));
		$data['current_page'] = $this->generalclass->sanitize_string($this->input->get('page'));
		
		//유효성검사
		if(!$this->generalclass->valid_en_num_($bbs_id)) //게시판 아이디 유효성
			show_error(lang('msg_error_abnormal_val'));
		if(!$this->generalclass->valid_en_num_($this->input->get('q_type'))) //검색 타입 유효성
			show_error(lang('msg_error_abnormal_val'));
		if(!$this->generalclass->valid_num($this->input->get('page'))) //페이지 숫자만 유효성
			show_error(lang('msg_error_abnormal_val'));
		
		//변수
		$data['bbs_info']     = $this->bbsmodel->get_bbs_info($bbs_id);
		$data['current_page'] = ($data['current_page'] > 0) ? intval($data['current_page']) : 1;
		
		//유효성검사
		if(count($data['bbs_info']) == 0) //게시판 존재여부
			show_error(lang('msg_error_not_exsist_bbs'));
		
		//변수
		$start_limit       = ($data['current_page'] - 1) * $data['bbs_info']->listing_cnt;
		$total_rows        = $data['bbs_info']->documents_cnt;
		$params_http_query = http_build_query($params, '', '&amp;');
		
		
		$data['document_data']    = $this->bbsmodel->get_document_data('document_num', $document_num);
		$data['documents_result'] = $this->bbsmodel->get_documents_result($bbs_id, $start_limit, $data['bbs_info']->listing_cnt, $params['q_type'], $params['q']);
		$data['pagination']       = $this->bbsclass->getPagination($total_rows, $data['bbs_info']->listing_cnt, "/bbs/listing/" . $bbs_id, $params_http_query);
		
		$this->load->view('include/header', $data);
		$this->load->view('bbs/view', $data);
		$this->load->view('include/footer', $data);
	}
	
	public function write()
	{
		$data = array();
		$data['page_info']['page_title'] = "설치";
	
		$this->load->view('include/header', $data);
		$this->load->view('bbs/listing', $data);
		$this->load->view('include/footer', $data);
	}
	
	public function write_update()
	{
		$data = array();
		$data['page_info']['page_title'] = "설치";
	
		$this->load->view('include/header', $data);
		$this->load->view('bbs/listing', $data);
		$this->load->view('include/footer', $data);
	}
}
