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
	
}

