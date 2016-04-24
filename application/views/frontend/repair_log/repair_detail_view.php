<div class="primary">
	<div class="form_group">
		<div class="form_page_title">社區環境報修紀錄</div>
			<table>
				<tr>
					<td colspan="2">
						報修日期 : <?php echo showDateFormat($repair_info["created"],"Y/m/d");?>
					</td>					
				</tr>
				<tr>
					<td colspan="2">
						住戶姓名 : <?php echo $this->session->userdata("f_user_name")?>
					</td>					
				</tr>
				<tr>
					<td colspan="2">
						維修範圍 : <?php echo tryGetData( $repair_info["type"],$this->config->item('repair_type') );?> 
					</td>					
				</tr>				
				<tr>
					<td colspan="2">
						報修內容 : <?php echo nl2br($repair_info["content"]);?> 						
					</td>
				</tr>
				<tr>
					<td colspan="2">
						處理進度 : <?php echo tryGetData( $repair_info["status"],$this->config->item('repair_status') );?> 
					</td>					
				</tr>
				<tr>
					<td colspan="2">
						回覆內容 : <?php echo nl2br($repair_info["reply"]);?> 
					</td>					
				</tr>
				
			</table>
			<button class="btn block" onclick="event.preventDefault(); history.back(1);"><i class="fa fa-chevron-left"></i> 回上頁 </button>
	</div>
</div>