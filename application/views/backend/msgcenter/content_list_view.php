<form  role="search" action="<?php echo bUrl('contentList');?>">
<article class="well">              
    <div class="btn-group">
		<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("editContent",FALSE);?>">
			<i class="icon-edit bigger-120"></i>訊息發布
		</a>	
    </div>    
    <div class="btn-group">
      <select name="cat_sn" class="form-control">
      		<option value=""> 綜合 </option>
      </select>
    </div>    
    <div class="btn-group">        
          <button type="submit" class="btn btn-primary btn-sm btn_margin"><i class="icon-search nav-search-icon"></i>搜尋</button>
    </div>                
</article>	
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
										<th style="width:100px">序號</th>
										<th style="width:100px">分類</th>										
										<th>訊息標題</th>
										<th>內容</th>	
										<th>發送人員</th>									
										<th style="width:200px"><i class="icon-time bigger-110 hidden-480"></i>發送時間</th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach ($msg_list as $key => $item) 
								{
									$sales_people = explode( ",",$item["to_user_id"]);
									//dprint($item["to_user_id"]);
									$sales_content = '';
									foreach ($sales_people as $key => $value) 
									{
										$sales_content .= '<li style="float:left">'.$value.'</li>';
									}
									
									
									echo
									'
									<tr>
										<td>'.(($key)+(($this->page-1) * 10)).'</td>										
										<td>'.tryGetData($item["category_id"], $this->config->item("sys_message_category_array")).'</td>
										<td>'.$item["title"].'</td>
										<td>'.nl2br($item["msg_content"]).'</td>
										<td>										
											<div class="tooltiptd" style="display:inline-block;">
												<span class="tooltiptitle">共'.count($sales_people).'人</span>												
												<div class="tooltip" style="width:500%;">
													<ul>
														'.$sales_content.'
													</ul>
												</div>
											
											</div>
										</td>
										<td>'.showDateFormat($item["created"]).'</td>
									</tr>
									';
								}
								
								?>		
									
								</tbody>								
							</table>
							<?php echo showBackendPager($pager)?>
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



