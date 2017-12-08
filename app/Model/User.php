<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('CakeTime', 'Utility');

/**
 * User Model
 *
 */
class User extends AppModel {

    public function authenticateUser($requestData, $resultType = NULL) {
        $arrResponse = array();
        $arrResponse['status'] = 0;
        try {
            $conditions = array('User.agentId'=>base64_encode($requestData['User']['username']), 'User.role'=>1);
            $userInfo = $this->find('first', array('conditions'=>$conditions));
            if(!empty($userInfo)){
                $arrResponse['status'] = 1;
                $arrResponse['resultData'] = $userInfo;
            }
            
        } catch (Exception $e) {
            $arrResponse['status'] = 0;
            $arrResponse['message'] = $e->getMessage();
        }

        if ($resultType == 'json') {
            return json_encode($arrResponse);
        } else {
            return $arrResponse;
        }
    }

    public function findAgentData($roleId, $resultType = NULL){
        $arrResponse = array();
        $arrResponse['status'] = 0;
        try{

            $this->belongsTo = array( 
                'ParentGroup' => 
                    array('className' => 'User', 
                          'foreignKey' => 'parent_id'
                )); 

            $this->hasMany = array( 
                'ChildGroup' => 
                    array('className' => 'User', 
                          'foreignKey' => 'parent_id'
                    )); 

            $conditions = array('User.role'=>$roleId, 'User.is_deleted'=>0);            
            $userInfo = $this->find('all', array('conditions'=>$conditions));
            //pr($userInfo);
            if(!empty($userInfo)){
                $arrResponse['status'] = 1;
                $arrResponse['resultData'] = $userInfo;
            }else{
                $arrResponse['message'] = "No record found";
            }
        }catch(Exception $e){
            $arrResponse['status'] = 0;
            $arrResponse['message'] = $e->getMessage();
        }
        if ($resultType == 'json') {
            return json_encode($arrResponse);
        } else {
            return $arrResponse;
        }
    }

    
    public function checkAgentUsername($username, $userId=''){
        if($userId!=''){
            $conditions = array('User.agentId'=>base64_encode($username), 'User.id <>'=>$userId);
        }else{
            $conditions = array('User.agentId'=>base64_encode($username));
        }
        
        $ucheck = $this->find('count', array('conditions'=>$conditions));
        if($ucheck > 0){
            return true;
        }else{
            return false;
        }

    }

    public function createUser($contentArr) {
        $arrResultSet = array();
        try {
            
            if (!empty($contentArr['User']['id'])) {
                $this->id = $contentArr['User']['id'];
            } else {
                $this->create();
            }
            if ($this->save($contentArr)) {
                $arrResultSet['status'] = 1;
                if (!empty($contentArr['User']['id'])) {
                    $arrResultSet['User'] = $contentArr['User']['id'];
                } else {
                    $arrResultSet['User'] = $this->getInsertID();
                }  
                
            } else {
                $arrResultSet['status'] = 0;
                $arrResultSet['message'] = 'Error found.';
            }
        } catch (Exception $e) {
            $arrResultSet['status'] = 0;
            $arrResultSet['message'] = $e->getMessage();
        }
        return $arrResultSet;
    }

