<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rent_house extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
	}


	public function index()
	{
		$this->addCss("css/rent.css");
		$data = array();
		/*
		$daily_good_list = $this->c_model->GetList2( "daily_good" , "" ,TRUE, $this->per_page_rows , $this->page , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );

		$data["pager"] = $this->getPager($daily_good_list["count"],$this->page,$this->per_page_rows,"index");	
		
		$data["daily_good_list"] = $daily_good_list["data"];
		*/
		$houses = array();
		$sn = tryGetData('sn', $_GET, NULL);

		
		$given_sale_type = tryGetData('given_sale_type', $_GET, NULL);

		$given_room = tryGetData('given_room', $_GET, NULL);

/*
		$comm_id = $this->getCommid();
        if ( isNotNull($sn) ) {
            $this->set_response([
                'status' => FALSE,
                'message' => '請指定社區'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        } else {
			$condition = 'comm_id="'.$comm_id.'" AND '.$this->it_model->getEffectedSQL('house_to_rent');
*/
			$condition = ' ';
			$condition = ' '. $this->it_model->getEffectedSQL('house_to_rent') ;
			if ( isNotNull($sn) ) {
				// If the sn parameter doesn't exist return all the rents
				$condition .= ' AND sn = '.$sn;
			}
			/*if ( isNotNull($given_sale_type) ) {
				// If the sn parameter doesn't exist return all the rents
				$condition .= ' AND sale_type = '.$given_sale_type;
			}
			if ( isNotNull($given_room) ) {
				// If the sn parameter doesn't exist return all the rents
				$condition .= ' AND room = '.$given_room;
			}*/

$this->per_page_rows = 1;
$this->page = 1;
			$result = $this->it_model->listData('house_to_rent', $condition, $this->per_page_rows , $this->page , array('sn'=>'desc'));
			
			// Check if the rents data store contains rents (in case the database result returns NULL)
			if ($result['count'] > 0) {

				//$rents = $result['data'];
				$config_electric_array = config_item('electric_array');
				$config_furniture_array = config_item('furniture_array');
				$config_gender_array2 = config_item('gender_array2');
				$config_parking_array = config_item('parking_array');
				$config_rent_sale_type_array = config_item('rent_sale_type_array');
				$config_house_type_array = config_item('house_type_array');
				$config_house_direction_array = config_item('house_direction_array');

				foreach ($result['data'] as $item) {

					//$gender_term = $item['gender_term'];
					//$item['gender_term'] = $config_gender_array2[$gender_term];

					// 可否開伙
					$flag_cooking = tryGetData('flag_cooking', $item, NULL);
					if ( isNotNull($flag_cooking) ) {

						if ($flag_cooking != 1) {
							$item['flag_cooking'] = "無";

						} else {
							$item['flag_cooking'] = "有";
						}
					}
					// 可否開伙
					$flag_pet = tryGetData('flag_pet', $item, NULL);
					if ( isNotNull($flag_pet) ) {

						if ($flag_pet != 1) {
							$item['flag_pet'] = "不可";

						} else {
							$item['flag_pet'] = "可";
						}
					}



					// 車位
					$flag_parking = tryGetData('flag_parking', $item, NULL);
					$item['flag_parking'] = tryGetData($flag_parking, $config_parking_array, NULL);

					// 型態
					$rent_type = tryGetData('rent_type', $item, NULL);
					$item['rent_type'] = tryGetData($rent_type, $config_rent_sale_type_array, NULL);

					// 物件類型
					$house_type = tryGetData('house_type', $item, NULL);
					$item['house_type'] = tryGetData($house_type, $config_house_type_array, NULL);

					/* 物件類型
					$direction = tryGetData('direction', $item, NULL);
					$item['direction'] = tryGetData($direction, $config_house_direction_array, NULL);
					*/
					// 家具
					$given_furni_ary = explode(',' , $item['furniture']);
					$furni_ary = array();
					foreach($config_furniture_array as $furni) {

						if ( in_array($furni['value'], $given_furni_ary) ) {
							$furni_ary[] = $furni['title'];
						}
					}
					$item['furniture'] = implode(',', $furni_ary);

					// 家電
					$given_ele_ary = explode(',' , $item['electric']);
					$ele_ary = array();
					foreach($config_electric_array as $ele) {

						if ( in_array($ele['value'], $given_ele_ary) ) {
							$ele_ary[] = $ele['title'];
						}
					}
					$item['electric'] = implode(',', $ele_ary);
					

					// 照片
					//$condition = 'comm_id="'.$comm_id.'" AND house_to_rent_sn='.$item['sn'];
					$condition = 'house_to_rent_sn='.$item['sn'];
					$phoresult = $this->it_model->listData('house_to_rent_photo', $condition);
					$photos = array();
					foreach ($phoresult['data'] as $photo) {
						$img = base_url('upload/website/house_to_rent/'.$item['comm_id'].'/'.$item['sn'].'/'.$photo['filename']);
						$photos[] = array('photo' => $img
										, 'title' => $photo['title'] );
					}
					$item['photos'] = $photos;
					$houses[] = $item;
				}
				// Set the response and exit
				//$this->response($rents, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

			} else {

				$msg = '找不到任何租屋資訊，請確認';
			}
		//}
		$data['houses'] = $houses;

		$data["pager"] = $this->getPager(sizeof($houses),$this->page,$this->per_page_rows,"index");

		if ( $result['count'] == 1 ) {
			$this->display("house_detail_view", $data);
		} else {
			$this->display("house_list_view", $data);
		}
	}
	
}

