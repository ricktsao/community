<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();

	}

	public function index()
	{
		$this->addCss("css/index.css");
		
		$this->addJs("js/jquery-2.2.3.min.js");
		$this->addJs("js/jquery.cycle2.min.js");
		$this->addJs("js/jquery.cycle2.carousel.min.js");
		$this->addJs("js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js");
		$this->addJs("js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5");		
		
		$data = array();
		
		$this->display("homepage_view",$data);
	}		
}

