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
    }
    
    #dates {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        font-weight: bold;
        font-size: 36px;
        height: 172px;
        z-index:500;
    }
    
    #dates > div {
        background: url('<?php echo $bg_img;?>') center center repeat-x;
        margin: 0 auto;
        position: relative;
        height: 172px;
        width: 1007px;
        padding: 22px 40px 0;
    }
    
    #d1 {
        padding-top: 10px;
    }
    
    #d3 {
        position: absolute;
        right: 400px;
        font-size: 40px;
        top: 55px;
    }
    
    #d4 {
        position: absolute;
        right: 40px;
        font-size: 80px;
        top: 35px;
    }
    
    #dd {
        position: absolute;
        top: 30px;
    }
    
    #dd tr > td:last-child {
        text-align: right;
    }
    
    #slide {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        z-index:100;
        display: none;
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
	<a href="<?php echo frontendUrl("login")?>" id="blocks"></a>
    <div id="dates">
        <div>
            <table id="dd">
            </table>
            <div id="d3"></div>
            <div id="d4"></div>
        </div>
    </div>
    <div id="slide">
        <img src="<?php echo base_url();?>template/frontend/images/film.png" alt="">
        <img src="<?php echo base_url();?>template/frontend/images/qr.png" alt="">
    </div>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/jquery-1.12.4.min.js"></script>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/jquery.cycle2.min.js"></script>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/lunar.js"></script>
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
        var d1 = "<tr><td>西元</td><td>" + ty + "年" + tm + "月" + td + "日</td></tr>"; //" 星期" + day_list[day];
        var d3 = "星期" + day_list[day];
        var d4 = thour + " : " + tmin;

        $('#dd').html("");
        $('#dd').append(d1);
        $('#dd').append(d2);

        /*$('#d1').html(d1);
        $('#d2').html(d2);*/
        $('#d3').html(d3);
        $('#d4').html(d4);
    }



    $(function() {
    	renderDate();
        $('#slide').cycle();
         setInterval(renderDate,1000*60);

    })
    </script>
</body>

</html>
