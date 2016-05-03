<div class="primary">
    <ul class="ul_unstyle" id="photo">
        <?php  for ($i=0;$i<count($ad_list);$i++) :?>
        <li>
            <a href="<?php echo $ad_list[$i]['img_filename'];?>" class="fancybox" rel="gallery1" >
                    <img src="<?php echo $ad_list[$i]['img_filename'];?>" alt="">
                </a>
        </li>
        <?php endfor;?>
    </ul>


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
