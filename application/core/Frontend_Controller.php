<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class Frontend_Controller extends IT_Controller 
{
	public $title = "";	//標題	


	public $web_menu_list = array();
	public $web_menu_map = array();

	
	public $style_css = array();
	public $style_js = array();
	
	public $cdn_css = array();
	public $cdn_js = array();
	
	public $menu_id;
	public $menu_root_id;
	public $menu_info;
	public $web_menu_content_sn;
	public $web_menu_content_parent_sn;
	
	public $page_title_img;
	public $parent_title;
	
	
	public $page = 1;
	public $per_page_rows = 10;
	
	public $navi = array();
	public $navi_path = '';
	
	public $is_marguee = TRUE;
	public $show_header = TRUE;
	public $show_footer = TRUE;

	public $web_access = 0;
	
	
	function __construct() 
	{
		parent::__construct();
		
		/*
		//檢查是否登入		
		if(!checkUserLogin())
		{
			redirect(frontendUrl("login"));
		}		
		
		//檢查是否有前台單元權限
		if(!checkFrontendAuth())
		{
			redirect(frontendUrl("login"));
		}
		*/
		
		$this->initNavi();
		$this->initFrontend();
		$this->getParameter();
		
	}	
	
	function initFrontend()
	{		
		$this->menu_info = $this->getMenuInfo();	
		$this->_getFrontendMenu();
	}		
	
	
	function initNavi()
	{	
		$this->navi["首頁"] = frontendUrl();		
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
			
			if($navi_size != $navi_count)
			{
				$this->navi_path .= '<a href="'.$value.'">'.$key.'</a>';
				$this->navi_path .= ' <div class="separator">&gt;</div> ';
				
				
			}
			else 
			{
				//$this->navi_path .= ' '.$key.'';
				$this->navi_path .= '<a class="last" >'.$key.'</a>';
			}

		}
		
		$this->navi_path = '<div id="breadcrumb">'.$this->navi_path.'</div>';
		
	}
	
	
	public function getParameter()
	{
		$this->page = $this->input->get('page',TRUE);
		//$this->per_page_rows =	$this->config->item('per_page_rows','pager');		
	}
	
	/**
	 * 回到Froentend 首頁
	 */	
	public function redirectHome()
	{
		header("Location:".base_url()."home");
	}
	
	/**
	 * 回到login頁
	 */	
	public function redirectLoginPage()
	{
		//取得預設語系		
		$condition = "is_default = 1";		
		$list = $this->language_model->GetList( $condition );		
		$list = $list["data"];	
		
		
		if( sizeof( $list ) > 0 )
		{
			
			header("Location:".getFrontendControllerUrl("member","login"));
			exit;
		}
		else
		{
			show_error('language not found');
		}
	}


	//設定跑馬燈
	public function setMarguee($is_marguee = TRUE)
	{
		$this->is_marguee = $is_marguee;
	}

	
	//設定Header區塊是否顯示
	public function displayHeader($show_header = TRUE)
	{
		$this->show_header = $show_header;
	}

	//設定Footer區塊是否顯示
	public function displayFooter($show_footer = TRUE)
	{
		$this->show_footer = $show_footer;
	}
		
	protected function getMenuInfo()
	{	
		$this->menu_id = $this->uri->segment(1);				
		$menu_info = $this->it_model->listData("web_menu" , "id = '".$this->menu_id."' ");	
		
		//echo $this->uri->segment(2);
		//dprint($menu_info);
		if( sizeof($menu_info["data"])>0)
		{
			//parent info
			//-------------------------------------------------
			$parent_info = $this->it_model->listData("web_menu" , "sn = '".$menu_info["data"][0]["parent_sn"]."' ");
			
			//dprint($menu_info);
			if($parent_info["count"] > 0)
			{
				$this->page_title_img = $parent_info["data"][0]["img_filename"];	
				$this->parent_title = $parent_info["data"][0]["title"];
							
				$this->menu_root_id = $parent_info["data"][0]["id"]; 
				$this->addNavi($parent_info["data"][0]["title"], fUrl("index"));
				
			}			
			//-------------------------------------------------			
			
				
			$this->module_sn = $menu_info["data"][0]["sn"];	
			
			$this->addNavi($menu_info["data"][0]["title"], fUrl("index"));
	
		  	return $menu_info["data"][0];
		}
		else 
		{
		
			
						
			return array("id"=>"","title"=>"");
		}
	}



	public function redirectPage()
	{
		$page_sn = 0;	
		$condition = "";
		
		$page_parent_list = $this->c_model->GetList( $this->router->fetch_class() , "" ,TRUE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
		if($page_parent_list["count"]>0)
		{
			$condition = "parent_sn = '".$page_parent_list["data"][0]["sn"]."'";
		}
		
		$page_info = $this->c_model->GetList( $this->router->fetch_class()."_sub" , $condition ,TRUE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
		//dprint($page_info);
		//exit;
		//$page_info = $this->c_model->GetList2( $this->router->fetch_class()."_sub" , "" ,FALSE, NULL , NULL , array("parent.sort"=>"asc", "web_menu_content.sort"=>"asc","sn"=>"web_menu_content.desc") );
		
		if($page_info["count"]>0)
		{
			$page_sn = $page_info["data"][0]["sn"];
		}
		else 
		{
			$page_info = $this->c_model->GetList( $this->router->fetch_class() , "" ,TRUE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
			if($page_info["count"]>0)
			{
				$page_sn = $page_info["data"][0]["sn"];
			}
			//dprint($page_info);
			//exit;
		}
		//echo $page_sn;
		header("Location:".frontendUrl($this->router->fetch_class(),"page/".$page_sn));
		exit;	
	}




	public function page($web_menu_content_sn = '')
	{	
		$data = array();
		$this->getWebMenuContentInfo($data,$web_menu_content_sn);
		
		$this->display("page_view", $data, "page");	
	}
		
	
	
	function addCss($css_value, $is_cdn = FALSE)
	{
		if($is_cdn)
		{
			array_push($this->cdn_css, $css_value);
		}
		else 
		{
			array_push($this->style_css, $css_value);
		}
				
	}
	
	function addJs($js_value, $is_cdn = FALSE)
	{
		if($is_cdn)
		{
			array_push($this->cdn_js, $js_value);
		}
		else 
		{
			array_push($this->style_js, $js_value);
		}
		
	}
	
	
	/**
	 * 組前端view所需css及js
	 */
	function _bulidJsCss(&$data = array())
	{
		$data['style_css'] = '';
		$data['style_js'] = '';
		foreach ($this->style_css as $value) 
		{
			$data['style_css'] .= '<link href="'.base_url().$this->config->item("template_frontend_path").$value.'" rel="stylesheet" type="text/css" />';    	
		}
		
		foreach ($this->cdn_css as $value) 
		{
			$data['style_css'] .= '<link href="'.$value.'" rel="stylesheet" type="text/css" />';    	
		}
		
		
		foreach ($this->style_js as $value) 
		{
			$data['style_js'] .= '<script type="text/javascript" src="'.base_url().$this->config->item("template_frontend_path").$value.'"></script>';
		}
		
		foreach ($this->cdn_js as $value) 
		{
			$data['style_js'] .= '<script  src="'.$value.'"></script>';
		}
	}
	
	
	public function getWebMenuContentInfo(&$data = array(),$web_menu_content_sn = '')
	{	
		
        $condition = "";
		if(isNotNull($web_menu_content_sn))
		{
			$condition = "sn='".$web_menu_content_sn."'";
		}
		
		$page_info = $this->c_model->GetList( "" , $condition ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );	
			
		if($page_info["count"]>0)
		{
			if(isNull($page_info["data"][0]["parent_sn"]))
			{
			
				$this->setSubTitle($page_info["data"][0]["title"]);
				$this->addNavi($page_info["data"][0]["title"], fUrl("index"));
				$this->web_menu_content_sn = $page_info["data"][0]["sn"];
			}
			else 
			{
				
				$parent_info = $this->c_model->GetList( "" , $condition ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
				
				$parent_info = $this->it_model->listData("web_menu_content" , "sn = '".$page_info["data"][0]["parent_sn"]."' ");
				if($parent_info["count"]>0)
				{
					$this->addNavi($parent_info["data"][0]["title"], fUrl("index"));
				}	
				
				
				$this->setSubTitle("《".$page_info["data"][0]["title"]."》");
				$this->addNavi($page_info["data"][0]["title"], fUrl("index"));
				$this->web_menu_content_sn = $page_info["data"][0]["sn"];
				$this->web_menu_content_parent_sn = $page_info["data"][0]["parent_sn"];
			}
			
			
			$data["html_page"] = $page_info["data"][0];
		}
		else 
		{
			header("Location:".frontendUrl());
			exit;
		}	

		return $data;		
	}
	
	
	public function getWebMenuContentInfoById(&$data = array(),$web_menu_content_id = '')
	{	
		
        $condition = "";
		if(isNotNull($web_menu_content_id))
		{
			$condition = "content_type='".$web_menu_content_id."'";
		}
		
		$page_info = $this->c_model->GetList( "" , $condition ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );	
			
		//dprint($page_info);
		//exit;
		
		if($page_info["count"]>0)
		{
			if(isNull($page_info["data"][0]["parent_sn"]))
			{
			
				$this->setSubTitle("[".$page_info["data"][0]["title"]."]");
				$this->addNavi($page_info["data"][0]["title"], fUrl("index"));
				$this->web_menu_content_sn = $page_info["data"][0]["sn"];
			}
			else 
			{
				
				$parent_info = $this->c_model->GetList( "" , $condition ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
				
				$parent_info = $this->it_model->listData("web_menu_content" , "sn = '".$page_info["data"][0]["parent_sn"]."' ");
				if($parent_info["count"]>0)
				{
					$this->addNavi($parent_info["data"][0]["title"], fUrl("index"));
				}	
				
				
				$this->setSubTitle("《".$page_info["data"][0]["title"]."》");
				$this->addNavi($page_info["data"][0]["title"], fUrl("index"));
				$this->web_menu_content_sn = $page_info["data"][0]["sn"];
				$this->web_menu_content_parent_sn = $page_info["data"][0]["parent_sn"];
			}
			
			
			$data["html_page"] = $page_info["data"][0];
		}
		else 
		{
			header("Location:".frontendUrl());
			exit;
		}	

		return $data;		
	}

	/**
	 * 取得Html page info
	 */
	public function getHtmlPageInfo(&$data = array(),$page_id = '')
	{	
		
        //$this->addJs("js/string.js");     
		
		$page_list = $this->it_model->listData( "html_page" , "page_id  = '".$page_id."' and ".$this->it_model->eSql('html_page'));
			

		if($page_list["count"]>0)
		{
			$data["html_page"] = $page_list["data"][0];
		}
		else 
		{
			header("Location:".frontendUrl());
			exit;
		}		
		return $data;		
	}
	
	
		
	
	private function _getFrontendMenu()
	{
			
		$sort = array
		(			
			"sort" => "asc" 
		);		
		
		$condition = "";
		if($this->web_access == 1 && $this->config->item("web_access_enable") == 1)
		{
			$condition = " AND allow_internet = 1";
		}
		
		$l1_list = $this->it_model->listData("web_menu","level=1 AND (launch=1 or launch=2 or launch=3) ".$condition,NULL,NULL,$sort);
		$this->web_menu_list = $l1_list["data"];
		
		$l2_list = $this->it_model->listData("web_menu","level=2 AND (launch=1 or launch=2 or launch=3) ".$condition,NULL,NULL,$sort);	
		//dprint($l2_list);
		foreach ($l2_list["data"] as $item) 
		{
			$this->web_menu_map[$item["parent_sn"]]["item_list"][]=$item;
		}
		
		$l3_list = $this->it_model->listData("web_menu","level=3 AND (launch=1 or launch=2 or launch=3) ".$condition,NULL,NULL,$sort);
		foreach ($l3_list["data"] as $item) 
		{
			$this->web_menu_map[$item["parent_sn"]]["item_list"][]=$item;
		}		
	}
	
		
	
	

	
	function loadWebSetting()
	{
		$setting_list = $this->it_model->listData("web_setting","launch = 1");
		
		$setting_info = array();
		foreach ($setting_list["data"] as $key => $item) {
			$setting_info[$item["key"]] = $item["value"];
		}
		
		return $setting_info;	
	}
	
	/**
	 * header區最新管委公告
	 */
	function _getLatestBulletin()
	{		
		$bulletin_info = $this->c_model->GetList2( "bulletin" , "" ,TRUE, 1 , 1 , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		if($bulletin_info["count"]>0)
		{
			$bulletin_info = $bulletin_info["data"][0];
		}
		else 
		{
			$bulletin_info = array();
		}
		
		return $bulletin_info;
	}



	
	function _commonArea($view="",&$data = array())
	{
		//$data["top_p_cat_list"] = $this->_getTopProductCategory();

		## 暫時以判斷目前的 Class && Method 方式來決定左側選單 - by Claire
		$data['current_class'] = $this->router->fetch_class();
		$data['current_method'] = $this->router->fetch_method();
		
		
		$data['web_menu_list'] = $this->web_menu_list;
		$data['web_menu_map'] = $this->web_menu_map;
		$data['web_access'] = $this->web_access;
				
		//特殊權限
		$data['func_auth'] = $this->session->userdata('func_auth');
				
		//前台單元權限
		$data['frontend_auth'] = $this->session->userdata('frontend_auth');
				

		
		
		//dprint($data['frontend_auth']);
		
		$data['menu_info'] = $this->menu_info;
		$data['menu_id'] = $this->menu_id;
		$data['menu_root_id'] = $this->menu_root_id;
		if(!key_exists("web_menu_content_sn", $data))
		{
			$data['web_menu_content_sn'] = $this->web_menu_content_sn;	
		}
		
		$data['show_message'] =$this->session->flashdata('show_message');
		
		//dprint($data);
		
		$data['web_menu_content_parent_sn'] = $this->web_menu_content_parent_sn;
		$data['page_title_img'] = $this->page_title_img;
		$data['parent_title'] = $this->parent_title;
		
		
		
		$data['webSetting'] = $this->loadWebSetting();
		
		$data['templateUrl'] = $this->config->item("template_frontend_path");
		

		$data['latest_bulletin_info'] = $this->_getLatestBulletin();

		$data['show_header'] = $this->show_header;
		$data['show_footer'] = $this->show_footer;
		
		$data['header'] = $this->load->view('frontend/template_header_view', $data, TRUE);
		//$data['left_menu'] = $this->load->view('frontend/template_left_view', $data, TRUE);
		
		$data['content_js'] = '';
		if(file_exists(APPPATH.'views/'.$view.'_js.php'))
		{
			$data['content_js'] = $this->load->view($view.'_js', $data, TRUE);
		}		
		$data['footer'] = $this->load->view('frontend/template_footer_view', $data, TRUE);
		
		//麵包屑
		$this->buildNavi();
		$data['navi_path'] = $this->navi_path;
				
		
		
		//dprint($data['frontend_auth']);
		
		$this->_bulidJsCss($data);	
		return $data;	
	}
	

	/**
	 * output view
	 */
	function display($view, $data = array())
	{
		if(isNotNull(tryGetData("view_folder", $data)))
		{
			$view = "frontend/".tryGetData("view_folder", $data)."/".$view;
		}
		else 
		{
			$view = "frontend/".$this->router->fetch_class()."/".$view;	
		}
			
		$this->_commonArea($view,$data);

		$data['content'] = $this->load->view($view, $data, TRUE);
		
		//dprint($data);
		
		// 讓瀏覽器不快取
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->output->set_header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
		$this->output->set_header('Pragma: no-cache');

		return $this->load->view('frontend/template_index_view', $data);
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
		redirect(getBackendUrl($redirect_action, FALSE));	
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
	
	

	
	
	public function showMessage($message = '')
	{
		$this->session->set_flashdata('show_message',$message);
	}


	
	
	
	
	function loadElfinder()
	{
	  $this->load->helper('path');
	  $opts = array(
	    'debug' => true, 
	    'roots' => array(
	      array( 
	        'driver' => 'LocalFileSystem', 
	        'path'   => set_realpath('upload'), 
	        'URL'    => site_url('upload').'/'
	        // more elFinder options here
	      ) 
	    )
	  );
	  $this->load->library('elfinderlib', $opts);
	}
	
	
	
	
		/**
	 * 登出
	 */
	public function logout()
	{
		$who = $this->session->userdata('unit_name').$this->session->userdata('user_name');
		logData("前台登出-".$who, 1);

		$this->sysLogout();
	}	
	
	
	
	function speed()
	{
		$this->output->enable_profiler(TRUE);	
	}
	
	
	
	
	//取得地區代碼
	public function getSectionMap()
    {
    	$list = $this->it_model->listData( "section" , "status =1" , NULL , NULL , array("sn"=>"asc") );			
		
		$section_map = array();
		foreach ($list["data"] as $key => $item) 
		{
			$section_map[$item["section_name"]] = $item;
		}
			
		return $section_map;		 
    }
	
	
	
	//取得地區代碼 Plus
	public function getSectionPlusMap()
    {    	
		$query = "		
		SELECT SQL_CALC_FOUND_ROWS section.* ,location.city_code,location.city_name,location.town_name
		FROM section 
		LEFT JOIN location on location.sn = section.location_sn
		WHERE section.status = 1 AND location.status = 1	
		";

		$list = $this->it_model->runSql( $query );
		
		$section_map = array();
		foreach ($list["data"] as $key => $item) 
		{
			$section_map[$item["sn"]] = $item;
		}
			
		return $section_map;		 
    }
	
	//取得地區代碼
	public function getLocationMap()
    {
    	$list = $this->it_model->listData( "location" , "status =1" , NULL , NULL , array("sn"=>"asc") );			
		
		$location_map = array();
		foreach ($list["data"] as $key => $item) 
		{
			$location_map[$item["sn"]] = $item;
		}
			
		return $location_map;		 
    }
	
	//取得地目代碼
	public function getLandKindMap()
    {
    	$list = $this->it_model->listData( "land_kind" , "" , NULL , NULL , array("id"=>"asc") );			
		
		$kind_map = array();
		foreach ($list["data"] as $key => $item) 
		{
			$kind_map[$item["id"]] = $item["title"];
		}
			
		return $kind_map;		 
    }

	//取得使用分區代碼
	public function getUseLandKindMap()
    {
    	$list = $this->it_model->listData( "land_use_kind" , "" , NULL , NULL , array("id"=>"asc") );			
		
		$use_kind_map = array();
		foreach ($list["data"] as $key => $item) 
		{
			$use_kind_map[$item["id"]] = $item["title"];
		}
			
		return $use_kind_map;		 
    }

	//取得使用分區代碼
	public function getUseLevelKindMap()
    {
    	$list = $this->it_model->listData( "land_use_level" , "" , NULL , NULL , array("id"=>"asc") );			
		
		$use_level_map = array();
		foreach ($list["data"] as $key => $item) 
		{
			$use_level_map[$item["id"]] = $item["title"];
		}
			
		return $use_level_map;		 
    }

	//取得登記原因代碼
	public function getLandRegDescMap()
    {
    	$list = $this->it_model->listData( "land_reg_desc" , "" , NULL , NULL , array("id"=>"asc") );				
		$map = array();
		foreach ($list["data"] as $key => $item) 
		{
			$map[$item["id"]] = $item["title"];
		}
			
		return $map;			 
    }
	
	//取得權利種類代碼
	public function getLandRightKindMap()
    {
    	$list = $this->it_model->listData( "land_right_kind" , "" , NULL , NULL , array("id"=>"asc") );				
		$map = array();
		foreach ($list["data"] as $key => $item) 
		{
			$map[$item["id"]] = $item["title"];
		}
			
		return $map;		 
    }
	
	
	
	
	//ajax 取得縣市
    public function ajaxGetCityList()
    {		
		$list = $this->it_model->listData( "land_city" , "status =1" , NULL , NULL , array("field(`title`, '台北市','新北市','桃園市','新竹縣','新竹市','苗栗縣')"=>"asc", "id"=>"asc"));	
		echo json_encode($list["data"]); 
    }
	
	//ajax 取得計畫區By City
    public function ajaxGetPlanListByCity()
    {
    	$city_code = $this->input->get('city_code');		
		$list = $this->it_model->listData( "planing_region" , "status = 1 and city_code=".$this->db->escape($city_code)."" , NULL , NULL , array("region_name"=>"asc"));	
		if($list["count"]==0)
		{
			array_push($list["data"],array("region_name"=>"計畫區","city_code"=>"0"));
		}
		echo json_encode($list["data"]); 
    }
	


	
	//ajax 取得所有社團名稱
    public function ajaxGetLeagueList()
    {
		$query = 'SELECT DISTINCT league_name FROM league WHERE league_name NOT IN ("諮議", "北獅園地") ';
		$list = $this->it_model->runSql( $query , NULL , array("league_name"=>"asc", "league_area"=>"asc"));

		$array1 = array(NULL=>array('league_name'=>'-請選擇-'));
		$result = array_merge($array1, $list["data"]);

		echo json_encode($result); 
    }
	


	//ajax 取得指定社團的分會
    public function ajaxGetLeagueAreaList()
    {
    	$name = $this->input->get('name');
		$query = 'SELECT DISTINCT league_area FROM league WHERE league_name NOT IN ("諮議", "北獅園地") ';
		//if ( isNotNull($name)) {
			$query .= 'AND league_name = "'.$name.'" ';
		//}
		$list = $this->it_model->runSql( $query , NULL , array("league_name"=>"asc", "league_area"=>"asc"));

		echo json_encode($list["data"]); 
    }
	
	
	//ajax 取得所有計畫區
    public function ajaxGetPlanList()
    {		
		$list = $this->it_model->listData( "planing_region" , "status = 1" , NULL , NULL , array("city_code"=>"asc", "region_name"=>"asc"));	
		echo json_encode($list["data"]); 
    }
	
	public function ajaxGetLocationByCity()
    {
    	$city_code = $this->input->get('city_code');
				
		$list = $this->it_model->listData( "location" , "city_code = ".$this->db->escape($city_code)." and status =1" , NULL , NULL , array("town_name"=>"asc"));	
		echo json_encode($list["data"]); 
    }


	public function ajaxGetTownByPlan()
    {    			
		echo $this->ajaxGetLocationByPlan(); 
    }

	public function ajaxGetLocationByPlan()
    {
    	$plan_sn = $this->input->get('plan_sn');
		
		$list = $this->land_model->getLocationByPlan($plan_sn, "planing_region_sn = ".$this->db->escape($plan_sn) , NULL , NULL , array("town_name"=>"asc"));	
		
		//dprint($list);
		
		echo json_encode($list["data"]); 
    }
	
	
	public function ajaxGetSection()
    {
    	$location_sn = $this->input->get('location_sn');
		$drop_type = $this->input->get('drop_type');
		$pc = $this->input->get('pc');
		
		//針對新竹市處理
		if($location_sn == 43 || $location_sn == 44)
		{
			$location_sn = 42;
		}
		
		
		if($drop_type == 1)
		{
			$list = $this->it_model->listData( "planing_region_section_view" , "planing_region_sn = ".$this->db->escape($pc)."  and status=1 and location_sn = ".$this->db->escape($location_sn) , NULL , NULL , array("section_code"=>"asc"));
		}
		else
		{
			$list = $this->it_model->listData( "section" , "status=1 and location_sn = ".$this->db->escape($location_sn) , NULL , NULL , array("section_code"=>"asc"));
		}		
		
		echo json_encode($list["data"]); 
    }
	
	public function ajaxGetSectionList()
    {
    	$location_sn = $this->input->get('location_sn');
		$drop_type = $this->input->get('drop_type');
		$plan_sn = $this->input->get('plan');

		
		//針對新竹市處理
		if($location_sn == 43 || $location_sn == 44)
		{
			$location_sn = 42;
		}
		
		
		if($drop_type == "plan")
		{
			$list = $this->it_model->listData( "planing_region_section_view" , "planing_region_sn = ".$this->db->escape($plan_sn)."  and status=1 and location_sn = ".$this->db->escape($location_sn) , NULL , NULL , array("section_code"=>"asc"));
		}
		else
		{
			$list = $this->it_model->listData( "section" , "status=1 and location_sn = ".$this->db->escape($location_sn) , NULL , NULL , array("section_code"=>"asc"));
		}		
		
		echo json_encode($list["data"]); 
    }
	

	
	// 取得自己下屬的業務列表
	public function ajaxGetMyUnitList()
    {
		$secretary_user_id = null;
		if ($this->session->userdata('unit_sn') ==10 && $this->session->userdata('job_title') =='秘書') {
			$secretary_user_id = $this->session->userdata('user_id');
			$belong_unit_array = $this->person_model->getBelongUnitbySecretaryId($secretary_user_id);
			$user_id = $belong_unit_array['manager_user_id'];

		} else {

			$user_id = $this->session->userdata('user_id');
		}
		//$my_sales_list = $this->person_model->getMyOwnSalesList($user_id );

		## 查詢自己管轄的單位別
		if ( isNotNull($secretary_user_id)) {
			// 佳惠負責《桃園業務四組》與《桃園業務四組開發》
			if ($this->session->userdata('job_title') =='秘書' && strtoupper($secretary_user_id) == 'SH0057') {
				$my_unit_list_01 = $this->person_model->getMyOwnUnitList( 'SH0010' );
				$my_unit_list_02 = $this->person_model->getMyOwnUnitList( 'SH0039' );

				$my_unit_list = array_merge($my_unit_list_01, $my_unit_list_02);
			}

			// 怡萱負責《台北》與《新莊》與《悅陽》
			if ( strtoupper($secretary_user_id) == 'CH0088') {

				//$my_unit_list_01 = $this->person_model->getMyOwnUnitList( 'CH0008' );
				//$my_unit_list = array_merge($my_unit_list_01, $my_unit_list_02, $my_unit_list_03);
				$my_unit_list_02 = $this->person_model->getMyOwnUnitList( 'CH0029' );
				$my_unit_list_03 = $this->person_model->getMyOwnUnitList( 'YU0002' );

				$my_unit_list = array_merge($my_unit_list_02, $my_unit_list_03);
			}

		} else {
			$my_unit_list = $this->person_model->getMyOwnUnitList( $user_id );
		}



		echo json_encode($my_unit_list); 
    }

	
	// 取得自己下屬的業務列表
	public function ajaxGetMySalesList()
    {

		//	$my_sales_list = $this->person_model->getMyOwnSalesList( 'LA0001' );
		$my_sales_list = array();
		$secretary_user_id = null;
		if ($this->session->userdata('unit_sn') == 10 && $this->session->userdata('job_title') =='秘書') {
			$secretary_user_id = $this->session->userdata('user_id');
			$belong_unit_array = $this->person_model->getBelongUnitbySecretaryId($secretary_user_id);
			$user_id = $belong_unit_array['manager_user_id'];

		} else {

			$user_id = $this->session->userdata('user_id');
		}
		if ( isNotNull($secretary_user_id)) {

			// 佳惠負責《桃園業務四組》與《桃園業務四組開發》
			if ( strtoupper($secretary_user_id) == 'SH0057') {
				$my_sales_list_01 = $this->person_model->getMyOwnSalesList( 'SH0010' );
				$my_sales_list_02 = $this->person_model->getMyOwnSalesList( 'SH0039' );

				$my_sales_list = array_merge($my_sales_list_01, $my_sales_list_02);
			}
			// 怡萱負責《台北》與《新莊》與《悅陽》
			if ( strtoupper($secretary_user_id) == 'CH0088') {
				//$my_sales_list_01 = $this->person_model->getMyOwnSalesList( 'CH0008' );
				//$my_sales_list = array_merge($my_sales_list_01, $my_sales_list_02, $my_sales_list_03);
				$my_sales_list_02 = $this->person_model->getMyOwnSalesList( 'CH0029' );
				$my_sales_list_03 = $this->person_model->getMyOwnSalesList( 'YU0002' );

				$my_sales_list = array_merge($my_sales_list_02, $my_sales_list_03);
			}
		
		} else {

			$my_sales_list = $this->person_model->getMyOwnSalesList( $user_id );
		
		}

		echo json_encode($my_sales_list); 
    }

	// 針對自己單位屬於公司分派名單的拜訪回報，取得那些公司名單的列表
	public function ajaxGetMyUnitCompList()
    {
    	$result = array();

		// 查詢自己管轄的單位別
		$secretary_user_id = null;
		if ($this->session->userdata('supper_admin') == 1) {
			$result = $this->person_model->getUnitList('is_sales = 1' );
			$my_unit_list = $result['data'];

		} else {
			//$user_id = $this->session->userdata('user_id');
			//$my_unit_list = $this->person_model->getUnitList('UPPER(u.manager_user_id)  ="'.strtoupper($user_id).'"' );

			if ($this->session->userdata('unit_sn') == 10 && $this->session->userdata('job_title') =='秘書') {
				$secretary_user_id = $this->session->userdata('user_id');
				$belong_unit_array = $this->person_model->getBelongUnitbySecretaryId($secretary_user_id);
				$user_id = $belong_unit_array['manager_user_id'];

			} else {

				$user_id = $this->session->userdata('user_id');
			}
			// 佳惠負責《桃園業務四組》與《桃園業務四組開發》
			if ( isNotNull($secretary_user_id)) {
				if ( strtoupper($secretary_user_id) == 'SH0057') {
					$my_sales_list_01 = $this->person_model->getMyOwnSalesList( 'SH0010' );
					$my_sales_list_02 = $this->person_model->getMyOwnSalesList( 'SH0039' );

					$my_sales_list = array_merge($my_sales_list_01, $my_sales_list_02);
				}
				if ( strtoupper($secretary_user_id) == 'CH0088') {
					$my_sales_list_01 = $this->person_model->getMyOwnSalesList( 'CH0008' );
					$my_sales_list_02 = $this->person_model->getMyOwnSalesList( 'CH0029' );
					$my_sales_list_03 = $this->person_model->getMyOwnSalesList( 'YU0002' );

					$my_sales_list = array_merge($my_sales_list_01, $my_sales_list_02, $my_sales_list_03);
				}
			} else {
				$my_unit_list = $this->person_model->getMyOwnUnitList( $user_id );
			}
		}

		foreach ($my_unit_list as $unit) {
			$unit_sn = tryGetData('sn', $unit);
			$result = array();
			$result = $this->person_model->getSalesList('u.sn='.$unit_sn.' oR u.parent_sn='.$unit_sn, NULL, NULL, array('u.sn'=>'asc', 's.level'=>'desc', 's.id'=>'asc') );

			if ($result['count'] > 0 ) {
				$sales_list = array();
				foreach ($result['data'] as $user) {
					$sales_list[] = $user;
					$sales_sn_list[] = $user['sn'];
				}
				
				$sales_list = array_unique($sales_list, SORT_REGULAR);
				$sales_sn_list = array_unique($sales_sn_list, SORT_REGULAR);

				if (sizeof($sales_sn_list) > 0) {
					$my_sales_sn_list = implode(',', $sales_sn_list);
			
					$sql_for_project =   'SELECT DISTINCT vps.visit_project_sn as sn, p.title '
										.'  FROM visit_project_to_sales vps  '
										.'  LEFT JOIN visit_project p ON vps.visit_project_sn = p.sn '
										.' WHERE p.title IS NOT NULL AND user_sn IN ('.$my_sales_sn_list.')'
										;
			
					$list_for_project = $this->person_model->runSql($sql_for_project, $this->per_page_rows , $this->page, array("visit_project_sn"=>"desc") );
					$result = $list_for_project["data"];
				}

			}
		}

		echo json_encode($result); 
    }


	// 針對自己屬於公司分派名單的拜訪回報，取得那些公司名單的列表
	public function ajaxGetMyCompList()
    {
		$user_sn = $this->session->userdata('user_sn');

		$sql_for_project =   'SELECT DISTINCT vps.visit_project_sn as sn, p.title '
							.'  FROM visit_project_to_sales vps  '
							.'  LEFT JOIN visit_project p ON vps.visit_project_sn = p.sn '
							.' WHERE user_sn = '.$user_sn
							;

		$list_for_project = $this->person_model->runSql($sql_for_project, $this->per_page_rows , $this->page, array("visit_project_sn"=>"desc") );

		echo json_encode($list_for_project["data"]); 
    }

}
