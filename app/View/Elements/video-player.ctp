<?php  echo $this->Html->css(array('media.min.css')); ?>
<div id="vidHolder" style="display:block; background:red; width:100%; height:100%; position: relative;" >
    <!--  @Media information -->
    <div id="vid-player" class="vid-player-box"></div>
</div>
<?php echo $this->Html->script(array('jquery.script.min.js')); ?>
<script>
    var parmas = {file: "<?php echo $src; ?>", image: "../../img/default_player_screen.jpg", autostart: true, forcestart: true, 'mediaType': "<?php if(isset($mediaType)){  echo $mediaType; }?>" };
    $(document).ready(function () {
        PlayerInit(parmas);
    });
</script>