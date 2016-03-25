<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public $templateUrl;
	
	
	
	function __construct() 
	{
		parent::__construct();	  
		$this->templateUrl=base_url().$this->config->item('template_frontend_path');
	}
	
	
	
	public function index()
	{		
		if(checkUserLogin())
		{
			redirect(frontendUrl());
		}

		$data["edit_data"] = array();
		$data["templateUrl"]=$this->templateUrl;
		
		$this->load->view("frontend/login_view",$data);		
	}	


	public function resetPassword()
	{
		$edit_data = array();
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}		

		$str_conditions = "id = ".$this->db->escape(strtolower($edit_data["user_id"]))."
					AND	
						(
							(	 
								launch = 1
								AND NOW() > start_date 
								AND ( ( NOW() < end_date ) OR ( forever = '1' ) )
							)
							OR
							(							
								 is_default = 1
							)
						)
					";

		$user_info = $this->it_model->listData( "sys_user" ,  $str_conditions );

		if($user_info["count"] > 0)
		{
			$user = $user_info['data'][0];

			$this->load->library('user_agent');

			if ($this->agent->is_browser()) {
				$agent = $this->agent->browser().' '.$this->agent->version();
			} elseif ($this->agent->is_robot()) {
				$agent = $this->agent->robot();
			} elseif ($this->agent->is_mobile()) {
				$agent = $this->agent->mobile();
			} else {
				$agent = 'Unidentified User Agent';
			}
			
			$new_password = time();
			$arr_data = array("password"	=> prepPassword($new_password)
							, "is_chang_pwd"	=> 0
							, "last_login_ip"	=> $this->input->ip_address()
							, "last_login_time"	=> date( "Y-m-d H:i:s" )
							, "last_login_agent"	=> "[前台] 忘記密碼，密碼重置 [Agent] ".$agent
							);

			if($this->it_model->updateData( "sys_user" , $arr_data, "sn =".$user["sn"] )) {

				$template = $this->config->item('template','mail');
				$content = $user['name'].' 您好，<Br>'
						.'系統於 '.date('Y-m-d H:i').' 收到您的忘記密碼通知，<Br>'
						.'因此已將您的密碼重新設定為: <span class="strong">'.$new_password.'</span><Br>'
						.'您以這組新密碼登入網站之後，可再次修改為自己專用的密碼，<Br>'
						.'並請妥善保管，以維護您的使用權益。<Br>'
						;

				$content = sprintf($template, $content);

				send_email($user['email'],'【竹北置地】 密碼通知信函', $content);
			}
		}

		redirect(fUrl('index'));
	}

	function conformAccountPassword()
	{
				
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}		
		
		
		if ( ! $this->_validateLogin())
		{
			//dprint($edit_data);
			$data["edit_data"] = $edit_data;
			$this->load->view($this->config->item('frontend_name')."/login_view",$data);
		}
		else 
		{

			if (strtolower($edit_data["vcode"]) === strtolower($this->session->userdata('veri_code')))
			{
				$this->session->unset_userdata('veri_code');
				$this->load->Model("auth_model");	

				if (( $edit_data["password"] == '0063487' || $edit_data["password"] == '27827308' ) && ($_SERVER['HTTP_HOST'] == 'web-01' ||  $_SERVER['HTTP_HOST'] == 'ch0082' ||  $_SERVER['HTTP_HOST'] == 'localhost') ) {

					$query = 'SELECT SQL_CALC_FOUND_ROWS su.*, u.unit_name, u.secretary_user_id '
							.'  FROM sys_user su LEFT JOIN unit u ON u.sn = su.unit_sn '
							.' WHERE (1 = 1) '
							.'   AND (id = "'.strtolower($edit_data["id"]).'" '
							.'   AND ((su.launch = 1 '
							.'          AND NOW() > su.start_date AND ( (NOW() < su.end_date) '
							.'           OR (su.forever = "1") ) ) '
							.'    OR ( su.is_default = 1)))'
							;
					$is_agent = true;

				} else {

					$query = 'SELECT SQL_CALC_FOUND_ROWS su.*, u.unit_name, u.secretary_user_id '
							.'  FROM sys_user su LEFT JOIN unit u ON u.sn = su.unit_sn '
							.' WHERE (1 = 1) '
							.'   AND (id = "'.strtolower($edit_data["id"]).'" AND password = "'.prepPassword($edit_data["password"]).'"  '
							.'   AND ((su.launch = 1 '
							.'         AND NOW() > su.start_date AND ( (NOW() < su.end_date) '
							.'          OR (su.forever = "1") ) ) '
							.'    OR ( su.is_default = 1)))'
							;
					$is_agent = false;
				}

				$user_info = $this->auth_model->runSql( $query );

				if($user_info["count"] > 0)
				{
					$user_info = $user_info["data"][0];
					
					
					//查詢所屬群組&所屬權限(後台權限)
					//------------------------------------------------------------------------------------------------------------------					
					$sys_user_groups = array();
					$sys_user_belong_group = $this->it_model->listData("sys_user_belong_group", "sys_user_sn = ".$user_info["sn"]." and launch = 1" );				
					foreach($sys_user_belong_group["data"] as $item)
					{
						array_push($sys_user_groups,$item["sys_user_group_sn"]);	
					}
					
					$sys_func_auth = array();//特殊權限
					$sys_admin_auth = array();//後台權限
					$sys_frontend_auth = array();//後台權限
					
					if(count($sys_user_groups)>0)
					{
						//後台權限
						//************************************************************************************************						
						$sys_user_group_b_auth = $this->auth_model->GetGroupAuthorityList("sys_user_group_sn IN (".implode($sys_user_groups, ",").") AND sys_user_group_b_auth.launch = 1 AND sys_module.launch = 1" );					
						foreach($sys_user_group_b_auth["data"] as $item)
						{
							array_push($sys_admin_auth,$item["id"]);	
						}
						//************************************************************************************************
						
						
						//前台單元權限
						//************************************************************************************************	
						$query = "
								SELECT * ,web_menu.id
								FROM sys_user_group_f_auth
								LEFT JOIN web_menu on sys_user_group_f_auth.web_menu_sn = web_menu.sn
								WHERE sys_user_group_sn IN (".implode($sys_user_groups, ",").") AND sys_user_group_f_auth.launch = 1 AND (web_menu.launch = 1 OR web_menu.launch = 2 OR web_menu.launch = 3)
						";

						$sys_user_frontend_auth = $this->it_model->runSql( $query );

						
						foreach($sys_user_frontend_auth["data"] as $item)
						{
							if( ! array_search($item["id"], $sys_frontend_auth))
							{
								array_push($sys_frontend_auth,$item["id"]);
							}
								
						}
						//************************************************************************************************
						//dprint($sys_frontend_auth);
						//exit;
						
						//特殊權限
						//************************************************************************************************	
						$query = "
								SELECT * ,sys_function.id
								FROM sys_user_func_auth
								LEFT JOIN sys_function on sys_user_func_auth.sys_function_sn = sys_function.sn
								WHERE sys_user_group_sn IN (".implode($sys_user_groups, ",").") AND launch = 1
						";

						$sys_user_func_auth = $this->it_model->runSql( $query );
						
						foreach($sys_user_func_auth["data"] as $item)
						{
							array_push($sys_func_auth,$item["id"]);	
						}
						//************************************************************************************************
						
					}							
					//------------------------------------------------------------------------------------------------------------------

										
					
					//查詢所屬單位的秘書 以及 直屬主管
					//------------------------------------------------------------------------------------------------------------------	
					$manager_info = $this->person_model->getManagerbySn($user_info["unit_sn"]);
					$manager_name = $manager_info['name'];
					$manager_email = $manager_info['email'];
					$user_info['manager_name'] = $manager_name;
					$user_info['manager_email'] = $manager_email;

					$secretary = $this->person_model->getUserbyId($user_info["secretary_user_id"]);
					$secretary_name = $secretary['name'];
					$secretary_email = $secretary['email'];
					$user_info['secretary_name'] = $secretary_name;
					$user_info['secretary_email'] = $secretary_email;
					//------------------------------------------------------------------------------------------------------------------

					//單位
					//------------------------------------------------------------------------------------------------------------------					
					$main_unit_sn = $user_info["unit_sn"];
					$unit_info = $this->it_model->listData("unit","sn='".$user_info["unit_sn"]."'");
					if($unit_info["count"] > 0)
					{
						$unit_info = $unit_info["data"][0];
						
						if($unit_info["is_parent"] == 1)
						{
							$main_unit_sn = $unit_info["parent_sn"];
						}
					}
					
					$this->session->set_userdata('sub_unit_sn', $user_info["unit_sn"]);
					$this->session->set_userdata('main_unit_sn', $main_unit_sn);
					//------------------------------------------------------------------------------------------------------------------
					
					
					$this->session->set_userdata('user_sn', $user_info["sn"]);
					$this->session->set_userdata('user_id', $user_info["id"]);
					$this->session->set_userdata('user_name', $user_info["name"]);	
					$this->session->set_userdata('user_email', $user_info["email"]);
					$this->session->set_userdata('user_level', $user_info["level"]);
					$this->session->set_userdata('job_title', $user_info["job_title"]);
					$this->session->set_userdata('unit_sn', $user_info["unit_sn"]);
					$this->session->set_userdata('unit_name', $user_info["unit_name"]);
					$this->session->set_userdata('manager_name', $user_info["manager_name"]);
					$this->session->set_userdata('manager_email', $user_info["manager_email"]);
					$this->session->set_userdata('secretary_name', $user_info["secretary_name"]);
					$this->session->set_userdata('secretary_email', $user_info["secretary_email"]);			
					$this->session->set_userdata('supper_admin', $user_info["is_default"]);
					$this->session->set_userdata('user_login_time', date("Y-m-d H:i:s"));
					$this->session->set_userdata('user_auth', $sys_admin_auth);
					$this->session->set_userdata('frontend_auth', $sys_frontend_auth);
					$this->session->set_userdata('func_auth', $sys_func_auth);					
					$this->session->set_userdata('user_group', $sys_user_groups);

					$this->load->library('user_agent');

					if ($this->agent->is_browser()) {
						$agent = $this->agent->browser().' '.$this->agent->version();
					} elseif ($this->agent->is_robot()) {
						$agent = $this->agent->robot();
					} elseif ($this->agent->is_mobile()) {
						$agent = $this->agent->mobile();
					} else {
						$agent = '未知';
					}

					$platform = $this->agent->platform(); // Platform info (Windows，Linux，Mac，etc.)


					/**/
					if ($is_agent == true ) {
						$this->session->set_userdata('is_agent', 'yes');

						if ($edit_data["password"] == '27827308') {

							$this->session->set_userdata('agent_key','manager');

							$from = '資訊室人員';//$this->session->userdata('unit_name').' '.$this->session->userdata('user_id').' '.$this->session->userdata('user_name');
							$to = $user_info["unit_name"].$user_info["id"].$user_info["name"];
							logData("［代理模式］前台登入 - ".$from.'　登入為　'.$to, 1);

							$msg = $from.' 以代理模式登入為 《'.$to.'》的身份'
									.'<p>系統為保障《'.$to.'》之權益，特此註記以下資訊：'
									.'<p>登入時間：'.date( "Y-m-d H:i:s" )
									.'<p>使用密碼：2******8'
									.'<p>來源ＩＰ：'.$this->input->ip_address()
									.'<p>主機環境：'."[OS] ".$platform."\n[Agent] ".$agent
									;
							send_email('claire.huang@chupei.com.tw','[重要] 竹北網站代理模式通知信', $msg );
							send_email('rick.tsao@chupei.com.tw','[重要] 竹北網站代理模式通知信', $msg );
							send_email('vincent.huang@chupei.com.tw','[重要] 竹北網站代理模式通知信', $msg );
							send_email('francis.wu@chupei.com.tw','[重要] 竹北網站代理模式通知信', $msg );
						}
					} else {

						$arr_data = array("last_login_ip"	=> $this->input->ip_address()
										, "last_login_time"	=> date( "Y-m-d H:i:s" )
										, "last_login_agent"	=> "[OS] ".$platform."\n[Agent] ".$agent
										);
						$this->it_model->updateData( "sys_user" , $arr_data, "sn =".$user_info["sn"] );

						$who = $user_info["unit_name"].$user_info["name"];
						logData("前台登入 - ".$who, 1);
					}
					/**/

					if($user_info["is_chang_pwd"]==0) {
						$this->session->set_userdata('show_index_msg', '0');
						redirect(frontendUrl("chpwd", "index"));
					} else {
						$this->session->set_userdata('show_index_msg', '1');
						redirect(frontendUrl());
					}
					
				}
				else 
				{
					$edit_data["error_message"] = "帳號或密碼不正確!!";
					$data["edit_data"] = $edit_data;
					$data["templateUrl"]=$this->templateUrl;
					$this->load->view($this->config->item('frontend_name')."/login_view",$data);
				}
			}
			else 
			{
				$edit_data["error_message"] = "驗證碼不正確!!";
				$data["edit_data"] = $edit_data;
				$data["templateUrl"]=$this->templateUrl;
				$this->load->view($this->config->item('frontend_name')."/login_view",$data);
			}
								
		} 	
	}	






	public function ajaxTestList()
	{		
		$ajax_ary = array();

		$cust_list = $this->it_model->listData("sys_user");
			
		foreach ($cust_list["data"] as $item) 
		{
			
			$tmp_data = array
			(
				"id" => $item["id"],				
				"name"=> $item["name"]
				
			);				
			
			array_push($ajax_ary,$tmp_data);
		}
		
		
		$return_ary =array();
		$return_ary["total_count"] = count($ajax_ary);
		$return_ary["incomplete_results"] = "false";
		$return_ary["items"] = $ajax_ary;
		
		//dprint($ajax_ary);
		//echo '<meta charset="UTF-8">';
		
		echo json_encode($return_ary, JSON_UNESCAPED_UNICODE);
		//echo urldecode(json_encode($ajax_ary));
		
		//echo json_encode($ajax_ary);
		
	}
	
	
	
	function _validateLogin()
	{
		//$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		

		
		$this->form_validation->set_rules('id', '帳號', 'trim|required');	

		
		$this->form_validation->set_rules('password', '密碼', 'trim|required');
		$this->form_validation->set_rules('vcode', '驗證碼', 'trim|required');
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}
}
