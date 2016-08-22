<!-- 
	navi_type1 住戶服務
	navi_type2 社區服務
	navi_type3 郵件管理
-->

<div id="left_navi" class="navi_type3">
	<div id="left_navi_title">
		<div></div>
		社區服務
	</div>
	<?php
	## [社區服務] 與 [住戶服務] 底下的次選單不同，
	## 依據 controller 來區分要顯示哪一組 ；而 [郵件管理] 不需次選單
	$current_class = $this->router->fetch_class();
	$sub_menu_a = array('voting', 'repair', 'repair_log', 'suggestion', 'suggestion_log', 'rent_house', 'sale_house' );
	$sub_menu_b = array('message', 'mailbox', 'gas', 'keycode' );

	if ( in_array($current_class, $sub_menu_a) ) {
		// [社區服務]次選單
	?>
		<ul class="ul_unstyle">
			<li>
				<a href="<?php echo frontendUrl("voting")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					社區議題調查
				</a>
			</li>

			<li>
				<a href="<?php echo frontendUrl("repair")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					社區環境報修
				</a>
			</li>

			<li>
				<a href="<?php echo frontendUrl("repair_log")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					社區環境報修紀錄
				</a>
			</li>

			<li>
				<a href="<?php echo frontendUrl("suggestion")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					住戶意見箱
				</a>
			</li>

			<li>
				<a href="<?php echo frontendUrl("suggestion_log")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					意見箱回覆查詢
				</a>
			</li>

			<li>
				<a href="<?php echo frontendUrl("rent_house")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					租屋資訊
				</a>
			</li>

			<li>
				<a href="<?php echo frontendUrl("sale_house")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					售屋資訊
				</a>
			</li>
		</ul>

	<?php
	} elseif ( in_array($current_class, $sub_menu_b) ) {
		// [住戶服務]次選單
	?>
		<ul class="ul_unstyle">
			<li>
				<a href="<?php echo frontendUrl("message")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					個人訊息通知
				</a>
			</li>

			<li>
				<a href="<?php echo frontendUrl("mailbox")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					郵件物品通知
				</a>
			</li>

			<li>
				<a href="<?php echo frontendUrl("gas")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					瓦斯度數登記
				</a>
			</li>

			<li>
				<a href="<?php echo frontendUrl("keycode")?>">
					<i class="fa fa-chevron-circle-right icon1" aria-hidden="true"></i>
					<i class="fa fa-chevron-right icon2" aria-hidden="true" ></i>
					磁扣使用查詢
				</a>
			</li>
		</ul>

	<?php
	}
	?>
</div>
