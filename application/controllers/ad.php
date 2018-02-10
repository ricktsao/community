<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->displayBanner(FALSE);
                $this->getEdomaData();
	}

	public function index()
	{		
		$this->addCss("css/album.css");
		$this->addJs("js/masonry.pkgd.min.js");	

		$data = array();

		$ad_list = $this->c_model->GetList( "ad" , "" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc"));
                foreach ($ad_list['data'] as &$info) {                
                    if($info['is_edoma']==1) {
                        $info['img_filename'] = "http://edoma.acsite.org/edoma/upload/website/ad/{$info['img_filename']}";
                    } else {
                        $info['img_filename'] = isNotNull($info['img_filename'])?base_url()."upload/website/ad/{$info['img_filename']}":'';
                    }
                }
		//img_show_list($ad_list["data"],'img_filename',"ad");
		$data["ad_list"] = $ad_list["data"];
		
		$this->displayHome("ad_list_view",$data);
	}


}

