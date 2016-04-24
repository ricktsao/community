<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voting extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();
		$this->load->Model("voting_model");
		$this->checkLogin();
	}


	public function index()
	{
		$data = array();
		
		

		$voting = $this->voting_model->frontendGetVotingList($this->session->userdata("f_user_sn"));
	
		$data['voting_list'] = $voting['data'];
		$this->display("voting_list_view",$data);
	}
	
	
	public function detail()
	{
		$content_sn = $this->input->get('sn');
				
		if($content_sn == "")
		{
			redirect(fUrl("index"));	
		}
		
		$voting_info = $this->voting_model->frontendGetVotingDetail($content_sn);
			
		if($voting_info)
		{
			$data["voting_info"] = $voting_info;
			$this->display("voting_detail_view",$data);
		}
		else
		{
			redirect(fUrl("index"));	
		}
		
	}

	public function update(){

		$arr_data = [];

		foreach ($_POST as $key => $value) {
			$arr_data[$key] = $value;
		}

		if (!$this->_validateVoting())
		{
			header("location:".fUrl('index'));

		}else{


			for($i=0;$i<count($arr_data['voting']);$i++){
				$this->voting_model->frontendGetVotingUpdate($arr_data['sn'],$arr_data['voting'][$i],$this->session->userdata("f_user_sn"),$this->session->userdata("f_user_id"));

			}


			header("location:".fUrl('index'));

		}




	}

	function _validateVoting()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'voting', '投票', 'required' );	
		$this->form_validation->set_rules( 'sn', '投票', 'required' );
		
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}
	
	
}

