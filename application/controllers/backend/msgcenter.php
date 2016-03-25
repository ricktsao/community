<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msgcenter extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	

	public function contentList()
	{				
		$msg_list = $this->it_model->listData("sys_message_assign","from_user_sn = '".$this->session->userdata("user_sn")."' ",$this->per_page_rows,$this->page,array("created" => "desc"));		
		$data["msg_list"] = $msg_list["data"];
		
		//取得分頁
		$data["pager"] = $this->getPager($msg_list["count"],$this->page,$this->per_page_rows,"contentList");			
		$this->display("content_list_view",$data);
	}
	
	
	
	function _initUnitData(&$data)
	{
		//秘書所屬單位
		//----------------------------------------------------------------------------------------------
		$unit_info = $this->it_model->listData("unit","secretary_user_id = '".$this->session->userdata("user_id")."' ");		
		if($unit_info["count"]>0)
		{
			$unit_info = $unit_info["data"][0];
		}	
		else 
		{
			$unit_info = array();
		}
			
		$data["unit_info"] = $unit_info;
		
		//----------------------------------------------------------------------------------------------
		
		//秘書所屬單位的業務
		//----------------------------------------------------------------------------------------------
		//秘書所屬單位
		$unit_list = $this->it_model->listData("unit","secretary_user_id = '".$this->session->userdata("user_id")."'");		
				
		//dprint($unit_list);
		$unit_sn_ary = array();
		$unit_sales_list = array();
		foreach ($unit_list["data"] as $key => $item) 
		{
			array_push($unit_sn_ary,$item["sn"]);
			$unit_sales_list[$item["sn"]]["unit_info"] = $item;
			$unit_sales_list[$item["sn"]]["sales_list"] = array();
		}
		
		//單位所屬業務
		
		if(count($unit_sn_ary) > 0)
		{
			$sales_list = $this->it_model->listData("sys_user","unit_sn in ( ".implode(",", $unit_sn_ary)." ) AND launch = 1");
			$sales_list = $sales_list["data"];
			foreach ($sales_list as $key => $item) 
			{
				if(array_key_exists($item["unit_sn"], $unit_sales_list))
				{
					array_push($unit_sales_list[$item["unit_sn"]]["sales_list"],$item);
				}				
			}
		}
		
		//dprint($unit_sales_list);
		$data["unit_sales_list"] = $unit_sales_list;
		//----------------------------------------------------------------------------------------------
	}
	

	public function editContent()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");	
				
		$user_id = $this->session->userdata("user_id");
		
		$data = array();
		
		$this->_initUnitData($data);
		
		$data["edit_data"] = array
		(			
			'meeting_date' => date( "Y-m-d 09:00" ),			
			'target' => 1,			
		);
		$this->display("content_form_view",$data);
	
	}
	
	
	public function updateContent()
	{			
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		$to_user_sn_ary = tryGetData("to_user_sn", $edit_data,array());
						
		if ( ! $this->_validateContent())
		{
			$this->addCss("css/chosen.css");
			$this->addJs("js/chosen.jquery.min.js");	
			$data["edit_data"] = $edit_data;
			$this->_initUnitData($data);					
			$this->display("content_form_view",$data);
		}
        else 
        {		
        	
			$sales_list = array();//發送的業務SN列表	
				
				
				
			if(tryGetData("target", $edit_data)=="1")
			{
				//秘書所屬單位的業務
				//----------------------------------------------------------------------------------------------
				//秘書所屬單位
				$unit_list = $this->it_model->listData("unit","secretary_user_id = '".$this->session->userdata("user_id")."'");		
						
				//dprint($unit_list);
				$unit_sn_ary = array();
				
				foreach ($unit_list["data"] as $key => $item) 
				{
					array_push($unit_sn_ary,$item["sn"]);
				}
				
				//單位所屬業務				
				if(count($unit_sn_ary) > 0)
				{
					$sales_list = $this->it_model->listData("sys_user","unit_sn in ( ".implode(",", $unit_sn_ary)." ) AND launch = 1");
					$sales_list = $sales_list["data"];
				}				
				//----------------------------------------------------------------------------------------------
			}
			else if(count($to_user_sn_ary) > 0)
			{				
				
				$sql = "
					select SQL_CALC_FOUND_ROWS sys_user.* ,unit.unit_name
					from sys_user
					left join unit on sys_user.unit_sn = unit.sn
					where sys_user.sn in (".implode(",", $to_user_sn_ary).")
				";				

				$sales_list = $this->it_model->runSql( $sql );
				$sales_list = $sales_list["data"];				
			}
			
			//新增指派		
			$assign_sn = $this->updateMessageAssign($edit_data,$sales_list);
			

							
			if($assign_sn > 0)
			{
				$msg_count =0;	
				$error_user_ary = array();	
				foreach ($sales_list as $key => $item) 
				{
					$from_uint_sn = $item["sn"];
				
					$arr_data = array
					(      
						  "from_unit_sn" => $this->session->userdata('unit_sn')
						, "from_unit_name" => $this->session->userdata('unit_name')
						, "from_user_sn" => $this->session->userdata('user_sn')
						, "to_user_sn" => $item["sn"]
						, "category_id" => tryGetData("category_id", $edit_data)
						, "title" => tryGetData("title", $edit_data)					
						, "msg_content" => tryGetData("msg_content", $edit_data)			
						, "updated" => date( "Y-m-d H:i:s" )
						, "created" => date( "Y-m-d H:i:s" )
					);
										
					
					if(tryGetData("category_id", $edit_data) == "meeting")
					{
						$arr_data["meeting_date"] = tryGetData("meeting_date", $edit_data,NULL);
					}
					
					
					$content_sn = $this->it_model->addData( "sys_message" , $arr_data );
					if($content_sn > 0)
					{
						
						//更新至行事曆
						//---------------------------------------------------
						if(tryGetData("category_id", $edit_data) == "meeting")
						{							
							$this->_updateCalendar($edit_data,$item["sn"],$item["id"]);
						}
						//---------------------------------------------------
						
						
						
						$msg_count++;
					}
					else 
					{
						array_push($error_user_ary,$item["id"]."-".$item["name"]);
					}
				}
				
				
				if($msg_count == count($sales_list))
				{	
					$this->showSuccessMessage();							
				}
				else 
				{
					
					$this->updateMessageAssign($edit_data,$sales_list,$error_user_ary,$assign_sn);//更新指派記錄
					$msg = "下列人員請重新發送:<br>".implode("<br>", $error_user_ary);
					$this->showMessage($msg);
							
				}
			}
			else 
			{
				$this->showFailMessage();
			}

			
			redirect(bUrl("contentList"));	
        }	
	}

		 

	/**
	 * 更新指派記錄
	 */
	function updateMessageAssign($edit_data = array(),$sales_list = array(),$error_user_ary = array(),$assign_sn = 0)
	{
		if(count($sales_list)==0)
		{
			return $assign_sn;
		}
		
		
		$to_user_sn_ary = array();
		$to_user_id_ary = array();
		
		foreach ($sales_list as $key => $item) 
		{
			array_push($to_user_sn_ary,$item["sn"]);
			array_push($to_user_id_ary,$item["id"]."-".$item["name"]);
		}		
		
	
		if($assign_sn == 0)
		{
			$arr_data = array
			(      
				  "from_unit_sn" => $this->session->userdata('unit_sn')
				, "from_unit_name" => $this->session->userdata('unit_name')
				, "from_user_sn" => $this->session->userdata('user_sn')
				, "to_user_sn" => implode(",", $to_user_sn_ary)
				, "to_user_id" => implode(",", $to_user_id_ary)
				, "category_id" => tryGetData("category_id", $edit_data)
				, "title" => tryGetData("title", $edit_data)					
				, "msg_content" => tryGetData("msg_content", $edit_data)			
				, "updated" => date( "Y-m-d H:i:s" )
				, "created" => date( "Y-m-d H:i:s" )
			);			
			
			if(tryGetData("category_id", $edit_data) == "meeting")
			{
				$arr_data["meeting_date"] = tryGetData("meeting_date", $edit_data,NULL);
				
			}
			
			$assign_sn = $this->it_model->addData( "sys_message_assign" , $arr_data );
		}
		else 
		{
			$arr_data = array
			(				
				  "fail_user_id" =>	implode(",", $error_user_ary)		
				, "updated" => date( "Y-m-d H:i:s" )
			);		
			
			$condition = "sn ='".$assign_sn."'";
			$result = $this->it_model->updateData( "sys_message_assign" , $arr_data, $condition );
		}
		
		return $assign_sn;
		
	}



	/**
	 * 將行程更更新至行事曆
	 */
	 private function _updateCalendar($edit_data,$sales_sn,$sales_id)
	 {					
	 	$arr_data = array
		(
			  "cal_type" => "conference"
			, "user_sn" => $sales_sn
			, "user_id" => $sales_id
			, "title" => tryGetData("title", $edit_data)
			, "content" => tryGetData("msg_content", $edit_data)
			, "readyonly" => 1
			, "start_date" => tryGetData("meeting_date", $edit_data)		
			, "updated" => date( "Y-m-d H:i:s" )
			, "created" => date( "Y-m-d H:i:s" )
		);
		
		$assign_sn = $this->it_model->addData( "calendar" , $arr_data );		
	 }

	
	/**
	 * 驗證faqedit 欄位是否正確
	 */
	function _validateContent()
	{
		$category_id = $this->input->post("category_id",TRUE);
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'title', '名稱', 'required' );	
		$this->form_validation->set_rules( 'msg_content', '內容', 'required' );
		if($category_id == "meeting")
		{
			$this->form_validation->set_rules( 'meeting_date', '會議日期', 'required' );
		}
				
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}




	public function deleteContent()
	{
		$del_ary =array('sn'=> $this->input->post('del',TRUE));		
		
		if($del_ary!= FALSE && count($del_ary)>0)
		{
			$this->it_model->deleteDB( "web_menu_content",NULL,$del_ary );				
		}
		$this->showSuccessMessage();
		redirect(bUrl("contentList", FALSE));	
	}


	public function launchContent()
	{		
		$this->ajaxChangeStatus("web_menu_content","launch",$this->input->post("content_sn", TRUE));
	}


	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */