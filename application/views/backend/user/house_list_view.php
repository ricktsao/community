
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
<!-- 
<article class="well">
    <div class="btn-group">
		<a class="btn  btn-sm btn-yellow" href="<?php echo bUrl("index/", false);?>">
			<i class="icon-edit bigger-120"></i>所有住戶列表
		</a>
    </div>

    <div class="btn-group">
		<a class="btn  btn-sm btn-yellow" href="<?php echo bUrl("listMgrs", false);?>">
			<i class="icon-edit bigger-120"></i>管委人員列表
		</a>
    </div>

    <div class="btn-group">
		<a class="btn  btn-sm btn-yellow" href="<?php echo bUrl("listConts", false);?>">
			<i class="icon-edit bigger-120"></i>緊急聯絡人列表
		</a>
    </div>

    <div class="btn-group">
		<a class="btn  btn-sm btn-yellow" href="<?php echo bUrl("listOwns", false);?>">
			<i class="icon-edit bigger-120"></i>所有權人列表
		</a>
    </div>

    <div class="btn-group">
		<a class="btn  btn-sm btn-info" target="_blank" href="<?php echo bUrl("exportExcel", false);?>">
			<i class="icon-edit bigger-120"></i>住戶資料匯出
		</a>
    </div>
</article>
 -->

<form role="search" method="get" action="<?php echo bUrl('editHouseUser/?role=I');?>">
<article class="well">
    <div class="btn-group">
	<?php
	// 戶別
	echo $building_part_01 .'：';
	echo form_dropdown('b_part_01', $building_part_01_array, $b_part_01);
	echo '&nbsp;&nbsp;';
	echo $building_part_02 .'：';
	echo form_dropdown('b_part_02', $building_part_02_array, $b_part_02);
	echo '&nbsp;&nbsp;門牌地址：';
	$js = 'id="addr_part_01"';
	echo form_dropdown('addr_part_01', $addr_part_01_array, 0, $js);
	echo '&nbsp;&nbsp;樓層：';
	$js = 'id="addr_part_02"';
	echo form_dropdown('addr_part_02', $addr_part_02_array, 0, $js);
	echo '樓';
	?>
    </div>
    <div class="btn-group">
		<button type="submit" class="btn btn-success btn-sm btn_margin">
		<i class="icon-edit nav-search-icon"></i>新增
		</button>
    </div>
</article>
</form>
<form  role="search" >
<article class="well">
    <div class="btn-group">
	<?php
	// 戶別
	echo $building_part_01 .'：';
	echo form_dropdown('b_part_01', $building_part_01_array, $b_part_01);
	echo '&nbsp;&nbsp;';
	echo $building_part_02 .'：';
	echo form_dropdown('b_part_02', $building_part_02_array, $b_part_02);
//	echo '&nbsp;&nbsp;';
//	echo $building_part_03 .'：';
//	echo '<input type="text" name="b_part_03" value="'.$b_part_03.'" size="1">';
	?>
    </div>

    <div class="btn-group">
		<button type="submit" class="btn btn-primary btn-sm btn_margin">
		<i class="icon-search nav-search-icon"></i>搜尋
		</button>
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
										<!-- <th>序號</th> -->
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										<th style='text-align: center'>地址</th>
										<th>人數</th>
										<th>操作</th>
										
									</tr>
								</thead>
								<tbody>
									<?php
									//for($i=0;$i<sizeof($list);$i++) {
									$i = 0;
									foreach ( $list as $item) {
										/*$building_id = tryGetData('building_id', $item, NULL);
										if ( isNotNull($building_id) ) {
											$building_parts = building_id_to_text($building_id, true);
										}*/
									?>
									<tr>
										<!-- td style='text-align: center'><?php //echo ($i+1)+(($this->page-1) * 10);?></td-->
										<td style='text-align: center'><?php echo tryGetData('building_01', $item);?></td>
										<td style='text-align: center'><?php echo tryGetData('building_02', $item);?></td>
										<td style='text-align: center'><?php echo tryGetData('addr', $item);?></td>
										<td><?php echo tryGetData('users', $item).' 人';?></td>
										<td>
											<a class="btn  btn-minier btn-success" title='編輯此戶別人員' href="<?php echo bUrl("editUser",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
										</td>
									</tr>
									<?php
										$i++;
									}
									?>
										
									
								</tbody>
								<tr>
					              	<td colspan="13">
									<?php echo showBackendPager($pager)?>
					                </td>
								</tr>
								
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


