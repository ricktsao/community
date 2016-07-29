<style type="text/css">
	.spec {font-size:16px; color:#f00; font-weight:bold;}
	.normal {font-size:16px; color:#004080; font-weight:bold;}
	.red-font {color: #ff0080;}
	input[type="number"] {width: 80px; text-align:center; color: #369;}

</style>

<div class="page-header">
	<h1>
		白板戰報
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			請輸入個別月份的累積點數與獎金
		</small>
	</h1>
</div>

<div class="row">
	<div class="col-xs-12">
		<form  role="search" action="<?php echo bUrl('index');?>">
		<article class="well">              
			業務單位：
			<div class="btn-group">
			<?php
			echo form_dropdown('unit_sn',$unit_list, $unit_sn);
			?>
			  <!-- <select name="unit_sn" class="form-control">
					<option value=""> - 不拘 - </option>
					
					<option value="1" <?php //if ($case_type == 1) echo 'selected';?>> 專任 </option>
					<option value="2" <?php //if ($case_type == 2) echo 'selected';?>> 一般 </option>
			  </select> -->
			</div>
			期間：
			<?php
			$cur_year = date('Y');
			$year_array = array_combine(range($cur_year-5, $cur_year+1), range($cur_year-5, $cur_year+1));
			$month_array = array_combine(range(1, 12), range(1, 12));;
			echo form_dropdown( 'year', $year_array, $year ).' 年 ';
			echo form_dropdown( 'month', $month_array, $month ).'月';
			?>
			<div class="btn-group">
				  <button type="submit" class="btn btn-primary btn-sm btn_margin"><i class="icon-search nav-search-icon"></i>搜尋</button>			
			</div>
		</article>	
		</form>

		<form method="post" action="<?php echo bUrl('updateReward');?>">
		<div class="col-xs-12">
			<div class="table-responsive">
				<table id="entry" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:10px">序號</th>
							<th style="width:150px">業務單位</th>
							<th style="width:120px">業務姓名</th>
							<th>委託 - 專任</th>
							<th>委託 - 一般</th>
							<th>附表</th>
							<th>斡旋／要約</th>
							<th style="width:160px">本月激勵獎金</th>
							<th style="width:150px">修改時間</th>
							<th style="width:120px">修改人員</th>
						</tr>
					</thead>

					<tbody>
					<?php
					$i = 1;
					$hidden = '';

					foreach ($sales_list as $sales) {
						if ( tryGetData('job_type', $sales) == '內勤' && tryGetData('job_title', $sales)!='總經理' ) continue;
						$user_sn = tryGetData('sn', $sales);
						$hidden = form_hidden('user_sn[]', $user_sn);

						$user_id = tryGetData('id', $sales);
						$hidden .= form_hidden('user_id_array['.$user_sn.']', $user_id);

						$user_name = tryGetData('name', $sales);
						$hidden .= form_hidden('user_name_array['.$user_sn.']', $user_name);

						$point_esp = tryGetData('point_esp', $sales, NULL);
						$point_gen = tryGetData('point_gen', $sales, NULL);
						$point_sub = tryGetData('point_sub', $sales, NULL);
						$point_demand = tryGetData('point_demand', $sales, NULL);
						$amount = tryGetData('amount', $sales, NULL);
						$updated = tryGetData('updated', $sales, '');
						$updated_by_name = tryGetData('updated_by_name', $sales, '');

						echo sprintf('<tr>'
									.'<td>%d %s</td>'
									.'<td style="text-align:center">%s</td>'
									.'<td style="text-align:center">%s</td>'
									.'<td><input type="number" min="0" step="0.01" name="point_esp_array[%d]" value="%.2f"> 點</td>'
									.'<td><input type="number" min="0" step="0.01" name="point_gen_array[%d]" value="%.2f"> 點</td>'
									.'<td><input type="number" min="0" step="0.01" name="point_sub_array[%d]" value="%.2f"> 點</td>'
									.'<td><input type="number" min="0" step="0.01" name="point_demand_array[%d]" value="%.2f"> 點</td>'
									.'<td><input type="number" min="0" step="1" name="amount_array[%d]" value="%d"> 元</td>'
									.'<td>%s</td>'
									.'<td>%s</td>'
									.'</tr>'."\n\n"
									//, $i+1+(($this->page-1) * $per_page_rows)
									, $i
									, $hidden
									, tryGetData('unit_name', $sales)
									, tryGetData('name', $sales).' '.tryGetData('job_title', $sales)
									, $user_sn
									, $point_esp
									, $user_sn
									, $point_gen
									, $user_sn
									, $point_sub
									, $user_sn
									, $point_demand
									, $user_sn
									, $amount
									, $updated
									, $updated_by_name
									)
							;
						$i++;
					}

					
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
	if ( sizeof($sales_list) > 0 ) {
	?>
	<div class="col-xs-12">
		<input type="hidden" name="year" value="<?php echo $year?>" />
		<input type="hidden" name="month" value="<?php echo $month?>" />
		<input type="hidden" name="updated_by" value="<?php echo $this->session->userdata("user_id")?>" />
		<input type="hidden" name="updated_by_name" value="<?php echo $this->session->userdata("user_name")?>" />
		<div class="clearfix form-actions">
			<div class="col-md-offset-11 col-md-1">
				<button class="btn btn-info" type="submit">
					<i class="icon-ok bigger-110"></i>
					確定儲存
				</button>
			</div>
		</div>
		</form>
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