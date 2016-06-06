<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cycle_img extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	

	/**
	 * cycle_img list page
	 */
	public function contentList()
	{	
		$cat_sn = $this->input->get('cat_sn');		
						
		
		$condition = "";

		
		$list = $this->c_model->GetList( "cycle_img" , $condition ,FALSE, $this->per_page_rows , $this->page , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		img_show_list($list["data"],'img_filename',$this->router->fetch_class());
		
		$data["list"] = $list["data"];
		
		//dprint($data);
		//取得分頁
		$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		
		$this->display("content_list_view",$data);
	}
	
	/**
	 * category edit page
	 */
	public function editContent()
	{		
		$content_sn = $this->input->get('sn');
			
		$cat_list = $this->c_model->GetList( "cycle_imgcat" , "" ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
		$data["cat_list"] = $cat_list["data"];
				
		if($content_sn == "")
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'start_date' => date( "Y-m-d" ),
				'content_type' => "cycle_img",
				'target' => 0,
				'forever' => 1,
				'launch' =>1
			);
			$this->display("content_form_view",$data);
		}
		else 
		{		
			$cycle_img_info = $this->c_model->GetList( "cycle_img" , "sn =".$content_sn);
			
			if(count($cycle_img_info["data"])>0)
			{
				img_show_list($cycle_img_info["data"],'img_filename',$this->router->fetch_class());			
				
				$data["edit_data"] = $cycle_img_info["data"][0];			

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
		//dprint($edit_data);exit;
		$edit_data["is_sync"] = 0;
		
		if ( ! $this->_validateContent())
		{
			$data["edit_data"] = $edit_data;		
			$this->display("content_form_view",$data);
		}
        else 
        {		
						
			if(isNotNull($edit_data["sn"]))
			{				
				if($this->it_model->updateData( "web_menu_content" , $edit_data, "sn =".$edit_data["sn"] ))
				{					
					$img_filename = $this->uploadImage($edit_data["sn"]);					
					$edit_data["img_filename"] = $img_filename;
					//$this->sync_to_server($edit_data);
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
					$img_filename =$this->uploadImage($content_sn);
					$edit_data["img_filename"] = $img_filename;
					$edit_data["sn"] = $content_sn;
					//$this->sync_to_server($edit_data);
				
					
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
	
	function changeBg()
	{
		$content_sn = $this->input->post('hot');
		
		if(isNotNull($content_sn))
		{
			$this->it_model->updateData("web_menu_content",array("hot"=>1,"update_date"=>date("Y-m-d H:i:s")),"content_type = 'cycle_img' and sn = ".$content_sn);
			$this->it_model->updateData("web_menu_content",array("hot"=>0,"update_date"=>date("Y-m-d H:i:s")),"content_type = 'cycle_img' and sn != ".$content_sn);
		
			$this->showSuccessMessage();
		}
		else
		{
			$this->showFailMessage();
		}
		
		
		redirect(bUrl("contentList"));	
	}
	
	
		
	//圖片處理
	private function uploadImage($content_sn)
	{
		$img_filename = "";
		if(isNull($content_sn))
		{
			return;
		}
		//dprint($_FILES);exit;
		if(isNotNull($_FILES['img_filename']['name']))
		{
			$folder_name = $this->router->fetch_class();
			
			//圖片處理 img_filename				
			$img_config['resize_setting'] =array($folder_name=>array(1080,1920));					
			$uploadedUrl = './upload/tmp/' . $_FILES['img_filename']['name'];
			move_uploaded_file( $_FILES['img_filename']['tmp_name'], $uploadedUrl);
			
			$img_filename = resize_img($uploadedUrl,$img_config['resize_setting']);					
				
			
			$this->it_model->updateData( "web_menu_content" , array("img_filename"=> $img_filename), "sn = '".$content_sn."'" );			
			$orig_img_filename = $this->input->post('orig_img_filename');
			
			
			@unlink($uploadedUrl);	
			@unlink(set_realpath("upload/website/".$folder_name).$orig_img_filename);	
			@unlink(set_realpath("upload/".$this->getCommId()."/".$folder_name).$orig_img_filename);	
			
			
		}
		return $img_filename;
	}
	
	
	
	/**
	 * 驗證cycle_imgedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		//$this->form_validation->set_rules( 'title', '名稱', 'required' );	
		$this->form_validation->set_rules('sort', '排序', 'trim|required|numeric|min_length[1]');			
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}



	public function deleteContent()
	{
		$del_ary =array('sn'=> $this->input->post('del',TRUE));		
		
		if($del_ary!= FALSE && count($del_ary)>0)
		{
			$this->it_model->deleteDB( "web_menu_content",NULL,$del_ary );				
		}
		$this->showSuccessMessage();
		redirect(bUrl("contentList", FALSE));	
	}

	

	public function launchContent()
	{
		$this->ajaxChangeStatus("web_menu_content","launch",$this->input->post("content_sn", TRUE));		
	}

	

	public function hotContent()
    {
		$sn = $this->input->post("content_sn", TRUE);
		$table_name = 'web_menu_content';
        $field_name = 'hot';
        if(isNull($table_name) || isNull($field_name) || isNull($sn) )
        {
            echo json_encode(array());
        }
        else 
        {		

            $data_info = $this->it_model->listData($table_name," sn = '".$sn."'");
			if($data_info["count"]==0)
			{
				echo json_encode(array());
				return;
			}			  
			
			$data_info = $data_info["data"][0];
			
			$change_value = 1;
			if($data_info[$field_name] == 0)
			{
				$change_value = 1;
			}
			else
			{
				$change_value = 0;
			}
			
			
			$result = $this->it_model->updateData( $table_name , array($field_name => $change_value),"sn ='".$sn."'" );				
			if($result)
			{
				//社區主機同步
				//----------------------------------------------------------------------------------------------------
				$query = "SELECT SQL_CALC_FOUND_ROWS * from web_menu_content where sn =	'".$sn."'";			
				$content_info = $this->it_model->runSql($query);
				if($content_info["count"] > 0)
				{
					$content_info = $content_info["data"][0]; 					
					$this->sync_to_server($content_info);									
				}			
				//----------------------------------------------------------------------------------------------------
				echo json_encode($change_value);
			}
			else
			{
				echo json_encode($data_info[$field_name]);
			}
			                      
        }
    }
	
	
	
	
	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */