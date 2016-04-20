<div class="primary">
    <ul class="ul_unstyle" id="photo">
        <?php  for ($i=0;$i<count($photo_list);$i++) :?>
        <li>
            <a href="<?php echo $photo_list[$i]['img_filename'];?>" class="fancybox" rel="gallery1" title="<?php echo $photo_list[$i]['title'];?>">
                    <img src="<?php echo $photo_list[$i]['img_filename'];?>" alt="">
                </a>
        </li>
        <?php endfor;?>
    </ul>

    <a class="btn" ><i class="fa fa-chevron-left"></i> 返回相簿列表 </a>
</div>
<script>
$(function() {
    var $album = $('#photo').masonry({
        percentPosition: true
    });

    $(".fancybox").fancybox({
        openEffect: 'none',
        closeEffect: 'none'
    });
})
</script>
