<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userimport extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
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

				$building_id_1 = null;
				$b_id_1 = tryGetData('A', $item, null);
				$b_id_1 = (string) $b_id_1;

				if (isNotNull($b_id_1)) {
					$building_id_1 = array_search($b_id_1, $this->building_part_01_array);
					if ($building_id_1 === false) {
						$building_id_1 = null;
					}
				}

				$building_id_2 = null;
				$b_id_2 = tryGetData('B', $item, null);
				$b_id_2 = intval($b_id_2);
				if (isNotNull($b_id_2)) {
					$building_id_2 = array_search($b_id_2, $this->building_part_02_array);
					if ($building_id_2 === false) {
						$building_id_2 = null;
					}
				}

				$building_id_3 = tryGetData('C', $item, null);
				$building_id_3 = (int) $building_id_3;

/************/
				$name = tryGetData('D', $item, null);

				$parking_id = tryGetData('M', $item, null);
				$parking_id = tryGetData('N', $item, null);
				$parking_id = tryGetData('O', $item, null);

				$parking_id_1 = null;
				$p_id_1 = tryGetData('M', $item, null);

				if (isNotNull($p_id_1)) {
					$parking_id_1 = array_search($p_id_1, $this->parking_part_01_array);
					if ($parking_id_1 === false) {
						$parking_id_1 = null;
					}
				}

				$parking_id_2 = null;
				$p_id_2 = tryGetData('N', $item, null);
				if (isNotNull($p_id_2)) {
					$parking_id_2 = array_search($p_id_2, $this->parking_part_02_array);
					if ($parking_id_2 === false) {
						$parking_id_2 = null;
					}
				}

				$parking_id_3 = tryGetData('O', $item, null);
				$parking_id_3 = (int) $parking_id_3;
				$parking_sn = false;
				$parking_id = NULL;
				if (isNotNull($parking_id_1) && isNotNull($parking_id_2) && isNotNull($parking_id_3) ) {
					$parking_id = $parking_id_1.'_'.$parking_id_2.'_'.$parking_id_3;
					$parking_sn = $this->auth_model->getFreeParkingSn($parking_id);

					if ( $parking_sn === false ) {
						$parking_id_text = parking_id_to_text($parking_id);
						$errorr_msg .= '第'.$i.'列　住戶【'.$name.'】之車位別【'.$parking_id_text.'】找不到或已被使用，因此不予新增其所屬的車位，請另外設定'."\n";
						//continue;
					}

				}


