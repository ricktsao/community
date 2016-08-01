<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
    * {
        box-sizing: border-box;
        font-family: "Microsoft JhengHei";
    }

    body,
    html {
        margin: 0;
        padding: 0;
        position: relative;
    }
    
    body {
        /*
        background: url(<?php echo $bg_img;?>) center center no-repeat #aac9eb;        
        background-size:100% 100%;
        height: 1920px;
        width: 100%;
        min-width: 1075px;
        */
    }
    
    #bg {
        z-index:1;
        min-width:1075px;
        width: 100%;
        height: 1920px;
        position: absolute;
        top: 0;
        left: 0;
        background: url(<?php echo $bg_img;?>) center center no-repeat;        
       background-size: 100% 100%;

    }

    #block {
        z-index:40;
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
        z-index: 20;
        position: relative;
        width: 1075px;
        height: 1351px;
        margin: 0 auto -20px;
        

        background: url(<?php echo base_url().$templateUrl;?>/images/bg3.png) center center no-repeat;
    }
    
    #slide > div {
        width: 1030px;
        height: 1250px;
        overflow: hidden;
        font-size: 20px;
        padding: 70px 0 0 40px;
    }
    
    #slide > div > h1 {
        text-align: center;
    }
    
    #slide > div img {
        max-width: 100%;
        max-height: 100%;
        margin: 0 auto;
        display: block;
    }
    
    #dates {
          z-index: 20;
        position: relative;
    
        font-weight: bold;
        font-size: 36px;
        margin: 0 auto -20px;
        
      
        width: 1075px;
      
    }

    #dates > div {
        background: url(<?php echo base_url().$templateUrl;?>/images/bg1.png) center center repeat-x;
       margin: 0 auto;
       position: relative;
      
       height: 186px;      
        width: 1042px;
        padding: 22px 40px 0; 
    }
    
    #d1{
        padding-top: 10px;
    }
    #d3 {
        position: absolute;
        right: 410px;
		font-size: 48px;
        top: 55px;
    }
	#d4 {
        position: absolute;
        right: 20px;
		font-size: 120px;
        top: 5px;
        color:#F15A24;
    }

    #dd {
        position: absolute;
        top: 18px;
        left:20px;
        font-size: 48px;
    }

    #dd tr > td:last-child{
        text-align: right;
    }

 

    #comm_title {  
      z-index: 20;     
        position: relative;
     
        font-weight: bold;
        font-size: 36px;
       
        width: 1075px;
        margin: 0 auto -20px;
      
    }

    #comm_title > div {
        background: url(<?php echo base_url().$templateUrl;?>/images/bg2.png) center center repeat-x;
       margin: 0 auto;
       position: relative;
        font-size: 72px;
        text-align: center;
        height: 186px;      
        width: 1042px;
        padding: 35px 40px 0; 
    }

    #marquee {
         z-index: 20;
        position: relative;
          width: 1075px;
          margin: 0 auto;
      
        font-size: 72px;
        font-weight: bold;
         background: url(<?php echo base_url().$templateUrl;?>/images/bg4.png) center center no-repeat;
          height: 185px;
    }

     #marquee > marquee {
     	
        position: absolute;   
      top: 60px;
        width: 940px;
        left: 50%;
        margin-left: -470px;
        color:red;
     }

     #date_2 {

        display: inline-block;
        width: 236px;
        text-align: right;
     }

    </style>
</head>

