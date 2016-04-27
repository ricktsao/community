<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->checkLogin();
	}


	public function index()
	{
		$data = array();		
		$message_list = $this->it_model->listData("user_message","to_user_sn = '".$this->session->userdata("f_user_sn")."'",10,1,array("created"=>"desc"));
		
		$data["message_list"] = $message_list["data"];		
		$this->display("message_list_view",$data);
	}
	
	
	public function detail()
	{
		$content_sn = $this->input->get('sn');
						
		if($content_sn == "")
		{
			redirect(fUrl("index"));	
		}
		
		$message_info = $this->it_model->listData("user_message","sn ='".$content_sn."'");
			
		if($message_info["count"]>0)
		{				
			
			$data["message_info"] = $message_info["data"][0];			

			$this->display("message_detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}
	
	
}