/************/
				$gender = tryGetData('E', $item, null);
				$tel = tryGetData('F', $item, null);
				$phone = tryGetData('G', $item, null);
				$is_contact = tryGetData('H', $item, null);
				$is_owner = tryGetData('I', $item, null);
				$owner_addr = tryGetData('J', $item, null);
				$gas_right = tryGetData('K', $item, null);
				$voting_right = tryGetData('L', $item, null);
				$car_number = tryGetData('P', $item, null);


				if (isNull($building_id_1) || isNull($building_id_2) ) {
					
					$errorr_msg .= '第'.$i.'列　請確認 [棟別或門牌號] 及 [樓層]有填寫正確'."\n";
					continue;

				} elseif (isNull($building_id_1) || isNull($building_id_2) || isNull($building_id_3)
					|| isNull($name) || isNull($gender) ) {
					
					$errorr_msg .= '第'.$i.'列　請確認必填欄位都有確實填寫'."\n";
					continue;

				} else {

					// 緊急聯絡人標註
					if ( isNotNull($is_contact) ) {
						if ( strtoupper($is_contact) != 'Y' ) {
							$errorr_msg .= '第'.$i.'列　[緊急聯絡人] 請務必填寫 "Y" 或保留空白 #'.$is_contact."\n";
							continue;
						}
					}

					// 所有權人標註
					if ( isNotNull($is_owner) ) {
						if ( strtoupper($is_owner) != 'Y' ) {
							$errorr_msg .= '第'.$i.'列　[所有權人] 請務必填寫 "Y" 或保留空白 #'.$is_owner."\n";
							continue;
						}
					}				

					// 瓦斯表權限
					if ( isNotNull($gas_right) ) {
						if ( strtoupper($gas_right) != 'Y' ) {
							$errorr_msg .= '第'.$i.'列　[瓦斯表權限] 請務必填寫 "Y" 或保留空白 #'.$gas_right."\n";
							continue;
						}
					}

					// 投票權權限
					if ( isNotNull($voting_right) ) {
						if ( strtoupper($voting_right) != 'Y' ) {
							$errorr_msg .= '第'.$i.'列　[投票權權限] 請務必填寫 "Y" 或保留空白 #'.$voting_right."\n";
							continue;
						}
					}

					// 性別
					if ( isNotNull($gender) ) {
						if ( in_array($gender, array('男','女')) === false ) {
							$errorr_msg .= '第'.$i.'列　 [性別] 請務必填寫 "男" 或 "女"  #'.$gender."\n";
							continue;
						}
					}


					// 取得社區ＩＤ
					$comm_id = $this->auth_model->getWebSetting('comm_id');

					## DB step 1 -> sys_user
					$add_data = array( 'comm_id'	=> $comm_id
									,  'building_id'	=> $building_id_1.'_'.$building_id_2.'_'.$building_id_3
									,  'id'			=> 'none'
									,  'role'		=> 'I'
									,  'name'		=> $name
									,  'gender'		=> $gender=='男' ? 1 : 2
									,  'tel'		=> $tel
									,  'phone'		=> $phone
									,  'is_contact'	=> $is_contact=='Y' ? 1 : 0
									,  'is_owner'	=> $is_owner=='Y' ? 1 : 0
									,  'owner_addr'	=> $owner_addr
									,  'gas_right'	=> $gas_right=='Y' ? 1 : 0
									,  'voting_right'	=> $voting_right=='Y' ? 1 : 0
									,  'is_sync'	=> 0
									,  'launch'		=> 0
									,  'created'	=> $now
									,  'created_by'	=> $this->session->userdata('user_name')
									);

					$query = 'INSERT IGNORE INTO `sys_user` '
							.'       (`comm_id`, `building_id`, `id`, `role`, `name`, `gender` '
							.'       , `tel`, `phone`, `is_contact`, `is_owner`, `owner_addr` '
							.'       , `gas_right`, `voting_right`, is_sync, launch, `created`, `created_by`) '
							.'VALUES (?, ?, ?, ?, ?, ? '
							.'       , ?, ?, ?, ?, ? '
							.'       , ?, ?, ?, ?, ?, ?)'
							;
					$ins_res = $this->db->query($query, $add_data);
					$user_sn = $this->db->insert_id();

					if ($user_sn > 0 ) {
						$count++;

						if ($parking_sn > 0 ) {
							## DB step 2 -> user_parking
							$arr_data = array('comm_id'	=> $comm_id
											, 'parking_sn'	=> $parking_sn
											, 'user_sn'	=> $user_sn
											, 'person_sn'	=> 0
											, 'user_id'	=> 'none'
											, 'car_number'	=> $car_number
											, 'updated'	=> $now
											, 'updated_by'	=> $this->session->userdata('user_name')
											);

							$query = 'INSERT INTO `user_parking` '
									.'       (`comm_id`, `parking_sn`, `user_sn`, `person_sn` '
									.'        , `user_id`, `car_number`, `updated`, `updated_by`) '
									.'VALUES (?, ?, ?, ? '
									.'        , ?, ?, ?, ? ) '
									.'    ON DUPLICATE KEY UPDATE  '
									.'       `car_number` = VALUES(`car_number`) '
									.'       , `updated` = VALUES(`updated`) '
									.'       , `updated_by` = VALUES(`updated_by`) '
									;
							$this->db->query($query, $arr_data);
						}

					} else {
							$building_id = $building_id_1.'_'.$building_id_2.'_'.$building_id_3;
							$building_id_text = building_id_to_text($building_id);
							$errorr_msg .= '第'.$i.'列　住戶'.$name.'之戶別編號（'.$building_id_text.'）已存在，因此不予新增'."\n";
							continue;
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
		$this->addTopMenu(array("index","updateImport"));
	}

}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */