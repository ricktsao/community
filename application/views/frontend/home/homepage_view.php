<div class="primary">
	<div class="blockRow">
		<div id="blockA">
			<div class="block_title">
				<img src="<?php echo base_url().$templateUrl;?>images/index_title1.png" alt="">
				<a href="#"><img src="<?php echo base_url().$templateUrl;?>images/more.png" alt=""></a>
			</div>
			<ul id="picGroup" class="ul_unstyle">
				<?php for($i=0;$i<count($album_list);$i++):?>
				<li>
					<div class="dateTime">
						<div class="day"><?php echo $i+1?></div>
						<div class="date"><?php echo $album_list[$i]['start_date'];?></div>
					</div>
					<ul class="film ul_unstyle">
						<?php for($j=0;$j<count($album_list[$i]['imgs']);$j++):?>
						<li>
							<a href="<?php echo frontendUrl('album');?>">
								<img src="<?php echo $album_list[$i]['imgs'][$j]['img_filename'];?>" alt="">
							</a>
						</li>
						<?php endfor;?>					
					</ul>
					<a href="<?php echo frontendUrl('album');?>"><?php echo $album_list[$i]['title'];?></a>
					<div class="clear"></div>
				</li>
				<?php endfor;?>
				
			</ul>
		</div>
		<?php //dprint($houses);?>
		<div id="blockB">
			<div class="block_title">
				<img src="<?php echo base_url().$templateUrl;?>images/index_title2.png" alt="">
				<a href="#"><img src="<?php echo base_url().$templateUrl;?>images/more.png" alt=""></a>
			</div>
			<ul id="rental" class="ul_unstyle">
				<?php
							foreach ($houses as $house) {

				$photos = tryGetData('photos', $house, array());
				//dprint($photos );
				$photo = '';
				if ( sizeof($photos) > 0) {
					$photo = tryGetData('photo', $photos[0], NULL);
					if (isNotNull($photo)) {
						$photo = '<img src="'.$photo.'" alt="'.$photo.'">';
					}
				}
				echo sprintf('	<li>
									<a href="%s" class="img">
										%s
									</a>
									<div class="rental_content">
										<a href="%s">%s</a>
										<div>%s - %s.....</div>
										<div>%d 元/月</div>
									</div>
								</li>'
							, frontendUrl('rent_house','index/?sn='.$house['sn'])
							, $photo
							, frontendUrl('rent_house','index/?sn='.$house['sn'])
							, $house['title']
							, $house['rent_type']
							, $house['house_type']
							, number_format_clean($house['rent_price'],2)
							);
				}
				?>
			</ul>
		</div>
	</div>
</div>
<div class="glayBg">
	<div class="primary" id="secondPart">
		<div style="margin-bottom:40px">
			<div class="leftArea">
				<div class="title_area">
					日行一善
					<a href="<?php echo frontendUrl("daily_good","index"); ?>">更多</a>
				</div>
				<ul class="ul_unstyle list_type_1">
				<?php
				foreach ($daily_good_list as $key => $daily_good_info) 
				{
					echo '<li><a href="'.frontendUrl("daily_good","index").'" >'.showMoreStringSimple($daily_good_info["content"],15) .'</a></li>';
				}
				
				?>
				</ul>
			</div>
			<div class="rightArea">
				<div class="title_area">
					社區公告
					<a href="<?php echo frontendUrl("news","index"); ?>">更多</a>
				</div>
				<ul class="ul_unstyle list_type_2">
				<?php
				//dprint($news_list);
				foreach ($news_list as $key => $news_info) 
				{
					echo					
					'<li><a href="'.frontendUrl("news","index").'" > '.showMoreStringSimple($news_info["title"],40) .'</a>
						<div class="date">'.showDateFormat($news_info["start_date"],"Y.m.d").'</div>
					</li>';
				}
				
				?>
				</ul>
			</div>
			<div class="clear"></div>
		</div>
		<div>
			<div class="leftArea">
				<div class="title_area">
					工商服務
					<a href="#">更多</a>
				</div>
				<div id="cycle_bg">
					<div id="cycle">
						<a href="#"><img src="upload/k4.png" alt=""></a>
						<a href="#"><img src="upload/k4.png" alt=""></a>
						<a href="#"><img src="upload/k4.png" alt=""></a>
						<a href="#"><img src="upload/k4.png" alt=""></a>
						<a href="#"><img src="upload/k4.png" alt=""></a>
						<a href="#"><img src="upload/k4.png" alt=""></a>
					</div>
					<div id="pager"></div>
				</div>
			</div>
			<div class="rightArea">
				<div class="title_area">
					課程訊息
					<a href="<?php echo frontendUrl("course","index");?>">更多</a>
				</div>
				<ul class="ul_unstyle list_type_2">
				<?php
				//dprint($news_list);

				foreach ($course_list as $key => $course_info) 
				{
					echo					
					'<li><a href="'.frontendUrl("course","detail",TRUE,array("all"=>"all"),array("sn"=>$course_info["sn"])).'" > '.showMoreStringSimple($course_info["title"],40) .'</a>
						<div class="date">'.showDateFormat($course_info["start_date"],"Y.m.d").'</div>
					</li>';
				}
				?>	
					
				
				</ul>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>