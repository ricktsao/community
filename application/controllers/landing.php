<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Landing extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();		
	}


	public function index()
	{
		
		$data = array();
		
		$this->load->view($this->config->item('frontend_name')."/landing_view",$data);		
	}

	
	
}

