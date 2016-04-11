<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parking extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	
	/**
	 * faq list page
	 */
	public function index()
	{
		$query = 'SELECT SQL_CALC_FOUND_ROWS p.*, up.car_number, u.building_id, u.name, u.tel, u.phone
					FROM parking p left join user_parking up on p.sn = up.parking_sn
					left join sys_user u on up.user_sn = u.sn
					WHERE ( 1 = 1 ) 
					';
		$exist_parking_list = $this->it_model->runSql( $query , NULL , NULL , array("p.parking_id"=>"asc","sn"=>"desc"));

		$data["user_parking_list"] = count($exist_parking_list["data"]) > 0 ? $exist_parking_list["data"] : array();
		//---------------------------------------------------------------------------------------------------------------

			$this->display("index_view",$data);
	}



	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("contentList", "updateLandSummary"));
	}



	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */