<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
<div class="form-group ">
	<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">廠商名稱 : </label>
	<div class="col-xs-12 col-sm-6">
		<?php echo tryGetData("filename",$edit_data);?>				
	</div>
</div>

<div class="form-group ">
	<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">聯絡電話一 : </label>
	<div class="col-xs-12 col-sm-6">
		<?php echo tryGetData("brief",$edit_data);?>				
	</div>
</div>

<div class="form-group ">
	<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">聯絡電話二 : </label>
	<div class="col-xs-12 col-sm-6">
		<?php echo tryGetData("brief2",$edit_data);?>				
	</div>
</div>

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
		<?php
		$cost = tryGetData("url",$edit_data);
		if($cost != "")
		{
			echo $cost." 元";
		}
		else 
		{
		 	echo "不收費";
		}
		 ?>
	</div>
</div>

<div class="form-group ">
	<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">圖　　片 : </label>
	<div class="col-xs-12 col-sm-6">
       <?php 
       $edoma_sn = tryGetData('img_filename2',$edit_data);
       $img_ary = explode(',', tryGetData('img_filename',$edit_data));
                        foreach ($img_ary as $img) {
                            $img_url = "http://edoma.acsite.org/edoma/upload/content_photo/{$edoma_sn}/{$img}";
                            echo '<img  border="0" style="width:400px;" src="'.$img_url.'">';
                        }
       ?>
			
	</div>
</div>
	

<div class="form-group ">
	<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">收費起訖日 : </label>
	<div class="col-xs-12 col-sm-6">
		
		<?php echo showEffectiveDate($edit_data["start_date"],$edit_data["end_date"], $edit_data["forever"]) ?>		
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
	

  