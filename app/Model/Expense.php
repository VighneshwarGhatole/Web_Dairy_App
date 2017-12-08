<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('CakeTime', 'Utility');

/**
 * User Model
 *
 */
class Expense extends AppModel {

    //public $recursive = -1;

    /*public $hasOne = array('UserRoleMapping' => array(
            'className' => 'UserRoleMapping',
            'foreignKey' => 'user_id'
        )
    );*/
    
    /*public $hasMany = array(
        'UserBatchMapping' => array(
            'className' => 'UserBatchMapping',
            'foreignKey' => 'user_id'
        )
    );*/

    /*public $belongsTo = array(
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id'
        )
    );*/
    public function checkExpenseInputParams($dataReq){
        if(!empty($dataReq->data['code'])){
            if(ctype_digit(strval($dataReq->data['code'])) && ($dataReq->data['code'] > 0)){
                if(!empty($dataReq->data['expenses'])){
                    if(($dataReq->data['expenses'] > 0)){
                        $outputArray['status'] = 1;
                    }else{
                        $outputArray['status'] = 37;
                    }
                }else{
                    $outputArray['status'] = 37;
                }
            }else{
                $outputArray['status'] = 30;
            }
        }else{
            $outputArray['status'] = 29;
        }
        return $outputArray;
    }

    public function saveExpense($agentId, $value, $resultType = NULL){ 
        
        $userData['Expense']['user_id'] = $agentId; 
        $userData['Expense']['code_id'] = $value['code']; 
        $userData['Expense']['expense'] = $value['expense'];                 
        $contentArray['Entry']['created'] = strtotime($value['created_at']);
        $contentRes = $this->saveData($userData);
        if($contentRes['status']==1){
            $outputArray['status'] = 1;
        }else{
            $outputArray['status'] = 6;
        }
        return $outputArray;
    }

    private function saveData($data, $resultType = NULL) {
        $arrResponse = array();
        try {
            $this->create();
            if ($this->save($data)) {
                $arrResponse['status'] = 1;                
                $arrResponse['id'] = $this->id;
            } else {
                $arrResponse['status'] = 0;
            }
        } catch (Exception $e) {
            $arrResponse['status'] = 0;            
        }
        if ($resultType == 'json') {
            return json_encode($arrResponse);
        } else {
            return $arrResponse;
        }
    }
   
    public function findAgentExpenseList($resultType = null){ 
        $arrResponse = array();
        try{
            
            $joins = [
                array('table' => 'users',
                        'alias' => 'User',
                        'type' => 'left',
                        'conditions' => array(
                            'Expense.user_id = User.id'
                    )),
                array('table' => 'codes',
                        'alias' => 'Code',
                        'type' => 'left',
                        'conditions' => array(
                            'Expense.code_id = Code.id'
                    )
                )];
            
            $fields = array('Expense.id', 'Expense.expense', 'Expense.created', 'User.fname', 'User.address', 'Code.name');

            $codeInfo = $this->find('all', array('joins'=>$joins, 'fields'=>$fields));
            if(!empty($codeInfo)){
                $arrResponse['status'] = 1;
                $arrResponse['message'] = 'success';
                $arrResponse['resultData'] = $codeInfo;
            }else{
                $arrResponse['status'] = 0;
                $arrResponse['message'] = 'No Result Found';
            }
        }catch(Exception $e){
            $arrResponse['status'] = 0;
            $arrResponse['message'] = $e->getMessage();
        }
        if ($resultType == 'json'){
            return json_encode($arrResponse);
        }else{
            return $arrResponse;
        }
    }

    public function createExpense($data, $agentId){ 
        $resData['status'] = 32;        
        foreach ($data as $key => $value) { 
            if($this->checkExpenseData($agentId, $value)){                
                $res = $this->saveExpense($agentId, $value);
                if($res['status']==1){
                    $resData['status'] =1;
                }
            }
        }
        return $resData;
    }

    private function checkExpenseData($agentId, $value){
        $st = true;
        if(empty($agentId)){ 
            $st = false;

        }else if(empty($value['code'])){ 
            $st = false;

        }else if(!empty($value['code']) && !is_numeric($value['code'])){ 
            $st = false;

        }else if(empty($value['expense'])){ 
            $st = false;

        }else if(!empty($value['expense']) && !is_numeric($value['expense'])){ 
            $st = false;

        }
        return $st;

    }
} /*** Class End Here ***/
