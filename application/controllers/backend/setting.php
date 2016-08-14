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

		
		## 既有照片list
		$photo_list = $this->it_model->listData( "web_setting_photo");
		$data["photo_list"] = $photo_list["data"];
		
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


	
	
	
	
	
	
	
	/**
	 * 照片上傳
	 */
	public function uploadPhoto()
	{
		$edit_data = array();
		foreach( $_POST as $key => $value ) 
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}		
	
		$config['upload_path'] = './upload/website/setting/';
		$config['allowed_types'] = 'jpg|png';
		
		
		$filename = date( "YmdHis" )."_".rand( 100000 , 999999 );	
		$config['file_name'] = $filename;
		$config['overwrite'] = false;
		
		
		//$config['max_size']	= '1000';
		//$config['max_width']  = '1200';
		//$config['max_height']  = '1000';
		$config['overwrite']  = true;

		$this->load->library('upload', $config);

		if (!is_dir('./upload/website/setting/')) 
		{
			mkdir('./upload/website/setting/', 0777, true);
		}
		
		

		if ( ! $this->upload->do_upload('img_filename'))
		{
			$error = array('error' => $this->upload->display_errors());

			$this->showFailMessage('圖片上傳失敗，請稍後再試　' .$error['error'] );
		} 
		else 
		{

			$upload = $this->upload->data();
			$img_filename = tryGetData('file_name', $upload);
			
			$arr_data = array(														  
							  'img_filename'			=>	$img_filename
							, 'title'				=>	tryGetData('title', $edit_data)
							, 'updated'				=>	date('Y-m-d H:i:s')
							, 'updated_by'			=>	$this->session->userdata('user_name')
							, 'created'				=>	date('Y-m-d H:i:s')
							);

			$photo_sn = $this->it_model->addData('web_setting_photo', $arr_data);
			if ( $this->db->affected_rows() > 0 or $this->db->_error_message() == '') 
			{				
				$this->showSuccessMessage('圖片上傳成功');
			} else {
				$this->showFailMessage('圖片上傳失敗，請稍後再試');
			}
		}

		redirect(bUrl("index"));
	}

	/**
	 * 刪除web_menu_photo照片
	 */
	function deletePhoto()
	{
		$del_array = $this->input->post("del",TRUE);
		if(count($del_array)>0)
		{			
			$content_sn = 0;
			foreach( $del_array as $item ) 
			{

				$tmp = explode('!@', $item);
				$sn = $tmp[0];				
				$filename = $tmp[1];

				unlink('./upload/website/setting/'.$filename);

				$del = $this->it_model->deleteData('web_setting_photo',  array('sn' => $sn));
				
				if ($del) 
				{			
					
				}
			}
			
			$this->pingConentPhoto($content_sn);
		}
		$this->showSuccessMessage('圖片刪除成功');


		redirect(bUrl("index"));
	}
	
	
	
	
	
	
	
	public function generateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  
		$this->addTopMenu("設定", array("set"));		
	}
}

/* End of file setting.php */