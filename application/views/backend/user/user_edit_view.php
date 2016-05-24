<div class="page-header">
	<h1>
		住戶資料編輯
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			
		</small>
	</h1>
</div>

<article class="well">
			<?php
			if (isNotNull(tryGetData('id', $edit_data, NULL)) ){
			?>
				<a class="btn btn-sm btn-info" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $edit_data), "role"=>tryGetData('role', $edit_data))); ?>" >
					<i class="icon-edit bigger-120"></i>變更磁卡
				</a>
				<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("setParking",TRUE,NULL,array("sn"=>tryGetData('sn', $edit_data), "role"=>tryGetData('role', $edit_data))); ?>">
					<i class="icon-edit bigger-120"></i>設定車位
				</a>
			<?php
			} else {
			?>
				<a class="btn btn-sm btn-info" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $edit_data), "role"=>tryGetData('role', $edit_data))); ?>" >
					<i class="icon-edit bigger-120"></i>設定磁卡
				</a>
				<a class="btn btn-sm btn-gray" href="#" onclick='return alert("請先設定住戶磁卡")'>
					<i class="icon-edit bigger-120"></i>設定車位
				</a>
			<?php
			}

			$building_id = tryGetData('building_id', $edit_data);
			$tmp_array = array("sn" => tryGetData('sn', $edit_data)
							,  "id" => tryGetData('id', $edit_data)
							,  "b_id" => base64_encode($building_id)
							,  "n" => tryGetData('name', $edit_data)
							,  "g" => tryGetData('gender', $edit_data)
							,  "role"=>tryGetData('role', $edit_data)
							) ;
			if ( isNotNull(tryGetData('app_id', $edit_data , NULL)) || isNotNull(tryGetData('act_code', $edit_data , NULL))) {
			?>
				<a class="btn btn-sm btn-pink" href="<?php echo bUrl("resetActCode",TRUE,NULL, $tmp_array); ?>" onclick='return confirm("重設APP開通碼，必須請住戶重新執行APP啟用程序，\n\n請再次確認是否重設??")'>
					<i class="icon-edit bigger-120"></i>重設APP開通碼
				</a>
			<?php
			} else {

				if (isNotNull(tryGetData('id', $edit_data, NULL)) ){
				?>
					<a class="btn btn-sm btn-pink" href="<?php echo bUrl("resetActCode", TRUE, NULL, $tmp_array); ?>" onclick='return confirm("設定APP開通碼，須請住戶執行APP啟用程序，\n\n請確認??")'>
						<i class="icon-edit bigger-120"></i>設定APP開通碼
					</a>
				<?php
				} else {
				?>
					<a class="btn btn-sm btn-gray" href="#" onclick='return alert("請先設定住戶磁卡")'>
						<i class="icon-edit bigger-120"></i>設定APP開通碼
					</a>
				<?php
				}
			}
			?>
</article>

<?php
  if(validation_errors() != false) {
  //  echo "<div id='errors'>" . validation_errors() . "</div>" ;
  }
