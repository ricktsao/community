<div class="primary">
    <div class="form_group">
		<div class="form_page_title"><?php echo $message_info["title"];?></div>
		<div class="form_page_text">
		<?php echo nl2br($message_info["msg_content"]);?>
		</div>
		
		<button class="btn block" onclick="event.preventDefault(); history.back(1);"><i class="fa fa-chevron-left"></i> 回上頁 </button>
	</div>
</div>