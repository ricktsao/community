<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>富網通 EDOMA 社區API Url Structure</title>

    <style type="text/css">

    ::selection { background-color: #E13300; color: white; }
    ::-moz-selection { background-color: #E13300; color: white; }

    body {
        background-color: #FFF;
        margin: 40px;
        font-family: "微軟正黑體", Arial, sans-serif;
        color: #4F5155;
		font-size: 14px;
        word-wrap: break-word;
		
    }

    a {
        color: #003399;
        background-color: transparent;
        font-weight: normal;
    }

    h1 {
        color: #444;
        background-color: transparent;
        border-bottom: 1px solid #D0D0D0;
        font-size: 24px;
        font-weight: normal;
        margin: 0 0 14px 0;
        padding: 14px 15px 10px 15px;
    }

    code {
        font-family: Consolas, Monaco, Courier New, Courier, monospace;
        font-size: 16px;
        background-color: #f9f9f9;
        border: 1px solid #D0D0D0;
        color: #002166;
        display: block;
        margin: 14px 0 14px 0;
        padding: 12px 10px 12px 10px;
    }

    #body {
        margin: 0 15px 0 15px;
    }

    table{
        border: 0px solid #D0D0D0;
        width: 720px;
    }

    td, th {
        border: 0px solid #D0D0D0;
        padding: 6px;
		background-color: #f2f2f2;
		vertical-align : middle;text-align: center;
		/*height: 45px;*/
    }

    p.footer {
        text-align: right;
        font-size: 16px;
        border-top: 1px solid #D0D0D0;
        line-height: 32px;
        padding: 0 10px 0 10px;
        margin: 20px 0 0 0;
    }

    #container {
        margin: 10px;
        border: 1px solid #D0D0D0;
        box-shadow: 0 0 8px #D0D0D0;
    }
	.left {text-align: left; padding-left:60px;}
    </style>
</head>
<body>

<div id="container">
    <!-- <h1>API Url Structure</h1> -->
    <h1>API 預設網址  http://27.147.4.239/commapi/</h1>

    <div id="body">

<div class="section" id="defining-the-api-user">
<h3>住戶資訊  (參照 community_cloud.sys_user 資料表)</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-odd"><td>POST</td>
<td>/user/index/</td>
<td class="left">comm_id : 社區ID<br /> id : 住戶ID <br /> app_id : 住戶App ID</td>
<td>取得指定的住戶資訊<br /> (簡單個資與權限)</td>
</tr>
<tr class="row-even"><td>POST</td>
<td>/user/activate/</td>
<td class="left">comm_id : 社區ID<br /> id : 住戶ID <br /> app_id : 住戶App ID</td>
<td>住戶開通</td>
</tr>

</tbody>
</table>
</div>


<div class="section" id="defining-the-api-news">
<h3>社區公告</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-even"><td>GET</td>
<td>/news/index/</td>
<td class="left">comm_id : 社區ID</td>
<td>取得所有社區公告列表</td>
</tr>
<tr class="row-odd"><td>GET</td>
<td>/news/index/</td>
<td class="left">comm_id : 社區ID<br /> sn : 社區公告編號</td>
<td>取得指定的一則社區公告</td>
</tr>
<tr class="row-even"><td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>


<div class="section" id="defining-the-api-news">
<h3>管委公告</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-even"><td>GET</td>
<td>/bulletin/index/</td>
<td class="left">comm_id : 社區ID</td>
<td>取得所有管委公告列表</td>
</tr>
<tr class="row-odd"><td>GET</td>
<td>/bulletin/index/</td>
<td class="left">comm_id : 社區ID<br /> sn : 管委公告編號</td>
<td>取得指定的一則管委公告</td>
</tr>
<tr class="row-even"><td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>

<div class="section" id="defining-the-api-news">
<h3>日行一善</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-even"><td>GET</td>
<td>/daily_good/index/</td>
<td class="left">comm_id : 社區ID</td>
<td>取得所有日行一善列表</td>
</tr>
<tr class="row-odd"><td>GET</td>
<td>/daily_good/index/</td>
<td class="left">comm_id : 社區ID<br /> sn : 日行一善編號</td>
<td>取得指定的一則日行一善</td>
</tr>
<tr class="row-even"><td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>

<div class="section" id="defining-the-api-news">
<h3>課程專區</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-even"><td>GET</td>
<td>/course/index/</td>
<td class="left">comm_id : 社區ID</td>
<td>取得所有課程專區列表</td>
</tr>
<tr class="row-odd"><td>GET</td>
<td>/course/index/</td>
<td class="left">comm_id : 社區ID<br /> sn : 課程專區編號</td>
<td>取得指定的一則課程專區</td>
</tr>
<tr class="row-even"><td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>


<div class="section" id="defining-the-api-rents">
<h3>租屋資訊</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-even"><td>GET</td>
<td>/rent/index/</td>
<td class="left">comm_id : 社區ID</td>
<td>取得所有租屋資訊列表</td>
</tr>
<tr class="row-odd"><td>GET</td>
<td>/rent/index/</td>
<td class="left">comm_id : 社區ID<br /> sn : 租屋資訊編號</td>
<td>取得指定的一則租屋資訊</td>
</tr>
<tr class="row-odd"><td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>


    </div>

    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php //echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>
