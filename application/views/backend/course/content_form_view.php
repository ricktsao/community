

<?php showOutputBox("tinymce/tinymce_js_view", array('elements' => 'content'));?>
<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<?php echo textOption("課程主旨","title",$edit_data); ?>
	  	
	<?php
	  echo textAreaOption("課程內容","content",$edit_data);
	?>	
	<?php echo checkBoxOption("收費","brief",$edit_data);?>
	<?php 
		//$elements = array("title"=>"代表圖", "id"=>"img_filename","name"=>"img_filename","img_value"=>tryGetData('img_filename', $edit_data),"orig_value"=>tryGetData('orig_img_filename', $edit_data));
		//showOutputBox("tools/pickup_img_view", array('elements'=>$elements)); 
	?>
	
	<?php
	//echo dropdownOption("分類","parent_sn",$edit_data,$cat_list); 
	?>
	
	<?php 
		//echo urlOption("開啟方式","url",$edit_data); 
	?>
	
	
	<div class="form-group ">
		<label for="start_date" class="col-xs-12 col-sm-3 control-label no-padding-right">課程日期</label>
		<div class="col-xs-12 col-sm-4">
			<input type="text" onclick="WdatePicker()" value="<?php echo tryGetData("start_date",$edit_data)?>" class="width-30" name="start_date" id="start_date">					
		</div>
		<div class="help-block col-xs-12 col-sm-reset inline"></div>
	</div>
	
	
	<?php 
	//echo textOption("排序","sort",$edit_data); 
	?>
	<?php echo checkBoxOption("啟用","launch",$edit_data);?>
	<input type="hidden" name="forever" value="<? echo tryGetData('forever', $edit_data)?>" />
	<input type="hidden" name="sn" value="<? echo tryGetData('sn', $edit_data)?>" />
	<input type="hidden" name="content_type" value="<? echo tryGetData('content_type', $edit_data)?>" />
		

	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				Back
			</a>		
		

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				Submit
			</button>
			
		</div>
	</div>
</form>
	
	

  