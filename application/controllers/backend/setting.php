<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends Backend_Controller{
	
	function __construct() 
	{
		parent::__construct();
	}

	
	
	public function index()
	{	
		$setting_list = $this->it_model->listData("web_setting","launch = 1",NULL,NULL,array("sort"=>"asc","sn"=>"desc"));
		
		$data["setting_list"] = $setting_list["data"];

		
		$parking = $this->it_model->listData('parking');
		$parking_flag = false;
		if ( $parking['count'] > 0 ) {
			$parking_flag = true;
		}
		$data["parking_flag"] = $parking_flag;

		$users = $this->it_model->listData('sys_user', 'role="I"');
		$users_flag = false;
		if ( $users['count'] > 0 ) {
			$users_flag = true;
		}
		$data["users_flag"] = $users_flag;

		$this->display("setting_form_view",$data);
	}
	
	
	
	public function set_old()
	{	
		$this->sub_title = "設定";	
				
		$setting_info = $this->it_model->listData("sys_setting","sn =1");
		if(count($setting_info["data"])>0)
		{
			$data["edit_data"] = $setting_info["data"][0];				
			$this->display("setting_form_view",$data);
		}
		else
		{
			echo 'error';
		}
	}
	
	
	
	
	
	
	/**
	 * 更新setting
	 */
	public function updateSetting()
	{
		
		foreach( $_POST as $key => $value )
		{
			//$edit_data[$key] = $this->input->post($key,TRUE);	
			
			
			$value = $this->input->post($key,FALSE); 
			if ( in_array($key, array('manager_title','building_part_01_value','building_part_02_value','parking_part_01_value','parking_part_02_value','mail_box_type')) ) {
				if ( mb_substr($value, -1) == ',') {
					$value = mb_substr($value, 0, -1);
				}
			}

			$arr_data = array
			(	
				  "value" =>  $value
				, "update_date" => date( "Y-m-d H:i:s" )
			);

			$this->it_model->updateData( "web_setting" , $arr_data, "key ='".$key."'");
					
		}		
		
		
		
		
		$this->showSuccessMessage();
		
		
		redirect(bUrl("index"));		
 
	}
	

	/**
	 * 驗證setting edit 欄位是否正確
	 */
	function _validateSetting()
	{
	
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		

		$this->form_validation->set_rules( 'setting_id', "Setting ID", 'required|alpha_dash' );	
		$this->form_validation->set_rules( 'title', "單元名稱", 'required' );			
				
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}



	
	/**
	 * delete setting
	 */
	function deleteSetting()
	{
		$this->deleteItem("html_setting", "settingList");
	}

	/**
	 * launch setting
	 */
	function launchSetting()
	{
		$this->launchItem("html_setting", "settingList");
	}


	
	public function generateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  
		$this->addTopMenu("設定", array("set"));		
	}
}

/* End of file setting.php */