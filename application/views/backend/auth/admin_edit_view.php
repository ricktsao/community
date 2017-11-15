<?php
  if(validation_errors() != false) {
    echo "<div id='errors'>" . validation_errors() . "</div>" ;
  }
?>
<form action="<?php echo bUrl("updateAdmin")?>" method="post"  id="update_form" class="form-horizontal" role="form">

	<?php
	
	echo form_hidden("role", $role);
	echo form_hidden("comm_id", $edit_data['comm_id']);

		if(tryGetData('sn', $edit_data) > 0) {
			echo textDisplay("帳　號", "account", $edit_data);
			echo form_hidden( "account", $edit_data['account']);
			echo textOption("職　稱","title",$edit_data);
		} else {
			echo textOption("帳　號","account",$edit_data);
			echo passwordOption("密　碼","password",$edit_data);
			echo textOption("職　稱","title",$edit_data);
		}
	?>
	<?php echo textOption("姓　名","name",$edit_data);?>
	<?php echo textOption("電　話","tel",$edit_data);?>
	<?php echo textOption("行動電話","phone",$edit_data);?>






	
	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">性 別</label>
		<div class="col-xs-12 col-sm-4">
			<label class="middle" style="width:100%;">
			<?php echo gender_radio('gender', (int) tryGetData('gender', $edit_data, 1));?>
			</label>
		</div>
	</div>
	
	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">權限群組</label>
		<div class="col-xs-12 col-sm-4">
			<label class="middle" style="width:100%;">
				<?php
					echo '<select multiple class="chzn-select" name="group_sn[]" id="form-field-select-4" data-placeholder="請選擇(可複選)..." style="width:100%;">';
				
				foreach ($group_list as $key => $item) {
					echo '<option '.(in_array( $item["sn"], $sys_user_group) ? "selected" : "").'  value="'.$item["sn"].'" />'.$item["title"];
				}
				?>					
				</select>
				
				<?php
				foreach ($sys_user_group as $key => $value) {
					echo '<input type="hidden" name="old_group_sn[]" value="'.$value.'">';
				}
				?>	
			</label>
		</div>
	</div>
	<div class="hr hr-16 hr-dotted"></div>

	<?php echo checkBoxOption("啟　用", "launch", $edit_data);?>
	
	
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("admin",TRUE,array("sn")) ?>">
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
	
	<input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />
</form>


<script>
	$(function () {

		$(".chzn-select").chosen().change(function(){

			$('input[name=is_manager]').prop('checked',false);
			
			if(!$(this).val()){
				$('input[name=is_manager][value="0"]').prop("checked",true);
			
				return 
			}
			var _idx = $(this).val().indexOf("2");
		
			if(_idx>-1){
				$('input[name=is_manager][value=1]').prop("checked",true);
				
			}else{

				$('input[name=is_manager][value=0]').prop("checked",true);
				
			}
		});

		

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