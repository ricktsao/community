<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
    html,
    body {
        margin: 0;
        padding: 0;
        font-family: "微軟正黑體";
    }
    
    .bg {
        background: url(<?php echo base_url();?>template/frontend/images/landing_bg.png) center center no-repeat;
        height: 196px;
        z-index:500;
        position: fixed;
        left: 0;
        right: 0;
        background-size: 100% 100%;
    }

     .s1,.s2{        
        color:#FFF;
        line-height: 166px;
        text-align: center;
     }

    .s1 {
         background: url(<?php echo base_url();?>template/frontend/images/landing_2.png) center center no-repeat;
        width: 165px;
        height: 166px;
        display: inline-block;
        font-size:36px;
     }

     .s2 {
         background: url(<?php echo base_url();?>template/frontend/images/landing_3.png) center center no-repeat;
        width: 308px;
        height: 166px;
        display: inline-block;
         font-size:24px;
     }

    #topL {       
        top: 0;      
      
    }

    #topL > div {
        background: url(<?php echo base_url();?>template/frontend/images/landing_1.png) center center no-repeat;
        width: 549px;
        height: 196px;
        margin: 0 auto;
        color:#FFF;
        font-size: 42px;
        line-height: 196px;
        text-align: center;
    }

    #bottomL{

        bottom: 0;
    }

    #bottomL > div { 
        padding-top: 15px;
        width: 1024px;
        margin: 0 auto;
        text-align: center;
    }
  
    
  
    
    #slide {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        z-index:100;
        display: block;
    }
    
    #slide > img {
        display: block;
        width: 100%;
        height: 100%;
    }

    #blocks {
    	display: block; 
    	top: 0;
    	right: 0;
    	bottom: 0;
    	left: 0;
    	position: absolute;   
    	z-index: 999; 	

    }
    </style>
</head>

<body>
    <div id="topL" class="bg">
        <div> <?php echo $comm_name; ?></div>

    </div>
    <div id="bottomL" class="bg">
        <div>
            <div class="s1" id="block1"></div>
            <div class="s2" id="block2"></div>
            <div class="s2" id="block3"></div>
            <div class="s1" id="block4"></div>


        </div>

    </div>
	<a href="<?php echo frontendUrl()?>" id="blocks"></a>
    <div id="dates">
        <div>
            <table id="dd">
            </table>
            <div id="d3"></div>
            <div id="d4"></div>
        </div>
    </div>
    <div id="slide">
        <?php foreach ($img_list as $value):?>
        <img src="<?php echo $value;?>" alt="">        
        <?php endforeach;?> 
    </div>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/jquery-1.12.4.min.js"></script>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/jquery.cycle2.min.js"></script>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/lunar_l.js"></script>
    <script>
    var day_list = ['日', '一', '二', '三', '四', '五', '六'];

    function renderDate() {

        var date = new Date();
        var day = date.getDay();
        var ty = date.getFullYear();
        var tm = date.getMonth() + 1;
        var td = date.getDate();
        var thour = "0" + date.getHours();
        var tmin = "0" + date.getMinutes();

        thour = thour.substr(-2);
        tmin = tmin.substr(-2);

        console.log(mainx(ty, tm, td, 0));
        var d2 = mainx(ty, tm, td, 0)
        var d1 = "西元" + ty + "年" + tm + "月" + td + "日"; //" 星期" + day_list[day];
        var d3 = "星期" + day_list[day];
        var d4 = thour + " : " + tmin;

      
        $('#block2').html(d1);
        $('#block3').html(d2);

        /*$('#d1').html(d1);
        $('#d2').html(d2);*/
        $('#block1').html(d3);
        $('#block4').html(d4);
    }



    $(function() {
    	renderDate();
        $('#slide').cycle();
         setInterval(renderDate,1000*20);

    })
    </script>
</body>

</html>
