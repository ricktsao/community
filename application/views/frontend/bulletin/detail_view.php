<div class="primary">
    <div class="form_group">
		<div class="form_page_title"><?php echo $item_info["title"];?></div>
		<div class="form_page_text">
		<?php echo $item_info["content"];?>
		</div>
		<div class="form_page_img">
		<img src="<?php echo $item_info["img_filename"];?>" >
		</div>
		<br>
		<button class="btn block" onclick="event.preventDefault(); history.back(1);"><i class="fa fa-chevron-left"></i> 回上頁 </button>
	</div>
</div>