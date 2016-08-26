<div class="primary">
	<!--
	<div><a href="<?php echo fUrl("index")?>">郵件登錄</a></div>
	<div><a href="<?php echo fUrl("user_keycode")?>">郵件領取</a></div>
	<div><a href="<?php echo fUrl("log")?>" >郵件物品記錄</a></div>
	-->
	<div class="form_group"><div class="form_page_title">郵件登錄</div></div>
	
	<form  role="search" class="searchArea" >
		<table>
			<tr class="borderLine">
				<td>
					<?php 
					echo $building_part_01 .'：';
					echo form_dropdown('b_part_01', $building_part_01_array, $b_part_01);
					?>
				</td>
				<td>
					<?php 
					echo $building_part_02 .'：';
					echo form_dropdown('b_part_02', $building_part_02_array, $b_part_02);
					?>
				</td>
			</tr>
			<tr class="borderLine">
				<td colspan="2">
						關鍵字：<input class="input_style inline" type='text' name='keyword' value='<?php echo $given_keyword;?>'>
				</td>
			</tr>	
				<tr>
				<td colspan="2">
					<button type="submit" class="btn btn-primary btn-sm btn_margin"><i class="icon-search nav-search-icon"></i>搜尋</button>
				</td>
			</tr>

		</table>	
	</form>
	
	<table class="table_style">
		<thead>
			
				<tr>
					<td>操作</td>
					<td>戶　別</td>
					<td style='text-align: center'>姓　名</td>
					<td>性　別</td>							
				</tr>				
			
		</thead>
		<tbody>
		<?php foreach ( $list as $item) { ?>
		<tr>
			<td style='text-align: center'>
				<a class="btn  btn-minier btn-info" style="color:#FFF" href="<?php echo fUrl("regMail",TRUE,NULL,array("user_sn"=>tryGetData('sn', $item))); ?>">
				選擇
				</a>
			</td>
			<td style='text-align: center'>
				<?php 
				$building_id = tryGetData('building_id', $item, NULL);
				if ( isNotNull($building_id) ) {
					echo building_id_to_text($building_id);
				}
				?>
			</td>
			<td>
				<?php echo tryGetData('name', $item);?>
			</td>
			<td style='text-align: center'>
				<?php echo tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
			</td>
			
		</tr>
		<?php }	?>       
		</tbody>            
	</table>
	<?php echo showFrontendPager($pager)?>
</div>