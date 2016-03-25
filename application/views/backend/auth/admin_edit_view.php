<form action="<?php echo bUrl("updateAdmin")?>" method="post"  id="update_form" class="form-horizontal" role="form">
	
	<?php
	 if(tryGetData( 'sn',$edit_data)== '')
	 {
	 	echo textOption("工號","id",$edit_data);
	 }
	 else 
	 {
		echo  textDisplay("工號","id",$edit_data);
	 }	 
	?>
	
	
	<?php if(tryGetData('sn', $edit_data)== ''){ ?>
	<?php echo passwordOption("密碼","password",$edit_data);?>
	<?php } ?>
	



	<?php echo textOption("姓　名","name",$edit_data);?>
	<?php echo textOption("行動電話","phone",$edit_data);?>
	<?php echo textOption("職　稱","job_title",$edit_data);?>
	<?php echo textOption("職務類別","job_type",$edit_data);?>

	<?php echo pickDateOption($edit_data);?>
	
	
	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-3 control-label no-padding-right">權限群組</label>
		<div class="col-xs-12 col-sm-4">
			<label class="middle" style="width:100%;">
				<select multiple="" class="chzn-select" name="group_sn[]" id="form-field-select-4" data-placeholder="請選擇(可複選)..." style="width:100%;">
				<?php
				foreach ($group_list as $key => $item) 
				{
					echo '<option '.(in_array( $item["sn"],$sys_user_group)?"selected":"").'  value="'.$item["sn"].'" />'.$item["title"];						
				}
				?>					
				</select>
				
				<?php
					foreach ($group_list as $key => $item) 
					{
						echo '<input type="hidden" name="old_group_sn[]" value="'.$item["sn"].'">';						
					}
				?>	
			</label>
		</div>
	</div>
	
	
	
	
	<?php echo checkBoxOption("啟用","launch",$edit_data);?>
	
	
	
	
	
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("admin",TRUE,array("sn")) ?>">
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
	
	<input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />
</form>



<script>
		$(function () {

			$(".chzn-select").chosen();

			

			//chosen plugin inside a modal will have a zero width because the select element is originally hidden
			//and its width cannot be determined.
			//so we set the width after modal is show
			$('#modal-form').on('show', function () {
				$(this).find('.chzn-container').each(function(){
					$(this).find('a:first-child').css('width' , '200px');
					$(this).find('.chzn-drop').css('width' , '210px');
					$(this).find('.chzn-search input').css('width' , '200px');
				});
			})
		});
	</script>

                                                                                                                                 