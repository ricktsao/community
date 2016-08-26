<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->displayBanner(FALSE);  	
	}
	
	
	
	public function index()
	{
		$this->addCss("css/rent.css");
		$item_info = $this->c_model->GetList( "about");			

			
		if($item_info["count"]>0)
		{				
			img_show_list($item_info["data"],'img_filename',"about");
			
			$data["item_info"] = $item_info["data"][0];			
		}
		else 
		{
			$data["item_info"] = array();
		}
		
		
		
		## 既有照片list
		$photo_list = $this->it_model->listData( "web_setting_photo");
		$data["photo_list"] = $photo_list["data"];
		
		
		
		$this->displayHome("detail_view",$data);
	}
}

