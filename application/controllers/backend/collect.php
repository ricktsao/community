<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collect extends Backend_Controller 
{
	
	function __construct() 
	{
		parent::__construct();

	}

	public function index()
	{
		// 列出未有磁扣ID的住戶
		$condition = ' AND role = "I" AND del=0 AND ( `id` IS NULL OR TRIM(`id`) = "" ) ';

		$query_key = array();
		foreach( $_GET as $key => $value ) {
			$query_key[$key] = $this->input->get($key,TRUE);
		}

		$manager_title_value = $this->auth_model->getWebSetting('manager_title');
		if (isNotNull($manager_title_value)) {
			$manager_title_array = array_merge(array(0=>' -- '), explode(',', $manager_title_value));
		}
		$data['manager_title_array'] = $manager_title_array;

		$b_part_01 = tryGetData('b_part_01', $query_key, NULL);
		$b_part_02 = tryGetData('b_part_02', $query_key, NULL);
		$b_part_03 = tryGetData('b_part_03', $query_key, NULL);
		
		// 搜尋戶別
		$building_id = NULL;
		if (isNotNull($b_part_01) && $b_part_01 > 0) {
			$building_id = $b_part_01.'_';
		}
		if (isNotNull($b_part_01) && isNotNull($b_part_02) && $b_part_01 > 0 && $b_part_02 > 0) {
			$building_id .= $b_part_02.'_';
		}
		if (isNotNull($b_part_01) && isNotNull($b_part_02) && isNotNull($b_part_03) && $b_part_01 > 0 && $b_part_02 > 0 && $b_part_03 > 0) {
			$building_id .= $b_part_03;
		}
		if (isNotNull($building_id)) {
			$condition .= ' AND building_id like "'.$building_id.'%"' ;
		}

		// 指定客戶姓名
		$keyword = tryGetData('keyword', $query_key, NULL);	
		$data['given_keyword'] = '';
		if(isNotNull($keyword)) {
			$data['given_keyword'] = $keyword;
			$condition .= " AND ( `id` like '%".$keyword."%' "
						."      OR `name` like '%".$keyword."%' "
						."      OR `tel` like '".$keyword."%' " 
						."      OR `phone` like '".$keyword."%'  ) "
						;
		}

		$query = "select SQL_CALC_FOUND_ROWS s.* "
						."    FROM sys_user s " //left join unit u on s.unit_sn = u.sn
						."   where 1 ".$condition
						."   order by s.building_id, s.name "
						;

		$admin_list = $this->it_model->runSql( $query,  $this->per_page_rows , $this->page );
		//dprint( $admin_list["sql"]);
		$data["list"] = $admin_list["data"];
		
		//取得分頁
		$data["pager"] = $this->getPager($admin_list["count"],$this->page,$this->per_page_rows,"admin");


		$data['b_part_01'] = $b_part_01;
		$data['b_part_02'] = $b_part_02;
		$data['b_part_03'] = $b_part_03;

		// 戶別相關參數
		$data['building_part_01'] = $this->building_part_01;
		$data['building_part_02'] = $this->building_part_02;
		$data['building_part_03'] = $this->building_part_03;
		$data['building_part_01_array'] = $this->building_part_01_array;
		$data['building_part_02_array'] = $this->building_part_02_array;

		$this->display("user_list_view",$data);
	}




	/**
	*   匯出 json 
	*/

	public function exportJson()
	{	
		$fPath = "template/backend/card_id_manager/";
		$this->load->library('zip');

		$query = "select SQL_CALC_FOUND_ROWS s.* "
						."    FROM sys_user s "
						."   where role = 'I' "
						." AND id IS NULL "
						."   order by s.building_id, s.name "
						;

		$result = $this->it_model->runSql( $query,  NULL, NULL );

		$list = array();
		foreach ($result['data'] as $item) {

			$building_id = tryGetData('building_id', $item, NULL);
			if ( isNotNull($building_id) ) {
				$building_parts = building_id_to_text($building_id, true);
				if ( $building_parts !== false) {
					$mixed_sn = tryGetData('sn', $item);
					$mixed_sn = ($mixed_sn + 1911 ) * 3;

					$comm_id = tryGetData('comm_id', $item);
					$comm_name = $this->auth_model->getWebSetting('comm_name');
					$part_1_name = $this->auth_model->getWebSetting('building_part_01');
					$part_2_name = $this->auth_model->getWebSetting('building_part_02');
					$part_3_name = $this->auth_model->getWebSetting('building_part_03');

					$list[] = array( 'comm_id' => $comm_id
									, 'comm_name' => $comm_name
									, 'part_1_name' => $part_1_name
									, 'b_parts_1' => $building_parts[0]
									, 'part_2_name' => $part_2_name
									, 'b_parts_2' => $building_parts[1]
									, 'part_3_name' => $part_3_name
									, 'b_parts_3' => $building_parts[2]
									, 'sn' => $mixed_sn
									, 'name' => tryGetData('name', $item)
									, 'tel' => tryGetData('tel', $item)
									, 'phone' => tryGetData('phone', $item)
									, 'id' => ''
									, 'act_code' => tryGetData('act_code', $item)		// APP開通碼
									);
				}
			}
		}

		$fp = fopen($fPath.'d.js', 'w');
		fwrite($fp, "var member = ".json_encode($list));
		fclose($fp);

		$this->zip->read_dir($fPath,FALSE);
		$this->zip->download('id_manager.zip');

		
	}

	
	/**
	 * category edit page
	 */
	public function implodeJson()
	{
		$data[] = array();
		$edit_data[] = array();
		$data['edit_data'] = $edit_data;

		$this->display("import_form_view",$data);
	}
	
	
	public function updateImport()
	{
		set_time_limit(2000);//執行時間

		$edit_data = array();
		
		$config['upload_path'] = getcwd().'./upload/user/';
		$config['allowed_types'] = 'js';
        $config['max_size'] = '100000';

		$this->load->library('upload',$config);

		$message = '主機忙碌中，請重新上傳...';

		$count = 0;

		// 檢查上傳的檔案是否為 xls
		if ( ! $this->upload->do_upload("jsfile")) {

			$edit_data["error"] = $this->upload->display_errors();
			$data["edit_data"] = $edit_data;				

			//$this->display("import_form_view",$data);

		} else {


			$file_info = $this->upload->data();

			$fcontent = read_file($file_info['full_path']);
			$fcontent = mb_substr($fcontent, 12);

			$dataset = json_decode($fcontent, true);
			$i = 0;
			foreach ($dataset as $data) {
				$tmp_sn = tryGetData('sn', $data);
				$sn = $tmp_sn / 3 - 1911;
				$id = tryGetData('id', $data, random_string('numeric',12));

				$arr_data = array("id" => $id
								, "updated" => date('Y-m-d H:i:s')
								);

				$result = $this->it_model->updateData( "sys_user" , $arr_data
					, "sn =".$sn." AND ( id IS NUll OR trim(id) ='' ) AND comm_id='".$this->getCommId()."' and role='I' " );

				if ($result) {
					$count++;
				}
			}
			$data['message'] = '共計入庫 '.$count.' 筆住戶磁扣資訊';

			$this->display("import_result_view", $data);


		}
	}

	public function generateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  
		$this->addTopMenu(array("user","editUser","updateUser"));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
