<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Login</title>
   
    <?php
      echo $this->Html->css(array('bootstrap.min', 'font-awesome.min', 'custom', 'development'));
      echo $this->Html->script(array('jquery.min', 'bootstrap.min.js', 'validation.js'));
    ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- <link rel="stylesheet" type="text/css" href="https://dairy.cosmeatiles.com/app/webroot/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://dairy.cosmeatiles.com/app/webroot/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://dairy.cosmeatiles.com/app/webroot/css/custom.css">
    <link rel="stylesheet" type="text/css" href="https://dairy.cosmeatiles.com/app/webroot/css/custdevelopmentom.css">

    <script type="text/javascript" src="https://dairy.cosmeatiles.com/app/webroot/js/jquery.min.js"></script>
    <script type="text/javascript" src="https://dairy.cosmeatiles.com/app/webroot/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://dairy.cosmeatiles.com/app/webroot/js/validation.js"></script> -->
  </head>
  <body>
      <script language="javascript">
            var webURL = "<?php echo $params['webURL']; ?>";
            // alert(webURL);
            var imgURL = "<?php echo $params['imgURL']; ?>";
            var controllerName = "<?php echo $params['controller']; ?>";
            var actionName = "<?php echo $params['action']; ?>";
      </script>
      <div class="container">
          <div class="main_container">
            <div class="col-xs-12 col-md-6 rpdng">
                <div class="logolgn"><a href="#"><img src="<?php echo $params['imgURL']; ?>/logo.png"/></a></div>
                <div class="lftLoginPanel">
                    <img src="<?php echo $params['imgURL']; ?>/lgn-banner.jpg"/>
                    <h3 class="lgnpagettl">WORLD CLASS DAIRY PRODUCT</h3>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 rpdng">
              <?php echo $this->fetch('content'); ?>
            </div>
            <div class="help"><span class="helptxt">Help</span><a href="#"><img src="<?php echo $params['imgURL']; ?>/help.png"/></a></div>  
          </div>
      </div>
      
<!-- Forgot Password modal -->
<div class="modal fade bs-example-modal-fgtpwd" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Forgot Password</h4>
      </div>
        <?php echo $this->Form->create('forgotPassword',array('class' => 'form-horizontal form-label-left')); ?>  
        <div class="modal-body">
			<br>
			<div class="form-group">
			  <div id="fPwdEmailSuccess" class="greenDiv"></div>
			   <label style="margin-bottom:10px;">Please enter Email ID</label>
			   
				<?php echo $this->Form->input('', array('type'=>'text', 'name'=>'data[fPwdEmail]', 'id'=>'fPwdEmail', 'class'=>'form-control col-md-7 col-xs-12')); ?>
			 <br><br>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" id="fPwdProceed" class="btn btn-primary validateFpwdForm">Proceed</button>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
<!------->
      
      <!-- jQuery -->
<script>   
        $(document).ready(function() {
          function setHeight() {
            documentHeight = $(document).height()
            $('.lgnform').css('min-height', documentHeight);
          };
          setHeight();

          $(window).resize(function() {
            setHeight();
          });
          
        $(".validateFpwdForm").on('click', function () {
        formNameNew = 'forgotPasswordLoginForm';    
        $.ajax({
            url: webURL + "Users/forgotPassword",
            type: 'POST',
            data: $("#forgotPasswordLoginForm").serialize() + "&type=add",
            beforeSend: function(msg){
			$("#loaderImg").show();
		  },
            success: function (data) {
		$("#loaderImg").hide();
                var json = JSON.parse(data);
                if (json.status == "error") {
                    $(".redDiv").remove();
                    $(".greenDiv").empty();
                    $.each(json.errors, function (index, element) {
                        $("#" + index + "Error").remove();
                        $("#" + index).after("<div id='" + index + "Error' class='redDiv'>" + element + "</div>");
                    });
                } else if (json.status == "success") {
                    $(".redDiv").remove();
                    $("#fPwdProceed").hide();
                    $("#fPwdEmailSuccess").html(json.success['fPwdEmailSuccess']);
                }
            }
        });
        return false;
    });
       
});
</script>
      
  </body>
</html>
