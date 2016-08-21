<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailmgr extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->displayBanner(FALSE);		
		$this->load->model('auth_model');
	}
	
	
	public function index()
	{
		$this->checkGuardLogin();
		$data = array();
		$this->displayHome("mail_index_view",$data);
	}
	
	//登錄郵件	
	public function reg()
	{
		$this->checkGuardLogin();		
		$condition = ' AND role = "I"';

		$query_key = array();
		foreach( $_GET as $key => $value ) {
			$query_key[$key] = $this->input->get($key,TRUE);
		}

		$b_part_01 = tryGetData('b_part_01', $query_key, NULL);
		$b_part_02 = tryGetData('b_part_02', $query_key, NULL);
		$b_part_03 = tryGetData('b_part_03', $query_key, NULL);
		
		// 搜尋戶別
		$building_id = NULL;
		if (isNotNull($b_part_01) && $b_part_01 > 0) {
			$building_id = $b_part_01.'_';
		}
		if (isNotNull($b_part_01) && isNotNull($b_part_02) && $b_part_01 > 0 && $b_part_02 > 0) {
			$building_id .= $b_part_02.'_';
		}
		if (isNotNull($b_part_01) && isNotNull($b_part_02) && isNotNull($b_part_03) && $b_part_01 > 0 && $b_part_02 > 0 && $b_part_03 > 0) {
			$building_id .= $b_part_03;
		}
		if (isNotNull($building_id)) {
			$condition .= ' AND building_id like "'.$building_id.'%"' ;
		}

		// 指定客戶姓名
		$keyword = tryGetData('keyword', $query_key, NULL);	
		$data['given_keyword'] = '';
		if(isNotNull($keyword)) {
			$data['given_keyword'] = $keyword;
			$condition .= " AND ( `id` like '%".$keyword."%' "
						."      OR `name` like '%".$keyword."%' "
						."      OR `tel` like '".$keyword."%' " 
						."      OR `phone` like '".$keyword."%'  ) "
						;
		}

		$headline = '所有住戶列表';
		if (isNotNull(tryGetData('qy', $query_key, NULL))) {
			if (tryGetData('qy', $query_key) == 'mgrs' ) {
				$condition .= ' AND is_manager = 1 ' ;
				$headline = '管委人員列表';
			}
			if (tryGetData('qy', $query_key) == 'cnts' ) {
				$condition .= ' AND is_contact = 1 ' ;
				$headline = '緊急聯絡人員列表';
			}
			if (tryGetData('qy', $query_key) == 'owns' ) {
				$condition .= ' AND is_owner = 1 ' ;
				$headline = '所有權人列表';
			}
		}
		$data['headline'] = $headline;

		$query = "select SQL_CALC_FOUND_ROWS s.* "
						."    FROM sys_user s " //left join unit u on s.unit_sn = u.sn
						."   where 1 ".$condition
						."   order by s.building_id, s.name "
						;

		$admin_list = $this->it_model->runSql( $query,  $this->per_page_rows , $this->page );		
		$data["list"] = $admin_list["data"];
		
		//取得分頁
		$data["pager"] = $this->getPager($admin_list["count"],$this->page,$this->per_page_rows,"index");
		

		$data['b_part_01'] = $b_part_01;
		$data['b_part_02'] = $b_part_02;
		$data['b_part_03'] = $b_part_03;

		$this->getBuildInfo($data);

		$this->display("reg_list_view",$data);
	}	
	
	function getBuildInfo(&$data)
	{
		// 取得戶別相關參數		
		$this->building_part_01 = $this->auth_model->getWebSetting('building_part_01');
		$building_part_01_value = $this->auth_model->getWebSetting('building_part_01_value');
		$this->building_part_02 = $this->auth_model->getWebSetting('building_part_02');
		$building_part_02_value = $this->auth_model->getWebSetting('building_part_02_value');
		$this->building_part_03 = $this->auth_model->getWebSetting('building_part_03');
		$addr_part_01 = $this->auth_model->getWebSetting('addr_part_01');
		$addr_part_02 = $this->auth_model->getWebSetting('addr_part_02');

		if (isNotNull($building_part_01_value)) {
			$this->building_part_01_array = array_merge(array(0=>' -- '), explode(',', $building_part_01_value));
		}

		if (isNotNull($building_part_02_value)) {
			$this->building_part_02_array = array_merge(array(0=>' -- '), explode(',', $building_part_02_value));
		}

		$this->addr_part_01_array = array_merge(array(0=>' -- '), explode(',', $addr_part_01));
		$this->addr_part_02_array = array_merge(array(0=>' -- '), explode(',', $addr_part_02));
		
		
		// 戶別相關參數
		$data['building_part_01'] = $this->building_part_01;
		$data['building_part_02'] = $this->building_part_02;
		$data['building_part_03'] = $this->building_part_03;
		$data['building_part_01_array'] = $this->building_part_01_array;
		$data['building_part_02_array'] = $this->building_part_02_array;
	}
	
	
	
	public function regMail()
	{
		
		$user_sn = $this->input->get('user_sn');
		$user_info = $this->it_model->listData("sys_user","sn='".$user_sn."'");	
		if($user_info["count"]==0)
		{
			redirect(fUrl("index"));	
		}
		$user_info = $user_info["data"][0];
		$data["user_info"] = $user_info;
		
		//郵件類型
		$mail_box_type = $this->auth_model->getWebSetting('mail_box_type');
		$mail_box_type_ary = explode(",",$mail_box_type);
		$data["mail_box_type_ary"] = $mail_box_type_ary;

		$this->display("reg_mail_form_view",$data);		
	}
	
	
	public function updateRegMail()
	{	
				
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		//郵件類型
		$mail_box_type = $this->auth_model->getWebSetting('mail_box_type');
		$mail_box_type_ary = explode(",",$mail_box_type);
		
		
		
		$update_data = array(
		"comm_id" => $this->getCommId(),
		"type" => tryGetData("type",$edit_data),
		"desc" => tryGetData("desc",$edit_data),
		"booked" => date( "Y-m-d H:i:s" ),
		"booker" => $this->session->userdata("user_sn"),
		"booker_id" => $this->session->userdata("user_sn"),
		"user_name" => tryGetData("user_name",$edit_data),
		"updated" => date( "Y-m-d H:i:s" )
		);
		
		$update_data["type_str"] = tryGetData(tryGetData("type",$edit_data), $mail_box_type_ary);
		
		
		
		$user_info = $this->it_model->listData("sys_user","sn='".tryGetData("user_sn",$edit_data)."'");
		if($user_info["count"]>0)
		{
			$user_info = $user_info["data"][0];
			$update_data["user_sn"] = $user_info["sn"];
			$update_data["user_app_id"] = $user_info["app_id"];
			$update_data["user_building_id"] = $user_info["building_id"];
		}
		
		
		
		$content_sn = $this->it_model->addData( "mailbox" , $update_data );
		if($content_sn > 0)
		{				
			//設定　代收編號　日期＋流水後號３碼
			//--------------------------------------------------
			$mail_no = str_pad($content_sn,10,'0',STR_PAD_LEFT);
			$mail_no = date("Ymd").substr($mail_no,7,3);
			$this->it_model->updateData( "mailbox" , array("no"=>$mail_no,"updated" => date( "Y-m-d H:i:s" )),"sn = ".$content_sn );					
			//--------------------------------------------------
			
			$update_data["sn"] = $content_sn;
			$update_data["no"] = $mail_no;
			
			$this->sync_item_to_server($update_data,"updateMailbox","mailbox");
			
			
			//$this->showSuccessMessage();							
		}
		else 
		{
			//$this->showFailMessage();					
		}
	
			
			
		redirect(fUrl("index"));	
        
	}
	
	
	
	
	public function user_keycode()
	{
				
		$this->display("keycode_view",array());
	}
	
	
	
	public function receiveList()
	{
		$keycode_id = $this->input->post('keycode');
		
		$user_info = $this->it_model->listData("sys_user","id ='".$keycode_id."'");
		if($user_info["count"]==0)
		{
			$this->showMessage("磁卡錯誤");	
			redirect(fUrl("user_keycode"));
		}
		
		//領收人
		$user_info = $user_info["data"][0];
		$data["user_info"] = $user_info;
	
		//郵件類型
		$mail_box_type = $this->auth_model->getWebSetting('mail_box_type');
		$mail_box_type_ary = explode(",",$mail_box_type);
		$data["mail_box_type_ary"] = $mail_box_type_ary;
		
		$build_id_ary = explode("_", tryGetData("building_id", $user_info));
		$build_id_str = "";
		if(count($build_id_ary)==3)
		{
			$build_id_str = $build_id_ary[0]."_".$build_id_ary[1]."_%";
		}
				
		$mailbox_list = $this->it_model->listData("mailbox","is_receive = 0 and user_building_id like '".$build_id_str."' ", NULL , NULL, array("booked"=>'desc'));				
		//dprint($mailbox_list);
		
		$data["mailbox_list"] = $mailbox_list["data"];
		
		$this->display("receive_list_view",$data);
	}
	
	
	
	
	public function updateMailbox()
	{			
		$receive_sn_ary = $this->input->post('is_receive',TRUE);		
		$mailbox_sn_ary = $this->input->post('mailbox_sn',TRUE);


		$receive_user_name = $this->input->post('receive_user_name',TRUE);
		$receive_user_sn = $this->input->post('receive_user_sn',TRUE);
		
		for ($i=0; $i < count($mailbox_sn_ary) ; $i++) 
		{
			if(in_array($mailbox_sn_ary[$i], $receive_sn_ary))
			{

				
				$update_data = array(
					"is_receive" => 1,
					"receive_user_name" => $receive_user_name,
					"receive_user_sn" => $receive_user_sn,
					"is_sync" => 0,
					"receive_agent_sn" => $this->session->userdata("user_sn"),
					"received" => date("Y-m-d H:i:s"),
					"updated" => date("Y-m-d H:i:s")
				);
				
				$result = $this->it_model->updateData( "mailbox" , $update_data,"sn ='".$mailbox_sn_ary[$i]."'" );
				if($result)
				{
					$mail_info = $this->it_model->listData("mailbox","sn ='".$mailbox_sn_ary[$i]."'");
					if($mail_info["count"]>0)
					{
						$mail_info =$mail_info["data"][0];
						$this->sync_item_to_server($mail_info,"updateMailbox","mailbox");
					}
										
				}
				
				
			}			
		}
		
		//$this->showSuccessMessage();
		redirect(fUrl("user_keycode", TRUE));	
	}
	
	
	public function log()
	{
		$mailbox_list = $this->it_model->listData("mailbox","", 500 , 1, array("booked"=>'desc'));
		$data["mailbox_list"] = $mailbox_list["data"];	
		
		
		$user_list = $this->it_model->listData("sys_user");
		$user_map =  $this->it_model->toMapValue($user_list["data"],"sn","name");
		$data["user_map"] = $user_map;
		
		
		
		//郵件類型
		$mail_box_type = $this->auth_model->getWebSetting('mail_box_type');
		$mail_box_type_ary = explode(",",$mail_box_type);
		$data["mail_box_type_ary"] = $mail_box_type_ary;
		
		$this->display("log_list_view",$data);
	}
	
	
	
	public function login()
	{
		$data = array();
		$edit_data["error_message"] = "";
		$data["edit_data"] = $edit_data;
			
		//$pre_url= tryGetData("HTTP_REFERER",$_SERVER);
		
		//echo $pre_url;
		
		
		
		$this->display("login_view",$data);
	}	

	
	
	function checkLogin()
	{
		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		$account = $this->input->post("account",TRUE);
		$pwd = $this->input->post("pwd",TRUE);
		
		
		$str_conditions = " account = ".$this->db->escape(strtolower($account))."
		AND	password = ".$this->db->escape(prepPassword($pwd))." AND	launch = 1
		";		
		
		$query = "SELECT SQL_CALC_FOUND_ROWS sys_user.* FROM sys_user WHERE role='M' AND ".$str_conditions	;
		
		//echo $query ;exit;		
				
				
		$user_info = $this->it_model->runSql( $query );
		
		if($user_info["count"] > 0)
		{
			$user_info = $user_info["data"][0];
			
				$this->load->Model("auth_model");


			//查詢所屬群組&所屬權限(後台權限)
			//------------------------------------------------------------------------------------------------------------------					
			$sys_user_groups = array();
			$sys_user_belong_group = $this->it_model->listData("sys_user_belong_group", "sys_user_sn = ".$user_info["sn"]." and launch = 1" );				
			foreach($sys_user_belong_group["data"] as $item)
			{
				array_push($sys_user_groups,$item["sys_user_group_sn"]);	
			}
			
			if(count($sys_user_groups)>0)
			{
				$guard_group_info = $this->it_model->listData("sys_user_group", "sn IN (".implode($sys_user_groups, ",").") AND id='guard'");
				if($guard_group_info["count"] > 0)
				{
					$this->session->set_userdata('guard_name', $user_info["name"]);
					$this->session->set_userdata('guard_sn', $user_info["sn"]);
					
					redirect(fUrl("index"));
				}
				else 
				{
					$edit_data["error_message"] = "磁卡不正確!!";
					$data["edit_data"] = $edit_data;
					
					$this->displayBanner(FALSE);
					$this->display("login_view",$data);
				}
			}
			else 
			{
				$edit_data["error_message"] = "磁卡不正確!!";
				$data["edit_data"] = $edit_data;
				
				$this->displayBanner(FALSE);
				$this->display("login_view",$data);
			}
		
		
			
		}
		else 
		{
			$edit_data["error_message"] = "磁卡不正確!!";
			$data["edit_data"] = $edit_data;
			
			$this->displayBanner(FALSE);
			$this->display("login_view",$data);
		}
		
	}



	function checkGuardLogin()
	{		
		
		if(
			$this->session->userdata("guard_name") !== FALSE 
			&& $this->session->userdata("guard_sn") !== FALSE 
		)
		{
			
		}
		else 
		{
			redirect(fUrl("login"));
		}
	}


	function generateCommId($length = 8) 
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}


	
}
