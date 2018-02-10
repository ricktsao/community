<div class="primary">
    <div class="form_group">
		<div class="form_page_title"><?php echo $course_info["title"];?></div>
		<div class="form_page_text">
		<?php echo $course_info["content"];?>
		</div>
		<div class="form_page_img">
		<?php echo $course_info["img_filename"];?>
		</div>
		<br>
		<button class="btn block" onclick="event.preventDefault(); history.back(1);"><i class="fa fa-chevron-left"></i> 回上頁 </button>
	</div>
</div>