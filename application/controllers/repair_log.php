<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair_log extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->checkLogin();
		$this->displayBanner(FALSE);
	}

	/**
	 * 查詢server上有無app新增的資料
	 **/
	public function getAppData()
	{
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync/getAppRepair";
		//dprint($post_data);exit;
		
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
		
		
		foreach( $app_data_ary as $key => $server_info ) 
		{			
			$repair_server_info = $this->it_model->listData("repair","server_sn='".$server_info["sn"]."'");
			if($repair_server_info["count"]==0)
			{
								
				$user_info = $this->it_model->listData("sys_user","app_id='".$server_info["app_id"]."'");
				if($user_info["count"]>0)
				{
					$user_info = $user_info["data"][0];
					
					$add_data = array(
					"comm_id" => $this->getCommId(),
					"server_sn" => $server_info["sn"],
					"user_sn" => $user_info["sn"],
					"user_name" => $user_info["name"],
					"app_id" => $user_info["app_id"], 
					"type" => $server_info["type"],
					"status" =>0,
					"content" => $server_info["content"],
					"updated" => date("Y-m-d H:i:s"),
					"created" => date("Y-m-d H:i:s")
					);
					$repair_sn = $this->it_model->addData( "repair" , $add_data );	
					if($repair_sn > 0)
					{
						$add_data["sn"] = $repair_sn;								
						$this->sync_item_to_server($add_data,"updateServerRepair","repair");
					}
				}				
				
			}
						
		}
		
		//echo '<meta charset="UTF-8">';
		//dprint($app_data_ary);
		
	}
	
	public function index()
	{
		$this->getAppData();//查詢server有無要同步的資料
		$data = array();
		
		$repair_list = $this->it_model->listData("repair","user_sn = '".$this->session->userdata("f_user_sn")."'",$this->per_page_rows , $this->page,array("created"=>"desc"));
		$data["pager"] = $this->getPager($repair_list["count"],$this->page,$this->per_page_rows,"index");
		$data["repair_list"] = $repair_list["data"];
		$this->display("repair_list_view",$data);
	}
	
	
	public function detail()
	{
		$this->getAppData();//查詢server有無要同步的資料
		$content_sn = $this->input->get('sn');
						
		if($content_sn == "")
		{
			redirect(fUrl("index"));	
		}
		
		$repair_info = $this->it_model->listData( "repair" , "user_sn = '".$this->session->userdata("f_user_sn")."' and sn =".$content_sn);			

			
		if($repair_info["count"]>0)
		{				
			$repair_info = $repair_info["data"][0];
			$data["repair_info"] = $repair_info;			
			
			//reply list
			//------------------------------------------------------------------
			$reply_list = $this->it_model->listData("repair_reply","repair_sn = '".$repair_info["sn"]."'",NULL , NULL,array("created"=>"asc"));
			$data["reply_list"] = $reply_list["data"];
			//------------------------------------------------------------------
			
			
			$this->display("repair_detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}
	
	
}

