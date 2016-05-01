
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

<form  role="search" action="<?php echo bUrl('index');?>">
<article class="well">
    <div class="btn-group">
	<?php
	echo $parking_part_01 .'：';
	echo form_dropdown('p_part_01', $parking_part_01_array, $p_part_01);
	echo '&nbsp;&nbsp;';
	echo $parking_part_02 .'：';
	echo form_dropdown('p_part_02', $parking_part_02_array, $p_part_02);
	echo '&nbsp;&nbsp;';
	echo $parking_part_03 .'：';
	echo '<input type="text" name="p_part_03" value="'.$p_part_03.'" size="1">';
	?>
    </div>
	

    <div class="btn-group">
		<button type="submit" class="btn btn-primary btn-sm btn_margin"><i class="icon-search nav-search-icon"></i>搜尋</button>
    </div>
</article>	

</form>

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
										<th>車位別</th>
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
										<td>
										<?php 
										$parking_id = tryGetData('parking_id', $item, NULL);
										if ( isNotNull($parking_id) ) {
											echo parking_id_to_text($parking_id);
										}
										?>
										</td>
										<!-- <td>
										<?php echo tryGetData('location', $item);?>
										</td> -->
										<td>
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