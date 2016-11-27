<style type="text/css">
/*	th, td {text-align:center}*/
	#add-1 td label {text-align:center; font-size:12px}
	.invalid {color:#f00}
	.required ,.error {color:#f00; font-size:14px; font-style: italic; }
</style>

<div class="page-header">
	<h1>
		<?php echo $house_text.'　　地址：'.$addr_text; ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			<?php echo $headline;?>
		</small>
	</h1>
</div>
<article class="well">
	<div class="btn-group">
		<a class="btn  btn-sm btn-success" href="<?php echo bUrl('houseList');?>">
			<i class="icon-edit bigger-120"></i>返回
		</a>
    </div>
</article>
	
		<?php
		// 共用 ??~~~~
		if (validation_errors() != false) {
			echo "<div class='error'>" . $msg . "</div>" ;
			echo "<div class='error'>" . validation_errors() . "</div>" ;
		}
		?>
		<!-- = = = = = = = = = = = = = = PART 1 begin = = = = = = = = = = = = = = = = = = =  -->
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							<?php
							echo '<h3>一般住戶列表</h3>';
							?>
							<!-- 新增住戶 -->
							<?php
							echo form_open(bUrl('editHouseUser'));
							echo form_hidden("comm_id", tryGetData('comm_id', $fm1));
							echo form_hidden('role', 'I');
							echo form_hidden('living_here', 1);
							echo form_hidden('b_part_01', tryGetData('b_part_01', $fm1, NULL));
							echo form_hidden('b_part_02', tryGetData('b_part_02', $fm1, NULL));
							echo form_hidden('addr_part_01', tryGetData('addr_part_01', $fm1, NULL));
							echo form_hidden('addr_part_02', tryGetData('addr_part_02', $fm1, NULL));
							echo form_hidden('start_date', tryGetData('start_date', $fm1, NULL));
							echo form_hidden('end_date', tryGetData('end_date', $fm1, NULL));
							echo form_hidden('forever', tryGetData('forever', $fm1, NULL));
							echo form_hidden('launch', tryGetData('launch', $fm1, NULL));
							?>
							<table id="add-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<!--
										<th>序號</th> 
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										-->
										<th style='text-align: center'>住戶姓名</th>
										<th style='text-align: center'>電話</th>
										<th style='text-align: center'>行動電話</th>
										<th>性別</th>
										<th>所有權人</th>
										<th>緊急<br />聯絡人</th>
										<th>投票<br />權限</th>
										<th>瓦斯<br />權限</th>
										<th>管委</th>
										<th>管委職稱</th>
										<th>意見箱<br />權限</th>
										<th>租屋</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style='text-align: center; width: 150px;'><span class='required'>＊</span><?php echo form_input('name',tryGetData('name', $fm1, ''), 'class="width-80"');?></td>
										<td style='text-align: center; width: 150px;'><span class='required'>＊</span><?php echo form_input('tel',tryGetData('tel', $fm1, ''), 'class="width-80"');?></td>
										<td style='text-align: center; width: 150px;'><span class='required'>＊</span><?php echo form_input('phone',tryGetData('phone', $fm1, ''), 'id="phone]" class="width-80"');?></td>
										<!-- <td style='text-align: center'><?php //echo form_input('id',tryGetData('id', $fm1, ''), 'class="width-100"');?></td> -->
										<td style='width: 70px'><?php echo gender_radio('gender', (int) tryGetData('gender', $fm1, 1));?></td>
										<td style='width: 60px'><?php echo form_checkbox('is_owner',1 , tryGetData('is_owner', $fm1, 0));?></td>
										<td style='width: 80px'><?php echo form_checkbox('is_contact',1 , tryGetData('is_contact', $fm1, 0));?></td>
										<td style='width: 60px'><?php echo form_checkbox('voting_right',1 , tryGetData('voting_right', $fm1, 0));?></td>
										<td style='width: 60px'><?php echo form_checkbox('gas_right',1 , tryGetData('gas_right', $fm1, 0));?></td>
										<td style='width: 60px'><?php echo form_checkbox('is_manager',1 , tryGetData('is_manager', $fm1, 0));?></td>
										<td>
										<?php
										$js = 'id="manager_title"';
										echo form_dropdown('manager_title', $manager_title_array, tryGetData('manager_title', $fm1), $js);
										?>
										</td>
										<td style='width: 80px'><?php echo form_checkbox('suggest_flag',1 , tryGetData('suggest_flag', $fm1, 0));?></td>
										<td style='width: 60px'><?php echo form_checkbox('tenant_flag',1, tryGetData('tenant_flag', $fm1, 0)); ?></td>
										<td><?php echo form_submit('submit1', '新增');?></td>
									</tr>
								</tbody>
							</table>
							<?php echo form_close();?>
							<!-- 住戶列表 -->
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<!--
										<th>序號</th> 
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										<th width="30"><?php //echo $building_part_03;?>編號</th>
										-->
										<th style='text-align: center'>住戶姓名</th>
										<th style='text-align: center'>行動電話</th>
										<th style='text-align: center'>電　話</th>
										<th style='text-align: center'>磁　卡</th>
										<th>APP開通</th>
										<th>所有權人</th>
										<th>緊急<br />聯絡人</th>
										<th>投票</th>
										<th>瓦斯</th>
										<th>管委</th>
										<th>意見箱<br />權限</th>
										<th>租屋</th>
										<th width="200">操作</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 0;
									foreach ( $list as $item) {
										if ($item['living_here'] !== '1') {
											continue;
										}
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
										<td style='text-align: center'><?php echo $building_parts[2];?></td>
										-->
										<td>
										<?php echo tryGetData('name', $item);?>
										<?php echo '<br>'.tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
										<?php echo $item['launch']==0? "&nbsp;<span class='invalid'>(停用)</span>":"";?>
										</td>
										<td><?php echo tryGetData('tel', $item);?></td>
										<td><?php echo tryGetData('phone', $item);?></td>
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
										if (tryGetData("voting_right", $item) == 1) {
											echo '✔';
										} //else echo '否';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("gas_right", $item) == 1) {
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
										<td>
										<?php
										if (tryGetData("suggest_flag", $item) == 1) {
											echo '✔';
										} //else echo '否';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("tenant_flag", $item) == 1) {
											echo '✔';
										} //else echo '否';
										?>
										</td>
										<td style='text-align: left'>
											<a class="btn  btn-minier btn-success" href="<?php echo bUrl("editUser",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>修改
											</a>
											<a class="btn  btn-minier btn-danger" onclick="return confirm('您確定刪除此位住戶資料？')" href="<?php echo bUrl("deleteUser",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item), "name"=>tryGetData('name', $item))); ?>">
												<i class="icon-edit bigger-120"></i>刪除
											</a>
										<?php
										if (tryGetData('launch', $item)==1) {
											if (isNotNull(tryGetData('id', $item, NULL)) ){
											?>
												<a class="btn btn-minier btn-info" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>" >
													<i class="icon-edit bigger-120"></i>變更磁卡
												</a><br>
												<a class="btn  btn-minier btn-purple" href="<?php echo bUrl("setParking",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
													<i class="icon-edit bigger-120"></i>設定車位
												</a>
											<?php
											} else {
											?>
												<a class="btn btn-minier btn-primary" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>" >
													<i class="icon-edit bigger-120"></i>設定磁卡
												</a><br>
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
										} else {
										//	echo "&nbsp;<span class='invalid'>(停用)</span>";
										?>
												<a class="btn btn-minier btn-gray" href="#" onclick='return alert("此住戶帳號為`停用`狀態，無法處理")'>
													<i class="icon-edit bigger-120"></i>設定磁卡
												</a>
												<a class="btn btn-minier btn-gray" href="#" onclick='return alert("此住戶帳號為`停用`狀態，無法處理")'>
													<i class="icon-edit bigger-120"></i>設定車位
												</a>
												<a class="btn btn-minier btn-gray" href="#" onclick='return alert("此住戶帳號為`停用`狀態，無法處理")'>
													<i class="icon-edit bigger-120"></i>設定APP開通碼
												</a>
										<?php
										}
										?>
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
		<!-- = = = = = = = = = = = = = = PART 1 end = = = = = = = = = = = = = = = = = = = =  -->
		<div class="hr hr-16 hr-dotted"></div>
		<!-- = = = = = = = = = = = = = = PART 2 begin = = = = = = = = = = = = = = = = = = =  -->
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							<?php
							//echo '<h3>'.$house_text.'　　地址：'.$addr_text.'</h3>';
							echo '<h3>非住戶所有權人</h3>';
							?>
							<!-- 新增住戶 -->
							<?php
							echo form_open(bUrl('editHouseUser'));
							echo form_hidden("comm_id", tryGetData('comm_id', $fm2));
							echo form_hidden('role', 'I');
							echo form_hidden('is_owner', 1);
							echo form_hidden('living_here', 0);
							echo form_hidden('b_part_01', tryGetData('b_part_01', $fm2, NULL));
							echo form_hidden('b_part_02', tryGetData('b_part_02', $fm2, NULL));
							echo form_hidden('addr_part_01', tryGetData('addr_part_01', $fm2, NULL));
							echo form_hidden('addr_part_02', tryGetData('addr_part_02', $fm2, NULL));
							echo form_hidden('start_date', tryGetData('start_date', $fm2, NULL));
							echo form_hidden('end_date', tryGetData('end_date', $fm2, NULL));
							echo form_hidden('forever', tryGetData('forever', $fm2, NULL));
							echo form_hidden('launch', tryGetData('launch', $fm2, NULL));
							?>
							<table id="add-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<!--
										<th>序號</th> 
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										-->
										<th style='text-align: center'>住戶姓名</th>
										<th style='text-align: center'>電話</th>
										<th style='text-align: center'>行動電話</th>
										<th>性別</th>
										<th>地址</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<!-- <td style='text-align: center'><?php //echo form_input('id',tryGetData('id', $fm2, ''), 'class="width-100"');?></td> -->
										<td style='text-align: center; width: 150px;'><span class='required'>＊</span><?php echo form_input('name',tryGetData('name', $fm2, ''), 'class="width-80"');?></td>
										<td style='text-align: center; width: 150px;'><span class='required'>＊</span><?php echo form_input('tel',tryGetData('tel', $fm2, ''), 'class="width-80"');?></td>
										<td style='text-align: center; width: 150px;'><span class='required'>＊</span><?php echo form_input('phone',tryGetData('phone', $fm2, ''), 'id="phone]" class="width-80"');?></td>
										<td style='width: 70px'><?php echo gender_radio('gender', (int) tryGetData('gender', $fm2, 1));?></td>
										<td><span class='required'>＊</span><?php echo form_input('owner_addr',tryGetData('owner_addr', $fm2, ''), 'style="width:450px"');?></td>
										<td><?php echo form_submit('submit2', '新增');?></td>
									</tr>
								</tbody>
							</table>
							<?php echo form_close();?>
							<!-- 住戶列表 -->
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<!--
										<th>序號</th> 
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										<th width="30"><?php //echo $building_part_03;?>編號</th>
										-->
										<th style='text-align: center'>住戶姓名</th>
										<th style='text-align: center'>行動電話</th>
										<th style='text-align: center'>電話</th>
										<th>地址</th>
										<!-- 
										<th style='text-align: center'>磁　卡</th>
										<th>APP開通</th>
										-->
										<th width="200">操作</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 0;
									foreach ( $list as $item) {
										if ($item['living_here']!=='0' OR $item['is_owner']!=='1') {
											continue;
										}
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
										<td style='text-align: center'><?php echo $building_parts[2];?></td>
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
										-->
										<td>
										<?php echo tryGetData('name', $item);?>
										<?php echo '&nbsp;'.tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
										<?php echo $item['launch']==0? "&nbsp;<span class='invalid'>(停用)</span>":"";?>
										</td>
										<td><?php echo tryGetData('tel', $item);?></td>
										<td><?php echo tryGetData('phone', $item);?></td>
										<td><?php echo tryGetData('owner_addr', $item);?></td>
										<td style='text-align: left'>
											<a class="btn  btn-minier btn-success" href="<?php echo bUrl("editUser",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>修改
											</a>
											<a class="btn  btn-minier btn-danger" onclick="return confirm('您確定刪除此位住戶資料？')" href="<?php echo bUrl("deleteUser",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item), "name"=>tryGetData('name', $item))); ?>">
												<i class="icon-edit bigger-120"></i>刪除
											</a>
										<?php
										/*
										if (tryGetData('launch', $item)==1) {
											if (isNotNull(tryGetData('id', $item, NULL)) ){
											?>
												<a class="btn btn-minier btn-info" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>" >
													<i class="icon-edit bigger-120"></i>變更磁卡
												</a><br>
												<a class="btn  btn-minier btn-purple" href="<?php echo bUrl("setParking",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
													<i class="icon-edit bigger-120"></i>設定車位
												</a>
											<?php
											} else {
											?>
												<a class="btn btn-minier btn-primary" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>" >
													<i class="icon-edit bigger-120"></i>設定磁卡
												</a><br>
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
										} else {
										//	echo "&nbsp;<span class='invalid'>(停用)</span>";
										?>
												<a class="btn btn-minier btn-gray" href="#" onclick='return alert("此住戶帳號為`停用`狀態，無法處理")'>
													<i class="icon-edit bigger-120"></i>設定磁卡
												</a>
												<a class="btn btn-minier btn-gray" href="#" onclick='return alert("此住戶帳號為`停用`狀態，無法處理")'>
													<i class="icon-edit bigger-120"></i>設定車位
												</a>
												<a class="btn btn-minier btn-gray" href="#" onclick='return alert("此住戶帳號為`停用`狀態，無法處理")'>
													<i class="icon-edit bigger-120"></i>設定APP開通碼
												</a>
										<?php
										}
										*/
										?>
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
		<!-- = = = = = = = = = = = = = = PART 2 end = = = = = = = = = = = = = = = = = = = =  -->
		<div class="hr hr-16 hr-dotted"></div>
		<!-- = = = = = = = = = = = = = = PART 3 begin = = = = = = = = = = = = = = = = = = =  -->
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							<?php
							//echo '<h3>'.$house_text.'　　地址：'.$addr_text.'</h3>';
							echo '<h3>非住戶緊急聯絡人</h3>';
							?>
							<!-- 新增住戶 -->
							<?php
							echo form_open(bUrl('editHouseUser'));
							echo form_hidden("comm_id", tryGetData('comm_id', $fm3));
							echo form_hidden('role', 'I');
							echo form_hidden('is_contact', 1);
							echo form_hidden('living_here', 0);
							echo form_hidden('b_part_01', tryGetData('b_part_01', $fm3, NULL));
							echo form_hidden('b_part_02', tryGetData('b_part_02', $fm3, NULL));
							echo form_hidden('addr_part_01', tryGetData('addr_part_01', $fm3, NULL));
							echo form_hidden('addr_part_02', tryGetData('addr_part_02', $fm3, NULL));
							echo form_hidden('start_date', tryGetData('start_date', $fm3, NULL));
							echo form_hidden('end_date', tryGetData('end_date', $fm3, NULL));
							echo form_hidden('forever', tryGetData('forever', $fm3, NULL));
							echo form_hidden('launch', tryGetData('launch', $fm3, NULL));
							?>
							<table id="add-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<!--
										<th>序號</th> 
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										<th>所有權人</th>
										<th>緊急<br />聯絡人</th>
										<th>投票<br />權限</th>
										<th>瓦斯<br />權限</th>
										<th>管委</th>
										<th>管委職稱</th>
										<th>意見箱<br />權限</th>
										<th>租屋</th>
										-->
										<th style='text-align: center'>住戶姓名</th>
										<th style='text-align: center'>電話</th>
										<th style='text-align: center'>行動電話</th>
										<th style='text-align: center'>性別</th>
										<th>地址</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<tr><!--
										<td style='text-align: center'><?php //echo form_input('id',tryGetData('id', $fm3, ''), 'class="width-100"');?></td>
										<td style='width: 60px'><?php echo form_checkbox('is_owner',1 , tryGetData('is_owner', $fm3, 0));?></td>
										<td style='width: 80px'><?php echo form_checkbox('is_contact',1 , tryGetData('is_contact', $fm3, 0));?></td>
										<td style='width: 60px'><?php echo form_checkbox('voting_right',1 , tryGetData('voting_right', $fm3, 0));?></td>
										<td style='width: 60px'><?php echo form_checkbox('gas_right',1 , tryGetData('gas_right', $fm3, 0));?></td>
										<td style='width: 60px'><?php echo form_checkbox('is_manager',1 , tryGetData('is_manager', $fm3, 0));?></td>
										<td>
										<?php
										$js = 'id="manager_title"';
										echo form_dropdown('manager_title', $manager_title_array, tryGetData('manager_title', $fm3), $js);
										?>
										</td>
										<td style='width: 80px'><?php echo form_checkbox('suggest_flag',1 , tryGetData('suggest_flag', $fm3, 0));?></td>
										<td style='width: 60px'><?php echo form_checkbox('tenant_flag',1, tryGetData('tenant_flag', $fm3, 0)); ?></td>
										-->
										<td style='text-align: center; width: 150px;'><span class='required'>＊</span><?php echo form_input('name',tryGetData('name', $fm3, ''), 'class="width-80"');?></td>
										<td style='text-align: center; width: 150px;'><span class='required'>＊</span><?php echo form_input('tel',tryGetData('tel', $fm3, ''), 'class="width-80"');?></td>
										<td style='text-align: center; width: 150px;'><span class='required'>＊</span><?php echo form_input('phone',tryGetData('phone', $fm3, ''), 'id="phone]" class="width-80"');?></td>
										<td style='width: 70px'><?php echo gender_radio('gender', (int) tryGetData('gender', $fm3, 1));?></td>
										<td><span class='required'>＊</span><?php echo form_input('owner_addr',tryGetData('owner_addr', $fm3, ''), 'style="width:450px"');?></td>
										<td><?php echo form_submit('submit3', '新增');?></td>
									</tr>
								</tbody>
							</table>
							<?php echo form_close();?>
							<!-- 住戶列表 -->
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<!--
										<th>序號</th> 
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										<th width="30"><?php //echo $building_part_03;?>編號</th>
										<th>APP開通</th>
										<th style='text-align: center'>磁　卡</th>
										<th>所有權人</th>
										<th>緊急<br />聯絡人</th>
										<th>投票</th>
										<th>瓦斯</th>
										<th>管委</th>
										<th>意見箱<br />權限</th>
										<th>租屋</th>
										-->
										<th style='text-align: center'>住戶姓名</th>
										<th style='text-align: center'>行動電話</th>
										<th style='text-align: center'>電話</th>
										<th >地址</th>
										<th width="200">操作</th>
									</tr>
								</thead>
								<tbody>
									<?php
									//for($i=0;$i<sizeof($list);$i++) {
									$i = 0;
									foreach ( $list as $item) {
										if ($item['living_here']!=='0' OR $item['is_contact']!=='1') {
											continue;
										}
										$building_id = tryGetData('building_id', $item, NULL);
										if ( isNotNull($building_id) ) {
											$building_parts = building_id_to_text($building_id, true);
										}
										//dprint($item['launch']);
									?>
									<tr>
										<!-- 
										<td style='text-align: center'><?php //echo ($i+1)+(($this->page-1) * 10);?></td>
										<td style='text-align: center'><?php echo $building_parts[0];?></td>
										<td style='text-align: center'><?php echo $building_parts[1];?></td>
										<td style='text-align: center'><?php echo $building_parts[2];?></td>
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
										if (tryGetData("voting_right", $item) == 1) {
											echo '✔';
										} //else echo '否';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("gas_right", $item) == 1) {
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
										<td>
										<?php
										if (tryGetData("suggest_flag", $item) == 1) {
											echo '✔';
										} //else echo '否';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("tenant_flag", $item) == 1) {
											echo '✔';
										} //else echo '否';
										?>
										</td>
										-->
										<td>
										<?php echo tryGetData('name', $item);?>
										<?php echo '&nbsp;'.tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
										<?php echo $item['launch']==0? "&nbsp;<span class='invalid'>(停用)</span>":"";?>
										</td>
										<td><?php echo tryGetData('tel', $item);?></td>
										<td><?php echo tryGetData('phone', $item);?></td>
										<td><?php echo tryGetData('owner_addr', $item);?></td>
										<td style='text-align: left'>
											<a class="btn  btn-minier btn-success" href="<?php echo bUrl("editUser",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>修改
											</a>
											<a class="btn  btn-minier btn-danger" onclick="return confirm('您確定刪除此位住戶資料？')" href="<?php echo bUrl("deleteUser",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item), "name"=>tryGetData('name', $item))); ?>">
												<i class="icon-edit bigger-120"></i>刪除
											</a>
										<?php
										/*
										if (tryGetData('launch', $item)==1) {
											if (isNotNull(tryGetData('id', $item, NULL)) ){
											?>
												<a class="btn btn-minier btn-info" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>" >
													<i class="icon-edit bigger-120"></i>變更磁卡
												</a><br>
												<a class="btn  btn-minier btn-purple" href="<?php echo bUrl("setParking",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
													<i class="icon-edit bigger-120"></i>設定車位
												</a>
											<?php
											} else {
											?>
												<a class="btn btn-minier btn-primary" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>" >
													<i class="icon-edit bigger-120"></i>設定磁卡
												</a><br>
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
										} else {
										//	echo "&nbsp;<span class='invalid'>(停用)</span>";
										?>
												<a class="btn btn-minier btn-gray" href="#" onclick='return alert("此住戶帳號為`停用`狀態，無法處理")'>
													<i class="icon-edit bigger-120"></i>設定磁卡
												</a>
												<a class="btn btn-minier btn-gray" href="#" onclick='return alert("此住戶帳號為`停用`狀態，無法處理")'>
													<i class="icon-edit bigger-120"></i>設定車位
												</a>
												<a class="btn btn-minier btn-gray" href="#" onclick='return alert("此住戶帳號為`停用`狀態，無法處理")'>
													<i class="icon-edit bigger-120"></i>設定APP開通碼
												</a>
										<?php
										}
										*/
										?>
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
		<!-- = = = = = = = = = = = = = = PART 3 end = = = = = = = = = = = = = = = = = = = =  -->
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


