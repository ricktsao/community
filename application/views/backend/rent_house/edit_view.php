<div class="page-header">
	<h1>
		租屋資料編輯
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			
		</small>
	</h1>
</div>
<?php //dprint($edit_data);?>
<div class="row">
	<div class="col-xs-12 form-horizontal">
	
		<?php
		
		echo textOption("標題", "title", $edit_data);
		echo textOption("月租金", "rent_price", $edit_data,'元','（請輸入數字）');
		echo textOption("押金", "deposit", $edit_data);
		
		echo textOption("格局", "room", $edit_data,'房','（請輸入數字）');
		echo textOption("", "livingroom", $edit_data,'廳','（請輸入數字）');
		echo textOption("", "bathroom", $edit_data,'衛','（請輸入數字）');
		echo textOption("位於幾樓", "locate_level", $edit_data);
		echo textOption("總樓層", "total_level", $edit_data);
		echo textOption("地址", "addr", $edit_data);
		?>
		<div class="hr hr-16 hr-dotted"></div>
		<?php
		echo textOption("身份要求", "tenant_term", $edit_data);
		echo textOption("性別要求", "gender_term", $edit_data);
		echo textOption("隔間材質", "meterial", $edit_data);
		echo textOption("可遷入日", "move_in", $edit_data);
		echo textOption("最短租期", "rent_term", $edit_data);
		echo textOption("面積(坪)", "area_ping", $edit_data);
		echo textOption("法定用途", "usage", $edit_data);
		echo textOption("型態/現況", "current", $edit_data);
		?>
		<div class="hr hr-16 hr-dotted"></div>
		<div class="form-group ">
			<label for="flag_cooking" class="col-xs-12 col-sm-3 control-label no-padding-right">是否可開伙</label>
			<div class="col-xs-12 col-sm-4">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('flag_cooking', tryGetData('flag_cooking', $edit_data, 0));?>
				</label>
			</div>
		</div>
		<div class="form-group ">
			<label for="flag_pet" class="col-xs-12 col-sm-3 control-label no-padding-right">是否可養寵物</label>
			<div class="col-xs-12 col-sm-4">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('flag_pet', tryGetData('flag_pet', $edit_data, 0));?>
				</label>
			</div>
		</div>
		<div class="form-group ">
			<label for="flag_parking" class="col-xs-12 col-sm-3 control-label no-padding-right">是否有停車位</label>
			<div class="col-xs-12 col-sm-4">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('flag_parking', tryGetData('flag_parking', $edit_data, 0), 'yes_no_array2');?>
				</label>
			</div>
		</div>
		<div class="form-group ">
			<label for="gender_term" class="col-xs-12 col-sm-3 control-label no-padding-right">性別要求</label>
			<div class="col-xs-12 col-sm-4">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('gender_term', tryGetData('gender_term', $edit_data, 0), 'gender_array2');?>
				</label>
			</div>
		</div>
		<?php
		echo textOption("生活機能", "living", $edit_data);
		echo textOption("附近交通", "traffic", $edit_data);
		echo textAreaOption("特色說明", "desc", $edit_data);
		echo pickDateOption($edit_data);
		echo checkBoxOption("啟　用", "launch", $edit_data);
		?>


	</div>	
</div>