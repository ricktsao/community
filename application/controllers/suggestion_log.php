<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suggestion_log extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->checkLogin();
		$this->displayBanner(FALSE);
	}


	public function index()
	{
		$data = array();
		
		$suggestion_list = $this->it_model->listData("suggestion","user_sn = '".$this->session->userdata("f_user_sn")."'",$this->per_page_rows , $this->page,array("created"=>"desc"));
		$data["pager"] = $this->getPager($suggestion_list["count"],$this->page,$this->per_page_rows,"index");
		$data["suggestion_list"] = $suggestion_list["data"];
		$this->display("suggestion_list_view",$data);
	}
	
	
	public function detail()
	{
		$content_sn = $this->input->get('sn');
						
		if($content_sn == "")
		{
			redirect(fUrl("index"));	
		}
		
		$suggestion_info = $this->it_model->listData( "suggestion" , "user_sn = '".$this->session->userdata("f_user_sn")."' and sn =".$content_sn);			

			
		if($suggestion_info["count"]>0)
		{				
			$suggestion_info = $suggestion_info["data"][0];
			$data["suggestion_info"] = $suggestion_info;		

			
			$this->display("suggestion_detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}
	
	
}

