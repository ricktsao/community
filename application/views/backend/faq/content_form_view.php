

<?php showOutputBox("tinymce/tinymce_js_view", array('elements' => 'content'));?>
<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<?php echo textOption("單元名稱","title",$edit_data); ?>
	
	<?php
	  echo textAreaOption("內容","content",$edit_data);
	?>	
	
	<?php 
		//$elements = array("title"=>"代表圖", "id"=>"img_filename","name"=>"img_filename","img_value"=>tryGetData('img_filename', $edit_data),"orig_value"=>tryGetData('orig_img_filename', $edit_data));
		//showOutputBox("tools/pickup_img_view", array('elements'=>$elements)); 
	?>
	
	<?php echo dropdownOption("分類","parent_sn",$edit_data,$cat_list); ?>
	
	<?php 
		//echo urlOption("開啟方式","url",$edit_data); 
	?>
	
	
	
	
	
	<?php echo pickDateOption($edit_data);?>
	<?php echo textOption("排序","sort",$edit_data); ?>
	<?php echo checkBoxOption("啟用","launch",$edit_data);?>
	
	<input type="hidden" name="sn" value="<? echo tryGetData('sn', $edit_data)?>" />
	<input type="hidden" name="content_type" value="<? echo tryGetData('content_type', $edit_data)?>" />
		

	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				回上一頁
			</a>		
		

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				送出
			</button>
			
		</div>
	</div>
</form>
	
	

  