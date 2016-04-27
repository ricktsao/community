<div class="primary">
	<div class="form_group">
		<div class="form_page_title">社區環境報修</div>

		<form action="<?php echo fUrl("postRepair");?>" method="post" class="form_style">
			<table>
				<tr>
					<td colspan="2">
						報修日期 : <?php echo date("Y-m-d");?>
					</td>					
				</tr>
				<tr>
					<td colspan="2">
						住戶姓名 : <?php echo $this->session->userdata("f_user_name")?>
					</td>					
				</tr>
				<tr>
					<td colspan="2">
						維修範圍 : 
						<select name="type" id="" class="input_style">
							<option value="1">公共區域</option>
							<option value="2">住家內部</option>
						</select>
					</td>					
				</tr>				
				<tr>
					<td colspan="2">
						<textarea name="content" id="" cols="30" rows="10" class="input_style" placeholder="報修內容"></textarea>
						<div class="error_msg"><?php echo tryGetData("error_message",$edit_data);?></div>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<button class="btn block">送出 <i class="fa fa-chevron-right"></i></button>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>