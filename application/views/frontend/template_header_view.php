<div id="header">
    <div>
        <a href="<?php echo frontendUrl();?>" id="c_title"><?php echo tryGetData("comm_name",$webSetting);?></a>
        <ul id="navi" class="ul_unstyle">
            <li>
                <a href="<?php echo frontendUrl("message")?>" title="住戶專屬服務" class="naviBgStyle1">
                    <img src="<?php echo base_url().$templateUrl;?>images/navi_1.png" alt="">
                    住戶專屬服務</a>
                    <!--
                <ul class="ul_unstyle">
                    <li><a href="<?php echo frontendUrl("message")?>">個人訊息通知<span></span></a></li>
                    <li><a href="<?php echo frontendUrl("mailbox")?>">郵件物品通知<span></span></a></li>
                    <li><a href="<?php echo frontendUrl("gas")?>">瓦斯度數登記<span></span></a></li>
					<li><a href="<?php echo frontendUrl("keycode")?>">磁扣使用查詢<span></span></a></li>
                </ul>
            -->
            </li>
            <li>
                <a href="<?php echo frontendUrl("bulletin")?>" title="社區服務"  class="naviBgStyle2">
                     <img src="<?php echo base_url().$templateUrl;?>images/navi_2.png" alt="">
                    社區服務</a>
                    <!--
				<ul class="ul_unstyle">
                    <li><a href="<?php echo frontendUrl("repair")?>">社區環境報修<span></span></a></li>
                    <li><a href="<?php echo frontendUrl("voting")?>">社區議題調查<span></span></a></li>                  
					<li><a href="<?php echo frontendUrl("repair_log")?>">社區環境報修紀錄<span></span></a></li>
                    <li><a href="<?php echo frontendUrl("suggestion")?>">住戶意見箱<span></span></a></li>	
					<li><a href="<?php echo frontendUrl("suggestion_log")?>">意見箱回覆查詢<span></span></a></li>
					<li><a href="<?php echo frontendUrl("rent_house")?>">租屋資訊<span></span></a></li>
					<li><a href="<?php echo frontendUrl("sale_house")?>">售屋資訊<span></span></a></li>
					<li><a href="<?php echo frontendUrl("mailmgr")?>">郵件管理<span></span></a></li>
                </ul>
                -->
            </li>
            <li >
                <a href="<?php echo frontendUrl("about")?>" title="關於社區" class="naviBgStyle3">
                     <img src="<?php echo base_url().$templateUrl;?>images/navi_3.png" alt="">
                    關於社區</a>
            </li>
            <li>
			
			<?php if ($this->session->userdata("user_auth")!== FALSE ){ ?>
			<a href="<?php echo backendUrl()?>" title="物業管理" class="naviBgStyle4">
                 <img src="<?php echo base_url().$templateUrl;?>images/navi_4.png" alt="">
                物業管理</a>
			<?php } else { ?>
			<a href="#" title="物業管理" class="naviBgStyle4">
                 <img src="<?php echo base_url().$templateUrl;?>images/navi_4.png" alt="">
                物業管理</a>	
			<?php } ?>
            </li>

              <li>
                <a href="<?php echo frontendUrl("mailmgr")?>" title="郵務管理" class="naviBgStyle5">
                     <img src="<?php echo base_url().$templateUrl;?>images/navi_5.png" alt="">
                    郵務管理</a>
            </li>
        </ul>
    </div>
</div>

<div id="member_bar">
    <div class="mPrimary">
        <div class="login_status">
		
		<?php if($this->session->userdata("guard_name")!== FALSE ){ ?>
			<img src="<?php echo base_url().$templateUrl;?>images/login_icon.png" alt=""> <?php echo $this->session->userdata("guard_name");?> 您好
			
             <button type="button" onclick='self.location="<?php echo fUrl("logout"); ?>"' >登出</button>
		<?php }else if($this->session->userdata("f_user_name")!== FALSE ){ ?>
			<img src="<?php echo base_url().$templateUrl;?>images/login_icon.png" alt=""> <?php echo $this->session->userdata("f_user_name");?> 您好
			
             <button type="button" onclick='self.location="<?php echo fUrl("logout"); ?>"' >登出</button>
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
                <td style="text-align:center">住戶登入</td>
            </tr>           
            <tr>
                <td>
                <div style="text-align:center">
                <br/>
                            - 請感應磁卡 -
                        </div>
                     <input type="password" name="keycode" class="input_style" autofocus placeholder="請使用磁卡感應" style="width:0px;height:0px;padding:0px;border:none;">
                </td>
            </tr>
           
        </table>
    </form>
</div>
