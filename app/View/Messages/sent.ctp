<div class="page-header">
<div class="page-title">
  <div class="title_left">
		<div class="com-profile">
			<div class="com-pic">
				
			</div>
			<div class="com-detail">
				
				<ol class="breadcrumb">
					<li><?php echo $this->Html->link('Home','/Users/Dashboard');?></li>
					<li class="active">Messages</li>
				</ol>
			</div>                        
		</div>                  
  </div>
</div>

<div class="clearfix"></div>

	<div class="x_panel flt-left">
	  <div class="x_content">                      
		<div class="" role="tabpanel" data-example-id="togglable-tabs">
		  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
			<li role="presentation" class="">
				<?php echo $this->Html->link(
						'Inbox',
						'/Messages/inbox',
						array()
					);?>
				
			</li>
			<li role="presentation" class="active"><a href="#Tab_Sent" role="tab" data-toggle="tab" aria-expanded="true">Sent</a>
			</li>                       
		  </ul>
		  <div id="myTabContent" class="tab-content grybg">
			<div role="tabpanel" class="tab-pane fade" id="Tab_inbox" aria-labelledby="home-tab">
			</div>
			<div role="tabpanel" class="tab-pane fade active in" id="Tab_Sent" aria-labelledby="profile-tab">
			  
			  <div class="row">
					<div class="col-xs-12 col-md-6">
						<div class="discussion-ttl inbx">
							<i class="fa fa-inbox" aria-hidden="true"></i>
							Messages <!--<span>(Showing 4 of 48)</span>  -->                                
															   
						</div>
					</div>
										
<?php echo $this->Form->create(false, array(
    'url' => array('controller' => 'Messages', 'action' => 'deletemsg'),
    'id' => 'listForm'
));
if(empty($listdata)) {
	$disabled = "disabled";
} else {
	$disabled = '';
}
?>
					<div class="col-xs-12 col-md-6">
						<div class="pull-right btn-mrgn">
							<button type="submit" class="btn btn-box mgrn-right" <?php echo $disabled;?>> Delete</button>
							
						</div>
					</div>

				   <div class="col-xs-12 col-md-12">  
					   <?php echo $this->Form->hidden('type',array('value'=>'sent'));?> 
					   <?php if(!empty($listdata)){ 
						   foreach($listdata as $data){ ?>                                
					   <div class="inbx-list <?php //echo $data['Message']['is_read']?'':'un-read'?>">
							<div class="input-chk"><input type="checkbox" id="msgs" name="msg[]" value="<?php echo $data['Message']['id'];?>">
							</div>
							<div class="mail-input" onclick="window.location='<?php echo $this->Html->url(array("controller" => "Messages","action" => "sentview",$data['Message']['id']));?>'">
							<div class="col-xs-12 col-md-2">
							<?php echo $data['User']['name'];if($data['Message']['count']>0){ echo '+'.$data['Message']['count'];};?>	
							</div>
							<div class="col-xs-12 col-md-8" style="cursor:pointer" >
								<div class="sender"><?php echo substr($data['Message']['subject'],0,15);?> <span class="msg">- <?php echo substr($data['Message']['message'],0,100);?></span>
								</div>
								
							</div>
							<div class="col-xs-12 col-md-2">  
								<?php if($data['Message']['attach']){?>
								<div class="attach-icn"><i class="fa fa-paperclip" aria-hidden="true"></i></div>
								<?php }?>                                          
								<span class="pull-right">
									<?php //echo date('d/m/Y',strtotime($data['Message']['created']));?>
									<?php 
										$msddate = explode(" ", $data['Message']['created']);
										if($msddate[0] == date('Y-m-d')){
											echo date('h:i a', strtotime($data['Message']['created']));
										}else if(date('Y') == date('Y', strtotime($data['Message']['created']))){
											echo date('M d',strtotime($data['Message']['created']));
										}else{
											echo date('M d Y',strtotime($data['Message']['created']));
										}
									?>
								</span>           
							</div>
							</div>
					   </div>
					   <?php }
					   }//If data found
						else {?>
							<div class="panel openpanel md-planer bdr">
								<div class="row">
									<div class="col-xs-12 col-md-12">
										<div class="sec-activity lndscreen">
											<div class="col-xs-2 col-md-12">No messages.</div>

										</div>
									</div>                                       
								</div>
							</div><?php							
						}
						?>
					  </div>
					  </form>
				</div>
			
			  
			</div>                        
		  </div>
		</div>
		  
	  </div>
	</div>

</div>

<?php echo $this->element('pagination'); ?>

<script>
 $('#listForm').submit(function() {
   return confirm("Click OK to continue?");
  });
  
  function textAreaAdjust(o) {
		o.style.height = "1px";
		o.style.height = (25+o.scrollHeight)+"px";
	}               
</script>

<script>
	 var formNameNew = "MessageInboxForm";
	
</script>
<script>
// Initialize multiselect plugin:
$(document).ready(function() {        
		$('#AddStudent').multiselect({
		includeSelectAllOption: true,
		numberDisplayed: 6,
		nonSelectedText: 'To',
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true
	});        
});
</script>
    
<!-- /compose -->  
<?php //echo $this->element('sql_dump');?>
