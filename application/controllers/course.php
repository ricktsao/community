<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->displayBanner(FALSE);  	
	}


	public function index()
	{
		$data = array();
		
		$course_list = $this->c_model->GetList2( "course" , "" ,TRUE, $this->per_page_rows , $this->page , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );

		$data["pager"] = $this->getPager($course_list["count"],$this->page,$this->per_page_rows,"index");	
		
		$data["course_list"] = $course_list["data"];
		
		
		
		$this->displayHome("course_list_view",$data);
	}
	
	
	public function detail()
	{
		$content_sn = $this->input->get('sn');
						
		if($content_sn == "")
		{
			redirect(fUrl("index"));	
		}
		
		$course_info = $this->c_model->GetList( "course" , "sn =".$content_sn,TRUE);			

			
		if($course_info["count"]>0)
		{				
			img_show_list($course_info["data"],'img_filename',"course");
			$data["course_info"] = $course_info["data"][0];			

			$this->displayHome("course_detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}
	
	
}

