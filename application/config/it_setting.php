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
$config['gender_array'] = array(  '1' => '男'
								, '2' => '女'
								);
// 角色
$config['role_array'] = array(  'I' => '住戶'
								, 'M' => '物業人員'
								//, 'G' => '警衛'
								//, 'S' => '秘書'
								, 'F' => '富網通'
								);

// 全資產等級
$config['customer_level_array'] = array(  'A' => 'A'
										, 'B' => 'B'
										, 'C' => 'C'
										, 'D' => 'D'
										, 'E' => 'E'
										, 'F' => 'F'
										);


// 行程類別
$config['cal_type_array'] = array('visit'		=>	'拜訪'
								, 'conference'	=>	'會議'
								, 'signed'		=>	'簽約'
								, 'other'		=>	'其它'
								);


// 客戶有無需求
$config['demand_array'] = array( 0	=>	'無需求'
								,1	=>	'有需求'
								);
// 客戶需求類型 
$config['demand_type_array'] = array( 1	=>	'想買土地'
									 ,2	=>	'想賣土地'
									 ,3	=>	'想買權值'
									 ,4	=>	'想賣權值'
									);

// 拜訪類型(1:自行拜訪,2:主管陪同)
$config['visit_type_array'] = array( 1	=>	'自行拜訪'
									,2	=>	'主管陪同'
									);

// 是否有拜訪到
$config['is_effective_array'] = array(0	=>	'未拜訪到'
									,1	=>	'有拜訪到'
									);



// 拜訪結果
$config['visit_result_array'] = array( 1	=>	'本人'
									,  2	=>	'家人'
									,  3	=>	'不在家'
									,  4	=>	'社區門禁'
									,  5	=>	'無此地址'
									,  6	=>	'找不到人/地址'
									,  7	=>	'公出'
									,  8	=>	'開會'
									,  9	=>	'休假'
									,  99	=>	'其他'
									);


// 拜訪方式(1:面談,2:電訪) 
$config['is_first_array'] = array( 0	=>	'未填'
								 , 1	=>	'初訪'
								 , 2	=>	'覆訪'
								);
// 拜訪方式(1:面談,2:電訪) 
$config['visit_way_array'] = array(1	=>	'面談'
								,  2	=>	'電訪'
								);

// 拜訪目的(1:開發,2:銷售,3:一般拜訪,4:偶遇,5:其他 )
$config['visit_purpose_array'] = array(1	=>	'開發'
									,  2	=>	'銷售'
									,  3	=>	'一般拜訪'
									,  4	=>	'偶遇'
									,  5	=>	'其它'
									);

// 拜訪場合(1:客戶住家,2:客戶公司,3:公開活動,4:其他 )
$config['visit_place_array'] = array(1	=>	'客戶住家'
									,2 	=>	'客戶公司'
									,3 	=>	'公開活動'
									,4 	=>	'其它'
									);


// 興趣－美食
$config['hobby_food'] = array(1	=>	'日式'
							,  2	=>	'中式'
							,  3	=>	'西式'
							,  4	=>	'素食'
							,  5	=>	'不吃牛'
							);

// 興趣－酒類
$config['hobby_wine'] = array(1	=>	'紅酒'
							,  2	=>	'威士忌'
							,  3	=>	'啤酒'
							,  4	=>	'白酒'
							);

// 興趣－旅遊區域
$config['hobby_travel'] = array(1	=>	'大陸'
							,  2	=>	'港澳'
							,  3	=>	'東南亞'
							,  4	=>	'東北亞'
							,  5	=>	'歐洲'
							,  6	=>	'美洲'
							,  7	=>	'澳洲'
							);


// 興趣－運動
$config['hobby_sport'] = array(1	=>	'高爾夫球'
							,  2	=>	'游泳'
							,  3	=>	'腳踏車'
							,  4	=>	'跑步'
							);

// 職業分類
$config['job_type'] = array(
							0=>"-",
							1=>"經營/人資類",
							2=>"行政/總務/法務類",
							3=>"財會/金融專業",
							4=>"行銷/企劃/專案管理類",
							5=>"客服/門市/業務/貿易類",
							6=>"餐飲/旅遊 /美容美髮類",
							7=>"資訊軟體系統類",
							8=>"研發相關類",
							9=>"生產製造/品管/環衛類",
							10=>"操作/技術/維修類",
							11=>"資材/物流/運輸類",
							12=>"營建/製圖類",
							13=>"傳播藝術/設計類",
							14=>"文字/傳媒工作類",
							15=>"醫療/保健服務類",
							16=>"學術/教育/輔導類",
							17=>"軍警消/保全類",
							18=>"其他職類"
							);
							
// 客戶屬性
$config['cust_property'] = array(
							'1' => "原地主",
							'2' => "代書",
							'3' => "建設公司",
							'4' => "同業",
							'5' => "個人投資客",
							'6' => "團體投資客",
							'7' => "其他"
							);			
							
							
// 客戶關係人-分類
$config['relationship_category'] = array(
							   0	=>	'家族'
							,  1	=>	'股東'
							,  2	=>	'同事'
							,  3	=>	'朋友'
							,  4	=>	'鄰居'
							);
							
// 客戶關係人
$config['relationship_array'] = array(
							  1 => "祖父母"
							, 2 => "父母"
							, 3 => "子女"
							, 4 => "孫子女"
							, 5 => "兄弟姐妹"
							, 6 => "配偶"
							, 7 => "叔伯"
							, 8 => "姑姨"
							, 9 => "姪甥"
							, 10 => "堂表關係"
							, 11 => "其他親屬"
							, 12 => "公司"
							, 13 => "投資"
							, 14 => "董事長"
							, 15 => "主管"
							, 16 => "同事"
							, 17 => "朋友"
							, 18 => "鄰居"
							);
											



