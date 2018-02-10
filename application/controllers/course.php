<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->displayBanner(FALSE);  	
                $this->getEdomaData();
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
                    $course_info = $course_info["data"][0];	
                    //dprint($course_info);
                    //exit;
                    
                    
                    if($course_info['is_edoma']==1) {                        
                        
                        $edoma_sn = tryGetData('img_filename2',$course_info);
                        $img_ary = explode(',', tryGetData('img_filename',$course_info));
                        $img_list = array();
                        foreach ($img_ary as $img) {
                            $img_url = "http://edoma.acsite.org/edoma/upload/content_photo/{$edoma_sn}/{$img}";
                            array_push($img_list, '<img  border="0" src="'.$img_url.'">');
                            
                        }
                        $course_info['img_filename'] = implode('<br>', $img_list);
                        //echo $course_info['img_filename'];exit;
                        
                        
                    } else {
                        if(isNotNull($course_info['img_filename'])) {
                            $img_url = base_url()."upload/website/course/{$course_info['img_filename']}";
                            $course_info['img_filename'] = '<img  border="0" src="'.$img_url.'">';    
                        }                        
                    }
                    
			
                     
			$data["course_info"] = $course_info;	
                        
                        

			$this->displayHome("course_detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}
	
	
}

