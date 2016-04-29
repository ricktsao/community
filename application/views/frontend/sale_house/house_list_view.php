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

        <table class="table_style" style="width:100%;">
            <thead>
                <div>
                    <tr>
                        <td class="text_left">房源列表</td>
                        <td style="width:15%;">
                            <a href="#" class="orange_btn">
                                    坪數
                                <i style="display:none" class="fa fa-arrow-down" aria-hidden="true"></i>
                                <i style="display:none" class="fa fa-arrow-up" aria-hidden="true"></i> 
                            </a>
                        </td>
                        <td style="width:15%;">
                            <a href="#" class="gray_btn">
                                總價
                                <i style="display:none" class="fa fa-arrow-down" aria-hidden="true"></i>
                                <i style="display:none" class="fa fa-arrow-up" aria-hidden="true"></i> 
                            </a>
                        </td>
                    </tr>
                </div>
            </thead>
            <tbody>

			<?php
			foreach ($houses as $house) {

				$photos = tryGetData('photos', $house, NULL);
				if ( isNotNull($photos) ) {
					$photo = $photos[0]['photo'];
					$photo = '<img src="'.$photo.'" alt="物件照片" onerror="if (this.src != \''.base_url('/upload/rent.jpg').'\') this.src = \''.base_url('/upload/rent.jpg').'\';">';
				} else {

					$photo = '<img src="'.base_url('/upload/rent.jpg').'" alt="物件照片" >';
				}

				echo sprintf('
							<tr>
								<td>
									<div class="rent_list_img">
										%s
									</div>
									<div class="rent_list_info">
										<div class="p_title"><a href="%s">%s</a></div>
										<div class="p_style1">%s　｜　%d/%d層</div>
										<div class="p_style2">%s</div>
										<div class="p_style3">%d房 %d廳 %d衛 %d陽台</div>
									</div>
								</td>
								<td class="text_center">%d 坪</td>
								<td class="text_center"><span class="price">%s</span> 萬元</td>
							</tr>'
							, $photo
							, fUrl('index/?sn='.$house['sn'])
							, $house['title']
							, $house['sale_type']
							, $house['locate_level']
							, $house['total_level']
							, $house['addr']
							, $house['room']
							, $house['livingroom']
							, $house['bathroom']
							, $house['balcony']
							, $house['area_ping']
							, number_format_clean($house['total_price'],2)
					
				
				);

			
			}
			
			?>

            </tbody>
        </table>
        
        <?php echo showFrontendPager($pager)?>
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
