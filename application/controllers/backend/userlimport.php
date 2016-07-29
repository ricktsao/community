<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userimport extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
		$this->load->model('deal_model');
	}
	

	/**
	 * Deal一覽表數據
	 */
	public function detail()
	{
		$sn = $this->input->get('sn');


		$role_array = array('B' => '買家', 'S' => '賣家', 'N' => '名義登記人');
		$data["role_array"] = $role_array;

		$deal_list = $this->it_model->listData("deal" ,'sn = '.$sn);
		$org_map = array();
		$deal_info = $deal_list["data"][0];

		$deal_sn = tryGetData('sn', $deal_info);

		$deal_lands_list = $this->it_model->listData("deal_lands" ,'deal_sn = '.$deal_sn);
		$deal_info['deal_land_list'] = $deal_lands_list['data'];

		$deal_customers_list = $this->it_model->listData("deal_customers" ,'deal_sn = '.$deal_sn, NULL, NULL, array('customer_role'=>'ASC') );
		$deal_info['deal_customer_list'] = $deal_customers_list['data'];

		$deal_sales_list = $this->it_model->listData("deal_sales" ,'deal_sn = '.$deal_sn, NULL, NULL, array('agent_role'=>'ASC') );
		$deal_info['deal_sales_list'] = $deal_sales_list['data'];

		// 查詢單位成交編號
		$seller_unit_deal_list = $this->deal_model->getUnitDealNoByDealSn($deal_sn, 'S');
		$deal_info['seller_unit_deal_list'] = $seller_unit_deal_list;

		$buyer_unit_deal_list = $this->deal_model->getUnitDealNoByDealSn($deal_sn, 'B');
		$deal_info['buyer_unit_deal_list'] = $buyer_unit_deal_list;

		$register_unit_deal_list = $this->deal_model->getUnitDealNoByDealSn($deal_sn, 'N');
		$deal_info['register_unit_deal_list'] = $register_unit_deal_list;

		$deal_info['total_deal_amount'] = number_format_clean($deal_info['total_deal_amount'],2);
		$deal_info['fake_commission'] = number_format_clean($deal_info['fake_commission'],2);
		$deal_info['total_commission'] = number_format_clean($deal_info['total_commission'],2);
		$deal_info['m_commission_01'] = number_format_clean($deal_info['m_commission_01'],2);
		$deal_info['m_commission_02'] = number_format_clean($deal_info['m_commission_02'],2);
		$deal_info['m_commission_03'] = number_format_clean($deal_info['m_commission_03'],2);
		$deal_info['m_commission_04'] = number_format_clean($deal_info['m_commission_04'],2);
		$deal_info['m_commission_05'] = number_format_clean($deal_info['m_commission_05'],2);
		$deal_info['m_commission_06'] = number_format_clean($deal_info['m_commission_06'],2);

		$data["edit_data"] = $deal_info;


		$this->display("content_view", $data);

	}




	
	/**
	 * 清除成交客資，重新上傳，主表的成交日期跟不會清
	 */
	public function resetDealSubInfo()
	{
		$deal_sn = $this->input->get('sn', true);
		$contract_no = $this->input->get('cno', true);

		if (isNotNull($contract_no) && $deal_sn > 0) {

			## 交易開始　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　
			$this->db->trans_begin();
			$this->db->trans_strict(FALSE);

			$update_01 = 'UPDATE deal '
						.'   SET deal_date=NULL, target_type=NULL, deal_type=NULL, sub_source=NULL, sub_source_alert=NULL '
						.'       , sub_source_user_id=NULL, sub_source_created=NULL '
						.' WHERE sn = '.$deal_sn.' AND contract_no="'.$contract_no.'" '
						;

			$this->db->query($update_01);

			if ( $this->db->affected_rows() > 0 ) {

				//dprint($this->db->last_query(). ' → '. $this->db->affected_rows() .'筆');

				$this->db->delete('deal_sales', array('deal_sn' => $deal_sn)); 
				//dprint($this->db->last_query(). ' → '. $this->db->affected_rows() .'筆');

				$this->db->delete('deal_lands', array('deal_sn' => $deal_sn)); 
				//dprint($this->db->last_query(). ' → '. $this->db->affected_rows() .'筆');

				$this->db->delete('deal_customers', array('deal_sn' => $deal_sn)); 
				//dprint($this->db->last_query(). ' → '. $this->db->affected_rows() .'筆');
			}


			if ( $this->db->trans_status() === FALSE) {

				$this->db->trans_rollback();
				$this->showFailMessage();

			} else {
				$this->db->trans_commit();
				$this->showSuccessMessage();

			}
			## 交易結束　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　－　

		}
		redirect(bUrl('listDeal'));

	}



	/**
	 * Deal一覽表數據
	 */
	public function listDeal()
	{
		$list = array();
		/* 改用 dataTable → ajax 取得資料
		$deal_list = $this->it_model->listData("deal" );
		$org_map = array();
		foreach ($deal_list["data"] as $item) 
		{
			$deal_sn = tryGetData('sn', $item);
			// 查詢單位成交編號
			$seller_unit_deal_list = $this->deal_model->getUnitDealNoByDealSn($deal_sn, 'S');
			$item['seller_unit_deal_list'] = $seller_unit_deal_list;

			$buyer_unit_deal_list = $this->deal_model->getUnitDealNoByDealSn($deal_sn, 'B');
			$item['buyer_unit_deal_list'] = $buyer_unit_deal_list;

			$register_unit_deal_list = $this->deal_model->getUnitDealNoByDealSn($deal_sn, 'N');
			$item['register_unit_deal_list'] = $register_unit_deal_list;

			$list[] = $item;
		}
		*/

		$data["list"] = $list;
		$this->display("list_view", $data);

	}


	/**
	 * Deal一覽表數據
	 */
	public function ajaxGetDealList()
	{	
		$list = array();

		$deal_list = $this->it_model->listData("deal", null, null, null, array('deal_date'=>'desc', 'contract_no'=>'asc'));

		$org_map = array();
		foreach ($deal_list["data"] as $item) 
		{
			$deal_sn = tryGetData('sn', $item);

			// 查詢單位成交編號
			$seller_unit_deal_list = '';
			$seller_unit_deal_result = $this->deal_model->getUnitDealNoByDealSn($deal_sn, 'S');
			if ( isNotNull($seller_unit_deal_result) ) {
				foreach ($seller_unit_deal_result as $seller_unit_deal_info) {
					$agent_role = tryGetData('agent_role', $seller_unit_deal_info);
					$unit_name = tryGetData('unit_name', $seller_unit_deal_info);
					$unit_deal_no = tryGetData('unit_deal_no', $seller_unit_deal_info);
					$seller_unit_deal_list .= $unit_name.' '.$unit_deal_no.'<br>';
				}
			} else {
				$seller_unit_deal_list = '-';
			}
			$item['seller_unit_deal_list'] = $seller_unit_deal_list;

			$buyer_unit_deal_list = '';
			$buyer_unit_deal_result = $this->deal_model->getUnitDealNoByDealSn($deal_sn, 'B');
			if ( isNotNull($buyer_unit_deal_result) ) {
				foreach ($buyer_unit_deal_result as $seller_unit_deal_info) {
					$agent_role = tryGetData('agent_role', $seller_unit_deal_info);
					$unit_name = tryGetData('unit_name', $seller_unit_deal_info);
					$unit_deal_no = tryGetData('unit_deal_no', $seller_unit_deal_info);
					$buyer_unit_deal_list .= $unit_name.' '.$unit_deal_no.'<br>';
				}
			} else {
				$buyer_unit_deal_list = '-';
			}
			$item['buyer_unit_deal_list'] = $buyer_unit_deal_list;

			//$register_unit_deal_list = $this->deal_model->getUnitDealNoByDealSn($deal_sn, 'N');
			//$item['register_unit_deal_list'] = $register_unit_deal_list;
			if ( isNotNull(tryGetData('total_deal_amount', $item, NULL)) ) {
				$item['total_deal_amount'] = '$ '.number_format_clean($item['total_deal_amount'],2);
			} else {
				$item['total_deal_amount'] = '';
			}

			$urls = '<a class="btn btn-minier btn-info" href="'.bUrl("detail",TRUE,NULL,array("sn"=>tryGetData('sn', $item))).'">
												<i class="icon-edit bigger-120"></i>檢視
											</a>';
			$urls .= '&nbsp;&nbsp;<a class="btn btn-minier btn-purple" href="'.bUrl("resetDealSubInfo",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "cno"=>tryGetData('contract_no', $item))).'">
												<i class="icon-edit bigger-120"></i>重設客資
											</a>';
			$item['urls'] = $urls;
			$list[] = $item;
		}

		$data["list"] = $list;


		$output_ary = array();
		$output_ary["data"] = $list;

		echo json_encode($output_ary, JSON_UNESCAPED_UNICODE);

		//$this->display("list_view",$data);

	}







	/**
	 * 匯入Ａ７配地街廓一覽表數據
	 */
	public function importA7()
	{	
					
		$edit_data[] = array();
		$data["edit_data"] = $edit_data;
		$this->display("import_a7_view",$data);
	}
	
	

	/**
	 * 匯入Ａ７配地街廓一覽表數據
	 */
	public function updateA7Import()
	{
		set_time_limit(2000);//執行時間
		$edit_data = array();
											
		$config['upload_path'] = getcwd().'./upload/tmp/';
		$config['allowed_types'] = 'xlsx';
        $config['max_size'] = '100000';

		$this->load->library('upload',$config);
		
		
		
		if ( ! $this->upload->do_upload("xlsfile"))
		{
			$edit_data["error"] = $this->upload->display_errors();
			$data["edit_data"] = $edit_data;				
			 
			
			$this->display("import_form_view",$data);
		}
		else
		{
			$file_info = $this->upload->data();
			
			//dprint($file_info);
			//exit;
			//echo $file_info["full_path"];
		
			$this->load->library('excel');
			
			
			
			//讀取excel資料
			//--------------------------------------------------------------------------------
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load(iconv("UTF-8", "big5",$file_info["full_path"]) );
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			
			//extract to a PHP readable array format
			foreach ($cell_collection as $cell) 
			{
			    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();	
			    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();


				// $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

				// 儲存格若為日期時間格式，須轉出日期
				$given_cell = $objPHPExcel->getActiveSheet()->getCell($cell);

				if (is_object($given_cell->getValue())) {
					$data_value= $given_cell->getValue()->getPlainText();

				} else {
					$data_value= $given_cell->getValue();

				}


				if (PHPExcel_Shared_Date::isDateTime($given_cell)) {
					$format = 'Y-m-d';
					$data_value = date($format, PHPExcel_Shared_Date::ExcelToPHP($data_value)); 
				}
				
			    //header will/should be in row 1 only. of course this can be modified to suit your need.
			    if ($row == 1) {			    	    	
			        $header[$row][$column] = $data_value;
			    } else {
			        $arr_data[$row][$column] = $data_value;
			    }
			}
			
			//send the data in an array format
			$xls_data['header'] = $header;
			$xls_data['values'] = $arr_data;
			
			//dprint($xls_data['values']);

			$mapping = array(
				'no' => '項次'
				, 'street_id' => '街廓編號'
				, 'street_total_m2' => '街廓總面積'
				, 'street_total_value' => '街廓總地價'
				, 'first_m2' => '第一宗 面積'
				, 'first_value' => '第一宗 評定地價'
				, 'first_right' => '第一宗 所需權利價值'
				, 'gen_m2' => '一般土地 總面積'
				, 'gen_value' => '一般土地 評定地價'
				, 'gen_total_right' => '一般土地 總地價'
				, 'gen_min_width' => '一般土地 最小分配寬度'
				, 'gen_min_m2' => '一般土地 最小分配面積'
				, 'gen_min_right' => '一般土地 最低所需權利價值'
				, 'gen_deep_m2' => '一般土地 街廓較深處 參考面積'
				, 'gen_deep_right' => '一般土地 街廓較深處 參考權值'
				, 'last_m2' => '最後宗土地 面積'
				, 'last_value' => '最後宗土地 評定地價'
				, 'last_right' => '最後宗土地 所需權利價值'
				, 'deep_01' => '深度 起'
				, 'deep_02' => '深度 末'
				, 'memo' => '備註'
				, 'flag' => '限制'
				);
			$tmp = array();
			$parsed_array = array();
			$i = 0;
			foreach ($xls_data['values'] as $key => $item) 
			{
				$i++;
				if ($i > 2) {
//			echo $key;	
//dprint($item);
if (isNull(tryGetData('C', $item, NULL)) ) continue;

				$tmp['no'] = tryGetData('A', $item);
				$tmp['street_id'] = tryGetData('C', $item);
				$tmp['street_total_m2'] = tryGetData('D', $item);
				$tmp['street_total_value'] = tryGetData('F', $item);
				$tmp['first_m2'] = tryGetData('G', $item);
				$tmp['first_value'] = tryGetData('H', $item);
				$tmp['first_right'] = tryGetData('I', $item);
				$tmp['gen_m2'] = tryGetData('J', $item); 
				$tmp['gen_value'] = tryGetData('L', $item);
				$tmp['gen_total_right'] = tryGetData('M', $item);
				$tmp['gen_min_width'] = tryGetData('P', $item);
				$tmp['gen_min_m2'] = tryGetData('R', $item);
				$tmp['gen_min_right'] = tryGetData('U', $item);
				$tmp['gen_deep_m2'] = tryGetData('V', $item); 
				$tmp['gen_deep_right'] = tryGetData('W', $item);
				$tmp['last_m2'] = tryGetData('X', $item); 
				$tmp['last_value'] = tryGetData('Y', $item);
				$tmp['last_right'] = tryGetData('Z', $item);
				$tmp['deep_01'] = tryGetData('AA', $item);
				$tmp['deep_02'] = tryGetData('AB', $item);
				$tmp['memo'] = tryGetData('AH', $item);
				$parsed_array[] = $tmp;
				}
				//$parsed_array['flag'] = tryGetData('B', $item);

				/*
				$no = tryGetData('A', $item);
				$street_id = tryGetData('C', $item);
				$street_total_m2 = tryGetData('D', $item);
				$street_total_value = tryGetData('F', $item);
				$first_m2 = tryGetData('G', $item);
				$first_value = tryGetData('H', $item);
				$first_right = tryGetData('I', $item);
				$gen_m2 = tryGetData('J', $item); 
				$gen_value = tryGetData('L', $item);
				$gen_total_right = tryGetData('M', $item);
				$gen_min_width = tryGetData('P', $item);
				$gen_min_m2 = tryGetData('R', $item);
				$gen_min_right = tryGetData('U', $item);
				$gen_deep_m2 = tryGetData('V', $item); 
				$gen_deep_right = tryGetData('W', $item);
				$last_m2 = tryGetData('X', $item); 
				$last_value = tryGetData('Y', $item);
				$last_right = tryGetData('Z', $item);
				$deep_01 = tryGetData('AA', $item);
				$deep_02 = tryGetData('AB', $item);
				$memo = tryGetData('AH', $item);
				$all_flag = tryGetData('B', $item);
				*/
			}

			$sql = array();
			$insert = '';
			foreach ($parsed_array as $v) 
			{
				$insert = 'Insert into a7_setting SET ';
				foreach ($v as $k => $vv) 
				{	
					if ($k == 'no')		echo '<p>';
					if (isset($mapping[$k])) 
						echo $mapping[$k].' = ';
					else
						echo $k.' ?= ';
					
					echo $vv .'('.gettype($vv). ')<br>';

					if (is_string($vv) && $k != 'memo' && $k != 'street_id' ) {
//						$vv = NULL;
						$insert .= $k.' = NULL, ';
					} else {
						if ($k == 'memo' || $k == 'street_id') {
							$insert .= $k.' = "'.$vv.'", ';
						} else {
							$insert .= $k.' = '.$vv.', ';
						}
					}
					//$vv = (float) $vv;
//					$insert .= $k.' = "'.$vv.'", ';
					//$k = ;
				}
				$insert = substr($insert, 0, -2);
				$insert .= ';';
				$sql[] = $insert;
			}
//dprint($sql);
				foreach ($sql as $query) 
				{
					echo $query.'<br>';
				}
			die;

			$error = array();
			$i = 0;
			$j = 0;
			foreach ($xls_data['values'] as $key => $item) 
			{
				$name = tryGetData('A', $item);
				$name = big5_for_utf8($name);
				$addr = tryGetData('B', $item);
				$addr = big5_for_utf8($addr);
				$sales = tryGetData('F', $item);
				$sales = big5_for_utf8($sales);
				$unit = tryGetData('D', $item);
				$unit = big5_for_utf8($unit);
				$deal_date = tryGetData('G', $item);
				
				// 先查詢業務序號
				$result = $this->person_model->getSalesList('name="'.$sales.'" and u.unit_name like "'.$unit.'%"');
				if ($result['count'] < 1 ) {

					$error['wrong_sales'][] = $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.')';

				} else {

					$u_sn = $result['data'][0]['sn'];
					$u_id = $result['data'][0]['id'];
					
					$item['u_sn'] = $u_sn;
					$item['u_id'] = $u_id;

					// 查詢客戶序號
					$cust_sn = $this->person_model->getCustomerSnbyNamenAddr($name, $addr);

					if ($cust_sn == false) {

						$error['wrong_cust'][] =  $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.')';

					} else {
					
						$item['c_sn'] = $cust_sn;
/*
						$arr_data = array('user_sn' => tryGetData('u_sn', $item)
										, 'user_id' => tryGetData('u_id', $item)
										, 'tmp_user_name' => tryGetData('F', $item)
										, 'source' => 'uploaded'
										, 'customer_sn' => tryGetData('c_sn', $item)
										, 'customer_name' => tryGetData('A', $item)
										, 'tmp_address' => tryGetData('B', $item)
										, 'restricted' => 1
										, 'restrict_forever' => 1
										, 'status' => 1
										, 'created' => date('Y-m-d H:i:59')
										, 'updated' => date('Y-m-d H:i:59')
										, 'updated_user_id' => $this->session->userdata('user_id')
										);
*/
						$arr_data = array(tryGetData('u_sn', $item)
										, tryGetData('u_id', $item)
										, tryGetData('F', $item)
										, 'uploaded'
										, tryGetData('c_sn', $item)
										, tryGetData('A', $item)
										, tryGetData('B', $item)
										, 1
										, 1
										, 1
										, date('Y-m-d H:i:s')
										, date('Y-m-d H:i:s')
										, $this->session->userdata('user_id')
										, $this->session->userdata('user_name').'上傳列管名單 '.date('Y-m-d H:i:s')
										);
						$query = 'INSERT INTO `sales_customer` '
								.'       (`user_sn`, `user_id`, `tmp_user_name`, `source`, `customer_sn`, `customer_name`, `tmp_address` '
								.'        , `restricted`, `restrict_forever`, `status`, `created`, `updated`, `updated_user_id`, `memo`) '
								.'VALUES (?, ?, ?, ?, ?, ?, ? '
								.'        , ?, ?, ?, ?, ?, ?, ?) '
								.'    ON DUPLICATE KEY UPDATE  '
								.'       `source` = VALUES(`source`) '
								.'       , `customer_name`=VALUES(`customer_name`) '
								.'       , `restricted`=VALUES(`restricted`) '
								.'       , `restrict_forever` = VALUES(`restrict_forever`) '
								.'       , `updated` = VALUES(`updated`) '
								.'       , `updated_user_id` = VALUES(`updated_user_id`) '
								.'       , `memo` = VALUES(`memo`) '
								;


						$this->db->query($query, $arr_data);
						if ( $this->db->affected_rows() > 0 or $this->db->_error_message() == '') {
							$j++;
							$error['ok'][] =  $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.')';
						} else {
							//$error['wrong_db'] = ''
							$error['wrong_db'][] =  $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.') <br>錯誤訊息：'.$this->db->_error_message().'<br> 語法； '.$this->db->last_query();
						}
					}
				}
				$i++;
			}
			


			$wrong_sales_count = count(tryGetData('wrong_sales', $error, array()));
			$wrong_cust_count = count(tryGetData('wrong_cust', $error, array()));
			$wrong_db_count = count(tryGetData('wrong_db', $error, array()));
			$error_count = $wrong_sales_count + $wrong_cust_count + $wrong_db_count;

			$message = "<p>檔案【".$file_info["file_name"] .'】'
			.'共 <strong>'.$i.'</strong> 組列管名單，'
			.'成功配對 <strong>'.$j.'</strong> 組，失敗 <strong>'.$error_count.'</strong> 組';

			/*
			$ok_count = count(tryGetData('ok', $error, array()));
			if ( $ok_count > 0 ) {
				$message .= '<p>成功配對的記錄如下：<br>';
				foreach ($error['ok'] as $perline){
					$message .= ' '.$perline.'<br>';
				}
			}
			$message = '- - - - - - - - - - - - - - - - - - - - - - - - ';
			*/

			if ( $wrong_sales_count > 0 ) {
				$message .= '<p>因 查無業務 而無法設定列管的記錄如下：<br>';
				foreach ($error['wrong_sales'] as $perline){
					$message .= ' '.$perline.'<br>';
				}
			}
				
			if ( $wrong_cust_count > 0 ) {
				$message .= '<p>因 查無客戶 而無法設定列管的記錄如下：<br>';
				foreach ($error['wrong_cust'] as $perline){
					$message .= ' '.$perline.'<br>';
				}
			}

			if ( $wrong_db_count > 0 ) {
				$message .= '<p>因 未知原因 而無法設定列管的記錄如下：<br>';
				foreach ($error['wrong_db'] as $perline){
					$message .= ' '.$perline.'<br>';
				}
			}


			logData("列管名單上傳 - ".$file_info["file_name"], 1);
			

			$template = $this->config->item('template','mail');
			$content = '<p>'.$this->session->userdata('user_name').' 於 '.date('Y-m-d H:i:s').' 上傳列管名單檔案<Br>'
						.'處理狀況如下：</p>'
						.$message
						;
			
			$content = sprintf($template, $content);
			send_email($this->session->userdata('user_email'),'【竹北置地】列管名單上傳通知信函', $content);
			send_email('claire.huang@chupei.com.tw','【竹北置地】列管名單上傳通知信函', $content);

			if ($error_count > 0) {
				$message .= '<br><br>請確實檢視您上傳的檔案資料；若無法解決，請洽詢資訊室程式組人員，謝謝。';
			}


			//刪除暫存檔
			//unlink( iconv("UTF-8", "big5",$file_info["full_path"]) );
			
			//$this->session->set_flashdata('backend_message', $message);
			//redirect(bUrl("editContent"));	

			$data['message'] = $message;
			$this->display("import_a7_result_view", $data);
		}
	}
	





	/**
	 * category edit page
	 */
	public function importContent()
	{
		$content_sn = $this->input->get('sn');

		$edit_data[] = array();
		$data["edit_data"] = $edit_data;
		$this->display("import_form_view",$data);
	}
	
	
	public function updateImport()
	{
		set_time_limit(2000);//執行時間

		$edit_data = array();
		
		$config['upload_path'] = getcwd().'./upload/deal/main/';
		$config['allowed_types'] = 'xlsx';
        $config['max_size'] = '100000';

		$this->load->library('upload',$config);

		$message = '主機忙碌中，請重新上傳...';

		$deals = array();
		$succeed_to_db = array();
		$faild_to_db = array();
		$error_deals = array();

		// 檢查上傳的檔案是否為 xls
		if ( ! $this->upload->do_upload("xlsfile")) {

			$edit_data["error"] = $this->upload->display_errors();
			$data["edit_data"] = $edit_data;				

			//$this->display("import_form_view",$data);

		} else {


			$file_info = $this->upload->data();
			$this->load->library('excel');

			## 讀取excel資料 - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load(iconv("UTF-8", "big5",$file_info["full_path"]) );
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

			$header = array();
			$arr_data = array();

			//extract to a PHP readable array format
			foreach ($cell_collection as $cell) 
			{
			    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();	
			    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();


				// $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

				// 儲存格若為日期時間格式，須轉出日期
				$given_cell = $objPHPExcel->getActiveSheet()->getCell($cell);
				$data_value= $given_cell->getValue();

				if (PHPExcel_Shared_Date::isDateTime($given_cell)) {
					$format = 'Y-m-d';
					$data_value = date($format, PHPExcel_Shared_Date::ExcelToPHP($data_value)); 
				}
				
			    //header will/should be in row 1 only. of course this can be modified to suit your need.
			    if ($row == 1) {			    	    	
			        $header[$row][$column] = $data_value;
			    } else {
			    			    	
			        $arr_data[$row][$column] = $data_value;
			    }
			}
			//send the data in an array format
			$xls_data['header'] = $header;
			$xls_data['values'] = $arr_data;
			## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			

			$error = array();
			$i = 0;
			$j = 0;			

			$parsed_result = array();
			$unit_sales = array();
			$deal_data = array();
			$errorr_msg  = '';

			mb_internal_encoding("UTF-8");

			foreach ($xls_data['values'] as $key => $item) 
			{
				if ($i < 1 ) {
					$i++;
					continue;
				}
				$i++;

				if (sizeof($item) < 8 || $i==sizeof($xls_data['values'] )) {
					break;
				}
				$handle = true;

				$contract_no = tryGetData('A', $item, null);
				$unit_name = tryGetData('B', $item, null);
				// 檢查若沒有［契約書編號］，必須要有［成交編號］ (for 桃園公司)
				if (isNull($contract_no)) {
					$unit_deal_no = tryGetData('K', $item, null);
					if ( isNull($unit_deal_no) ) {
						$errorr_msg .= '查無［契約書編號］及［成交編號］'.$i.' : '.$contract_no.' &'.$unit_deal_no;
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						//var_dump($unit_deal_no);
						//dprint('查無［契約書編號］及［成交編號］'.$i.' : '.$contract_no.' &'.$unit_deal_no);dprint($item);
					continue;
					} else {
						$contract_no = 'Z'.$unit_deal_no;
					}
				}

				$target_text = tryGetData('L', $item, null);
				$planing_region = tryGetData('M', $item, null);
				$city_town = tryGetData('N', $item, null);
				$section = tryGetData('O', $item, null);
				$section_sub = tryGetData('P', $item, null);
				$land_no_text = tryGetData('Q', $item, null);
				$belong_no_text = tryGetData('R', $item, null);
				$total_area_ping = tryGetData('S', $item, null);

				$deal_date = tryGetData('W', $item, null);
				$total_deal_amount = (int) tryGetData('V', $item, null);

//				$fake_commission = tryGetData('X', $item, null);
				if (isNotNull($total_deal_amount) ) {
					$fake_commission = $total_deal_amount * 0.06;
				} else {
					$fake_commission = null;
				}

				$seller_commission = tryGetData('Y', $item, null);
				$buyer_commission = tryGetData('Z', $item, null);

				$seller_commission_date = tryGetData('AA', $item, null);
				$buyer_commission_date = tryGetData('AB', $item, null);

				$m_commission_01 = tryGetData('AC', $item, null);
				$m_commission_02 = tryGetData('AD', $item, null);
				$m_commission_03 = tryGetData('AE', $item, null);
				$m_commission_04 = tryGetData('AF', $item, null);
				$m_commission_05 = tryGetData('AG', $item, null);
				$m_commission_06 = tryGetData('AH', $item, null);

				(int) $chk_total_commission = ($seller_commission + $buyer_commission) - ( $m_commission_01 + $m_commission_02 + $m_commission_03 + $m_commission_04 + $m_commission_05 + $m_commission_06 ) ;
				
				// AI 欄的實收傭金是公式，因此不抓取，由程式自動計算就好
				// (int) $total_commission = tryGetData('AI', $item, null);
				$total_commission = $chk_total_commission;

				$target_text = big5_for_utf8($target_text);
				if (isNotNull($planing_region)) $planing_region = big5_for_utf8($planing_region);
				if (isNotNull($city_town)) $city_town = big5_for_utf8($city_town);
				if (isNotNull($section)) $section = big5_for_utf8($section).'段';
				if (isNotNull($section_sub)) $section_sub = big5_for_utf8($section_sub).'小段';
				if (isNotNull($land_no_text)) $land_no_text = big5_for_utf8($land_no_text);
				if (isNotNull($belong_no_text)) $belong_no_text = big5_for_utf8($belong_no_text);

				if ( isNotNull($seller_commission_date ) && $seller_commission_date !== false ) {
					if ( strtotime($seller_commission_date) > strtotime($deal_date.' +5 year') ) {
						$error_deals[$contract_no][] = '［'.$contract_no.'］賣方最遲佣收日期 ('.$seller_commission_date.') 距成交日期 ('.$deal_date.') 過久，請確認';
						continue;
					}
				}
				if ( isNotNull($buyer_commission_date ) && $buyer_commission_date !== false ) {
					if ( strtotime($buyer_commission_date) > strtotime($deal_date.' +5 year') ) {
						$error_deals[$contract_no][] = '［'.$contract_no.'］買方最遲佣收日期 ('.$buyer_commission_date.') 距成交日期 ('.$deal_date.') 過久，請確認';
						continue;
					}
				}
				
				if ( isNull($total_commission) || $total_commission < 0 ) {
					$error_deals[$contract_no][] = '［'.$contract_no.'］實收佣金金額 ('.$chk_total_commission.') 有誤，請確認';
					continue;
				}
				
				/*
				＊＊＊明明上傳103.10~104.10的資料時表示，有些是合建或違約，成交金額就會為０，因此不檢查 ＊＊＊
				＊＊＊（但這種狀況會有實收佣金，故執行績效處就可以實收佣金計算績效）           ＊＊＊
				if ( isNull($total_deal_amount) || $total_deal_amount < 0 ) {  
					$error_deals[$contract_no][] = '［'.$contract_no.'］成交金額有誤，請確認'.$total_deal_amount;
					continue;
				}
				if ( $total_commission != $chk_total_commission ) {
					$error_deals[$contract_no][] = '［'.$contract_no.'］佣金核算有誤，請確認'. $total_commission .' != '.$chk_total_commission;
				}
				*/
				/*
				if ( isNull($total_area_ping) || $total_area_ping < 0 ) {
					$error_deals[$contract_no][] = '［'.$contract_no.'］成交面積有誤，請確認'.$total_area_ping;
					continue;
				}*/

				// 契約書編號　從１＋３碼改為１＋５碼
				if (mb_strlen($contract_no) <= 5 ) {
					$prefix = substr($contract_no,0,1);
					$tail = substr($contract_no,1,3);
					$tail = str_pad($tail, 5, "0", STR_PAD_LEFT);
					$contract_no = $prefix.$tail;
				}
				
				$deal_data = array('contract_no'	=> $contract_no
								,  'source'			=> 1
								,  'target_type'	=> null
								,  'deal_type'		=> null
								,  'deal_date'		=> $deal_date
								,  'target_text'	=> $target_text
								,  'planing_region'	=> $planing_region
								,  'city_town'		=> $city_town
								,  'section'		=> $section.$section_sub
								,  'land_no_text'	=> $land_no_text
								,  'belong_no_text'	=> $belong_no_text
								,  'total_area_ping'		=> $total_area_ping
								,  'total_deal_amount'		=> $total_deal_amount
								,  'fake_commission'		=> $fake_commission

								,  'seller_commission'		=> $seller_commission
								,  'buyer_commission'		=> $buyer_commission
								,  'seller_commission_date'	=> $seller_commission_date
								,  'buyer_commission_date'	=> $buyer_commission_date

								,  'm_commission_01'		=> $m_commission_01
								,  'm_commission_02'		=> $m_commission_02
								,  'm_commission_03'		=> $m_commission_03
								,  'm_commission_04'		=> $m_commission_04
								,  'm_commission_05'		=> $m_commission_05
								,  'm_commission_06'		=> $m_commission_06
								,  'total_commission'		=> $total_commission
								,  'main_source'			=> $file_info['file_name']
								,  'main_source_user_id'	=> $this->session->userdata('user_id')
								);
				$deals[$contract_no] = $deal_data;
			}

			/*********** TO DB  ********************************************************/

			if ( sizeof($deals) > 0) {

				## 將成交客資附表存入資料庫  - - - - - - - - - - - - - - - - - - - - - - - 
				foreach ($deals as $c_no=>$deal) {

					$result = $this->deal_model->insertDealviaUploadDealCustomers($deal);
					if ($result === false) {
						$faild_to_db[] = $c_no;
					} else {
						$succeed_to_db[] = $c_no;
					}
				}
				## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			}
			## 將處理結果匯集起來  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$message = '成交總表【<span style="color:#c00; font-weight:bold;">'.$file_info['file_name'].'</span>】處理結果如下：';
			$message .= '<p>成功入庫的契約書編號為：<span style="font-weight:bold; color: #c00">'.implode('、', $succeed_to_db).'</span></p>';
			
			if ( sizeof($error_deals) > 0) {
				$message .= '<hr>無法處理之契約編號及原因如下：';
				foreach($error_deals as $cont_no=>$reasons){
					$message .=  '<p><span style="color:#c00; font-weight:bold;">'.$cont_no.'</span><br>';
					$message .=  '<span style="color:#6a3500; font-size:12px">';
					$unique_reasons = array_unique($reasons);
					foreach ($unique_reasons as $reason){
						$message .=  $reason.'<br>';
					}
					$message .=  '</span>';
				}
			}

			
			## 寄送Email通知程式組   - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$content = '<p>上傳人員：'.$this->session->userdata('unit_name').' '.$this->session->userdata('user_name').'</p>';
			$content .= '<p>上傳時間：'.date('Y-m-d H:i:s').'</p>';
			$content .= '<div>'.$message.'</div>';
			send_email('claire.huang@chupei.com.tw','【竹北置地】成交總表上傳通知 '.date('Y年m月d日H點i分'), $content);
			## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		}

		$data['message'] = $message;

		//刪除暫存檔
		//unlink( iconv("UTF-8", "big5",$file_info["full_path"]) );
				
		$this->display("import_result_view", $data);


	}
	

	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("listDeal","importContent","updateImport"));
	}

}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */