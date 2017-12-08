<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo isset($title_for_layout) ? $title_for_layout : 'Dairy ';?></title>
        
	<?php
        /*********** For Local ************************/ 
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('bootstrap.min.css', 'font-awesome.min.css', 'custom.css', 'development.css'));
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		echo $this->Html->script(array('jquery.min.js'));
        echo $this->Html->script(array( 'bootstrap.min.js', 'custom.js', 'validation.js','moment.min.js', 'jquery.dataTables.min.js'));
        /*********** End For Local ************************/ 
	?>
        <!--************* For Live ***********************-->
        <!-- <link rel="stylesheet" type="text/css" href="https://dairy.cosmeatiles.com/app/webroot/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://dairy.cosmeatiles.com/app/webroot/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="https://dairy.cosmeatiles.com/app/webroot/css/custom.css">
        <link rel="stylesheet" type="text/css" href="https://dairy.cosmeatiles.com/app/webroot/css/development.css">

        <script type="text/javascript" src="https://dairy.cosmeatiles.com/app/webroot/js/jquery.min.js"></script>
        <script type="text/javascript" src="https://dairy.cosmeatiles.com/app/webroot/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://dairy.cosmeatiles.com/app/webroot/js/validation.js"></script>
        <script type="text/javascript" src="https://dairy.cosmeatiles.com/app/webroot/js/jquery.dataTables.min.js"></script> -->

        <!--************* End For Live ***********************-->

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="nav-md">
        <script language="javascript">
            var webURL = "<?php echo $params['webURL']; ?>";
            // alert(webURL);
            var imgURL = "<?php echo $params['imgURL']; ?>";
            var controllerName = "<?php echo $params['controller']; ?>";
            var actionName = "<?php echo $params['action']; ?>";
            var globalSize = "<?php echo (Configure::read('GlobalSettings.FILE_UPLOAD_SIZE_MB')*1024*1024)?>";	
            var modulePlannerContent= false;		
        </script>
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <!-- sidebar menu -->
            <?php echo $this->element('sidebar-menu'); ?>
                        <!-- /sidebar menu -->
                    </div>
                </div>
                <!-- top navigation -->        
                <div class="top_nav"><?php echo $this->element('top-navigation'); ?></div>        
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col body" role="main">			
                    <div class="successMessage"><div id="flashMsg"><?php echo $this->Flash->render(); ?><a class="noty-remove" href="#"><i class="fa fa-times" aria-hidden="true"></i></a></div></div> 

					<div class="loader" style="display:none;" id="loaderImg">
						<img src="<?php echo $params['imgURL'];?>/Loader.gif"/>
					</div> 
					<?php echo $this->fetch('content'); ?>
                </div>
                <!-- /page content -->

                <!-- footer content -->
                <footer>
                    <div class="pull-right">
                        Mukesh Dairy
                    </div>
                    <div class="clearfix"></div>

                </footer>
                <!-- /footer content -->

            </div> <!-- /main container -->
        </div> <!-- /body container -->
        <!-- jQuery -->       

        <script>
            setTimeout(function () {
                $('.successMessage').fadeOut('slow');
            }, 3000); // <-- time in milliseconds
			
			$(document).ready(function() 
			{ 
				var msgval = $("#flashMessage").text();            
				var trimtxt = msgval.trim();
				var count = trimtxt.length;
			   if(count > 0)
				{
					$('.successMessage').show();                
				}
				else
				{
					$('.successMessage').hide();
				}
			});
			$('.noty-remove').click(function(){
			$('.successMessage').hide();
		  });
        </script>
        <div class="modal fade bs-example-modal-view-clone-subject" tabindex="-1" role="dialog" aria-hidden="true" id='cloneSubDtl'></div>
		<div class="modal fade bs-example-modal-view-attendance" tabindex="-1" role="dialog" aria-hidden="true" id='viewLCAttendance'></div>
    </body>
</html>
<?php //echo  $this->element('sql_dump');   ?>
