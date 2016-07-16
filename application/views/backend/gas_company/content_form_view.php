

<?php showOutputBox("tinymce/tinymce_js_view", array('elements' => 'content'));?>
<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">


	<?php echo textOption("公司名稱","title",$edit_data); ?>
	<?php echo textOption("地址","content",$edit_data); ?>
	<?php echo textOption("電話","brief",$edit_data); ?>
	<?php echo textOption("手機","brief2",$edit_data); ?>
	<?php echo textOption("網址","url",$edit_data); ?>
	
	
	
	<div class="form-group ">
        <label class="col-xs-12 col-sm-2 control-label no-padding-right" for="content">圖片</label>
        <div class="col-xs-12 col-sm-6">
            <input type="file" name="img_filename" size="20" /><br /><br />
				<input type="hidden" name="orig_img_filename" value="<?php echo tryGetData('orig_img_filename',$edit_data)?>"  />
				<?php if(isNotNull(tryGetData('img_filename',$edit_data))){ ?>
				<img  border="0" style="width:200px;" src="<?php echo tryGetData('img_filename',$edit_data); ?>"><br />		
				
            	<?php } ?>
        <div class="message">
            <?php echo  form_error('start_date');?>
        </div>
        </div>
    </div>
	
	<input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />
	<input type="hidden" name="content_type" value="<?php echo tryGetData('content_type', $edit_data)?>" />
	<input type="hidden" name="forever" value="<?php echo tryGetData('forever', $edit_data)?>" />		
	<input type="hidden" name="sort" value="500" />
	<input type="hidden" name="launch" value="1" />
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				儲存
			</button>
			
		</div>
	</div>
</form>
	
	

  