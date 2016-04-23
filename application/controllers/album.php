<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Album extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();

		$this->load->Model("album_model");			

	}

	public function index()
	{		
		$data = array();
		
		$album_list = $this->it_model->listData( "album" , NULL, $this->per_page_rows , $this->page , array("start_date"=>'desc'));

		$data["pager"] = $this->getPager($album_list["count"],$this->page,$this->per_page_rows,"index");	
		
		$data["album_list"] = $album_list["data"];
		
		$this->display("album_list_view",$data);
	}

	public function album_detail($sn=null)
	{

		if(!isset($sn)){			
			header("location:".fUrl("index"));
			die();
		}
		$this->addCss("css/album.css");
		$this->addJs("js/masonry.pkgd.min.js");	

		$data = array();

		$photo = $this->album_model->GetPhoto($sn);

		$data['photo_list'] =  $photo;

		$this->display("album_photo_view",$data);

	}	
}

