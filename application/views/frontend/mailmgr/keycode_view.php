<div class="primary">
	<!--
	<div><a href="<?php echo fUrl("index")?>">郵件登錄</a></div>
	<div><a href="<?php echo fUrl("user_keycode")?>">郵件領取</a></div>
	<div><a href="<?php echo fUrl("log")?>" >郵件物品記錄</a></div>
	-->
	<div class="form_group">
		<div class="form_page_title">領收人請使用磁扣感應</div>
		
		<form action="<?php echo fUrl("receiveList");?>" method="post" class="form_style">
			<table>
				<tr>
					<td colspan="2">
						<div style="text-align:center">
							- 請感應磁卡 -
						</div>
						<input id="input_keycode" type="password" name="keycode" class="input_style" placeholder="請使用磁卡感應" style="width:0px;height:0px;padding:0px;border:none;">						
						
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
