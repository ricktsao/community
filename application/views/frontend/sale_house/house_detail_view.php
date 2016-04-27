    <div class="primary rent">
        <div class="row" style="display:none">
            <form action="<?php fUrl('index');?>" class="searchArea">
                
                <table>
                    <tr class="borderLine">
                        <td colspan="2">
                          <span>房屋型態</span> <label ><input type="checkbox">公寓 </label>
                          <label ><input name='given_sale_type' type="checkbox">電梯大樓 </label>
                          <label ><input name='given_sale_type' type="checkbox"> 透天厝  </label>  
                          <label ><input name='given_sale_type' type="checkbox">別墅 </label>  
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2"><span>坪數</span>
                            <input type="text" name='given_area_ping_start' class="input_style inline" placeholder="坪"> ~ 
                            <input type="text" name='given_area_ping_end' class="input_style inline" placeholder="坪">
                        </td>
                    </tr>
                     <tr>
                        <td colspan="2"><span>總價範圍</span>  
                            <input type="text" name='given_total_price_start' class="input_style inline" placeholder="元"> ~ 
                            <input type="text" name='given_total_price_end' class="input_style inline" placeholder="元">
                        </td>
                    </tr>
                     <tr>
                        <td><span>格局</span>  
                            <input type="text" name='given_room' class="input_style inline" placeholder="坪">
                        </td>
                        <td>
                            <button class="btn block">送出 <i class="fa fa-chevron-right"></i></button>

                        </td>
                    </tr>


                </table>
                




            </form>


        </div>
<?php
			foreach ($rents as $rent) {

				$photos = tryGetData('photos', $rent, NULL);
				$photo = '';
					$photo = '<img src="'.base_url('/upload/rent.jpg').'" alt="">';
				if ( isNotNull($photos) ) {
					$photo = $photos[0];
					$photo = '<img src="'.$photo.'" alt="">';
					
					
				}
							/*, $photo
							, $rent['title']
							, $rent['sale_type']
							, $rent['locate_level']
							, $rent['total_level']
							, $rent['addr']
							, $rent['room']
							, $rent['livingroom']
							, $rent['bathroom']
							, $rent['balcony']
							, $rent['area_ping']
							, $rent['total_price']*/
?>
        <div id="rent_title"><span>住家出售</span><?php echo $rent['title'];?></div>
        <div class="row">
            <div id="slide_area">
                <div id="slide">
                    <img src="<?php echo base_url('/upload/rent.jpg');?>" alt="">
                    <img src="<?php echo base_url('/upload/rent.jpg');?>" alt="">
                    <img src="<?php echo base_url('/upload/rent.jpg');?>" alt="">
                    <img src="<?php echo base_url('/upload/rent.jpg');?>" alt="">
                    <img src="<?php echo base_url('/upload/rent.jpg');?>" alt="">
                    <img src="<?php echo base_url('/upload/rent.jpg');?>" alt="">
                    <img src="<?php echo base_url('/upload/rent.jpg');?>" alt="">
                    <img src="<?php echo base_url('/upload/rent.jpg');?>" alt="">
                </div>
                <div id="slide_pager_area">
                    <div id="slide_pager">
                        <div></div>
                    </div>
                    <a href="#" class="slide_ctrl_btn pre"><i class="fa fa-chevron-left fa-2x" aria-hidden="true"></i></a>
                    <a href="#" class="slide_ctrl_btn next"><i class="fa fa-chevron-right fa-2x" aria-hidden="true"></i></a>
                </div>
            </div>
            <div id="rent_info">
                <table>
                    <thead>
                        <tr>
                            <td class="text_right rent_title" colspan="2">
                                <span><?php echo number_format_clean($rent['total_price'],2);?></span>萬元
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="rent_sub_title">房源介绍</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>單價</td>
                            <td><?php echo number_format_clean($rent['unit_price'],2).' 萬元/坪';?></td>
                        </tr>
                        <tr>
                            <td>格局</td>
                            <td><?php echo sprintf("%d房 %d廳 %d衛 %d陽台" , $rent['room'], $rent['livingroom'], $rent['bathroom'], $rent['balcony']);?></td>
                        </tr>
                        <tr>
                            <td>坪數</td>
                            <td><?php echo $rent['area_ping'];?> 坪</td>
                        </tr>
                        <tr>
                            <td>樓層</td>
                            <td><?php echo $rent['locate_level'];?> F / <?php echo $rent['total_level'];?> F</td>
                        </tr>
                        <tr>
                            <td>型態/現況</td>
                            <td><?php echo $rent['sale_type'];?>/<?php echo $rent['house_type'];?></td>
                        </tr>
                        <tr>
                            <td>是否含租約</td>
                            <td><?php echo $rent['flag_rent'];?></td>
                        </tr>
                        <tr>
                            <td>車位</td>
                            <td><?php echo $rent['flag_parking'];?></td>
                        </tr>
                        <tr>
                            <td>地址</td>
                            <td><?php echo $rent['addr'];?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br clear="all" />
        </div>
<?php
		}
?>


        <table class="table_style" style="width:100%;">
            <thead>
                <div>
                    <tr>
                        <td class="text_left">其他房源</td>
                    </tr>
                </div>
            </thead>
            <tbody>

			<?php
			foreach ($rents as $rent) {
				echo sprintf('
							<tr>
								<td>坪數說明：%s</td>
							</tr>
							<tr>
								<td>生活機能：%s</td>
							</tr>
							<tr>
								<td>附近交通：%s</td>
							</tr>
							<tr>
								<td>特色說明：%s</td>
							</tr>'
							, $rent['area_desc']
							, $rent['living']
							, $rent['traffic']
							, nl2br($rent['desc'])
					
				
				);

			
			}
			
			?>

            </tbody>
        </table>
    </div>

    <script>
    $(function() {

        $('#slide').cycle({
            pager: "#slide_pager > div",
            pagerTemplate: "<a href=# ><img src='{{src}}'></a>",
            timeout: 0
        });

        $('.slide_ctrl_btn.next').click(function() {
            $('#slide_pager').animate({
                scrollLeft: "+=102"
            }, 300);

            return false;

        })

        $('.slide_ctrl_btn.pre').click(function() {
            $('#slide_pager').stop().animate({
                scrollLeft: "-=102"
            }, 300);

            return false;

        })
    })
    </script>
