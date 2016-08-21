<div class="primary">
	<div class="form_group">
		<div class="form_page_title">住戶登入111(LOG-IN)</div>
		
		<form action="<?php echo fUrl("checkLogin");?>" method="post" class="form_style">
			<table>
				<tr>
					<td colspan="2">
						<input id="account" type="text" name="account" class="input_style" placeholder="帳號">
						<div class="error_msg"><?php echo tryGetData("error_message",$edit_data);?></div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input id="password" type="password" name="pwd" class="input_style" placeholder="密碼">
						<div class="error_msg"><?php echo tryGetData("error_message",$edit_data);?></div>
					</td>
				</tr>    
				<tr>
					<td colspan="2">
						<button class="btn block">登入 <i class="fa fa-chevron-right"></i></button>
					</td>
                </tr>    				
			</table>
		</form>
	</div>
</div>


<script>
$(function() {
	$( "#account" ).focus();
})
</script>
