<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class check_login extends IT_Controller {


	function __construct() 
	{
		parent::__construct();		
	}


	public function index()
	{
		if(checkUserLogin())
		{
			echo 'Y';
		}
		else 
		{
			echo 'N';
		}
	}
	
		
}

