<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="<?php echo tryGetData("website_title",$webSetting);?>" name="Author"> 
<meta name="keywords" lang="zh-TW" content="<?php echo tryGetData("meta_keyword",$webSetting);?>"/>
<meta name="description" content="<?php echo tryGetData("meta_description",$webSetting);?>">
<title><?php echo tryGetData("website_title",$webSetting);?></title>

<link rel="stylesheet" href="<?php echo base_url().$templateUrl;?>js/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url().$templateUrl;?>css/default.css">


<!-- 本頁使用-->
<?php echo $style_css;?>

<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url().$templateUrl;?>js/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
<script type="text/javascript" src="<?php echo base_url().$templateUrl;?>js/fancybox/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script>
$(function(){
	$('#topBtn').click(function(){		
		
		$('html,body').animate({
			scrollTop: 0
		}, 600);
 
		return false;
	})
})
</script>
<!-- 本頁使用-->
<?php echo $style_js;?>


</head>


<body>

<?php echo $header;?>




<?php echo $content;?>

<?php echo $footer;?>



</body>
</html>

