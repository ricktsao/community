<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail_history extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	

	/**
	 * list page
	 */
	public function contentList()
	{
		$mailbox_list = $this->it_model->listData("mailbox","is_receive = 1", NULL , NULL, array("booked"=>'desc'));				
		$data["mailbox_list"] = $mailbox_list["data"];
		$data["pager"] = $this->getPager($mailbox_list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		
		$user_list = $this->it_model->listData("sys_user");
		$user_map =  $this->it_model->toMapValue($user_list["data"],"sn","name");
		
		$data["user_map"] = $user_map;
		
		$this->display("content_list_view",$data);
	}
	

	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */