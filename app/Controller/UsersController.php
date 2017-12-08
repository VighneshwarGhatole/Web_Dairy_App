<?php
/**
 * Users controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Users controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/users-controller.html
 */
class UsersController extends AppController {

    public $uses = array('User');
    public $components = array();

    
    public function login() {		 
		if (strstr(ABSOLUTE_URL, 'localhost')) {
			$redirectUrl = ABSOLUTE_URL;
		} else {
			$redirectUrl = str_replace('http', 'https', ABSOLUTE_URL);
			$redirectUrl = str_replace('httpss', 'https', $redirectUrl);
		}
        
		if ($this->Session->read('UserDetails.id') > 0) { 
            header('Location:'. $redirectUrl.'Users/agentlist');
            exit;
        }
        $this->layout = 'default';

        $outputArray = array();
        $outputArray['status'] = 0;
        $outputArray['message'] = '';
        try {
            if ($this->request->is('post')) {
                if (!empty($this->request->data)) {
					if (empty($this->request->data['User']['username']) && empty($this->request->data['User']['password'])) {
						$outputArray['message'] = 'Please enter your username and password.';

					}else if (empty($this->request->data['User']['username'])) {
						$outputArray['message'] = 'Please enter your username';

					} else if (empty($this->request->data['User']['password'])) {
						$outputArray['message'] = 'Please enter your password';

					} else if (!empty($this->request->data['User']['username']) && !empty($this->request->data['User']['password'])) {
                        $outputArray = $this->User->authenticateUser($this->request->data);
                    }
                }
            }
        } catch (Exception $e) {
            $outputArray['message'] = 'Something went wrong!!!';
        }
 

        if ($outputArray['status'] == 1) {
            $user = $outputArray['resultData']['User'];
            if($user['password']!=base64_encode($this->request->data['User']['password'])){
                $outputArray['message'] = 'You have entered incorrect password.';
            }elseif($user['status']<>1){
                $outputArray['message'] = 'Account is locked';
            }elseif($user['is_deleted']<>0){
                $outputArray['message'] = 'Account has removed';
            }elseif($user['role']<>1){ 
                $outputArray['message'] = 'Sorry!! Not Authorized.';
            }else{
                $this->Session->write('UserDetails.id', $user['id']);            
                $this->Session->write('UserDetails.fname', $user['fname']);
                $this->Session->write('UserDetails.email', $user['email']);
                $this->Session->write('UserDetails.pic', $user['pic']);
                $this->Session->write('UserDetails.roleId', $user['role']);
                header('Location:'. $redirectUrl.'Users/agentlist');
                exit;
            }
        }else{
            $outputArray['message'] = 'You have entered incorrect username';
        }

        $error = $outputArray['message'];
        $this->set('error', $error);        
        $this->set('title_for_layout', 'Agent List');
    }

