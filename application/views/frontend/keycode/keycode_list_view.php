<div class="primary">
	<div class="form_group"><div class="form_page_title">磁扣使用查詢</div></div>
        <table class="table_style">
            <thead>
                <div>
					<tr>
                        <td style="width:20%">用戶 : </td>	
                        <td ><?php echo $user_info["name"] ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%">磁扣使用次數 : </td>	
                        <td ><?php echo $user_info["use_cnt"] ?></td>
                    </tr>
					<tr>
                        <td style="width:20%">登入時間 :　</td>	
                        <td ><?php echo $user_info["login_time"] ?></td>
                    </tr>
					<tr>
                        <td style="width:20%">上次登入時間　: </td>	
                        <td ><?php echo $user_info["last_login_time"] ?></td>
                    </tr>
                </div>
            </thead>
                       
        </table>
       
    </div>