<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Landing extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();		
	}


	public function index()
	{
		//登出
		//---------------------------------------------
		$this->session->unset_userdata('f_user_name');
		$this->session->unset_userdata('f_user_sn');
		$this->session->unset_userdata('f_user_id');
		$this->session->unset_userdata('f_user_app_id');
		$this->session->unset_userdata('f_comm_id');
		$this->session->unset_userdata('f_building_id');
		//---------------------------------------------
		
		$data = array();
		
		$setting_info = $this->loadWebSetting();		
		$data["comm_name"] =  tryGetData("comm_name",$setting_info);		

		
		
		$img_list = $this->c_model->GetList( "landing" , "" ,FALSE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		img_show_list($img_list["data"],'img_filename',"landing");
		$img_list = $this->it_model->toMapValue($img_list["data"],"sn","img_filename");
		$data["img_list"] = $img_list;
		
		//dprint($img_list);
		/*
		$bg_img = base_url().'template/'.$this->config->item('frontend_name').'/images/cycle_title_bg.png';
		
		$bg_img_info = $this->c_model->GetList( "landing");				
		if(count($bg_img_info["data"])>0)
		{
			img_show_list($bg_img_info["data"],'img_filename',$this->router->fetch_class());						
			$bg_img_info = $bg_img_info["data"][0];
			if( isNotNull($bg_img_info["img_filename"]) )
			{
				$bg_img = $bg_img_info["img_filename"];
			}
			
			
		}
		$data["bg_img"] = $bg_img;
		*/
		
		
		$this->load->view($this->config->item('frontend_name')."/landing_view",$data);		
	}

	
	
}

