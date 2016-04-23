<div class="primary">
        <table class="table_style">
            <thead>
                <div>
                    <tr>
                        <td >議題</td>	
                        <td style="width:30%"> 活動時間</td>
                    </tr>
                </div>
            </thead>
            <tbody>
            <?php
        	for ($i=0;$i<count($voting_list);$i++) 
        	{
				
				echo '
				
				<tr>
                    
                    <td><a href="'.fUrl("detail",TRUE,array(),array("sn"=>$voting_list[$i]["sn"])).'">'.$voting_list[$i]["subject"].'</a></td>                
					<td>
                        <div class=>'.$voting_list[$i]["start_date"].' ~ '.$voting_list[$i]["end_date"].'</div>
                    </td>
                </tr>
                
				'; 
				
			}
            
            ?>                
            </tbody>            
        </table>
       
    </div>