

<?php
$btn_string = "填寫";
if(isNotNull($this_mon_gas_info))
{
	$btn_string = "修改";
}
?>

<div class="primary">
	<form id="gas_form" action="<?php echo fUrl("readGas"); ?>" method="post">
		<table class="table_style">
			<thead>
				<div>
					<tr>
						<td style="width:20%;">瓦斯抄表</td>
						<td><?php echo date("Y") ?> 年 <?php echo date("m") ?> 月 </td>
						<td>
							<input type="text" name="degress" class="input_style" value="<?php echo tryGetData("degrees",$this_mon_gas_info) ;?>" style="display:inline-block !important;width:50px; "> 度
						</td>
						<td style="width:20%;">									
							<button class="btn"  ><?php echo $btn_string;?> <i class="fa fa-chevron-right"></i></button>
						</td>
					</tr>
				</div>
			</thead>
		</table>
		<input type="hidden" name="year" value="<?php echo date("Y") ?>">
		<input type="hidden" name="month" value="<?php echo date("m") ?>">
	</form>	
	<table class="table_style">
		<tbody>
		<?php
		foreach ($gas_list as $key => $gas_info) 
		{
			if($gas_info["sn"]==$this_mon_gas_info["sn"])
			{
				continue;
			}
			
			echo '
			<tr>
				<td style="width:20%;">
					<div class="date_style">'.$gas_info["year"].'/'.$gas_info["month"].'</div>
				</td>
				<td class="text_center">'.$gas_info["degrees"].'度</td>
			</tr>
			';
		}
		?>
		</tbody>
	</table>
	<table id="gas_table">
		<thead>
			<tr>
				<td>瓦斯公司基本資料</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>公司名稱：ＸＸＸＸＸ</td>
			</tr>
			<tr>
				<td>公司地址：台北市北投區天母西路132之15號</td>
			</tr>
			<tr>
				<td>服務電話：02-28231975</td>
			</tr>
		</tbody>
	</table>
</div>

