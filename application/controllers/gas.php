<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gas extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->checkLogin();
	}


	public function index()
	{
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
			$now_condition = "building_id = '".$this->session->userdata("f_building_id")."' ";
			$now_condition.= "and year = '".date("Y")."' and month = '".date("m")."'";
			
			$this_mon_gas_info = $this->it_model->listData("gas",$now_condition,1,1);
			//dprint($this_mon_gas_info);
			
			if($this_mon_gas_info["count"]>0)
			{
				$this_mon_gas_info = $this_mon_gas_info["data"][0];
			}
			else
			{
				$this_mon_gas_info = array();
			}
			$data["this_mon_gas_info"] = $this_mon_gas_info;
			//dprint($this_mon_gas_info);
			
			$condition = "building_id = '".$this->session->userdata("f_building_id")."' ";
			//$condition.= "and ( year != '".date("Y")."' and month != '".date("m")."')";
			$gas_list = $this->it_model->listData("gas",$condition,12,1,array("year"=>"desc","month"=>"desc"));
			$data["gas_list"] = $gas_list["data"];		
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
			$update_data = array(
			"degrees" => $degress,							
			"updated" => date( "Y-m-d H:i:s" )
			);
			
			$condition = "building_id = '".$this->session->userdata('f_building_id')."' AND year = '".$year."' AND month = '".$month."' ";
			$result = $this->it_model->updateData( "gas" , $update_data,$condition );					
			
			if($result === FALSE)
			{
				$update_data["building_id"] = $this->session->userdata('f_building_id');
				$update_data["year"] = $year;
				$update_data["month"] = $month;
				$update_data["created"] = date( "Y-m-d H:i:s" );
				
				$content_sn = $this->it_model->addData( "gas" , $update_data );
				
				if($content_sn > 0)
				{				
					//$this->showSuccessMessage();							
				}
				else 
				{
					//$this->showFailMessage();					
				}				
			}
		}
		redirect(fUrl("index"));	
	}
	
}