// 縣市
$config['city_array'] = array(  
		  "a" => "台北市"
		, "b" => "台中市"
		, "c" => "基隆市"
		, "d" => "台南市"
		, "e" => "高雄市"
		, "f" => "新北市"
		, "g" => "宜蘭縣"
		, "h" => "桃園市"
		, "i" => "嘉義市"
		, "j" => "新竹縣"
		, "k" => "苗栗縣"
		, "m" => "南投縣"
		, "n" => "彰化縣"
		, "o" => "新竹市"
		, "p" => "雲林縣"
		, "q" => "嘉義縣"
		, "t" => "屏東縣"
		, "u" => "花蓮縣"
		, "v" => "臺東縣"
		, "w" => "金門縣"
		, "x" => "澎湖縣"
		, "y" => "陽明山"
		, "z" => "連江縣"							  
);


// 縣市
$config['available_city_array'] = array('a' => '台北市'
										, 'f' => '新北市'
										, 'h' => '桃園市'
										, 'j' => '新竹縣'
										, 'o' => '新竹市'
										);



//發名單的有效期限
$config['visit_project_effective_day'] = 60;


//客戶需求-土地坪數範圍級距
//---------------------------------------------------
$config['customer_need_land_ping'] = array();
for ($i=50; $i <= 1000; $i+=50) 
{ 
	$config['customer_need_land_ping'][$i] = $i;
}
for ($i=1100; $i <= 5000; $i+=100) 
{ 
	$config['customer_need_land_ping'][$i] = $i;
}
//---------------------------------------------------

//客戶需求-土地總價級距
//---------------------------------------------------
$config['customer_need_land_price'] = array(
	0 => "不限",
	100 => 100,
	200 => 200,
	300 => 300,
	400 => 400,
	500 => 500,
	800 => 800,
	1000 => 1000,
	1200 => 1200,
	1500 => 1500,
	2000 => 2000,
	2500 => 2500,
	3000 => 3000,
	4000 => 4000,
	5000 => 5000,
	7500 => 7500,
	10000 => 10000,
	20000 => 20000,
	50000 => 50000
);
//---------------------------------------------------

//案源-土地坪數範圍級距
//---------------------------------------------------
$config['land_ping_array'] = array();
/*
for ($m=0; $m<20; $m++) 
{
	$i = $m*50;
	$j = $i+50;
	$tmp = $i.' ~ '.$j.'坪';
	$config['land_ping_array'][] = $tmp;
}
for ($m=10; $m < 50; $m++) 
{
	$i = $m*100;
	$j = $i+100;
	$tmp = $i.' ~ '.$j.'坪';
	$config['land_ping_array'][] = $tmp;
}
$config['land_ping_array'][] = '5000坪以上';
//---------------------------------------------------
*/

//案源-土地坪數範圍級距
//---------------------------------------------------
$config['land_ping_array'] = array();
for ($m=0; $m<20; $m++) 
{
	if ($m == 0 )
		$i = 10;
	else 
		$i = $m*50;
	$j = $i + 50;
	$tmp = $i.' 坪';//.' ~ '.$j.'坪';
	$config['land_ping_array'][] = $tmp;
}
for ($m=10; $m < 50; $m++) 
{
	$i = $m*100;
	$j = $i+100;
	$tmp = $i.' 坪';//.' ~ '.$j.'坪';
	$config['land_ping_array'][] = $tmp;
}
$config['land_ping_array'][] = '5000 坪以上';
//---------------------------------------------------


// 案源　狀態 （1.正常刊登  5.指定開發，暫不揭露 10.成交   20.作廢   30.更改委託類型  40.過期）
$config['case_status_array'] = array( 0 => '準備中'
									, 1 => '正常刊登'
									, 5 => '指定開發，暫不揭露'
									, 10 => '成交'
									, 20 => '作廢'
									, 30 => '更改委託類型'
									, 40 => '過期');
// 案源　本案特色
$config['case_point_array'] = array( array('value'=>1, 'title' => '臨路')
									,array('value'=>2, 'title' => '近公園')
									,array('value'=>3, 'title' => '近學校')
									,array('value'=>4, 'title' => '交通便利')
									,array('value'=>5, 'title' => '近捷運'));
// 案源　現況說明
$config['case_current_array'] = array(  1 => '素地'
									  , 2 => '有地上物'
									  , 3 => '有地上物（出租）'
									  , 4 => '嫌惡設施');


// 案源　住一、住二、住三，商一、商二、商三
$config['case_land_use_kind_detail_array'] = array( 1 => '無'
													, 10 => '住一'
													, 20 => '住二'
													, 30 => '住三'
													, 40 => '住四'
													, 50 => '住五'
													, 60 => '商一'
													, 70 => '商二'
													, 80 => '商三');

//---------------------------------------------------

// 案源　現況說明
$config['sales_customer_relationship'] = array( 'assigned'		=> '公司名單'
											  , 'byself'		=> '自行新增'
											  , 'restricted'	=> '成交列管'
											  , 'other'			=> '自行約訪');

//訊息分類
$config['sys_message_category_array'] = array("meeting" => '會議'
									  		, "notify" => '通知'
											);
											
											
//檔案類型
$config['planing_file_type_array'] = array(
	"land" => "土管要點",
	"gov" => "政府相關資料",
	"report" => "簡報",
	"fig" => "細部計畫圖",
	"book" => "細部計畫書"		
);
