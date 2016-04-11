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
		$condition = '';

		// 指定客戶姓名
		$keyword = $this->input->get('keyword', true);
		$given_keyword = '';
		if(isNotNull($keyword)) {
			$given_keyword = $keyword;
			$condition .= " AND ( `title` like '%".$keyword."%' "
						."      OR `addr` like '%".$keyword."%' "
						."      OR `living` like '%".$keyword."%' "
						."      OR `rent_price` = '".$keyword."'  ) "
						;
		}

		$room = $this->input->get('room', true);
		$given_room = '';
		if(isNotNull($room)) {
			$given_room = $room;
			$condition .= " AND `room` = '".$room."' ";
		}
		$livingroom = $this->input->get('livingroom', true);
		$given_livingroom = '';
		if(isNotNull($livingroom)) {
			$given_livingroom = $livingroom;
			$condition .= " AND `livingroom` = '".$livingroom."' ";
		}
		$bathroom = $this->input->get('bathroom', true);
		$given_bathroom = '';
		if(isNotNull($bathroom)) {
			$given_bathroom = $bathroom;
			$condition .= " AND `bathroom` = '".$bathroom."' ";
		}

		$query = 'SELECT SQL_CALC_FOUND_ROWS *
					FROM rent_house
					WHERE ( 1 = 1 ) '.$condition
				;
		$dataset = $this->it_model->runSql( $query , NULL , NULL , array("sn"=>"desc","rent_price"=>"asc"));

		$data["dataset"] = count($dataset["data"]) > 0 ? $dataset["data"] : array();
		//---------------------------------------------------------------------------------------------------------------
		$data['given_keyword'] = $given_keyword;
		$data['given_room'] = $given_room;
		$data['given_livingroom'] = $given_livingroom;
		$data['given_bathroom'] = $given_bathroom;


		$this->display("index_view",$data);
	}


	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("contentList", "updateLandSummary"));
	}



	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */