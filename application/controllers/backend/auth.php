<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Backend_Controller 
{
	
	function __construct() 
	{
		parent::__construct();	
	}
		
	

	public function admin()
	{

		$condition = ""; 
		

		$unit_sn = $this->input->get('unit_sn');			
		if(isNotNull($unit_sn))
		{
			$condition .= " AND unit_sn = ".$unit_sn ;
		}
		$data["unit_sn"] = $unit_sn;	
		/*
		$query_unit = 'SELECT SQL_CALC_FOUND_ROWS distinct u.sn, u.unit_name, u.level, u.parent_sn '
					.'   FROM sys_user s LEFT JOIN unit u ON s.unit_sn = u.sn '
					.'  WHERE ( unit_sn IS NOT NULL ) '
					;
		$unit_list = $this->it_model->runSql( $query_unit , FALSE, FALSE , array("field(`unit_name`, '雄獅開發','資訊室','會計部','管理部','總管理處','董事長室','董事長')"=>"desc", "u.level"=>"asc", "u.sn"=>"asc") );
		*/

		$data["unit_list"] = array(); 


		// 指定客戶姓名
		$keyword = $this->input->get('keyword', NULL);
		$data['given_keyword'] = '';
		if(isNotNull($keyword)) {
			$data['given_keyword'] = $keyword;
			$condition .= " AND ( `id` like '%".$keyword."%' "
						."      OR `name` like '%".$keyword."%' "
						."      OR `building_id` like '%".$keyword."%' "
						."      OR `account` like '%".$keyword."%'  ) "
						;
		}

		$query = "select SQL_CALC_FOUND_ROWS s.* "
						."    FROM sys_user s " //left join unit u on s.unit_sn = u.sn
						."   where 1 ".$condition
						."   order by field(`role`, 'I', 'S', 'M', 'G') ASC, s.building_id, s.id, s.name "
						;

		$admin_list = $this->it_model->runSql( $query,  $this->per_page_rows , $this->page );

		$data["list"] = $admin_list["data"];
		
		//dprint($data);
		//取得分頁
		$data["pager"] = $this->getPager($admin_list["count"],$this->page,$this->per_page_rows,"admin");

		$this->display("admin_list_view",$data);
	}


	public function editAdmin()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");		
		
		$admin_sn = $this->input->get("sn", TRUE);
		$role = $this->input->get("role", TRUE);
		//權組list
		//---------------------------------------------------------------------------------------------------------------
		if ( $role == 'I') {
			$condi = ' AND title IN ("住戶", "管委會") AND title != "富網通" ';
		} else {
			$condi = ' AND title NOT IN ("住戶", "管委會") AND title != "富網通" ';
		}

		$group_list = $this->it_model->listData( "sys_user_group" , "launch = 1 ".$condi , NULL , NULL , array("sort"=>"asc","sn"=>"desc"));

		$data["group_list"] = count($group_list["data"]) > 0 ? $group_list["data"] : array();
		//---------------------------------------------------------------------------------------------------------------
		$sys_user_group = array();		
						
		if($admin_sn == "")
		{
			$data["edit_data"] = array
			(
				'start_date' => date( "Y-m-d" ),
				'forever' => 1,
				'launch' =>1
			);
			
			$data["sys_user_group"] = $sys_user_group;
			$data['role'] = $role;
			$this->display("admin_edit_view",$data);
		}
		else 
		{
			$admin_info = $this->it_model->listData( "sys_user" , "sn =".$admin_sn);
			
			if(count($admin_info["data"])>0)
			{				
				$data["edit_data"] =$admin_info["data"][0];
				
				$data["edit_data"]["start_date"] = $data["edit_data"]["start_date"]==NULL?"": date( "Y-m-d" , strtotime( $data["edit_data"]["start_date"] ) );
				$data["edit_data"]["end_date"] = $data["edit_data"]["end_date"]==NULL?"": date( "Y-m-d" , strtotime( $data["edit_data"]["end_date"] ) );
				
						
				$sys_user_belong_group = $this->it_model->listData("sys_user_belong_group","sys_user_sn = ".$data["edit_data"]["sn"]." and launch = 1" );				
				foreach($sys_user_belong_group["data"] as $item)
				{
					array_push($sys_user_group,$item["sys_user_group_sn"]);	
				}
				
				//dprint($sys_user_group);
				$data["sys_user_group"] = $sys_user_group;
				$data['role'] = $role;				
				$this->display("admin_edit_view",$data);
			}
			else
			{
				redirect(bUrl("admin"));	
			}
		}
	}
	
	public function updateAdmin()
	{
		$this->load->library('encrypt');
		
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		
		//dprint($edit_data);
		//exit;
				
		
		if ( ! $this->_validateAdmin())
		{
			//權組list
			//---------------------------------------------------------------------------------------------------------------		
			$group_list = $this->it_model->listData( "sys_user_group" , "launch = 1" , NULL , NULL , array("sort"=>"asc","sn"=>"desc"));		
			$data["group_list"] = count($group_list["data"])>0?$group_list["data"]:array();
			//---------------------------------------------------------------------------------------------------------------
					
						
			$data["edit_data"] = $edit_data;			
			
			$data["sys_user_group"] = array();
			
			
			$this->display("admin_edit_view",$data);
		}
        else 
        {	
        	$arr_data = array(				
        		//"email" =>$edit_data["email"]
				  "name"		=>	tryGetData("name", $edit_data)
				, "phone"		=>	tryGetData("phone", $edit_data)
				, "job_title"		=>	tryGetData("job_title", $edit_data)
				, "job_type"		=>	tryGetData("job_type", $edit_data)
				, "start_date"	=>	tryGetData("start_date", $edit_data, NULL)
				, "end_date"	=>	tryGetData("end_date", $edit_data, NULL)
				, "forever"		=>	tryGetData("forever", $edit_data, 0)
				, "launch"		=>	tryGetData("launch", $edit_data, 0)
				, "update_date" =>  date( "Y-m-d H:i:s" ) 				
			);        	
			
			if($edit_data["sn"] != FALSE)
			{
				$arr_return=$this->it_model->updateDB( "sys_user" , $arr_data, "sn =".$edit_data["sn"] );
				
				if($arr_return['success'])			
				{					
					$this->_updateWebAdminGroup($edit_data);
					$this->showSuccessMessage();					
				}
				else 
				{
					//$this->output->enable_profiler(TRUE);
					$this->showFailMessage();
				}
				
				redirect(bUrl("admin",TRUE,array("sn")));		
			}
			else 
			{
				$arr_data["id"] = $edit_data["id"];				
				$arr_data["password"] = prepPassword($edit_data["password"]);
				$arr_data["created"] = date( "Y-m-d H:i:s" ); 	
				
				$sys_user_sn = $this->it_model->addData( "sys_user" , $arr_data );
				//$this->logData("新增人員[".$arr_data["id"]."]");
				if($sys_user_sn > 0)
				{				
					$edit_data["sn"] = $sys_user_sn;
					$this->_updateWebAdminGroup($edit_data);
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}
				
				redirect(bUrl("admin",TRUE,array("sn")));		
			}
        }	
	}	
	
		
	/**
	 * 更新權限群組
	 */
	function _updateWebAdminGroup(&$edit_data)
	{					
		$group_sn_ary = tryGetData("group_sn", $edit_data,array());				
		$old_group_sn_ary = tryGetData("old_group_sn", $edit_data,array());	

		foreach ($group_sn_ary as $key => $group_sn) 
		{
				
			$arr_data = array
			(				
				"launch" => 1,				
				"update_date" => date( "Y-m-d H:i:s" )
			);			
			
			
			//與原先的群組相同-->不動做
			if(in_array($key_string, $relationship_old_ary))
			{
				
				//$result = $this->it_model->updateData( "sys_user_belong_group" , array('launch'=>1,'update_date'=>date( "Y-m-d H:i:s" ) ),"sys_user_sn ='".$sys_user_sn."' and sys_user_group_sn ='".$group_sn."'" );				
				//$condition = "customer_sn ='".tryGetData("customer_sn", $edit_data)."' AND user_sn='".$this->session->userdata('user_sn')."' AND relationship_cat_sn='".$relationship_cat_sn."' AND relationship_sn='".$relationship_sn."' AND relationship_people = '".$relationship_people."' ";
				//$result = $this->it_model->updateData( "sys_user_belong_group" , $arr_data, $condition );
			}
			else //新的群組-->新增
			{
				$arr_data["sys_user_group_sn"] = $group_sn;		
				$arr_data["sys_user_sn"] = $edit_data["sn"];	
				$result_sn = $this->it_model->addData( "sys_user_belong_group" , $arr_data );
			}
			
		}
		
					
		//需要刪除的群組(將launch設為0)
		$del_land_ary = array_diff($old_group_sn_ary,$group_sn_ary);		
		foreach ($del_land_ary as $key => $group_sn) 
		{			
			
			$arr_data = array
			(				
				"launch" => 0,				
				"update_date" => date( "Y-m-d H:i:s" )
			);		
			
			$condition = "sys_user_group_sn ='".$group_sn."' AND sys_user_sn='".$edit_data["sn"]."' ";
			$result = $this->it_model->updateData( "sys_user_belong_group" , $arr_data, $condition );
		}
	}	
		
	
	
		
		
		
		
		
		
	
		
	function _validateAdmin()
	{
		$forever = tryGetValue($this->input->post('forever',TRUE),0);
		$sn = tryGetValue($this->input->post('sn',TRUE),0);		
		
		
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');	
		
		if($sn==0)
		{
			$this->form_validation->set_rules('id', $this->lang->line("field_account"), 'trim|required|checkAdminAccountExist' );			
			$this->form_validation->set_rules('password', $this->lang->line("field_password"), 'trim|required|min_length[4]|max_length[10]' );
		}		
		if($forever!=1)
		{
			$this->form_validation->set_rules( 'end_date', $this->lang->line("field_end_date"), 'required' );	
		}

		//$this->form_validation->set_rules('email', $this->lang->line("field_email"), 'trim|required|valid_email|checkAdminEmailExist' );
		//$this->form_validation->set_rules( 'sys_user_group', $this->lang->line("field_admin_belong_group"), 'required' );
		$this->form_validation->set_rules( 'start_date', $this->lang->line("field_start_date"), 'required' );		

		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}






	public function deleteAdmin()
	{
		$del_ary =array('sn'=> $this->input->post('del',TRUE));		
		
		if($del_ary!= FALSE && count($del_ary)>0)
		{
			$this->it_model->deleteDB( "sys_user",NULL,$del_ary );				
		}
		$this->showSuccessMessage();
		redirect(bUrl("admin", FALSE));	
	}

	public function launchAdmin()
	{		
		$this->ajaxChangeStatus("sys_user","launch",$this->input->post("user_sn", TRUE));
	}

	
	
	
	
	public function generateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  
		$this->addTopMenu(array("admin","editAdmin","updateAdmin"));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */