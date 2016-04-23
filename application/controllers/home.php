<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();

	}

	public function index()
	{
		$this->addCss("css/index.css");
		
		$this->addJs("js/jquery-2.2.3.min.js");
		$this->addJs("js/jquery.cycle2.min.js");
		$this->addJs("js/jquery.cycle2.carousel.min.js");
		$this->addJs("js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js");
		$this->addJs("js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5");		
		
		
		$data = array();
		
		//日行一善 
		//----------------------------------------------------------------------------
		$daily_good_list = $this->c_model->GetList2( "daily_good" , "" ,TRUE, 3 , 1 , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		$data["daily_good_list"] = $daily_good_list["data"];
		//----------------------------------------------------------------------------
		
		//社區公告
		//----------------------------------------------------------------------------
		$news_list = $this->c_model->GetList2( "news" , "" ,TRUE, 3 , 1 , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		$data["news_list"] = $news_list["data"];
		
		//dprint($news_list);
		//----------------------------------------------------------------------------
		
		//課程訊息 
		//--------------------------------------------------------------------
		$course_list = $this->c_model->GetList2( "course" , "" ,TRUE, 3 , 1 , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		
		$data["course_list"] = $course_list["data"];
		//--------------------------------------------------------------------	
		
		//相簿
		//-----------------------------------------
		$this->load->Model("album_model");			
		$data["album_list"] = $this->album_model->GetHomeAlbumList();


		//-----------------------------------------
		
		$this->display("homepage_view",$data);
	}		
}

