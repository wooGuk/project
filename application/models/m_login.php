<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_login extends CI_Model {

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

	function login($param){
		$this->db->where('user_id', $param->email);
		$this->db->where('user_password', $param->passwd);
		$this->db->where('confirm_flag', true);
		$query = $this->db->get('user');

		if($query->num_rows()==1){
			$result = $query->row();

			$this->session->set_userdata(array(
				'user_idx' => $result->user_idx
				,'user_id' => $result->user_id
				,'user_name' => $result->user_name
			));
			return true;
		}else{
			return false;
		}
	}

	function get_id(){
		$query = $this->db->get('user');
		$result = $query->result();
		$result = 1;
		return $result;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */