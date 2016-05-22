
<style type="text/css">
	th, td {text-align:center}
</style>

<div class="page-header">
	<h1>
		���Ϧ��`��
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div>

<article class="well">

    <div class="btn-group">
		<a class="btn  btn-sm btn-info" target="_blank" href="<?php echo bUrl("exportExcel", false);?>">
			<i class="icon-edit bigger-120"></i>����ƶץX
		</a>
    </div>

    <div class="btn-group">
		<a class="btn  btn-sm btn-info" target="_blank" href="<?php echo bUrl("exportJson", false);?>">
			<i class="icon-edit bigger-120"></i>���ץX
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
										<th>�Ǹ�</th>
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										<th width="80"><?php echo $building_part_03;?></th>
										<th style='text-align: center'>���m�W</th>										
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