<body>
<div id="bg"></div>
    <div id="block">
        <a href="#" id="fc"></a>
    </div>
  
    <div id="dates">
        <div>
           
            <table id="dd">
                
            </table>
            <div id="d3"></div>
			<div id="d4"></div>
        </div>
       
    </div>

    <div id="comm_title">
        <div> <?php echo $comm_name; ?></div>
    </div>
    <div id="slide" class="slideshow">

        <?php

      
                /*
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
            
            $img_str = '';
            foreach( $item["photo_list"] as $key => $photo ) 
            {
                $img_url = base_url('upload/content_photo/'.$photo["content_sn"].'/'.$photo["img_filename"]);
                
                $img_str .= '<p><img src="'.$img_url.'"></p>';
            }
            
            
            echo '
            <div>
                <h1 style="text-align:center">'.$type_title.'</h1>
                <h2>'.$item["title"].'</h2>
                <p>'.$item["content"].'</p>
                '.$img_str.'
            </div>      
                      
            '; 
            
        }   

        */  
    ?>
    </div>
      <div id="marquee" >
        <marquee direction="left">
            <?php echo $marquee_str;?>
        </marquee>
    </div>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/jquery-1.12.4.min.js"></script>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/jquery.cycle2.min.js"></script>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/lunar.js"></script>
    <script>
    var baseUrl = "<?php echo base_url('upload/content_photo/');?>";

    var day_list = ['日', '一', '二', '三', '四', '五', '六'];


    
    function renderDate(){

        var date = new Date();
        var day = date.getDay();
        var ty = date.getFullYear();
        var tm = date.getMonth() + 1;
        var td = date.getDate();
        var thour = "0"+date.getHours();
        var tmin = "0"+date.getMinutes();

        thour = thour.substr(-2);
        tmin = tmin.substr(-2);

        console.log(mainx(ty,tm,td,0));
        var d2 = mainx(ty,tm,td,0)
        var d1 = "<tr><td>西元</td><td>" + ty + "年" + tm + "月" + td + "日</td></tr>";//" 星期" + day_list[day];
        var d3 = "星期" + day_list[day] ;
		var d4 =  thour + " : " + tmin;

        $('#dd').html("");
        $('#dd').append(d1);
        $('#dd').append(d2);

        /*$('#d1').html(d1);
        $('#d2').html(d2);*/
        $('#d3').html(d3);
		$('#d4').html(d4);
    }

    function renderSlide(){
    	
    	 ajaxData(true);
    }

    function ajaxData(loop){



    	 $.ajax({
            url: "<?php echo frontendUrl("cmsys","ajaxGetNews");?>",
            dataType: "JSON",
            cache: false,
            success: function(data) {
               console.log(data);
            	if(loop){
            		//numSlides = $('.slideshow').data('cycle.opts').slideCount
            		//console.log(numSlides);
            		$('.slideshow').cycle('destroy');
            		 $('#slide').html("");
            		console.log("timer");
            	}

                var mainPage = [];

                for (var i = 0; i < data.length; i++) {

                    _title = "";

                    if (data[i].title != undefined) {
                        _title = data[i].title;
                    }
                    var subPage = pText(data[i].content);
                    if (subPage) {

                        for (var j = 0; j < subPage.length; j++) {
                            mainPage.push({
                                type: "text",
                                content: subPage[j],
                                title: _title
                            });
                        }
                    }
                    if (data[i].photo_list) {
                        for (var j = 0; j < data[i].photo_list.length; j++) {


                            var photo = data[i].photo_list[j];
                            mainPage.push({
                                type: "image",
                                content: photo.img_filename,
                                title: _title
                            });
                        }
                    }
                }

                console.log(mainPage);

                var innerCon = "";

                for (var i = 0; i < mainPage.length; i++) {
                    var m = mainPage[i];
                    innerCon += "<div>";
                    if (m.type == "image") {
                        innerCon += "<img src='" + m.content + "'/>";
                    } else {
                        innerCon += m.content;
                    }

                    innerCon += "</div>";

                    $('#slide').append(innerCon);

                }



                $('#slide').html(innerCon);
                $('.slideshow').cycle({
                    slides: "> div",
                    timeout: <?php echo $cycle_sec;?> //速度
                });

            },
            error: function() {
                console.log("A");
            }
        })	
    }

    //day_list[day]
    $(function() {
        /*
     
        */
        renderDate();

        setInterval(renderDate,1000*60);

        
     //   $('#dates').html(today);


        $('#fc').click(function() {
            launchIntoFullscreen(document.documentElement);
        })

        ajaxData(false);
       
        setInterval(renderSlide,1000*600);

    })


    function pText(_txt) {

        if (!_txt) {
            return false;
        }

        var pageFontLimit = 500;
        var page = [];

        var pageTemp = '';
        var arr_t = _txt.split("\n");

        for (var i = 0; i < arr_t.length; i++) {

            pageTemp += arr_t[i] + "<br/>";

            if (i + 1 < arr_t.length) {
                if ((pageTemp.length + arr_t[i + 1].length) > pageFontLimit) {
                    page.push(pageTemp);
                    pageTemp = '';
                }
            }

            if (i == arr_t.length - 1) {
                if (pageTemp != '') {
                    page.push(pageTemp);
                }
            }

        }



        return page;

    }



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
