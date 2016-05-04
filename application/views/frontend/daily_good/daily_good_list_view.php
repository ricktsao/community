<div class="primary">
	<div class="form_group"><div class="form_page_title">日行一善</div></div>
        <table class="table_style">
            <thead>
                <div>
                    <tr>
                        <td style="width:150px;">發起人</td>
                        <td>資料來源</td>                        
                    </tr>
                </div>
            </thead>
            <tbody>
            <?php
        	foreach ($daily_good_list as $key => $daily_good_info) 
        	{
				echo '
				
				<tr>
					<td>
						<a href="'.fUrl("detail",TRUE,array(),array("sn"=>$daily_good_info["sn"])).'">'.$daily_good_info["title"].'</a>
					</td>
					<td>
						<a href="'.fUrl("detail",TRUE,array(),array("sn"=>$daily_good_info["sn"])).'">'.$daily_good_info["brief"].'</a>
					</td>		
                </tr>
                
				'; 
				
			}
            
            ?>                
            </tbody>            
        </table>
		<?php echo showFrontendPager($pager)?>

    </div>