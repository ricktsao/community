<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();

	}

	public function index()
	{		
		$data = array();
		
		$news_list = $this->c_model->GetList2( "news" , "" ,TRUE, $this->per_page_rows , $this->page , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );

		$data["pager"] = $this->getPager($news_list["count"],$this->page,$this->per_page_rows,"index");	
		
		$data["news_list"] = $news_list["data"];
		
		$this->display("news_list_view",$data);
	}		
}

