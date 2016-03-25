<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>E-DOMA e化你家 後台管理中心</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="shortcut icon" href="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/images/favicon.ico">
	<link href="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/css/login.css" rel="stylesheet" type="text/css" />

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>
	    window.jQuery || document.write('\x3Cscript src="http://code.jquery.com/jquery-1.9.1.min.js">\x3C/script>')
	</script>
	<script>window.jQuery || document.write('<script src="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/js/jquery-1.9.1.min.js"><\/script>')</script>
	<script src="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/js/jquery.easing.1.3.js"></script>
	<script language="javascript">

	//重新產生驗證碼
	function RebuildVerifyingCode( obj_verifying_code_img )
	{
		var verifying_code_url = obj_verifying_code_img.src.split( "?" );
		verifying_code_url = verifying_code_url[0];		
		obj_verifying_code_img.src = verifying_code_url + "?" + Math.random();
	}

	</script>
</head>

<body>
	
<div class="bg"></div>
<div class="overlay"></div>
<div class="form-box" id="login-box">
    <div class="header"><img src="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/images/img_logo.png" alt="E-DOMA e化你家 後台管理中心" /></div>
    <form action="<?php echo bUrl("conformAccountPassword",FALSE)?>" method="post">	
        <div class="body">
            <div class="form-group">
                <input type="text" name="id" value="<?php echo tryGetArrayValue('id',$edit_data)?>" class="form-control" placeholder="帳號"/>
            </div>
            <div class="form-group">
                <input type="password" name="password" value="<?php echo tryGetArrayValue('password',$edit_data)?>" class="form-control" placeholder="密碼"/>
            </div>     
            <div id="verification" class="form-group">
				<input type="text" name="vcode" placeholder="驗證碼" class="form-control" style="width: 50%;">  &nbsp;&nbsp; 
				<img style="width:130px;" id="img_verifying_code" align="absmiddle" src="<?php echo base_url()?>verifycodepic" style="cursor:pointer" onclick="RebuildVerifyingCode(this)">
            </div>   
            <div class="form-group">
                <div class="message">
	                <?php echo form_error('id');?>
	            	<?php echo form_error('password');?>
	            	<?php echo form_error('vcode');?>
	            	<?php echo tryGetArrayValue('error_message',$edit_data);?>
                </div>
            </div>   
        </div>
        <div class="footer">  
        	<div class="form-group">                                                             
            	<input type="submit" class="btn-login" value="登入">
            </div>  
        </div>
    </form>
</div>
<script>
        $(function(){
            $(window).load(function(){
                $('.overlay').css('opacity',0);
            });
        });
</script>            

</body>
</html>
