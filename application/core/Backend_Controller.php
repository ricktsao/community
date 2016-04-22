<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class Backend_Controller extends IT_Controller 
{
	public $title = "";	//標題	

	public $left_menu_list = array();
	public $top_menu_list = array();
	public $module_id = "home";
	public $module_sn = 0;	
	public $module_parent_sn = 0;	
	public $module_item_map = array();
	
	public $module_info;
	public $sub_title = "";	
	public $page = 1;
	public $per_page_rows = 20;
		
	public $img_config = array();
	
	public $navi = array();
	public $navi_path = '';
	
	public $style_css = array();
	public $style_js = array();
	
	function __construct() 
	{
		parent::__construct();
		
		if(!checkUserLogin())
		{
			redirect(backendUrl("login","index/?check_user_error"));
		}		
		
		$this->initNavi();
		$this->initBackend();
		$this->getParameter();
		$this->generateTopMenu();	
		$this->lang->load("common");
		//$this->traceLog();
		//$this->config->set_item('language', $this->language_value);	
	}
	
	
	function initBackend()
	{
		$this->getLeftMenu();
		$this->module_info = $this->getModuleInfo();
	}
	
	
	
	
	public function getParameter()
	{
		$this->page = $this->input->get('page',TRUE);
		if(isNull($this->page))
		{
			$this->page = 1;
		}
		$this->per_page_rows =	$this->config->item('per_page_rows','pager');		
	}
	
	private function _checkAuth()
	{
		if($this->module_id != "home")
		{
			$admin_auth = $this->session->userdata("user_auth");
		
			if( ! in_array($this->module_id, $admin_auth) )
			{
				$this->redirectHome();
			}
		}		
		
	}

	
	
	
	//取得單元上方子選單
	abstract public function generateTopMenu();	

	protected function getModuleInfo()
	{	
		$this->module_id = $this->uri->segment(2);				
		$module_info = $this->it_model->listData("sys_module" , "id = '".$this->module_id."' ");	
		
		//echo 'test';
		//dprint($module_info);
	
		if( sizeof($module_info["data"])>0)
		{
			$this->module_sn = $module_info["data"][0]["sn"];			
			$this->module_parent_sn = $module_info["data"][0]["parent_sn"];		
			
			$this->addNavi($module_info["data"][0]["title"], fUrl("index"));
			//$this->addNavi("test", fUrl("index"));		
		  	return $module_info["data"][0];
		}
		else 
		{						
			return array("id"=>"","title"=>"");
		}
	}

	
	
	protected function getLeftMenu()
	{
		$condition = " type=1 and launch = 1";
		
		$sort = array
		(
			//"sys_module.module_category_sn" => "> 0 desc" , 
			//"sys_module_category.sort" => "asc" , 
			"sort" => "asc" 
		);
		
		//$left_menu_list = $this->module_model->GetList( $condition , NULL , NULL , $sort  );
		
		$left_menu_list = $this->it_model->listData("sys_module"," type=1 and level=1 and launch=1",NULL,NULL,$sort);	
		

		$this->left_menu_list = $this->_adjustLeftMenu($left_menu_list["data"]);
		
		
		$l2_list = $this->it_model->listData("sys_module"," type=1 and level=2 and launch=1",NULL,NULL,$sort);	
		$l2_list = $this->_adjustLeftMenu($l2_list["data"]);
		//$module_item_list = $this->it_model->listData("sys_module_item",$condition,NULL,NULL,$sort);	
		//$module_item_list = $this->_adjustLeftMenu($module_item_list["data"]);
		
		foreach ($l2_list as $item) 
		{
			$this->module_item_map[$item["parent_sn"]]["item_list"][]=$item;
		}
		
		//dprint($this->module_item_map);
		
		//$this->module_item_map = $this->it_model->convertArrayToKeyArray($module_item_list,"module_sn");

		//dprint($this->module_item_map);
		
	}
	
	private function _adjustLeftMenu($left_menu_list)
	{				
		if($left_menu_list!=FALSE)
		{
			for($i=0; $i<sizeof($left_menu_list);$i++)
			{
				$left_menu_list[$i]["url"] = base_url().$this->config->item('backend_name')."/".$left_menu_list[$i]["id"];
			}
		}		
		return $left_menu_list;
	}
	
	
	
	function initNavi()
	{	
		$this->navi["首頁"] = backendUrl();		
	}
	
	function addNavi($key,$url)
	{
		$this->navi[$key] = $url;	
	}
	
	
	function buildNavi()
	{
		$navi_size = count($this->navi);
		$navi_count = 0;
		foreach ($this->navi as $key => $value) 
		{
			$navi_count++;
			
			if($navi_count == 1)
			{
				$this->navi_path .= 
				'<li>
					<i class="icon-home home-icon"></i>
					<a href="'.backendUrl().'">'.$key.'</a>
				</li>';
				
			}			
			else if($navi_size != $navi_count && $key == "首頁")
			{
				$this->navi_path .= 
				'<li class="active">			
					<a href="'.$value.'">'.$key.'</a>
				</li>';
			}
			else 
			{
				$this->navi_path .= 
				'<li class="active">'.$key.'</li>';
			}

		}
		
		$this->navi_path = '<ul class="breadcrumb">'.$this->navi_path.'</ul>';
		
		
	}
	
	
		
	/**
	 * 回到backend 首頁
	 */	
	public function redirectHome()
	{
		header("Location:".base_url().$this->config->item('backend_name')."/home");
	}
	
	
	
	/**
	 * 登出
	 */
	public function logout()
	{
		$who = $this->session->userdata('unit_name').$this->session->userdata('user_name');
		logData("後台登出-".$who, 1);

		$this->sysLogout();
	}	

	
	
	
	/**
	 * output view
	 */
	function display($view, $data = array())
	{
		if(strrpos($view, "/") === FALSE)
		{
			$view = $this->config->item('backend_name').'/'.$this->router->fetch_class()."/".$view;	
		}		
		
		
		$data['templateUrl'] = $this->config->item("template_backend_path");
		
		$data['module_info'] = $this->getModuleInfo();
		$data['module_id'] = $this->module_id;		
		$data['module_sn'] = $this->module_sn;	
		$data['module_parent_sn'] = $this->module_parent_sn;	
		
		
		$data['backend_message'] =$this->session->flashdata('backend_message');		
		$data['top_menu_list'] = $this->top_menu_list;	
		$data['left_menu_list'] = $this->left_menu_list;
		$data['module_item_map'] = $this->module_item_map;
		
		//麵包屑
		$this->buildNavi();
		$data['navi_path'] = $this->navi_path;		
		$data['breadcrumb_area'] = $this->load->view($this->config->item('backend_name').'/template_breadcrumb_view', $data, TRUE);	
		
		
		//內頁title區
		$data['page_header_area'] = $this->load->view($this->config->item('backend_name').'/template_page_header_view', $data, TRUE);
		
		//左側選單
		$data['nvai_menu'] = $this->load->view($this->config->item('backend_name').'/template_navi_view', $data, TRUE);
		
		//提示訊提
		$data['alert_message_area'] = $this->load->view($this->config->item('backend_name').'/template_alert_message_view', $data, TRUE);
		
		//js & css
		$this->_bulidJsCss($data);	
		
		
		$data['page_content'] = $this->load->view($view, $data, TRUE);		
		
		//$data['header_area'] = $this->load->view($this->config->item('backend_name').'/template_header_view', $data, TRUE);		
		//$data['left_menu'] = $this->load->view($this->config->item('backend_name').'/template_left_menu_view', $data, TRUE);
		
		
		 //dprint($this->left_menu_list); 
		
		// 讓瀏覽器不快取
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->output->set_header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
		$this->output->set_header('Pragma: no-cache');

		return $this->load->view($this->config->item('backend_name').'/template_index_view', $data);
	}
	
	/*2代*/
	function displayPlus($view, $data = array() )
	{	
		$data["language_value"] = $this->language_value;
		
		$view="/backend/".$this->router->fetch_class()."/".$view;
		$data['content'] = $this->load->view($view, $data, TRUE);
		
		$data['backend_message'] =$this->session->flashdata('backend_message');
		$data['language_select_list'] = $this->language_select_list;	
		$data['top_menu_list'] = $this->top_menu_list;	
		$data['left_menu_list'] = $this->left_menu_list;	
		$data['header_area'] = $this->load->view('backend/template_header_view', $data, TRUE);		
		$data['left_menu'] = $this->load->view('backend/template_left_menu_view', $data, TRUE);
		
		return $this->load->view('backend/template_index_view', $data);
	}
	
	function addCss($css_value)
	{
		array_push($this->style_css, $css_value);
		
	}
	
	function addJs($js_value)
	{
		array_push($this->style_js, $js_value);
	}
	
	
	/**
	 * 組view所需css及js
	 */
	function _bulidJsCss(&$data = array())
	{
		$data['style_css'] = '';
		$data['style_js'] = '';
		foreach ($this->style_css as $value) 
		{
			$data['style_css'] .= '<link href="'.base_url().$this->config->item("template_backend_path").$value.'" rel="stylesheet" type="text/css" />';    	
		}
		
		
		foreach ($this->style_js as $value) 
		{
			$data['style_js'] .= '<script type="text/javascript" src="'.base_url().$this->config->item("template_backend_path").$value.'"></script>';
		}
	}
	
	
	
	/**
	 * items:相關action  
	 */
	public function addTopMenu($items = array())
	{
		
		$action = "index";
		if(sizeof($items)>0)
		{
			$action = $items[0];
		}				
		
		$url = base_url().$this->config->item('backend_name')."/".$this->router->fetch_class()."/".$action;
		
		$this->top_menu_list[] = array("url"=>$url,"items"=>$items);
	}
	
	public function setSubTitle($sub_title = "")
	{
		$this->sub_title = $sub_title;
	}
	
	
	public function index()
	{
		if($this->top_menu_list!= FALSE && sizeof($this->top_menu_list) > 0)
		{
			redirect($this->top_menu_list[0]["url"]);	
		}			
		else
		{
			$this->redirectHome();	
		}		
	}
		
	
	/**
	 * launch item
	 * @param	string : launch table
	 * @param	string : redirect action
	 * 
	 */
	public function launchItems($launch_str_table,$redirect_action)
	{
		//原本啟用的
		if( isset( $_POST['launch_org'] ) )
		{
			$launch_org = $_POST['launch_org'];
		}			
		else
		{
			$launch_org = array();
		}
			
		
		//被設為啟用的
		if( isset( $_POST['launch'] ) )
		{
			$launch = $_POST['launch'];
		}
		else 
		{
			$launch = array();
		}		
		
		
		//要更改為啟用的
		$launch_on = array_values( array_diff( $launch , $launch_org ) );
		
		//要更改為停用的
		$launch_off = array_values( array_diff( $launch_org , $launch ) );
		
		
		
		//啟用
		if( sizeof( $launch_on ) > 0 )
		{
			$this->it_model->updateData( $launch_str_table , array("launch" => 1),"sn in (".implode(",", $launch_on).")" );	
		}
		
		
		//停用
		if( sizeof( $launch_off ) > 0 )
		{
			$this->it_model->updateData( $launch_str_table , array("launch" => 0),"sn in (".implode(",", $launch_off).")" );	
		}
		
		//$this->output->enable_profiler(TRUE);
		
		$this->showSuccessMessage();
		redirect(bUrl($redirect_action));	
	}
	
	
	
	/**
	 * delete item
	 * @param	string : launch table
	 * @param	string : redirect action
	 * 
	 */
	public function deleteItem($launch_str_table,$redirect_action)
	{
		$del_ary = $this->input->post('del',TRUE);
		if($del_ary!= FALSE && count($del_ary)>0)
		{
			foreach ($del_ary as $item_sn)
			{
				$this->it_model->deleteData( $launch_str_table , array("sn"=>$item_sn) );	
			}
		}		
		$this->showSuccessMessage();
		redirect(bUrl($redirect_action, FALSE));	
	}
	
	/**
	 * delete item
	 * @param	string : launch table
	 * @param	string : redirect action
	 * 
	 */
	public function deleteItemAndFile($launch_str_table,$redirect_action,$del_forder = '')
	{
		$del_ary = $this->input->post('del',TRUE);
		if($del_ary!= FALSE && count($del_ary)>0)
		{
			foreach ($del_ary as $item_sn)
			{				
				$this->it_model->deleteData( $launch_str_table , array("sn"=>$item_sn) );
				
				if($this->input->post('del_file_'.$item_sn,TRUE) !== FALSE)
				{
					@unlink($del_forder.$this->input->post('del_file_'.$item_sn,TRUE));		
				}	
			}
		}		
		$this->showSuccessMessage();
		redirect(bUrl($redirect_action, FALSE));	
	}
	
	public function showSuccessMessage($msg=null)
	{
		if ( isNotNull($msg) ) {
			$this->showMessage( $msg );
		} else {
			$this->showMessage('資料更新成功!!');
		}
	}
	
	public function showFailMessage($msg=null)
	{
		if ( isNotNull($msg) ) {
			$this->showMessage($msg,'backend_error');
		} else {
			$this->showMessage('資料更新失敗，請稍後再試!!','backend_error');
		}
	}
	
	public function showMessage($message = '', $calss = 'backend_message')
	{
		$this->session->set_flashdata('backend_message',$message);
	}
	
	
	
	
	/**
	 * page edit page
	 */
	public function editPage()
	{
		
		$page_sn = $this->input->get('sn');
			
		$this->sub_title = $this->lang->line("page_form");	
		
		$page_info = $this->it_model->listData("html_page","page_id ='".$this->router->fetch_class()."'");
		
				
		if($page_info["count"] == 0)
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'launch' =>1
			);			
		}
		else 
		{			
			$data["edit_data"] = $page_info["data"][0];		
		}
		
		$this->display($this->config->item('backend_name')."/page/page_form_view",$data);		
	}
	
	
	/**
	 * 更新page
	 */
	public function updatePage()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		$edit_data["content"] = $this->input->post("content");	
				
		if ( ! $this->_validatepage())
		{
			$data["edit_data"] = $edit_data;		
				
			$this->display($this->config->item('backend_name')."/page/page_form_view",$data);
		}
        else 
        {
        			
        	$arr_data = array
        	(	
        		  "title" =>  tryGetData("title",$edit_data)     
				, "page_id" =>  $this->router->fetch_class()  		
				, "start_date" => date( "Y-m-d" )
				, "end_date" => NULL
				, "forever" => 1	
				, "launch" => 1	
				, "sort" => tryGetData("sort",$edit_data,500)
				, "target" => tryGetData("target",$edit_data)
				, "content" => tryGetData("content",$edit_data)
				, "update_date" => date( "Y-m-d H:i:s" )
			);        	
			
					
			
			if(isNotNull($edit_data["sn"]))
			{
				if($this->it_model->updateData( "html_page" , $arr_data, "sn =".$edit_data["sn"] ))
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
				
				$page_sn = $this->it_model->addData( "html_page" , $arr_data );
				if($page_sn > 0)
				{				
					$edit_data["sn"] = $page_sn;
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}				
			}			
			
			redirect(bUrl("editPage"));		
        }	
	}
	
	/**
	 * 驗證page edit 欄位是否正確
	 */
	function _validatePage()
	{
	
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		

		//$this->form_validation->set_rules( 'page_id', "Page ID", 'required|alpha_dash' );	
		$this->form_validation->set_rules( 'title', "單元名稱", 'required' );	
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}
	
	/**
	 * 分頁
	 */	
	public function getPager($total_count,$cur_page,$per_page,$redirect_action)
	{
		$config['total_rows'] = $total_count;
		$config['cur_page'] = $cur_page;
		$config['per_page'] = $per_page;		
		
		$this->pagination->initialize($config);
		$pager = $this->pagination->create_links();		
		$pager['action'] = $redirect_action;
		$pager['per_page_rows'] = $per_page;
		$pager['total_rows'] = $total_count;		
		//$offset = $this->pagination->offset;
		//$per_page = $this->pagination->per_page;
				
		return $pager;	
	} 
	
	
	//記得要加上media bank權限
	function loadElfinder()
	{
		$this->load->helper('path');
	  
		$opts = array(
		// 'debug' => true,
		
			'roots' => array(
				array(
					'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
					'path'          => set_realpath('upload')."media",     // path to files (REQUIRED)
					'URL'           => site_url('upload').'/media', // URL to files (REQUIRED)
					'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
				)
			)
		);

	  $this->load->library('elfinderlib', $opts);
	}
	
	
	public function sortContent($table_name = "web_menu_content", $redirect_page = "contentList")
	{
		$sort_ary = $this->input->post('sort',TRUE);	
		$sort_sn_ary = $this->input->post('sort_sn',TRUE);	
		
		for ($i=0; $i < count($sort_ary) ; $i++) 
		{
			$this->it_model->updateData( $table_name , array("sort" => $sort_ary[$i]),"sn ='".$sort_sn_ary[$i]."'" );
		}

		$this->showSuccessMessage();
		redirect(bUrl($redirect_page, TRUE));	
	}
	
	
	/*
	 * 紀錄log
	 * $desc:log描述
	function logData($desc="",$action = 1)
	{
		$table_name = $this->it_model->tryAddLogTable();
			
		$date = new DateTime();
			
		$arr_data = array(				

			 "user_id" => $this->session->userdata("user_id")
			, "session_id"=> $this->session->userdata('session_id')
			, "ip"=> $this->input->ip_address()
			, "module_id" =>  $this->module_id
			, "desc" => $desc
			, "action" => $action
			, "active_time" =>  $date->getTimestamp()	
			, "create_date" =>  date( "Y-m-d H:i:s" )
		);      
		$sys_user_sn = $this->it_model->addData( $table_name , $arr_data );		
	}
	 */
	
	
	function traceLog()
	{
		$date = new DateTime();
		
		$last_module_id = $this->session->flashdata('last_module_id');		
		$session_id = $this->session->userdata('session_id');		
		$table_name = "sys_backend_log_".date( "Y" );
		$log_info = $this->it_model->listData($table_name , "session_id = '".$session_id."' AND module_id = '".$this->module_id."' AND action = 0  AND ip = '".$this->input->ip_address()."'");
			
		
			
		if($log_info["count"]>0)
		{			
			
			$active_time = $log_info['data'][0]['active_time'];
			$stay_time = $log_info['data'][0]['stay_time'];
			
			if($last_module_id == $this->module_id)
			{
				$stay_time = ($date->getTimestamp() - $active_time) + $stay_time;
				$result = $this->it_model->updateDB( $table_name , array("stay_time"=>$stay_time,"active_time" =>  $date->getTimestamp()), "session_id = '".$session_id."' AND module_id = '".$this->module_id."' AND action = 0  AND ip = '".$this->input->ip_address()."'");
				//dprint($result);
			}
			else 
			{
				//$stay_time = ($date->getTimestamp() - $active_time) + $stay_time;
				//$result = $this->it_model->updateDB( $table_name , array("stay_time"=>$stay_time,"active_time" =>  $date->getTimestamp()), "session_id = '".$session_id."' AND module_id = '".$last_module_id."' AND action = 0  AND ip = '".$this->input->ip_address()."'");
				//dprint($result);
			}			
		}
		else 
		{
			logData("",0);			
			//$this->it_model->updateData( $table_name , array("stay_time"=>6), "session_id = '".$session_id."' and ip = '".$this->input->ip_address()."'");
			
			
		}
		
		$this->session->set_flashdata('last_module_id',$this->module_id);
		
	}
	
	
	
	function profiler()
	{
		$this->output->enable_profiler(TRUE);	
	}
	
	
	function dealPost()
	{
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		$edit_data["content"] = $this->input->post("content");	
		
		$arr_data = array
		(				
		     "sn" => tryGetData("sn",$edit_data,NULL)	
			, "comm_id" => $this->getCommId()	
			, "parent_sn" => tryGetData("parent_sn",$edit_data,NULL)	
			, "title" => tryGetData("title",$edit_data)	
			, "brief" => tryGetData("brief",$edit_data)
			, "brief2" => tryGetData("brief2",$edit_data)	
			, "id" => tryGetData("id",$edit_data,NULL)	
			, "content_type" => tryGetData("content_type",$edit_data)	
			, "filename" => tryGetData("filename",$edit_data)		
			, "img_filename" => tryGetData("img_filename",$edit_data)			
			, "img_filename2" => tryGetData("img_filename2",$edit_data)
			, "start_date" => tryGetData("start_date",$edit_data,date( "Y-m-d H:i:s" ))
			, "end_date" => tryGetData("end_date",$edit_data,NULL)
			, "forever" => tryGetData("forever",$edit_data,0)
			, "launch" => tryGetData("launch",$edit_data,0)
			, "hot" => tryGetData("hot",$edit_data,0)
			, "sort" => tryGetData("sort",$edit_data,500)
			, "url" => tryGetData("url",$edit_data)
			, "target" => tryGetData("target",$edit_data,0)
			, "content" => tryGetData("content",$edit_data)
			, "update_date" =>  date( "Y-m-d H:i:s" )
		);        	
		
		
		
		return $arr_data;
	}



	/**
	 * web_menu_content 同步至雲端server
	 */
	function sync_to_server($post_data)
	{
		$url = "http://localhost/commapi/sync/updateContent/";
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
		
		$this->it_model->updateData( "web_menu_content" , array("is_sync"=>$is_sync,"update_date"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
		//------------------------------------------------------------------------------
	}



	//ajax 取得
	public function ajaxChangeStatus($table_name ='', $field_name = ',',$sn)
    {

        
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
				echo json_encode($change_value);
			}
			else
			{
				echo json_encode($data_info[$field_name]);
			}
			                      
        }
    }
	
	
	//ajax 取得
	public function ajaxlaunchContent($sn)
    {
		$table_name = 'web_menu_content';
        $field_name = 'launch';
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
	
	
	
	
    public function ajaxChangeStatus1()
    {   
        $edit_data = array();
        foreach( $_POST as $key => $value )
        {
            $edit_data[$key] = $this->input->post($key, FALSE);         
        }
        
        if(isNull(tryGetData("tname",$edit_data)) || isNull(tryGetData("fname",$edit_data)) || isNull(tryGetData("sn",$edit_data)) )
        {
            echo json_encode(array());
        }
        else 
        {
        	$table_name = tryGetData("tname",$edit_data);
			$field_name = tryGetData("fname",$edit_data);
			$sn = tryGetData("sn",$edit_data);
			

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
				echo json_encode($change_value);
			}
			else 
			{
				echo json_encode($data_info[$field_name]);
			}			
			                      
        }
    }



	
	
	/**
	 * 取得社區id
	 */
	function getCommId()
	{
		$comm_id = $this->session->userdata("comm_id");
		return $comm_id;
	}
	
	
}