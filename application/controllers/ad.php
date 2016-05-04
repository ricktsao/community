<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->displayBanner(FALSE);
	}

	public function index()
	{		
		$this->addCss("css/album.css");
		$this->addJs("js/masonry.pkgd.min.js");	

		$data = array();

		$ad_list = $this->c_model->GetList( "ad" , "" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc"));
		img_show_list($ad_list["data"],'img_filename',"ad");
		$data["ad_list"] = $ad_list["data"];
		
		$this->display("ad_list_view",$data);
	}


}

