
<style type="text/css">
	th, td {text-align:center}
</style>

<form  role="search" action="<?php echo bUrl('admin');?>">
<article class="well">              
    <div class="btn-group">
		<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("editAdmin/?role=I");?>">
			<i class="icon-edit bigger-120"></i>新增住戶
		</a>
    </div>
    <div class="btn-group">
		<a class="btn  btn-sm btn-info" href="<?php echo bUrl("editAdmin/?role=M");?>">
			<i class="icon-edit bigger-120"></i>新增物業人員
		</a>
    </div>
	
    <!-- <div class="btn-group">
      <select name="role" class="form-control">
      		<option value=""> -不拘- </option>
      		<option value="I" <?php echo ($role=='I') ? 'selected': ''?>> 住戶 </option>
      		<option value="M" <?php echo ($role=='M') ? 'selected': ''?>> 物業人員 </option>
      </select>
    </div> -->

    <div class="btn-group">
	<?php
	echo $building_part_01 .'：';
	echo form_dropdown('b_part_01', $building_part_01_array, $b_part_01);
	echo $building_part_02 .'：';
	echo form_dropdown('b_part_02', $building_part_02_array, $b_part_02);
	echo $building_part_03 .'：';
	echo '<input type="text" name="b_part_03" value="'.$b_part_03.'" size="1">';
	?>
    </div>
	
    <div class="btn-group">
		關鍵字：<input type='text' name='keyword' value='<?php echo $given_keyword;?>'>
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
										<th>角色</th>
										<th>職稱</th>
										<th>戶別</th>
										<th style='text-align: center'>姓名<br>ID / 帳號</th>
										<th>性別</th>
										<th>所有權人</th>
										<th>緊急聯絡人</th>
										<th>管委</th>
										<th style="width:150px">操作</th>
										<th>啟用/停用</th>
										
									</tr>
								</thead>
								<tbody>
									<?php
									//for($i=0;$i<sizeof($list);$i++) {
									$i = 0;
									foreach ( $list as $item) {
									?>
									<tr>
										<td style='text-align: center'><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td style='text-align: center'>
										<?php echo tryGetData($item['role'], config_item('role_array'), '-'); ?>
										</td>
										<td style='text-align: center'><?php echo tryGetData('title', $item, '-');?></td>
										<td style='text-align: center'>
										<?php 
										$building_id = tryGetData('building_id', $item, NULL);
										if ( isNotNull($building_id) ) {
											echo building_id_to_text($building_id);
										}
										?>
										</td>
										<td>
										<?php echo tryGetData('name', $item);?>
										<br>
										<?php
										if ( tryGetData('role', $item, NULL) == 'I' ) {
											echo '<span style="color:#069">'.tryGetData('id', $item).'</span>';
										} else {
											echo '<span style="color:#f00">'.tryGetData('account', $item).'</span>';
											}
										?>
										</td>
										<td style='text-align: center'>
										<?php echo tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
										</td>
										<td>
										<?php
										if (tryGetData("is_owner", $item) == 1) {
											echo '是';
										} else echo '否';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("is_contact", $item) == 1) {
											echo '是';
										} else echo '否';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("is_manager", $item) == 1) {
											echo tryGetData("manager_title", $item);
										} else echo '否';
										?>
										</td>
										<td>
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
										</td>
										
									</tr>
									<?php
										$i++;
									}
									?>
										
									
								</tbody>
								<tr>
					              	<td colspan="11">
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
            url: "<?php echo bUrl("launchAdmin");?>",
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


