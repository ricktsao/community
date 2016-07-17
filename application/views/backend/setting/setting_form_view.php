<style type="text/css">
	.note {color: #808040; font-size:12px}
</style>

<form action="<?php echo bUrl("updateSetting")?>" method="post"  id="update_form" class="form-horizontal" role="form">
	<?php 
	// 5.	 [片語管理] ，<管委職稱>、<郵件類型>、<公告輪播停留秒數>設定為可以修改增。其餘的第一次設定後不得修改。
	$users_limit_array = array('building_part_01', 'building_part_02', 'building_part_03', 'building_part_01_value', 'building_part_02_value'); //, 'manager_title', 'mail_box_type'
	$parking_limit_array = array('parking_part_01', 'parking_part_02', 'parking_part_03', 'parking_part_01_value', 'parking_part_02_value');
		
		foreach ($setting_list as $key => $item) 
		{
			if ($item["key"] == 'mail_box_type' || $item["key"] == 'parking_part_01' || $item["key"] == 'addr_part_01') {
				echo '<div class="hr hr-16 hr-dotted"></div>';
			}

			switch($item["type"])
			{
				
				case 'text' :
					echo
					'<div class="form-group ">
						<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="'.$item["key"].'">'.$item["title"].'</label>
						<div class="col-xs-12 col-sm-4">';

					if ( in_array($item["key"], $users_limit_array) && $users_flag===true) {
						echo $item["value"];

					} elseif ( in_array($item["key"], $parking_limit_array ) && $parking_flag===true) {
						echo $item["value"];

					} else { 

						echo '		<input type="text" id="'.$item["key"].'" name="'.$item["key"].'"  class="width-100" value="'.$item["value"].'"  />';
					}
					echo
					'</div>			
						<span class="note">'.$item["memo"].'</span>		
					</div>';
					break;		
								
				case 'textarea' :					
					echo 
					
					'<div class="form-group">
						<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="'.$item["key"].'">'.$item["title"].'</label>
						<div class="col-xs-12 col-sm-6" >
							<textarea id="'.$item["key"].'" name="'.$item["key"].'" class="autosize-transition form-control" style="height:250px">'.$item["value"].'</textarea>
						'.$item["memo"].'			
						</div>						
					</div>';
					break;
			}
		}
	
	
	?>
	
	
	
		<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				Submit
			</button>
			
		</div>
	</div>

</form>        