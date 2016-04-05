<?php 
class Bbsmodel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	public function get_bbs_info($bbs_id)
	{
		$bbs_data = $this->db->query("
				SELECT * 
				FROM bbs_info 
				WHERE bbs_id='" . $bbs_id . "'")->row();
		
		return $bbs_data;
	}
	
	public function get_documents_result($bbs_id, $start_limit, $listing_cnt, $search_type, $search_query)
	{
		$this->load->database();
		$search_where = "";
			
		if(!empty($search_query))
		{
			switch($search_type)
			{
				case "title_content":
					$search_type = array('title', 'content');
				break;
				
				case "title":
					$search_type = array('title');
				break;
				
				case "content":
					$search_type = array('content');
				break;
				
				case "nickname":
					$search_type = array('documents_bbs.nickname');
				break;
				
				case "member_id":
					$search_type = array('documents_bbs.member_id');
				break;
				
				default:
					$search_type = array();
			}
			
			if(!empty($search_type))
			{
				foreach($search_type as $key => $item)
				{
					if($key == 0)
						$tmp = " ";
					else 
						$tmp = " OR ";
					
					$search_where .= $tmp . $item . " LIKE '%" . $search_query . "%'";
				}
				$search_where = "AND (" . $search_where . ")";
			}
		}
		
		$documents_result = $this->db->query("
			SELECT documents_bbs.*,member.has_profile_icon
			FROM documents_bbs
			LEFT JOIN member
			ON documents_bbs.member_num=member.member_num
			WHERE documents_bbs.bbs_id='".$bbs_id."' " . $search_where . "
			ORDER BY documents_bbs.document_num DESC LIMIT " . $start_limit . "," . $listing_cnt)->result();
		
		return $documents_result;
	}
	
	public function get_document_data($column, $value)
	{
		$this->load->database();
		
		$document_data = $this->db->query("
				SELECT documents_bbs.*,member.member_id,member.has_profile_icon 
				FROM documents_bbs 
				LEFT JOIN member 
				ON documents_bbs.member_num=member.member_num 
				WHERE documents_bbs.document_num='".$document_num."' AND documents_bbs.bbs_id='".$bbs_id."'
				")->row();
		
		return $document_data;
	}
	
	public function getTagsResult($bbs_id, $document_num)
	{
		$this->load->database();
		
		if($this->generalclass->is_bbs_id_music($bbs_id))
		{
			$tags_result = $this->db->query("
				SELECT * 
				FROM tags
				WHERE document_num='".$document_num."'
				")->result();
		}
		else
		{
			$tags_result = $this->db->query("
				SELECT * 
				FROM tags_bbs
				WHERE document_num='".$document_num."'
				")->result();
		}
		
		return $tags_result;
	}
	
	public function getDocumentsMusicResult($bbs_id = 'free', $start_limit = 0, $num = 20)
	{
		$this->load->database();
		$documents_result = $this->db->query("
				SELECT documents.*,member.has_profile_icon,member.member_id 
				FROM documents
				INNER JOIN member
				ON documents.member_num=member.member_num
				WHERE bbs_id='".$bbs_id."'
				ORDER BY document_num DESC LIMIT " . $start_limit . "," . $num)->result();
		
		return $documents_result;
	}
	
	public function getNewestDocumentsResult($num = 5, $bbs_id = 'free')
	{
		if(!$this->cache->get('bbs_sidebar_documents_result')) {
			$this->load->database();
			$bbs_sidebar_documents_result = $this->db->query("
					SELECT documents_bbs.*,member.has_profile_icon
					FROM documents_bbs
					INNER JOIN member
					ON documents_bbs.member_num=member.member_num
					WHERE bbs_id='".$bbs_id."'
					ORDER BY document_num DESC LIMIT " . $num)->result();
			//세번째 파라미터는 초단위
			$this->cache->save('bbs_sidebar_documents_result' , $bbs_sidebar_documents_result , 600);
		}
		$bbs_sidebar_documents_result = $this->cache->get('bbs_sidebar_documents_result');
		
		return $bbs_sidebar_documents_result;
	}
	
	public function getCommentData($bbs_id, $comment_num = 0)
	{
		$this->load->database();
	
		if($this->generalclass->is_bbs_id_music($bbs_id))
		{
			$data = $this->db->query("
				SELECT comments.*,member.has_profile_icon,member.signature
				FROM comments
				INNER JOIN member
				ON comments.member_num=member.member_num
				WHERE comment_num='".$comment_num."' AND bbs_id='".$bbs_id."'
				")->row();
		}
		else
		{
			$data = $this->db->query("
				SELECT comments_bbs.*,member.has_profile_icon,member.signature
				FROM comments_bbs
				INNER JOIN member
				ON comments_bbs.member_num=member.member_num
				WHERE comment_num='".$comment_num."' AND bbs_id='".$bbs_id."'
				")->row();
		}
	
		return $data;
	}
	
	public function getCommentsResult($bbs_id, $document_num)
	{
		$this->load->database();
		
		if($this->generalclass->is_bbs_id_music($bbs_id))
		{
			$comments_result = $this->db->query("
				SELECT comments.*,member.has_profile_icon
				FROM comments
				LEFT JOIN member
				ON comments.member_num=member.member_num
				WHERE document_num='" . $document_num . "' AND comments.is_valid='Y'
				ORDER BY parent_comment_num ASC, comment_num ASC")->result();
		}
		else
		{
			$comments_result = $this->db->query("
				SELECT comments_bbs.*,member.has_profile_icon,member.signature
				FROM comments_bbs
				LEFT JOIN member
				ON comments_bbs.member_num=member.member_num
				WHERE document_num='" . $document_num . "' AND comments_bbs.is_valid='Y'
				ORDER BY parent_comment_num ASC, comment_num ASC")->result();
		}
		
		return $comments_result;
	}
	
	public function commentCntEdit($document_num, $bbs_id, $cnt)
	{
		if($this->generalclass->is_bbs_id_music($bbs_id))
			$table = 'documents';
		else
			$table = 'documents_bbs';
		
		$this->load->database();
		$this->db->set('comment_cnt', 'comment_cnt ' . $cnt, false);
		$this->db->where('document_num', $document_num);
		$this->db->update($table);
	}
	
	public function getMemberData($member_num)
	{
		$this->load->database();
	
		$data = $this->db->query("
				SELECT member.* 
				FROM member
				WHERE member_num='".$member_num."' 
				")->row();
	
		return $data;
	}
	
	public function isAlreadyVoted($document_num, $member_num)
	{
		$this->load->database();
		
		$data = $this->db->query("
				SELECT * 
				FROM vote_log
				WHERE member_num='".$member_num."' AND document_num='".$document_num."' 
				")->row();
		
		if(count($data) > 0)
			return true;
		else 
			return false;
	}
	
	public function vote($document_num, $bbs_id)
	{
		if($this->generalclass->is_bbs_id_music($bbs_id))
			$table = 'documents';
		else
			$table = 'documents_bbs';
		
		$this->load->database();
		$this->db->set('like_cnt', 'like_cnt + 1', false);
		$this->db->where('document_num', $document_num);
		$this->db->update($table);
	}
	
	public function documentViewCntUp($document_num, $bbs_id)
	{
		if($this->generalclass->is_bbs_id_music($bbs_id))
			$table = 'documents';
		else
			$table = 'documents_bbs';
		
		$this->load->database();
		$this->db->set('view_cnt', 'view_cnt + 1', false);
		$this->db->where('document_num', $document_num);
		$this->db->update($table);
	}
	
	public function bbsDocumentsCntHandler($bbs_id, $cnt)
	{
		$table = 'bbs_info';
		
		$this->load->database();
		$this->db->set('documents_cnt', 'documents_cnt' . $cnt, false);
		$this->db->where('bbs_id', $bbs_id);
		$this->db->update($table);
	}
}
?>