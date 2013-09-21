<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

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
		$this->load->model('m_login');
		// 생성자 코드에 기능추가
	}

	public function index(){
		if($this->session->userdata('user_idx')>0){
			$this->load->helper('url');
			redirect('/project_list', 'refresh');
		}
		$this->load->view('login_page');
	}

	public function loginCheck($i=null){
		$email = $this->input->post('email', true);
		$passwd = $this->input->post('passwd', true);

		if($email==null){
			echo 'email'; return false;
		}

		if($passwd==null){
			echo 'passwd'; return false;
		}

		$qParam = new stdClass();
		$qParam->email = $email;
		$qParam->passwd = $passwd;
		$result = $this->m_login->login($qParam);

		if($result){
			if($i=='mobile'){
				echo $this->session->userdata('user_idx');
			}else{
				echo 'success';
			}
		}else{
			echo 'error';
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */