<div class="page-header">
<div class="page-title">
  <div class="title_left">
		<div class="com-profile">
			<div class="com-pic">
				
			</div>
			<div class="com-detail">
				
				<ol class="breadcrumb">
					<li><a href="#">Home</a></li>
					<li class="active">Module Listing</li>
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
			<li role="presentation" ><a href="<?php echo $this->Html->url(array('controller' => 'Messages', 'action' => 'inbox'));?>" style="cursor:pointer">Inbox</a>
			</li>
			<li role="presentation" class="active"><a href="<?php echo $this->Html->url(array('controller' => 'Messages', 'action' => 'sent'));?>" role="tab" style="cursor:pointer">Sent</a>
			</li>                   
		  </ul>
		  <div id="myTabContent" class="tab-content grybg">
			<div role="tabpanel" class="tab-pane fade active in" id="Tab_inbox" aria-labelledby="home-tab">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<div class="discussion-ttl inbx">
							<i class="fa fa-inbox" aria-hidden="true"></i>
							<?php echo $listdata[0]['Message']['subject'];?>                                                                  
						</div>
					</div>
					<div class="col-xs-12 col-md-6">
						<div class="pull-right btn-mrgn">                                        
							<a href="<?php echo $this->Html->url($backurl);?>"><button type="button" class="btn btn-box"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back</button></a>
							<!--<button id="compose" type="submit" class="btn btn-primary mgrn-right"> Reply</button>-->
						</div>
					</div>

				   <div class="col-xs-12 col-md-12">
					   <ul class="reply-list">
							
							<li class="closed active">                                        
								 <div class="col-xs-12 col-md-6">
									 <div class="sender">
									<?php 
									$str = '';
									 foreach ($grouplist as $list){
									 $str .= ' '.$list['User']['name'].',';
									 } 
									 echo rtrim($str,',');
									 ?>
									 </div>                                                                                             
								 </div>
								<div class="col-xs-12 col-md-6">                                            
									 <span class="pull-right"><?php
									 $time = strtotime($listdata[0]['Message']['created']);
									 echo date('M d Y - h:i a',$time).'&nbsp;&nbsp; <i class="fa fa-hand-o-right" aria-hidden="true"></i> '.$this->timeconvertion->humanTiming($time).' ago';
									 
									  ?></span>           
								 </div>
								<div class="col-xs-12 col-md-12">                                            
									 <div class="msg"><?php echo $listdata[0]['Message']['message'];?>
									 </div>          
								 </div>
								<div class="col-xs-12 col-md-12">
									
											<?php 
												//FILE_PATH
												if(!empty($listdata[0]['Message']['attach'])){
													
													echo '<div class="attachment-pnl">
										<h2>Attachments <i aria-hidden="true" class="fa fa-paperclip"></i><!--<a href="#" class="download-bt" data-toggle="tooltip" data-placement="top" title="" data-original-title="Download All"><i class="glyphicon glyphicon-download"></i></a>--></h2>                                        
										<ul>';
													
												$files = explode(',',$listdata[0]['Message']['attach']);
												foreach($files as $file){
													$name = substr($file,strrpos($file,"/")+1);?>
													<li><a onclick="location.href='<?php echo $this->webroot.'Messages/download?file='.$file?>'" href="#"><?php echo $name;?></a><?php echo " -".round(filesize(FILE_PATH.$file)/1024)."KB";?></li><?php
													
													//echo "<li>".$this->Html->link($name,'/Messages/download?file='.$file)." -".round(filesize(FILE_PATH.$file)/1024)."KB </li>";
													
												}
											echo '</ul>
									</div>';
											}
											?>
										
								</div>
							</li>
					   
					   <?php foreach($parentdata as $data){ 
						   if($data['Message']['id'] == $listdata[0]['Message']['id'])
						   continue;
						   if($this->Session->read('UserDetails.id')==$data['Message']['to_deleted_id'] || $this->Session->read('UserDetails.id')==$data['Message']['from_deleted_id'])
						    continue;
						   ?> 
							<li class="closed">                                        
								<div class="col-xs-12 col-md-11">
									 <div class="sender"><?php echo $data['User']['fname'].' '.$data['User']['lname'];?>
										<?php if(!empty($data['Message']['attach'])){?>
										 <div class="attach-icn"><i class="fa fa-paperclip" aria-hidden="true"></i></div>
										<?php }?>
									 </div>
									 
								 </div>
								<div class="col-xs-12 col-md-1">                                            
									 <span class="pull-right"><?php echo date('d/m/Y',strtotime($data['Message']['created']));?></span>           
								 </div>
								<div class="col-xs-12 col-md-12">                                            
									 <div class="msg"><?php echo $data['Message']['message']?>
									 </div>          
								 </div>
								 <div class="col-xs-12 col-md-12">
									
											<?php 
												//FILE_PATH
												if(!empty($data['Message']['attach'])){
													
													echo '<div class="attachment-pnl">
										<h2>Attachments <i aria-hidden="true" class="fa fa-paperclip"></i><!--<a href="#" class="download-bt" data-toggle="tooltip" data-placement="top" title="" data-original-title="Download All"><i class="glyphicon glyphicon-download"></i></a>--></h2>                                        
										<ul>';
													
												$files = explode(',',$data['Message']['attach']);
												foreach($files as $file){
													$name = substr($file,strrpos($file,"/")+1);?>
													<li><a onclick="location.href='<?php echo $this->webroot.'Messages/download?file='.$file?>'" href="#"><?php echo $name?></a><?php echo " -".round(filesize(FILE_PATH.$file)/1024)."KB";?></li><?php
													
													//echo "<li>".$this->Html->link($name,'/Messages/download?file='.$file)." -".round(filesize(FILE_PATH.$file)/1024)."KB </li>";
													
												}
											echo '</ul>
									</div>';
											}
											?>
										
								</div>
							</li>
						<?php }?>
					   </ul>
				   </div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="Tab_Sent" aria-labelledby="profile-tab">
			  Sent
			</div>                        
		  </div>
		</div>
		  
	  </div>
	</div>

</div>
<!-- compose -->
<div class="compose col-md-5 col-xs-12">
  <div class="compose-header">
	New Message
	<button type="button" class="close compose-close">
	  <span>×</span>
	</button>
  </div>
<?php echo $this->Form->create('Message',array('class' => 'form-horizontal form-label-left','enctype' => 'multipart/form-data','inputDefaults' => array(
                                                'label' => false,
                                                'required'=>false,
                                                'error' => array('attributes' => array('wrap' => 'div', 'class' => 'help-inline redDiv'))
                                                ))); ?>
  <div class="compose-body" style="display:block;">          
	  <div class="lgn-frm">
		
			<div class="form-group">                       
			  <div class="multsl" id="to_id">
				  <?php $AllUser=array($listdata[0]['Message']['from_id']=>$listdata[0]['User']['name']);?>
				  <?php echo $this->Form->input('to_id', array("disabled"=>true,'multiple'=>"multiple",'id'=>'AddStudent','options' => $AllUser,'selected'=>$listdata[0]['Message']['from_id']));?>                 
				
			</div>
			</div>
			<div class="form-group">    
				<?php echo $this->Form->input('subject',array('id'=>'subject','class'=>'form-control','value'=>$listdata[0]['Message']['subject']));?>
				<?php echo $this->Form->hidden('parent_id',array('class'=>'form-control','value'=>$listdata[0]['Message']['id']));?>
				<?php echo $this->Form->hidden('to_id',array('class'=>'form-control','value'=>$listdata[0]['Message']['from_id']));?>
			</div>
			<div class="form-group">
				<?php echo $this->Form->input('message',array('id'=>'message',"onkeyup"=>"textAreaAdjust(this)",'style'=>'overflow:hidden','Placeholder'=>'Message text here..','rows'=>"5"));?>
				
			</div>
			<div class="attached-files posabsolute" id="attachlist">
				
			</div>
			
		
	</div>
	  
  </div>

  <div class="compose-footer">
	  
	  <span class="attach-file"> 
			
		   <button type="submit" class="btn btn-default"><i class="fa fa-paperclip" aria-hidden="true"></i> Attachment</button>
		  <?php echo $this->Form->file('file.', array('required'=>false,'class'=>'btn btn-default','type'=>'file','multiple'=>'multiple'));?>
		  
	  </span>        
	<button id="send" class="btn btn-sm btn-primary validateForm" type="submit">Send</button>
	<?php echo $this->Form->input('is_doubt',array('label'=>'Is doubt'));?>
  </div>
  </form>
</div>
 <!-- compose -->
<script>
 $('#compose, .compose-close').click(function(){
	$('.compose').slideToggle();
  });
  $('.reply-list .closed').click(function(e){                
        $(this).parent().find('li.open').addClass("closed");
        $(this).parent().find('li.open').removeClass("open");
        $(this).parent().find('li').removeClass("active");
        $(this).removeClass('closed');
        $(this).addClass("open");
        //e.preventDefault();
      });
  function textAreaAdjust(o) {
		o.style.height = "1px";
		o.style.height = (25+o.scrollHeight)+"px";
	}              
</script>
    
<script>
	 var formNameNew = "MessageViewForm";
	var validatedFiles = [];
	$("#MessageFile").on("change", function (event) {
		$('#attachlist').html("");		
		
		var files = event.originalEvent.target.files;		
		if(files.length > <?php echo Configure::Read('GlobalSettings.MAX_NUMBER_OF_FILE_UPLOAD');?>){
			//event.preventDefault();
			//alert('Can not attach more than 3 files.');
			$('#attachlist').append('<div id="attachlistError" style="color: red;text-align: right;"> Can not attach more than 3 files.</div>');
		}else{
		$.each(files,function(idx,elm){
			var sizeerror='';
			if(elm.size > <?php echo (Configure::Read('GlobalSettings.FILE_UPLOAD_SIZE_MB')*1024*1024);?>){				
				sizeerror = '<div id="attachlistError" style="color: red;text-align: right;"> File size exceeding.</div>'
			}
			validatedFiles[idx]=elm;
            console.log(elm);
            
            $('#attachlist').append('<div class="attach-file-show"><div class="file-name"><a class="flname" href="#">' + elm.name + '</a> <span>' + Math.round(elm.size/1024)+ 'KB</span><span class="pull-right">' + sizeerror + '</span></div></div>');
        });
	 }
	});
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
