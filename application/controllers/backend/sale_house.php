<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sale_House extends Backend_Controller {
	
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
						."      OR `traffic` like '%".$keyword."%' "
						."      OR `desc` like '%".$keyword."%' "
						."      OR `unit_price` = '".$keyword."' "
						."      OR `total_price` = '".$keyword."'  ) "
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

		$balcony = $this->input->get('balcony', true);
		$given_balcony = '';
		if(isNotNull($balcony)) {
			$given_balcony = $balcony;
			$condition .= " AND balcony = '".$balcony."' ";
		}

		$query = 'SELECT SQL_CALC_FOUND_ROWS *
					FROM house_to_sale
					WHERE ( 1 = 1 ) '.$condition
				;

		$dataset = $this->it_model->runSql( $query , NULL , NULL , array("sn"=>"desc","total_price"=>"asc","unit_price"=>"asc"));

		$data["dataset"] = count($dataset["data"]) > 0 ? $dataset["data"] : array();
		//---------------------------------------------------------------------------------------------------------------
		$data['given_keyword'] = $given_keyword;
		$data['given_room'] = $given_room;
		$data['given_livingroom'] = $given_livingroom;
		$data['given_bathroom'] = $given_bathroom;
		$data['given_balcony'] = $given_balcony;

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
				'launch' => 1,
				//'rent_type' => array(),
				//'house_type' => array(),
				'furniture' => '',
				'electric' => ''
			);
			
			$data["sys_user_group"] = $sys_user_group;
			$this->display("edit_view",$data);
		}
		else 
		{
			$result = $this->it_model->listData( "house_to_sale" , "sn =".$sn);
			
			if (count($result["data"]) > 0) {			
				$edit_data = $result["data"][0];
				
				$edit_data["start_date"] = $edit_data["start_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["start_date"] ) );
				$edit_data["end_date"] = $edit_data["end_date"]==NULL?"": date( "Y-m-d" , strtotime( tryGetData('end_date',$edit_data, '+1 month' ) ) );
				
				
				$data['edit_data'] = $edit_data;
				$this->display("edit_view",$data);
			}
			else
			{
				redirect(bUrl("index"));	
			}
		}
	}



	public function update()
	{
		$this->load->library('encrypt');
		
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}

		if ( ! $this->_validateData() ) {
			//權組list
			//---------------------------------------------------------------------------------------------------------------		
			//$group_list = $this->it_model->listData( "sys_user_group" , "launch = 1" , NULL , NULL , array("sort"=>"asc","sn"=>"desc"));		
			//$data["group_list"] = count($group_list["data"])>0?$group_list["data"]:array();
			//---------------------------------------------------------------------------------------------------------------
			

			//$edit_data['rent_type'] = implode(',', tryGetData('rent_type', $edit_data, array()));
			//$edit_data['house_type'] = implode(',', tryGetData('house_type', $edit_data, array()));
			$edit_data['furniture'] = implode(',', tryGetData('furniture', $edit_data, array()));
			$edit_data['electric'] = implode(',', tryGetData('electric', $edit_data, array()));
			$data["edit_data"] = $edit_data;
			
			$data["sys_user_group"] = array();
			
			$this->display("edit_view",$data);
		}
        else 
        {

			/*
			dprint($edit_data);
Array
    [furniture] => Array
        (
            [0] => a
            [1] => b
            [2] => c
        )

    [electric] => Array
        (
            [0] => e
            [1] => f
            [2] => g
        )

)
			*/
        	$arr_data = array(
				 "sn"		=>	tryGetData("sn", $edit_data, NULL)
				, "rent_type"		=>	tryGetData("rent_type", $edit_data)
				, "house_type"		=>	tryGetData("house_type", $edit_data)
				, "furniture"		=>	implode(',', tryGetData("furniture", $edit_data))
				, "electric"		=>	implode(',', tryGetData("electric", $edit_data))
				, "title"		=>	tryGetData("title", $edit_data)
				, "name"		=>	tryGetData("name", $edit_data)
				, "phone"		=>	tryGetData("phone", $edit_data)
				, "room"		=>	tryGetData("room", $edit_data)
				, "livingroom"		=>	tryGetData("livingroom", $edit_data)
				, "bathroom"		=>	tryGetData("bathroom", $edit_data)
				, "locate_level"	=>	tryGetData("locate_level", $edit_data)
				, "total_level"		=>	tryGetData("total_level", $edit_data)
				, "area_ping"		=>	tryGetData("area_ping", $edit_data)
				, "rent_price"		=>	tryGetData("rent_price", $edit_data)
				, "deposit"			=>	tryGetData("deposit", $edit_data)
				, "addr"		=>	tryGetData("addr", $edit_data)
				, "move_in"		=>	tryGetData("move_in", $edit_data)
				, "rent_term"		=>	tryGetData("rent_term", $edit_data)
				, "current"		=>	tryGetData("current", $edit_data)
				, "usage"		=>	tryGetData("usage", $edit_data)
				, "meterial"		=>	tryGetData("meterial", $edit_data)

				, "flag_parking"		=>	tryGetData("flag_parking", $edit_data, 0)
				, "flag_cooking"		=>	tryGetData("flag_cooking", $edit_data, 0)
				, "flag_pet"		=>	tryGetData("flag_pet", $edit_data, 0)
				, "gender_term"		=>	tryGetData("gender_term", $edit_data)
				, "tenant_term"		=>	tryGetData("tenant_term", $edit_data)
				, "living"		=>	tryGetData("living", $edit_data)
				, "traffic"		=>	tryGetData("traffic", $edit_data)
				, "desc"		=>	tryGetData("desc", $edit_data)
				, "start_date"		=>	tryGetData("start_date", $edit_data)
				, "end_date"		=>	tryGetData("end_date", $edit_data)
				, "forever"		=>	tryGetData("forever", $edit_data, 0)
				, "launch"		=>	tryGetData("launch", $edit_data, 0)
				, "created" =>  date( "Y-m-d H:i:s" )
				, "updated" =>  date( "Y-m-d H:i:s" )
			);        	
			
			if($edit_data["sn"] != FALSE)
			{
				dprint($arr_data);
				$arr_return = $this->it_model->updateDB( "house_to_sale" , $arr_data, "sn =".$edit_data["sn"] );
				dprint($this->db->last_query());
				if($arr_return['success'])
				{
					$this->showSuccessMessage();					
				}
				else 
				{
					//$this->output->enable_profiler(TRUE);
					$this->showFailMessage();
				}
				
				redirect(bUrl("index",TRUE,array("sn")));		
			}
			else 
			{
				$arr_data["created"] = date( "Y-m-d H:i:s" ); 	
				
				$rent_sn = $this->it_model->addData( "house_to_sale" , $arr_data );
				//$this->logData("新增人員[".$arr_data["id"]."]");

				if($rent_sn > 0) {
					$edit_data["sn"] = $rent_sn;
					$this->showSuccessMessage();
				}
				else 
				{
					$this->showFailMessage();
				}
				
				redirect(bUrl("index",TRUE,array("sn")));
			}
        }
	}

