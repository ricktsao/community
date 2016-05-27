<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
<div class="form-group ">
	<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">課程主旨 : </label>
	<div class="col-xs-12 col-sm-6">
		<?php echo tryGetData("title",$edit_data);?>				
	</div>
</div>

<div class="form-group ">
	<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">課程內容 : </label>
	<div class="col-xs-12 col-sm-6">
		<?php echo nl2br(tryGetData("content",$edit_data));?>				
	</div>
</div>

<div class="form-group ">
	<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">收　　費 : </label>
	<div class="col-xs-12 col-sm-6">
		<?php echo tryGetData("brief",$edit_data)==1?"是":"否" ;?>				
	</div>
</div>

<div class="form-group ">
	<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">圖　　片 : </label>
	<div class="col-xs-12 col-sm-6">
		<img  border="0" style="width:400px;" src="<?php echo tryGetData('img_filename',$edit_data); ?>">		
	</div>
</div>
	

<div class="form-group ">
	<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">課程日期收費 : </label>
	<div class="col-xs-12 col-sm-6">
		<?php echo showDateFormat(tryGetData("start_date",$edit_data),"Y-m-d") ;?>				
	</div>
</div	
		

	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				回上一頁
			</a>		
			
		</div>
	</div>

</form>
	

  