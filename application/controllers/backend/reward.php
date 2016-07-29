<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reward extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}



	/**
	 * category edit page
	 */
	public function index()
	{
		$unit_sn = $this->input->get('unit_sn');
		$year = tryGetData('year', $_GET, date('Y'));//$this->input->get('year');
		$month =  tryGetData('month', $_GET, date('m'));$this->input->get('month');
		$data["unit_sn"] = $unit_sn;
		$data["year"] = $year;
		$data["month"] = $month;
		
		// 若為單位秘書，取得自己管轄的單位別，其餘取得全單位
		if ( $this->session->userdata['unit_sn'] == 10 && $this->session->userdata['job_title'] == '秘書') {
			$secretary_user_id = $this->session->userdata('user_id');

			if (strtoupper($secretary_user_id) == 'SH0057') {
				// 佳惠負責《桃園業務四組》與《桃園業務四組開發》
				$my_unit_list_01 = $this->person_model->getMyOwnUnitList( 'SH0010' );
				$my_unit_list_02 = $this->person_model->getMyOwnUnitList( 'SH0039' );

				$my_unit_list = array_merge($my_unit_list_01, $my_unit_list_02);
			
			} elseif ( strtoupper($secretary_user_id) == 'CH0088') {
				// 怡萱負責新莊台北與悅陽
				//$my_unit_list_01 = $this->person_model->getMyOwnUnitList( 'CH0008' );
				//$my_unit_list = array_merge($my_unit_list_01, $my_unit_list_01, $my_unit_list_03);
				$my_unit_list_02 = $this->person_model->getMyOwnUnitList( 'CH0029' );
				$my_unit_list_03 = $this->person_model->getMyOwnUnitList( 'YU0002' );

				$my_unit_list = array_merge($my_unit_list_02, $my_unit_list_03);

			} elseif ( strtoupper($secretary_user_id) == 'SH0004') {
				// 姜姜特別負責桃園公司所有組別
				$my_unit_list = $this->person_model->getMyOwnUnitList( 'SH0001' );

				//$my_unit_list = array_merge($my_sales_list_01, $my_sales_list_02, $my_sales_list_03);

			} else {

				$belong_unit_array = $this->person_model->getBelongUnitbySecretaryId($secretary_user_id);
				$user_id = $belong_unit_array['manager_user_id'];
				$my_unit_list = $this->person_model->getMyOwnUnitList( $user_id );
			}


			$unit_list = array();
			foreach ($my_unit_list as $unit) {

				$belong_unit_sn = tryGetData('sn', $unit);
				$belong_unit_name = tryGetData('unit_name', $unit);
				$unit_list[$belong_unit_sn] = $belong_unit_name;
			}

		} else {
		
			// 單位別
			$unit_list = array(0=>'- 不拘 -');
			$cond = ' is_parent = 1 AND is_sales = 1 AND unit_name NOT IN ("董事長","行政單位","業務單位","會計部","董事長室","資訊室","大園公司") ';
			$tmp = $this->person_model->getUnitList($cond, null, null, array('is_sales'=>'ASC'));
			foreach ($tmp['data'] as $u) {
				$unit_list[$u['sn']] = $u['unit_name'];
			}
		}

		$data["unit_list"] = $unit_list;
		$sales_list = array();


		if ($unit_sn > 0 ) {

			$given_unit_name = $this->person_model->getUnitNamebySn($unit_sn);

			$result = array();
			$result = $this->person_model->getSalesList('s.launch=1 AND ( u.sn='.$unit_sn.' OR u.parent_sn='.$unit_sn.') ', NULL, NULL, array('u.sn'=>'asc', 's.level'=>'desc', 's.id'=>'asc') );
												//	$eecho = $given_unit_sn.' -- '.$given_unit_name.' --- ';
												//	echo $eecho.'<br>';
			if ($result['count'] > 0 ) {
				$sales_list = array();
//dprint($result['data']);
				foreach ($result['data'] as $user) {
					$sn = tryGetData('sn' , $user);

					$query = $this->it_model->listData('sales_reward' , ' user_sn='.$sn.' AND year='.$year.' AND month='.$month);

					if ( $query['count'] > 0 ) {
						$reward = $query['data'][0];

						$user['point_esp']	= tryGetData('point_esp', $reward, NULL);
						$user['point_gen']	= tryGetData('point_gen', $reward, NULL);
						$user['point_sub']	= tryGetData('point_sub', $reward, NULL);
						$user['point_demand']	= tryGetData('point_demand', $reward, NULL);
						$user['amount']	= tryGetData('amount', $reward, NULL);
						$user['updated']	=  tryGetData('updated', $reward, NULL);
						$user['updated_by']	=  tryGetData('updated_by', $reward, NULL);
						$user['updated_by_name']	=  tryGetData('updated_by_name', $reward, NULL);

					}
					$sales_list[] = $user;
					//}
				}
				
				$sales_list = array_unique($sales_list, SORT_REGULAR);

			}

		}

		$data['sales_list'] = $sales_list;
		$this->display("setting_reward_view",$data);
	}
	
	
	/**
	 * category edit page
	 */
	public function updateReward()
	{
		$this->load->model('deal_model');

		$edit_data = array();
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		$updated_by = tryGetData('updated_by', $edit_data);
		$updated_by_name = tryGetData('updated_by_name', $edit_data);
		$year = tryGetData('year', $edit_data);
		$month = tryGetData('month', $edit_data);

		$user_sn_array = tryGetData('user_sn', $edit_data, array());
		$user_id_array = tryGetData('user_id_array', $edit_data, array());
		$user_name_array = tryGetData('user_name_array', $edit_data, array());
		$point_esp_array = tryGetData('point_esp_array', $edit_data, array());
		$point_gen_array = tryGetData('point_gen_array', $edit_data, array());
		$point_sub_array = tryGetData('point_sub_array', $edit_data, array());
		$point_demand_array = tryGetData('point_demand_array', $edit_data, array());
		$amount_array = tryGetData('amount_array', $edit_data, array());

		$now = date('Y-m-d H:i:s');
		$arr_data = array();
		$i = 0;

		foreach ($user_sn_array as $user_sn) {

			if ( isNotNull(tryGetData($user_sn, $point_esp_array, NULL))  
				 || isNotNull(tryGetData($user_sn, $point_gen_array, NULL)) 
				 || isNotNull(tryGetData($user_sn, $point_sub_array, NULL)) 
				 || isNotNull(tryGetData($user_sn, $point_demand_array, NULL)) 
				 || isNotNull(tryGetData($user_sn, $amount_array, NULL))  ) {

				//$user_sn_array = tryGetData($user_sn, $user_sn_array, NULL);
				$user_id = tryGetData($user_sn, $user_id_array, 0);
				$user_name = tryGetData($user_sn, $user_name_array, 0);
				$point_esp = tryGetData($user_sn, $point_esp_array, 0);
				$point_gen = tryGetData($user_sn, $point_gen_array, 0);
				$point_sub = tryGetData($user_sn, $point_sub_array, 0);
				$point_demand = tryGetData($user_sn, $point_demand_array, 0);
				$amount = tryGetData($user_sn, $amount_array, 0);

				$update_flag = true;
				
				// 檢查是否已經有設定過，若沒有可直接 update
				$current = $this->it_model->listData('sales_reward' , 'user_sn='.$user_sn.' AND year='.$year.' AND month='.$month );
				if ( $current['count'] > 0 ) {
					$current_data = $current['data'][0];

					$current_point_esp = tryGetData('point_esp', $current_data, 0);
					$current_point_gen = tryGetData('point_gen', $current_data, 0);
					$current_point_sub = tryGetData('point_sub', $current_data, 0);
					$current_point_demand = tryGetData('point_demand', $current_data, 0);
					$current_amount = tryGetData('amount', $current_data, 0);
					


					// 若有，再檢查是否有更動，如果沒更動就不須update
					if ( $current_point_esp == $point_esp && $current_point_gen == $point_gen
						 && $current_point_sub == $point_sub && $current_point_demand == $point_demand
						 && $current_amount == $amount) {

						$update_flag = false;
					}
				}

				if ($update_flag == true) {
					$arr_data = array(   'user_sn' => $user_sn
										, 'year' => $year
										, 'month' => $month
										, 'date' => $year.'-'.$month.'-01'
										, 'user_id' => $user_id
										, 'user_name' => $user_name
										, 'point_esp' => $point_esp
										, 'point_gen' => $point_gen
										, 'point_sub' => $point_sub
										, 'point_demand' => $point_demand
										, 'amount' => $amount
										, 'created' => $now
										, 'created_by' => $updated_by
										, 'created_by_name' => $updated_by_name
										, 'updated' => $now
										, 'updated_by' => $updated_by
										, 'updated_by_name' => $updated_by_name);

					$result = $this->deal_model->updateSalesReward($arr_data);
					if ( $result == true ) {
						$i++;
					}
				}
			}  
		}

		if ($i > 0) {
			$this->showSuccessMessage();
		}

		redirect( burl('index') );

	}


	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("index", "updateReward"));
	}



	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */