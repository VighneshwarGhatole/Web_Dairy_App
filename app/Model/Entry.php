<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('CakeTime', 'Utility');

/**
 * User Model
 *
 */
class Entry extends AppModel {

   
    public function checkEntryInputParams($dataReq){
        if(!empty($dataReq->data['code'])){
            if(ctype_digit(strval($dataReq->data['code'])) && ($dataReq->data['code'] > 0)){
                if(!empty($dataReq->data['clr'])){
                    if(($dataReq->data['clr'] > 0)){
                        if(!empty($dataReq->data['fat'])){
                            if(($dataReq->data['fat'] > 0)){
                                if(!empty($dataReq->data['ltr'])){
                                    if(($dataReq->data['ltr'] > 0)){
                                        if(!empty($dataReq->data['total'])){
                                            if(($dataReq->data['total'] > 0)){
                                                $outputArray['status'] = 1;
                                            }else{
                                                $outputArray['status'] = 34;
                                            }
                                        }else{
                                            $outputArray['status'] = 34;
                                        }
                                    }else{
                                        $outputArray['status'] = 33;
                                    }
                                }else{
                                    $outputArray['status'] = 33;
                                }
                            }else{
                                $outputArray['status'] = 32;
                            }
                        }else{
                            $outputArray['status'] = 32;
                        }
                    }else{
                        $outputArray['status'] = 36;
                    }
                }else{
                    $outputArray['status'] = 31;
                }
            }else{
                $outputArray['status'] = 30;
            }
        }else{
            $outputArray['status'] = 29;
        }
        return $outputArray;
    }

