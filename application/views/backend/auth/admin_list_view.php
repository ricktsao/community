<form  role="search" action="<?php echo bUrl('admin');?>">
<article class="well">              
    <div class="btn-group">
		<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("editAdmin",FALSE);?>">
			<i class="icon-edit bigger-120"></i>新增
		</a>
    </div>
	
    <div class="btn-group">
      <select name="unit_sn" class="form-control">
      		<option value=""> -不拘- </option>
			<?php 	
			foreach ($unit_list as $key => $item){
				if ($unit_sn == $item['sn']) 
					$chk ='selected';
				else 
					$chk='';
			?>
			<option value="<?php echo $item['sn'] ?>" <?php echo $chk?>><?php echo $item['unit_name'] ?></option>
			<?php 	
			}
			?> 
      </select>
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
										<th style='text-align: center'>帳號</th>
										<th>姓名</th>
										<th>職稱</th>										
										<th>行動電話</th>
										<th style='text-align: center'>是否變更密碼？</th>
										<th><i class="icon-time bigger-110 hidden-480"></i>有效日期</th>

										<th style="width:150px">編輯</th>
										<th>啟用/停用</th>
										
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<sizeof($list);$i++){ ?>
									<tr>
										<td><?php echo ($i+1)+(($this->page-1) * 10);?></td>									
										<td style='text-align: center'><?php echo $list[$i]["id"]?></td>
										<td>
										<?php echo $list[$i]["name"]?>
										<?php
										//if ($list[$i]["launch"] == 2) {
										//	echo '（離職）';
										//}
										?>
										</td>
										<td><?php echo $list[$i]["job_title"]?></td>										
										<td><?php echo $list[$i]["phone"]?></td>
										<td style='text-align: center'>
										<?php
										if ($list[$i]["is_chang_pwd"] == 1) {
											echo '是';
										} else {
											echo '<span style="color: #f00">否</span>';
										}
										?>
										</td>
										<td><?php echo showEffectiveDate($list[$i]["start_date"], $list[$i]["end_date"], $list[$i]["forever"]) ?></td>
										<td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editAdmin",TRUE,NULL,array("sn"=>$list[$i]["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>edit
											</a>
										</td>
										<td>					
											<div class="col-xs-3">
												<label>
													<input name="switch-field-1" class="ace ace-switch" type="checkbox"  <?php echo $list[$i]["launch"]==1?"checked":"" ?> value="<?php echo $list[$i]["sn"] ?>" onClick='javascript:launch(this);' />
													<span class="lbl"></span>
												</label>
											</div>
										</td>
										
									</tr>
									<?php } ?>
										
									
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


