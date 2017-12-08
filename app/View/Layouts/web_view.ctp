<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title><?php echo isset($title_for_layout) ? $title_for_layout : 'TalentEdge - LMS'; ?></title>

        <?php
        echo $this->Html->meta('icon');
        echo $this->Html->css(array('bootstrap.min.css', 'font-awesome.min.css', 'custom.css', 'development.css'));
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js'));
        ?>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <script language="javascript">
            var webURL = "<?php echo $params['webURL']; ?>";
            // alert(webURL);
            var imgURL = "<?php echo $params['imgURL']; ?>";
            var controllerName = "<?php echo $params['controller']; ?>";
            var actionName = "<?php echo $params['action']; ?>";
            var FILE_PATH = "<?php echo ABSOLUTE_URL . 'app/webroot/js/Filemanager/index.html' ?>";
            var globalSize = "<?php echo (Configure::read('GlobalSettings.FILE_UPLOAD_SIZE_MB') * 1024 * 1024) ?>";
            var modulePlannerContent= false;
        </script>
        <?php echo $this->fetch('content'); ?>

        <?php  echo $this->Html->script(array('custom.js',  'moment.min.js')); ?>
      
        <div class="modal fade bs-example-modal-view-testimonial" tabindex="-1" role="dialog" aria-hidden="true" id='viewTesti'></div>
        <div class="modal fade bs-example-modal-view-Question" tabindex="-1" role="dialog" aria-hidden="true" id='viewTestQ'></div>
        <div class="modal fade bs-example-modal-view-attendance" tabindex="-1" role="dialog" aria-hidden="true" id='viewLCAttendance'></div>
        <div class="modal fade bs-example-modal-view-online-users" tabindex="-1" role="dialog" aria-hidden="true" id='viewOnlineUsers'></div>
        <div id='composeMsgDiv'></div>
    </body>
</html>
<?php // echo  $this->element('sql_dump');   ?>