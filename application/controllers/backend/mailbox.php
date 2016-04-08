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
		$edit_data = $this->dealPost();
						
		if ( ! $this->_validateContent())
		{
			$data["edit_data"] = $edit_data;		
			$this->display("content_form_view",$data);
		}
        else 
        {
			
			deal_img($edit_data ,"img_filename",$this->router->fetch_class());			
			
			
			if(isNotNull($edit_data["sn"]))
			{

				
				if($this->it_model->updateData( "web_menu_content" , $edit_data, "sn =".$edit_data["sn"] ))
				{					
					$this->showSuccessMessage();					
				}
				else 
				{
					$this->showFailMessage();
				}
				
			}
			else 
			{
									
				$edit_data["create_date"] =   date( "Y-m-d H:i:s" );
				
				$content_sn = $this->it_model->addData( "web_menu_content" , $edit_data );
				if($content_sn > 0)
				{				
					$edit_data["sn"] = $content_sn;
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}
	
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
		
		$this->form_validation->set_rules( 'title', '名稱', 'required' );	
		$this->form_validation->set_rules('sort', '排序', 'trim|required|numeric|min_length[1]');			
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}



	public function updateMailbox()
	{	
		$edit_data = $this->dealPost();
				
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		
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
		echo '
			<ul id="names_list">
				<li onclick="selectCountry(\'14509\',\'林心城\',\'\',\'桃園市桃園區中路里２５鄰國際路二段２８號\');">林心城（身分證號：未知）　地址：桃園市桃園區中路里２５鄰國際路二段２８號</li><li onclick="selectCountry(\'14510\',\'林心正\',\'\',\'新北市新莊區西盛里２１鄰民安西路４８０巷３號\');">林心正（身分證號：未知）　地址：新北市新莊區西盛里２１鄰民安西路４８０巷３號</li>
			</ul>';
	}

	/**
	 
	  
	 */

	public function ajaxGetPeople1()
	{
		$keyword = $this->input->get('keyword', true);
		$case_city_code = $this->input->get('ccd', true);
		$case_location_sn = $this->input->get('lsn', true);

		if (mb_strlen($keyword) > 1) {

			$keyword = big5_for_utf8($keyword);

			// 搜尋客戶序號
			/*$query = 'SELECT c.sn, c.name, c.uni_id, c.addr '
					.'  FROM customer c LEFT JOIN customer_land_detail cld ON c.sn = cld.customer_sn '
					.' WHERE c.name like "'.$keyword.'%"  '
					;*/
			
			## 搜尋客戶在指定行政區是否有土地
			// 先查詢客戶的資產
			$query = 'SELECT c.sn, c.name, c.uni_id, c.addr , GROUP_CONCAT(cld.land_sn) as land_list '
					.'  FROM customer c LEFT JOIN customer_land_detail cld ON c.sn = cld.customer_sn   '
					.' WHERE c.name like "%'.$keyword.'%" '
					.'   AND LOWER(cld.city_code) = "'.strtolower($case_city_code).'" '
					.' GROUP BY customer_sn '
					;
			
			$result = $this->it_model->runSql($query, null, null, array('sn'=>'asc'));

			echo '<ul id="names_list">';
			$cust = array();

			if ( $result['count'] > 0 ) {
				$i = 0;
				foreach ( $result['data'] as $item) {

					$flag = false;

					$land_list = tryGetData('land_list', $item);
					if (substr($land_list,-1) == ',') {
						$land_list = substr($land_list,0,-1);
					}
					$land_array = explode(',' , $land_list);

					// 檢查客戶的資產是否在指定行政區？
					foreach ($land_array as $land_sn) {
						
						$condi = 'sn='.$land_sn . ' AND location_sn='.$case_location_sn;
						$result2 = $this->it_model->listData('b'.$case_city_code.'_land_view', $condi, null, null);

						if ($result2['count'] > 0) {
							$flag = true;
							break;
						} else {
						
					//	dprint($result['count']);
						}
					}
		//dprint($query);
					$sn = tryGetData('sn', $item);
					$name = tryGetData('name', $item, NULL);
					$uni_id = tryGetData('uni_id', $item, NULL);
					$addr = tryGetData('addr', $item, '未知');

					if ($flag == false) {
						continue;
					}

					$layout = $name.'';
					//if (isNotNull(tryGetData('uni_id', $item, NULL)) ) {
						$layout .= '（身分證號：'.tryGetData('uni_id', $item, '未知').'）';
					//}
					
					$layout .= '　地址：'.$addr;

					echo '<li onclick="selectCountry(\''.$sn .'\',\''. $name .'\',\''. $uni_id .'\',\''. $addr.'\');">'. $layout . "</li>";
					$i++;
				}
				if ( $i == 0 ) {
					echo '<li style="font-weight:normal; color: #c8c8c8">... 查無客戶資料，請確認您的客戶擁有（或曾經擁有）此區域的土地 ...</li>';
				}
			} else {
					echo '<li style="font-weight:normal; color: #c8c8c8">... 查無客戶資料，請確認您的客戶擁有（或曾經擁有）此區域的土地 ....</li>';
			}
		} else {
					echo '<li style="font-weight:normal; color: #c8c8c8">... 查無客戶資料，請確認您的客戶擁有（或曾經擁有）此區域的土地 .....</li>';
		}
		// echo json_encode($return);
		echo '</ul>';
	}

	



	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */