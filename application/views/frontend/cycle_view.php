<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
    body,
    html {
        margin: 0;
        padding: 0;
    }
    
    body {
        background: url(<?php echo base_url();
        ?>template/<?php echo $this->config->item('frontend_name');
        ?>/images/bg.jpg) top center no-repeat #aac9eb;
        height: 1920px;
    }
    
    #block {
        height: 260px;
        width: 900px;
        margin: 0 auto;
        position: relative;
    }
    
    #block > a {
        display: block;
        width: 100px;
        height: 100px;
        bottom: 0;
        left: 60px;
        position: absolute;
    }
    
    #slide {
        width: 900px;
        height: 1008px;
        margin: 0 auto;
    }
    
    #slide > div {
        width: 900px;
        height: 1008px;
        overflow: scroll;
    }
    
    #slide > div img {
        max-width: 100%;
        display: block;
    }
    </style>
</head>

<body>
    <div id="block">
        <a href="#" id="fc"></a>
    </div>
    <div id="slide" class="slideshow">
        <?php
		foreach ($list as $key => $item) 
		{
			$type_title = "";
			switch($item["content_type"])
			{
				case 'news':
					$type_title = "社區公告";
				case 'bulletin':
					$type_title = "管委公告";
				break;
			}
			
			echo '
			<div>
				<h1 style="text-align:center">'.$type_title.'</h1>
				<h2>'.$item["title"].'</h2>
				<p>'.$item["content"].'</p>
				<p><img src="'.$item["img_filename"].'"></p>
			</div>		
			          
			'; 
			
		}		
	?>
    </div>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/jquery-1.12.4.min.js"></script>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/jquery.cycle2.min.js"></script>
    <script>
    $(function() {

        $('.slideshow').cycle({
            slides: "> div",
            speed:2000//速度
        });



        $('#fc').click(function() {
            launchIntoFullscreen(document.documentElement);
        })
    })

    function launchIntoFullscreen(element) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
    }
    </script>
</body>

</html>
