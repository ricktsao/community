<?php //echo validation_errors(); ?>

<style type="text/css">
	.dataTable th[class*=sorting_] { color: #808080; }
	.dataTables_empty { text-align: center; color: #993300; font-size: 16px;}
	.require, .error {color: #d16e6c;}
	.note {color: #993300; font-size:12px; padding: 5px;}
	.dataTable td {font-size:13px; font-family:verdana;}
	#add_form {background: #f7f7f7; border-top: #d1d1d1 1px dashed; padding:10px 5px 10px 5px}

	#parking_list ul {margin: 0px;}
	#parking_list li {
		list-style-type: none;
		padding: 3px;
		background: #ffffff;
		font-size:14px;
		color: #369;
		border: #d1d1d1 1px solid;
	}
	#parking_list li:hover {
		background: #f7f7f7;
		color: #c00;
		cursor: pointer;
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
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">戶　別：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'>
				<?php
				$building_id = tryGetData('building_id', $user_data, NULL);
				if ( isNotNull($building_id) ) {
					echo building_id_to_text($building_id);
				}
				?>
				</span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">住戶磁卡：</label>
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


			<form action="<?php echo bUrl("addUserParking")?>" method="post"  id="add_formx" role="form">
			<input type='hidden' name='parking_sn' id='parking_sn' >
			<input type='hidden' name='user_sn' value='<?php echo tryGetData('sn', $user_data); ?>'>
			<input type='hidden' name='user_id' value='<?php echo tryGetData('id', $user_data); ?>'>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">新增車位：</label>
				<div class="col-xs-12 col-sm-8">
				<?php
				echo $parking_part_01 .'：';
				echo form_dropdown('p_part_01', $parking_part_01_array, $p_part_01);
				echo '&nbsp;&nbsp;';
				echo $parking_part_02 .'：';
				echo form_dropdown('p_part_02', $parking_part_02_array, $p_part_02);
				echo '&nbsp;&nbsp;';
				echo $parking_part_03 .'：';
				//echo '<input type="text" name="p_part_03" value="'.$p_part_03.'" size="1">';
				echo "<select name='p_part_03'></select>";
				?>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">車號：</label>
				<div class="col-xs-12 col-sm-8"><input type='text' id='car_number' name='car_number' size=50></div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url"></label>
				<div class="col-xs-12 col-sm-6">
				<button class="btn" type="button" id="search-reset" >
						<i class="icon-warning bigger-110"></i>
						重設
				</button>
				<button class="btn btn-success" type="Submit">
						<i class="icon-ok bigger-110"></i>
						確定新增
				</button>
				</div>
			</div>
		</form>

			<div class="form-group">
				<div class="table-responsive">
					<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="id">持有車位：</label>
					<div class="col-xs-12 col-sm-9">
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
									<th><?php echo $parking_part_01;?></th>
									<th><?php echo $parking_part_02;?></th>
									<th><?php echo $parking_part_03;?></th>
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

										$parking_id = tryGetData('parking_id', $parking, NULL);
										if ( isNotNull($parking_id) ) {
											$parking_part =  parking_id_to_text($parking_id, true);
										}
									?>
									<tr>
										<td class="center">
											<?php
											//if ( sizeof($exist_lands_array) < 1 && sizeof($exists_custs_array) > 0) {
											?>
											<label>
												<input type="checkbox" class="ace" name="del[]" value="<?php echo $parking["parking_sn"].'!@'.$parking["user_sn"].'!@'.$parking["user_id"];?>" />
												<span class="lbl"></span>
											</label>
											<?php
											//} else {
											//	echo '-';
											//}
											?>
										</td>
										<td><?php echo $parking_part[0];?></td>
										<td><?php echo $parking_part[1];?></td>
										<td><?php echo $parking_part[2];?></td>
										<td><?php echo '<span style="font-size:16px">'.tryGetData('car_number', $parking, '-').'</span>';?></td>
										
										<td><?php echo tryGetData('updated', $parking, '-');?></td>
										<td><?php echo tryGetData('updated_by', $parking, '-');?></td>
									</tr>
									<?php
									}
									?>
								</tbody>
								<?php
								}
								?>
								<tfoot>
									<tr>
										<td class="center">
											<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deleteUserParking');?>');">
												<i class="icon-trash bigger-120"></i>刪除
											</a>
										</td>
										<td colspan="7"></td>
									</tr>
								</tfoot>
						</table>
						</form>
					</div>
				</div>
			</div>




<script type="text/javascript"> 

//To select country name
function selectParking(parking_sn, parking_id, xlocation) {
	$("#parking_sn").val(parking_sn);
	$("#parking_id").val(parking_id);
	$("#location").val(xlocation).attr("readonly",true);
	$("#suggesstion-box").hide();
}


$(function(){

	var pPart1 = $('select[name=p_part_01]');
	var pPart2 = $('select[name=p_part_02]');
	var pPart3 = $('select[name=p_part_03]');

	pPart1.change(getCartNum);
	pPart2.change(getCartNum);

	function getCartNum(){
		
		if(pPart1.val()!=0&&pPart2.val()!=0){

			$.ajax({
				type: "GET",
				dataType :"JSON",
				url: "<?php echo bUrl('ajaxGetAvailableParking', false);?>",
				data:{p_part_01:pPart1.val(),
					p_part_02:pPart2.val()},
				success:function(_data){
					var options = "";
					for (var k in _data){						
						options+="<option value='"+k+"'>"+_data[k]+"</option>";
					}

					pPart3.html(options);
					/*for(var i=0;i<data.length;i++){
						options+="<option ></option>";
					}
*/

				}
			

			})


		}
	}


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

});



</script>