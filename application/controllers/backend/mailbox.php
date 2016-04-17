<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailbox extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	
	

	/**
	 * list page
	 */
	public function contentList()
	{
		$mailbox_list = $this->it_model->listData("mailbox","is_receive = 0", NULL , NULL, array("booked"=>'desc'));				
		$data["mailbox_list"] = $mailbox_list["data"];
		$data["pager"] = $this->getPager($mailbox_list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		
		$user_list = $this->it_model->listData("sys_user");
		$user_map =  $this->it_model->toMapValue($user_list["data"],"sn","name");
		
		$data["user_map"] = $user_map;
		
		$this->display("content_list_view",$data);
	}
	
	/**
	 * category edit page
	 */
	public function editContent()
	{	
		$content_sn = $this->input->get('sn');
			
		$cat_list = $this->c_model->GetList( "newscat" , "" ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
		$data["cat_list"] = $cat_list["data"];
				
		if($content_sn == "")
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'start_date' => date( "Y-m-d" ),
				'content_type' => "news",
				'target' => 0,
				'forever' => 1,
				'launch' =>1
			);
			$this->display("content_form_view",$data);
		}
		else 
		{		
			$news_info = $this->c_model->GetList( "news" , "sn =".$content_sn);
			
			if(count($news_info["data"])>0)
			{
				img_show_list($news_info["data"],'img_filename',$this->router->fetch_class());			
				
				$data["edit_data"] = $news_info["data"][0];			

				$this->display("content_form_view",$data);
			}
			else
			{
				redirect(bUrl("contentList"));	
			}
		}
	}
	
	
	public function updateContent()
	{	
				
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		if ( ! $this->_validateContent())
		{
			$data["edit_data"] = $edit_data;		
			$this->display("content_form_view",$data);
		}
        else 
        {
				$update_data = array(
				"type" => tryGetData("type",$edit_data),
				"desc" => tryGetData("desc",$edit_data),
				"booked" => date( "Y-m-d H:i:s" ),
				"booker" => $this->session->userdata("user_sn"),
				"booker_id" => $this->session->userdata("user_sn"),
				"user_name" => tryGetData("user_name",$edit_data),
				"updated" => date( "Y-m-d H:i:s" )
				);
				
				$user_info = $this->it_model->listData("sys_user","sn='".tryGetData("user_sn",$edit_data)."'");
				if($user_info["count"]>0)
				{
					$user_info = $user_info["data"][0];
					$update_data["user_sn"] = $user_info["sn"];
					$update_data["user_app_id"] = $user_info["app_id"];
				}
				
				
				
				$content_sn = $this->it_model->addData( "mailbox" , $update_data );
				if($content_sn > 0)
				{				
					//設定　代收編號　日期＋流水後號３碼
					//--------------------------------------------------
					$mail_no = str_pad($content_sn,10,'0',STR_PAD_LEFT);
					$mail_no = date("Ymd").substr($mail_no,7,3);
					$this->it_model->updateData( "mailbox" , array("no"=>$mail_no,"updated" => date( "Y-m-d H:i:s" )),"sn = ".$content_sn );					
					//--------------------------------------------------
					
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}
	
			
			
			redirect(bUrl("contentList"));	
        }
	}
	
	/**
	 * 驗證newsedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'user_name', '收件人', 'required' );	
	
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}



	public function updateMailbox()
	{			
		$receive_sn_ary = $this->input->post('is_receive',TRUE);
		$receiver_ary =$this->input->post('receiver',TRUE);
		$mailbox_sn_ary = $this->input->post('mailbox_sn',TRUE);	
		
		for ($i=0; $i < count($mailbox_sn_ary) ; $i++) 
		{
			if(in_array($mailbox_sn_ary[$i], $receive_sn_ary))
			{
				if(isNull($receiver_ary[$i]) || trim($receiver_ary[$i])=="" )
				{
					continue;
				}
				
				$update_data = array(
					"is_receive" => 1,
					"receiver" => $receiver_ary[$i],
					"updated" => date("Y-m-d H:i:s")
				);
				
				$this->it_model->updateData( "mailbox" , $update_data,"sn ='".$mailbox_sn_ary[$i]."'" );
			}			
		}

		$this->showSuccessMessage();
		redirect(bUrl("contentList", TRUE));	
				
	}


	public function ajaxGetPeople()
	{
		$keyword = $this->input->get('keyword', true);
		
		$user_list = $this->it_model->listData("sys_user","name like '%".$keyword."%'");
		
		
		$return_string = '';
		foreach( $user_list["data"] as $key => $user )
		{
			
			$return_string .= '
			<ul id="names_list">
				<li onclick="selectCountry(\''.$user["sn"].'\',\''.$user["name"].'\');">'.$user["name"].'　地址：'.$user["owner_addr"].'</li>
			</ul>
			';	
		}
		echo $return_string;
	}

	


	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */