<div class="primary">
	<div class="form_group">
		<div class="form_page_title">社區環境報修紀錄</div>
			<table>
				<tr>
					<td colspan="2">
						日期 : <?php echo showDateFormat($suggestion_info["created"],"Y/m/d");?>
					</td>					
				</tr>
				<tr>
					<td colspan="2">
						住戶姓名 : <?php echo $this->session->userdata("f_user_name")?>
					</td>					
				</tr>
				<tr>
					<td colspan="2">
						意見主旨 : <?php echo $suggestion_info["title"];?> 
					</td>					
				</tr>				
				<tr>
					<td colspan="2">
						意見內容 : <?php echo nl2br($suggestion_info["content"]);?> 						
					</td>
				</tr>
				<tr>
					<td colspan="2">
						回覆內容 : <?php echo nl2br($suggestion_info["reply"]);?>
					</td>					
				</tr>
				
				
				
			</table>
			<button class="btn block" onclick="event.preventDefault(); history.back(1);"><i class="fa fa-chevron-left"></i> 回上頁 </button>
	</div>
</div>