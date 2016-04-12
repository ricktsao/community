<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Pager Setting
|--------------------------------------------------------------------------
|
| Set the default pager 
|
*/



$CI	=& get_instance();

$language_value = "zh-tw";

date_default_timezone_set('Asia/Taipei');



$config['admin_folder'] = '/backend';

$CI->lang->load('common', $language_value);

$config['pager']['per_page_rows'] = 10; //每頁筆數

$config['enable_box_cache'] = TRUE; //開啟box cache




$config['max_size'] = "200";


//前後台路徑
$config['backend_name'] = "backend";
$config['frontend_name'] = "frontend";
$config['template_backend_path']="template/backend/";
$config['template_frontend_path']="template/frontend/";


//upload image設定
//------------------------------------------------------
$config['image']['upload_tmp_path'] = './upload/tmp';
$config['image']['allowed_types'] = 'gif|jpg|png';
$config['image']['upload_max_size'] = '204800';

//------------------------------------------------------

//郵件設定
//------------------------------------------------------
$config['mail']['host'] = 'oa.chupei.com.tw'; 
$config['mail']['port'] = '25';
$config['mail']['sender_mail'] = '';
$config['mail']['sender_name'] = '竹北置地 <it@chupei.com.tw>';
$config['mail']['charset'] = 'utf-8';
$config['mail']['encoding'] = 'base64';
$config['mail']['is_html'] = TRUE;
$config['mail']['word_wrap'] = 50;//每50自斷行
$config['mail']['template'] = 
"<!doctype html>
<html lang='en'>
	<head>
	<meta charset='UTF-8'>
	<title>信函</title>
	<style type='text/css'>
		* {font-family: '微軟正黑體', Verdana; font-size:16px; line-height:180%%}
		#main {min-height: 260px }
		#header {height: 120px }
		#content {min-height: 300px }
		#footer {height: 160px }
		hr.style-one {
		border: 0;
		height: 1px;
		background: #333;
		background-image: linear-gradient(to right, #ccc, #333, #ccc);
		}
		.strong {font-weight: bold; font-size:20px; color:#00c}
	</style>
	</head>
<body>
<div id='main'>
	<div id='header'>
	<img src='http://web.chupei.com.tw/template/frontend/images/img_logo.png' width='280'>
	</div>
	<div id='content'>
	%s
	</div>
	<div id='footer'>
	<hr class='style-one'>
	<span style='color: #b0b0b0'>此信件是由系統發出，請勿直接回信；若有任何問題，可洽詢各單位秘書或資訊室人員，感謝您。</span>
	</div>
</div>
</body>
</html>";

//------------------------------------------------------



//外網權限功能(1:開啟,2:關閉)
$config['web_access_enable'] = 0;

// 性別
$config['gender_array'] = array(  1 => '先生'
								, 2 => '女士'
								);

// 性別
$config['yes_no_array'] = array( 0=>'否', 1=>'是');
// 性別
$config['yes_no_array2'] = array( 0=>'沒有', 1=>'有');
// 性別
$config['gender_array2'] = array('m'=>'男生','f'=>'女', 'a'=>'男女不拘');


// 角色
$config['role_array'] = array(  'I' => '住　戶'
								, 'M' => '物業人員'
								//, 'G' => '警衛'
								//, 'S' => '秘書'
								, 'F' => '富網通'
								);
									
											
//檔案類型
$config['mail_box_type'] = array(
	"A" => "掛號信",
	"B" => "包裹",
	"C" => "代收包裹",
	"D" => "送洗衣物"			
);
