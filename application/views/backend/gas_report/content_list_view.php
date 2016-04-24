<form  role="search" action="<?php echo bUrl('contentList');?>">
<article class="well">
    <div class="btn-group">
      <select name="year" class="form-control">
      		<option value=""> 請選擇年... </option>			
		<?php 	
			foreach ($year_list as $key => $year)
			{
				echo '<option '.($q_year == $year?"selected":"").'  value="'.$year.'">'.$year.'</option>';
			}
		?>
      </select>
	  
    </div>    
		
	<div class="btn-group">
		<select name="month" class="form-control">
      		<option value=""> 請選擇月... </option>			
		<?php 	
			 for($i=1;$i<=12;$i++)
			 {
				 echo '<option '.($q_month == $i?"selected":"").' value="'.$i.'">'.$i.'</option>';
			 }
		?>

      </select>
	</div>
    <div class="btn-group">        
          <button type="submit" class="btn btn-primary btn-sm btn_margin"><i class="icon-search nav-search-icon"></i>搜尋</button>        
    </div>                
</article>	
<span style="display: none" class="label label-sx label-warning">Hot於前端首頁只顯示1則(列表第一筆)</span>		
</form>

<form action="" id="update_form" method="post" class="contentForm">   
	
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
						
							<form id="update_form">
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>住戶地址</th>
										<th style="width:200px">年份</th>										
										<th style="width:200px">月份</th>
										<th style="width:120px">度數</th>
									</tr>
								</thead>
								<tbody>
								
								<?php
								foreach ($building_list as $key => $gas_info) 
								{
									echo '
									<tr>
										<td>'.$gas_info["owner_addr"].'</td>
										<td>'.$gas_info["year"].'</td>
										<td>'.$gas_info["month"].'</td>
										<td>'.$gas_info["degress"].'</td>
									</tr>
									';
								}
								?>		
									
								</tbody>								
							</table>							
							</form>
						</div>
						
					</div>					
				</div>	
			</div>
		</div>
	

</form>        

<script type="text/javascript"> 

	
	function launch(obj) {		
	
	 $.ajax({ 
            type : "POST",
            data: {'content_sn' : obj.value  },
            url: "<?php echo bUrl("launchContent");?>",
            timeout: 3000 ,
            error: function( xhr ) 
            {
                //不處理
            },
            success : function(result) 
            {
            	if(result == 1)
            	{
            		$(obj).prop("checked", true);	
            	}
            	else
            	{
            		$(obj).prop("checked", false);
            	}
           		     
            }
        });	 
	}
</script>



