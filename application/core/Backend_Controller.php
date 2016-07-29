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

	public $building_part_01 = "";	
	public $building_part_02 = "";	
	public $building_part_01_array = array();
	public $building_part_02_array = array();
	public $addr_part_01_array = array();
	public $addr_part_02_array = array();

	function __construct() 
	{
		parent::__construct();
		
		if(!checkUserLogin())
		{
			redirect(backendUrl("login","index",FALSE));
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
		
		// 取得戶別相關參數
		$this->load->model('auth_model');
		$this->building_part_01 = $this->auth_model->getWebSetting('building_part_01');
		$building_part_01_value = $this->auth_model->getWebSetting('building_part_01_value');
		$this->building_part_02 = $this->auth_model->getWebSetting('building_part_02');
		$building_part_02_value = $this->auth_model->getWebSetting('building_part_02_value');
		$this->building_part_03 = $this->auth_model->getWebSetting('building_part_03');
		$addr_part_01 = $this->auth_model->getWebSetting('addr_part_01');
		$addr_part_02 = $this->auth_model->getWebSetting('addr_part_02');

		if (isNotNull($building_part_01_value)) {
			$this->building_part_01_array = array_merge(array(0=>' -- '), explode(',', $building_part_01_value));
		}

		if (isNotNull($building_part_02_value)) {
			$this->building_part_02_array = array_merge(array(0=>' -- '), explode(',', $building_part_02_value));
		}
<<<<<<< HEAD

		if ( $addr_part_01 !== false ) 
			$this->addr_part_01_array = array_merge(array(0=>' -- '), explode(',', $addr_part_01));
		else 
			$this->addr_part_01_array = array(0=>' -- ');

		if ( $addr_part_02 !== false ) 
			$this->addr_part_02_array = array_merge(array(0=>' -- '), explode(',', $addr_part_02));
		else 
			$this->addr_part_02_array = array(0=>' -- ');
=======
		
		if ($addr_part_01 !== false) {
			$this->addr_part_01_array = array_merge(array(0=>' -- '), explode(',', $addr_part_01));
		} else {
			$this->addr_part_01_array = array(0=>' -- ');
		}
		
		if ($addr_part_02 !== false) {
			$this->addr_part_02_array = array_merge(array(0=>' -- '), explode(',', $addr_part_02));
		} else {
			$this->addr_part_02_array = array(0=>' -- ');
		}
>>>>>>> a6a90385ddf710c3afdb8b59d1a1a5e2c5e29bda

		
		$this->parking_part_01 = $this->auth_model->getWebSetting('parking_part_01');
		$parking_part_01_value = $this->auth_model->getWebSetting('parking_part_01_value');
		$this->parking_part_02 = $this->auth_model->getWebSetting('parking_part_02');
		$parking_part_02_value = $this->auth_model->getWebSetting('parking_part_02_value');
		$this->parking_part_03 = $this->auth_model->getWebSetting('parking_part_03');
		if (isNotNull($parking_part_01_value)) {
			$this->parking_part_01_array = array_merge(array(0=>' -- '), explode(',', $parking_part_01_value));
		}

		if (isNotNull($parking_part_02_value)) {
			$this->parking_part_02_array = array_merge(array(0=>' -- '), explode(',', $parking_part_02_value));
		}
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
		
		if(isNotNull(tryGetData("img_filename",$edit_data)))
		{
			$arr_data["img_filename"] = tryGetData("img_filename",$edit_data);
		}
		
		if(isNotNull(tryGetData("img_filename2",$edit_data)))
		{
			$arr_data["img_filename2"] = tryGetData("img_filename2",$edit_data);
		}	
		
		
		return $arr_data;
	}



	/**
	 * web_menu_content 同步至雲端server
	 */
	function sync_to_server($post_data)
	{
		//$url = "http://localhost/commapi/sync/updateContent";
		$url = $this->config->item("api_server_url")."sync/updateContent";
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
	
	
	
	/**
	 * web_menu_content 離線同步
	 */
	function check_offline_sync()
	{
		$wait_sync_list = $this->it_model->listData("web_menu_content","is_sync =0");
		foreach( $wait_sync_list["data"] as $key => $item )
		{
			$this->sync_to_server($item);
		}
	}
	
	
	
	/**
	 * 詢問server檔案差異
	 * $folder : /upload/社區ID 下的資料夾
	 */
	function ask_server_file($file_string,$folder)
	{
		if(isNull($file_string))
		{
			return;
		}
		$post_data = array();
		$post_data["file_string"] = $file_string;
		$post_data["comm_id"] = $this->getCommId();
		$post_data["folder"] = $folder;
		
		$url = $this->config->item("api_server_url")."sync/askFile";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$file_list = curl_exec($ch);
		curl_close ($ch);

		return $file_list;
	}
	
	/**
	 * 檔案同步至server
	 * $folder : /upload/社區ID 下的資料夾
	 */
	public function sync_file($folder="")
	{
		if(isNull($folder))
		{
			return;
		}
		
		//$folder = "news";
		$sync_folder = set_realpath("upload/".$this->getCommId()."/".$folder);
		$files = glob($sync_folder . '*');
		
		$filename_ary = array();
		foreach( $files as $key => $file_name_with_full_path )
		{
			array_push($filename_ary,basename($file_name_with_full_path));
		}		

		$upload_file_list = $this->ask_server_file(implode(",",$filename_ary),$folder);
		$upload_file_ary = explode(",",$upload_file_list);
		
		foreach( $upload_file_ary as $key => $file_name )
		{		
			$file_name_with_full_path = set_realpath("upload/".$this->getCommId()."/".$folder).$file_name;
		
			$cfile = new CURLFile($file_name_with_full_path);			
			$params = array($this->getCommId().'<#-#>'.$folder => $cfile );			

			$target_url = $this->config->item("api_server_url")."sync/fileUpload";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$target_url);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			$result = curl_exec($ch);
			curl_close ($ch);

		}	
		//	dprint($result);die;
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
	
	
	
	


	/**
	 * 同步至雲端server
	 */
	function sync_item_to_server($post_data,$func_name,$table_name)
	{
		$url = $this->config->item("api_server_url")."sync/".$func_name;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$is_sync = curl_exec($ch);
		curl_close ($ch);

		/* debug
		if ($table_name =='house_to_rent_photo') {
			dprint($url);
			dprint($post_data);
			dprint($is_sync);
			die;
		}
		*/
		
		//更新同步狀況
		//------------------------------------------------------------------------------
		if($is_sync != '1')
		{
			$is_sync = '0';
		}			
		
		$this->it_model->updateData( $table_name , array("is_sync"=>$is_sync,"updated"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );


		//------------------------------------------------------------------------------
	}
	
	/**
	 * mailbox 離線同步
	 */
	function check_mailbox_offline_sync()
	{
		$wait_sync_list = $this->it_model->listData("mailbox","is_sync =0");
		foreach( $wait_sync_list["data"] as $key => $item )
		{
			$this->sync_item_to_server($item,"updateMailbox","mailbox");
		}
	}
	
	
	/**
	 * repair 離線同步
	 */
	function check_repair_offline_sync()
	{
		$wait_sync_list = $this->it_model->listData("repair","is_sync =0");
		foreach( $wait_sync_list["data"] as $key => $item )
		{
			$this->sync_item_to_server($item,"updateRepair","repair");			
		}
		
		$sub_wait_sync_list = $this->it_model->listData("repair_reply","is_sync =0");
		foreach( $sub_wait_sync_list["data"] as $key => $item )
		{
			$item["comm_id"] = $this->getCommId();
			$this->sync_item_to_server($item,"updateRepairReply","repair_reply");			
		}
		
	}
	
	/**
	 * suggestion 離線同步
	 */
	function check_suggestion_offline_sync()
	{
		$wait_sync_list = $this->it_model->listData("suggestion","is_sync =0");
		foreach( $wait_sync_list["data"] as $key => $item )
		{
			$this->sync_item_to_server($item,"updateSuggestion","suggestion");			
		}		
	}
	
	/**
	 * gas 離線同步
	 */
	function check_gas_offline_sync()
	{
		$wait_sync_list = $this->it_model->listData("gas","is_sync =0");
		foreach( $wait_sync_list["data"] as $key => $item )
		{
			$this->sync_item_to_server($item,"updateGas","gas");			
		}		
	}
	

	
	/**
	 * User 離線同步
	 */
	function check_user_sync()
	{
		$wait_sync_list = $this->it_model->listData("sys_user","role='I' and is_sync =0");
		foreach( $wait_sync_list["data"] as $key => $item )
		{
			$this->sync_item_to_server($item,"updateUser","sys_user");			
		}
	}

	/**
	 * House to Rent 離線同步
	 */
	function check_house_to_rent_sync()
	{
		$wait_sync_list = $this->it_model->listData("house_to_rent","is_sync =0");
		foreach( $wait_sync_list["data"] as $key => $item )
		{
			$this->sync_item_to_server($item,"updateRentHouse","house_to_rent");			
		}
	}
	
	/**
	 * House to Sale 離線同步
	 */
	function check_house_to_sale_sync()
	{
		$wait_sync_list = $this->it_model->listData("house_to_sale","is_sync =0");
		foreach( $wait_sync_list["data"] as $key => $item )
		{
			$this->sync_item_to_server($item,"updateSaleHouse","house_to_sale");			
		}
	}
	







	/**
	 * 查詢server上有無edoma資料
	 **/
	public function getEdomaHouseToSale()
	{
			//$ddd = $this->it_model->listData( "house_to_sale");
			//dprint($ddd);
			//die;
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync_edoma_house/getEdomaHouseToSale";

		// dprint($url);
		// dprint($post_data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$json_data = curl_exec($ch);
		curl_close ($ch);
		
		
		$edoma_data_ary =  json_decode($json_data, true);

//dprint( $edoma_data_ary );
//die;

		if( ! is_array($edoma_data_ary))
		{
			$edoma_data_ary = array();
		}

		foreach( $edoma_data_ary as $key => $server_info ) 
		{	
			$ok_flag = false;

			$server_sn = $server_info["sn"];
			$edoma_sn = tryGetData("edoma_sn",$server_info);
			$arr_data = array
			(
				 "comm_id"		=>	$this->getCommId()	
				, "del"			=>	tryGetData("del",$server_info,0)
				, "is_edoma"	=>	1
				, "edoma_sn"	=>	$edoma_sn
				, "title"		=>	tryGetData("title",$server_info)

				, "sale_type"	=>	tryGetData("sale_type",$server_info)
				, "house_type"	=>	tryGetData("house_type",$server_info)
				, "direction"	=>	tryGetData("direction",$server_info)
				, "name"		=>	tryGetData("name",$server_info)
				, "phone"		=>	tryGetData("phone",$server_info)
				, "area_desc"	=>	tryGetData("area_desc",$server_info)

				, "total_price"	=>	tryGetData("total_price",$server_info)
				, "unit_price"	=>	tryGetData("unit_price",$server_info)
				, "manage_fee"	=>	tryGetData("manage_fee",$server_info)
				, "area_ping"	=>	tryGetData("area_ping",$server_info)
				, "house_age"	=>	tryGetData("house_age",$server_info)
				, "pub_ratio"	=>	tryGetData("pub_ratio",$server_info)
				, "room"	=>	tryGetData("room",$server_info)
				, "livingroom"	=>	tryGetData("livingroom",$server_info)
				, "bathroom"	=>	tryGetData("bathroom",$server_info)
				, "balcony"	=>	tryGetData("balcony",$server_info)
				, "locate_level"	=>	tryGetData("locate_level",$server_info)
				, "total_level"	=>	tryGetData("total_level",$server_info)
				, "usage"	=>	tryGetData("usage",$server_info)
				, "current"	=>	tryGetData("current",$server_info)
				, "flag_rent"	=>	tryGetData("flag_rent",$server_info)
				, "flag_parking"	=>	tryGetData("flag_parking",$server_info)
				, "addr"	=>	tryGetData("addr",$server_info)
				, "decoration"	=>	tryGetData("decoration",$server_info)
				, "start_date" => tryGetData("start_date",$server_info,NULL)
				, "end_date" => tryGetData("end_date",$server_info,NULL)
				, "forever" => tryGetData("forever",$server_info,0)
				, "launch" => tryGetData("launch",$server_info,0)
				, "living"	=>	tryGetData("living",$server_info)
				, "traffic"	=>	tryGetData("traffic",$server_info)
				, "desc"	=>	tryGetData("desc",$server_info)
				, "updated" =>  date( "Y-m-d H:i:s" )
				, "created" =>  date( "Y-m-d H:i:s" )
			);

			$upd = $this->it_model->updateData( "house_to_sale" , $arr_data, "server_sn =".$server_sn );
			
			if ($upd === false) {
				$arr_data['server_sn'] = $server_sn;
				$arr_data['created'] = date( "Y-m-d H:i:s" );
				$house_to_sale_sn = $this->it_model->addData( "house_to_sale" , $arr_data );

				//		dprint( $this->db->last_query());
				//		dprint( $this->db->_error_message()); 
				//		dprint( $int);
				//      echo 'add ok';

				$ok_flag = true;

				       echo 'house_to_sale_sn : '. $house_to_sale_sn;

			} else {

				$ok_flag = true;
				       echo 'updated ok';
				 		dprint( $this->db->last_query());
				//		dprint( $this->db->_error_message()); 
						dprint( $upd);

				$tmp = $this->it_model->listData( "house_to_sale" , "server_sn =".$server_sn );
				$house_to_sale_sn = $tmp['data'][0]['sn'];
				       echo 'house_to_sale_sn = '. $house_to_sale_sn;
			}


			//edoma_sn
			$post_data = array();
			$post_data["comm_id"] = $this->getCommId();
			$post_data["edoma_house_to_sale_sn"] = $edoma_sn;
			$url = $this->config->item("api_server_url")."sync_edoma_house/getEdomaHouseToSalePhoto";


			// dprint($url);
			// dprint($post_data);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			$json_photo = curl_exec($ch);
			curl_close ($ch);

			$edoma_photo_ary =  json_decode($json_photo, true);
dprint('<hr color=red>');
dprint($edoma_photo_ary);
/*
Array
(
    [sn] => 8
    [client_house_to_sale_sn] => 
    [client_sn] => 
    [edoma_house_to_sale_sn] => 1
    [edoma_sn] => 1
    [client_sync] => 0
    [del] => 0
    [filename] => 20160627013812_628277.jpg
    [title] => tooth gray
    [updated] => 2016-06-27 01:38:12
    [updated_by] => 管理者
)
*/
dprint('<hr color=blue>');
			if( ! is_array($edoma_photo_ary))
			{
				$edoma_photo_ary = array();
			}
$edoma_photo_ary = array();
			foreach( $edoma_photo_ary as $key => $server_photo_info ) 
			{	
				$ok_flag = false;
				$server_sn = tryGetData("sn",$server_photo_info);
				$arr_data = array
				(	"is_edoma"	=>	1
					//, "edoma_house_to_sale_sn"	=>	tryGetData("edoma_house_to_sale_sn",$server_photo_info)
					, "title"		=>	tryGetData("title",$server_photo_info)
					, "del"			=>	tryGetData("del",$server_photo_info,0)
					, "filename"	=>	tryGetData("filename",$server_photo_info)
					, "updated"		=>  tryGetData("updated",$server_photo_info)
					, "updated_by"	=>  tryGetData("updated_by",$server_photo_info)
				);

				$upd = $this->it_model->updateData( "house_to_sale_photo" , $arr_data, "server_sn =".$server_sn );
				
				if ($upd === false) {
					$arr_data['server_sn'] = $server_sn;
					$arr_data['edoma_house_to_sale_sn'] = $edoma_house_to_sale_sn;
					$arr_data['created'] = date( "Y-m-d H:i:s" );
					$int = $this->it_model->addData( "house_to_sale_photo" , $arr_data );

							dprint( $this->db->last_query());
					//		dprint( $this->db->_error_message()); 
					//		dprint( $int);
					      echo 'add OOok';




				} else {
					      echo 'updated OOok';
							dprint( $this->db->last_query());
					//		dprint( $this->db->_error_message()); 
					//		dprint( $upd);
				}
			
			}

		}
	}




	/**
	 * 查詢server上有無edoma資料
	 **/
	public function getEdomaHouseToSalePhoto()
	{
			//$ddd = $this->it_model->listData( "house_to_sale");
			//dprint($ddd);
			//die;
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync_edoma_house/getEdomaHouseToSalePhoto";

		// dprint($url);
		// dprint($post_data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$json_data = curl_exec($ch);
		curl_close ($ch);
		
//echo $json_data;
		
		$edoma_data_ary =  json_decode($json_data, true);

// dprint($edoma_data_ary);exit;

		if( ! is_array($edoma_data_ary))
		{
			$edoma_data_ary = array();
		}

		foreach( $edoma_data_ary as $key => $server_info ) 
		{	
			$server_sn = $server_info["sn"];
			$arr_data = array
			(
				 "comm_id"		=>	$this->getCommId()	
				, "del"			=>	tryGetData("del",$server_info,0)
				, "is_edoma"	=>	1
				, "title"		=>	tryGetData("title",$server_info)
				, "traffic"	=>	tryGetData("traffic",$server_info)
				, "desc"	=>	tryGetData("desc",$server_info)
				, "updated" =>  date( "Y-m-d H:i:s" )
				, "created" =>  date( "Y-m-d H:i:s" )
			);

			$upd = $this->it_model->updateData( "house_to_sale_photo" , $arr_data, "server_sn =".$server_sn );
			
			if ($upd === false) {
				$arr_data['server_sn'] = $server_sn;
				$arr_data['created'] = date( "Y-m-d H:i:s" );
				$int = $this->it_model->addData( "house_to_sale_photo" , $arr_data );

				//		dprint( $this->db->last_query());
				//		dprint( $this->db->_error_message()); 
				//		dprint( $int);
				//      echo 'add ok';




			} else {
				//      echo 'updated ok';
				//		dprint( $this->db->last_query());
				//		dprint( $this->db->_error_message()); 
				//		dprint( $upd);
			}
		}
	}

	
	
	/**
	 * 查詢server上有無edoma資料
	 **/
	public function getEdomaData()
	{
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync_edoma/getEdomaContent";
		//dprint($post_data);exit;
		
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
				 "comm_id" => $this->getCommId()	
				, "server_sn" => $server_info["sn"]				
				, "title" => tryGetData("title",$server_info)	
				, "brief" => tryGetData("brief",$server_info)
				, "brief2" => tryGetData("brief2",$server_info)	
				, "id" => tryGetData("id",$server_info,NULL)	
				, "content_type" => tryGetData("content_type",$server_info)	
				, "filename" => tryGetData("filename",$server_info)
				, "img_filename" => tryGetData("img_filename",$server_info)
				, "start_date" => tryGetData("start_date",$server_info,NULL)
				, "end_date" => tryGetData("end_date",$server_info,NULL)
				, "forever" => tryGetData("forever",$server_info,0)
				, "launch" => tryGetData("launch",$server_info,0)
				, "hot" => tryGetData("hot",$server_info,0)
				, "sort" => tryGetData("sort",$server_info,500)
				, "url" => tryGetData("url",$server_info)
				, "target" => tryGetData("target",$server_info,0)
				, "content" => tryGetData("content",$server_info)
				, "update_date" =>  date( "Y-m-d H:i:s" )
				, "del" => tryGetData("del",$server_info,0)
				, "is_edoma" => 1
			);        	
			

		
			$content_server_info = $this->it_model->listData("web_menu_content","server_sn = '".$server_info["sn"]."'");
			if($content_server_info["count"]==0)
			{
				$arr_data["create_date"] =   date( "Y-m-d H:i:s" );
				$content_sn = $this->it_model->addData( "web_menu_content" , $arr_data );
				if($content_sn > 0)
				{
					//sync image
					//--------------------------------------------------------------------
					if( isNotNull(tryGetData("img_filename",$server_info)) )
					{
						$img_url = $this->config->item("api_server_url")."upload/edoma/".$server_info["content_type"]."/".$server_info["img_filename"];
						$saveto = set_realpath("upload/website/".$server_info["content_type"]).$server_info["img_filename"];
						$this->download_image($img_url,$saveto);
					}					
					//--------------------------------------------------------------------
					
					
					$arr_data["sn"] = $content_sn;					
					$this->sync_edoma_to_server($arr_data);
				}
				
			}
			else
			{
				$content_server_info = $content_server_info["data"][0];
				$result = $this->it_model->updateData( "web_menu_content" , $arr_data, "server_sn = '".$server_info["sn"]."'" );
				if($result)
				{					
					//sync image
					//--------------------------------------------------------------------
					if( isNotNull(tryGetData("img_filename",$server_info)) )
					{
						$img_url = $this->config->item("api_server_url")."upload/edoma/".$server_info["content_type"]."/".$server_info["img_filename"];
						$saveto = set_realpath("upload/website/".$server_info["content_type"]).$server_info["img_filename"];
						//dprint($img_url);
						//dprint($saveto);
						//exit;
						$this->download_image($img_url,$saveto);
					}					
					//--------------------------------------------------------------------
			
					$arr_data["sn"] = $content_server_info["sn"];				
					$this->sync_edoma_to_server($arr_data);
				}
			}
						
		}
		
		//echo '<meta charset="UTF-8">';
		//dprint($app_data_ary);
		
	}
	
	
	/**
	 * web_menu_content 同步至雲端server
	 */
	function sync_edoma_to_server($post_data)
	{
		$url = $this->config->item("api_server_url")."sync_edoma/updateEdomaContent";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$is_sync = curl_exec($ch);
		curl_close ($ch);
		
		//echo $is_sync;exit;
		//更新同步狀況
		//------------------------------------------------------------------------------
		if($is_sync != '1')
		{
			$is_sync = '0';
		}			
		
		$this->it_model->updateData( "web_menu_content" , array("is_sync"=>$is_sync,"update_date"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
		//------------------------------------------------------------------------------
	}
		
	/**
	 * 下載圖片
	 */
	function download_image($url,$saveto)
	{
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$raw=curl_exec($ch);
		curl_close ($ch);
		if(file_exists($saveto)){
			unlink($saveto);
		}
		$fp = fopen($saveto,'x');
		fwrite($fp, $raw);
		fclose($fp);
	}
	
	
	/**
	 * 設定web_menu_photo照片
	 */
	public function contentPhoto()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");		
		
		$content_sn = tryGetData('sn', $_GET, NULL);
		
		if ( isNotNull($content_sn) ) {
			## 物件基本資料
			$content_info = $this->it_model->listData( "web_menu_content" , "sn =".$content_sn);
			
			if ($content_info["count"] > 0) 
			{
				$edit_data =$content_info["data"][0];
				
				$data['content_info'] = $edit_data;

				## 既有照片list
				$photo_list = $this->it_model->listData( "web_menu_photo" , "content_sn =".$content_sn);
				$data["photo_list"] = $photo_list["data"];
				
				$this->display("photo_setting_view",$data);
			}
			else
			{
				redirect(bUrl("contentList"));	
			}

		}
		else 
		{
			redirect(bUrl("contentList"));	
		}
	}



	/**
	 * web_menu_photo照片上傳
	 */
	public function updateContentPhoto()
	{
		$edit_data = array();
		foreach( $_POST as $key => $value ) 
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		$content_sn = tryGetData('content_sn', $edit_data, NULL);
		$comm_id = tryGetData('comm_id', $edit_data, NULL);
		$config['upload_path'] = './upload/content_photo/'.$edit_data['content_sn'];
		$config['allowed_types'] = 'jpg|png';
		
		
		$filename = date( "YmdHis" )."_".rand( 100000 , 999999 );	
		$config['file_name'] = $filename;
		$config['overwrite'] = false;
		
		
		//$config['max_size']	= '1000';
		//$config['max_width']  = '1200';
		//$config['max_height']  = '1000';
		$config['overwrite']  = true;

		$this->load->library('upload', $config);

		if (!is_dir('./upload/content_photo/')) 
		{
			mkdir('./upload/content_photo/', 0777, true);
		}
		
		if (!is_dir('./upload/content_photo/'.$edit_data['content_sn'])) 
		{
			mkdir('./upload/content_photo/'.$edit_data['content_sn'], 0777, true);
		}

		if ( isNull($content_sn) || ! $this->upload->do_upload('img_filename'))
		{
			$error = array('error' => $this->upload->display_errors());

			$this->showFailMessage('圖片上傳失敗，請稍後再試　' .$error['error'] );
		} 
		else 
		{

			$upload = $this->upload->data();
			$img_filename = tryGetData('file_name', $upload);
			
			$arr_data = array(							
							  'content_sn'	=>	tryGetData('content_sn', $edit_data)
							, 'img_filename'			=>	$img_filename
							, 'title'				=>	tryGetData('title', $edit_data)
							, 'updated'				=>	date('Y-m-d H:i:s')
							, 'updated_by'			=>	$this->session->userdata('user_name')
							, 'created'				=>	date('Y-m-d H:i:s')
							);

			$photo_sn = $this->it_model->addData('web_menu_photo', $arr_data);
			if ( $this->db->affected_rows() > 0 or $this->db->_error_message() == '') 
			{
				$this->pingConentPhoto(tryGetData('content_sn', $edit_data));
				$this->showSuccessMessage('圖片上傳成功');
			} else {
				$this->showFailMessage('圖片上傳失敗，請稍後再試');
			}
		}

		redirect(bUrl("contentPhoto"));
	}

	/**
	 * 刪除web_menu_photo照片
	 */
	function deleteContentPhoto()
	{
		$del_array = $this->input->post("del",TRUE);
		if(count($del_array)>0)
		{			
			$content_sn = 0;
			foreach( $del_array as $item ) 
			{

				$tmp = explode('!@', $item);
				$sn = $tmp[0];
				$content_sn = $tmp[1];
				$filename = $tmp[2];

				unlink('./upload/content_photo/'.$content_sn.'/'.$filename);

				$del = $this->it_model->deleteData('web_menu_photo',  array('sn' => $sn));
				
				if ($del) 
				{			
					
				}
			}
			
			$this->pingConentPhoto($content_sn);
		}
		$this->showSuccessMessage('圖片刪除成功');


		redirect(bUrl("contentPhoto"));
	}
	
	
	/**
	 * 拼接web_menu_photo照片
	 */
	function pingConentPhoto($content_sn)
	{
		ini_set("memory_limit","256M");
		$content_info = $this->it_model->listData("web_menu_content","sn = '".$content_sn."'");
		if($content_info["count"]==0)
		{
			return;
		}
		$content_info = $content_info["data"][0];
		
		$photo_list = $this->it_model->listData( "web_menu_photo" , "content_sn =".$content_sn);
		if($photo_list["count"]==0)
		{
			return;
		}
		$source = array();
		
		$dest_width = 0;
		$dest_height = 0;
		foreach( $photo_list["data"] as $key => $photo ) 
		{
			$img = set_realpath("upload/content_photo/".$content_sn).$photo["img_filename"];
			
			$exploded = explode('.',$photo["img_filename"]);
			$ext = $exploded[count($exploded) - 1]; 

			if (preg_match('/jpg|jpeg/i',$ext))
			{
				$source[$key]['source']=imagecreatefromjpeg($img);
			}				
			else if(preg_match('/png/i',$ext))
			{
				$source[$key]['source']=imagecreatefrompng($img);
			}			
			else if (preg_match('/gif/i',$ext))
			{
				$source[$key]['source']=imagecreatefromgif($img);
			}				
			else if (preg_match('/bmp/i',$ext))
			{
				$source[$key]['source']=imagecreatefrombmp($img);
			}
				

			$source[$key]['size'] = getimagesize($img);
			
			if($source[$key]['size'][0] > $dest_width)
			{
				$dest_width = (int)$source[$key]['size'][0];
			}
			
			
			$dest_height += (int)($source[$key]['size'][1] + 2);
			
			
			//echo '<br>'.;
			//echo '<br>'.$photo["img_filename"];
		}
		//dprint($source);die;
		
		
		$dest = imagecreatetruecolor($dest_width, $dest_height);
		$red = imagecolorallocate($dest, 255, 255, 255);
		imagefill($dest, 0, 0, $red);
				
		
		$dest_y = 0;
		$dest_x = 0;
		
		foreach( $source as $key => $item ) 
		{
			//echo '<br>-->'.$target_img;
			//dprint($item);
			
			/*
			語法 : int ImageCopy (source dst_im, source src_im, int dst_x, int dst_y, int src_x, int src_y, int src_w, int src_h)
			說明 :
			複製 src_im的一部份到 dst_im上，起始點在 src_x , src_y，src_w的寬度，src_y的高度，所定義的這一個部份將會複製到 dst_x , dst_y的位置上。

			*/
			
			$img_w = $item['size'][0];
			$img_h = $item['size'][1];
			imagecopy($dest, $item['source'], $dest_x , $dest_y, 0, 0, $img_w , $img_h);
			
			$dest_y += ($img_h + 2);

			
		}
		$filename = date( "YmdHis" )."_".rand( 100000 , 999999 ).".jpg";	
		$img_url = './upload/tmp/' . $filename;
		Imagejpeg($dest, $img_url);
		
		
		//sync file 
		//--------------------------------------------------------------------------------
		//圖片處理 img_filename				
		$folder_name = $content_info["content_type"];
		$img_config['resize_setting'] =array($folder_name=>array(1024,1024));		
		$img_filename = resize_img($img_url,$img_config['resize_setting']);	

		//社區同步資料夾
		$img_config['resize_setting'] =array($folder_name=>array(500,500));
		resize_img($img_url,$img_config['resize_setting'],$this->getCommId(),$img_filename);
				
		@unlink($img_url);
		
		$orig_img_filename = tryGetData("img_filename",$content_info);
		
		$this->it_model->updateData( "web_menu_content" , array("img_filename"=> $img_filename,"is_sync"=>0,"update_date" => date("Y-m-d H:i:s")  ), "sn = '".$content_sn."'" );
			
				
		@unlink(set_realpath("upload/website/".$folder_name).$orig_img_filename);	
		@unlink(set_realpath("upload/".$this->getCommId()."/".$folder_name).$orig_img_filename);	
		
		//檔案同步至server
		$this->sync_file($folder_name);
		
		$content_info["img_filename"] = $img_filename;
		$content_info["is_sync"] = 0;
		
		
		$content_info["img_filename"] = $img_filename;
		$this->sync_to_server($content_info);
		//--------------------------------------------------------------------------------
	}







	/**
	 * 查詢server上有無edoma資料
	 **/
	public function getEdomaSale()
	{
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync_edoma_sale/getEdomaSale";
		//dprint($post_data);exit;
		
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
		dprint($edoma_data_ary);
		
		foreach( $edoma_data_ary as $key => $server_info ) 
		{	

/*

Array
(
								[sn] => 6   server_sn
    [comm_id] => 5tgb4rfv
				 [client_sn] => 0
				 [edoma_sn] => 1
				  [client_sync] => 0
    [del] => 0
    [sale_type] => a
    [house_type] => c
    [direction] => c
    [title] => 瑞安馥邑庭院 平面車位
    [name] => 聯絡人
    [phone] => 聯絡電話
    [area_desc] => 主建物、主建物 和 附屬建物坪數
    [total_price] => 3111.00
    [unit_price] => 121.00
    [manage_fee] => 13333
    [area_ping] => 55
    [house_age] => 10
    [pub_ratio] => 6.00
    [room] => 1
    [livingroom] => 2
    [bathroom] => 3
    [balcony] => 4
    [locate_level] => 8
    [total_level] => 9
    [usage] => 住宅用
    [current] => 現況
    [flag_rent] => 1
    [flag_parking] => 1
    [addr] => 經國路1110號
    [start_date] => 2016-05-22
    [end_date] => 0000-00-00
    [forever] => 1
    [decoration] => 高檔裝潢
    [living] => 7-11
    [traffic] => 附近交通
    [desc] => 特色說明特色說明特色說明特色說明特色說明特色說明特色說明特色說明
    [launch] => 1
    [created] => 2016-05-28 17:59:39
    [updated] => 2016-05-28 17:59:39
)

*/
		dprint($server_info);die;
			$arr_data = array
			(
				 "comm_id" => $this->getCommId()	
				, "server_sn" => $server_info["sn"]				
				, "title" => tryGetData("title",$server_info)	
				, "brief" => tryGetData("brief",$server_info)
				, "brief2" => tryGetData("brief2",$server_info)	
				, "id" => tryGetData("id",$server_info,NULL)	
				, "content_type" => tryGetData("content_type",$server_info)	
				, "filename" => tryGetData("filename",$server_info)
				, "img_filename" => tryGetData("img_filename",$server_info)
				, "start_date" => tryGetData("start_date",$server_info,NULL)
				, "end_date" => tryGetData("end_date",$server_info,NULL)
				, "forever" => tryGetData("forever",$server_info,0)
				, "launch" => tryGetData("launch",$server_info,0)
				, "hot" => tryGetData("hot",$server_info,0)
				, "sort" => tryGetData("sort",$server_info,500)
				, "url" => tryGetData("url",$server_info)
				, "target" => tryGetData("target",$server_info,0)
				, "content" => tryGetData("content",$server_info)
				, "update_date" =>  date( "Y-m-d H:i:s" )
				, "del" => tryGetData("del",$server_info,0)
				, "is_edoma" => 1
			);        	
			

		
			$content_server_info = $this->it_model->listData("house_to_sale","server_sn = '".$server_info["sn"]."'");
			if($content_server_info["count"]==0)
			{
				$arr_data["create_date"] =   date( "Y-m-d H:i:s" );
				$content_sn = $this->it_model->addData( "house_to_rent" , $arr_data );
				if($content_sn > 0)
				{
					
					$arr_data["sn"] = $content_sn;					
					$this->sync_edoma_rent_to_server($arr_data);
				}
				
			}
			else
			{
				$content_server_info = $content_server_info["data"][0];
				$result = $this->it_model->updateData( "house_to_sale" , $arr_data, "server_sn = '".$server_info["sn"]."'" );
				if($result)
				{
			
					$arr_data["sn"] = $content_server_info["sn"];				
					$this->sync_edoma_sale_to_server($arr_data);
				}
			}
						
		}
		
		//echo '<meta charset="UTF-8">';
		//dprint($app_data_ary);
		
	}
	

	/**
	 * web_menu_content 同步至雲端server
	 */
	function sync_edoma_sale_to_server($post_data)
	{
		$url = $this->config->item("api_server_url")."sync_edoma_sale/updateEdomaSale";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$is_sync = curl_exec($ch);
		curl_close ($ch);
		
		//echo $is_sync;exit;
		//更新同步狀況
		//------------------------------------------------------------------------------
		if($is_sync != '1')
		{
			$is_sync = '0';
		}			
		
		$this->it_model->updateData( "house_to_sale" , array("is_sync"=>$is_sync,"update_date"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
		//------------------------------------------------------------------------------
	}









	
	/**
	 * 撈取 指定停車位棟別+樓層 的有效車位
	 */
	function ajaxGetAvailableParking()
	{
		$p_part_01 = $this->input->get('p_part_01', true);
		$p_part_02 = $this->input->get('p_part_02', true);

		$prefix = $p_part_01.'_'.$p_part_02.'_';
		$condition = 'sn NOT IN ( SELECT parking_sn FROM user_parking ) '
					.' AND parking_id LIKE "'.$prefix.'%" '
					;
		$pk_result = $this->it_model->listData('parking', $condition, NULL, NULL, array('parking_id'=>'asc') );
		$available_parking = array();
		if ( $pk_result['count'] > 0 ) {
			foreach ( $pk_result['data'] as $parking) {
				$sn = $parking['sn'];
				$parking_id = $parking['parking_id'];
				$parking_id = str_replace($prefix,'', $parking_id);
				// $available_parking[$sn] = $parking_id;
				$available_parking[$parking_id] = $parking_id;
			}
		}

		echo json_encode($available_parking);
	}





/**/


	/**
	 * 取得社區id
	 */
	function getCommId()
	{
		$comm_id = $this->session->userdata("comm_id");
		return $comm_id;
	}
	
	
		/**
	 * 關閉瀏覽器
	 */
	public function closebrowser()
	{
		echo
		'<script language="javascript">
		window.opener=null;
		window.open("","_self");
		window.close();
		</script>';
	}
	
	function speed()
	{
		$this->output->enable_profiler(TRUE);	
	}
	
}