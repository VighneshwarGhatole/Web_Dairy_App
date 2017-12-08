<div class="page-header">
    <div class="page-title nt-tab">
        <div class="title_left">
			<div class="com-profile">
				<div class="com-pic sm-spc">
					<div class="icn-circle"><i class="fa fa-users" aria-hidden="true"></i></div>
				</div>
				<div class="com-detail">
					<h3><a href="#"><span><?php echo __('Users'); ?></span></a></h3>
					<ol class="breadcrumb">
						<li><a href="<?php echo $this->webroot;?>Admin/dashboard">Home</a></li>
						<li class="active">Users Listing</li>
					</ol>
				</div>                        
			</div>
        </div>

        <?php echo $this->Form->create('User', array('type' => 'get','url' => '/Users/index')); ?>  
        <div class="title_right">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group pull-right top_search">
                <div class="input-group">
                    <!--<input type="text" class="form-control" placeholder="Search Users">-->
                    <?php echo $this->Form->input('name', array(
                        'maxlength' => 100, 
                        'class' => "form-control",
                        'label' => false, 
                        'div' => false, 
                        'placeholder'=>'Search Users...', 
                        'default' => $this->request->query('name')) ); ?>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Go!</button>
                    </span>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel flt-left">
                <div class="x_content">
					<div class="col-xs-12 col-md-12">						  
						  <div class="pull-right btn-mrgn addnlist"> 							                                    
							<?php echo $this->Html->link(__('<i class="fa fa-plus-circle" aria-hidden="true"></i> <span>ADD NEW</span>'), array('action' => 'add'), array('escape' => false, 'class'=>'addnew')); ?>
						  </div>
					</div>				
					<div class="col-xs-12 col-md-12">
                       <div class="tbl-list">
							<div class="table-responsive">
								<table class="table table-striped table-bordered jambo_table ">
									<thead>
										<tr class="headings">
											<th class="column-title"><?php echo $this->Paginator->sort('username','Username&nbsp;<i class="fa fa-sort" aria-hidden="true"></i>', ['escape' => false]); ?> </th>
											<th class="column-title"><?php echo $this->Paginator->sort('name','Name&nbsp;<i class="fa fa-sort" aria-hidden="true"></i>', ['escape' => false]); ?> </th>
											<th class="column-title"><?php echo $this->Paginator->sort('email','Email&nbsp;<i class="fa fa-sort" aria-hidden="true"></i>', ['escape' => false]); ?> </th>
											<!--<th class="column-title"><?php //echo $this->Paginator->sort('role_id','Type&nbsp;<i class="fa fa-sort" aria-hidden="true"></i>', ['escape' => false] ); ?></th> -->
											<th class="column-title"><?php echo $this->Paginator->sort('mobile_no','Mobile&nbsp;<i class="fa fa-sort" aria-hidden="true"></i>', ['escape' => false]); ?></th>
											<th class="column-title"><?php echo $this->Paginator->sort('status','status&nbsp;<i class="fa fa-sort" aria-hidden="true"></i>', ['escape' => false]); ?> </th>
											<th class="column-title last">Action</th>
										</tr>
									</thead>

									<tbody>
								<?php if(isset($Users) && count($Users)>0) { foreach ($Users as $User): ?>
										<tr class="even pointer">
											<td><?php echo h($User['User']['username']); ?>&nbsp;</td>
											<td><?php echo h($User['User']['name']); ?>&nbsp;</td>
											<td><?php echo h($User['User']['email']); ?></td>
											<!--<td><?php //if(isset($UserRoles[$User['UserRoleMapping']['role_id']]))echo h($UserRoles[$User['UserRoleMapping']['role_id']]); ?>&nbsp;</td>-->

											<td><?php echo h($User['User']['mobile_no']); ?></td>
											<td <?php if($User['User']['status']) echo 'class="GreenTxt"'; ?> ><?php if($User['User']['status']) echo h('Active'); else echo h('Inactive'); ?></td>
											<td class="last">
												<div class="btn-group pnl-setting">
													<a data-target="#" href="#" class="btn btn-default btn-xs" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
														<span class="glyphicon glyphicon-cog"></span>
													</a>
													<ul class="dropdown-menu" aria-labelledby="dLabel">
														<?php echo '<li>'.$this->Html->link(__('View/Edit'), array('action' => 'edit', $User['User']['id'])).'</li>'; ?>
														<?php if( $User['UserRoleMapping']['role_id']!=1 ) {  echo '<li>'.$this->Form->postLink(__('Delete'), array('action' => 'delete', $User['User']['id']), array('confirm' => __('All associated data will be deleted with this user.Are you sure you want to delete this user # %s?', $User['User']['id']))).'</li>'; } ?>
													</ul>
                                                </div>
											</td>
										</tr>
								<?php endforeach; 
										}else { 
								?>
										<tr class="even pointer"  >
											<td colspan="7" >No record found</td>
										<tr>
								<?php }?>                    
									</tbody>
								</table>
							</div>
                    <?php echo $this->element('pagination'); ?>
					</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