    public function logout() {		
        $this->Session->destroy();
        $this->redirect(array('controller' => 'Users', 'action' => 'login'));
    }

    
    public function agentList() { 
        $this->layout = 'master';
        $Users = array();
        try {
            if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])) {
                $arrResponse = $this->User->findAgentData(0);
                
                if (isset($arrResponse['status']) && $arrResponse['status'] == 1) {
                    $Users = $arrResponse['resultData']; 
                                    
                } else {
                    $this->Flash->set(__($arrResponse['message']));
                }
            } else {
                $this->Flash->set(__('Sorry!! Not Authorized.'));
                $this->redirect(array('controller' => 'Users', 'action' => 'login'));
            }
        } catch (Exception $e) {
            $this->Flash->set(__('Something went wrong, Please try after some time'));
        }
        $this->set('Users', $Users); 
        $this->set('title_for_layout', 'Agent Listing');        
    }

    public function add(){
        $this->layout = 'master';
        $managerData = array();
        try{
            if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])) {
                $managerInfo = $this->User->getManagerList();  
                if(!empty($managerInfo)){
                    foreach ($managerInfo as $key => $value) {
                        $managerData[$value['User']['id']] = $value['User']['fname'];
                    }
                }
                if ($this->request->is('POST')) {
                    if (!empty($this->request->data)) {
                        $this->request->data['User']['created'] = date('Y-m-d H:i:s');
                        $this->request->data['User']['password'] = base64_encode($this->request->data['User']['password']);
                        $this->request->data['User']['agentId'] = base64_encode($this->request->data['User']['agentId']);

                        $arrResponse = $this->User->createUser($this->request->data);
                        if (isset($arrResponse['status']) && $arrResponse['status'] == 1) {
                            if($this->request->data['User']['role']==0){
                                $this->Flash->set(__('Agent is created successfully.'));
                                $this->redirect(array('controller' => 'Users', 'action' => 'agentlist'));

                            }elseif($this->request->data['User']['role']==2){
                                $this->Flash->set(__('Manager is created successfully.'));
                                $this->redirect(array('controller' => 'Users', 'action' => 'managerlist'));
                            }
                        } else {
                            $this->request->data['User']['password']='';
                            $this->request->data['User']['agentId']='';
                            $this->Flash->set(__('Something went wrong, Please try after some time'));
                        }
                    }
                }
            }else{
                $this->Flash->set(__('Sorry!! Not Authorized.'));
                $this->redirect(array('controller' => 'Users', 'action' => 'login'));
            }
        }catch(Exception $e){
            $this->Flash->set(__('Something went wrong, Please try after some time'));
        }
        $this->set('title_for_layout', 'Add Agent');     
        $this->set('managerData', $managerData);     
    }

    public function Users_validation() {
        $this->layout = FALSE;
        $errors = array();
        $responseArr = array('status' => 'success', 'errors' => array());
        $responseArr['data'] = $this->data;

        
        if(empty($this->data['User']['fname'])){
            $errors['fname'] = array('User name is required.');
        }elseif(empty($this->data['User']['email'])){
            $errors['email'] = array('User email is required.');
        }elseif(!empty($this->data['User']['email'])){
            if (!filter_var($this->data['User']['email'], FILTER_VALIDATE_EMAIL)) {
              $errors['email'] = array('Invalid agent email.');
            }
        }
        if(empty($this->data['User']['mobile_no'])){
            $errors['mobile_no'] = array('User mobile number is required.');
        }elseif(!empty($this->data['User']['mobile_no'])){
            if(ctype_digit(strval($this->data['User']['mobile_no']))){
                if(strlen($this->data['User']['mobile_no']) < 10){
                    $errors['mobile_no'] = array('Invalid User mobile number');
                }
            }else{
                $errors['mobile_no'] = array('Invalid User mobile number');
            }
        }
        $userId = '';
        if($this->data['dataType']=='edit'){
            $userId = $this->data['User']['id'];
        }
        if(empty($this->data['User']['agentId'])){
            $errors['agentId'] = array('User username is required');
        }elseif(!empty($this->data['User']['agentId'])){
            if(strlen($this->data['User']['agentId']) < 8){
                $errors['agentId'] = array('User username should be 8 character');
            }elseif($this->User->checkAgentUsername($this->data['User']['agentId'], $userId)){
                $errors['agentId'] = array('User username already exist');
            }
        }

        if(empty($this->data['User']['password'])){
            $errors['password'] = array('User password is required');
        }elseif(!empty($this->data['User']['password'])){
            if(strlen($this->data['User']['password']) < 8){
                $errors['password'] = array('User password should be 8 character');
            }
        }        

        if (count($errors) > 0) {
            $responseArr['dataType'] = $this->data['dataType'];            
            $responseArr['errors'] = $errors;
            $responseArr['status'] = 'error';

        }

        echo json_encode($responseArr);
        exit;
    }

    public function edit(){
        $this->layout = 'master';
        $agentInfo = array();
        try{
            if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])){

                $managerInfo = $this->User->getManagerList();  
                if(!empty($managerInfo)){
                    foreach ($managerInfo as $key => $value) {
                        $managerData[$value['User']['id']] = $value['User']['fname'];
                    }
                }
                if(isset($this->request->named) && !empty($this->request->named)){
                    if(isset($this->request->named['id']) && !empty($this->request->named['id'])){
                        $agentInfo = $this->User->getAgentInfo($this->request->named['id']);

                        if(!empty($agentInfo['User'])){
                            $agentInfo = $agentInfo['User'];
                            if($this->request->is('POST')){
                                if (!empty($this->request->data)){
                                    $this->request->data['User']['modified'] = date('Y-m-d H:i:s');
                                    $this->request->data['User']['password'] = base64_encode($this->request->data['User']['password']);
                                    $this->request->data['User']['agentId'] = base64_encode($this->request->data['User']['agentId']);
                                    $arrResponse = $this->User->createUser($this->request->data);
                                    if (isset($arrResponse['status']) && $arrResponse['status'] == 1) {
                                        if($this->request->data['User']['role']==0){
                                            $this->Flash->set(__('Agent Information is Updated successfully.'));
                                            $this->redirect(array('controller' => 'Users', 'action' => 'agentlist'));

                                        }elseif($this->request->data['User']['role']==2){
                                            $this->Flash->set(__('Manager Information is Updated successfully.'));
                                            $this->redirect(array('controller' => 'Users', 'action' => 'managerlist'));
                                        }                                        
                                        
                                    } else {
                                        $this->request->data['User']['password']='';
                                        $this->request->data['User']['agentId']='';
                                        $this->Flash->set(__('Something went wrong, Please try after some time'));
                                    }
                                }
                            }

                            $this->request->data['User'] = $agentInfo;
                            $this->request->data['User']['agentId'] = base64_decode($agentInfo['agentId']);
                            $this->request->data['User']['password'] = base64_decode($agentInfo['password']);                            

                        }else{
                            $this->Flash->set(__('No data found to edit.'));
                            $this->redirect(array('controller' => 'Users', 'action' => 'agentlist'));
                        }
                    }else{
                        $this->Flash->set(__('Invalid agent ID'));
                        $this->redirect(array('controller' => 'Users', 'action' => 'agentlist'));   
                    }
                }else{
                    $this->Flash->set(__('Invalid request.'));
                    $this->redirect(array('controller' => 'Users', 'action' => 'agentlist'));
                }
            }else{
                $this->Flash->set(__('Sorry!! Not Authorized.'));
                $this->redirect(array('controller' => 'Users', 'action' => 'login'));
            }
        }catch(Exception $e){
            $this->Flash->set(__('Something went wrong, Please try after some time'));
        }
        $this->set('title_for_layout', 'Edit Agent'); 
        $this->set('agentInfo', $agentInfo); 
        $this->set('managerData', $managerData);  
    }

    public function delete(){
        try{
            if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])){
                if(isset($this->request->named) && !empty($this->request->named)){
                    if(isset($this->request->named['id']) && !empty($this->request->named['id'])){
                        $agentInfo = $this->User->getAgentInfo($this->request->named['id']);
                        if(!empty($agentInfo['User'])){
                            if($this->User->removeAgentInfo($this->request->named['id'])){
                                $this->Flash->set(__('Agent removed successfully.'));

                            }else{
                                $this->Flash->set(__('Something went wrong, Please try after some time'));
                            }
                            $this->redirect(array('controller' => 'Users', 'action' => 'agentlist'));
                        }else{
                            $this->Flash->set(__('No data found to remove.'));
                            $this->redirect(array('controller' => 'Users', 'action' => 'agentlist'));
                        }
                    }else{
                        $this->Flash->set(__('Invalid agent ID'));
                        $this->redirect(array('controller' => 'Users', 'action' => 'agentlist'));   
                    }
                }else{
                    $this->Flash->set(__('Invalid request.'));
                    $this->redirect(array('controller' => 'Users', 'action' => 'agentlist'));
                }
            }else{
                $this->Flash->set(__('Sorry!! Not Authorized.'));
                $this->redirect(array('controller' => 'Users', 'action' => 'login'));
            }
        }catch(Exception $e){
            $this->Flash->set(__('Something went wrong, Please try after some time'));
        }
        $this->render('agentlist');
    }
    
    public function customer(){ 
        $this->layout = 'master';
        $customerInfo=array();
        try{ 
            if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])){ 
                App::import('model', 'Code');
                $Code = new Code();
                $arrResponse = $Code->findCustomerData();
                
                if (isset($arrResponse['status']) && $arrResponse['status'] == 1) {
                    $customerInfo = $arrResponse['resultData']; 
                                    
                } else {
                    $this->Flash->set(__($arrResponse['message']));
                }
            }else{
                $this->Flash->set(__('Sorry!! Not Authorized.'));
                $this->redirect(array('controller' => 'Users', 'action' => 'login'));
            }
        }catch(Exception $e){
            $this->Flash->set(__('Something went wrong, Please try after some time'));
        }
        $this->set('customerInfo', $customerInfo); 
        $this->set('title_for_layout', 'Customer Listing'); 
    }

    public function price(){
        $this->layout = 'master';
        $priceInfo=array();
        try{
            if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])){ 
                App::import('model', 'Price');
                $Price = new Price();
                $arrResponse = $Price->findAgentPriceList();
                
                if (isset($arrResponse['status']) && $arrResponse['status'] == 1) {
                    $priceInfo = $arrResponse['resultData']; 
                                    
                } else {
                    $this->Flash->set(__($arrResponse['message']));
                }
            }else{
                $this->Flash->set(__('Sorry!! Not Authorized.'));
                $this->redirect(array('controller' => 'Users', 'action' => 'login'));
            }
        }catch(Exception $e){
            $this->Flash->set(__('Something went wrong, Please try after some time'));
        }
        $this->set('priceInfo', $priceInfo); 
        $this->set('title_for_layout', 'Price Listing'); 
    }

    public function entry(){
        $this->layout = 'master';
        $entryInfo=array();
        try{
            if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])){ 
                App::import('model', 'Entry');
                $Entry = new Entry();
                $arrResponse = $Entry->findAgentEntryList();
                
                if (isset($arrResponse['status']) && $arrResponse['status'] == 1) {
                    $entryInfo = $arrResponse['resultData']; 
                                    
                } else {
                    $this->Flash->set(__($arrResponse['message']));
                }
            }else{
                $this->Flash->set(__('Sorry!! Not Authorized.'));
                $this->redirect(array('controller' => 'Users', 'action' => 'login'));
            }
        }catch(Exception $e){
            $this->Flash->set(__('Something went wrong, Please try after some time'));
        }
        $this->set('entryInfo', $entryInfo); 
        $this->set('title_for_layout', 'Entry Listing');
    }

    public function expense(){
        $this->layout = 'master';
        $expenseInfo=array();
        try{
            if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])){ 
                App::import('model', 'Expense');
                $Expense = new Expense();
                $arrResponse = $Expense->findAgentExpenseList();
                
                if (isset($arrResponse['status']) && $arrResponse['status'] == 1) {
                    $expenseInfo = $arrResponse['resultData']; 
                                    
                } else {
                    $this->Flash->set(__($arrResponse['message']));
                }
            }else{
                $this->Flash->set(__('Sorry!! Not Authorized.'));
                $this->redirect(array('controller' => 'Users', 'action' => 'login'));
            }
        }catch(Exception $e){
            $this->Flash->set(__('Something went wrong, Please try after some time'));
        }
        $this->set('expenseInfo', $expenseInfo); 
        $this->set('title_for_layout', 'Expense Listing');

    }

    public function updateAgentStatus(){
        $outputArray['status'] = 0;
        $outputArray['message'] = "Sorry!! Not Authorized";
        $this->layout = false;
        $this->autorender = false;
        if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])) {
            if($this->request->is('ajax')){                
                if($this->Session->read('UserDetails.roleId')==Configure::read('UserRoles.ADMIN')){
                    if(isset($this->request->named['uId'])){
                        $agentInfo = $this->User->find('first', array('conditions'=>array('User.id'=>$this->request->named['uId'], 'User.role'=>'0', 'User.is_deleted'=>0)));
                        if(!empty($agentInfo)){
                            $st = (($agentInfo['User']['status']==1) ? 0 : 1);                            
                            $agentData['User']['id'] = $this->request->named['uId'];
                            $agentData['User']['status'] = $st;
                            if($this->User->createUser($agentData)){
                                $outputArray['message'] = (($agentInfo['User']['status']==1) ? 'In-Active' : 'Active');   
                                $outputArray['status'] = 1;
                            }
                        }
                    }
                }               
            }
        }
        echo json_encode($outputArray);
        exit();
    }

    public function updatemanagerstatus(){
        $outputArray['status'] = 0;
        $outputArray['message'] = "Sorry!! Not Authorized";
        $this->layout = false;
        $this->autorender = false;
        if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])) {
            if($this->request->is('ajax')){                
                if($this->Session->read('UserDetails.roleId')==Configure::read('UserRoles.ADMIN')){
                    if(isset($this->request->named['uId'])){
                        $agentInfo = $this->User->find('first', array('conditions'=>array('User.id'=>$this->request->named['uId'], 'User.role'=>2, 'User.is_deleted'=>0)));
                        if(!empty($agentInfo)){
                            $st = (($agentInfo['User']['status']==1) ? 0 : 1);                            
                            $agentData['User']['id'] = $this->request->named['uId'];
                            $agentData['User']['status'] = $st;
                            if($this->User->createUser($agentData)){
                                $outputArray['message'] = (($agentInfo['User']['status']==1) ? 'In-Active' : 'Active');   
                                $outputArray['status'] = 1;
                            }
                        }
                    }
                }               
            }
        }
        echo json_encode($outputArray);
        exit();
    }

    public function managerList(){
        $this->layout = 'master';
        $Users = array();
        try {
            if ($this->checkPageAccess($this->params['controller'] . '/' . $this->params['action'])) {
                $arrResponse = $this->User->findAgentData(2);
                
                if (isset($arrResponse['status']) && $arrResponse['status'] == 1) {
                    $Users = $arrResponse['resultData']; 
                                    
                } else { 
                    $this->Flash->set(__($arrResponse['message']));
                }
            } else {
                $this->Flash->set(__('Sorry!! Not Authorized.'));
                $this->redirect(array('controller' => 'Users', 'action' => 'login'));
            }
        } catch (Exception $e) {
            $this->Flash->set(__('Something went wrong, Please try after some time'));
        }
        $this->set('Users', $Users); 
        $this->set('title_for_layout', 'Manager Listing');
    }


}/********** Class End ****************/
