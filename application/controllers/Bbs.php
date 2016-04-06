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
		if(!$this->generalclass->valid_en_num_($this->input->get('q_type'), true)) //검색 타입 유효성
			show_error(lang('msg_error_abnormal_val'));
		if(!$this->generalclass->valid_num($this->input->get('page'), true)) //페이지 숫자만 유효성
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
		
		
		$data['documents_result'] = $this->bbsmodel->get_documents_result($bbs_id, $start_limit, $data['bbs_info']->listing_cnt, $params); 
		$data['pagination']       = $this->bbsclass->getPagination($total_rows, $data['bbs_info']->listing_cnt, "/bbs/listing/" . $bbs_id, $params_http_query);
		
		
		foreach($data['documents_result'] as $item)
		{
			$space='';
			for($i=0; $i <=$item->depth; $i++)
				$space .= '&nbsp;&nbsp;';
			echo $space. $item->document_num . " " . $item->parent_document_num . "<br>";
		}
		
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

		//유효성검사 => 게시물 존재여부
		if(count($data['document_data']) == 0) //게시판 존재여부
			show_error(lang('msg_error_not_exsist_document'));
		
		$data['documents_result'] = $this->bbsmodel->get_documents_result($bbs_id, $start_limit, $data['bbs_info']->listing_cnt, $params['q_type'], $params['q']);
		$data['pagination']       = $this->bbsclass->getPagination($total_rows, $data['bbs_info']->listing_cnt, "/bbs/listing/" . $bbs_id, $params_http_query);
		
		$this->load->view('include/header', $data);
		$this->load->view('bbs/view', $data);
		$this->load->view('include/footer', $data);
	}
	
	public function write($bbs_id, $document_num = 0)
	{
		//라이브러리
		$this->load->model('bbsmodel');
		$this->load->library('bbsclass');
		
		
		$data = array();
		$bbs_id               = $this->generalclass->sanitize_string($bbs_id);
		$data['document_num'] = $this->generalclass->sanitize_string($document_num);
		$data['target_document_num']  = $this->generalclass->sanitize_string($this->input->get('target_document_num')) ? $this->generalclass->sanitize_string($this->input->get('target_document_num')) : 0;
		
		
		//유효성검사
		if(!$this->generalclass->valid_en_num_($bbs_id)) //게시판 아이디 유효성
			show_error(lang('msg_error_abnormal_val'));
		if(!$this->generalclass->valid_num($data['document_num'])) //페이지 숫자만 유효성
			show_error(lang('msg_error_abnormal_val'));
		if(!$this->generalclass->valid_num($data['target_document_num'])) //페이지 숫자만 유효성
			show_error(lang('msg_error_abnormal_val'));
		if(!empty($data['document_num']) && !empty($data['target_document_num'])) //수정과 답글을 같이하는 비정상적 행위 차단
			show_error(lang('msg_error_abnormal_val'));
		
		//변수
		$data['bbs_info']     = $this->bbsmodel->get_bbs_info($bbs_id);
		
		//유효성검사
		if(count($data['bbs_info']) == 0) //게시판 존재여부
			show_error(lang('msg_error_not_exsist_bbs'));
		
		
		//게시물 수정일때
		if($document_num != 0)
		{
			$data['document_data'] = $this->bbsmodel->get_document_data('document_num', $document_num);
			
			//유효성검사 => 게시물 존재여부
			if(count($data['document_data']) == 0) //게시판 존재여부
				show_error(lang('msg_error_not_exsist_document'));
			
			
		}

		
		$this->load->view('include/header', $data);
		$this->load->view('bbs/write', $data);
		$this->load->view('include/footer', $data);
	}
	
	public function write_update($bbs_id)
	{
		//라이브러리
		$this->load->model('bbsmodel');
		$this->load->library('bbsclass');
		$this->load->library('HTMLPurifier');
		
		$data = array();
		$bbs_id               = $this->generalclass->sanitize_string($bbs_id);
		$document_num         = $this->generalclass->sanitize_string($this->input->get('document_num'));
		$target_document_num  = $this->generalclass->sanitize_string($this->input->get('target_document_num'));
		$title        = $this->generalclass->sanitize_string($this->input->get('title'));
		$content      = $this->generalclass->sanitize_string($this->input->get('content'));

		$config_nohtml = HTMLPurifier_Config::createDefault();
		$config_nohtml->set('HTML.Allowed', "");
		$purifier_nohtml = new HTMLPurifier($config_nohtml);
		
		$config_html = HTMLPurifier_Config::createDefault();
		$config_html->set('HTML.Allowed', "p,a[href|style|target],span,div");
		$purifier_html = new HTMLPurifier($config_html);
		
		$title   = $purifier_nohtml->purify($title);
		$content = $purifier_html->purify($content);
		
		
		
		//유효성검사
		if(!$this->generalclass->valid_en_num_($bbs_id)) //게시판 아이디 유효성
			show_error(lang('msg_error_abnormal_val'));
		if(!$this->generalclass->valid_num($document_num)) //페이지 숫자만 유효성
			show_error(lang('msg_error_abnormal_val'));
		if(!$this->generalclass->valid_num($target_document_num)) //페이지 숫자만 유효성
			show_error(lang('msg_error_abnormal_val'));
		if($document_num != 0 && $target_document_num != 0) //수정과 답글을 같이하는 비정상적 행위 차단
			show_error(lang('msg_error_abnormal_val'));
		
		//변수
		$data['bbs_info']     = $this->bbsmodel->get_bbs_info($bbs_id);
		
		//유효성검사
		if(count($data['bbs_info']) == 0) //게시판 존재여부
			show_error(lang('msg_error_not_exsist_bbs'));
		
		
		
		//게시물 수정일때
		if($document_num != 0)
		{
			$this->db->set('bbs_id', $bbs_id);
			$this->db->set('title', $title . '1');
			$this->db->set('content', $content);
			$this->db->where('document_num', $document_num);
			$this->db->update('documents_bbs');
			
			$this->generalclass->go_to_url("/bbs/view/" . $bbs_id . "/" . $document_num);
		}
		//답글등록 일 때 
		else if($target_document_num != 0)
		{
			$this->db->trans_start();
				
			$target_document_data = $this->bbsmodel->get_document_data('document_num', $target_document_num);
			if(count($target_document_data) == 0)
				show_error(lang('msg_error_not_exsist_target_document'));
				
			$position_document_data = $this->bbsmodel->get_position_document_data($target_document_data);
			$insert_sequence = $position_document_data->sequence;
			if($position_document_data->sequence == 0)
			{
				$insert_sequence = 1;
			}
			else
			{
				//새 답글 등록시 기준이되는 position_document부터 sequence를 +1하여 밑으로 밀어놓는다 그리고 그 자리에 새 답글 등록한다
				$this->db->set('sequence', 'sequence+1', false);
				$this->db->where('sequence>=' , $insert_sequence);
				$this->db->update('documents_bbs');
			}
				
			$this->db->set('bbs_id', 'test');
			$this->db->set('member_num', '1');
			$this->db->set('parent_document_num', $target_document_data->parent_document_num);
			$this->db->set('target_document_num', $target_document_data->document_num);
			$this->db->set('depth', $target_document_data->depth + 1);
			$this->db->set('sequence', $insert_sequence);
			$this->db->set('title', 'test');
			$this->db->set('content', 'test');
			$this->db->insert('documents_bbs');
			$insert_id = $this->db->insert_id();
			$this->db->trans_complete();
				
			if ($this->db->trans_status() === false)
				show_error(lang('msg_error_unknown'));
				
			echo "<br>position_document_sequence:" . $position_document_data->sequence. "<br>";
			echo "<br>insert_sequence:" . $insert_sequence. "<br>";
		}
		else
		{
			$this->db->trans_start();
			
			$this->db->set('bbs_id', $bbs_id);
			$this->db->set('member_num', '1');
			$this->db->set('parent_document_num', 0);
			$this->db->set('target_document_num', 0);
			$this->db->set('depth', 0);
			$this->db->set('sequence', 0);
			$this->db->set('title', $title);
			$this->db->set('content', $content);
			$this->db->insert('documents_bbs');
			$insert_id = $this->db->insert_id();
			
			$this->db->set('parent_document_num', $insert_id);
			$this->db->where('document_num', $insert_id);
			$this->db->update('documents_bbs');
			
			$this->db->trans_complete();
		}
		
		
		$this->load->view('include/header', $data);
		$this->load->view('bbs/listing', $data);
		$this->load->view('include/footer', $data);
	}
}
