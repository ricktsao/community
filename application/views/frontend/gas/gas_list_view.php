<?php
$btn_string1 = "填寫";
$btn_string2 = "填寫";
if(tryGetData("sn",$this_mon_gas_info)>0)
{
	$btn_string1 = "修改";
}
if(tryGetData("sn",$last_mon_gas_info)>0)
{
	$btn_string2 = "修改";
}
?>

<div class="primary">
	
		<table class="table_style">
			<thead>
				<div>
					<tr>
						<td colspan=3 >瓦斯抄表</td>
					</tr>
					<form id="gas_form" action="<?php echo fUrl("readGas"); ?>" method="post">
					<tr>						
						<td style="width:20%;"><?php echo $this_mon_gas_info["year"] ?> 年 <?php echo $this_mon_gas_info["month"] ?> 月 </td>
						<td>
							<input type="text" name="degress" class="input_style" value="<?php echo tryGetData("degress",$this_mon_gas_info) ;?>" style="display:inline-block !important;width:50px; "> 度
						</td>
						<td style="width:20%;">									
							<button class="btn"  ><?php echo $btn_string1;?> <i class="fa fa-chevron-right"></i></button>
						</td>
					</tr>
					<input type="hidden" name="year" value="<?php echo $this_mon_gas_info["year"] ?>">
					<input type="hidden" name="month" value="<?php echo $this_mon_gas_info["month"] ?>">
					</form>
					
					<form id="gas_form" action="<?php echo fUrl("readGas"); ?>" method="post">
					<tr>						
						<td style="width:20%;"><?php echo $last_mon_gas_info["year"] ?> 年 <?php echo $last_mon_gas_info["month"] ?> 月 </td>
						<td>
							<input type="text" name="degress" class="input_style" value="<?php echo tryGetData("degress",$last_mon_gas_info) ;?>" style="display:inline-block !important;width:50px; "> 度
						</td>
						<td style="width:20%;">									
							<button class="btn"  ><?php echo $btn_string2;?> <i class="fa fa-chevron-right"></i></button>
						</td>
					</tr>
					<input type="hidden" name="year" value="<?php echo $last_mon_gas_info["year"] ?>">
					<input type="hidden" name="month" value="<?php echo $last_mon_gas_info["month"] ?>">
					</form>
				</div>
			</thead>
		</table>
		
		
	<table class="table_style">
		<tbody>
		
		<?php
		
		if(count($gas_list)>0)
		{
			echo '
			<tr>
				<td colspan=2 style="text-align:center;font-weight:bold;" >歷史紀錄</td>
			</tr>';
		}
		
		foreach ($gas_list as $key => $gas_info) 
		{
			if($gas_info["sn"]==$this_mon_gas_info["sn"] || $gas_info["sn"]==$last_mon_gas_info["sn"])
			{
				continue;
			}
			
			echo '
			<tr>
				<td style="width:20%;">
					<div class="date_style">'.$gas_info["year"].'/'.$gas_info["month"].'</div>
				</td>
				<td class="text_center">'.$gas_info["degress"].'度</td>
			</tr>
			';
		}
		?>
		</tbody>
	</table>
	<?php echo $gas_company_info["content"];?>	
</div>

