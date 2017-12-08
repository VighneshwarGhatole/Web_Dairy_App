<?php echo $this->Html->css(array('chart_custom.css', 'circle.css'));?>
<div class="page-header">
<?php echo ($batchId)?$this->element('Modules/page-top'):''; ?>
<div class="clearfix"></div>

	<div class="x_panel flt-left">
	  <div class="x_content">                      
		<div class="" role="tabpanel" data-example-id="togglable-tabs">
		 <?php echo ($batchId)?$this->element('Modules/tab-navigation', array('active' => 'Tab_Student')):''; ?>  
		  <div id="myTabContent" class="tab-content">
			<?php echo $this->element('Users/profile_body'); ?>
		  </div>
		</div>
		  
	  </div>
	</div>

</div>
