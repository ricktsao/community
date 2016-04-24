<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gas_report extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	

	/**
	 * list page
	 */
	public function contentList()
	{		
		$year = $this->input->get("year",TRUE);		
		$month = $this->input->get("month",TRUE);
		
		$building_list = array();
		if(isNotNull($year) && isNotNull($month))
		{
			$building_query ="
			select building_id,owner_addr from sys_user where role='I' group by building_id 	
			order by building_id
			";
			$building_list = $this->it_model->runSql($building_query);
			//dprint(building_list);exit;
			$building_list = $building_list["data"];
			foreach ($building_list as $key => $building_info) 
			{
				$building_list[$key]["year"] = $year;
				$building_list[$key]["month"] = $month;
				$building_list[$key]["degress"] = 0;			
				
				$query = "
				SELECT SQL_CALC_FOUND_ROWS * from gas 
				where year = '".$year."' and month = '".$month."'
				and building_id = '".$building_info["building_id"]."'";	
				
				$gas_info = $this->it_model->runSql($query);
				if($gas_info["count"]>0)
				{
					$gas_info = $gas_info["data"][0];
					$degress = tryGetData("degress",$gas_info,"-");
					if($degress > 0 )
					{
						$degress = $degress."度";
					}
					$building_list[$key]["degress"] = $degress ;
				}			
				
			}
		}
		
		
		$this_year = date("Y");
		$year_list = array();
		array_push($year_list,$this_year);
		array_push($year_list,$this_year-1);
		array_push($year_list,$this_year-2);
		$data["year_list"] = $year_list;
		
		$data["q_year"] = $year;	
		$data["q_month"] = $month;		
		$data["building_list"] = $building_list;
		
		$this->display("content_list_view",$data);
	}
	

	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */