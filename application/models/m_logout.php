<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_logout extends CI_Model {

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

	function logout($param){
		$this->session->delete($param);
		$this->session->session_destroy();
		$this->cookie->delete($param);
		$this->cookie->destroy();
		redirect('login');
	}
}
