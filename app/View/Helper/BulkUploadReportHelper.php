<?php
App::uses('Helper', 'View');
class BulkUploadReportHelper extends Helper {
    var $helper = array('Session');
    
    public function getQuestionType($tId=NULL,$quesTypeId=NULL,$fromDate=NULL,$toDate=NULL,$type=NULL,$typColumn,$columnVal){
        App::import('model', 'QuestionTemp');
        $QuestionTemp = new QuestionTemp();
        $conditions = array($typColumn=>$columnVal);
        if(!empty($fromDate) && !empty($toDate)){
            $conditions += array("DATE_FORMAT(QuestionTemp.created_on,'%Y-%m-%d') >="=>$fromDate,"DATE_FORMAT(QuestionTemp.created_on,'%Y-%m-%d') <="=>$toDate);
        } else {
            if(!empty($fromDate)){
                $conditions += array("DATE_FORMAT(QuestionTemp.created_on,'%Y-%m-%d') >="=>$fromDate);
            }
            if(!empty($toDate)){
                $conditions += array("DATE_FORMAT(QuestionTemp.created_on,'%Y-%m-%d') <="=>$toDate);
            }
        }
        
        if($type=='module'){
            $conditions += array("QuestionTemp.module_id"=>$tId);
        } else {
            $conditions += array("QuestionTemp.batch_id"=>$tId);
        }
        if($quesTypeId !=''){
            $conditions += array("QuestionTemp.question_type_id"=>$quesTypeId);
        }
        $res = $QuestionTemp->find('all',array("conditions"=>$conditions,"fields"=>array("count(QuestionTemp.id) as totalQuesCnt")));
        return $res[0][0]['totalQuesCnt'];
    }
    
    public function questionTypeList(){
        App::import('model', 'QuestionType');
        $QuestionType = new QuestionType();
        $quesTypeList = $QuestionType->getQuestionType();
        $quesTypeList = array(''=>'--Select Question Type--')+$quesTypeList;
        return $quesTypeList;
    }
    
    public function getBatch($id){
        if($id == '') return false;
        App::import('model', 'Batch');
        $Batch = new Batch();
        $batchData = $Batch->find('first',array('condition'=>array('id'=>$id))); 
        return $batchData;
    }
    
    public function getModule($id){
        if($id == '') return false;
        App::import('model', 'Module');
        $Module = new Module();
        $moduleData = $Module->find('first',array('condition'=>array('id'=>$id))); 
        return $moduleData;
    }
}
