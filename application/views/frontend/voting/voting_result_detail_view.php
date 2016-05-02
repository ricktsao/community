<div class="primary">
    <button class="btn" onclick="event.preventDefault(); history.back(1);"><i class="fa fa-chevron-left"></i> 回上頁 </button>
    <div class="form_group">
        <div class="form_page_title">
            <?php 
	            echo $voting_info["subject"];

	            if($voting_info['allow_anony']==1){
	            	echo " (不記名投票)";
	            }
            ?>
        </div>
        <div class="form_page_text">
            <?php echo $voting_info["description"];?>
        </div>
        <form  class="form_style" method="POST">
            <table class="voting_result_table">
            	<thead>
                 <tr>
                    <td>項次</td>
                     <td>標題</td>
                     <td>投票結果</td>
                 </tr>   
                </thead>
                <tbody>
                <?php for($i=0;$i<count($voting_info['options']);$i++):?>

                <tr>
                    <td>
                        <?php echo $i+1;?>.
                    </td>
                    <td>
                      <?php echo $voting_info['options'][$i]['option_text'];?>
                    </td>
                    <td>
                    <?php echo $voting_info['options'][$i]['voting_count'];?>
                    </td>
                </tr>
                <?php endfor;?>              
               </tbody>
                
            </table>
        </form>
    </div>
</div>
