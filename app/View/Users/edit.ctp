<div class="page-header">
    <div class="page-title nt-tab">
        <div class="title_left">
            <div class="com-profile">
                <div class="com-pic sm-spc">
                    <div class="icn-circle"><i class="fa fa-users" aria-hidden="true"></i></div>
                </div>
                <div class="com-detail">
                    <h3><a href="javascript:void(0)"><span>Edit <?php echo (($this->request->data['User']['role']==2) ? "Manager" : "Agent"); ?> Info</span></a></h3>                 
                </div>                        
            </div>             
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel flt-left">
                
                <div class="x_content">
                    <?php echo $this->Form->create('User', array('inputDefaults' => array(
                            'label' => false,
                            'error' => array('attributes' => array('wrap' => 'div', 'class' => 'help-inline redDiv'))
                        ),
                        'type' => 'POST',
                        'class' => "form-horizontal form-label-left",
                        'novalidate' => true,
                        'enctype' => 'multipart/form-data')
                    );
                    echo $this->Form->hidden('id');
                    $optionData=array();
                    if($this->request->data['User']['role']==0){
                        $optionData[0] = 'Agent';
                    }elseif($this->request->data['User']['role']==2){
                        $optionData[2] = 'Manager';
                    }
                    ?>
                    <input type='hidden' name='dataType' id='dataType' value='edit'>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">User Type<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->input('role',array('options'=>$optionData,'class'=>'form-control', 'id' => 'role', 'selected'=>$this->request->data['User']['role']) ); ?>
                        </div>
                    </div>
                    <?php if($this->request->data['User']['role'] == 0){ ?>
                        <div class="form-group" id="manager_sec">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Manager<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->input('parent_id',array('options'=>$managerData,'class'=>'form-control', 'id' => 'parent_id','selected'=>$this->request->data['User']['parent_id']) ); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="First Name">Name <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php echo $this->Form->input('fname',array('class'=>'form-control col-md-7 col-xs-12','id' => 'fname')); ?>
                        </div>
                    </div>
            
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email Id <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php echo $this->Form->input('email',array('class'=>'form-control col-md-7 col-xs-12','id' => 'email')); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile_no">Mobile No </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->input('mobile_no',  array('class' =>'form-control col-md-7 col-xs-12', 'id'=>'mobile_no')); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Agent Code">Agent Code [username] <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->input('agentId',array('class'=>'form-control col-md-7 col-xs-12','id' => 'agentId', 'maxlength'=>30)); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Password">Password<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php echo $this->Form->input('password',array('class'=>'form-control col-md-7 col-xs-12','id' => 'password', 'maxlength'=>30)); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Address</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php echo $this->Form->input('address',array('class'=>'date-picker form-control col-md-7 col-xs-12','rows'=>'3', 'placeholder'=>'Address...' ,'id' => 'address')); ?>
                        </div>
                    </div>                    
                                        
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Status<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->input('status',array('options'=>array('1'=>'Active','0'=>'Inactive'),'class'=>'form-control', 'id' => 'status') ); ?>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <?php if($this->request->data['User']['role']==0){ ?>
                                    <a href="<?php echo h($this->webroot.$this->request['controller'].'/agentlist');?>"  class="btn btn-primary" >Cancel</a>
                            <?php }elseif($this->request->data['User']['role']==2){ ?>
                                    <a href="<?php echo h($this->webroot.$this->request['controller'].'/managerlist');?>"  class="btn btn-primary" >Cancel</a>
                            <?php } ?>
                            
                            <button type="submit" class="btn btn-primary validateForm">Submit</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="javascript">
    var formNameNew = "UserEditForm";
    
</script>

