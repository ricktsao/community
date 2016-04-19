<script>

	$(document).ready(function ()
	{
		$('.fileUpload').liteUploader(
		{
			script: '<?php echo bUrl("uploadAlbumItem",FALSE).'?album_sn='.$album_sn; ?>',
			allowedFileTypes: 'image/jpeg,image/png,image/gif',
			maxSizeInBytes: 25000000,
			customParams: {
				'custom': 'tester'
			},
			before: function ()
			{
				$('#details, #previews').empty();
				$('#response').html('Uploading...');

				return true;
			},
			each: function (file, errors)
			{
				var i, errorsDisp = '';

				if (errors.length > 0)
				{
					$('#response').html('One or more files did not pass validation');

					$.each(errors, function(i, error)
					{
						errorsDisp += '<br /><span class="error">' + error.type + ' error - Rule: ' + error.rule + '</span>';
					});
				}

				$('#details').append('<p>name: ' + file.name + ', type: ' + file.type + ', size:' + file.size + errorsDisp + '</p>');
			},
			success: function (response)
			{
				window.location = "<?php echo bUrl("itemList",FALSE).'?album_sn='.$album_sn; ?>";
			}
		});
	});

</script>
<form action="" id="update_form" method="post" class="contentForm">   
    	<div id="option_bar">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
            	<td>			
            		<select id="album_sn"  name="album_sn" onchange="jUrl('<?php echo bUrl("itemList",FALSE).'?cat_sn='?>'+ $('#cat_sn').val()+'&album_sn='+$('#album_sn').val())">		
					<?php foreach ($album_list as $key => $item){?>
						<option value="<?php echo $item["sn"];?>"  <?php echo $album_sn==$item["sn"]?"selected":"" ?>><?php echo $item["title"];?></option>
					<?php } ?>						
					</select>
					<button type="button" class="btn add" onclick="$('#add_zone').show();" > <?php echo $this->lang->line("common_insert");?></button>
					<div id="add_zone" style="display:none">
					<input type="file" name="fileUpload2" id="fileUpload2" class="fileUpload" multiple="multiple" />	
					<div id="details"></div>
					<div id="response"></div>
					<div id="previews"></div>	
					</div>
            		
        		</td>
              </tr>
            </table>
        </div>
        <div class="list">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" id='dataTable'>
              <tr class='first_row'>
                <td ><?php echo $this->lang->line("field_serial_number");?></td>
                <td>圖片</td>                     
                <td ><?php echo $this->lang->line("common_handle");?></td>
                <td ><?php echo $this->lang->line("common_launch");?></td>
                <td width="10%">
                	<button type="button" class='btn select_all'  onclick="SelectAll( 'del[]' )">
							<?php echo $this -> lang -> line("common_select_all"); ?>
					</button>
					<button type="button" class='btn select_revers'  onclick="ReverseSelect( 'del[]' )" >
						<?php echo $this -> lang -> line("common_reverse_select"); ?>
					</button>                	
                </td>
              </tr>
     		<tbody>
              <?php for($i=0;$i<sizeof($list);$i++){ ?>
              <tr class="<?php echo $i%2==0?"odd":"even"?>">
                <td><?php echo $list[$i]["sn"]?></td>
                <td><img Src="<?php echo $list[$i]["img_filename"]?>" style="width:200px"  ?></td>               
                <td>
                	<button type="button" class="btn edit" onclick="jUrl('<?php echo bUrl("editItem",TRUE,NULL,array('album_sn'=>$album_sn,'item_sn'=>$list[$i]["sn"]))  ?>')"><?php echo $this->lang->line("common_handle");?></button>
                </td>   
                <td>&nbsp;<?php
                	if($list[$i]["launch"]==1){
                		echo "O";	
					}                
                ?>
                	</td>
                <td><input name="del[]" id="del" value="<?php echo $list[$i]["sn"]?>" type="checkbox" ></td>
              </tr>
              <?php } ?>
			</tbody>
              <tr>
              	<td colspan="4">
				<?php echo showBackendPager($pager)?> 
                </td>
                <td>                	
                	<button type="button" class="btn del" onclick="listViewAction('#update_form','<?php echo bUrl("delItem")?>','是否確定刪除')"><?php echo $this->lang->line("common_delete");?></button>
					
                </td>
              </tr>
            </table>
            
        </div>    
		<input type="hidden" name="album_sn" value="<?php echo $album_sn?>" />
</form>        
