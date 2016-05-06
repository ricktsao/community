<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bulletin extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->displayBanner(FALSE);  	
	}

	public function index()
	{		
		$data = array();
		
		$bulletin_list = $this->c_model->GetList2( "bulletin" , "" ,TRUE, $this->per_page_rows , $this->page , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );

		$data["pager"] = $this->getPager($bulletin_list["count"],$this->page,$this->per_page_rows,"index");	
		
		$data["bulletin_list"] = $bulletin_list["data"];
		
		$this->display("bulletin_list_view",$data);
	}		
	
	
	public function detail()
	{
		$content_sn = $this->input->get('sn');
						
		if($content_sn == "")
		{
			redirect(fUrl("index"));	
		}
		
		$item_info = $this->c_model->GetList( "bulletin" , "sn =".$content_sn,TRUE);			

			
		if($item_info["count"]>0)
		{				
			img_show_list($item_info["data"],'img_filename',"bulletin");		
			
			$data["item_info"] = $item_info["data"][0];			

			$this->display("detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}
	
	
}

