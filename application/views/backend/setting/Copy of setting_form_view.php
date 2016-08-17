<form action="<? echo bUrl("updateSetting")?>" method="post"  id="update_form" class="form-horizontal" role="form">
	<?php echo textOption("網站名稱：","website_title",$edit_data);?>
	<?php echo textAreaOption("SEO Keywords：","meta_keyword",$edit_data);?>
	<?php echo textAreaOption("SEO Description：","meta_description",$edit_data);?>
	
		<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				Submit
			</button>
			
		</div>
	</div>

</form>        