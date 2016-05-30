<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cycle extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->getEdomaData();
	}

	public function index()
	{
		$setting_info = $this->loadWebSetting();
		$data["cycle_sec"] = tryGetData("bulletin_cycle_sec",$setting_info,4)*1000;
		
		$cycle_list = array();
		
		//社區公告
		//---------------------------------------------------------------
		$news_list = $this->c_model->GetList2( "news" , "" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		$news_list = $news_list["data"];
		foreach( $news_list as $key => $info ) 
		{
			$photo_list = $this->it_model->listData( "web_menu_photo" , "content_sn =".$info["sn"]);
			$news_list[$key]["photo_list"] = $photo_list["data"];						
		}
			
		
		//img_show_list($news_list["data"],'img_filename',"news");
		//---------------------------------------------------------------
		
		//管委公告
		//---------------------------------------------------------------
		$bulletin_list = $this->c_model->GetList2( "bulletin" , "" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		$bulletin_list = $bulletin_list["data"];
		foreach( $bulletin_list as $key => $info ) 
		{
			$photo_list = $this->it_model->listData( "web_menu_photo" , "content_sn =".$info["sn"]);
			$bulletin_list[$key]["photo_list"] = $photo_list["data"];						
		}
		//---------------------------------------------------------------
		
		$cycle_list = array_merge($news_list, $bulletin_list);
	
		
		
		$data["list"] = $cycle_list;

		
		
		
		$data["edit_data"] = array();
		//$data["templateUrl"] = $this->templateUrl;
		$data['templateUrl'] = $this->config->item("template_frontend_path");
		
		
		$this->load->view($this->config->item('frontend_name')."/cycle_view",$data);		
	}

	
	
}

