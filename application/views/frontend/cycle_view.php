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
        position: relative;
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
        overflow: hidden;
        font-size: 20px;
    }
    
    #slide > div img {
        max-width: 100%;
        display: block;
    }
    
    #dates {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        text-align: center;
        font-weight: bold;
        font-size: 50px;
        padding-top: 20px;
    }
    
    #marquee {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 40px;
        font-size: 50px;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div id="block">
        <a href="#" id="fc"></a>
    </div>
    <marquee id="marquee" direction="left">This text will scroll from bottom to top</marquee>
    <div id="dates">
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
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/jquery-1.12.4.min.js"></script>
    <script src="<?php echo base_url();?>template/<?php echo $this->config->item('frontend_name');?>/js/jquery.cycle2.min.js"></script>
    <script>
    var baseUrl = "<?php echo base_url('upload/content_photo/');?>";

    var day_list = ['日', '一', '二', '三', '四', '五', '六'];

    var date = new Date();
    var day = date.getDay();

    var today = date.getFullYear() + " 年 " + (date.getMonth() + 1) + " 月 " + date.getDate() + " 日 星期" + day_list[day];

    // console.log(today);


    //day_list[day]
    $(function() {
        /*
     
        */

        $('#dates').html(today);


        $('#fc').click(function() {
            launchIntoFullscreen(document.documentElement);
        })


        $.ajax({
            url: "<?php echo frontendUrl("cycle","ajaxGetNews");?>",
            dataType: "JSON",
            cache: false,
            success: function(data) {
                console.log(data);



                var mainPage = [];

                for (var i = 0; i < data.length; i++) {

                    var _title = "";

                    if(data[i].title!=undefined){
                        _title = data[i].title;
                    }

                    var subPage = pText(data[i].content);

                    if(subPage){

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

                var innerCon = "";

                for (var i = 0; i < mainPage.length; i++) {
                    var m = mainPage[i];
                    innerCon += "<div><h1>" + m.title + "</h1>";
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
                    speed: <?php echo $cycle_sec;?> //速度
                });

            },
            error: function() {
                console.log("A");
            }


        })


    })







    function pText(_txt) {
        if(!_txt){
            return false;
        }
        var pageFontLimit = 500;
        var page = [];

        var pageTemp = '';
        var arr_t = _txt.split("\n");

        for (var i = 0; i < arr_t.length; i++) {

            pageTemp += arr_t[i];

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
