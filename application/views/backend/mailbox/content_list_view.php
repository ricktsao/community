<style type="text/css">
	.spec {font-size:16px; color:#f00; font-weight:bold;}
	.normal {font-size:16px; color:#004080; font-weight:bold;}
	.red-font {color: #ff0080;}
	input[type="number"] {width: 80px; text-align:center; color: #369;}

</style>

<div class="row">
	<div class="col-xs-12">
		<form  role="search" action="<?php echo bUrl('index');?>">
		<article class="well"> 
			<div class="btn-group">				
				  <button type="submit" class="btn btn-primary btn-sm btn_margin"><i class="icon-search nav-search-icon"></i>新增</button>			
			</div>
			
		</article>	
		</form>

		<form method="post" action="<?php echo bUrl('updateMailbox');?>">
		<div class="col-xs-12">
			<div class="table-responsive">
				<table id="entry" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:40px">領取</th>		
							<th style="width:100px"><i class="icon-time bigger-110 hidden-480"></i>登錄時間</th>
							<th style="width:100px">代收編號</th>							
							<th style="width:80px">郵件類型</th>
							<th style="width:200px">郵件敘述說明</th>
							<th style="width:200px">收件人</th>
							<th style="width:120px">領收人</th>												
						</tr>
					</thead>

					<tbody>
					<?php
					$i = 1;
					$hidden = '';

					foreach ($mailbox_list as $mail_item) 
					{
						echo 
						'
						<tr>
							<td align="center">
								<label>
									<input type="checkbox" value="'.$mail_item["no"].'" name="is_receive[]" class="ace">
									<span class="lbl"></span>
								</label>
							</td>	
							<td>'.showDateFormat($mailbox_list[$i]["booked"],"Y-m-d").'</td>
							<td>'.$mail_item["no"].'</td>						
							<td>'.tryGetData($mail_item["type"], $this->config->item("mail_box_type")).'</td>	
							<td>'.$mail_item["desc"].'</td>										
							<td>'.tryGetData($mail_item["user_sn"], $user_map).'</td>										
							<td><input type="text" /></td>											
						</tr>
						';						
					}
					
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<?php
	if ( sizeof($mailbox_list) > 0 ) {
	?>
	

	
	
	<div class="col-xs-12">
		
		<div class="clearfix form-actions">
			
			<div class="col-md-offset-5 col-md-5" style="color: red">				
				※請填寫領收人並勾選領取,再確定儲存
			</div>
			
			<div class="col-md-offset-1 col-md-1">				
				<button class="btn btn-info" type="submit">
					<i class="icon-ok bigger-110"></i>
					確定儲存
				</button>
			</div>
		</div>
		
	</div>	
	
	<?php
	}
	?>
	</div>
	</form>



<link href="<?php echo base_url('template/backend/css/dataTables/jquery.dataTables.css')?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('template/backend/js/dataTables/jquery.dataTables.min.js')?>"></script>
<script type="text/javascript"> 
$(function(){
    $('#entry').dataTable({
		"language": {
            "url": "<?php echo base_url('template/backend/js/dataTables/lang/Chinese-traditional.json');?>"
        },
		"scrollY": "680px",
	    "scrollCollapse": true,
	    "paging": false, // 分頁模組
	    "info": true, // 分頁模組
		"ordering": false,
		// 表格完成加載繪製完成後執行此方法
		initComplete: function () {
/*
			$('.dataTables_scrollBody').css('margin-top','-18px');
			$('.dataTables_scrollBody thead tr').css('visibility','hidden');
			$('.dataTables_scrollHead thead tr th').click(function(event) {
				$('.dataTables_scrollBody').css('margin-top','-18px');
				$('.dataTables_scrollBody thead tr').css('visibility','hidden');
			});;
*/

		}
	});
});
</script>