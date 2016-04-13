<div id="header">
    <div>
        <a href="<?php echo frontendUrl();?>"><img src="<?php echo base_url().$templateUrl;?>images/logo.png" alt=""></a>
        <ul id="navi" class="ul_unstyle">
            <li>
                <a href="#"><img src="<?php echo base_url().$templateUrl;?>images/btn1.png" alt=""></a>
                <ul class="ul_unstyle">
                    <li><a href="#">個人訊息通知<span></span></a></li>
                    <li><a href="#">郵件物品通知<span></span></a></li>
                    <li><a href="#">瓦斯度數登記<span></span></a></li>
					<li><a href="#">磁扣使用查詢<span></span></a></li>
                </ul>
            </li>
            <li>
                <a href="#"><img src="<?php echo base_url().$templateUrl;?>images/btn2.png" alt=""></a>
				<ul class="ul_unstyle">
                    <li><a href="#">社區議題調查<span></span></a></li>
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
                    <li><a href="#">警衛登入<span></span></a></li>
                    <li><a href="#">秘書登入<span></span></a></li>                    
                </ul>
            </li>
        </ul>
    </div>
</div>
<div id="banner">
    <div>
        <img src="<?php echo base_url().$templateUrl;?>images/index_banner_title.png" alt="">
        <div id="banner_news"><?php echo tryGetData("title", $latest_bulletin_info) ?><br><?php echo showMoreStringSimple(tryGetData("content", $latest_bulletin_info),150) ?></div>
        <a href="<?php echo frontendUrl("bulletin","index"); ?>"><img src="<?php echo base_url().$templateUrl;?>images/index_banner_btn.png" alt=""></a>
    </div>
</div>
<div id="member_bar">
    <div class="primary">
        <div class="login_status">
            <img src="<?php echo base_url().$templateUrl;?>images/login_icon.png" alt=""> 陳XX 您好
            <a href="#member_login_form" id="member_login_btn">登入</a> /
            <a href="#">登出</a></div>
    </div>
</div>
<!--
處理light box
-->
<div id="hidden_area">
    <form action="" id="member_login_form">
        <table>
            <tr>
                <td>住戶登入</td>
            </tr>
            <tr>
                <td>
                    <input type="text" class="input_style" placeholder="用戶帳號">
                </td>
            </tr>
            <tr>
                <td>
                     <input type="password" class="input_style" placeholder="用戶密碼">
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" class="btn block">log-in</button>
                </td>
            </tr>
        </table>
    </form>
</div>
