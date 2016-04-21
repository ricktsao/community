<?php
Class Voting_model extends IT_Model
{
	
	public function change_option($sn =null,$arr_option=null){

		$this->it_model->deleteData( "voting_option",array("voting_sn"=>$sn));

		for($i=0;$i<count($arr_option);$i++){
			echo $arr_option[$i];
				$this->it_model->addData( "voting_option" , array("voting_sn" =>$sn,
																	"text"=>$arr_option[$i]));
		}
	
	}


	public function frontendGetVotingList($member_sn = null){
		//runSql
		$today = date("Y-m-d");

		$sql_date  = " AND '".$today."' >= voting.start_date AND '".$today."' <= voting.end_date ";

		$sql = "SELECT * FROM voting WHERE 1=1".$sql_date ;
		$result = $this->it_model->runSql($sql);
		return $result;
		//select * from voting left join (select count(*) as count,voting_record.voting_sn from voting_record where voting_record.user_sn = 9 ) as tt2 on voting.sn = tt2.voting_sn
	}

	public function frontendGetVotingDetail($voting_sn = null){

		$sql = "SELECT * FROM voting WHERE sn ='".$voting_sn."'";
		$voting = $this->it_model->runSql($sql);
		$voting = $voting['data'][0];

		$sql = "SELECT * FROM voting_option WHERE voting_sn ='".$voting_sn."'";
		$voting_option = $this->it_model->runSql($sql);

		$voting['voting_option'] = $voting_option['data'];

		return $voting;

	}

	public function frontendGetVotingUpdate($voting_sn,$voting_option_sn,$user_sn,$user_id){

		$arr_data =array(
			"voting_sn" => $voting_sn,
			"option_sn" =>$voting_option_sn,
			"user_sn" =>$user_sn,
			"user_id" =>$user_id,
			"created" => date("Y-m-d H:i:s")
			);

		

		$re = $this->it_model->addData("voting_record",$arr_data);

		return $re;

	}
	
}

