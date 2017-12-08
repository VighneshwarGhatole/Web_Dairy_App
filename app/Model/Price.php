<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('CakeTime', 'Utility');

/**
 * User Model
 *
 */
class Price extends AppModel {

    private function savePrice($agentId, $value, $resultType = NULL){

        $userData['Price']['user_id'] = $agentId; 
        $userData['Price']['lfat'] = $value['lfat']; 
        $userData['Price']['hfat'] = $value['hfat']; 
        $userData['Price']['lsnf'] = $value['lsnf']; 
        $userData['Price']['hsnf'] = $value['hsnf']; 
        $userData['Price']['start_price'] = $value['start_price']; 
        $userData['Price']['fat_intetval'] = $value['fat_intetval']; 
        $userData['Price']['snf_intetval'] = $value['snf_intetval']; 
        $userData['Price']['type'] = $value['type']; 
        $userData['Price']['time'] = $value['time']; 
        $contentArray['Price']['created'] = strtotime($value['created_at']);
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
   

    /*public function chkCodeValidity($code, $usrId){
        $arrResponse = array();
        if($this->find('count', array('conditions'=>array('code'=>$code, 'user_id'=>$usrId, 'status'=>1))) > 0){
            $arrResponse['status'] = 1;

        }else{
            $arrResponse['status'] = 35;
        }
        return $arrResponse;
    }*/

    public function findAgentPriceList($resultType = null){ 
        $arrResponse = array();
        try{
            
            $joins = [
                array('table' => 'users',
                    'alias' => 'User',
                    'type' => 'left',
                    'conditions' => array(
                        'Price.user_id = User.id'
                    )
                )];
            $conditions = array('Price.is_deleted'=>0, 'Price.status'=>1);
            $fields = array('Price.id', 'Price.lfat', 'Price.hfat', 'Price.lsnf', 'Price.hsnf', 'Price.start_price', 'Price.intetval', 'Price.type', 'Price.time', 'Price.status', 'Price.created', 'User.fname', 'User.address');

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

    public function createPrice($data, $agentId){
        $resData['status'] = 30;        
        foreach ($data as $key => $value) { 
            if($this->checkPriceData($agentId, $value)){                
                $res = $this->savePrice($agentId, $value);
                if($res['status']==1){
                    $resData['status'] =1;
                }
            }
        }
        return $resData;        
    }

    private function checkPriceData($agentId, $value){       
        $st = true;
        
        if(empty($agentId)){ 
            $st = false;

        }else if(empty($value['lfat'])){ 
            $st = false;

        }else if(!empty($value['lfat']) && !is_numeric($value['lfat'])){ 
            $st = false;

        }else if(empty($value['hfat'])){ 
            $st = false;

        }else if(!empty($value['hfat']) && !is_numeric($value['hfat'])){ 
            $st = false;

        }else if(empty($value['lsnf'])){ 
            $st = false;
            
        }else if(!empty($value['lsnf']) && !is_numeric($value['lsnf'])){ 
            $st = false;

        }else if(empty($value['hsnf'])){ 
            $st = false;

        }else if(!empty($value['hsnf']) && !is_numeric($value['hsnf'])){ 
            $st = false;

        }else if(empty(intval($value['start_price']))){ 
            $st = false;
            
        }else if(!empty($value['start_price']) && !is_numeric($value['start_price'])){ 
            $st = false;

        }else if(empty(floatval($value['fat_intetval']))){ 
            $st = false;

        }else if(!empty($value['fat_intetval']) && !is_numeric($value['fat_intetval'])){ 
            $st = false;

        }else if(empty(floatval($value['snf_intetval']))){ 
            $st = false;

        }else if(!empty($value['snf_intetval']) && !is_numeric($value['snf_intetval'])){ 
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

        if(empty($value['time']) && $value['time'] <> 0){ 
            $st = false;
            
        }else if(!empty($value['time']) && !is_numeric($value['time'])){ 
            $st = false;

        }else if(is_numeric($value['time'])){ 
            if($value['time'] <> 0 && $value['time'] <> 1){ 
                $st = false;
            }
            
        }

        if($this->find('count', array('conditions'=>array('Price.user_id'=>$agentId, 'Price.type'=>$value['type'], 'Price.time'=>$value['time']))) > 0){ 
            $st = false;
        }
        return $st;
    }


    

} /*** Class End Here ***/