    public function checkUniqueUsername($username) { 
        $condition = array("User.agentId" => base64_encode($username) /*, "User.is_deleted" => 0*/);        
        $result = $this->find("count", array("conditions" => $condition));        
        return $result;
    }
    public function randomToken($username = '', $createdDateTimestamp = '') {     
        $random_pass = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1, 32))), 1, 32);
        return $random_pass;
    }

    public function checkInputParams($dataReq){
        if((!empty($dataReq->data['name']))){
            if(!empty($dataReq->data['customerId'])){ 
                if(strlen($dataReq->data['customerId']) >= 8){
                    $uniqueRes = $this->checkUniqueUsername($dataReq->data['customerId']);
                    if($uniqueRes==0){
                        if(!empty($dataReq->data['email'])){
                            if (filter_var($dataReq->data['email'], FILTER_VALIDATE_EMAIL)){
                                if(!empty($dataReq->data['password'])){
                                    if(strlen($dataReq->data['password']) >= 8){
                                        if(!empty($dataReq->data['confirmPassword'])){
                                            if((base64_encode($dataReq->data['password']))===(base64_encode($dataReq->data['confirmPassword']))){
                                                if(!empty($dataReq->data['address'])){
                                                    $outputArray['status'] = 1;
                                                }else{
                                                    $outputArray['status'] = 25;
                                                }
                                            }else{
                                                $outputArray['status'] = 24;
                                            }                                            
                                        }else{
                                            $outputArray['status'] = 23;
                                        }
                                    }else{
                                        $outputArray['status'] = 27;
                                    }
                                }else{
                                    $outputArray['status'] = 22;
                                }
                            }else{
                                $outputArray['status'] = 21;
                            }                            
                        }else{
                            $outputArray['status'] = 20;
                        }
                    }else{
                        $outputArray['status'] = 19;
                    }  
                }else{
                    $outputArray['status'] = 26;
                }
            }else{
                $outputArray['status'] = 18;
            }
        }else{
            $outputArray['status'] = 17;
        }
        return $outputArray;
    }

    public function saveData($data, $resultType = NULL) {
        $arrResponse = array();
        try {
            $this->create();
            if ($this->save($data)) {
                $arrResponse['status'] = 1;                
                $arrResponse['id'] = $this->id;
            } else {
                $arrResponse['status'] = 0;
                $arrResponse['message'] = 'The content could not be saved. Please, try again.';
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

    public function checkLoginInputParams($dataReq){
        if(!empty($dataReq->data['agentId'])){
            if(strlen($dataReq->data['agentId']) >= 8){
                if(!empty($dataReq->data['password'])){
                    if(strlen($dataReq->data['password']) >= 8){
                        $uniqueRes = $this->checkUniqueUsername($dataReq->data['agentId']);
                        if($uniqueRes > 0){
                            $outputArray['status'] = 1;
                        }else{
                            $outputArray['status'] = 28;
                        }
                    }else{
                        $outputArray['status'] = 27;
                    }
                }else{
                    $outputArray['status'] = 22;
                }
            }else{
                $outputArray['status'] = 26;
            }
        }else{
            $outputArray['status'] = 18;
        }
        return $outputArray;
    }

    public function getUserInfo($customerId){
        $conditions = array('User.agentId'=>base64_encode($customerId), 'User.role <> '=>1);
        return $this->find('first', array('conditions'=>$conditions));
    }

    public function getManagerList(){
        $conditions = array('User.status'=>1,'User.is_deleted'=>0, 'User.role'=>2);
        return $this->find('all',array('conditions'=>$conditions,'fields'=>array('User.id', 'User.fname')));
        
    }

    public function updateUserInfo($fields, $conditions){
        if($this->updateAll($fields, $conditions)){
            return true;
        }else{
            return false;
        }
    }    

    public function getUserToken($params){        
        $conditions = array('status'=>1,'is_deleted'=>0,'token'=>$params['token']);
        $resToken = $this->find('first',array('conditions'=>$conditions,'fields'=>array('id')));
        if(!empty($resToken)){
            return $resToken['User']['id'];
        } else {
            return false;
        }
    }

    public function getUserInfoByToken($params){        
        $conditions = array('status'=>1,'is_deleted'=>0,'token'=>$params['token']);
        $resToken = $this->find('first',array('conditions'=>$conditions,'fields'=>array('id','role')));
        if(!empty($resToken)){
            return $resToken;
        } else {
            return false;
        }
    }

    public function getAgentInfo($Id){
        $conditions = array('User.id'=>$Id, 'User.role <>'=>1, 'User.is_deleted'=>0);
        $fields = array('User.id', 'User.fname', 'User.address', 'User.email', 'User.mobile_no', 'User.agentId', 'User.password','User.status', 'User.role', 'User.parent_id');
        return $this->find('first', array('conditions'=>$conditions, 'fields'=>$fields));
    }
    
    public function removeAgentInfo(){



        
    }
}
