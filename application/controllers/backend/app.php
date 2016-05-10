<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App extends Backend_Controller 
{
	
	function __construct() 
	{
		parent::__construct();

	}

	public function index()
	{
		$this->getAppData();//同步app登入資料
		
		$condition = ' AND role = "I" AND app_id IS NOT NULL ';

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



		$headline = '所有住戶列表';
		if (isNotNull(tryGetData('qy', $query_key, NULL))) {
			if (tryGetData('qy', $query_key) == 'mgrs' ) {
				$condition .= ' AND is_manager = 1 ' ;
				$headline = '管委人員列表';
			}
			if (tryGetData('qy', $query_key) == 'cnts' ) {
				$condition .= ' AND is_contact = 1 ' ;
				$headline = '緊急聯絡人員列表';
			}
			if (tryGetData('qy', $query_key) == 'owns' ) {
				$condition .= ' AND is_owner = 1 ' ;
				$headline = '所有權人列表';
			}
		}
		$data['headline'] = $headline;

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

		$this->display("index_view",$data);
	}


	/**
	 * 查詢server user 登入app資料
	 **/
	public function getAppData()
	{		
		
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync/getAppUser";		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$json_data = curl_exec($ch);
		curl_close ($ch);
		
		$app_data_ary =  json_decode($json_data, true);
		
		//dprint($app_data_ary );exit;
		
		if( ! is_array($app_data_ary))
		{
			$app_data_ary = array();
		}
		
		
		foreach( $app_data_ary as $key => $s_user_info ) 
		{		
		
		
			$update_data = array(			
			"app_id" => $s_user_info["app_id"],			
			"app_last_login_ip" => $s_user_info["app_last_login_ip"],			
			"app_last_login_time" => $s_user_info["app_last_login_time"],
			"app_login_time" => $s_user_info["app_login_time"],
			"app_use_cnt" => $s_user_info["app_use_cnt"],							
			"updated" => date( "Y-m-d H:i:s" )
			);
			
			$condition = "sn = '".$s_user_info["client_sn"]."' ";
			$result = $this->it_model->updateData( "sys_user" , $update_data,$condition );
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