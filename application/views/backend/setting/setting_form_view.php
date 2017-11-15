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
				送出
			</button>
			
		</div>
	</div>	
	</form>   
	
	<BR>
	
	<div class="page-header">
		<h1>
			社區圖片設定
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>			
			</small>
		</h1>
	</div>

<div class="row">
	<div class="col-xs-12 form-horizontal">
	
	<form action="<?php echo bUrl("uploadPhoto")?>" method="post"  id="add_form" name="add_form" role="form" enctype="multipart/form-data">			
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">新增圖片：</label>
			<div class="col-xs-12 col-sm-6"><input type='file' id='filename' name='img_filename' size=20><span class="note">只允許上傳jpg,png,gif 格式圖檔</span></div>				
		</div>
		<div class="form-group" style="display:none">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">說明：</label>
			<div class="col-xs-12 col-sm-6"><input type='text' id='title' name='title' size=50></div>
		</div>

		<div class="form-group">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url"></label>
			<div class="col-xs-12 col-sm-6">
			<button class="btn" type="button" id="search-reset" >
					<i class="icon-warning bigger-110"></i>
					重設
			</button>
			<button class="btn btn-success" type="button" onclick="checkFile();">
					<i class="icon-ok bigger-110"></i>
					確定新增
			</button>
		</div>
		</div>
	</form>

	
	<div class="form-group">
		<div class="table-responsive">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="id">圖片：</label>
			<div class="col-xs-12 col-sm-8">
				<!-- <div style="float:right;" id="click_add_cust">
					<button class="btn btn-success">新增圖片</button>
				</div> -->
				<form method="post"  id="photo_form" role="form" action="<?php echo bUrl('deletePhoto');?>" >				
				<table id="sample-table-2" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>										
							<th class="center" style="width:80px">
								<label>
									<input id="checkDelAll_custs" type="checkbox" class="ace"  />
									<span class="lbl"></span>
								</label>
							</th>
							<th>圖片</th>									
							<th>上傳日期</th>
							<th>上傳者</th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (sizeof($photo_list) < 1) 
					{
						echo '<tr><td colspan="4"><span class="note">查無任何圖片，請由【新增圖片】功能上傳圖片</span></td></tr>';
					} 
					else 
					{							
						foreach ($photo_list as $key=>$photo) 
						{
							$sn = tryGetData('sn', $photo, NULL);							
							$img_filename = tryGetData('img_filename', $photo, NULL);

							if ( isNull($img_filename) ) continue;

							$url = base_url('upload/website/setting/'.$img_filename);
						?>
						<tr>
							<td class="center">
								<?php
								//if ( sizeof($exist_lands_array) < 1 && sizeof($exists_custs_array) > 0) {
								?>
								<label>
									<input type="checkbox" class="ace" name="del[]" value="<?php echo $sn.'!@'.$img_filename;?>" />
									<span class="lbl"></span>
								</label>
							</td>
							<td><?php echo '<a href="'.$url.'" title="檢視大圖" target=_blank><img border="0" width="150" src="'.$url.'?"></a>'; ?></td>
							
							
							<td><?php echo tryGetData('updated', $photo, '-');?></td>
							<td><?php echo tryGetData('updated_by', $photo, '-');?></td>
						</tr>
						<?php
						}
						?>
					</tbody>
					<?php
					}
					?>
						<tfoot>
							<tr>
								<td class="center">
									<button class="btn  btn-minier btn-inverse" type="button" onclick="checkSubmit();">
										<i class="icon-trash bigger-120"></i>刪除
									</button>
								</td>
								<td colspan="7"></td>
							</tr>
						</tfoot>
				</table>
				</form>
			</div>
		</div>
	</div>
	
	
	
	</div>
</div>

<script type="text/javascript"> 
function checkFile() { 
	var f = document.add_form; 
	var re = /\.(jpg|gif|png)$/i;  //允許的圖片副檔名 
	if (!re.test(f.img_filename.value)) { 
		alert("只允許上傳jpg,png,gif圖檔"); 
	}
	else
	{
		f.submit();
	}
} 

function checkSubmit()
{
	result = confirm('是否確定刪除?')
	if(result)
	{
		$('#photo_form').submit();
	}
	
}


</script>
