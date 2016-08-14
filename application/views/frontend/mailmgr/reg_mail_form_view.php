<div class="primary">
	<div class="form_group">
		<div class="form_page_title">郵件登錄</div>

		<form action="<? echo fUrl("updateRegMail")?>" method="post" class="form_style">
			<table>
				<tr>
					<td colspan="2">						
						收件人 : <?php echo $user_info["name"]?>
					</td>					
				</tr>
				
				<tr>
					<td colspan="2">						
						郵件類型 : 
						<select class="form-control" name="type">
			              	<?php 
			              	foreach ($mail_box_type_ary as $key => $value) 
			              	{
								echo '<option value="'.$key.'">'.$value.'</option>';
							}
			              	?>	
			              </select>
					</td>					
				</tr>
				
				<tr>
					<td colspan="2">		
						<textarea name="desc" placeholder="郵件敘述說明"></textarea>
					</td>					
				</tr>
				
						
				
				
				<tr>
					<td colspan="2">
						<button class="btn block">登錄 <i class="fa fa-chevron-right"></i></button>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<a class="btn" href="<?php echo fUrl("index",TRUE,array("sn")) ?>">							
							回上一頁
						</a>							
					</td>
				</tr>
			</table>
			<input type="hidden" id="user_sn" name="user_sn" value="<?php echo $user_info["sn"]?>" >
			<input type="hidden" id="user_name" name="user_name" value="<?php echo $user_info["name"]?>" >
		</form>
	</div>
</div>