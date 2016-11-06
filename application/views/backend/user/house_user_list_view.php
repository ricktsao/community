
<style type="text/css">
	th, td {text-align:center}
	.invalid {color:#f00}
</style>

<div class="page-header">
	<h1>
		<?php echo $headline;?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			可依需求切換住戶資料列表
		</small>
	</h1>
</div>

<?php
	
		dprint($_GET);
		?>

<form action="" id="update_form" method="post" class="contentForm"> 
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<!--
										<th>序號</th> 
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										-->
										<th width="80"><?php //echo $building_part_03;?>編號</th>
										<th style='text-align: center'>住戶姓名</th>
										<th style='text-align: center'>行動電話</th>
										<th style='text-align: center'>磁　卡</th>
										<th>APP開通</th>
										<th>所有權人</th>
										<th>緊急<br />聯絡人</th>
										<th>管委</th>
										<!-- 
										<th>編輯</th>
										-->
										<th>操作</th>
										<th>啟用/停用</th>
										
									</tr>
								</thead>
								<tbody>
									<?php
									//for($i=0;$i<sizeof($list);$i++) {
									$i = 0;
									foreach ( $list as $item) {
										$building_id = tryGetData('building_id', $item, NULL);
										if ( isNotNull($building_id) ) {
											$building_parts = building_id_to_text($building_id, true);
										}
									?>
									<tr>
										<!-- 
										<td style='text-align: center'><?php //echo ($i+1)+(($this->page-1) * 10);?></td>
										<td style='text-align: center'><?php echo $building_parts[0];?></td>
										<td style='text-align: center'><?php echo $building_parts[1];?></td>
										-->
										<td style='text-align: center'><?php echo $building_parts[2];?></td>
										<td>
										<?php echo tryGetData('name', $item);?>
										<?php echo tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
										</td>
										<td>
										<?php echo tryGetData('phone', $item);?>
										</td>
										<td>
										<?php
										if (isNotNull(tryGetData('id', $item, NULL)) ) {
											// echo mask($item['id'] , 2, 4);
											echo '✔';
										} else {
											echo '尚未開通';
										}
										?>
										</td>
										<td>
										<?php
										if (isNotNull(tryGetData("app_id", $item, NULL))) {
											//echo '******'.mb_substr(tryGetData("app_id", $item), 6);
											echo '✔';
										?>
										
											
										<?php
										} else {

											if (isNotNull(tryGetData("act_code", $item, NULL))) {
												echo '待開通';
											} else {
												echo '尚未開通';
											}
										}
										?>
										</td>
										<td>
										<?php
										if (tryGetData("is_owner", $item) == 1) {
											echo '✔';
										} //else echo '否';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("is_contact", $item) == 1) {
											echo '✔';
										} //else echo '否';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("is_manager", $item) == 1) {
											$manager_title =  tryGetData("manager_title", $item);
											echo tryGetData($manager_title, $manager_title_array);
										} //else echo '否';
										?>
										</td>
										<td style='text-align: center'>

											<a class="btn  btn-minier btn-success" href="<?php echo bUrl("editUser",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>修改
											</a>

										<?php
										if (isNotNull(tryGetData('id', $item, NULL)) ){
										?>
											<a class="btn btn-minier btn-info" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>" >
												<i class="icon-edit bigger-120"></i>變更磁卡
											</a>
											<a class="btn  btn-minier btn-purple" href="<?php echo bUrl("setParking",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>設定車位
											</a>
										<?php
										} else {
										?>
											<a class="btn btn-minier btn-primary" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>" >
												<i class="icon-edit bigger-120"></i>設定磁卡
											</a>
											<a class="btn btn-minier btn-gray" href="#" onclick='return alert("請先設定住戶磁卡")'>
												<i class="icon-edit bigger-120"></i>設定車位
											</a>
										<?php
										}

										$building_id = tryGetData('building_id', $item);
										$tmp_array = array("sn" => tryGetData('sn', $item)
														,  "id" => tryGetData('id', $item)
														,  "b_id" => base64_encode($building_id)
														,  "n" => tryGetData('name', $item)
														,  "g" => tryGetData('gender', $item)
														,  "role"=>tryGetData('role', $item)
														) ;
										if ( isNotNull(tryGetData('app_id', $item , NULL)) || isNotNull(tryGetData('act_code', $item , NULL))) {
										?>
											<a class="btn btn-minier btn-pink" href="<?php echo bUrl("resetActCode",TRUE,NULL, $tmp_array); ?>" onclick='return confirm("重設APP開通碼，必須請住戶重新執行APP啟用程序，\n\n請再次確認是否重設??")'>
												<i class="icon-edit bigger-120"></i>重設APP開通碼
											</a>
										<?php
										} else {

											if (isNotNull(tryGetData('id', $item, NULL)) ){
											?>
												<a class="btn btn-minier btn-pink" href="<?php echo bUrl("resetActCode", TRUE, NULL, $tmp_array); ?>" onclick='return confirm("設定APP開通碼，須請住戶執行APP啟用程序，\n\n請確認??")'>
													<i class="icon-edit bigger-120"></i>設定APP開通碼
												</a>
											<?php
											} else {
											?>
												<a class="btn btn-minier btn-gray" href="#" onclick='return alert("請先設定住戶磁卡")'>
													<i class="icon-edit bigger-120"></i>設定APP開通碼
												</a>
											<?php
											}
										}
										?>




										</td>
										<td style='text-align: center'>
											<?php echo tryGetData('launch', $item)==1?"啟用":"<span class='invalid'>停用</span>" ?>
										</td>
										
									</tr>
									<?php
										$i++;
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	

</form>        

<script type="text/javascript"> 

	
	function launch(obj) 
	{		
	
	 $.ajax({ 
            type : "POST",
            data: {'user_sn' : obj.value  },
            url: "<?php echo bUrl("launchUser");?>",
            timeout: 3000 ,
            error: function( xhr ) 
            {
                //不處理
            },
            success : function(result) 
            {
            	if(result == 1)
            	{
            		$(obj).prop("checked", true);	
            	}
            	else
            	{
            		$(obj).prop("checked", false);
            	}
           		     
            }
        });	 
	}
</script>


