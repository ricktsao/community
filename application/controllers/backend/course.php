<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		$this->getEdomaData();
	}
	


	/**
	 * course list page
	 */
	public function contentList()
	{				
		
		foreach( $_GET as $key => $value )
		{
			$query_data[$key] = $this->input->get($key,TRUE);			
		}		
		$data["query_data"] = $query_data;
		
		$is_cost = tryGetData("is_cost", $query_data);
		$mname = tryGetData("mname", $query_data);
		$tel = tryGetData("tel", $query_data);
		$s_date = tryGetData("s_date", $query_data);
		$e_date = tryGetData("e_date", $query_data);
		
		
		$condition = "";
		$condition_ary = array();
		
		if($is_cost == 1)
		{
			array_push($condition_ary,"url > 0");
		}
		else if($is_cost == 0)
		{
			array_push($condition_ary,"(url is null or url = 0)");
		}
		
		if($mname != "")
		{
			array_push($condition_ary,"filename like '%".$mname."%'");
		}
		
		if($tel != "")
		{
			array_push($condition_ary,"(brief like '%".$tel."%' OR brief2 like '%".$tel."%')");
		}
		
		if($s_date != "")
		{
			array_push($condition_ary,"start_date >= '".$s_date."'");
		}
		
		
		if($e_date != "")
		{
			array_push($condition_ary,"(end_date <= '".$e_date."' OR forever = 1 )");
		}
		
		
		$condition = implode(" AND ", $condition_ary);

		
		$list = $this->c_model->GetList( "course" , $condition ,FALSE, $this->per_page_rows , $this->page , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		img_show_list($list["data"],'img_filename',$this->router->fetch_class());
		
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
		$list = $this->c_model->GetList( "course" , $condition ,FALSE, NULL , NULL , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		img_show_list($list["data"],'img_filename',$this->router->fetch_class());		
		
		
		if($list["count"]>0)
		{
			$list = $list["data"];	
			$html = "<h1 style='text-align:center'>課程專區</h1>";
				
			
			$tables = 
			'<tr>										
				<th style="width:60px">序號</th>
				<th>課程主旨</th>
				<th>廠商名稱</th>
				<th>聯絡電話一</th>
				<th>聯絡電話二</th>
				<th>收費起訖日</th>		
				<th>收費金額</th>
			</tr>';
			
			
			for($i=0;$i<sizeof($list);$i++)
			{
				$cost = tryGetData("url", $list[$i]);
				if($cost != "")
				{
					echo $cost." 元";
				}
				else 
				{
				 	echo "不收費";
				}
				
				$tables .= 
				'<tr>
					<td>'.($i+1).'</td>
					<td>'.$list[$i]["title"].'</td>
					<td>'.$list[$i]["filename"].'</td>
					<td>'.$list[$i]["brief"].'</td>
					<td>'.$list[$i]["brief2"].'</td>
					<td>'.showEffectiveDate($list[$i]["start_date"], $list[$i]["end_date"], $list[$i]["forever"]).'</td>
					<td>'.$cost.'</td>											
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
			
		$cat_list = $this->c_model->GetList( "course_cat" , "" ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
		$data["cat_list"] = $cat_list["data"];
				
		if($content_sn == "")
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'start_date' => date( "Y-m-d" ),
				'content_type' => "course",
				'target' => 0,
				'forever' => 1,
				'launch' =>1
			);
			$this->display("content_form_view",$data);
		}
		else 
		{		
			$course_info = $this->c_model->GetList( "course" , "sn =".$content_sn);
			

			
			if($course_info["count"]>0)
			{				
				img_show_list($course_info["data"],'img_filename',"course");
				$course_info = $course_info["data"][0];
				$data["edit_data"] = $course_info;			
				
				if($course_info["is_edoma"]==1)
				{
					$this->display("content_view",$data);
				}
				else
				{
					$this->display("content_form_view",$data);
				}
				
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
		$edit_data["brief"] = tryGetData("brief",$_POST,0);	
			
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
			
			$sync_data = array(
			
			);			
			
			
			redirect(bUrl("contentList"));	
        }	
	}
	
	
	/**
	 * pdf下載頁面
	 */
	public function showPdf()
	{
		$content_sn = $this->input->get('sn');
		$item_info = $this->c_model->GetList( "course" , "sn =".$content_sn);
			
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
			
			if($img_str == "")
			{
				if(isNotNull($item_info["img_filename"]))
				{
					$img_str = "<tr><td><img  src='".$item_info["img_filename"]."'></td></tr>";
				}
				
			}
			
			
						
			$html = "<h1 style='text-align:center'>課程專區</h1>";
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
			$pdfFilePath = "課程專區_".$time .".pdf";
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
	 * 驗證daily_goodedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'title', '課程主旨', 'required' );	
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


	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */