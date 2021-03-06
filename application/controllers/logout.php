<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

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
		$this->load->model('m_logout');
	}

	public function index($i=null){
		$this->session->sess_destroy();
		if($i!='mobile'){
			redirect('/login', 'refresh');
		}
	}
/*
	public function logoutLogic($i=null) {
		$user_idx = $this->input->post('user_idx', true);		
		$user_index = ($i=='mobile')? $user_idx : $this->session->userdata('user_idx');
		$this->m_logout->logout($user_index, $i);
	}
*/
}
