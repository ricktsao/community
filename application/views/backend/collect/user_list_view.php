
<style type="text/css">
	th, td {text-align:center}
</style>

<div class="page-header">
	<h1>
		住戶磁扣蒐集
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			針對仍未登錄磁扣ID的住戶進行磁扣蒐集作業
		</small>
	</h1>
</div>

<article class="well">

    <div class="btn-group">
		<a class="btn  btn-sm btn-info" target="_blank" href="<?php echo bUrl("exportJson", false);?>">
			<i class="icon-edit bigger-120"></i>住戶資料匯出
		</a>
    </div>

    <div class="btn-group">
		<a class="btn  btn-sm btn-success" href="<?php echo bUrl("implodeJson", false);?>">
			<i class="icon-edit bigger-120"></i>蒐集完成匯入作業
		</a>
    </div>

</article>



		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>序號</th>
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										<th width="80"><?php echo $building_part_03;?></th>
										<th style='text-align: center'>住戶姓名</th>
										<th style='text-align: center'>電話</th>
										<th style='text-align: center'>手機號碼</th>
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
										<td style='text-align: center'><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td style='text-align: center'><?php echo $building_parts[0];?></td>
										<td style='text-align: center'><?php echo $building_parts[1];?></td>
										<td style='text-align: center'><?php echo $building_parts[2];?></td>
										<td>
										<?php echo tryGetData('name', $item);?>
										<?php echo tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
										</td>
										<td>
										<?php echo tryGetData('tel', $item);?>
										</td>
										<td>
										<?php echo tryGetData('phone', $item);?>
										</td>
									</tr>
									<?php
										$i++;
									}
									?>
										
									
								</tbody>
								<tr>
					              	<td colspan="14">
									<?php echo showBackendPager($pager)?>
					                </td>
								</tr>
								
							</table>
							
						</div>
						
					</div>					
				</div>
			</div>
		</div>
