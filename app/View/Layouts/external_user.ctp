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
 $this->Common->updateUserLoginLogoutTime();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title><?php echo isset($title_for_layout) ? $title_for_layout : 'TalentEdge - LMS'; ?></title>

        <!--for sharing-->
            <!--<meta property="og:image" content="<?php echo (isset($og_image) ? $og_image : '') ?>">
            <meta property="og:description" content="<?php echo (isset($og_description) ? $og_description : '') ?>">
            <meta property="og:title" content="<?php echo (isset($og_title) ? $og_title : '') ?>">
            <meta property="og:type" content="article">
            <meta property="og:url" content="<?php echo (isset($og_url) ? $og_url : '') ?>">">

            <meta property="twitter:card" content="summary">
            <meta property="twitter:creator" content="@Talentedge">
            <meta property="twitter:site" content="@Talentedge">-->
        <!--End sharing--> 
        
        <!--<link rel="shortcut icon" type="image/x-icon" href="URL">-->
        <?php
        echo $this->Html->meta('icon');
        echo $this->Html->css(array('bootstrap.min.css', 'font-awesome.min.css', 'custom.css', 'development.css', 'bootstrap-datetimepicker.css', 'bootstrap-toggle.min.css', 'tokenfield-typeahead.min.css', 'bootstrap-tokenfield.min.css', 'bootstrap-multiselect.css')); //'easyTree.css',
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js')); //,'easyTree.js'));
        ?>
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
            var FILE_PATH = "<?php echo ABSOLUTE_URL . 'app/webroot/js/Filemanager/index.html' ?>";
            var globalSize = "<?php echo (Configure::read('GlobalSettings.FILE_UPLOAD_SIZE_MB') * 1024 * 1024) ?>";
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
                    <div class="successMessage">
                        <div id="flashMsg">
                            <?php echo $this->Flash->render(); ?>
                            <a class="noty-remove" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </div>
                    </div>  


                    <div class="loader" style="display:none;" id="loaderImg">
                        <img src="<?php echo $params['imgURL']; ?>/Loader.gif"/>
                    </div> 
                    <?php echo $this->fetch('content'); ?>
                </div>
                <!-- /page content -->

                <!-- footer content -->
                <footer>
                    <div class="pull-right">
                        Talentedge
                    </div>
                    <div class="clearfix"></div>

                </footer>
                <!-- /footer content -->

            </div> <!-- /main container -->
        </div> <!-- /body container -->
        <!-- jQuery -->
        <?php echo $this->Html->script(array('custom.js', 'validation.js', 'moment.min.js', 'bootstrap-datetimepicker.min.js', 'bootstrap-toggle.min.js', 'photo-gallery.js', 'bootstrap-multiselect.js')); ?>
        <script type="text/javascript">
            $(function () {
                var opts = {
                    format: "YYYY-MM-DD",
                    stepping: 5,
                    maxDate: new Date(),
                    //minDate: moment.utc(),
                    useCurrent: true,
                    sideBySide: false,
                    showTodayButton: false,
                    widgetPositioning: {
                        'vertical': 'bottom'
                    },
                    ignoreReadonly: true,
                };
                $('#dob').datetimepicker(opts);

            });
        </script>

        <script>
			function changecontroler(cn){
				var controllerName = cn;
				alert(controllerName);
			}
			/*load compose box from out side*/
			function loadExternalCompose(batch_id,user_id)
			{
				$.ajax({
					url: "<?php echo $this->webroot;?>Messages/compose",
					type: "post",
					data: {'type':'external_auto','batch_id':batch_id,'user_id':user_id},
					beforeSend: function(msg){
						$("#loaderImg").show();
					},
					success: function (data) {
						$("#loaderImg").hide();
						//var json = JSON.parse(data);
						$('#composeMsgDiv').html(data);
						$('.compose').slideToggle();
						/*$('#compose, .compose-close').click(function(){
							$('.compose').slideToggle();
						  });*/
					},
					error: function(jqXHR, textStatus, errorThrown) {
					   console.log(textStatus, errorThrown);
					}
				});                 
			}
			
            /*load message on every 30 seconds*/
            function loadMessageNotification()
            {
                /*$.ajax({
                    url: "<?php echo $this->webroot; ?>Messages/topMessage",
                    type: "post",
                    data: {},
                    success: function (response) {
                        $('#topMessageSpace').html(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });*/

                $.ajax({
                    url: "<?php echo $this->webroot; ?>Notifications/topNotification",
                    type: "post",
                    data: {},
                    success: function (response) {
                        $('#topNotification').html(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            }

            loadMessageNotification();
            setInterval(function () {
                loadMessageNotification();
            }, 30 * 1000); // 60 * 1000 milsec

        </script>

        <script>

            $(document).ready(function ()
            {
                var msgval = $("#flashMessage").text();
                var trimtxt = msgval.trim();
                var count = trimtxt.length;
                if (count > 0)
                {
                    $('.successMessage').show();
                } else
                {
                    $('.successMessage').hide();
                }
            });
            $('.noty-remove').click(function () {
                $('.successMessage').hide();
            });
        </script>

        <?php //echo $this->Html->script(array('ckeditor/ckeditor.js')); ?>
        <?php echo $this->Html->script(array('content-log.js')); ?>

        <!-- Large modal -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel"><?php if(isset($batch['Batch']['name'])){ echo $batch['Batch']['name']; } ?></h4>
                    </div>
                    <?php echo $this->Form->create('BatchModule', array('class' => 'form-horizontal form-label-left')); ?>  
                    <div class="modal-body">
						<div class="form-group">
                        <label style="margin-bottom:10px;">Please select a Module</label>
                        <?php echo $this->Form->hidden('attach', array('value' => '')); ?>
                        <?php if(!isset($batchModulesList)){ $batchModulesList=[];} echo $this->Form->input('moduleId', array('options' => $batchModulesList, 'empty' => 'Choose one', 'required' => false, 'label' => false, 'class' => 'form-control')); ?>
						<br/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Proceed</button>
                    </div>
                    <?php echo $this->Form->end(); ?>  
                </div>
            </div>
        </div>
        <div class="modal fade bs-example-modal-view-testimonial" tabindex="-1" role="dialog" aria-hidden="true" id='viewTesti'></div>
        <div class="modal fade bs-example-modal-view-Question" tabindex="-1" role="dialog" aria-hidden="true" id='viewTestQ'></div>
        <div class="modal fade bs-example-modal-view-attendance" tabindex="-1" role="dialog" aria-hidden="true" id='viewLCAttendance'></div>
        <div class="modal fade bs-example-modal-view-online-users" tabindex="-1" role="dialog" aria-hidden="true" id='viewOnlineUsers'></div>
        <div id='composeMsgDiv'></div>
    </body>
</html>
<?php  //echo  $this->element('sql_dump');   ?>
