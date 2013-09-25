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

		$nav_param = new stdClass();
		$nav_param->name = "프로젝트";
		$nav_param->user_name = $this->session->userdata('user_name');
		
		$this->load->view('navigator', $nav_param);
		$this->load->view('project_list_page', $param);
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
			return $project_idx;
		}else{
			echo 'error';
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */