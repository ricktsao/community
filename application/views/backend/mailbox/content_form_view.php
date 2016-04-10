

<?php showOutputBox("tinymce/tinymce_js_view", array('elements' => 'content'));?>
<form action="<? echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<?php echo textOption("<span class='require'>*</span>收件人","user_name",$edit_data); ?>
	
	<div class="form-group ">
		<label for="parent_sn" class="col-xs-12 col-sm-3 control-label no-padding-right">郵件類型 </label>
		<div class="col-xs-12 col-sm-4">
			<div class="btn-group">
              <select class="form-control" name="type">
              	<?php 
              	foreach ($this->config->item("mail_box_type") as $key => $value) 
              	{
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
              	?>	
              	
                 
              </select>
            </div>			
		</div>
		
	</div>
	
	
	<?php
	  echo textAreaOption("郵件敘述說明","desc",$edit_data);
	?>	
	
	
	<div class="form-group" style="display:none" >
		<label class="col-xs-12 col-sm-3 control-label no-padding-right" for="url"><span class='require'>*</span> 收件人：</label>
		<div class="col-xs-12 col-sm-4">
			<input type='text' name='name' size="50" id="name">
			<button type="button" class="btn btn-purple" id="search-box">
				<i class="ace-icon fa fa-key"></i> 搜尋
			</button>
			<div id="suggesstion-box"></div>
		</div>
	</div>	
		
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				回上一頁
			</a>		
		

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				送交
			</button>
			
		</div>
	</div>
</form>
	
<style type="text/css">
#names_list {
    float: left;
    list-style: none; font-family: '微軟正黑體';
	font-size:14px; color: #369;
    margin: 0;
    padding: 0; 
    max-height: 440px;
    overflow-y: auto;
}
#names_list li {
    padding: 10px;
    background: #FAFAFA; font-weight:bold;
    border-bottom: #F0F0F0 1px solid;width: 760px;
}
#names_list li:hover{background:#fff0f0; cursor: pointer;}
</style>

<script type="text/javascript"> 
$(function(){
	$("#search-box").click(function(){
	    
		$("#cust_sn").val('');

		$("#addr").val('').attr("readonly",false);

		$.ajax({
				type: "GET",
				url: "<?php echo bUrl('ajaxGetPeople');?>",
				data:'keyword='+$("#name").val()+'&ccd='+$("#ccd").val()+'&lsn='+$("#lsn").val(),
				beforeSend: function(){
					var input = $('#name');
					var inputValue = input.val();
					var nowLehgth = inputValue.length;
					input.css("background","#FFF url(http://phppot.com/demo/jquery-ajax-autocomplete-country-example/loaderIcon.gif) no-repeat 165px");
					if(inputValue != '' && nowLehgth >= 2) {
						input.css("background-image","none");
					} else {
						input.css("background-image","none");
						alert('請至少輸入二個字');
					}

		},
		success: function(data){
			console.log(data);

			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#search-box").css("background","#FFF");
		}
		});
	});
}); 


//To select country name
function selectCountry(sn, name, id, addr) {
	$("#cust_sn").val(sn);
	$("#name").val(name);
	/* $("#uni_id").val(id).attr("readonly","readonly");  Emma 說身分證號要讓user編修 */
	/*$("#addr").val(addr).attr("readonly",true);*/
	$("#uni_id").val(id); 
	$("#addr").val(addr);
	$("#suggesstion-box").hide();
}
</script>	
  