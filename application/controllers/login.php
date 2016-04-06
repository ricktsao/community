<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public $templateUrl;
	
	
	
	function __construct() 
	{
		parent::__construct();	  
		//$this->templateUrl=base_url().$this->config->item('template_frontend_path');
	}
	
	public function test()
	{		
		include 'crontab/CrontabManager.php';
		
		//use 'php\manager\crontab\CrontabManager';

	//	$crontab = new CrontabManager();
		//$crontab.listJobs();
	}
	
	public function index()
	{		
		//echo '<img src="http://27.147.4.239/phpjobscheduler/firepjs.php?return_image=1" border="0">';
		echo '<br>rick test OK';
	}	

	
	
	function test_sch()
	{
		$arr_data = array(
						  "memo"	=> $this->input->ip_address()
						, "updated"	=> date( "Y-m-d H:i:s" )
						);
			$this->it_model->addData( "test_sch" , $arr_data );
	}
	
}
