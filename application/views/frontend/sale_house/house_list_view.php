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

        <div style="display:none" id="rent_title"><span>住家出售</span>海景佛跳牆 住商兩用</div>
        <div style="display:none" class="row">
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
                                <span>1200</span>元/月
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="rent_sub_title">房源介绍</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>押金</td>
                            <td>面議</td>
                        </tr>
                        <tr>
                            <td>格局</td>
                            <td>4房2廳2衛1陽台</td>
                        </tr>
                        <tr>
                            <td>坪數</td>
                            <td>68坪</td>
                        </tr>
                        <tr>
                            <td>樓層</td>
                            <td>7/16F</td>
                        </tr>
                        <tr>
                            <td>型態/現況</td>
                            <td>透天厝/獨立套房</td>
                        </tr>
                        <tr>
                            <td>車位</td>
                            <td>平面式，已含租金內</td>
                        </tr>
                        <tr>
                            <td>社區</td>
                            <td>冠德天驕</td>
                        </tr>
                        <tr>
                            <td>地址</td>
                            <td>新北市三重區重新路</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br clear="all" />
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
			foreach ($rents as $rent) {

				$photos = tryGetData('photos', $rent, NULL);
				$photo = '';
					$photo = '<img src="'.base_url('/upload/rent.jpg').'" alt="">';
				if ( isNotNull($photos) ) {
					$photo = $photos[0];
					$photo = '<img src="'.$photo.'" alt="">';
					
					
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
							, fUrl('index/?sn='.$rent['sn'])
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
							, number_format_clean($rent['total_price'],2)
					
				
				);

			
			}
			
			?>

            </tbody>
        </table>
        <div class="pager">
            <a href="#"><i class="fa fa-chevron-left"></i></a>
            <div>1</div>
            <a href="#">2</a>
            <a href="#">...</a>
            <a href="#"><i class="fa fa-chevron-right"></i></a>
        </div>
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