    private function saveEntry($agentId, $value, $role, $resultType = NULL){ 
        $outputArray['status'] = 0;
        $conditions = array('Entry.code_id'=> $value['code'], 'Entry.more'=>$value['more'], 'DATE(Entry.created)'=>date('Y-m-d', $value['created_at']));        
        $entInfo = $this->find('first', array('conditions'=>$conditions));
        if(!empty($entInfo)){
            /********** Update **************/
            if($role==2){
                $userData['Entry']['id'] = $entInfo['Entry']['id'];
                $userData['Entry']['user_id'] = $agentId; 
                $userData['Entry']['code_id'] = $value['code']; 
                $userData['Entry']['crl'] = $value['crl']; 
                $userData['Entry']['fat'] = $value['fat']; 
                $userData['Entry']['snf'] = $value['snf']; 
                $userData['Entry']['ltr'] = $value['ltr']; 
                $userData['Entry']['price'] = $value['price']; 
                $userData['Entry']['more'] = $value['more']; 
                $userData['Entry']['total'] = $value['total']; 
                $contentArray['Entry']['created'] = strtotime($value['created_at']);
                $contentRes = $this->saveData($userData);
                if($contentRes['status']==1){
                    $outputArray['status'] = 1;
                }else{
                    $outputArray['status'] = 6;
                }
            }
        }else{
            $userData['Entry']['user_id'] = $agentId; 
            $userData['Entry']['code_id'] = $value['code']; 
            $userData['Entry']['crl'] = $value['crl']; 
            $userData['Entry']['fat'] = $value['fat']; 
            $userData['Entry']['snf'] = $value['snf']; 
            $userData['Entry']['ltr'] = $value['ltr']; 
            $userData['Entry']['price'] = $value['price']; 
            $userData['Entry']['more'] = $value['more']; 
            $userData['Entry']['total'] = $value['total']; 
            $contentArray['Entry']['created'] = strtotime($value['created_at']);
            $contentRes = $this->saveData($userData);
            if($contentRes['status']==1){
                $outputArray['status'] = 1;
            }else{
                $outputArray['status'] = 6;
            }
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
   
    public function findAgentEntryList($resultType = null){ 
        $arrResponse = array();
        try{
            
            $joins = [
                array('table' => 'users',
                        'alias' => 'User',
                        'type' => 'left',
                        'conditions' => array(
                            'Entry.user_id = User.id'
                    )),
                array('table' => 'codes',
                        'alias' => 'Code',
                        'type' => 'left',
                        'conditions' => array(
                            'Entry.code_id = Code.id'
                    )
                )];
            $conditions = array('Entry.is_deleted'=>0, 'Entry.status'=>1);
            $fields = array('Entry.id', 'Entry.crl', 'Entry.fat', 'Entry.snf', 'Entry.ltr', 'Entry.price', 'Entry.more', 'Entry.total','Entry.status', 'Entry.created', 'User.fname', 'User.address', 'Code.name');

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

    public function createEntry($data, $agentId, $role){
        $resData['status'] = 31;        
        foreach ($data as $key => $value) { 
            if($this->checkEntryData($agentId, $value)){                
                $res = $this->saveEntry($agentId, $value, $role);
                if($res['status']==1){
                    $resData['status'] =1;
                }
            }
        }
        return $resData;
    }

    private function checkEntryData($agentId, $value){
        $st = true;
        
        if(empty($agentId)){ 
            $st = false;

        }else if(empty($value['code'])){ 
            $st = false;

        }else if(!empty($value['code']) && !is_numeric($value['code'])){ 
            $st = false;

        }else if(empty($value['crl'])){ 
            $st = false;

        }else if(!empty($value['crl']) && !is_numeric($value['crl'])){ 
            $st = false;

        }else if(empty($value['fat'])){ 
            $st = false;
            
        }else if(!empty($value['fat']) && !is_numeric($value['fat'])){ 
            $st = false;

        }else if(empty($value['snf'])){ 
            $st = false;

        }else if(!empty($value['snf']) && !is_numeric($value['snf'])){ 
            $st = false;

        }else if(empty($value['ltr'])){ 
            $st = false;

        }else if(!empty($value['ltr']) && !is_numeric($value['ltr'])){ 
            $st = false;

        }else if(empty($value['price'])){ 
            $st = false;

        }else if(!empty($value['price']) && !is_numeric($value['price'])){ 
            $st = false;

        }else if(empty($value['total'])){ 
            $st = false;
            
        }else if(!empty($value['total']) && !is_numeric($value['total'])){ 
            $st = false;

        }else if(empty($value['more'])){ 
            $st = false;

        }else if(!empty($value['more']) && !is_numeric($value['more'])){ 
            $st = false;

        }
        return $st;
    }


    public function getManagerData($userId, $date){ 
        $arrResponse['status'] = 0;  
        $data = array();
        $agentData = array();
        $agentId = array();
        $managerData = array();
        $codeId = array();
        $joins = [
                array('table' => 'users',
                    'alias' => 'User',
                    'type' => 'left',
                    'conditions' => array(
                        'Entry.user_id = User.id'
                    )
                ),
                array('table' => 'codes',
                    'alias' => 'Code',
                    'type' => 'left',
                    'conditions' => array(
                        'Entry.code_id = Code.id'
                    )
                )                
            ];
        $conditions = array('DATE(Entry.created)'=>date('Y-m-d', $date), 'User.parent_id'=>$userId, 'Entry.is_deleted'=>0);
        $fields = array('Entry.*', 'User.id', 'User.fname', 'User.email', 'User.mobile_no', 'User.agentId', 'Code.name', 'Code.type', 'Code.mobile_no');

        $priceInfo = $this->find('all', array('joins'=>$joins,'conditions'=>$conditions, 'fields'=>$fields));  
              
        if(!empty($priceInfo)){
            foreach ($priceInfo as $key => $value) {
                if(in_array($value['Entry']['code_id'], $codeId)){
                    if($value['Entry']['ltr'] > 0){
                        $data[$value['Entry']['code_id']]['total_ltr']['sum'] +=  $value['Entry']['ltr'];
                        $data[$value['Entry']['code_id']]['total_ltr']['count'] += 1;

                    }
                    
                    if($value['Entry']['fat'] > 0){
                        $data[$value['Entry']['code_id']]['fat_avg']['sum'] += $value['Entry']['fat'];
                        $data[$value['Entry']['code_id']]['fat_avg']['count'] += 1;
                    }
                    
                    if($value['Entry']['snf'] > 0){
                        $data[$value['Entry']['code_id']]['snf_avg']['sum'] += $value['Entry']['snf'];
                        $data[$value['Entry']['code_id']]['snf_avg']['count'] += 1;
                    }
                    
                    if($value['Entry']['price'] > 0){
                        $data[$value['Entry']['code_id']]['price_avg']['sum'] += $value['Entry']['price'];
                        $data[$value['Entry']['code_id']]['price_avg']['count'] += 1;
                    }
                    
                    if($value['Entry']['crl'] > 0){
                        $data[$value['Entry']['code_id']]['crl_avg']['sum'] += $value['Entry']['crl'];
                        $data[$value['Entry']['code_id']]['crl_avg']['count'] += 1;
                    }
                    
                    if($value['Entry']['total'] > 0){
                        $data[$value['Entry']['code_id']]['total_price']['sum'] += $value['Entry']['total'];
                        $data[$value['Entry']['code_id']]['total_price']['count'] += 1;
                    }                    

                }else{
                    $codeId[] = $value['Entry']['code_id'];
                    $data[$value['Entry']['code_id']] = array(
                            'total_ltr' => array('sum'=>$value['Entry']['ltr'], 'count'=>(($value['Entry']['ltr'] > 0) ? 1 : 0)),
                            'fat_avg' => array('sum'=>$value['Entry']['fat'], 'count'=>(($value['Entry']['fat'] > 0) ? 1 : 0)),
                            'snf_avg' => array('sum'=>$value['Entry']['snf'], 'count'=>(($value['Entry']['snf'] > 0) ? 1 : 0)),
                            'price_avg' => array('sum'=>$value['Entry']['price'], 'count'=>(($value['Entry']['price'] > 0) ? 1 : 0)),
                            'crl_avg' => array('sum' =>$value['Entry']['crl'], 'count' => (($value['Entry']['crl'] > 0) ? 1 : 0)),
                            'total_price' => array('sum'=>$value['Entry']['total'], 'count'=>(($value['Entry']['total'] > 0) ? 1 : 0)),

                            'code' => array(
                                'name'=> $value['Code']['name'], 
                                'type'=> ($value['Code']['type'] ? 1 : 0), 
                                'mobile_no'=>$value['Code']['mobile_no'],
                                'agent_name' => $value['User']['fname'],
                                'agent_id'=>$value['User']['id'])
                        );
                }

            }
            if(!empty($data)){
                foreach ($data as $key => $v) {
                    $managerData[] = array('code'=>$v['code'],'entry'=>array(
                        'total_ltr' => $v['total_ltr']['sum'], 
                        'fat_avg'=> ($v['fat_avg']['count'] > 0) ? $v['fat_avg']['sum']/$v['fat_avg']['count'] : $v['fat_avg']['sum'],
                        'snf_avg'=> ($v['snf_avg']['count'] > 0) ? $v['snf_avg']['sum']/$v['snf_avg']['count'] : $v['snf_avg']['sum'],
                        'price_avg'=> ($v['price_avg']['count'] > 0) ? $v['price_avg']['sum']/$v['price_avg']['count'] : $v['price_avg']['sum'],
                        'crl_avg' => ($v['crl_avg']['count'] > 0) ? $v['crl_avg']['sum']/$v['crl_avg']['count'] : $v['crl_avg']['sum'],
                        'total_price'=>$v['total_price']['sum']));
                }
            }
            $arrResponse['data'] = array('managerData'=>$managerData);
        }
        if(!empty($managerData)){
            $arrResponse['status'] = 1; 
        }

        return $arrResponse;
    }

} /*** Class End Here ***/
