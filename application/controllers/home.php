<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();

	}

	public function index()
	{
		$this->addCss("css/index.css");
		$this->addJs("js/jquery.cycle2.min.js");
		$this->addJs("js/jquery.cycle2.carousel.min.js");
		
		
		$data = array();
		
		$this->display("homepage_view",$data);
	}		
}

