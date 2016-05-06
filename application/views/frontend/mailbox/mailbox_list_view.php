<div class="primary">
	<div class="form_group"><div class="form_page_title">郵件物品通知</div></div>

        <table class="table_style">
            <thead>
                <div>
                    <tr>
						<td ></td>
						<td >郵件類型</td>
                        
                        <td style="width:20%">代收編號</td>
						<td >是否領取</td>
						<td >登錄時間</td>	
                    </tr>
                </div>
            </thead>
            <tbody>
            <?php
        	foreach ($mailbox_list as $key => $mail_info) 
        	{
				$mail_type_string = tryGetData($mail_info["type"],$mail_box_type_ary);
				
				$receive_string = '<span style="color:red;">未領取</span>';
				if($mail_info["is_receive"]==1)
				{
					$receive_string = '<span style="color:blue;">已領取</span>';
				}
				
				echo '
				
				<tr>
					<td>
                        <div class="text_center"><img src="'.base_url().$templateUrl.'images/mailbox_'.$mail_info["type"].'.png" alt=""></div>
                    </td>
                    <td>'.$mail_type_string.'</td>
                    
                    <td>'.$mail_info["no"].'</td>
					<td>'.$receive_string.'</td>	
					<td>
                        <div class="date_style">'.showDateFormat($mail_info["booked"],"Y/m/d").'</div>
                    </td>		
					
                </tr>
                
				'; 
				
			}
            
            ?>                
            </tbody>            
        </table>
       
    </div>