?>
<form action="<?php echo bUrl("updateUser")?>" method="post"  id="update_form" class="form-horizontal" role="form">

	<?php echo form_hidden("role", 'I');?>
	<?php echo form_hidden("comm_id", tryGetData('comm_id', $edit_data));?>
	<?php echo form_hidden("id", tryGetData('id', $edit_data));?>
	<?php echo form_hidden("app_id", tryGetData('app_id', $edit_data));?>
	<?php
	//echo textDisplay("角　色", 'I', config_item('role_array'));
	?>
	<?php
	if(tryGetData('sn', $edit_data) > 0) {
	?>
	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">磁　卡</label>
		<div class="col-xs-12 col-sm-3">
			<label class="middle" style="width:100%;">
			<?php
			if (isNotNull(tryGetData('id', $edit_data , NULL)) ){
				echo mask($edit_data['id'] , 2, 4);
			} else {
				echo '（未登錄）';
			}
			?>
			</label>
		</div>

		<!-- <div class="col-xs-12 col-sm-2">
			<?php
			if (isNotNull(tryGetData('id', $edit_data , NULL)) ){
			?>
			<a class="btn  btn-sm btn-yellow" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $edit_data), "role"=>tryGetData('role', $edit_data))); ?>">
				<i class="icon-edit bigger-120"></i>變更磁卡
			</a>
			<?php
			}
			?>
		</div> -->
	</div>


	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">APP ID</label>
		<div class="col-xs-12 col-sm-3">
			<label class="middle" style="width:100%;">
			<?php
			if (isNotNull(tryGetData('app_id', $edit_data , NULL)) ){
				echo mask($edit_data['app_id'] , 2, 4).'　　　';

			} else {
				
				if (isNotNull(tryGetData('act_code', $edit_data , NULL)) ){
					echo '（APP 開通碼：'.tryGetData('act_code', $edit_data).'）';
				} else {
					echo '（待開通）';
				}
			}
			?>
			</label>
		</div>

		<!-- <div class="col-xs-12 col-sm-2">
			<?php
					$building_id = tryGetData('building_id', $edit_data);
					$tmp_array = array("sn" => tryGetData('sn', $edit_data)
									,  "id" => tryGetData('id', $edit_data)
									,  "b_id" => base64_encode($building_id)
									,  "n" => tryGetData('name', $edit_data)
									,  "g" => tryGetData('gender', $edit_data)
									,  "role"=>tryGetData('role', $edit_data)
									) ;
			if ( isNotNull(tryGetData('app_id', $edit_data , NULL)) || isNotNull(tryGetData('act_code', $edit_data , NULL))) {
			?>
				<a class="btn  btn-sm btn-pink" href="<?php echo bUrl("resetActCode",TRUE,NULL, $tmp_array); ?>">
					<i class="icon-edit bigger-120"></i>重設
				</a>
			<?php
			} else {

				if (isNotNull(tryGetData('id', $edit_data , NULL)) ){

					?>
					<a class="btn  btn-sm btn-pink" href="<?php echo bUrl("resetActCode", TRUE, NULL, $tmp_array); ?>">
						<i class="icon-edit bigger-120"></i>設定
					</a>
				<?php
				}
			}
			?>
		</div> -->


	</div>
	
	<?php
	}
	?>

	<?php echo textOption("＊姓　名","name",$edit_data);?>
	<?php echo textOption("電　話","tel",$edit_data);?>
	<?php echo textOption("＊行動電話","phone",$edit_data);?>


	<?php
	$error = '';
	if (form_error('b_part_01') || form_error('b_part_02') || form_error('b_part_03')) {
		$error = 'has-error';
	}
	?>

	<div class="form-group <?php echo $error?>">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">＊戶　別</label>
		<div class="col-xs-12 col-sm-6">
			<label class="middle" style="width:100%;">
			<?php
			echo $building_part_01 .'：';
			$js = 'id="b_part_01"';
			echo form_dropdown('b_part_01', $building_part_01_array, tryGetData('b_part_01', $edit_data), $js);
			echo '&nbsp;&nbsp;';
			echo $building_part_02 .'：';
			$js = 'id="b_part_02"';
			echo form_dropdown('b_part_02', $building_part_02_array, tryGetData('b_part_02', $edit_data), $js);
			echo '&nbsp;&nbsp;';
			echo $building_part_03 .'：';
			echo '<input type="text" name="b_part_03" value="'.tryGetData('b_part_03', $edit_data).'" size="1" id="b_part_03">';
			?>
			</label>
		</div>
			<?php
			echo '<div class="help-block col-xs-12 col-sm-4 col-sm-reset inline">';
			echo '<div class="error">';
			echo form_error('b_part_01');
			echo form_error('b_part_02');
			echo form_error('b_part_03');
			echo '</div>';
			echo '</div>';
			?>
	</div>

	
	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">性 別</label>
		<div class="col-xs-12 col-sm-4">
			<label class="middle" style="width:100%;">
			<?php echo gender_radio('gender', (int) tryGetData('gender', $edit_data, 1));?>
			</label>
		</div>
	</div>
	
	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">所有權人</label>
		<div class="col-xs-12 col-sm-4">
			<label class="middle" style="width:100%;">
			<?php echo generate_radio('is_owner', $edit_data['is_owner']);?>
			</label>
		</div>
	</div>

	<?php echo textOption("所有權人地址", "owner_addr", $edit_data);?>

	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">警急聯絡人</label>
		<div class="col-xs-12 col-sm-4">
			<label class="middle" style="width:100%;">
			<?php echo generate_radio('is_contact', $edit_data['is_contact']);?>
			</label>
		</div>
	</div>
	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">投票權限</label>
		<div class="col-xs-12 col-sm-4">
			<label class="middle" style="width:100%;">
			<?php echo generate_radio('voting_right', $edit_data['voting_right']);?>
			</label>
		</div>
	</div>
	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">瓦斯登記權限</label>
		<div class="col-xs-12 col-sm-4">
			<label class="middle" style="width:100%;">
			<?php echo generate_radio('gas_right', $edit_data['gas_right']);?>
			</label>
		</div>
	</div>

	<?php echo textOption("門牌號碼","addr",$edit_data,'若有瓦斯登記權限，請一併填寫門牌號碼');?>
	
	
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

	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">管 委</label>
		<div class="col-xs-12 col-sm-4">
			<label class="middle" style="width:100%;">
			<?php echo generate_radio('is_manager', $edit_data['is_manager']);?>
			</label>
		</div>
	</div>
	<?php //echo checkBoxOption("警急聯絡人", "is_contact", $edit_data);?>
	<?php //echo checkBoxOption("投票權限", "voting_right", $edit_data);?>
	<?php //echo checkBoxOption("瓦斯登記權限", "gas_right", $edit_data);?>
	<?php //echo checkBoxOption("管委", "is_manager", $edit_data);?>

	<?php
	$error = '';
	if (form_error('manager_title') ) {
		$error = 'has-error';
	}
	?>

	<div class="form-group <?php echo $error?>">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">＊管委職稱</label>
		<div class="col-xs-12 col-sm-6">
			<label class="middle" style="width:100%;">
			<?php
			$js = 'id="manager_title"';
			echo form_dropdown('manager_title', $manager_title_array, tryGetData('manager_title', $edit_data), $js);

			?>
			</label>
		</div>
			<?php
			echo '<div class="help-block col-xs-12 col-sm-4 col-sm-reset inline">';
			echo '<div class="error">';
			echo form_error('manager_title');
			echo '</div>';
			echo '</div>';
			?>
	</div>




	<?php echo pickDateOption($edit_data);?>

	<?php echo checkBoxOption("啟　用", "launch", $edit_data);?>
	
	
	
	
	
	
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

                                                                                                                                 