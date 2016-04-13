

<div class="page-header">
	<h1>
		租屋資料編輯
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			
		</small>
	</h1>
</div>
<?php //dprint($edit_data);?>

<form action="<?php echo bUrl("update")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
<div class="row">
	<div class="col-xs-12 form-horizontal">
	
		<?php
		
		echo form_hidden('sn', tryGetData('sn', $edit_data,NULL));
		echo textOption("<span class='red'>＊</span>租屋標題", "title", $edit_data);
		echo textOption("<span class='red'>＊</span>聯絡人", "name", $edit_data);
		echo textOption("<span class='red'>＊</span>聯絡電話", "phone", $edit_data);
		?>
		<div class="hr hr-16 hr-dotted"></div>
		<?php
		echo textNumberOption("<span class='red'>＊</span>格局 - 房", "room", $edit_data,'房');
		echo textNumberOption(" - 廳", "livingroom", $edit_data,'廳');
		echo textNumberOption(" - 衛", "bathroom", $edit_data,'衛');
		echo textNumberOption("<span class='red'>＊</span>位於幾樓", "locate_level", $edit_data);
		echo textNumberOption("<span class='red'>＊</span>總樓層", "total_level", $edit_data);
		echo textNumberOption("<span class='red'>＊</span>面積", "area_ping", $edit_data,'坪');
		echo textNumberOption("<span class='red'>＊</span>月租金", "rent_price", $edit_data,'元');
		echo textOption("<span class='red'>＊</span>押金", "deposit", $edit_data, 'ex.兩個月');
		echo textOption("<span class='red'>＊</span>地址", "addr", $edit_data);
		?>
		<div class="hr hr-16 hr-dotted"></div>
		<?php
		echo textOption("<span class='red'>＊</span>可遷入日", "move_in", $edit_data,'ex.隨時');
		echo textOption("<span class='red'>＊</span>最短租期", "rent_term", $edit_data,'ex.一年');
		echo textOption("<span class='red'>＊</span>型態/現況", "current", $edit_data, 'ex.電梯大樓、公寓、獨立套房、華廈、整層住家');
		echo textOption("法定用途", "usage", $edit_data,'ex.住宅用');
		echo textOption("隔間材質", "meterial", $edit_data);
		?>
		<div class="hr hr-16 hr-dotted"></div>
		<div class="form-group ">
			<label for="flag_cooking" class="col-xs-12 col-sm-3 control-label no-padding-right"><span class='red'>＊</span>是否可開伙</label>
			<div class="col-xs-12 col-sm-4">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('flag_cooking', tryGetData('flag_cooking', $edit_data, 0));?>
				</label>
			</div>
		</div>
		<div class="form-group ">
			<label for="flag_pet" class="col-xs-12 col-sm-3 control-label no-padding-right"><span class='red'>＊</span>是否可養寵物</label>
			<div class="col-xs-12 col-sm-4">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('flag_pet', tryGetData('flag_pet', $edit_data, 0));?>
				</label>
			</div>
		</div>
		<div class="form-group ">
			<label for="flag_parking" class="col-xs-12 col-sm-3 control-label no-padding-right"><span class='red'>＊</span>是否有停車位</label>
			<div class="col-xs-12 col-sm-8">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('flag_parking', tryGetData('flag_parking', $edit_data, 0), 'parking_array');?>
				</label>
			</div>
		</div>
		<div class="form-group ">
			<label for="gender_term" class="col-xs-12 col-sm-3 control-label no-padding-right"><span class='red'>＊</span>性別要求</label>
			<div class="col-xs-12 col-sm-4">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('gender_term', tryGetData('gender_term', $edit_data, 0), 'gender_array2');?>
				</label>
			</div>
		</div>
		<?php
		echo textOption("身份要求", "tenant_term", $edit_data, 'ex.學生、上班族、家庭');
		echo textOption("生活機能", "living", $edit_data);
		echo textOption("附近交通", "traffic", $edit_data);
		echo textAreaOption("<span class='red'>＊</span>特色說明", "desc", $edit_data);
		echo pickDateOption($edit_data);
		echo checkBoxOption("啟　用", "launch", $edit_data);
		?>

	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			
			<button class="btn btn-info" id="Submit" type="submit">
				<i class="icon-ok bigger-110"></i>
				確定上傳
			</button>
			
		</div>
	</div>

	</div>	
</div>
</form>