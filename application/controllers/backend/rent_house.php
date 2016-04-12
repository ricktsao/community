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


	public function edit()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");		
		
		$sn = $this->input->get("sn", TRUE);
		$role = $this->input->get("role", TRUE);

		//權組list
		//---------------------------------------------------------------------------------------------------------------
		if ( $role == 'I') {
			$condi = ' AND title IN ("住戶", "管委會") AND title != "富網通" ';
		} else {
			$condi = ' AND title NOT IN ("住戶", "管委會") AND title != "富網通" ';
		}

		$group_list = $this->it_model->listData( "sys_user_group" , "launch = 1 ".$condi , NULL , NULL , array("sort"=>"asc","sn"=>"desc"));

		$data["group_list"] = count($group_list["data"]) > 0 ? $group_list["data"] : array();
		//---------------------------------------------------------------------------------------------------------------

		$sys_user_group = array();		
						
		if($sn == "")
		{
			$data["edit_data"] = array
			(
				'start_date' => date( "Y-m-d" ),
				'end_date' => date( "Y-m-d", strtotime("+1 month") ),
				'forever' => 1,
				'launch' => 1
			);
			
			$data["sys_user_group"] = $sys_user_group;
			$this->display("edit_view",$data);
		}
		else 
		{
			$result = $this->it_model->listData( "rent_house" , "sn =".$sn);
			
			if (count($result["data"]) > 0) {			
				$edit_data = $result["data"][0];
				
				$edit_data["start_date"] = $edit_data["start_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["start_date"] ) );
				$edit_data["end_date"] = $edit_data["end_date"]==NULL?"": date( "Y-m-d" , strtotime( tryGetData('end_date',$edit_data, '+1 month' ) ) );
				
						
				$sys_user_belong_group = $this->it_model->listData("sys_user_belong_group","sys_user_sn = ".$edit_data["sn"]." and launch = 1" );				
				foreach($sys_user_belong_group["data"] as $item)
				{
					array_push($sys_user_group,$item["sys_user_group_sn"]);	
				}
				
				//dprint($sys_user_group);
				$data["sys_user_group"] = $sys_user_group;
				$data['edit_data'] = $edit_data;
				$this->display("edit_view",$data);
			}
			else
			{
				redirect(bUrl("index"));	
			}
		}
	}


	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("contentList", "updateLandSummary"));
	}



	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */