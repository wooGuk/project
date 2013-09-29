<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_list extends CI_Controller {

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
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_project');
		$this->load->model('m_user');
		// 생성자 코드에 기능추가
	}

	public function index(){
		if($this->session->userdata('user_idx')==0){
			redirect('/login', 'refresh');
		}
		$this->get_project_list();
	}

	function get_project_list($i=null){
		$user_idx = $this->input->post('user_idx', true);

		$qParam = new stdClass();
		$qParam->user_idx = ($i=='mobile') ? $user_idx : $this->session->userdata('user_idx');
		$result = $this->m_project->getList($qParam);

		if($i=='mobile') {
			if($result!=null)
				echo $result;
			else
				echo 'empty';
			return;
		}

		$param = new stdClass();
		$param->list = json_decode($result);

		$nav_param = $param;
		$nav_param->name = "프로젝트";
		$nav_param->user_name = $this->session->userdata('user_name');
		
		$this->load->view('start');
		$this->load->view('navigator', $nav_param);
		$this->load->view('project_list_page', $param);
		$this->load->view('end');
	}

	function add_project(){
		$project_name = $this->input->post('project_name', true);

		if($project_name==null){
			echo 'addPJname'; return false;
		}

		$qParam = new stdClass();
		$qParam->project_name = $project_name;
		$qParam->user_idx = $this->session->userdata('user_idx');
		$project_idx = $this->m_project->addProject($qParam);

		if($project_idx>0){
			echo $project_idx;
			return;
		}else{
			echo 'error';
		}
	}

	function invite_project(){
		$project_idx = $this->input->post('project_idx', true);
		$user_id = $this->input->post('user_id', true);
		$limit = $this->input->post('limit', true);

		if($project_idx==null){
			echo 'inviteProjectIdx'; return false;
		}
		if($user_id==null){
			echo 'inviteUserId'; return false;
		}
		if($limit==null){
			echo 'limitDate'; return false;
		}

		$qParam = new stdClass();
		$qParam->user_id = $user_id;
		$user = $this->m_user->getUserIdx($qParam);

		if($user==false){
			echo 'error'; return false;
		}

		$qParam = new stdClass();
		$qParam->project_idx = $project_idx;
		$qParam->receiver_idx = $user->user_idx;
		$qParam->sender_idx = $this->session->userdata('user_idx');
		$qParam->limit = $limit;
		$invite_idx = $this->m_project->inviteProject($qParam);
		if($invite_idx>0){
			echo $invite_idx;
			return;
		}else{
			echo 'error';
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */