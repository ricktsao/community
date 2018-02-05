<div class="primary">
    <div class="nRow">
        <div class="nCol-4">
        	<div id="album_area" class="bRadius bShadow">
        		 <div class="block_title">
					 社區花絮
		                <a href="<?php echo frontendUrl('album');?>">
		                    <i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Learn more
		                </a>
        		 </div>

				<div id="album_slide"></div>
				<div id="album_slide_title"></div>
				<div class="album_pager" id="album_pager"></div>

        	</div>

        </div>
        <div class="nCol-8">
        	<div id="rent_area" class="bRadius bShadow">
        		 <div class="block_title">
					 租售服務
		                <a href="<?php echo frontendUrl('rent_house','index')?>">
		                    <i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Learn more
		                </a>
        		 </div>
                <table id="rent_table">
                 <?php
                foreach ($houses as $house) {

                    $photos = tryGetData('photos', $house, array());
                    //dprint($photos );
                    $photo = '';
                    if ( sizeof($photos) > 0) {
                        $photo = tryGetData('photo', $photos[0], NULL);
                        if (isNotNull($photo)) {
                            $photo = '<img src="'.$photo.'" width=113 height=66>';
                        }
                    }
                    echo sprintf('  <tr>
                                        <td>
                                            <a href="%s" title="【租屋】 %s" class="img">
                                                %s
                                            </a>
                                        </td>
                                        <td>
                                            <a href="%s">%s</a>
                                            <div>%s - %s.....</div>
                                            <div>%d 元/月</div>
                                        </td>
                                    </tr>'
                                , frontendUrl('rent_house','index/?sn='.$house['sn'])
                                , $house['title']
                                , $photo
                                , frontendUrl('rent_house','index/?sn='.$house['sn'])
                                , $house['title']
                                , $house['rent_type']
                                , $house['house_type']
                                , number_format_clean($house['rent_price'],2)
                                );
                }
                ?>
            </table>
        	</div>



        </div>
        <div style="clear:both"></div>
    </div>
     <div class="nRow">
        <div class="nCol-4">
        	<div id="course_area" class="bRadius bShadow">

				<div class="block_title">
					 課程訊息
		                <a href="<?php echo frontendUrl('course','index');?>">
		                    <i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Learn more
		                </a>
        		 </div>
                 <table class="tables">
                         <?php
                //dprint($news_list);

                foreach ($course_list as $key => $course_info)
                {
                    echo
                    '<tr>
                        <td>
                            <a href="'.frontendUrl("course","detail",TRUE,array("all"=>"all"),array("sn"=>$course_info["sn"])).'" > '.showMoreStringSimple($course_info["title"],40) .'</a>
                        </td>
                    </tr>';
                }
                ?>

                 </table>
        	</div>

        </div>
        <div class="nCol-4">
        	<div id="good_area" class="bRadius bShadow">
        		
				<div class="block_title">
					 日行一善
		                <a href="<?php echo frontendUrl("daily_good","index");?>">
		                    <i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Learn more
		                </a>
        		 </div>
                <table class="tables">
                <?php

                foreach ($daily_good_list as $key => $daily_good_info)
                {
                    echo '<tr>
                            <td>
                                <a href="'.frontendUrl("daily_good","index").'" >'.showMoreStringSimple($daily_good_info["content"],15) .'</a>
                            </td>
                        </tr>';
                }


                ?>

                </table>
        	</div>




        </div>
        <div class="nCol-4">
        	<div id="service_area" class="bRadius bShadow">
        		 <div class="block_title">
					 工商服務
		                <a href="<?php echo frontendUrl('ad')?>">
		                    <i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Learn more
		                </a>
        		 </div>
                 <div id="cycle_slide"></div>
                <div id="cycle_pager" class="album_pager"></div>
        	</div>
        </div>
         <div style="clear:both"></div>
    </div>




</div>

<script>
var album = <?php echo json_encode($album_list);?>;
var ads = <?php echo json_encode($ad_list);?>;
var albumContent = '';
var adsContent = '';
var albumUrl = '<?php echo frontendUrl("album","album_detail")?>';

for(var i=0;i<album.length;i++){
	albumContent+="<a href='"+albumUrl+"/"+album[i].sn+"' data-cycle-title='"+album[i].title+"'><img src='"+album[i].img_filename+"' /></a>";
}

for(var i=0;i<ads.length;i++){
    adsContent+="<a href='javascript:void(0)'><img src='"+ads[i].img_filename+"'/></a>";

}

//adsContent+="<a href='javascript:void(0)'><img src='http://localhost:8080/community/upload/k1.png'/></a>";
//adsContent+="<a href='javascript:void(0)'><img src='http://localhost:8080/community/upload/house.jpg'/></a>";


$(function() {

	$('#album_slide').html(albumContent);
    $('#cycle_slide').html(adsContent);

	$('#album_slide').cycle({
		caption:"#album_slide_title",
		captionTemplate:"{{cycleTitle}}",
		pager: "#album_pager",
        pagerTemplate : "<a href='#'></a>",
        slides: ">a"

      //  slides: " > img",
      //  fx: "carousel"
    });

    $('#cycle_slide').cycle({
        slides: ">a",
        pager: "#cycle_pager",
        pagerTemplate : "<a href='#'></a>"
    });


})
</script>
