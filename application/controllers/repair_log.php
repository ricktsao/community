<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair_log extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->checkLogin();
		$this->displayBanner(FALSE);
	}


	public function index()
	{
		$data = array();
		
		$repair_list = $this->it_model->listData("repair","user_sn = '".$this->session->userdata("f_user_sn")."'",$this->per_page_rows , $this->page,array("created"=>"desc"));
		$data["pager"] = $this->getPager($repair_list["count"],$this->page,$this->per_page_rows,"index");
		$data["repair_list"] = $repair_list["data"];
		$this->display("repair_list_view",$data);
	}
	
	
	public function detail()
	{
		$content_sn = $this->input->get('sn');
						
		if($content_sn == "")
		{
			redirect(fUrl("index"));	
		}
		
		$repair_info = $this->it_model->listData( "repair" , "user_sn = '".$this->session->userdata("f_user_sn")."' and sn =".$content_sn);			

			
		if($repair_info["count"]>0)
		{				
			
			$data["repair_info"] = $repair_info["data"][0];			

			$this->display("repair_detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}
	
	
}

