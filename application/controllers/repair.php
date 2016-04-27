<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();	
		
		$this->checkLogin();	
		$this->displayBanner(FALSE);  	
	}
	
	
	public function index()
	{
		$data = array();
		$edit_data["error_message"] = "";
		$data["edit_data"] = $edit_data;
	
		
		
		$this->display("repair_view",$data);
	}	

		

	
	function postRepair()
	{
		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		if(tryGetData("content",$edit_data))
		{
			$user_info = $this->it_model->listData("sys_user","sn='".$this->session->userdata("f_user_sn")."'");
			if($user_info["count"]>0)
			{
				$user_info = $user_info["data"][0];
			}
			else 
			{
				redirect(fUrl("index"));	
			}
			
			
			$add_data = array(
			"user_sn" => $this->session->userdata("f_user_sn"),
			"user_name" => mb_substr($user_info["name"],0,1).tryGetData($user_info["gender"],$this->config->item("gender_array"),"君"),
			"app_id" => $this->session->userdata("f_user_app_id"), 
			"type" => tryGetData("type",$edit_data,0),
			"content" => tryGetData("content",$edit_data),
			"updated" => date("Y-m-d H:i:s"),
			"created" => date("Y-m-d H:i:s")
			);
			
			$repair_sn = $this->it_model->addData( "repair" , $add_data );					
			if($repair_sn > 0)
			{
				$add_data["sn"] = $content_sn;
				$add_data["comm_id"] = $this->getCommId();					
				$this->sync_repair_to_server($add_data);
				
				$msg_count++;
			}
			
						
			redirect(frontendUrl("repair_log"));			
		}
		else 
		{
			$edit_data["error_message"] = "請填寫報修內容!!";
			$data["edit_data"] = $edit_data;
			
			$this->displayBanner(FALSE);
			$this->display("repair_view",$data);
		}
		
	}


	/**
	 * 同步至雲端server
	 */
	function sync_repair_to_server($post_data)
	{
		$url = $this->config->item("api_server_url")."sync/updateRepair";
		//dprint($post_data);
		//exit;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$is_sync = curl_exec($ch);
		curl_close ($ch);
		
		
		//更新同步狀況
		//------------------------------------------------------------------------------
		if($is_sync != '1')
		{
			$is_sync = '0';
		}			
		
		$this->it_model->updateData( "repair" , array("is_sync"=>$is_sync,"updated"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
		//------------------------------------------------------------------------------
	}
	
}
