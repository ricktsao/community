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
        <div class="pager">
            <a href="#"><i class="fa fa-chevron-left"></i></a>
            <div>1</div>
            <a href="#">2</a>
            <a href="#">...</a>
            <a href="#"><i class="fa fa-chevron-right"></i></a>
        </div>
    </div>