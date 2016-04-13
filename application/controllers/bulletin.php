<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bulletin extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();

	}

	public function index()
	{		
		$data = array();
		
		$bulletin_list = $this->c_model->GetList2( "bulletin" , "" ,TRUE, $this->per_page_rows , $this->page , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );

		$data["bulletin_list"] = $bulletin_list["data"];
		
		$this->display("bulletin_list_view",$data);
	}		
}

