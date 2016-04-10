<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userimport extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
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
	public function index()
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
		
		$config['upload_path'] = getcwd().'./upload/user/';
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
			$count  = 0;

			mb_internal_encoding("UTF-8");
			$now = date('Y-m-d H:i:s');
			foreach ($xls_data['values'] as $key => $item) 
			{
				if ($i < 1 ) {
					$i++;
					continue;
				}
				$i++;

				if (sizeof($item) < 5 || $i>sizeof($xls_data['values'] )) {
					$errorr_msg .= '第'.$i.'列　請確認必填欄位都有確實填寫';
					continue;
				}
				$handle = true;


				$building_id_1 = tryGetData('A', $item, null);
				$building_id_2 = tryGetData('B', $item, null);
				$building_id_3 = tryGetData('C', $item, null);
				$name = tryGetData('D', $item, null);
				$gender = tryGetData('E', $item, null);
				$tel = tryGetData('F', $item, null);
				$phone = tryGetData('G', $item, null);
				$is_contact = tryGetData('H', $item, null);
				$is_owner = tryGetData('I', $item, null);
				$owner_addr = tryGetData('J', $item, null);
				$gas_right = tryGetData('K', $item, null);
				$voting_right = tryGetData('L', $item, null);
				$parking_id = tryGetData('M', $item, null);
				$car_number = tryGetData('N', $item, null);

				if (isNull($building_id_1) || isNull($building_id_2) || isNull($building_id_3)
					|| isNull($name) || isNull($gender) ) {
					
					$errorr_msg .= '第'.$i.'列　請確認必填欄位都有確實填寫'."\n";
					continue;
				} else {

					// 緊急聯絡人標註
					if ( isNotNull($is_contact) ) {
						if ( strtoupper($is_contact) != 'Y' ) {
							$errorr_msg .= '第'.$i.'列　緊急聯絡人請務必填寫 "Y" 或保留空白 #'.$is_contact."\n";
							continue;
						}
					}

					// 所有權人標註
					if ( isNotNull($is_owner) ) {
						if ( strtoupper($is_owner) != 'Y' ) {
							$errorr_msg .= '第'.$i.'列　所有權人請務必填寫 "Y" 或保留空白 #'.$is_owner."\n";
							continue;
						}
					}				

					// 瓦斯表權限
					if ( isNotNull($gas_right) ) {
						if ( strtoupper($gas_right) != 'Y' ) {
							$errorr_msg .= '第'.$i.'列　瓦斯表權限請務必填寫 "Y" 或保留空白 #'.$gas_right."\n";
							continue;
						}
					}

					// 投票權權限
					if ( isNotNull($voting_right) ) {
						if ( strtoupper($voting_right) != 'Y' ) {
							$errorr_msg .= '第'.$i.'列　投票權權限請務必填寫 "Y" 或保留空白 #'.$voting_right."\n";
							continue;
						}
					}

					// 性別
					if ( isNotNull($gender) ) {
						if ( in_array($gender, array('男','女')) === false ) {
							$errorr_msg .= '第'.$i.'列　 性別請務必填寫 "男" 或 "女"  #'.$gender."\n";
							continue;
						}
					}

					$building_id_1 = tryGetData('A', $item, null);
					$building_id_2 = tryGetData('B', $item, null);
					$building_id_3 = tryGetData('C', $item, null);
					$name = tryGetData('D', $item, null);
					$gender = tryGetData('E', $item, null);
					$tel = tryGetData('F', $item, null);
					$phone = tryGetData('G', $item, null);
					$is_contact = tryGetData('H', $item, null);
					$is_owner = tryGetData('I', $item, null);
					$owner_addr = tryGetData('J', $item, null);
					$gas_right = tryGetData('K', $item, null);
					$voting_right = tryGetData('L', $item, null);
					$parking_id = tryGetData('M', $item, null);
					$car_number = tryGetData('N', $item, null);



					## DB step 1 -> sys_user
					$add_data = array('building_id'	=> $building_id_1.'_'.$building_id_2.'_'.$building_id_3
									,  'id'			=> 'none'
									,  'role'		=> 'I'
									,  'name'	=> $name
									,  'gender'		=> $gender=='男' ? 1 : 2
									,  'tel'		=> $tel
									,  'phone'		=> $phone
									,  'is_contact'	=> $is_contact=='Y' ? 1 : 0
									,  'is_owner'	=> $is_owner=='Y' ? 1 : 0
									,  'owner_addr'	=> $owner_addr
									,  'gas_right'	=> $gas_right=='Y' ? 1 : 0
									,  'voting_right'	=> $voting_right=='Y' ? 1 : 0
									,  'created'	=> $now
									,  'created_by'	=> $this->session->userdata('user_name')
									);

					$query = 'INSERT IGNORE INTO `sys_user` '
							.'       (`building_id`, `id`, `role`, `name`, `gender` '
							.'       , `tel`, `phone`, `is_contact`, `is_owner`, `owner_addr` '
							.'       , `gas_right`, `voting_right`, `created`, `created_by`) '
							.'VALUES (?, ?, ?, ?, ? '
							.'       , ?, ?, ?, ?, ? '
							.'       , ?, ?, ?, ?)'
							;
					$this->db->query($query, $add_data);
					$user_sn = $this->db->insert_id();

					if ($user_sn > 0 ) {
						$count++;

						if ( isNotNull($parking_id) ) {
							## DB step 2 -> parking
							$arr_data = array('parking_id'	=> $parking_id
											, 'location'	=> '-'
											, 'status'	=> 1
											);
							$query = 'INSERT IGNORE INTO `parking` '
									.'       (`sn`, `parking_id`, `location`, `status`) '
									.'VALUES (NULL, ?, ?, ? ) '
									;
							$this->db->query($query, $arr_data);
							$parking_sn = $this->db->insert_id();

							## DB step 3 -> user_parking
							$arr_data = array('parking_sn'	=> $parking_sn
											, 'user_sn'	=> $user_sn
											, 'person_sn'	=> 0
											, 'user_id'	=> 'none'
											, 'car_number'	=> $car_number
											, 'updated'	=> $now
											, 'updated_by'	=> $this->session->userdata('user_name')
											);

							$query = 'INSERT INTO `user_parking` '
									.'       (`parking_sn`, `user_sn`, `person_sn` '
									.'        , `user_id`, `car_number`, `updated`, `updated_by`) '
									.'VALUES (?, ?, ? '
									.'        , ?, ?, ?, ? ) '
									.'    ON DUPLICATE KEY UPDATE  '
									.'       `car_number` = VALUES(`car_number`) '
									.'       , `updated` = VALUES(`updated`) '
									.'       , `updated_by` = VALUES(`updated_by`) '
									;
							$this->db->query($query, $arr_data);
						}
					}
				}
			}
 
			## 將處理結果匯集起來  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$message = '<p>住戶資料檔案【<span style="color:#c00; font-weight:bold;">'.$file_info['file_name'].'</span>】';
			$message .= '成功建立 '.$count.' 位住戶資料</span></p>';
			if (mb_strlen($errorr_msg) > 0) {
				$message .= '<p>無法處理的記錄如下：</p>';
				$message .= nl2br($errorr_msg);
			}

			/*
			## 寄送Email通知程式組   - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$content = '<p>上傳人員：'.$this->session->userdata('user_id').' '.$this->session->userdata('user_name').'</p>';
			$content .= '<p>上傳時間：'.date('Y-m-d H:i:s').'</p>';
			$content .= '<div>'.$message.'</div>';
			send_email('myinfo.huang@gmail.com','【富網通】ＯＯ社區賀物資料上傳通知 '.date('Y年m月d日H點i分'), $content);
			## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			*/
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