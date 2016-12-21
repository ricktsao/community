<?php
if ( tryGetData('is_post', $edit_data,0) == 1 || $mode=='view') {
?>
<script type="text/javascript">
$(document).ready(function(){
    $(".useless_form :input").prop("disabled", true);
    $("#back").prop("disabled", false);

});
</script>
<?php
}
?>
<?php // echo validation_errors(); ?>

<div class="page-header">
	<h1>
		售屋資料編輯
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
            <?php
            if ( tryGetData('is_post', $edit_data,0) == 1) {
                echo '<span style="color:#c00">此筆售屋資料已發佈聯賣，無法再進行任何編修。</span>';
                //echo '此筆租屋資料已發佈聯賣，若再進行資料修改，不會與先前的資料同步。';
            } else {
                if ($mode == 'view') {
                    echo '<span style="color:#c00">此筆售屋資料由Edoma發佈，無法進行任何編修。</span>';
                }
            }
            ?>
		</small>
	</h1>
</div>
<?php //dprint($edit_data);?>
<?php
if ( tryGetData('is_post', $edit_data,0) != 1 && $mode!='view') {
?>
<form action="<?php echo bUrl("update")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
<?php
} else {
?>
<form class='useless_form' action='#'>
<?php
}
?>
<div class="row">
	<div class="col-xs-12 form-horizontal">

		<?php

		echo form_hidden('sn', tryGetData('sn', $edit_data,NULL));
		echo textOption("<span class='red'>＊</span>售屋標題", "title", $edit_data);
		echo textOption("<span class='red'>＊</span>聯絡人", "name", $edit_data);
		echo textOption("<span class='red'>＊</span>聯絡電話", "phone", $edit_data);
		?>
		<div class="hr hr-16 hr-dotted"></div>
		<div class="form-group ">
			<label for="house_type" class="col-xs-12 col-sm-2 control-label no-padding-right"><span class='red'>＊</span>型 態</label>
			<div class="col-xs-12 col-sm-6">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('house_type', tryGetData('house_type', $edit_data, 'a'), 'house_type_array');?>
				</label>
			</div>
		</div>
		<div class="form-group ">
			<label for="rent_type" class="col-xs-12 col-sm-2 control-label no-padding-right"><span class='red'>＊</span>類 別</label>
			<div class="col-xs-12 col-sm-6">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('sale_type', tryGetData('sale_type', $edit_data, 'a'), 'rent_sale_type_array');?>
				</label>
			</div>
		</div>
		<div class="form-group ">
			<label for="direction" class="col-xs-12 col-sm-2 control-label no-padding-right"><span class='red'>＊</span>座 向</label>
			<div class="col-xs-12 col-sm-8">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('direction', tryGetData('direction', $edit_data, 'a'), 'house_direction_array');?>
				</label>
			</div>
		</div>
		<?php
		echo textNumberOption("<span class='red'>＊</span>格局 - 房", "room", $edit_data, 0, 10, 1,'房');
		echo textNumberOption(" - 廳", "livingroom", $edit_data, 0, 10, 1,'廳');
		echo textNumberOption(" - 衛", "bathroom", $edit_data, 0, 10, 1,'衛');
		echo textNumberOption(" - 陽台", "balcony", $edit_data, 0, 10, 1,'陽台');
		echo textNumberOption("<span class='red'>＊</span>面積", "area_ping", $edit_data, 0, 300, 0.01, '坪');
		echo textNumberOption("<span class='red'>＊</span>公設比", "pub_ratio", $edit_data, 0, 60, 0.1, '%');
		echo textOption("<span class='red'>＊</span>坪數說明", "area_desc", $edit_data,'ex.主建物、主建物 和 附屬建物坪數');
		echo textOption("裝潢程度", "decoration", $edit_data);
		?>
		<div class="hr hr-16 hr-dotted"></div>
		<?php
		echo textNumberOption("<span class='red'>＊</span>位於幾樓", "locate_level", $edit_data, -3, 30, 1,'樓');
		echo textNumberOption("<span class='red'>＊</span>總樓層", "total_level", $edit_data, -3, 30, 1,'樓');
		echo textNumberOption("<span class='red'>＊</span>屋 齡", "house_age", $edit_data, 0, 40, 0.1, '年');
		echo textNumberOption("<span class='red'>＊</span>總 價", "total_price", $edit_data, 0, 9999, 0.01, '萬元');
		echo textNumberOption("<span class='red'>＊</span>每坪單價", "unit_price", $edit_data, 0, 999, 0.01, '萬元');
		echo textNumberOption("管理費", "manage_fee", $edit_data, 0, 100000, 1, '元');
		echo textOption("<span class='red'>＊</span>地址", "addr", $edit_data);
		echo textOption("<span class='red'>＊</span>現 況", "current", $edit_data, '');
		echo textOption("法定用途", "usage", $edit_data,'ex.住宅用');
		echo textOption("隔間材質", "meterial", $edit_data);
		//dprint(config_item('electric_array'));
		?>
		<div class="hr hr-16 hr-dotted"></div>
		<div class="form-group ">
			<label for="flag_rent" class="col-xs-12 col-sm-2 control-label no-padding-right"><span class='red'>＊</span>是否帶租約</label>
			<div class="col-xs-12 col-sm-4">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('flag_rent', tryGetData('flag_rent', $edit_data, 0));?>
				</label>
			</div>
		</div>
		<div class="form-group ">
			<label for="flag_parking" class="col-xs-12 col-sm-2 control-label no-padding-right"><span class='red'>＊</span>是否有停車位</label>
			<div class="col-xs-12 col-sm-8">
				<label class="middle" style="width:100%;">
				<?php echo generate_radio('flag_parking', tryGetData('flag_parking', $edit_data, 0), 'parking_array');?>
				</label>
			</div>
		</div>
		<div class="hr hr-16 hr-dotted"></div>
		<?php
		echo textOption("生活機能", "living", $edit_data);
		echo textOption("附近交通", "traffic", $edit_data);
		echo textAreaOption("<span class='red'>＊</span>特色說明", "desc", $edit_data);
		echo pickDateOption($edit_data);
		echo checkBoxOption("啟　用", "launch", $edit_data);
		?>

	<div class="clearfix form-actions">
        <div class="col-md-offset-1 col-md-9">
        <?php
        if ( tryGetData('is_post', $edit_data,0)!=1 && $mode!='view') {
        ?>
            <button class="btn btn-green" id="Reset" type="reset">
                <i class="icon-ok bigger-110"></i>
                重新填寫
            </button>
			<button class="btn btn-info" id="Submit" type="submit">
				<i class="icon-ok bigger-110"></i>
				確定送出
			</button>
        <?php
        } else {
        ?>
            <button class="btn btn-info" id="back" type="button" onclick="javascript:history.go(-1);">
                <i class="icon-ok bigger-110"></i>
                返回上一頁
            </button>
        <?php
        }
        ?>
		</div>
	</div>

	</div>
</div>
</form>