<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily_good extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
	}


	public function index()
	{
		$data = array();
		
		$daily_good_list = $this->c_model->GetList2( "daily_good" , "" ,TRUE, $this->per_page_rows , $this->page , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );

		$data["daily_good_list"] = $daily_good_list["data"];
		
		$this->display("daily_good_list_view",$data);
	}
	
}

