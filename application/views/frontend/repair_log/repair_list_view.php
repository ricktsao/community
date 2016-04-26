<div class="primary">
	<div class="form_group">
		<div class="form_page_title">社區環境報修紀錄</div>
	</div>		
        <table class="table_style">
            <thead>
                <div>
                    <tr>
						<td style="width:200px">報修日期</td>
						<td>維修範圍</td>
                        <td>報修內容</td>
                        <td>處理進度</td>                        
                    </tr>
                </div>
            </thead>
            <tbody>
            <?php
        	foreach ($repair_list as $key => $repair_info) 
        	{				
				echo '				
				<tr>                    
                    <td><div class="date_style">'.showDateFormat($repair_info["created"],"Y/m/d").'</div></td>
                    <td class="text_center">'.tryGetData( $repair_info["type"],$this->config->item('repair_type') ).'</td>
					<td class="text_center">'.nl2br($repair_info["content"]).'</td>
					<td class="text_center">'.tryGetData( $repair_info["status"],$this->config->item('repair_status') ) .' 
						<button class="btn" onclick="location.href=\''.fUrl("detail",TRUE,array(),array("sn"=>$repair_info["sn"])).'\'"  >詳情 <i class="fa fa-chevron-right"></i></button> 
					</td>
                </tr>                
				'; 
			}
            
            ?>                
            </tbody>            
        </table>
        <?php echo showFrontendPager($pager)?>
    </div>