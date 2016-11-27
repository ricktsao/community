<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suggestion extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();	  
		$this->displayBanner(FALSE);	
		$this->checkLogin();
	}
	
	
	public function index()
	{
		$data = array();
		
		if($this->checkSuggestFalg($this->session->userdata("f_user_sn")))
		{
			$edit_data["error_message"] = "";
			$data["edit_data"] = $edit_data;		
			$this->display("suggestion_view",$data);
		}
		else 
		{
			$this->display("no_permission_view.php",$data);
		}
		
		
		
		
	}	

		

	
	function postSuggestion()
	{
		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
				
		
		if(tryGetData("content",$edit_data)!='' && tryGetData("title",$edit_data)!='' )
		{			
			$add_data = array(
			"title" => tryGetData("title",$edit_data,0),
			"content" => tryGetData("content",$edit_data),
			"user_sn" => $this->session->userdata("f_user_sn"),
			"to_role" => tryGetData("to_role",$edit_data,"a"),
			"updated" => date("Y-m-d H:i:s"),
			"created" => date("Y-m-d H:i:s")
			);
			
			$add_data["comm_id"] = $this->getCommId();	
			$add_data["app_id"] = $this->session->userdata('f_user_app_id');	
			
			
			$suggestion_sn = $this->it_model->addData( "suggestion" , $add_data );					
			if($suggestion_sn > 0)
			{
				$add_data["sn"] = $suggestion_sn;								
				$this->sync_suggestion_to_server($add_data);
			}
			
			redirect(frontendUrl("suggestion_log"));			
		}
		else 
		{
			$edit_data["error_message"] = "請填主旨及內容!!";
			$data["edit_data"] = $edit_data;
			
			
			$this->display("suggestion_view",$data);
		}
		
	}
	
	
	/**
	 * 同步至雲端server
	 */
	function sync_suggestion_to_server($post_data)
	{
		$url = $this->config->item("api_server_url")."sync/updateSuggestion";
		
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
		
		$this->it_model->updateData( "suggestion" , array("is_sync"=>$is_sync,"updated"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
		//------------------------------------------------------------------------------
	}
	
	
}
