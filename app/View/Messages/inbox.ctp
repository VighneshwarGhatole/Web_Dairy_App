<?php echo $this->Html->css(array('bootstrap-select.min.css'));?>
<div class="page-header">
<div class="page-title">
  <div class="title_left">
		<div class="com-profile">
			<div class="com-pic">
				
			</div>
			<div class="com-detail">
				
				<ol class="breadcrumb">
					<li><?php echo ($isSRM)?$this->Html->link('Home','/SRM/Dashboard'):$this->Html->link('Home','/Users/Dashboard');?></li>
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
			<li role="presentation" class="active"><a href="#Tab_inbox" role="tab" data-toggle="tab" aria-expanded="true">Inbox</a>
			</li>
			<li role="presentation"><?php echo $this->Html->link(
						'Sent',
						'/Messages/sent',
						array()
					);?>  
			</li>                     
		  </ul>
		  <div id="myTabContent" class="tab-content grybg">
			<div role="tabpanel" class="tab-pane fade active in" id="Tab_inbox" aria-labelledby="home-tab">
				<div class="row">
					<div class="col-xs-12 col-md-4">
						<div class="discussion-ttl inbx">
							<i class="fa fa-inbox" aria-hidden="true"></i>
							Messages <!--<span>(Showing 4 of 48)</span>  -->                                
															   
						</div>	
										
					</div>
					<?php echo $this->Form->create(false, array(
						'url' => array('controller' => 'Messages', 'action' => 'deletemsg'),
						'id' => 'listForm'
					));?>
					
					<div class="col-xs-12 col-md-8">
						<div class="pull-right btn-mrgn">
							<div class="slt-bx">
							<?php echo $this->Form->input('batches', array('label'=>false,'id'=>'listFilterBatch','options' => $AllBatches,'selected'=>$batch_id,'empty' => '--All batches--','class'=>'selectpicker show-tick form-control'));?>
							</div>
							
							<span class="button-checkbox">
								<button type="button" class="btn mgrn-right" data-color="primary">Show Only Doubt</button>
                                <input type="checkbox" <?php echo $is_doubt?'checked':'';?> id="is_doubt_filter" class="hidden" name="is_doubt"/>
								
							</span>
							<?php
								if(empty($listdata)) {
									$disabled = "disabled";
								} else {
									$disabled = '';
								}
							?>
							<button type="submit" class="btn btn-box mgrn-right" <?php echo $disabled;?>> Delete</button>
							<?php //if(!$isSRM){?>
							<button id="compose" type="button" onclick="loadCompose()" class="btn btn-primary"><i class="fa fa-pencil-square" aria-hidden="true"></i> Compose</button>
							<?php //}?>  
						</div>
					</div>

				   <div class="col-xs-12 col-md-12" id="msgrow">   
					    <?php echo $this->Form->hidden('type',array('value'=>'inbox'));?> 
					   <?php if(!empty($listdata)){
						   foreach($listdata as $data){ ?>                                
					   <div class="inbx-list <?php echo $data['Message']['is_read']?'':'un-read'?>" >
							<div class="input-chk"><input type="checkbox" id="msgs" name="msg[]" value="<?php echo $data['Message']['id'];?>">
							</div>
							<div class="mail-input" onclick="window.location='<?php echo $this->Html->url(array("controller" => "Messages","action" => "view",$data['Message']['id']));?>'">
							<div class="col-xs-12 col-md-2">								
								<?php echo $data['User']['name'];?>
							</div>
							
							<div class="col-xs-12 col-md-8" style="cursor:pointer"  >
								<div class="sender"><?php echo substr($data['Message']['subject'],0,15);?> <span class="msg">- <?php echo substr($data['Message']['message'],0,100);?></span>
								</div>
								
							</div>
							<div class="col-xs-12 col-md-2">   
								<?php if($data['Message']['attach']){?>
								<div class="attach-icn"><i class="fa fa-paperclip" aria-hidden="true"></i></div>
								<?php }?>                                         
								<span class="pull-right">
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
					   <?php 
							}
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
					<div class="col-xs-12 col-md-12">						
						<?php echo $this->element('pagination'); ?>
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
<?php //pr($AllBatches);?>


<!-- compose -->
<script>	
$('#listForm').submit(function() {
	var cnt = $("#msgrow :checkbox:checked").length;
	if(cnt==0){
		
		if($('#flashMessage').html()){
			$('#flashMessage').html('Please select message to delete');
		}else{
		$('#flashMsg').append('<div id="flashMessage">Please select message to delete</div>');
		}
		$('.successMessage').show();
		return false;
	}
	return confirm("Are you sure you want to delete " + cnt + " message(s)");
  });
  
  
  function loadCompose()
    {
		$.ajax({
            url: "<?php echo $this->webroot;?>Messages/compose",
            type: "post",
            data: {'type':'<?php echo ($isSRM)?'srm':'external'?>'} ,
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
  
  
  
  function textAreaAdjust(o) {
		o.style.height = "1px";
		o.style.height = (25+o.scrollHeight)+"px";
	}               
</script>


<?php echo $this->Html->script(array('bootstrap-select.js'));?>

    <script>
        $(function () {
    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-box')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-box');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
    
    $("#listFilterBatch").on("change", function (event) {
		if(document.getElementById('is_doubt_filter').checked){
			window.location= "<?php echo $this->webroot;?>Messages/inbox/isDoubt:1/batchId:"+$("#listFilterBatch").val();
		}else{
			window.location= "<?php echo $this->webroot;?>Messages/inbox/batchId:"+$("#listFilterBatch").val();
		}
	});
	$('#is_doubt_filter').change(function(){
		if(document.getElementById('is_doubt_filter').checked){
			window.location= "<?php echo $this->webroot;?>Messages/inbox/isDoubt:1/batchId:"+$("#listFilterBatch").val();
		}else{
			window.location= "<?php echo $this->webroot;?>Messages/inbox/batchId:"+$("#listFilterBatch").val();
		}
	});
});

    </script>
  <?php //echo $this->element('sql_dump');?>
  
<!-- /compose -->  
