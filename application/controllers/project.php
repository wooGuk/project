<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Controller {

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
		$project_idx = $this->input->post('project_idx', true);
		if(!$project_idx){
			redirect('/login', 'refresh');
		}
		$this->session->set_userdata('project_idx', $project_idx);

		$qParam = new stdClass();
		$qParam->project_idx = $this->session->userdata('project_idx');

		$param = new stdClass();
		$canvasListArray = $this->m_project->getCanvas($qParam);

		foreach($canvasListArray as $index => $canvas){
			$qParam->canvas_idx = $canvas['canvas_idx'];
			$boxListArray = $this->m_project->getBox($qParam);

			$canvasListArray[$index]['boxList'] = $boxListArray;
		}
		$param->canvasList = json_encode($canvasListArray);

		$this->load->view('project_page', $param);
	}

	function mainView(){
		$this->load->view('mainView');
	}

	public function curPageAndroid() {
		$project_id = $this->input->post('projectID', true);
		$canvas_id = $this->input->post('canvasID', true);
		//슬라이드쇼를 호출하는 방식으로~!!
		
	}

}