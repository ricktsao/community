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
        	foreach ($bulletin_list as $key => $bulletin_info) 
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
        <div class="pager">
            <a href="#"><i class="fa fa-chevron-left"></i></a>
            <div>1</div>
            <a href="#">2</a>
            <a href="#">...</a>
            <a href="#"><i class="fa fa-chevron-right"></i></a>
        </div>
    </div>