<style type="text/css">
	th, td {text-align:center}
</style>

<div class="page-header">
	<h1>
		群組管理
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div>

<form  role="search" action="<?php echo bUrl('contentList');?>">
<article class="well" style="">              
    <div class="btn-group">
		<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("editContent",FALSE);?>">
			<i class="icon-edit bigger-120"></i>新增群組
		</a>	
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
										<th style="width:100px">序號</th>
										<th style="text-align: left;">名稱</th>
										<th style="width:120px">編輯</th>
										<th style="width:120px">權限設定</th>
										<th style="width:120px">人員設定</th>
										<th style="width:120px">啟用/停用</th>
										<th class="center" style="width:80px">
											<label>
												<input type="checkbox" class="ace"  />
												<span class="lbl"></span>
											</label>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
									//for($i=0;$i<sizeof($list);$i++){
									$i = 0;
									foreach ( $list as $item ){
										if ( in_array($item["sn"], array('1', '5')) ) continue;		// 1是住戶 5是富網通
										$i++;
									?>
									<tr>
										<td><?php echo $i+(($this->page-1) * 10);?></td>
										<td style="text-align: left;"><?php echo $item["title"]?></td>
										<td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editContent",TRUE,NULL,array("sn"=>$item["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
										</td>
										<td class="center">
										<?php
										if ( in_array($item["sn"], array('1')) === false ) {	// 須從住戶管理作設定
										?>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editBackendAuth",TRUE,NULL,array("sn"=>$item["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>後台
											</a>
										<?php
										}
										?>
										</td>
										<td class="center">
										<?php
										if ( in_array($item["sn"], array('1','2')) === false ) {	// 須從住戶管理作設定
										?>
											<a class="btn  btn-minier btn-success" href="<?php echo bUrl("editGroupUser",TRUE,NULL,array("sn"=>$item["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>人員設定
											</a>
										<?php
										}
										?>
										</td>
										<td>
										<?php
										if ( $item["sn"] > 5 ) {					// 預設群組不能啟用停用
										?>
											<div class="col-xs-3">
												<label>
													<input name="switch-field-1" class="ace ace-switch" type="checkbox"  <?php echo $item["launch"]==1?"checked":"" ?> value="<?php echo $item["sn"] ?>" onClick='javascript:launch(this);' />
													<span class="lbl"></span>
												</label>
											</div>
										<?php
										}
										?>
										</td>
										<td class="center">
										<?php
										if ( $item["sn"] > 5 ) {					// 預設群組不能刪除
										?>
											<label>
												<input type="checkbox" class="ace" name="del[]" value="<?php echo $item["sn"];?>" />
												<span class="lbl"></span>
											</label>
										<?php
										}
										?>
										</td>
									</tr>
									<?php } ?>
									
									<tr>
										<td colspan="6">
											
										</td>	
										<td class="center">
											<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deleteContent');?>');">
												<i class="icon-trash bigger-120"></i>刪除
											</a>
										</td>
									</tr>
									
								</tbody>								
							</table>
							<?php echo showBackendPager($pager)?>
							
						</div>
						
					</div>					
				</div>	
			</div>
		</div>
	

</form>        

<script type="text/javascript"> 

	
	function launch(obj) {		
	
	 $.ajax({ 
            type : "POST",
            data: {'content_sn' : obj.value  },
            url: "<?php echo bUrl("launchGroup");?>",
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



