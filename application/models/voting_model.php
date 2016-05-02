<?php
Class Voting_model extends IT_Model
{
	
	public function change_option($sn =null,$arr_option=null){

		$this->it_model->deleteData( "voting_option",array("voting_sn"=>$sn));

	//	$this->it_model->updateData( "voting_option",array("is_del"=>0),"'voting_sn'='".$sn."'");

		//$query = "UPDATE voting_option SET is_del='0' WHERE voting_sn='".$sn."'";

		//$this->it_model->runSqlCmd($query);


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

		$sql_subquery =  " SELECT 
							count(*) as counts,voting_sn 
							FROM voting_record 
							WHERE user_sn = ".$member_sn." 
							GROUP BY voting_sn ";

		$sql = "SELECT 
				voting.sn,
				subject,
				description,
				start_date,
				end_date,
				counts
				FROM voting LEFT JOIN (".$sql_subquery.") AS vr ON  voting.sn = vr.voting_sn
				WHERE vr.counts IS NULL".$sql_date." AND is_del = 0" ;

		$result = $this->it_model->runSql($sql);

	
		return $result;
	}

	public function frontendGetVotingResultList(){
		//runSql
		$today = date("Y-m-d");

		$sql_date  = " AND  '".$today."' >= voting.end_date ";

		

		$sql = "SELECT *
				FROM voting 
				WHERE 1=1 ".$sql_date." AND is_del = 0" ;

		$result = $this->it_model->runSql($sql);

	
		return $result;
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

	public function votingRecord($voting_sn,$show_user = FALSE){

		//get voting info

		$sql="SELECT * FROM voting WHERE sn=".$voting_sn;
		$re = $this->it_model->runSql($sql);
		$re = $re['data'][0];
		$data =array("subject" => $re['subject'],
					"description" => $re['description'],
					"start_date" => $re['start_date'],
					"end_date" => $re['end_date'],
					"allow_anony" => $re['allow_anony'],
					"is_multiple" => $re['is_multiple'],
					"options" => array());




		$sql ="SELECT voting_option.sn AS option_sn,					
					IFNULL(voting_count,0) as voting_count,
					voting_option.text 
					FROM voting_option 
					LEFT JOIN 
    				(select option_sn,count(*) as voting_count from voting_record group by option_sn) AS vr ON voting_option.sn = vr.option_sn
					WHERE voting_option.voting_sn = ".$voting_sn;

		//echo $sql;die();

		$re = $this->it_model->runSql($sql);
		$re = $re['data'];		
		
	

		for($i=0;$i<count($re);$i++){

			$_arr = array(
				"option_sn" => $re[$i]['option_sn'],
				"option_text" => $re[$i]['text'],
				"voting_count" => $re[$i]['voting_count'],
				"user"=>NULL		 
			);

			if($show_user){
				$sql="SELECT sys_user.name 
					FROM voting_record LEFT JOIN sys_user ON voting_record.user_sn = sys_user.sn
					WHERE voting_record.option_sn =".$re[$i]['option_sn'];

				$user = $this->it_model->runSql($sql);

				$_arr['user'] = $user['data'];
			}
			array_push($data['options'],$_arr);

		}

		return $data;

	}	


	public	function sync_to_server($post_data =null,$page_name){
		//$url = "http://localhost/commapi/sync/updateContent";
		//$url = $this->config->item("api_server_url").$page_name;
		$url = "http://localhost/commapi/".$page_name;

		return $url;
		$post_data['comm_id'] =  $this->session->userdata("comm_id");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$is_sync = curl_exec($ch);
		curl_close ($ch);
		

		
		//更新同步狀況
		//------------------------------------------------------------------------------
		if($is_sync != '1')
		{
			$is_sync = '0';
		}			
		
		return $is_sync;
		//------------------------------------------------------------------------------
	}
	

	
}