/*

    [title] => 敦北生活圈新裝潢住宅華廈出租
    [rent_price] => 42000
    [deposit] => 兩個月
    [room] => 3
    [livingroom] => 1
    [bathroom] => 2
    [locate_level] => 10
    [total_level] => 12
    [addr] => 台北市松山區民族東路743號
    [tenant_term] => 
    [gender_term] => a
    [meterial] => 水泥磚牆
    [move_in] => 5月
    [rent_term] => 一年
    [area_ping] => 32
    [usage] => 住宅用
    [current] => 電梯大樓/整層住家
    [flag_cooking] => 1
    [flag_pet] => 0
    [flag_parking] => 1
    [living] => 近便利商店；傳統市場；百貨公司；公園綠地；學校；醫療機構；夜市
    [traffic] => 近敦化民權公車站； 松山機場捷運站
    [desc] => 
    [start_date] => 2016-04-01
    [end_date] => 
    [launch] => 1
*/

	function _validateData()
	{
		$sn = tryGetValue($this->input->post('sn',TRUE),0);
		$is_manager = tryGetValue($this->input->post('is_manager',TRUE), 0);
		$end_date = tryGetValue($this->input->post('end_date',TRUE), 0);
		$forever = tryGetValue($this->input->post('forever',TRUE), 0);

		$this->form_validation->set_message('checkAdminAccountExist', 'Error Message');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');	
		
		
		$forever = tryGetValue($this->input->post('forever',TRUE),0);
		if($forever!=1) {
			$this->form_validation->set_rules( 'end_date', $this->lang->line("field_end_date"), 'required' );	
		}
		$this->form_validation->set_rules( 'start_date', $this->lang->line("field_start_date"), 'required' );
		

		$this->form_validation->set_rules( 'rent_price', '租金 ', 'required|less_than[300000]|greater_than[1000]' );
		$this->form_validation->set_rules( 'deposit', '押金', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'area_ping', '面積', 'required|less_than[1000]|greater_than[0]' );
		$this->form_validation->set_rules( 'room', '格局-房', 'required|less_than[10]|greater_than[0]' );
		$this->form_validation->set_rules( 'livingroom', '格局-廳', 'required|less_than[10]|greater_than[0]' );
		$this->form_validation->set_rules( 'bathroom', '格局-衛', 'required|less_than[10]|greater_than[0]' );
		$this->form_validation->set_rules( 'locate_level', '位於幾樓', 'required|less_than[30]|greater_than[0]' );
		$this->form_validation->set_rules( 'total_level', '總樓層', 'required|less_than[30]|greater_than[0]' );


		$this->form_validation->set_rules( 'title', '租屋標題', 'required|max_length[50]' );
		$this->form_validation->set_rules( 'name', '聯絡人', 'required|max_length[50]' );
		$this->form_validation->set_rules( 'phone', '聯絡電話', 'required|max_length[50]' );
		$this->form_validation->set_rules( 'meterial', '隔間材質', 'max_length[50]' );
		$this->form_validation->set_rules( 'addr', '地址', 'required|max_length[100]' );
		$this->form_validation->set_rules( 'move_in', '可遷入日', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'rent_term', '最短租期', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'current', '型態/現況', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'desc', '特色說明', 'required|max_length[300]' );

		if ($is_manager == 1) {
			$this->form_validation->set_rules( 'manager_title', $this->lang->line("field_manager_title"), 'required|max_length[30]' );
			$this->form_validation->set_rules( 'start_date', $this->lang->line("field_start_date"), 'required');
			
		}

		//$this->form_validation->set_rules('email', $this->lang->line("field_email"), 'trim|required|valid_email|checkAdminEmailExist' );
		//$this->form_validation->set_rules( 'sys_user_group', $this->lang->line("field_admin_belong_group"), 'required' );
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}




	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("contentList", "updateLandSummary"));
	}



	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */