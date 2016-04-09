<?php //echo validation_errors(); ?>

<style type="text/css">
	.dataTable th[class*=sorting_] { color: #808080; }
	.dataTables_empty { text-align: center; color: #993300; font-size: 16px;}
	.require, .error {color: #d16e6c;}
	.note {color: #993300; font-size:12px; padding: 5px;}
	.dataTable td {font-size:13px; font-family:verdana;}
	#parking_list li:hover {
    background: #fff0f0;
    cursor: pointer;
	}
	#parking_list li {
		list-style-type: none;
		padding: 10px;
		background: #FAFAFA;
		font-weight: bold;
		border-bottom: #F0F0F0 1px solid;
		width: 320px;
	}
</style>

<div class="page-header">
	<h1>
		車位設定
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			
		</small>
	</h1>
</div>

<div class="row">
	<div class="col-xs-12 form-horizontal">
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">戶　號：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('building_id',$user_data); ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">住戶ID：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('id',$user_data); ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">住戶姓名：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('name',$user_data); ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">行動電話：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('phone',$user_data); ?></span></div>
			</div>


			<div class="form-group">
				<div class="table-responsive">
					<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="id">所屬車位：</label>
					<div class="col-xs-12 col-sm-8">
						<div style="float:right;" id="click_add_cust">
							<button class="btn btn-success">新增車位</button>
						</div>
						<form method="post"  id="update_form" role="form">
						<input type="hidden" name="cases_sn" value="<?php //echo $cases_sn;?>">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>										
									<th class="center" style="width:80px">
										<label>
											<input id="checkDelAll_custs" type="checkbox" class="ace"  />
											<span class="lbl"></span>
										</label>
									</th>
									<th>車位編號</th>
									<th>位置</th>
									<th>車號</th>
									<th>設定日期</th>
									<th>設定人</th>
								</tr>
							</thead>
							<tbody>
							<?php
							if (sizeof($exist_parking_array) < 1) {
								echo '<tr><td colspan="6"><span class="note">查無任何車位，請由【新增車位】功能設定住戶的車位資訊</span></td></tr>';
							} else {
									$note_flag = false;
									foreach ($exist_parking_array as $key=>$parking) {
									?>
									<tr>
										<td class="center">
											<?php
											//if ( sizeof($exist_lands_array) < 1 && sizeof($exists_custs_array) > 0) {
											?>
											<label>
												<input type="checkbox" class="ace" name="del[]" value="<?php echo $parking["parking_sn"].'!@'.$parking["parking_id"];?>" />
												<span class="lbl"></span>
											</label>
											<?php
											//} else {
											//	echo '-';
											//}
											?>
										</td>
										<td><?php echo tryGetData('parking_id', $parking, '-');?></td>
										<td><?php echo tryGetData('location', $parking, '-');?></td>
										<td><?php echo '<span style="font-size:16px">'.tryGetData('car_number', $parking, '-').'</span>';?></td>
										
										<td><?php echo tryGetData('created', $parking, '-');?></td>
										<td><?php echo tryGetData('created_by', $parking, '-');?></td>
									</tr>
									<?php
									}
									?>					
								</tbody>
								<?php
								}
								if ( sizeof($exist_parking_array) < 1 && sizeof($exist_parking_array) > 0) {
									?>	
										<tr>
											<td class="center">
												<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deleteCaseCust');?>');">
													<i class="icon-trash bigger-120"></i>刪除委託人
												</a>
											</td>
											<td colspan="7">
											<?php
											if ( $note_flag == true ) {
												echo '<span class="note">　△ 表示資料庫查該名客戶資料，亦即此名委託人雖然設定為"地主"身份，系統將無法撈出此人的土地資料</span>';
											}
											?>
											</td>
										</tr>
								<?php
								}
							?>	
						</table>
						</form>
					</div>
				</div>
			</div>



			<div class="table-responsive" id="add_cust">

				<form action="<?php echo bUrl("addParking")?>" method="post"  id="update_formX" role="form">
				<input type='hidden' name='cases_sn' value='<?php echo tryGetData('sn', $user_data); ?>'>
				<input type='hidden' name='cust_sn' id='cust_sn'>
				
				<div class="form-group" >
					<label class="col-xs-12 col-sm-3 control-label no-padding-right" for="url"><span class='require'>*</span> 車位ID：</label>
					<div class="col-xs-12 col-sm-4">
						<input type='text' name='parking_id' size="50" id="parking_id">
						<button type="button" class="btn btn-purple" id="search-box">
							<i class="ace-icon fa fa-key"></i> 搜尋
						</button>
						<div id="suggesstion-box"></div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 control-label no-padding-right" for="url">位置：</label>
					<div class="col-xs-12 col-sm-4"><input type='text' id='location' name='location' size=20></div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 control-label no-padding-right" for="url">車號：</label>
					<div class="col-xs-12 col-sm-4"><input type='text' id='car_number' name='car_number' size=50></div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 control-label no-padding-right" for="url"></label>
					<div class="col-xs-12 col-sm-4">
					<button class="btn" type="button" id="search-reset" >
							<i class="icon-warning bigger-110"></i>
							清除重設
					</button>
					<button class="btn btn-success" type="Submit">
							<i class="icon-ok bigger-110"></i>
							確定新增
					</button>
				</div>
				</div>
				</form>
			</div>

			<div class="hr hr-16 hr-dotted"></div>



<script type="text/javascript"> 

//To select country name
function selectCountry(sn, parking_id, xlocation) {
	$("#parking_id").val(parking_id);
	/* $("#uni_id").val(id).attr("readonly","readonly");  Emma 說身分證號要讓user編修 */
	/*$("#addr").val(addr).attr("readonly",true);*/
	$("#location").val(xlocation).attr("readonly",true);
	$("#suggesstion-box").hide();
}


$(function(){

	$("#search-reset").click(function(){

			$("#cust_sn").val('');
			$("#parking_id").val('').attr("readonly",false);
			$("#addr").val('').attr("readonly",false);
	});

/*
    $("search-box").autocomplete('<?php echo bUrl('ajaxGetPeople');?>', {
        minChars: 2
    });
*/
	$('#suggesstion-box').hide();

	$("#search-box").click(function(){
	    
		$("#cust_sn").val('');

		$("#addr").val('').attr("readonly",false);

		$.ajax({
				type: "GET",
				url: "<?php echo bUrl('ajaxGetParking', false);?>",
				data:'keyword='+$("#parking_id").val(),
				beforeSend: function(){
					var input = $('#parking_id');
					var inputValue = input.val();
					var nowLehgth = inputValue.length;
					input.css("background","#FFF url(http://phppot.com/demo/jquery-ajax-autocomplete-country-example/loaderIcon.gif) no-repeat 165px");
					if(inputValue != '' && nowLehgth >= 2) {
						input.css("background-image","none");
					} else {
						input.css("background-image","none");
						alert('請至少輸入二個字');
					}

		},
		success: function(data){
			console.log(data);

			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#search-box").css("background","#FFF");
		}
		});
	});




	$('#add_cust').hide();

	$('#add_cust').css('background-color', '#F3F3F3');
	$('.form-actions').css('background-color', '#F3F3F3');
	
	$('#click_add_cust').click(function() {

		$('#add_cust').toggle();

		if($('#add_cust').is(':hidden')) {
			$(this).text('新增車位').attr('class','btn btn-success');
		} else {
			$(this).text('取消新增').attr('class','btn btn-success');
		}


	});
});

</script>