<div class="primary">
	<div class="form_group">
		<div class="form_page_title">意見箱</div>

		<form action="<?php echo fUrl("postSuggestion");?>" method="post" class="form_style">
			<table>
				<tr>
					<td colspan="2">						
						<input type="text" name="title" class="input_style" placeholder="意見主旨">
					</td>					
				</tr>
				<tr>
					<td colspan="2">
						<textarea name="content" id="" cols="30" rows="10" class="input_style" placeholder="意見內容"></textarea>
						<div class="error_msg"><?php echo tryGetData("error_message",$edit_data);?></div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<label>
							<input name="to_role" value="M" type="radio" checked> 管委收
						</label>
						<label>
							<input name="to_role" value="S" type="radio"> 秘書收
						</label>
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