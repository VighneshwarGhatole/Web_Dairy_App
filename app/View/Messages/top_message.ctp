<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">                    
<i class="fa fa-envelope"></i>
<span class="badge bg-red"><?php echo $unread;?></span>
</a>
<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
<?php foreach($list as $data){?>
<li>
  <a href="<?php echo $this->Html->url(array("controller" => "Messages","action" => "view",$data['Message']['id']));?>">
	<span class="image"><img src="<?php echo ( $data['User']['pic'] == '') ? $params['imgURL'].'/img.jpg' : ASSETS_BASE_URL.$data['User']['pic']; ?>" alt="Profile Image" /></span>
	<span>
	  <span> <?php echo $data['User']['name'];?></span>
	  <span class="time"><?php $time = strtotime($data['Message']['created']);
							echo $this->timeconvertion->humanTiming($time).' ago';
						?></span>
	</span>
	<span class="message">
		<?php echo substr($data['Message']['message'],0,100);?>...
	  <?php //echo $data['Message']['message'];?>
	</span>
  </a>
</li>
<?php }?>
<li>
  <div class="text-center">
	<a href="<?php echo $this->Html->url(array("controller" => "Messages","action" => "inbox"));?>">
		
	  <strong>See All Messages</strong>
	  <i class="fa fa-angle-right"></i>
	</a>
  </div>
</li>
</ul>
<?php //echo $this->element('sql_dump');?>


