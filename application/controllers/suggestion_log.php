<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suggestion_log extends Frontend_Controller {


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
		$url = $this->config->item("api_server_url")."sync/getAppSuggestion";
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
		//dprint($app_data_ary);exit;
		
		
		foreach( $app_data_ary as $key => $server_info ) 
		{			
			$repair_server_info = $this->it_model->listData("suggestion","server_sn='".$server_info["sn"]."'");
			if($repair_server_info["count"]==0)
			{
								
				$user_info = $this->it_model->listData("sys_user","app_id='".$server_info["app_id"]."'");
				if($user_info["count"]>0)
				{
					$user_info = $user_info["data"][0];
					
					$add_data = array(
					"comm_id" => $this->getCommId(),
					"server_sn" => $server_info["sn"],
					"app_id" => $server_info["app_id"],
					"title" => $server_info["title"],
					"content" => $server_info["content"], 
					"user_sn" => $user_info["sn"],
					"to_role" => $server_info["to_role"], 					
					"updated" => date("Y-m-d H:i:s"),
					"created" => date("Y-m-d H:i:s")
					);
					$suggestion_sn = $this->it_model->addData( "suggestion" , $add_data );	
					if($suggestion_sn > 0)
					{
						$add_data["sn"] = $suggestion_sn;								
						$this->sync_item_to_server($add_data,"updateServerSuggestion","suggestion");
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
		
		$suggestion_list = $this->it_model->listData("suggestion","user_sn = '".$this->session->userdata("f_user_sn")."'",$this->per_page_rows , $this->page,array("created"=>"desc"));
		$data["pager"] = $this->getPager($suggestion_list["count"],$this->page,$this->per_page_rows,"index");
		$data["suggestion_list"] = $suggestion_list["data"];
		$this->display("suggestion_list_view",$data);
	}
	
	
	public function detail()
	{
		$this->getAppData();//查詢server有無要同步的資料
		$content_sn = $this->input->get('sn');
						
		if($content_sn == "")
		{
			redirect(fUrl("index"));	
		}
		
		$suggestion_info = $this->it_model->listData( "suggestion" , "user_sn = '".$this->session->userdata("f_user_sn")."' and sn =".$content_sn);			

			
		if($suggestion_info["count"]>0)
		{				
			$suggestion_info = $suggestion_info["data"][0];
			$data["suggestion_info"] = $suggestion_info;		

			
			$this->display("suggestion_detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}
	
	
}

