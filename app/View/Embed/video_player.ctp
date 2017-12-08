<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Talentedge Application  </title>
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimal-ui">	
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <?php
        $goToPage = 0;
        echo $this->Html->css(array('media.min.css'));
        echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.ui.touch-punch.js'));
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
        <script>
            (function (doc) {
                var addEvent = 'addEventListener', type = 'gesturestart', qsa = 'querySelectorAll', scales = [1, 1], meta = qsa in doc ? doc[qsa]('meta[name=viewport]') : [];
                function fix() {
                    meta.content = 'width=device-width,minimum-scale=' + scales[0] + ',maximum-scale=' + scales[1];
                    doc.removeEventListener(type, fix, true);
                }
                if ((meta = meta[meta.length - 1]) && addEvent in doc) {
                    fix();
                    scales = [.25, 1.6];
                    doc[addEvent](type, fix, true);
                }
            }(document));
        </script>
        <style>
            html, body {
                min-height: 100% !important;
                height: 100%;
                margin-top: 0px; 
                margin-bottom: 0px; 
                margin-left: 0px; 
                margin-right: 0px;
                padding: 0;
                border:0px solid black;
            }
        </style>
    </head>
    <body>
        <?php
        if (isset($this->request->query['id']) && empty($videoUrl)) {
            echo '<div>Video not available.</div>';
        } else {
            if (empty($videoUrl)) {
                $videoUrl = "http://staging.talentedge.in/dev/files/video/2016-11-21-132400videoplayback.mp4";
                echo $this->element('video-player', array('src' => $videoUrl));
            } else if (strpos($videoUrl, "https://www.youtube.com/") !== false) {
                   echo $this->element('video-player', array('src' => $videoUrl,'mediaType' => 'html'));
            }else if (strstr($videoUrl, 'index.html')) {
                ?>
                <iframe style="height: 99vh;width: 99vw;" id="videoplayer" src="<?php echo $videoUrl; ?>" frameborder="0" allowfullscreen></iframe><?php
                } else {
                    echo $this->element('video-player', array('src' => $videoUrl));
                }
            }
            ?>
    </body>
</html>
