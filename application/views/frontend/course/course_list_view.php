<div class="primary">
        <table class="table_style">
            <thead>
                <div>
                    <tr>
                        <td >課程主旨</td>
                        <td>收費標示</td>
                        <td style="width:200px">課程日期</td>
                    </tr>
                </div>
            </thead>
            <tbody>
            <?php
        	foreach ($course_list as $key => $course_info) 
        	{
				$money_string = '';
				if($course_info["brief"]==1)
				{
					$money_string = '需收費';
				}
				else 
				{
					$money_string = '不需收費';
				}
				
				echo '
				
				<tr>
                    
                    <td><a href="'.fUrl("detail",TRUE,array(),array("sn"=>$course_info["sn"])).'">'.$course_info["title"].'</a></td>
                    <td class="text_center">'.$money_string.'</td>
					<td>
                        <div class="date_style">'.showDateFormat($course_info["start_date"],"Y/m/d").'</div>
                    </td>
                </tr>
                
				'; 
				
			}
            
            ?>                
            </tbody>            
        </table>
        <?php echo showFrontendPager($pager)?>
    </div>