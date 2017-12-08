
<div class="page-header module-els-updated">   
    <div class="x_panel flt-left">
        <div class="x_content">                      
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <div id="myTabContent" class="tab-content grybg">
                    <div role="tabpanel" class="tab-pane fade active in" id="Tab_Modules" aria-labelledby="home-tab">
                        <div class="row md-planer">
                            <div class="col-xs-12 col-md-4">
                                <div class="discussion-ttl">
                                    <?php //echo $this->Html->image('icons/event-dark.png'); ?>Agent List
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6 pull-right">
                                <div class="pull-right btn-mrgn">                                      
                                    <a title="Add New Agent" class="addnew" href="<?php echo $this->webroot; ?>Users/add"><i class="fa fa-plus-circle" aria-hidden="true"></i> <span>ADD NEW</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- start accordion -->
                        <div class="panel openpanel md-planer bdr">                                
				            <div class="row">
				                <div class="col-xs-12 col-md-12">
				                	<?php echo $this->Element('Agentlist/list'); ?>
 								 </div>
				            </div>
                        </div>
                        <!--end of accordion -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
