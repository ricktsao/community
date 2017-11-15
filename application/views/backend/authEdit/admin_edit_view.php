
<form action="<?php echo bUrl("update")?>" method="post"  id="update_form" class="form-horizontal" role="form">
	<?php echo passwordOption("請輸入欲更改的密碼","password",$edit_data);?>
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo backendUrl() ?>">
				<i class="icon-undo bigger-110"></i>
				回上一頁
			</a>		
		

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				送出
			</button>
			
		</div>
	</div>
   
    
</form>        
