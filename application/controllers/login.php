<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();	  		
	}
	
	
	public function index()
	{
		$data = array();
		$edit_data["error_message"] = "";
		$data["edit_data"] = $edit_data;
			
		//$pre_url= tryGetData("HTTP_REFERER",$_SERVER);
		
		//echo $pre_url;
		
		
		$this->displayBanner(FALSE);
		$this->display("login_view",$data);
	}	

	
	
	function checkLogin()
	{
		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		$keycode = $this->input->post("keycode",TRUE);
		
		
		$str_conditions = "id = ".$this->db->escape(strtolower($keycode))."  
		AND	launch = 1
		";		
		
		$query = "SELECT SQL_CALC_FOUND_ROWS sys_user.* FROM sys_user WHERE role='I' AND ".$str_conditions	;
		
		//echo $query ;exit;		
				
				
		$user_info = $this->it_model->runSql( $query );
		
		if($user_info["count"] > 0)
		{
			$user_info = $user_info["data"][0];
			

			//取得comm_id
			//----------------------------------------------------------------------					
			$comm_id = $this->it_model->listData("sys_config","id='comm_id'");
			if($comm_id["count"]>0)
			{			
				$comm_id = $comm_id["data"][0]["value"];
				
			}
			else
			{
				echo 'no comm_id !!';
				die;
			}
			//----------------------------------------------------------------------
			
			
			$this->session->set_userdata('f_user_name', $user_info["name"]);
			$this->session->set_userdata('f_user_sn', $user_info["sn"]);
			$this->session->set_userdata('f_user_id', $user_info["id"]);	
			$this->session->set_userdata('f_building_id', $user_info["building_id"]);			
			$this->session->set_userdata('f_user_app_id', $user_info["app_id"]);
			$this->session->set_userdata('f_comm_id', $comm_id);
			
			if( $this->session->userdata("pre_login_url") !== FALSE) 
			{
				$pre_login_url = $this->session->userdata("pre_login_url");
				$this->session->unset_userdata('pre_login_url');
				redirect($pre_login_url);
			}
			else
			{
				redirect(frontendUrl());
			}
			
			
		}
		else 
		{
			$edit_data["error_message"] = "磁卡不正確!!";
			$data["edit_data"] = $edit_data;
			
			$this->displayBanner(FALSE);
			$this->display("login_view",$data);
		}
		
	}
	
}
