<div class="primary">
		<div class="form_group"><div class="form_page_title">瓦斯度數登記</div></div>
		<table class="table_style">
			<thead>
				<div>
					<tr>
						<td colspan=3 >瓦斯抄表</td>
					</tr>
                                        <?php
                                        foreach ($g_list as $key => $mon_gas_info) 
                                        {
                                            $btn_str = "填寫";
                                            if(tryGetData("sn",$mon_gas_info)>0)
                                            {
                                                    $btn_str = "修改";
                                            }
                                            $gas_input_str = 
                                            '<form id="gas_form" action="'.fUrl("readGas").'" method="post">
                                             <tr>						
                                                  <td style="width:20%;">'.$mon_gas_info["year"].' 年 '.$mon_gas_info["month"].' 月 </td>
                                                  <td>
                                                      <input type="text" name="degress" class="input_style" value="'.tryGetData("degress", $mon_gas_info).'" style="display:inline-block !important;width:50px; "> 度
                                                  </td>
                                                  <td style="width:20%;">									
                                                          <button class="btn"  >'.$btn_str.' <i class="fa fa-chevron-right"></i></button>
                                                  </td>
                                              </tr>
                                              <input type="hidden" name="year" value="'.$mon_gas_info["year"].'">
                                              <input type="hidden" name="month" value="'.$mon_gas_info["month"].'">
                                            </form>
                                            ';
                                            echo $gas_input_str;
                                        }
                                        ?>
				</div>
			</thead>
		</table>
		
		
	<table class="table_style">
		<tbody>
		
		<?php
		
		if(count($gas_list)>0)
		{
			echo '
			<tr>
				<td colspan=2 style="text-align:center;font-weight:bold;" ><H2>歷史紀錄</H2></td>
			</tr>';
		}
		
		foreach ($gas_list as $key => $gas_info) 
		{
			/*
			if($gas_info["sn"]==$this_mon_gas_info["sn"] || $gas_info["sn"]==$last_mon_gas_info["sn"])
			{
				continue;
			}
			*/
			
			$degress = "無";
			if(tryGetData("degress", $gas_info,0)>0)
			{
				$degress = tryGetData("degress", $gas_info)." 度";
			}
			
			
			echo '
			<tr>
				<td style="width:20%;">
					<div class="date_style">'.$gas_info["year"].'/'.$gas_info["month"].'</div>
				</td>
				<td class="text_center">'.$degress.'</td>
			</tr>
			';
		}
		?>
		</tbody>
	</table>
	
	
	
	<div class="row">
		<div class="form_group"><div class="form_page_title">瓦斯公司</div></div>
		<div id="rent_info">
			<table>
				<tbody>
					<tr>
						<td>公司名稱</td>
						<td><?php echo tryGetData("title",$gas_company_info);?></td>
					</tr>
					<tr>
						<td>地址</td>
						<td><?php echo nl2br(tryGetData("content",$gas_company_info));?></td>
					</tr>
					<tr>
						<td>電話</td>
						<td><?php echo tryGetData("brief",$gas_company_info);?></td>
					</tr>
					<tr>
						<td>手機</td>
						<td><?php echo tryGetData("brief2",$gas_company_info);?></td>
					</tr>
					<tr>
						<td>網址</td>
						<td><?php echo tryGetData("url",$gas_company_info);?></td>
					</tr>
					<tr>
						<td>圖片</td>
						<td>
							<img src="<?php echo tryGetData("img_filename",$gas_company_info);?>" width="599" height="447"  >
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<br clear="all" />
	
	
</div>

