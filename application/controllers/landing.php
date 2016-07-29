<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Landing extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();		
	}


	public function index()
	{
		$data = array();
		
		$setting_info = $this->loadWebSetting();		
		$data["comm_name"] =  tryGetData("comm_name",$setting_info);		

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
		
		$this->load->view($this->config->item('frontend_name')."/landing_view",$data);		
	}

	
	
}

