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
		// 後台
		$this->session->unset_userdata('user_sn');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('user_auth');
		$this->session->unset_userdata('user_email');
		$this->session->unset_userdata('supper_admin');
		$this->session->unset_userdata('user_login_time');
		$this->session->unset_userdata('frontend_auth');
		$this->session->unset_userdata('func_auth');
		$this->session->unset_userdata('user_group');
		$this->session->unset_userdata('comm_id');
		$this->session->unset_userdata('user_app_id');
		
		if ($this->session->userdata("f_user_id") !== FALSE 
				&& $this->session->userdata("f_is_manager") !== FALSE) {
			$redirect = 'f';
		} else {
			$redirect = 'b';
		}
		// 前台
		$this->session->unset_userdata('f_user_name');
		$this->session->unset_userdata('f_user_sn');
		$this->session->unset_userdata('f_user_id');
		$this->session->unset_userdata('f_user_app_id');
		$this->session->unset_userdata('f_comm_id');
		$this->session->unset_userdata('f_building_id');
		
		// 警衛
		$this->session->unset_userdata('guard_name');
		$this->session->unset_userdata('guard_sn');		
		
		
		if ($redirect == 'b') {
			$this->redirectHome();
		} else {
			header("Location:".base_url()."home/?".time());
		}
		
	}	
	


}

require('Backend_Controller.php');
require('Frontend_Controller.php');