<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keycode extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->checkLogin();
		$this->displayBanner(FALSE);  
	}


	public function index()
	{
		$data = array();
		$user_sn = $this->session->userdata('f_user_sn');
		$user_info = $this->it_model->listData("sys_user","sn='".$user_sn."'");
		if($user_info["count"]>0)
		{
			$user_info = $user_info["data"][0];			
		}
		else
		{
			$this->redirectHome();
		}		
		$data["user_info"] = $user_info;
		$this->display("keycode_list_view",$data);
	}
	
	

	public function app()
	{
		$data = array();
		$user_sn = $this->session->userdata('f_user_sn');
		$user_info = $this->it_model->listData("sys_user","sn='".$user_sn."'");
		if($user_info["count"]>0)
		{
			$user_info = $user_info["data"][0];
		}
		else
		{
			$this->redirectHome();
		}		
		$data["user_info"] = $user_info;
		$this->display("app_view",$data);
	}
	
}

