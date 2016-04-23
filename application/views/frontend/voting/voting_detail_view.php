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
        <form action="<?php echo fUrl('update');?>" class="form_style" method="POST">
            <table>
            	<?php

            		$selectType = "radio";
            		if($voting_info['is_multiple']==1){
            			$selectType = "checkbox";	
            		}
            	?>
                <?php for($i=0;$i<count($voting_info['voting_option']);$i++):?>
                <tr>
                    <td colspan="2">
                        <label>
                            <input type="<?php echo $selectType?>" name="voting[]" value="<?php echo $voting_info['voting_option'][$i]['sn']?>">  <?php echo $voting_info['voting_option'][$i]['text']?>
                        </label>
                    </td>
                </tr>
                <?php endfor;?>              
                <tr>
                    <td>
                        <div class="error_msg"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                    	<input type="hidden" name="sn" value ="<?php echo $voting_info['sn']?>">
                        <button class="btn block">送出 <i class="fa fa-chevron-right"></i></button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
