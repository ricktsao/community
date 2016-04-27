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
		$edit_data["error_message"] = "";
		$data["edit_data"] = $edit_data;		
		$this->display("suggestion_view",$data);
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
			
			$suggestion_sn = $this->it_model->addData( "suggestion" , $add_data );					
						
			redirect(frontendUrl("suggestion_log"));			
		}
		else 
		{
			$edit_data["error_message"] = "請填主旨及內容!!";
			$data["edit_data"] = $edit_data;
			
			$this->displayBanner(FALSE);
			$this->display("suggestion_view",$data);
		}
		
	}
	
}
