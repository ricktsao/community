<div id="header">
    <div>
        <a href="<?php echo frontendUrl();?>"><img src="<?php echo base_url().$templateUrl;?>images/logo.png" alt=""></a>
        <ul id="navi" class="ul_unstyle">
            <li>
                <a href="#"><img src="<?php echo base_url().$templateUrl;?>images/btn1.png" alt=""></a>
                <ul class="ul_unstyle">
                    <li><a href="<?php echo frontendUrl("message")?>">個人訊息通知<span></span></a></li>
                    <li><a href="<?php echo frontendUrl("mailbox")?>">郵件物品通知<span></span></a></li>
                    <li><a href="<?php echo frontendUrl("gas")?>">瓦斯度數登記<span></span></a></li>
					<li><a href="#">磁扣使用查詢<span></span></a></li>
                </ul>
            </li>
            <li>
                <a href="#"><img src="<?php echo base_url().$templateUrl;?>images/btn2.png" alt=""></a>
				<ul class="ul_unstyle">
                    <li><a href="<?php echo frontendUrl("voting")?>">社區議題調查<span></span></a></li>
                    <li><a href="#">社區環境報修<span></span></a></li>
                    <li><a href="#">社區意見箱<span></span></a></li>					
                </ul>
            </li>
            <li>
                <a href="#"><img src="<?php echo base_url().$templateUrl;?>images/btn3.png" alt=""></a>
            </li>
            <li>
                <a href="#"><img src="<?php echo base_url().$templateUrl;?>images/btn4.png" alt=""></a>
				<ul class="ul_unstyle">
                    <li><a href="<?php echo backendUrl("mailbox")?>">警衛登入<span></span></a></li>
                    <li><a href="<?php echo backendUrl("mailbox")?>">秘書登入<span></span></a></li>                    
                </ul>
            </li>
        </ul>
    </div>
</div>
<div id="banner" style="display:<?php if( ! $show_banner){echo "none";} ?>">
    <div>
        <img src="<?php echo base_url().$templateUrl;?>images/index_banner_title.png" alt="">
        <div id="banner_news"><?php echo tryGetData("title", $latest_bulletin_info) ?><br><?php echo showMoreStringSimple(tryGetData("content", $latest_bulletin_info),150) ?></div>
        <a href="<?php echo frontendUrl("bulletin","index"); ?>"><img src="<?php echo base_url().$templateUrl;?>images/index_banner_btn.png" alt=""></a>
    </div>
</div>
<div id="member_bar">
    <div class="primary">
        <div class="login_status">
		<?php if($this->session->userdata("f_user_name")!== FALSE ){ ?>
			<img src="<?php echo base_url().$templateUrl;?>images/login_icon.png" alt=""> <?php echo $this->session->userdata("f_user_name");?> 您好
			/ <a href="<?php echo fUrl("logout"); ?>">登出</a>
		<?php }else{ ?>            
             <a href="#member_login_form" id="member_login_btn">登入</a>
        <?php } ?>   
		</div>
    </div>
</div>
<!--
處理light box
-->
<div id="hidden_area">
    <form action="<?php echo frontendUrl("login","checkLogin");?>" method="post" id="member_login_form">
        <table>
            <tr>
                <td>住戶登入</td>
            </tr>           
            <tr>
                <td>
                     <input type="password" name="keycode" class="input_style" autofocus placeholder="請使用磁卡感應">
                </td>
            </tr>
           
        </table>
    </form>
</div>
