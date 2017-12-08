<div class="page-header">
    <div class="page-title nt-tab">
        <div class="title_left">
            <div class="com-profile">
                <div class="com-pic sm-spc">
                    <div class="icn-circle"><i class="fa fa-university" aria-hidden="true"></i></div>
                </div>
                <div class="com-detail">
                    <h3><a href="#"><span><?php echo __('Questions Bulk UPload'); ?></span></a></h3>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $this->webroot; ?>Users/Dashboard">Home</a></li>
                        <li class="active"><?php echo __('Question Bulk Upload'); ?></li>
                    </ol>
                </div>
            </div>              
        </div>
        <div class="title_right">
            <div class="com-detail">
                <?php /* <a class="btn btn-primary" href="<?php echo ABSOLUTE_URL.'BulkImports/viewLog/'?>" target="_blank">View Log</a> */?>
                <a class="btn btn-primary" href="<?php echo ABSOLUTE_URL.'BulkImportsReport/'?>">View Temp Table Data</a> 
            </div> 
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel flt-left">                
            <div class="x_content">
                <div class="col-xs-12 col-md-12">
                    <?php if(empty($arrPartUploadDetails)){?>
                    <?php echo $this->Form->create('QuestionSampleFile', array('inputDefaults' => array('label' => false,'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))),'type' => 'POST','class'=>"form-horizontal form-label-left",'novalidate'=> true,'enctype' => 'multipart/form-data')); ?>
                        <div class="col-xs-12 col-md-10">
                            <div class="col-xs-12 col-md-4">
                                <div class="input-group">
                                    <?php echo $this->Form->input('batch_id', array('options' => $batchList, 'empty' => ' --Select Batch-- ', 'required' => false, 'label' => false, 'default'=>$this->request->query('batch_id'), 'class' => 'form-control')); ?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                <div class="multsl">
                                    <select name="data[QuestionSampleFile][module_ids][]" class="form-control" id="module_id" multiple="multiple">
                                    </select>
                                </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <div class="input-group">
                                    <?php echo $this->Form->button('Download Sample File', array('id'=>'download', 'div' => true, 'disabled'=>true, 'class' => "form-contro btn btn-info")); ?>
                                </div>
                            </div>
                        </div>
                    <?php echo $this->Form->end(); ?>
                    <div class="creation-field">
                        <?php echo $this->Form->create('QuestionImport', array('enctype' => "multipart/form-data","accept-charset"=>"utf-8")); ?>
                        <div class="col-xs-12 col-md-10">
                            <div class="col-xs-12 col-md-8">
                                <div class="form-group">
                                    <div class="file-upload">
                                        <div class="uploadbtn"><i class="fa fa-upload" aria-hidden="true"></i> UPLOAD FILE FROM YOUR LOCAL DISK</div>
                                        <div class="help-block" style="width: 50%; margin-bottom:0px">XLS, XLSX ONLY</div>
                                        <?php echo $this->Form->file('uploaded_path',array("id"=>"uploadBtn")); ?>
                                        <div id="filename" class="filename"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <div class="form-group">
                                    <?php echo $this->Form->button('Process File', array('id'=>'submit', 'name' => 'Submit', 'div' => true, 'class' => "btn btn-info", "onClick"=>"return confirmUpload()")); ?>
                                </div>    
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                        <?php } else { $error=0; ?>
                        <?php echo $this->Form->create('BulkImports', array('url'=>array('controller'=>'BulkImports','action'=>'uploadQuestionAfterPreview'), 'enctype'=>'multipart/form-data')); ?>
                        <?php $tblName=''; foreach($arrPartUploadDetails as $fileName=>$arrFileBasedDetails){?>
                        <span style="color:#FF6000;"><strong>Preview For: <?php echo $fileName;?></strong></span>
                            <?php foreach($arrFileBasedDetails as $workSheetName=>$arrWorkSheetRelatedDetails){?>
                            <?php $tblName = $arrWorkSheetRelatedDetails['tableName'];?>
                            <?php $arrTempData = $this->Common->returnTableData($arrWorkSheetRelatedDetails['tableName'],$fileName,$workSheetName);?>
                            <div>
                                <span style="color:#40B737;"><strong>Worksheet: </strong><?php echo $workSheetName;?></span>
                                <span style="color:#FF6000;"><strong>Total Question: </strong><?php echo count($arrTempData);?></span>
                                <div class="clearfix"></div>
                            </div>
                            <div class="tbl-list">
                            <div class="table-responsive" style="overflow:auto !important">
                            <table class="table table-striped table-bordered jambo_table">
                                    <thead>
                                        <tr class="headings">
                                            <th class="column-title" width="2%">Sr. No.</th>
                                            <th class="column-title" width="3%">Batch /<br/> Module Ids</th>
                                            <th class="column-title" width="10%">Batch /<br/> Module Names</th>
                                            <th class="column-title" width="5%">Question Type</th>
                                            <th class="column-title" width="3%">Is Practice</th>
                                            <th class="column-title" width="10%">Question Statement</th>
                                            <th class="column-title" width="10%">Question Option1</th>
                                            <th class="column-title" width="10%">Question Option2</th>
                                            <th class="column-title" width="10%">Question Option3</th>
                                            <th class="column-title" width="10%">Question Option4</th>
                                            <th class="column-title" width="10%">Question Option5</th>
                                            <th class="column-title" width="2%">Correct Answer</th>
                                            <th class="column-title" width="9%">Question Explanation</th>
                                            <th class="column-title" width="3%">Difficulty Level</th>
                                            <th class="column-title" width="3%">Marks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php $i=0;foreach($arrTempData as $question){?>
                                            <?php $class=''; if($question['new']['statement_text'] == ''){ $class='style="background-color:#F2DEDE;"'; $error=1;}?>
                                            <?php $classCorrectAnswer='';
                                                  $classtdCorrectAnswer='';
                                                    $opArr = array('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);
                                                    if($question['new']['correct_answer'] ==''){
                                                        $classCorrectAnswer ='style="background-color:#F2DEDE;"';
                                                        $classtdCorrectAnswer ='style="background-color:red;"';
                                                        $error=1;	
                                                    } else if(!is_numeric($question['new']['correct_answer'])){
                                                        $ca = str_replace(array('<b>','</b>','(',')'),array('','','',''),$question['new']['correct_answer']);
                                                        if(trim($ca) ==''){
                                                                $classCorrectAnswer ='style="background-color:#F2DEDE;"';
                                                                $classtdCorrectAnswer ='style="background-color:red;"';
                                                                $error=1;
                                                        } else if($opArr[strtolower(trim($ca))]<1 && $opArr[strtolower(trim($ca))]>5){
                                                                $classCorrectAnswer ='style="background-color:#F2DEDE;"';
                                                                $classtdCorrectAnswer ='style="background-color:red;"';
                                                                $error=1;
                                                        }
                                                    }
                                            ?>
                                            <tr <?php echo $class;?> <?php echo $classCorrectAnswer;?>>
                                                    <td><?php echo ++$i;?></td>
                                                    <td>
                                                            <?php
                                                                    $strNodeIds='';
                                                                    $strNodeName='';
                                                                    if($question['new']['batch_id'] != 0){
                                                                            $strNodeIds .= $question['new']['batch_id'];
                                                                            $strNodeName .= $question['b']['name'];
                                                                    }
                                                                    if($question['new']['module_id'] != 0){
                                                                            $strNodeIds .= ' / '.$question['new']['module_id'];
                                                                            $strNodeName .= ' / '.$question['m']['name'];
                                                                    }
                                                            ?>
                                                            <?php echo $strNodeIds;?>
                                                    </td>
                                                    <td>
                                                            <?php echo $strNodeName;?>
                                                    </td>
                                                    <?php $class=''; if($question['new']['question_type'] == ''){ $class='style="color:red;"';}?>
                                                    <td <?php echo $class;?>><?php echo $question['new']['question_type'];?></td>
                                                    <td><?php echo ($question['new']['is_practice'])?'Yes':'No';?></td>
                                                    <?php $class1=''; if($question['new']['statement_text'] == ''){ $class1='style="background-color:red;"';}?>
                                                    <td <?php echo $class1;?>><?php echo $this->Common->getText($question['new']['statement_text']);?></td>
                                                    <td><?php echo $this->Common->getText($question['new']['option_1']);?></td>
                                                    <td><?php echo $this->Common->getText($question['new']['option_2']);?></td>
                                                    <td><?php echo $this->Common->getText($question['new']['option_3']);?></td>
                                                    <td><?php echo $this->Common->getText($question['new']['option_4']);?></td>
                                                    <td><?php echo $this->Common->getText($question['new']['option_5']);?></td>
                                                    <td <?php echo $classtdCorrectAnswer;?>><?php echo $question['new']['correct_answer'];?></td>
                                                    <td><?php echo $this->Common->getText($question['new']['explanation_text']);?></td>
                                                    <td><?php echo $question['new']['difficulty_level'];?></td>
                                                    <td><?php echo $question['new']['marks'];?></td>
                                            </tr>
                                            <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <?php } ?>
                    <?php } ?>
                    <input type="hidden" name="arrPartUploadDetails" value="<?php echo $tblName;?>"/>
                    <?php echo $this->Form->button('Save', array('name' => 'Submit', 'class' => 'btn btn-primary', 'onClick'=>"return confirmsubmit('save');")); ?>
                    <?php echo $this->Form->button('Cancel', array('name' => 'Cancel', 'class' => 'btn btn-primary','onClick'=>"return confirmsubmit('cancel');") ); ?>
                    <?php echo $this->Form->end(); ?>
                    <br/>
                    <?php } ?>
                    </div>
                </div>                    
            </div>
        </div>
    </div>
</div>
<!--Start-->
<script type="text/javascript">
$("#uploadBtn").change(function(){
    $("#filename").html(this.value);
});
$(document).ready(function() {
    $('#module_id').multiselect({
        includeSelectAllOption: true,
        enableFiltering: false
    });
    $('#QuestionSampleFileBatchId').on("change",function(){
        var batchId = $(this).val();
        if(batchId>0){
            $('#download').attr('disabled',false);
            $.ajax({
                beforeSend: function() { $("#loaderImg").show(); },
                url: webURL + 'Questions/getModuleList/'+batchId+'/assessment',
                type: 'POST',
                async:false,
                success: function(data, textStatus, jqXHR){
                    $("#loaderImg").hide();
                    $('#module_id').html(data);
                    $('#module_id').multiselect('rebuild');
                },
               error: function(data) {
                   $("#loaderImg").hide();
                    alert('Error...');
                    return false;
                }
            });
        } else {
            $('#download').attr('disabled',false);
            $('#module_id').html('');
        }
    });
});

function confirmsubmit(e){
    if(e=='save'){
        var error = "<?php echo isset($error)?$error:0;?>";
        if(error==1){
            if(confirm('You have some errors, Do you want to save data with these errors.')){
                return true;
            } else {
                return false;
            }
        } else {
            return true;;	
        }	
    } else {
        if(confirm('Do you want to cancel this process.')){
            return true;
        } else {
            return false;
        }
    }
}
</script>
<!--End-->   
<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/unpacked/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
