<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	


	/**
	 * course list page
	 */
	public function contentList()
	{					
		$status = $this->input->get('status');
		
		$condition = "";
		if(isNull($status))
		{
			$status = "0";
		}
		$condition = "status = '".$status."'";

		$user_map = $this->it_model->listData("sys_user","");
		$user_map = $this->it_model->toMapValue($user_map["data"],"sn","name");
		
		$repair_list = $this->it_model->listData("repair",$condition, $this->per_page_rows , $this->page,array("created"=>"asc"));
		$data["list"] = $repair_list["data"];

		$data["pager"] = $this->getPager($repair_list["count"],$this->page,$this->per_page_rows,"contentList");	
		$data["user_map"] = $user_map;
		$data["status"] = $status;
		
		//計算數量
		//--------------------------------------------------------
		$repair_0_list = $this->it_model->listData("repair","status = 0");
		$repair_1_list = $this->it_model->listData("repair","status = 1");
		$repair_2_list = $this->it_model->listData("repair","status = 2");
		$repair_3_list = $this->it_model->listData("repair","status = 3");
		$repair_4_list = $this->it_model->listData("repair","status = 4");
		
		$data["status_0_cnt"] = $repair_0_list["count"];
		$data["status_1_cnt"] = $repair_1_list["count"];
		$data["status_2_cnt"] = $repair_2_list["count"];
		$data["status_3_cnt"] = $repair_3_list["count"];
		$data["status_4_cnt"] = $repair_4_list["count"];
		
		//--------------------------------------------------------
		
		//dprint($data["list"]);
		$this->display("content_list_view",$data);
	}
	

	public function editContent()
	{
		$content_sn = $this->input->get('sn');
		
		
		
		$repair_info = $this->it_model->listData( "repair" , "sn =".$content_sn);
		if($repair_info["count"]>0)
		{				
			$repair_info = $repair_info["data"][0];		
			
			//若status = 0 更新為已讀
			//------------------------------------------------------------------
			if($repair_info["status"]==0)
			{
				$this->it_model->updateData( "repair" , array("status"=>1,"updated"=>date("Y-m-d H:i:s")), "sn =".$content_sn );
			}			
			//------------------------------------------------------------------
			
			$user_info = $this->it_model->listData( "sys_user" , "sn =".$repair_info["user_sn"]);
			if($user_info["count"]==0)
			{
				redirect(bUrl("contentList"));	
			}
			$repair_info["user_name"] = $user_info["data"][0]["name"];
			
			$data["repair_info"] = $repair_info;


			//reply list
			//------------------------------------------------------------------
			$reply_list = $this->it_model->listData("repair_reply","repair_sn = '".$repair_info["sn"]."'",NULL , NULL,array("created"=>"asc"));
			$data["reply_list"] = $reply_list["data"];
			//------------------------------------------------------------------
			
			$this->display("content_form_view",$data);
		}
		else
		{
			redirect(bUrl("contentList"));	
		}		
	}
	
	
	public function updateContent()
	{	
		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		//dprint($edit_data);
		//exit;
		if(isNull($edit_data["sn"]))
		{
			redirect(bUrl("contentList"));
		}
		else 
		{
			//更新處理狀態
			//------------------------------------------------------------------			
			$this->it_model->updateData( "repair" , array("status"=>tryGetData("status",$edit_data,1),"updated"=>date("Y-m-d H:i:s")), "sn =".$edit_data["sn"] );
			//------------------------------------------------------------------
			
			
			//更新回覆
			//------------------------------------------------------------------	
			if( isNotNull(tryGetData("reply",$edit_data)) )
			{
				$add_data = array(
				"repair_sn" => $edit_data["sn"],
				"repair_status" => tryGetData("status",$edit_data,1),
				"reply" => tryGetData("reply",$edit_data),
				"updated" => date( "Y-m-d H:i:s" ),
				"created" => date( "Y-m-d H:i:s" )
				);
				
				$content_sn = $this->it_model->addData( "repair_reply" , $add_data );
				if($content_sn > 0)
				{
					$edit_data["sn"] = $content_sn;
					//$this->sync_to_server($edit_data);
				
					
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}	
			}
						
			
			
		}				
		
		
		redirect(bUrl("contentList"));	
        	
	}
	

	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */