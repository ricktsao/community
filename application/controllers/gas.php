<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gas extends Frontend_Controller {

	
	function __construct() 
	{
		parent::__construct();
		$this->checkLogin();
		$this->displayBanner(FALSE);
	}


	public function index()
	{		
		$this->getAppData();//至server查詢有無app新增資料,並同步
		
		$data = array();
		$user_sn = $this->session->userdata('f_user_sn');
		$user_info = $this->it_model->listData("sys_user","sn='".$user_sn."'");
		if($user_info["count"]>0)
		{
			$user_info = $user_info["data"][0];			
		}
		else
		{
			$this->redirectHome();
		}
		
		if($user_info["gas_right"]==1)
		{
			//user data
			$user_info = $this->it_model->listData("sys_user","sn='".$this->session->userdata('f_user_sn')."'");
			if($user_info["count"]==0)
			{					
				redirect(fUrl("index"));	
			}
			$user_info = $user_info["data"][0];
			
			
			//本月瓦斯
			//-----------------------------------------------------------------------------------
			$now_condition = "building_id = '".$user_info["addr"]."' ";
			$now_condition.= "and year = '".date("Y")."' and month = '".date("m")."'";
			$this_mon_gas_info = $this->it_model->listData("gas",$now_condition,1,1);
			//dprint($this_mon_gas_info);
			
			if($this_mon_gas_info["count"]>0)
			{
				$this_mon_gas_info = $this_mon_gas_info["data"][0];
			}
			else
			{
				$this_mon_gas_info = array("year"=>date("Y"),"month"=>date("m"),"sn"=>0);
			}
			$data["this_mon_gas_info"] = $this_mon_gas_info;
			//-----------------------------------------------------------------------------------
			
			
			
			//上月瓦斯
			//-----------------------------------------------------------------------------------
			$last_date_year = date("Y", strtotime('-1 month'));
			$last_date_month = date("m", strtotime('-1 month'));
			
			$last_condition = "building_id = '".$user_info["addr"]."' ";
			$last_condition.= "and year = '".$last_date_year."' and month = '".$last_date_month."'";
			$last_mon_gas_info = $this->it_model->listData("gas",$last_condition,1,1);
			//dprint($last_mon_gas_info);
			
			if($last_mon_gas_info["count"]>0)
			{
				$last_mon_gas_info = $last_mon_gas_info["data"][0];
			}
			else
			{
				$last_mon_gas_info = array("year"=>$last_date_year,"month"=>$last_date_month,"sn"=>0);
			}
			//dprint($last_mon_gas_info);
			$data["last_mon_gas_info"] = $last_mon_gas_info;
			//-----------------------------------------------------------------------------------
			
			
			

			//歷史瓦斯紀錄
			//-----------------------------------------------------------------------------------
			$condition = "building_id = '".$user_info["addr"]."' ";
			$gas_list = $this->it_model->listData("gas",$condition,12,1,array("year"=>"desc","month"=>"desc"));
			$data["gas_list"] = $gas_list["data"];		
			//-----------------------------------------------------------------------------------
			
			//瓦斯公司資訊
			//-----------------------------------------------------------------------------------
			$gas_company_info = $this->c_model->GetList( "gas_company" );		
			if($gas_company_info["count"]>0)
			{		
				$gas_company_info = $gas_company_info["data"][0];
			}		
			else
			{
				$gas_company_info = array();
			}
			$data["gas_company_info"] = $gas_company_info;
			//-----------------------------------------------------------------------------------
			
			$this->display("gas_list_view",$data);
		}		
		else
		{
			
			$this->display("no_gas_right_view",array());
			//echo '無瓦斯權限!!';
		}
	}
	
	public function readGas()
	{				
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		$year = tryGetData("year",$edit_data);
		$month = tryGetData("month",$edit_data);
		$degress = tryGetData("degress",$edit_data);
		if ( ! is_numeric($degress)) 
		{
			$degress = NULL;
		}
		//dprint($edit_data);exit;
		if ( isNull($degress) || isNull($year) || isNull($month) ) 
		{
			redirect(fUrl("index"));	
		}
		else
		{

			$this->getBuildData();
			
			$user_info = $this->it_model->listData("sys_user","sn='".$this->session->userdata('f_user_sn')."'");
			if($user_info["count"]==0)
			{					
				redirect(fUrl("index"));	
			}
			$user_info = $user_info["data"][0];
			
			
			$update_data = array(
			"degress" => $degress,							
			"updated" => date( "Y-m-d H:i:s" ),
			"is_sync" => 0
			);
			
			//gas 的building_id = sys_user 的 addr
			$condition = "building_id = '".$user_info["addr"]."' AND year = '".$year."' AND month = '".$month."' ";
			$result = $this->it_model->updateData( "gas" , $update_data,$condition );					
			
			if($result === FALSE)
			{
				$build_text = building_id_to_text($this->session->userdata('f_building_id'));
				$build_text_ary = explode('&nbsp;&nbsp;',$build_text);
				
				
				$build_addr = tryGetData(0,$build_text_ary).'&nbsp;&nbsp;'.tryGetData(1,$build_text_ary).'&nbsp;&nbsp;門號'.$user_info["addr"];
				
				$update_data["comm_id"] = $this->getCommId();
				$update_data["building_id"] = $user_info["addr"];
				$update_data["building_text"] = $build_addr;
				$update_data["year"] = $year;
				$update_data["month"] = $month;
				$update_data["created"] = date( "Y-m-d H:i:s" );
				
				$content_sn = $this->it_model->addData( "gas" , $update_data );
				
				
				if($content_sn > 0)
				{				
					$update_data["sn"] = $content_sn;								
					$this->sync_gas_to_server($update_data);					
				}
				else 
				{
					//$this->showFailMessage();					
				}				
			}
			else
			{
				$condition = "building_id = '".$this->session->userdata('f_building_id')."' AND year = '".$year."' AND month = '".$month."' ";
				$gas_info = $this->it_model->listData("gas",$condition);
				if($gas_info["count"]>0)
				{
					$gas_info = $gas_info["data"][0];												
					$this->sync_gas_to_server($gas_info);
				}
				
				
					
			}
		}
		redirect(fUrl("index"));	
	}
	
	
	/**
	 * 查詢server上有無app新增的資料
	 **/
	public function getAppData()
	{
		$this->getBuildData();
		
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync/getAppGas";		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$json_data = curl_exec($ch);
		curl_close ($ch);
		
		$app_data_ary =  json_decode($json_data, true);
		
		if( ! is_array($app_data_ary))
		{
			$app_data_ary = array();
		}
		//dprint($app_data_ary);exit;
		
		
		foreach( $app_data_ary as $key => $server_info ) 
		{	
		
			$update_data = array(			
			"server_sn" => $server_info["sn"],			
			"degress" => $server_info["degress"],							
			"updated" => date( "Y-m-d H:i:s" ),
			"is_sync" => 0
			);
		
			$condition = "building_id = '".$server_info["building_id"]."' AND year = '".$server_info["year"]."' AND month = '".$server_info["month"]."' ";
			$result = $this->it_model->updateData( "gas" , $update_data,$condition );					
			
			if($result === FALSE)
			{
				$update_data["comm_id"] = $this->getCommId();
				$update_data["building_id"] =  $server_info["building_id"];
				$update_data["building_text"] = building_id_to_text($server_info["building_id"]);
				$update_data["year"] = $server_info["year"];
				$update_data["month"] = $server_info["month"];
				$update_data["created"] = date( "Y-m-d H:i:s" );
				
				$content_sn = $this->it_model->addData( "gas" , $update_data );
				
				
				if($content_sn > 0)
				{				
					$update_data["sn"] = $content_sn;								
											
					$this->sync_item_to_server($update_data,"updateServerGas","gas");				
				}
				else 
				{
					//$this->showFailMessage();					
				}				
			}
			else
			{
				$condition = "building_id = '".$this->session->userdata('f_building_id')."' AND year = '".$year."' AND month = '".$month."' ";
				$gas_info = $this->it_model->listData("gas",$condition);
				if($gas_info["count"]>0)
				{
					$gas_info = $gas_info["data"][0];		
					$this->sync_item_to_server($gas_info,"updateServerGas","gas");
				}					
			}
		
								
		}
		
		//echo '<meta charset="UTF-8">';
		//dprint($app_data_ary);
		
	}
	
	
	
	/**
	 * 同步至雲端server
	 */
	function sync_gas_to_server($post_data)
	{
		$url = $this->config->item("api_server_url")."sync/updateGas";
		
		//dprint($post_data);
		//exit;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$is_sync = curl_exec($ch);
		curl_close ($ch);
		
		
		//dprint($is_sync);
		//exit;
		
		//更新同步狀況
		//------------------------------------------------------------------------------
		if($is_sync != '1')
		{
			$is_sync = '0';
		}			
		
		$this->it_model->updateData( "gas" , array("is_sync"=>$is_sync,"updated"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
		//------------------------------------------------------------------------------
	}
	
	
	function getBuildData()
	{
		// 取得戶別相關參數
		$this->load->model('auth_model');
		$this->building_part_01 = $this->auth_model->getWebSetting('building_part_01');
		$building_part_01_value = $this->auth_model->getWebSetting('building_part_01_value');
		$this->building_part_02 = $this->auth_model->getWebSetting('building_part_02');
		$building_part_02_value = $this->auth_model->getWebSetting('building_part_02_value');
		$this->building_part_03 = $this->auth_model->getWebSetting('building_part_03');

		if (isNotNull($building_part_01_value)) {
			$this->building_part_01_array = array_merge(array(0=>' -- '), explode(',', $building_part_01_value));
		}

		if (isNotNull($building_part_02_value)) {
			$this->building_part_02_array = array_merge(array(0=>' -- '), explode(',', $building_part_02_value));
		}
	}
	
	
}

