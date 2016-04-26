<div class="primary">
	<div class="form_group">
		<div class="form_page_title">意見箱回覆查詢</div>
	</div>		
        <table class="table_style">
            <thead>
                <div>
                    <tr>
						<td style="width:200px">日期</td>
						<td>收件人</td>
						<td>意見主旨</td>                       
                        <td>回覆內容</td>                        
                    </tr>
                </div>
            </thead>
            <tbody>
            <?php
        	foreach ($suggestion_list as $key => $suggestion_info) 
        	{				
				$role_string = "";
				if($suggestion_info["to_role"]=="a")
				{
					$role_string ="管委";
				}
				else if($suggestion_info["to_role"]=="s")
				{
					$role_string ="總幹事";
				}
				
				echo '				
				<tr>                    
                    <td><div class="date_style">'.showDateFormat($suggestion_info["created"],"Y/m/d").'</div></td>
					<td class="text_center">'.$role_string.'</td>
                    <td class="text_center">'.tryGetData( "title",$suggestion_info ).'</td>					
					<td class="text_center"> 
						<button class="btn" onclick="location.href=\''.fUrl("detail",TRUE,array(),array("sn"=>$suggestion_info["sn"])).'\'"  >回覆內容 <i class="fa fa-chevron-right"></i></button> 
					</td>
                </tr>                
				'; 
			}
            
            ?>                
            </tbody>            
        </table>
        <?php echo showFrontendPager($pager)?>
    </div>