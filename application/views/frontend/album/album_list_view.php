<div class="primary">
        <table class="table_style">
            <thead>
                <div>
                    <tr>
                        <td style="width:20%;">日期</td>
                        <td>相簿主題</td>                        
                    </tr>
                </div>
            </thead>
            <tbody>
            <?php
        	for ($i=0;$i<count($album_list);$i++) 
        	{
				echo '
				
				<tr>
                    <td>
                        <div class="date_style">'.showDateFormat($album_list[$i]['start_date'],"Y/m/d").'</div>
                    </td>
                    <td><a href="'.fUrl('album_detail/'.$album_list[$i]["sn"]."/").'">'.$album_list[$i]["title"].'</a></td>
                 
                </tr>
                
				'; 
				
			}
            
            ?>                
            </tbody>            
        </table>
        <?php echo showFrontendPager($pager)?>
    </div>