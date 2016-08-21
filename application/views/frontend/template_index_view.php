<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
	<title><?php echo tryGetData("website_title",$webSetting).' - '.tryGetData("comm_name",$webSetting);?></title>
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

<?php
if($show_left_menu==1)
{
	echo 
	'<div class="mPrimary">
		<div id="left_content">'.$left_menu.'</div>
		<div id="right_content">'.$content.'</div>
		<div style="clear:both"></div>
	</div>';
}
else
{
	echo '<div class="mPrimary">'.$content.'</div>';
}	
?>

<?php echo $footer;?>

<script>

var min = 5;//分鐘
var coundownTime = 1000*60*min;


$(function() {

	$("#member_login_btn").fancybox({

    	afterShow:function(){
    		$('#member_login_form input[type=password]').focus();
    	}
    });

	setInterval(function(){
		coundownTime -= 1000;
		console.log(coundownTime);
		if(coundownTime<=0){
			self.location="<?php echo frontendUrl('landing');?>";
		}

	},1000);


})
</script>
</body>
</html>

