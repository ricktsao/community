<div class="primary">
	<div class="form_group"><div class="form_page_title">APP開通碼查詢</div></div>
        <table class="table_style">
            <thead>
                <div>
					<tr>
                        <td style="width:20%">住戶 : </td>	
                        <td ><?php echo $user_info["name"] ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%">APP開通碼 : </td>	
                        <td >
						<?php
						if ( isNull(tryGetData('app_id', $user_info , NULL))
							&& isNotNull(tryGetData('act_code', $user_info , NULL))) {

							echo '<span style="color: #c00; font-size:20px; font-weight:bold;">'.$user_info["act_code"].'</span>';
						} else {
							echo '住戶APP已經開通。若需重設APP，請洽物業人員，感謝。';
						}

						//var_dump($user_info['app_id']);
						//var_dump($user_info['act_code']);
						?>
						</td>
                    </tr>
                </div>
            </thead>
                       
        </table>
       
    </div>