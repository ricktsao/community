<div class="primary">
	<div class="form_group">
		<div class="form_page_title">住戶登入(LOG-IN)</div>
		
		<form action="<?php echo frontendUrl("login","checkLogin");?>" method="post" class="form_style">
			<table>
				<tr>
					<td colspan="2">
						<div style="text-align:center">
							- 請感應磁卡 -
						</div>
						
						<input id="input_keycode" type="password" name="keycode" class="input_style" placeholder="請使用磁卡感應" style="width:0px;height:0px;padding:0px;border:none;">
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
