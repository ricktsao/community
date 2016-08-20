<?php
$admin_auth = $this->session->userdata("user_auth");

if ( $admin_auth !== false) {
	$c = 0;
?>
	<ul class="nav nav-list">
		<?php foreach ($left_menu_list as $key => $module_item):
			$c++;
		  ?>
			
			<?php if (isNotNull(tryGetData('id', $module_item,NULL)) 
					&& isNotNull(tryGetData('dir', $module_item,NULL)) 
					&& in_array($module_item["id"], $admin_auth)
					&&  $module_item["dir"]==1) {						?>		
				
				<li <?php echo  $module_sn == $module_item["sn"]?'class="open"':''  ?>>	
					<a href="#" class="dropdown-toggle">
						
						
						
						<span class="menu-text"> <?php echo $c.".".$module_item["title"]; ?>  </span>	
						<b class="arrow icon-angle-down"></b>
					</a>
					
					<ul class="submenu" <?php echo  $module_parent_sn == $module_item["sn"]?'style="display: block;"':''  ?>>
						
					
					<?php 
						$cc = 0;
						foreach($module_item_map[$module_item["sn"]]["item_list"] as $item): 	
						if(in_array($item["id"], $admin_auth)){
							$cc++
					?>
						<li <?php echo  $module_id== $item["id"]?'class="active"':''  ?>>
							<a href="<?php echo $item["url"]?>">
								<i class="icon-double-angle-right"></i>						
								<?php echo $cc.".".$item["title"]?>
							</a>
						</li>			
					<?php 
						}
						endforeach; 
					?>
					
					</ul>
					
				</li>
			<?php  } else if(in_array($module_item["id"], $admin_auth)){ ?>
				
				<li <?php echo  $module_id== $module_item["id"]?'class="active"':''  ?>>
					<a href="<?php echo $module_item["url"]?>">
						
						<span class="menu-text"> <?php echo $c.".".$module_item["title"]?> </span>
					</a>
				</li>
			
			<?php  } ?>

		<?php endforeach; ?>
	</ul>
<?php
}
?>