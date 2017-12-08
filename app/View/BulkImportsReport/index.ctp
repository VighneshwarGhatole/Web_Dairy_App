<div class="page-header">
    <div class="page-title nt-tab">
        <div class="title_left">
            <div class="com-profile">
                <div class="com-pic sm-spc">
                    <div class="icn-circle"><i class="fa fa-university" aria-hidden="true"></i></div>
                </div>
                <div class="com-detail">
                    <h3><a href="#"><span><?php echo __('Bulkimport Report'); ?></span></a></h3>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $this->webroot; ?>Users/Dashboard">Home</a></li>
                        <li><a href="<?php echo $this->webroot; ?>BulkImports">BulkImports</a></li>
                        <li class="active"><?php echo __('Bulkimport Report'); ?></li>
                    </ol>
                </div>                        
            </div>              
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
        <?php
        if($this->Session->read('UserDetails.roleId')<>Configure::Read('UserRoles.External')){
            echo $this->Form->create('bulkImportsReport', array('type'=>'get', 'class' => "form-inline filter-form",'url' => '/BulkImportsReport/index'));
        ?>  
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="input-group">
                <?php echo $this->Form->input('batch_Id', array('options' => $batchList, 'empty' => ' --Select Batch-- ', 'required' => false, 'label' => false, 'default'=>$this->request->query('batch_id'), 'class' => 'form-control')); ?>
            </div>
            <div class="input-group">
                <?php echo $this->Form->input('module_id', array('class'=>'form-control', 'empty'=>'--Select Module--', 'label'=>false, 'id'=>'module_id', 'div'=>false)); ?>
            </div>
            <div class="input-group">
                <?php echo $this->Form->input('quesType', array('class'=>'form-control', 'options' => $this->BulkUploadReport->questionTypeList(), 'label'=>false, 'id'=>'quesType','div'=>false)); ?>
            </div>
            <div class="input-group">
                <?php echo $this->Form->input('fromDate', array('class'=>'form-control', 'readonly'=>true, 'id'=>'fromDate', 'label' => false, 'placeholder'=>'From Date')); ?>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <div class="input-group">
                <?php echo $this->Form->input('toDate', array('class'=>'form-control', 'readonly'=>true, 'id'=>'toDate', 'label' => false, 'placeholder'=>'To Date')); ?>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="input-group">
                <button type="button" class="btn btn-primary" onClick="getdata(0)">Search</button>
            </div>
        </div>
        <?php echo $this->Form->end(); 
	} ?> 
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel flt-left">                
                <div class="x_content">
                    <div class="col-xs-12 col-md-12">
                        <div class="tbl-list">
                            <div class="table-responsive" id="quesTemp"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .input-group{margin-bottom: 0px !important;}
</style>
<script>
var maxD = new Date('<?php echo date('Y-m-d',strtotime('+1 Day'))?>');
window.onload = function() {
  getdata(0);
};
</script>
<?php echo $this->Html->script('bulkimport.js');?>