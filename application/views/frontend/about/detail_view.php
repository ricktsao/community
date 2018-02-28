<div class="primary rent">
	<?php if ( count($photo_list)>0 ){ ?>
	<div class="row-6">
		<div id="slide_area">
			<div id="slide">
			<?php

				foreach($photo_list as $photo)
				{
					$cur_photo = base_url('/upload/website/setting/'.$photo['img_filename'])  ;



					echo '<img src="'.$cur_photo.'" width="599" height="447" alt="!!!'.$cur_photo.'" >
				';
				}

			?>
			</div>
			<div id="slide_pager_area">
				<div id="slide_pager">
					<div></div>
				</div>
				<a href="#" class="slide_ctrl_btn pre"><i class="fa fa-chevron-left fa-2x" aria-hidden="true"></i></a>
				<a href="#" class="slide_ctrl_btn next"><i class="fa fa-chevron-right fa-2x" aria-hidden="true"></i></a>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="row-6">
		<div id="rent_info">
			<table>
				<tbody>
					<tr>
						<td>社區名稱</td>
						<td><?php echo tryGetData("comm_name",$webSetting);?></td>
					</tr>
					<tr>
						<td>社區簡介</td>
						<td><?php echo nl2br(tryGetData("comm_desc",$webSetting));?></td>
					</tr>
					<tr>
						<td>社區電話</td>
						<td><?php echo tryGetData("comm_tel",$webSetting);?></td>
					</tr>
					<tr>
						<td>社區地址</td>
						<td><?php echo tryGetData("comm_addr",$webSetting);?></td>
					</tr>


				</tbody>
			</table>
		</div>
	</div>

	<br clear="all" />
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
