<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
	<title><?php echo tryGetData("website_title",$webSetting);?></title>
    <!-- 全站 -->
    <link rel="stylesheet" href="<?php echo base_url().$templateUrl;?>css/default.css">
	<script src="<?php echo base_url().$templateUrl;?>js/jquery-1.12.2.min.js"></script>
	
	<!-- 本頁使用-->
	<?php echo $style_css;?>
	<!-- 本頁使用-->
	<?php echo $style_js;?>
</head>
<body>

<?php echo $header;?>

<?php echo $content;?>

<?php echo $footer;?>



</body>
</html>

