<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_project extends CI_Model {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getList($param){
		$this->db->where('user_idx', $param->user_idx);
		$this->db->join('project', 'project_user.project_idx=project.project_idx', 'left');
		$query = $this->db->get('project_user');

		if($query->num_rows()>0){
			return json_encode($query->result());
		}else{
			return json_encode(array());
		}
	}

	function addProject($param){
		$this->db->set('project_name', $param->project_name);
		$this->db->set('create_user_idx', $param->user_idx);
		$this->db->set('create_date', date("Ymd"));
		$this->db->insert('project');
		$project_idx = $this->db->insert_id();

		$this->db->set('project_idx', $project_idx);
		$this->db->set('user_idx', $param->user_idx);
		$this->db->insert('project_user');

		return $project_idx;
	}

	function inviteProject($param){
		$this->db->set('sender_idx', $param->sender_idx);
		$this->db->set('receiver_idx', $param->receiver_idx);
		$this->db->set('project_idx', $param->project_idx);
		$this->db->set('invite_date', date("Ymd"));
		$this->db->set('due_date', date('Ymd', mktime(0,0,0,date('m'),date('d')+$param->limit,date('Y'))));
		$this->db->insert('invite');
		$invite_idx = $this->db->insert_id();

		return $invite_idx;
	}

	function getCanvas($param){
		$this->db->where('project_idx', $param->project_idx);
		$this->db->order_by('canvas_ord');
		$query = $this->db->get('canvas');
		return $query->result_array();
	}

	function getBox($param){
		$this->db->where('project_idx', $param->project_idx);
		$this->db->where('canvas_idx', $param->canvas_idx);
		$query = $this->db->get('box');
		return $query->result_array();
	}

	function getCanvasImg($param) {
		$this->db->select('canvas_img');
		$this->db->where('project_idx', $param->project_idx);
		$this->db->order_by('canvas_ord');
		$query = $this->db->get('canvas');
		if($query->num_rows()>0){
			return json_encode($query->result());
		}else{
			return json_encode(array());
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */