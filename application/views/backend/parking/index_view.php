
<style type="text/css">
	th, td {text-align:center}
</style>
<div class="page-header">
	<h1>
		車位資料
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			<!-- 請務必依據【住戶資料】的內容格式 -->
		</small>
	</h1>
</div>


<form action="" id="update_form" method="post" class="contentForm"> 
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>序號</th>
										<th>車位ＩＤ</th>
										<!-- <th>位置</th> -->
										<th>戶別</th>
										<th>住戶姓名</th>
										<th>電話</th>
										<th>行動</th>
										<th>車號</th>
										<!-- <th>所有權人</th>
										<th style="width:150px">操作</th> -->
										
									</tr>
								</thead>
								<tbody>
									<?php
									//for($i=0;$i<sizeof($list);$i++) {
									$i = 0;
									foreach ( $user_parking_list as $item) {
									?>
									<tr>
										<td style='text-align: center'><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td style='text-align: center'><?php echo tryGetData('parking_id', $item, '-');?></td>
										<!-- <td>
										<?php echo tryGetData('location', $item);?>
										</td> -->
										<td>
										<?php echo tryGetData('building_id', $item);?>
										</td>
										<td>
										<?php echo tryGetData('name', $item);?>
										</td>
										<td>
										<?php echo tryGetData('tel', $item);?>
										</td>
										<td>
										<?php echo tryGetData('phone', $item);?>
										</td>
										<td>
										<?php echo tryGetData('car_number', $item);?>
										</td>
										<!-- <td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editAdmin",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
											<?php
											if ( tryGetData('role', $item, NULL) == 'I' ) {
											?>
											<a class="btn  btn-minier btn-purple" href="<?php echo bUrl("setParking",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>車位設定
											</a>
											<?php
											}
											?>
										</td>
										<td>					
											<div class="col-xs-3">
												<label>
													<input name="switch-field-1" class="ace ace-switch" type="checkbox"  <?php echo tryGetData('launch', $item)==1?"checked":"" ?> value="<?php echo tryGetData('sn', $item) ?>" onClick='javascript:launch(this);' />
													<span class="lbl"></span>
												</label>
											</div>
										</td> -->
										
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