<div class="primary">
	<div class="form_group">
		<div class="form_page_title">住戶登入(LOG-IN)</div>
		
		<form action="<?php echo frontendUrl("login","checkLogin");?>" method="post" class="form_style">
			<table>
				<tr>
					<td colspan="2">
						<input id="input_keycode" type="password" name="keycode" class="input_style" placeholder="請使用磁卡感應">
						<div class="error_msg"><?php echo tryGetData("error_message",$edit_data);?></div>
					</td>
				</tr>                   
			</table>
		</form>
	</div>
</div>


<script>
$(function() {
	$( "#input_keycode" ).focus();
})
</script>
