<div class="primary">
        <table class="table_style">
            <thead>
                <div>
                    <tr>
                        <td style="width:20%;">日期</td>
                        <td>標題</td>
                        <td>內容</td>
                    </tr>
                </div>
            </thead>
            <tbody>
            <?php
        	foreach ($news_list as $key => $bulletin_info) 
        	{
				echo '
				
				<tr>
                    <td>
                        <div class="date_style">'.showDateFormat($bulletin_info["start_date"],"Y/m/d").'</div>
                    </td>
                    <td>'.$bulletin_info["title"].'</td>
                    <td class="text_center">'.$bulletin_info["content"].'</td>
                </tr>
                
				'; 
				
			}
            
            ?>                
            </tbody>            
        </table>
        <?php echo showFrontendPager($pager)?>
    </div>