
<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">主旨 : </label>
		<div class="col-xs-12 col-sm-6">
			<?php echo tryGetData('title',$edit_data); ?>
		</div>
	</div>
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">廠商名稱 : </label>
		<div class="col-xs-12 col-sm-6">
			<?php echo tryGetData('content',$edit_data); ?>
		</div>
	</div>
	
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">廠商電話1 : </label>
		<div class="col-xs-12 col-sm-6">
			<?php echo tryGetData('brief',$edit_data); ?>
		</div>
	</div>
	
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">廠商電話2 : </label>
		<div class="col-xs-12 col-sm-6">
			<?php echo tryGetData('brief2',$edit_data); ?>
		</div>
	</div>

	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">廣告圖 : </label>
		<div class="col-xs-12 col-sm-6">
			<img  border="0" style="width:400px;" src="<?php echo tryGetData('img_filename',$edit_data); ?>">		
		</div>
	</div>
		
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">有效日期 : </label>
		<div class="col-xs-12 col-sm-6">
			<?php echo showEffectiveDate($edit_data["start_date"], $edit_data["end_date"], $edit_data["forever"]) ?>
		</div>
	</div>
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				回上一頁
			</a>		
			
		</div>
	</div>
</form>
	
	

  