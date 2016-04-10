<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rent_House extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	
	/**
	 * faq list page
	 */
	public function index()
	{
		$given_keyword = '';
		$query = 'SELECT SQL_CALC_FOUND_ROWS *
					FROM rent_house
					WHERE ( 1 = 1 ) 
					';
		$dataset = $this->it_model->runSql( $query , NULL , NULL , array("sn"=>"desc","rent_price"=>"asc"));

		$data["dataset"] = count($dataset["data"]) > 0 ? $dataset["data"] : array();
		//---------------------------------------------------------------------------------------------------------------
		$data['given_keyword'] = $given_keyword;
		$this->display("index_view",$data);
	}


	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("contentList", "updateLandSummary"));
	}



	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */