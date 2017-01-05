<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cmsys extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->getEdomaData();
	}



	public function ajaxGetNews()
	{	
		$cycle_list = array();
		
		//社區公告
		//---------------------------------------------------------------
		$news_list = $this->c_model->GetList( "news" , "hot = 1" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		$news_list = $news_list["data"];
		foreach( $news_list as $key => $info ) 
		{
			$photo_list = $this->it_model->listData( "web_menu_photo" , "content_sn =".$info["sn"]);
			$photo_list = $photo_list["data"];
			
			foreach ($photo_list as $pkey => $photo) 
			{
				$photo_list[$pkey]["img_filename"] = base_url('upload/content_photo/'.$photo["content_sn"].'/'.$photo["img_filename"]);
				
			}			
			
			$news_list[$key]["photo_list"] = $photo_list;		
							
		}
		
		//img_show_list($news_list["data"],'img_filename',"news");
		//---------------------------------------------------------------
		
		//管委公告
		//---------------------------------------------------------------
		$bulletin_list = $this->c_model->GetList( "bulletin" , "hot = 1" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		$bulletin_list = $bulletin_list["data"];
		foreach( $bulletin_list as $key => $info ) 
		{
			$photo_list = $this->it_model->listData( "web_menu_photo" , "content_sn =".$info["sn"]);
			$photo_list = $photo_list["data"];
			
			foreach ($photo_list as $key => $photo) 
			{
				$photo_list[$key]["img_filename"] = base_url('upload/content_photo/'.$photo["content_sn"].'/'.$photo["img_filename"]);				
			}			
			
			$bulletin_list[$key]["photo_list"] = $photo_list;
				
		}
		//---------------------------------------------------------------
		
		$cycle_list = array_merge($news_list, $bulletin_list);	
		
		
		echo json_encode($cycle_list);
		
		
		//$data["list"] = $cycle_list;

		
		
		
		//$data["edit_data"] = array();
		//$data["templateUrl"] = $this->templateUrl;
		//$data['templateUrl'] = $this->config->item("template_frontend_path");
		
		
		//$this->load->view($this->config->item('frontend_name')."/cycle_view",$data);	
	}
	
	
	public function ajaxGetNewsItem()
	{	
		$cycle_list = array();
		
		//社區公告
		//---------------------------------------------------------------
		$news_list = $this->c_model->GetList( "news" , "hot = 1" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		$news_list = $news_list["data"];
		foreach( $news_list as $key => $info ) 
		{
			$photo_list = $this->it_model->listData( "web_menu_photo" , "content_sn =".$info["sn"]);
			$photo_list = $photo_list["data"];
			
			$tmp_ary = array();
			
			$page_url = frontendUrl("page","index/".$info["sn"]);
			array_push($tmp_ary,$page_url);
			foreach ($photo_list as $pkey => $photo) 
			{
				$photo_url = base_url('upload/content_photo/'.$photo["content_sn"].'/'.$photo["img_filename"]);
				//$photo_list[$pkey]["img_filename"] = base_url('upload/content_photo/'.$photo["content_sn"].'/'.$photo["img_filename"]);
				array_push($tmp_ary,$photo_url);
			}			
			
			//$news_list[$key]["photo_list"] = $photo_list;		
			array_push($cycle_list,$tmp_ary);				
		}
		
		//img_show_list($news_list["data"],'img_filename',"news");
		//---------------------------------------------------------------
		
		//管委公告
		//---------------------------------------------------------------
		$bulletin_list = $this->c_model->GetList( "bulletin" , "hot = 1" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		$bulletin_list = $bulletin_list["data"];
		
		$tmp_ary = array();
		
		$page_url = frontendUrl("page","index/".$info["sn"]);
		array_push($tmp_ary,$page_url);
			
		foreach( $bulletin_list as $key => $info ) 
		{
			$photo_list = $this->it_model->listData( "web_menu_photo" , "content_sn =".$info["sn"]);
			$photo_list = $photo_list["data"];
			
			foreach ($photo_list as $key => $photo) 
			{
				$photo_url = base_url('upload/content_photo/'.$photo["content_sn"].'/'.$photo["img_filename"]);	
				//$photo_list[$key]["img_filename"] = base_url('upload/content_photo/'.$photo["content_sn"].'/'.$photo["img_filename"]);				
				array_push($tmp_ary,$photo_url);
			}			
			
			array_push($cycle_list,$tmp_ary);	
				
		}
		//---------------------------------------------------------------
		
		//$cycle_list = array_merge($news_list, $bulletin_list);	
		
		//dprint($cycle_list);
		echo json_encode($cycle_list);
		
		
		//$data["list"] = $cycle_list;

		
		
		
		//$data["edit_data"] = array();
		//$data["templateUrl"] = $this->templateUrl;
		//$data['templateUrl'] = $this->config->item("template_frontend_path");
		
		
		//$this->load->view($this->config->item('frontend_name')."/cycle_view",$data);	
	}
	
	public function index()
	{
		
	}
	
	public function informer()
	{
		$setting_info = $this->loadWebSetting();
		
		$data["comm_name"] =  tryGetData("comm_name",$setting_info);
		$data["cycle_sec"] = tryGetData("bulletin_cycle_sec",$setting_info,4)*1000;
		
		
		//底圖
		//------------------------------------------------------------------------------
		$bg_img = base_url().'template/'.$this->config->item('frontend_name').'/images/bg.jpg';
		$bg_img_info = $this->c_model->GetList( "cycle_img" , "hot = 1 AND launch =1" ,FALSE );		
		img_show_list($bg_img_info["data"],'img_filename',"cycle_img");
		if($bg_img_info["count"]>0)
		{
			$bg_img_info = $bg_img_info["data"][0];
			$bg_img = $bg_img_info["img_filename"];
		}
		$data['bg_img'] = $bg_img;
		//------------------------------------------------------------------------------
		
		
		//跑馬燈
		//------------------------------------------------------------------------------
		$marquee_list = $this->c_model->GetList( "marquee" , "" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		//dprint($marquee_list);
		$marquee_str = '';
        foreach ($marquee_list["data"] as $key => $marquee) 
        {
			$marquee_str .= $marquee["content"].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		$data["marquee_str"] = $marquee_str;
		//------------------------------------------------------------------------------
		
		$data["edit_data"] = array();
		$data['templateUrl'] = $this->config->item("template_frontend_path");
		
		
		$this->load->view($this->config->item('frontend_name')."/cycle_view",$data);		
	}

	
	
}

