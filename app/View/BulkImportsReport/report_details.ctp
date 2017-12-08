<div class="page-header">
    <div class="page-title nt-tab">
        <div class="title_left">
            <div class="com-profile">
                <div class="com-pic sm-spc">
                    <div class="icn-circle"><i class="fa fa-university" aria-hidden="true"></i></div>
                </div>
                <div class="com-detail">
                    <h3><a href="#"><span><?php echo __('Bulkimport Report View'); ?></span></a></h3>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $this->webroot; ?>Users/Dashboard">Home</a></li>
                        <li><a href="<?php echo $this->webroot; ?>BulkImportsReport">BulkImportsReport</a></li>
                        <li class="active"><?php echo __('Bulkimport Report View'); ?></li>
                    </ol>
                </div>                        
            </div>              
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
        <?php
        if($this->Session->read('UserDetails.roleId')<>Configure::Read('UserRoles.External')){
            echo $this->Form->create('bulkImportsReport', array('type'=>'post', 'class' => "form-inline filter-form"));
        ?>  
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="input-group">
                <?php echo $this->Form->input('inputUploadFromDate', array('class'=>'form-control', 'id'=>'uploadfromDate', 'label' => false, 'placeholder'=>'Upload From Date', 'value'=>$inputUploadFromDate)); ?>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <div class="input-group">
                <?php echo $this->Form->input('inputUploadToDate', array('class'=>'form-control', 'id'=>'uploadtoDate', 'label' => false, 'placeholder'=>'Upload To Date', 'value'=>$inputUploadToDate)); ?>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <div class="input-group">
                <?php echo $this->Form->input('inputMovedFromDate', array('class'=>'form-control', 'id'=>'movedfromDate', 'label' => false, 'placeholder'=>'From Date', 'value'=>$inputMovedFromDate)); ?>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <div class="input-group">
                <?php echo $this->Form->input('inputMovedToDate', array('class'=>'form-control', 'id'=>'movedtoDate', 'label' => false, 'placeholder'=>'TO Date', 'value'=>$inputMovedToDate)); ?>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="input-group">
                <?php echo $this->Form->input('quesType', array('class'=>'form-control', 'options' => $this->BulkUploadReport->questionTypeList(), 'default'=>$typeId, 'label'=>false, 'id'=>'quesType','div'=>false)); ?>
            </div>
            <div class="input-group">
                <?php echo $this->Form->input('uploadType', array('class'=>'form-control', 'options' => array(0=>'New',1=>'Old'),'empty'=>'--Select Upload Type--', 'default'=>$uploadType, 'label'=>false, 'id'=>'quesType','div'=>false)); ?>
            </div>
            <div class="input-group">
                <input type="hidden" name="type" value="search">
                <?php echo $this->Form->button('Filter Data', array('id'=>'search', 'div' => true, 'class' => "btn btn-info withadd martop ani" , 'onclick' => 'return filterQuestion();')); ?>
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
                            <div class="table-responsive" id="quesTemp">
                                <?php
                                    $question_type_id = '';
                                    if($typeId!=''){
                                        $question_type_id = $typeId;
                                    }
                                    if(!empty($reportDetails)){
                                        $tId = ($reportDetails['QuestionTemp']['module_id'] !='')?$reportDetails['QuestionTemp']['module_id']:$reportDetails['QuestionTemp']['batch_id'];
                                        $type = ($reportDetails['QuestionTemp']['module_id'] !='')?'module':'batch';
                                    }
                                ?>
                                <table data-toggle="table" class="table table-striped table-bordered jambo_table" width="100%">
                                    <thead>
                                        <tr>
                                          <th width="10%">Upload Details</th>
                                          <th width="25%"></th>
                                          <th width="10%"></th>
                                          <th width="25%"></th>
                                          <th width="10%"></th>
                                          <th width="20%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($reportDetails)){?>
                                        <tr>
                                          <td>Batch [Id]</td>
                                          <td><?php echo $reportDetails['Batch']['name']; ?>[<?php echo $reportDetails['QuestionTemp']['batch_id']; ?>]</td>
                                          <td>Module [Id]</td>
                                          <td colspan="3"><?php echo $reportDetails['Module']['name']; ?>[<?php echo $reportDetails['QuestionTemp']['module_id']; ?>]</td>
                                        </tr>
                                        <tr>
                                          <td>Uploaded</td>
                                          <td><?php echo ($this->BulkUploadReport->getQuestionType($tId,$question_type_id,$fromDate,$toDate,$type,'is_new',0)+$this->BulkUploadReport->getQuestionType($tId,$question_type_id,$fromDate,$toDate,$type,'is_new',1)); ?></td>
                                          <td>New</td>
                                          <td><?php echo $this->BulkUploadReport->getQuestionType($tId,$question_type_id,$fromDate,$toDate,$type,'is_new',0); ?></td>
                                          <td>Old</td>
                                          <td><?php echo $this->BulkUploadReport->getQuestionType($tId,$question_type_id,$fromDate,$toDate,$type,'is_new',1); ?></td>
                                        </tr>
                                        <tr>
                                          <td>Moved</td>
                                          <td><?php echo $this->BulkUploadReport->getQuestionType($tId,$question_type_id,$fromDate,$toDate,$type,'updated_data',array('1','2')); ?></td>
                                          <td>Not Moved</td>
                                          <td colspan="3"><?php echo $this->BulkUploadReport->getQuestionType($tId,$question_type_id,$fromDate,$toDate,$type,'updated_data',array('0')); ?></td>
                                        </tr>
                                        <?php } else {?>
                                            <tr><td colspan="6" align="center"><font style="color:red;">Record(s) Not Found!</font></td></tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                                <table class="table table-striped table-bordered jambo_table">
                                    <thead>
                                        <tr>
                                            <th nowrap>Sr. No.</th>
                                            <th nowrap>Question Temp Id</th>
                                            <th nowrap>Question Master Id</th>
                                            <th nowrap>Question Type</th>
                                            <th nowrap>Upload Type</th>
                                            <th nowrap>Upload Date</th>
                                            <th nowrap>Moved Date</th>
                                            <th nowrap>Update Status</th>
                                            <th nowrap>
                                                <input type="checkbox" id="check_uncheck_all" value="1" />
                                                <span>Check/Uncheck All</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(!empty($allQuesData)){
                                            $sno=1;
                                            $check=0;
                                            foreach($allQuesData as $allData){
                                        ?>
                                        <tr id="qid_<?php echo $allData['QuestionTemp']['id'];?>">
                                            <td><?php echo $sno;?></td>
                                            <td>
                                                <a class="viewQuestion" href="javascript:void(0);" onclick="viewTempQuestion(<?php echo $allData['QuestionTemp']['id'];?>,<?php echo $allData['QuestionTemp']['question_type_id'];?>)">
                                                    <?php echo $allData['QuestionTemp']['id'];?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if($allData['QuestionTemp']['question_master_id']>0){?>
                                                <a class="viewQuestion" href="javascript:void(0);" onclick="viewQuestion(<?php echo $allData['QuestionTemp']['question_master_id'];?>,<?php echo $allData['QuestionTemp']['question_type_id'];?>)">
                                                <?php echo $allData['QuestionTemp']['question_master_id'];?>
                                                </a>
                                                <?php } else { echo 0;}?>
                                            </td>
                                            <td><?php echo $allData['QuestionType']['type'];?></td>
                                            <td><?php echo ($allData['QuestionTemp']['is_new']==0?'New':'Old');?></td>
                                            <td><?php if($allData['QuestionTemp']['created_on'] !='0000-00-00 00:00:00' && $allData['QuestionTemp']['modified_on'] !=NULL){ echo date('d-M-Y H:i:s A',strtotime($allData['QuestionTemp']['created_on']));} else { echo 'N/A'; }?></td>
                                            <td><?php if($allData['QuestionTemp']['modified_on'] !='0000-00-00 00:00:00' && $allData['QuestionTemp']['modified_on'] !=NULL){echo date('d-M-Y H:i:s A',strtotime($allData['QuestionTemp']['modified_on']));}else{ echo 'N/A'; }?></td>
                                            <td><?php echo ($allData['QuestionTemp']['updated_data']!=0)?'Moved':'Not Moved';?></td>
                                            <td><?php if($allData['QuestionTemp']['updated_data'] =='0'){ $check =1;?><input type="checkbox" class="checkitem" name="checkIds[]" value="<?php echo $allData['QuestionTemp']['id'];?>" id="checkIds"><?php } ?></td>
                                        </tr>
                                        <?php $sno++; } ?>
                                        <?php if($check>0){?>
                                        <tr>
                                            <td colspan="8">&nbsp;</td>
                                            <td>
                                            <input type="submit" value="Move" id="moveids" onclick="moveQuestion();" name="submitMoveQuestion">&nbsp;
                                            <input type="submit" value="Delete" id="deleteids" onclick="deleteQuestion();" name="submitDeleteQuestion"></td>
                                        </tr>
                                        <?php }?>
                                        <?php } else { ?>
                                        <tr><td colspan="8" align="center"><font style="color:red;">Record(s) Not Found!</font></td></tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--- TASK HISTORY -->
<div class="modal fade" id="viewQuestion"></div>
<!-- END OF TASK HISTORY -->
<!-- popup start here -->
<div class="modal treemodal fade right" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog tree-modal" role="document">
	<div class="modal-content">
	    <div id="spinner3" class="loaderad2 loderclass"><?php echo $this->Html->image('loading.gif', array('id' => 'busy-indicator')); ?></div>
	    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
	    <div class="modal-body" id="toc_data"></div>
	</div>
    </div>
</div>
<!-- popup end here -->
<style>
.modal-lg { min-width: 90%}
</style>
<script>
var maxD = new Date('<?php echo date('Y-m-d',strtotime('+1 Day'))?>');
</script>
<?php echo $this->Html->script('bulkimport.js');?>