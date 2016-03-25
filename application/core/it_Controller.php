<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class IT_Controller extends CI_Controller 
{
	
	
	function __construct() 
	{
		parent::__construct();
		
		/*
		if($_SERVER['HTTP_HOST'] == 'web.chupei.com.tw' || $_SERVER['HTTP_HOST'] == '118.163.146.74')
		{
			echo '';
			exit;
		}
		*/
		

	}	
	
	
	public function sysLogout()
	{
		$this->session->unset_userdata('user_sn');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('user_email');
		$this->session->unset_userdata('user_level');
		$this->session->unset_userdata('sub_unit_sn');
		$this->session->unset_userdata('main_unit_sn');
		$this->session->unset_userdata('unit_sn');
		$this->session->unset_userdata('unit_name');
		$this->session->unset_userdata('manager_name');
		$this->session->unset_userdata('manager_email');
		$this->session->unset_userdata('secretary_name');
		$this->session->unset_userdata('secretary_email');
		$this->session->unset_userdata('supper_admin');
		$this->session->unset_userdata('user_login_time');
		$this->session->unset_userdata('user_auth');
		$this->session->unset_userdata('frontend_auth');
		$this->session->unset_userdata('func_auth');
		$this->session->unset_userdata('is_agent');
		$this->session->unset_userdata('agent_key');
		$this->session->unset_userdata('agent_id');
		$this->session->unset_userdata('agent_name');
		$this->session->unset_userdata('user_group');
		
		$this->redirectHome();
	}	
	


}

require('Backend_Controller.php');
require('Frontend_Controller.php');