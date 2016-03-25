

<form action="<? echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<?php echo textOption("標題","title",$edit_data); ?>
	
	<?php
	  echo textAreaOption("內容","msg_content",$edit_data);
	?>	
	
	
	<div class="form-group ">
		<label for="parent_sn" class="col-xs-12 col-sm-3 control-label no-padding-right">分類</label>
		<div class="col-xs-12 col-sm-4">
			<div class="btn-group">
              <select class="form-control" id="category_id"  name="category_id">
              <?php 
              foreach ($this->config->item("sys_message_category_array") as $key => $value) 
              {
                  echo '<option value="'.$key.'">'.$value.'</option>'; 
              }
              ?>                 
              </select>
            </div>			
		</div>		
	</div>
	
	
	<div class="form-group">
		<label for="url" class="col-xs-12 col-sm-3 control-label no-padding-right">發佈對象</label>
		
		<div class="col-xs-12 col-sm-4">
			<div class="radio">
				<label>
					<input type="radio" class="ace" value="1" checked="" name="target">
					<span class="lbl"> <span style="color:red"><?php echo tryGetData("unit_name", $unit_info); ?></span>所有業務</span>					
				</label>
			</div>
			<br>
			<div class="radio">
				<label>
					<input type="radio" class="ace" value="2" name="target">
					<span class="lbl"> 依業務 </span>
				</label>
				<select multiple="" class="chzn-select" name="to_user_sn[]" id="form-field-select-4" data-placeholder="請選擇(可複選)..." style="width:100%;">
					<?php
					foreach ($unit_sales_list as $key => $item) 
					{
						if(count($item["sales_list"]) >0 )
						{							
							echo '<optgroup label="'.$item["unit_info"]["unit_name"].'">';
							foreach ($item["sales_list"] as $key => $sales_info) 
							{
								echo '<option   value="'.$sales_info["sn"].'" />'.$sales_info["name"];			
							}
							echo '</optgroup>';
						}									
					}
					?>					
					</select>
			</div>
		</div>
	</div>
	
	
	
	<div class="form-group <?php echo isNotNull(form_error("meeting_date")?"has-error":"")?> "   id="meeting_date" style="display:<?php echo tryGetData("category_id", $edit_data,"meeting")=="meeting"?"":"none" ?>">
		<label for="meeting_date" class="col-xs-12 col-sm-3 control-label no-padding-right">會議日期</label>
		<div class="col-xs-12 col-sm-4">
			<input type="text"  onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" value="<?php echo tryGetData("meeting_date", $edit_data) ?>" class="width-100" name="meeting_date" id="meeting_date">					
		</div>
		<div class="help-block col-xs-12 col-sm-reset inline"><?php echo form_error("meeting_date");?></div>
		
	</div>
	


	

		

	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				Back
			</a>		
		

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				Submit
			</button>
			
		</div>
	</div>
</form>
	
	
<script>
	$(function () {

		$(".chzn-select").chosen();

		//chosen plugin inside a modal will have a zero width because the select element is originally hidden
		//and its width cannot be determined.
		//so we set the width after modal is show
		$('#modal-form').on('show', function () {
			$(this).find('.chzn-container').each(function(){
				$(this).find('a:first-child').css('width' , '200px');
				$(this).find('.chzn-drop').css('width' , '210px');
				$(this).find('.chzn-search input').css('width' , '200px');
			});
		})
		
		
		$('#category_id').change(function()
	    {
	    	if($('#category_id').val()=="meeting")
	    	{
	    		$('#meeting_date').show();
	    	}
	    	else
	    	{
	    		$('#meeting_date').hide();
	    	}
	    });
	    
	   
		
	});
</script>
  