<div class="primary">
        <table class="table_style">
            <thead>
                <div>
                    <tr>
                        <td style="width:150px;">發起人</td>
                        <td>資料來源</td>
                        <td>資料內容</td>
                    </tr>
                </div>
            </thead>
            <tbody>
            <?php
        	foreach ($daily_good_list as $key => $daily_good_info) 
        	{
				echo '
				
				<tr>
                    <td class="text_center">'.$daily_good_info["title"].'</td>
                    <td>'.$daily_good_info["brief"].'</td>
                    <td class="text_center">'.$daily_good_info["content"].'</td>
                </tr>
                
				'; 
				
			}
            
            ?>                
            </tbody>            
        </table>
		<?php echo showFrontendPager($pager)?>

    </div>