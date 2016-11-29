<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		$this->getEdomaFeedbackData();
	}
	


	/**
	 * feedback list page
	 */
	public function contentList()
	{		
		$condition = "";
		
		$list = $this->c_model->GetList( "feedback" , $condition ,FALSE, $this->per_page_rows , $this->page , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		//img_show_list($list["data"],'img_filename',$this->router->fetch_class());
		
		$data["list"] = $list["data"];
		
		//dprint($data);
		//取得分頁
		$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		//dprint($data["pager"]);
		
		
		$this->display("content_list_view",$data);
	}
	
	
	/**
	 * pdf list print
	 */
	public function showPdfList()
	{
		
		$condition = "";		
		$list = $this->c_model->GetList( "feedback" , $condition ,FALSE, $this->per_page_rows , $this->page , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );		
			
		
		if($list["count"]>0)
		{
			$list = $list["data"];	
			$html = "<h1 style='text-align:center'>課程專區</h1>";
				
			
			$tables = 
			'<tr>										
				<th style="width:60px">序號</th>
				<th>社區</th>
				<th>主旨</th>
				<th>內容</th>								
				<th>狀態</th>					
			</tr>';
			
			
			
			/*
			 echo '<hr>回覆:<br>';
												echo '<span style="color:red;">	';												
												echo nl2br($list[$i]["brief2"]);
												echo '<br>['.$list[$i]["update_date"].']';
												echo '</span>'; 
			 * */
			
			
			for($i=0;$i<sizeof($list);$i++)
			{
				
				$comment = $list[$i]["content"];
				if(isNotNull($list[$i]["brief2"]))
				{
					$comment .= '<hr>回覆:<br>';
					$comment .= '<span style="color:red;">	';												
					$comment .= nl2br($list[$i]["brief2"]);
					$comment .= '<br>['.$list[$i]["update_date"].']';
					$comment .= '</span>'; 
				}
												
				
				
				$tables .= 
				'<tr>
					<td>'.($i+1).'</td>
					<td>'.tryGetData($list[$i]["comm_id"], $comm_map).'</td>
					<td>'.$list[$i]["title"].'</td>
					<td>'.$comment.'</td>
					<td>'.($list[$i]["target"]==1?"<span style='color:blue'>已回覆</span>":"<span style='color:red'>未回覆</span>").'</td>						
				</tr>';	
			}
			
			$html .= '<table border="1" width="100%" >'.$tables.'</table>';
			
			$this->load->library('pdf');
			$mpdf = new Pdf();
			$mpdf = $this->pdf->load();
			$mpdf->useAdobeCJK = true;
			$mpdf->autoScriptToLang = true;
			
			
			$water_img = base_url('template/backend/images/watermark.png');
			$water_info = $this->c_model->GetList( "watermark");			
			if(count($water_info["data"])>0)
			{
				img_show_list($water_info["data"],'img_filename',"watermark");
				$water_info = $water_info["data"][0];			
				$water_img = $water_info["img_filename"];
						
			}
			$mpdf->SetWatermarkImage($water_img);
			$mpdf->watermarkImageAlpha = 0.081;
			$mpdf->showWatermarkImage = true;		
			
			$mpdf->WriteHTML($html);			
			
			$time = time();
			$pdfFilePath = "課程專區_".$time .".pdf";
			$mpdf->Output($pdfFilePath,'I');
		}
		else
		{
			$this->closebrowser();
		}
		
	}
	
	
	
	/**
	 * category edit page
	 */
	public function editContent()
	{		
		$content_sn = $this->input->get('sn');
			
			
		if($content_sn == "")
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'start_date' => date( "Y-m-d" ),
				'content_type' => "feedback",
				'target' => 0,
				'forever' => 1,
				'launch' =>1
			);
			$this->display("content_form_view",$data);
		}
		else 
		{		
			$feedback_info = $this->c_model->GetList( "feedback" , "sn =".$content_sn);
			
			if(count($feedback_info["data"])>0)
			{
				img_show_list($feedback_info["data"],'img_filename',$this->router->fetch_class());			
				
				$data["edit_data"] = $feedback_info["data"][0];			

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
					$this->sync_to_server($edit_data);
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
					$this->sync_to_server($edit_data);
				
					
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


	public function showPdf_bak()
	{
		$time = time();
		$pdf_file_name = $time .".pdf";
		
		$file_url = fUrl("downloadPdf",TRUE);
		header('Content-Type: application/pdf');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"" . $pdf_file_name . "\""); 
		readfile($file_url);

	}
	
	/**
	 * pdf下載頁面
	 */
	public function showPdf()
	{
		$content_sn = $this->input->get('sn');
		$item_info = $this->c_model->GetList( "feedback" , "sn =".$content_sn);
			
		if(count($item_info["data"])>0)
		{
			img_show_list($item_info["data"],'img_filename',$this->router->fetch_class());
			$item_info = $item_info["data"][0];			
			
			//多圖處理
			//------------------------------------------------------------------------------
			$img_str = "";			
			$photo_list = $this->it_model->listData( "web_menu_photo" , "content_sn =".$content_sn);
			
			foreach( $photo_list["data"] as $key => $photo ) 
			{
				$img_url = base_url('upload/content_photo/'.$content_sn.'/'.$photo["img_filename"]);
				
				$img_str .= "<tr><td><img src='".$img_url."'></td></tr>";
			}
			//------------------------------------------------------------------------------
			//dprint($photo_list["data"]);exit;
			
			$html = "<h1 style='text-align:center'>社區公告</h1>";
			$html .= "<h3>".$item_info["title"]."</h3>";
			$html .= "<table border=0><tr><td>".$item_info["content"]."</td></tr>".$img_str."</table>";
	
			$this->load->library('pdf');
			$mpdf = new Pdf();
			$mpdf = $this->pdf->load();
			$mpdf->useAdobeCJK = true;
			$mpdf->autoScriptToLang = true;
			
			
			$water_img = base_url('template/backend/images/watermark.png');
			$water_info = $this->c_model->GetList( "watermark");			
			if(count($water_info["data"])>0)
			{
				img_show_list($water_info["data"],'img_filename',"watermark");
				$water_info = $water_info["data"][0];			
				$water_img = $water_info["img_filename"];
						
			}			
			$mpdf->SetWatermarkImage($water_img);
			$mpdf->watermarkImageAlpha = 0.081;
			$mpdf->showWatermarkImage = true;		
			
			$mpdf->WriteHTML($html);			
			
			$time = time();
			$pdfFilePath = "社區公告_".$time .".pdf";
			$mpdf->Output($pdfFilePath,'I');
		}
		else
		{
			$this->closebrowser();
		}
		
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
			$img_config['resize_setting'] =array($folder_name=>array(1024,1024));					
			$uploadedUrl = './upload/tmp/' . $_FILES['img_filename']['name'];
			move_uploaded_file( $_FILES['img_filename']['tmp_name'], $uploadedUrl);
			
			$img_filename = resize_img($uploadedUrl,$img_config['resize_setting']);					
				
			//社區同步資料夾
			$img_config['resize_setting'] =array($folder_name=>array(500,500));
			resize_img($uploadedUrl,$img_config['resize_setting'],$this->getCommId(),$img_filename);
			
			@unlink($uploadedUrl);	

			$this->it_model->updateData( "web_menu_content" , array("img_filename"=> $img_filename), "sn = '".$content_sn."'" );
			
			$orig_img_filename = $this->input->post('orig_img_filename');
			
			@unlink(set_realpath("upload/website/".$folder_name).$orig_img_filename);	
			@unlink(set_realpath("upload/".$this->getCommId()."/".$folder_name).$orig_img_filename);	
			
			//檔案同步至server
			$this->sync_file($folder_name);
		}
		return $img_filename;
	}
	
	
	
	/**
	 * 驗證feedbackedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'title', '名稱', 'required' );	
		//$this->form_validation->set_rules('sort', '排序', 'trim|required|numeric|min_length[1]');			
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}




	public function deleteContent()
	{
		
		$del_ary = tryGetData("del",$_POST,array());				

		//社區主機刪除
		//----------------------------------------------------------------------------------------------------
		$sync_sn_ary = array();//待同步至雲端主機 array
		foreach ($del_ary as  $content_sn) 
		{
			$result = $this->it_model->updateData( "web_menu_content" , array("del"=>1,"is_sync"=>0,"update_date"=>date("Y-m-d H:i:s")), "sn ='".$content_sn."'" );
			if($result)
			{
				array_push($sync_sn_ary,$content_sn);
			}						
		}
		//----------------------------------------------------------------------------------------------------
				
		//社區主機同步
		//----------------------------------------------------------------------------------------------------
		foreach ($sync_sn_ary as  $content_sn) 
		{			
			$query = "SELECT SQL_CALC_FOUND_ROWS * from web_menu_content where sn =	'".$content_sn."'";			
			$content_info = $this->it_model->runSql($query);
			if($content_info["count"] > 0)
			{
				$content_info = $content_info["data"][0]; 
				
				
				$this->sync_to_server($content_info);
				
				//dprint($content_info);exit;
								
			}			
		}		
		//----------------------------------------------------------------------------------------------------

		
		$this->showSuccessMessage();
		
		redirect(bUrl("contentList", FALSE));	
	}


	public function launchContent()
	{		
		$this->ajaxlaunchContent($this->input->post("content_sn", TRUE));
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
	
	
	/**
	 * 查詢server上有無edoma資料
	 **/
	public function getEdomaFeedbackData()
	{
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync_edoma/getFeedbackContent";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$json_data = curl_exec($ch);
		curl_close ($ch);
		
		//echo $json_data;exit;
		
		$edoma_data_ary =  json_decode($json_data, true);
		//dprint($edoma_data_ary);exit;
		if( ! is_array($edoma_data_ary))
		{
			$edoma_data_ary = array();
		}
		
		
		foreach( $edoma_data_ary as $key => $server_info ) 
		{	

			$arr_data = array
			(
				 "server_sn" => $server_info["sn"]
				, "brief2" => tryGetData("brief2",$server_info)
				, "update_date" =>  date( "Y-m-d H:i:s" )
				, "hot" => 1	
				, "target" => 1			
				, "is_edoma" => 1
			);        	
			
			
			$result = $this->it_model->updateData( "web_menu_content" , $arr_data, "sn = '".$server_info["client_sn"]."'" );
			if($result)
			{
				$data_info = $this->it_model->listData("web_menu_content"," sn = '".$server_info["client_sn"]."'");
				if($data_info["count"]>0)
				{
					$data_info = $data_info["data"][0];
					//dprint($data_info);exit;	
					
					$this->sync_edoma_to_server($data_info);
				}			  
				
			}		
		}
		
		//echo '<meta charset="UTF-8">';
		//dprint($app_data_ary);
		
	}
	
	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */