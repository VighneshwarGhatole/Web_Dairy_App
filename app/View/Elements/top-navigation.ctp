<div class="nav_menu">
	<nav>
	  <div class="nav toggle">
		<a id="menu_toggle"><i class="fa fa-bars"></i></a>
	  </div>

	  <ul class="nav navbar-nav navbar-right">
		<li class="">
		  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			  <?php 
			  $actualImg = ( $this->Session->read('UserDetails.pic') == '') ? $params['imgURL'].'/img.jpg' : ASSETS_BASE_URL.$this->Session->read('UserDetails.pic');
			  $actualImg = $this->Common->showImage($actualImg, '150');			 
			  ?>
			<img src="<?php echo $actualImg;?>"><?php echo $this->Session->read('UserDetails.fname');?>
			<span class=" fa fa-angle-down"></span>
		  </a>
		  <ul class="dropdown-menu dropdown-usermenu pull-right">
			<?php if ($this->Session->read('UserDetails.roleId') != Configure::Read('UserRoles.ADMIN')) {?>
			<li><a href="<?php echo $this->html->url(array('controller'=>'Users','action'=>'profile','userId'=>$this->Session->read('UserDetails.id')));?>">Profile</a></li>
			<?php } ?>
			<li>
			  <a href="javascript:;">
				<span class="badge bg-red pull-right">50%</span>
				<span>Settings</span>
			  </a>
			</li>
			<li><a href="javascript:;">Help</a></li>
			<li><a href="<?php echo ABSOLUTE_URL;?>Users/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
		  </ul>
		</li>
		<!--<li role="presentation" class="dropdown" id="topMessageSpace">
		
		</li>-->

		<li role="presentation" class="dropdown" id="topNotification">

		</li>

		<!-- <li><a href="#"><i class="fa fa-search"></i></a></li> -->
	  </ul>
	</nav>
  </div>
