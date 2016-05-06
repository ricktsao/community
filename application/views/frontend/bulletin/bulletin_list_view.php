<div class="primary">
	<div class="form_group"><div class="form_page_title">管委公告</div></div>
	<table class="table_style">
		<thead>
			<div>
				<tr>
					<td style="width:20%;">日期</td>
					<td>標題</td>                       
				</tr>
			</div>
		</thead>
		<tbody>
		<?php
		foreach ($bulletin_list as $key => $bulletin_info) 
		{
			echo '
			
			<tr>
				<td>
					<div class="date_style">'.showDateFormat($bulletin_info["start_date"],"Y/m/d").'</div>
				</td>
				<td>
					<a href="'.fUrl("detail",TRUE,array(),array("sn"=>$bulletin_info["sn"])).'">'.$bulletin_info["title"].'</a>
				</td>
			</tr>
			
			'; 
			
		}
		
		?>                
		</tbody>            
	</table>
	<?php echo showFrontendPager($pager)?>
</div>