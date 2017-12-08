<?php // var_dump( $api); die();      ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>TalentEdge Application  </title>
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimal-ui">  
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
        <meta name="apple-mobile-web-app-capable" content="yes">

        <?php
        $goToPage = 0;
        echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js'));
        echo $this->Html->css(array(/* 'custom.css', */'supersized.css', 'supersized.shutter.css', 'circle.css'));
        ?>   
        <script language="javascript">
            var webURL = "<?php echo $params['webURL']; ?>";
            // alert(webURL);
            var imgURL = "<?php echo $params['imgURL']; ?>";
            var controllerName = "<?php echo $params['controller']; ?>";
            var actionName = "<?php echo $params['action']; ?>";
            var FILE_PATH = "<?php echo ABSOLUTE_URL . 'app/webroot/js/Filemanager/index.html' ?>";
            var globalSize = "<?php echo (Configure::read('GlobalSettings.FILE_UPLOAD_SIZE_MB') * 1024 * 1024) ?>";
            var modulePlannerContent = false;
        </script>
        <style>
            #supersized{
                top:0px; 
                padding:0px;
                overflow: hidden;
            }
            .pdfheight{
                min-height: 100%;
                position:relative;
                overflow-x: hidden;
                overflow-y: hidden;
                /*width: 100%;*/
            }
            .content-file{
                /*overflow: hidden;*/
                /*overflow: hidden;*/
                margin: 0px 0px;
                padding: 0px 0px; 
                height:1100px;
            }
            #supersized li.prevslide img, #supersized li.activeslide img{
                /*max-height:  none;*/
                /*max-width:   none;*/
            }
            #pdfplayerdiv{
                height: auto;
                width: auto;
            }
            .load-item, #supersized-loader  {
                position: fixed!important;
            }
            #progress-bar{
                display: none;
                height: 0;
            }
            #controls-wrapper{
                display: none;
            }
        </style>
    </head>
    <body>
        <div id="pdfplayerdiv" class="col-xs-12 col-md-12" style="margin: 0px 1px 0px 1px;">                        
            <div class="content-file" scrolling="no" >
                <div class="pdfheight" id="pdf-viewer"></div>   
                <?php if (!$api) { ?>
                    <!--Thumbnail Navigation-->
                    <div id="prevthumb"></div>
                    <div id="nextthumb"></div>

                    <!--Arrow Navigation-->
                    <a id="prevslide" class="load-item"></a>
                    <a id="nextslide" class="load-item"></a>

                    <!--        <div id="thumb-tray" class="load-item">
                                <div id="thumb-back"></div>
                                <div id="thumb-forward"></div>
                            </div>-->

                    <!--Time Bar-->
                    <!--        <div id="progress-back" class="load-item">
                                <div id="progress-bar"></div>
                            </div>-->

                    <!--Control Bar-->
                    <div id="controls-wrapper" class="load-item">
                        <div id="controls">
                            <!--<a id="play-button"><img id="pauseplay" src="<?php echo $params['imgURL']; ?>/pause.png"/></a>-->

                            <!--Slide counter-->
                            <div id="slidecounter">
                                <span class="slidenumber"></span> / <span class="totalslides"></span>
                            </div>

                            <!--Slide captions displayed here-->
                            <div id="slidecaption"></div>
                            <!--- full screen----->
                            <a style="float:right;" id="full-screen-button">
                                <img id="tray-arrow" src="<?php echo $params['imgURL']; ?>/button-tray-up.png"/>
                            </a>
                            <!--Thumb Tray button-->
                            <!--<a id="tray-button"><img id="tray-arrow" src="<?php echo $params['imgURL']; ?>/button-tray-up.png"/></a>-->


                            <!--Navigation-->
                            <ul id="slide-list"></ul>
                        </div>
                    </div>

                <?php } ?>
            </div>
        </div>
        <?php echo $this->Html->script(array('jquery-progresspiesvg-min.js', 'jquery.easing.min.js', 'supersized.3.2.7.js', 'supersized.shutter.min.js', 'screenfull.min.js')); ?>



        <script type="text/javascript">
            //    var slide1;
            var slides = [// Slideshow Images
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-1.jpg',
                    title: '',
                    id: '718'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-2.jpg',
                    title: '',
                    id: '719'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-3.jpg',
                    title: '',
                    id: '720'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-4.jpg',
                    title: '',
                    id: '721'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-5.jpg',
                    title: '',
                    id: '722'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-6.jpg',
                    title: '',
                    id: '723'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-7.jpg',
                    title: '',
                    id: '724'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-8.jpg',
                    title: '',
                    id: '725'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-9.jpg',
                    title: '',
                    id: '726'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-10.jpg',
                    title: '',
                    id: '727'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-11.jpg',
                    title: '',
                    id: '727'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-12.jpg',
                    title: '',
                    id: '729'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-13.jpg',
                    title: '',
                    id: '730'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-14.jpg',
                    title: '',
                    id: '731'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-15.jpg',
                    title: '',
                    id: '732'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-16.jpg',
                    title: '',
                    id: '733'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-17.jpg',
                    title: '',
                    id: '734'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-18.jpg',
                    title: '',
                    id: '735'
                },
                {image: 'https://sliq.talentedge.in/files/MDLZ/MDLZ-_GST_Distribution_awareness_updatedV2-19.jpg',
                    title: '',
                    id: '736'
                }
            ];

            function loadSlider(slides) {
                jQuery(function ($) {

                    $.supersized({
                        // Functionality
                        slideshow: 1, // Slideshow on/off
                        autoplay: 0, // Slideshow starts playing automatically
                        start_slide: 1, // Start slide (0 is random)
                        stop_loop: 0, // Pauses slideshow on last slide
                        random: 0, // Randomize slide order (Ignores start slide)
                        slide_interval: 3000, // Length between transitions
                        transition: 6, // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
                        transition_speed: 500, // Speed of transition
                        new_window: 1, // Image links open in new window/tab
                        pause_hover: 0, // Pause slideshow on hover
                        keyboard_nav: 0, // Keyboard navigation on/off
                        performance: 1, // 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
                        image_protect: 3, // Disables image dragging and right click with Javascript

                        // Size & Position                         
                        min_width: 0, // Min width allowed (in pixels)
                        min_height: 0, // Min height allowed (in pixels)
                        vertical_center: 1, // Vertically center background
                        horizontal_center: 1, // Horizontally center background
                        fit_always: 0, // Image will never exceed browser width or height (Ignores min. dimensions)
                        fit_portrait: 1, // Portrait images will not exceed browser height
                        fit_landscape: 0, // Landscape images will not exceed browser width

                        // Components                           
                        slide_links: 'blank', // Individual links for each slide (Options: false, 'num', 'name', 'blank')
                        thumb_links: 0, // Individual thumb links for each slide
                        thumbnail_navigation: 0, // Thumbnail navigation
                        slides: slides,
                        // Theme Options               
                        progress_bar: 0, // Timer for each slide                            
                        mouse_scrub: 0

                    });
                });
            }
            $('#full-screen-button').click(function () {
                if (screenfull.enabled) {
                    screenfull.toggle();
                }
                //        screenfull.request();
            });

            $('#slide-list>li>a, .load-item').on('click', function () {

                $('html, body').animate({
                    scrollTop: $("#pdf-viewer").offset().top - 20
                }, 'fast');
            });
        </script>

        <script>

            //iframe script
            $(document).ready(function () {
<?php if ($api) { ?>
//                    window.addEventListener('message', receiveMessage, false);
                    $(window).on('message', receiveMessage);
                    function receiveMessage(e) {

                        //  if (typeof e.originalEvent != 'undefined' && e.originalEvent.origin == 'http://localhost' ) {
                        if (typeof e.originalEvent != 'undefined') {
                            var option = e.originalEvent.data;
                            //        slides = option.slides;
                            //        preload(slides);
                            if(option.action=='load'){
                              loadSlider(slides);
                            }else{
                            
                            //        api.start_slide = option.page;
                            //        console.log(api.goTo(option.page));
                            console.log("<<<<<=======Recived======>>>>>");
                            console.log(e.originalEvent.data);
                            if(option.action=='fullscreen'){
                               if (screenfull.enabled) {
                                    screenfull.toggle();
                                }
                            }
                            
                            if(option.action=='next'){
                                api.nextSlide();
                            }
                            if(option.action=='previous'){
                                api.prevSlide();
                            }
                            if(option.action=='goto'){
                                api.goTo(option.page);
                            }
                            gotpdfpageTop();
                         }
                        } else {
                            console.log(e.originalEvent);
                            console.log('origin not allowed');
                        }
                    }

                    function preload(arrayOfImages) {
                        $(arrayOfImages).each(function () {
                            $('<img />').attr('src', this.image).appendTo('body').css('display', 'none');
                        });
                    }

                <?php } else { ?>
                    loadSlider(slides);
                <?php } ?>

            });
            
            function gotpdfpageTop(){
                $('html, body').animate({
                    scrollTop: $("#pdf-viewer").offset().top - 20
                }, 'fast');
            }
        </script>

    </body>
</html>
