<div class="primary">
	<div class="form_group"><div class="form_page_title">個人訊息通知</div></div>
        <table class="table_style">
            <thead>
                <div>
                    <tr>
                        <td >訊息標題</td>	
                        <td style="width:20%">發送時間</td>
                    </tr>
                </div>
            </thead>
            <tbody>
            <?php
        	foreach ($message_list as $key => $msg_info) 
        	{
				
				echo '
				
				<tr>
                    
                    <td><a href="'.fUrl("detail",TRUE,array(),array("sn"=>$msg_info["sn"])).'">'.$msg_info["title"].'</a></td>                
					<td>
                        <div class="date_style">'.showDateFormat($msg_info["created"],"Y/m/d").'</div>
                    </td>
                </tr>
                
				'; 
				
			}
            
            ?>                
            </tbody>            
        </table>
       
    </div>