<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailbox extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->checkLogin();
		$this->displayBanner(FALSE);  
	}


	public function index()
	{
		$data = array();
		
		$mailbox_list = $this->it_model->listData("mailbox","user_sn = '".$this->session->userdata("f_user_sn")."'",10,1,array("booked"=>"desc"));		
		$data["mailbox_list"] = $mailbox_list["data"];		
		
		//郵件類型
		$mail_box_type = $this->auth_model->getWebSetting('mail_box_type');
		$mail_box_type_ary = explode(",",$mail_box_type);
		$data["mail_box_type_ary"] = $mail_box_type_ary;
		
		
		$this->display("mailbox_list_view",$data);
	}
	
	
	public function detail()
	{
		$content_sn = $this->input->get('sn');
						
		if($content_sn == "")
		{
			redirect(fUrl("index"));	
		}
		
		$mailbox_info = $this->it_model->listData("user_mailbox","sn ='".$content_sn."'");
			
		if($mailbox_info["count"]>0)
		{				
			
			$data["mailbox_info"] = $mailbox_info["data"][0];			

			$this->display("mailbox_detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}
	
	
}

