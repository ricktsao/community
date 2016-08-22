<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily_good extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->displayBanner(FALSE);  	
	}


	public function index()
	{
		$data = array();
		
		$daily_good_list = $this->c_model->GetList2( "daily_good" , "" ,TRUE, $this->per_page_rows , $this->page , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );

		$data["pager"] = $this->getPager($daily_good_list["count"],$this->page,$this->per_page_rows,"index");	
		
		$data["daily_good_list"] = $daily_good_list["data"];
		
		
		
		$this->displayHome("daily_good_list_view",$data);
	}
	
	
	public function detail()
	{
		$content_sn = $this->input->get('sn');
						
		if($content_sn == "")
		{
			redirect(fUrl("index"));	
		}
		
		$item_info = $this->c_model->GetList( "daily_good" , "sn =".$content_sn,TRUE);			

			
		if($item_info["count"]>0)
		{				
			img_show_list($item_info["data"],'img_filename',"daily_good");
			$data["item_info"] = $item_info["data"][0];			

			$this->displayHome("detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}
	
}

