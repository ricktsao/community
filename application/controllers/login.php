<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->displayBanner(FALSE);		
	}
	
	
	public function index()
	{
		$data = array();
		$edit_data["error_message"] = "";
		$data["edit_data"] = $edit_data;
			
		//$pre_url= tryGetData("HTTP_REFERER",$_SERVER);
		
		//echo $pre_url;
		
		
		
		$this->displayHome("login_view",$data);
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
			
				$this->load->Model("auth_model");


			//查詢所屬群組&所屬權限(後台權限)
			//------------------------------------------------------------------------------------------------------------------					
			$tmp_sys_user_groups = array();
			$sys_user_belong_group = $this->it_model->listData("sys_user_belong_group", "sys_user_sn = ".$user_info["sn"]." and launch = 1" );				
			foreach($sys_user_belong_group["data"] as $item)
			{
				array_push($tmp_sys_user_groups,$item["sys_user_group_sn"]);	
			}
			$sys_user_groups = array_unique($tmp_sys_user_groups);

			$sys_func_auth = array();//特殊權限
			$sys_admin_auth = array();//後台權限
			
			if(count($sys_user_groups)>0)
			{
				//後台單元權限
				//************************************************************************************************						
				$sys_user_group_b_auth = $this->auth_model->GetGroupAuthorityList("sys_user_group_sn IN (".implode($sys_user_groups, ",").") AND sys_user_group_b_auth.launch = 1 AND sys_module.launch = 1" );		
				
				if ( $sys_user_group_b_auth["count"] > 0) {
					foreach($sys_user_group_b_auth["data"] as $item)
					{
						array_push($sys_admin_auth,$item["id"]);	
					}
				}
				//************************************************************************************************
				
				//特殊權限
				//************************************************************************************************	
				$query = "
						SELECT * ,sys_function.id
						FROM sys_user_func_auth
						LEFT JOIN sys_function on sys_user_func_auth.sys_function_sn = sys_function.sn
						WHERE sys_user_group_sn IN (".implode($sys_user_groups, ",").") AND launch = 1
				";

				$sys_user_func_auth = $this->it_model->runSql( $query );
				if ( $sys_user_func_auth["count"] > 0) {
					foreach($sys_user_func_auth["data"] as $item)
					{
						array_push($sys_func_auth,$item["id"]);	
					}
				}
				//************************************************************************************************
			}							
			//------------------------------------------------------------------------------------------------------------------
					

			//取得comm_id
			//----------------------------------------------------------------------					
			$comm_id = $this->it_model->listData("sys_config","id='comm_id'");
			if($comm_id["count"]>0)
			{			
				$comm_id = $comm_id["data"][0]["value"];
				
			}
			else
			{
				$comm_id = $this->generateCommId();
				$update_data = array(
					"id" => "comm_id",
					"value" => $comm_id,
					"launch" => 1,
					"received" => date("Y-m-d H:i:s"),
					"updated" => date("Y-m-d H:i:s")
				);
				
				$result_sn = $this->it_model->addData( "sys_config" , $update_data);
				if($result_sn == 0)
				{
					$this->redirectLoginPage();
				}				
				
			}
			//----------------------------------------------------------------------
			
			
			$this->session->set_userdata('f_user_name', $user_info["name"]);
			$this->session->set_userdata('f_user_sn', $user_info["sn"]);
			$this->session->set_userdata('f_user_id', $user_info["id"]);
			$this->session->set_userdata('f_building_id', $user_info["building_id"]);			
			$this->session->set_userdata('f_user_app_id', $user_info["app_id"]);
			$this->session->set_userdata('f_comm_id', $comm_id);
			$this->session->set_userdata('f_is_manager', $user_info["is_manager"]);


			if ( sizeof($sys_admin_auth) > 0 ) {
				// 管委人員 後台使用
				$this->session->set_userdata('user_auth', $sys_admin_auth);
				$this->session->set_userdata('user_name', $user_info["name"]);
				$this->session->set_userdata('user_group', $sys_user_groups);
			}

			//紀錄Keycode使用紀錄
			//----------------------------------------------------------------------
			$use_cnt = $user_info["use_cnt"] + 1;
			$update_data = array(
			"use_cnt" => $use_cnt,
			"login_time" => date( "Y-m-d H:i:s" ),
			"last_login_time" => $user_info["login_time"],
			"updated" => date( "Y-m-d H:i:s" )
			);
			$this->it_model->updateData( "sys_user" , $update_data,"sn = '".$user_info["sn"]."'" );
			//----------------------------------------------------------------------	
			
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
			$this->displayHome("login_view",$data);
		}
		
	}


	function generateCommId($length = 8) 
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}


	
}
