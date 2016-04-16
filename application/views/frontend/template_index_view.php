<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
	<title><?php echo tryGetData("website_title",$webSetting);?></title>
    <!-- 全站 -->
	<link rel="stylesheet" href="<?php echo base_url().$templateUrl;?>css/default.css">
	<link rel="stylesheet" href="<?php echo base_url().$templateUrl;?>js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url().$templateUrl;?>css/font-awesome/css/font-awesome.min.css">
	
	
	<script src="<?php echo base_url().$templateUrl;?>js/jquery-2.2.3.min.js"></script>
	<script src="<?php echo base_url().$templateUrl;?>js/jquery.cycle2.min.js"></script>
	<script src="<?php echo base_url().$templateUrl;?>js/jquery.cycle2.carousel.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url().$templateUrl;?>js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript" src="<?php echo base_url().$templateUrl;?>js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
		
	
	
	<!-- 本頁使用-->
	<?php echo $style_css;?>
	<!-- 本頁使用-->
	<?php echo $style_js;?>
</head>
<body>

<?php echo $header;?>

<?php echo $content;?>

<?php echo $footer;?>

<script>
$(function() {

    $("#member_login_btn").fancybox();


})
</script>
</body>
</html>

