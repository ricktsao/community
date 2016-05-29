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
		img_show_list($news_list["data"],'img_filename',"news");
		//---------------------------------------------------------------
		
		//管委公告
		//---------------------------------------------------------------
		$bulletin_list = $this->c_model->GetList2( "bulletin" , "" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		img_show_list($bulletin_list["data"],'img_filename',"bulletin");
		//---------------------------------------------------------------
		
		$cycle_list = array_merge($news_list["data"], $bulletin_list["data"]);
	
		
		
		$data["list"] = $cycle_list;

		
		
		
		$data["edit_data"] = array();
		//$data["templateUrl"] = $this->templateUrl;
		$data['templateUrl'] = $this->config->item("template_frontend_path");
		
		
		$this->load->view($this->config->item('frontend_name')."/cycle_view",$data);		
	}

	
	
}

