<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();	  		
	}
	
	
	public function index()
	{
		$data = array();
		$edit_data["error_message"] = "";
		$data["edit_data"] = $edit_data;
	
		
		$this->displayBanner(FALSE);
		$this->display("repair_view",$data);
	}	

		

	
	function postRepair()
	{
		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
				
		
		if(tryGetData("content",$edit_data))
		{
			
			$add_data = array(
			"user_sn" => $this->session->userdata("f_user_sn"),
			"type" => tryGetData("type",$edit_data,0),
			"content" => tryGetData("content",$edit_data),
			"updated" => date("Y-m-d H:i:s"),
			"created" => date("Y-m-d H:i:s")
			);
			
			$repair_sn = $this->it_model->addData( "repair" , $add_data );					
						
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
	
}
