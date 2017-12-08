<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('CakeTime', 'Utility');

/**
 * User Model
 *
 */
class Code extends AppModel {


   private function saveCode($agentId, $name, $type, $phone, $createdAt, $resultType = NULL){

        $userData['Code']['user_id'] = $agentId; 
        $userData['Code']['name'] = strtolower($name); 
        $userData['Code']['type'] = $type; 
        $userData['Code']['mobile_no'] = $phone;         
        $contentArray['Code']['created'] = $createdAt;
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
   
    
    public function chkCodeValidity($code, $usrId){
        $arrResponse = array();
        if($this->find('count', array('conditions'=>array('code'=>$code, 'user_id'=>$usrId, 'status'=>1))) > 0){
            $arrResponse['status'] = 1;

        }else{
            $arrResponse['status'] = 35;
        }
        return $arrResponse;
    }

    public function findCustomerData($resultType = null){
        $arrResponse = array();
        try{
            
            $joins = [
                array('table' => 'users',
                    'alias' => 'User',
                    'type' => 'left',
                    'conditions' => array(
                        'Code.user_id = User.id'
                    )
                )];
            $conditions = array('Code.is_deleted'=>0, 'Code.status'=>1);
            $fields = array('Code.id', 'Code.name', 'Code.type', 'Code.mobile_no', 'Code.created', 'User.fname', 'User.address');

            $codeInfo = $this->find('all', array('joins'=>$joins, 'conditions'=>$conditions, 'fields'=>$fields));
            
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

    public function createCode($data, $agentId){ 
        $resData['status'] = 29;        
        foreach ($data as $key => $value) { 
            if($this->checkCodeData($agentId, $value)){
                $res = $this->saveCode($agentId, $value['name'], $value['type'], $value['phone'], strtotime($value['created_at']));
                if($res['status']==1){
                    $resData['status'] =1;
                }
            }
        }
        return $resData;
    }

    private function checkCodeData($agentId, $value){        
        $st = true;
        
        if(empty($agentId)){
            $st = false;

        }else if(empty($value['name'])){ 
            $st = false;

        }else if(empty($value['type']) && $value['type'] <> 0){ 
            $st = false;
            
        }else if(!empty($value['type']) && !is_numeric($value['type'])){ 
            $st = false;

        }else if(is_numeric($value['type'])){ 
            if($value['type'] <> 0 && $value['type'] <> 1){ 
                $st = false;
            }            
        }

        if(empty($value['phone'])){
            $st = false;

        }else if(!empty($value['phone']) && !is_numeric($value['phone'])){ 
            $st = false;

        }elseif(is_numeric($value['phone'])){ 
            if(strlen($value['phone']) < 10){ 
                $st = false;
            }else if($this->find('count', array('conditions'=>array('Code.user_id'=>$agentId, 'Code.name'=>strtolower($value['name']), 'Code.type'=>$value['type'], 'Code.mobile_no'=>$value['phone']))) > 0){ 
                $st = false;
            }
        }
        return $st;
    }



} /*** Class End Here ***/
