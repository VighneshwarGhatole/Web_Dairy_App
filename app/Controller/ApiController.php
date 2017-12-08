<?php

/**
 * Api controller.
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
 * Api controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/api-controller.html
 */
class ApiController extends AppController {

    public $components = array('RequestHandler', 'Utility');

    private function getStatusCode($id) {
        $codes = Array(
            0 => array('code' => 100, 'message' => 'Success'),
            1 => array('code' => 101, 'message' => 'Failed.'),
            2 => array('code' => 102, 'message' => 'Invalid Password.'),
            3 => array('code' => 103, 'message' => 'User has been disabled by adminstrator.'),
            4 => array('code' => 104, 'message' => 'Invalid User.'),
            5 => array('code' => 105, 'message' => 'Invalid request.'),
            6 => array('code' => 106, 'message' => 'Something went wrong!!!'),
            7 => array('code' => 107, 'message' => 'Please enter correct username and password.'),
            8 => array('code' => 108, 'message' => 'Invalid Access, Please login to access your account'),
            9 => array('code' => 201, 'message' => ''),
            10 => array('code' => 202, 'message' => 'Incorrect input data.'),
            11 => array('code' => 203, 'message' => 'Data Not Found.'),
            12 => array('code' => 204, 'message' => 'Old password not matched.'),
            13 => array('code' => 205, 'message' => ''),
            14 => array('code' => 206, 'message' => ''),
            15 => array('code' => 207, 'message' => ''),
            16 => array('code' => 208, 'message' => 'Invalid request method name.'),
            17 => array('code' => 301, 'message' => 'Agent name can not be blank.'),
            18 => array('code' => 302, 'message' => 'Agent ID can not be blank.'),
            19 => array('code' => 303, 'message' => 'Agent ID already exists.'),
            20 => array('code' => 304, 'message' => 'Email should not be blank.'),
            21 => array('code' => 305, 'message' => 'Invalid email address.'),
            22 => array('code' => 306, 'message' => 'Password should not be blank.'),
            23 => array('code' => 307, 'message' => 'Confirm Password should not be blank.'),
            24 => array('code' => 308, 'message' => 'Password does not match.'),
            25 => array('code' => 401, 'message' => 'Address should not be blank.'),
            26 => array('code' => 402, 'message' => 'Agent ID should be atleast 8 characters.'),
            27 => array('code' => 403, 'message' => 'Password should be atleast 8 characters.'),
            28 => array('code' => 404, 'message' => 'Invalid customer ID.'),
            29 => array('code' => 405, 'message' => 'Please check customer data'),
            30 => array('code' => 406, 'message' => 'Please check price data'),
            31 => array('code' => 407, 'message' => 'Please check Entry input data'),
            32 => array('code' => 501, 'message' => 'Please check Expense input data'),
            33 => array('code' => 502, 'message' => 'LTR can not be blank or should be numeric.'),
            34 => array('code' => 503, 'message' => 'Total can not be blank or should be numeric.'),
            35 => array('code' => 504, 'message' => 'Code does not exist.'),
            36 => array('code' => 505, 'message' => 'Invalid CLR'),
            37 => array('code' => 506, 'message' => 'Expense can not be blank or should be numeric.'),
        );
        return (isset($codes[$id])) ? $codes[$id] : '';
    }

    private function checkRequest($dataReq, $reqType){
    	$outputArray['status'] = 5;
    	switch ($reqType){
    		case 'signup':
    			if ($dataReq->is('post')){
    				App::import('model', 'User');
    				$User = new User();
    				$validRes = $User->checkInputParams($dataReq);
    				if($validRes['status']==1){
    					$outputArray['status'] =  1;  //when all case are successed.
    				}else{
    					$outputArray['status'] = $validRes['status'];
    				}
    			}else{
    				$outputArray['status'] = 16;
    			}
    			break;

    		case 'login':
    			if ($dataReq->is('post')){
    				App::import('model', 'User');
    				$User = new User();
    				$validRes = $User->checkLoginInputParams($dataReq);
    				if($validRes['status']==1){
    					$outputArray['status'] =  1;  //when all case are successed.
    				}else{
    					$outputArray['status'] = $validRes['status'];
    				}
    			}else{
    				$outputArray['status'] = 16;
    			}
    		   break;

            case 'entry':
                if ($dataReq->is('post')){
                    App::import('model', 'Entry');
                    $Entry = new Entry();
                    $validRes = $Entry->checkEntryInputParams($dataReq);
                    if($validRes['status']==1){
                        $outputArray['status'] =  1;  //when all case are successed.
                    }else{
                        $outputArray['status'] = $validRes['status'];
                    }
                }else{
                    $outputArray['status'] = 16;
                }
                break;

            case 'addexpenses':
                if ($dataReq->is('post')){
                    App::import('model', 'Expense');
                    $Expense = new Expense();
                    $validRes = $Expense->checkExpenseInputParams($dataReq);
                    if($validRes['status']==1){
                        $outputArray['status'] =  1;  //when all case are successed.
                    }else{
                        $outputArray['status'] = $validRes['status'];
                    }
                }else{
                    $outputArray['status'] = 16;
                }
                break;

            case 'syncdata':
            	if ($dataReq->is('post')){
            		$outputArray['status'] =  1;  //when all case are successed.
            	}else{
            		$outputArray['status'] = 16;
            	}
            	break;
            case 'getmanagerinfo':
            	if ($dataReq->is('post')){
            		$outputArray['status'] =  1;  //when all case are successed.
            	}else{
            		$outputArray['status'] = 16;
            	}
            	break;
    	}
    	return $outputArray;
    }

    public function login(){ 
    	$this->layout = FALSE;
        $outputArray['status'] = 0;
        $outputArray['resultData'] = array();
        try{
        	$resChk = $this->checkRequest($this->request, 'login');
        	if($resChk['status']==1){
        		App::import('model', 'User');
    			$User = new User();
    			$userInfo = $User->getUserInfo($this->request->data['agentId']);
    			if(!empty($userInfo)){
    				if($userInfo['User']['password'] === base64_encode($this->request->data['password'])){
    					if($userInfo['User']['status']==1){
    						if($userInfo['User']['is_deleted']<>1){
    							$token = $User->randomToken();
    							$modified = date('Y-m-d H:i:s', time());
    							if($User->updateUserInfo(array('User.token' => '"' . $token . '"','User.modified' => '"'.$modified.'"'), array('User.id'=>$userInfo['User']['id']))){
	    							$outputArray['status'] = 1;
	    							$code = $this->getStatusCode(0);
	    							$outputArray['resultData'] = array(
	    							              'id' => $userInfo['User']['id'],
	    							              'name' => $userInfo['User']['fname'],
	    							              'agentId' => base64_decode($userInfo['User']['agentId']),
	    							              'email' => $userInfo['User']['email'],
	    							              'address' => $userInfo['User']['address'],
	    							              'token' => $token,
	    							              'phone' => $userInfo['User']['mobile_no']
	    							        );
    							}else{
    								$code = $this->getStatusCode(6);
    							}
    						}else{
    							$code = $this->getStatusCode(3);
    						}
    					}else{
    						$code = $this->getStatusCode(3);
    					}
    				}else{
    					$code = $this->getStatusCode(2);
    				}
    			}else{
    				$code = $this->getStatusCode(28);
    			}
        	}else{
        		$code = $this->getStatusCode($resChk['status']);
        	}
        }catch(Exception $e){
        	$code['code'] = $e->getCode();
        	$code['message'] = $e->getMessage();
        }
        $outputArray['code'] = $code['code'];
    	$outputArray['message'] = $code['message'];
        echo json_encode($outputArray);
        exit;
    }

    
    /******
        @author : dasarath 
        @method : post
        @dataType : json
        @return type : json
        @description : Api used to create a new user, customerId is unique, 
    */
    public function logOut(){
        $this->layout = FALSE;
        $outputArray['status'] = 0;
        $outputArray['resultData'] = array();
        
        try{    
            $resChk = $this->checkRequest($this->request, 'logout');
             if (!empty(trim($this->request->header('token')))) {
                    $params['token'] = $this->request->header('token');
                    $userId = $resToken = $User->getUserToken($params);
                    
                App::import('model', 'User');
                $User = new User();
                $res = $User->createUser($this->request);
                if($res['status']==1){
                    $outputArray['status'] = 1; // when all case successed.
                    $code = $this->getStatusCode(0);
                    $outputArray['resultData'] = array(
                                'id' => $User->id,                       
                                'name' => $this->request->data['name'],
                                'customerId' => $this->request->data['customerId'],
                                'email' => $this->request->data['email'],
                                'address' => $this->request->data['address']
                            );
                }else{
                    $code = $this->getStatusCode(6);
                }
            }else{
                $code = $this->getStatusCode($resChk['status']);
            }
        }catch(Exception $e){
            $code['code'] = $e->getStatusCode();
            $code['message'] = $e->getMessage();
        }
        $outputArray['code'] = $code['code'];
        $outputArray['message'] = $code['message'];
        echo json_encode($outputArray);
        exit;
    }

    public function entry(){
        $this->layout = FALSE;
        $outputArray['status'] = 0;
        $outputArray['resultData'] = array();
        try{
            $resChk = $this->checkRequest($this->request, 'entry');
            if($resChk['status']==1){
                if (!empty(trim($this->request->header('token')))){
                    App::import('model', 'User');
                    $User = new User();
                    $params['token'] = $this->request->header('token');
                    $resToken = $User->getUserToken($params);
                    if (!empty($resToken)){
                        App::import('model', 'Entry');
                        $Entry = new Entry();
                        $res = $Entry->createEntry($this->request, $resToken);
                        if($res['status']==1){
                            $outputArray['status'] = 1;
                            $code = array('code'=>100 , 'message'=>'Your entry has been saved successfully.');
                            $outputArray['resultData'] = array('id'=>$Entry->id);
                        }else{
                            $code = $this->getStatusCode($res['status']);
                        }                        
                    }else{
                        $code = $this->getStatusCode(8);
                    }
                }else{
                    $code = $this->getStatusCode(8);
                }
            }else{
                $code = $this->getStatusCode($resChk['status']);
            }
        }catch(Exception $e){
            $code['code'] = $e->getCode();
            $code['message'] = $e->getMessage();
        }

        $outputArray['code'] = $code['code'];
        $outputArray['message'] = $code['message'];
        echo json_encode($outputArray);
        exit;
    }

    public function addexpenses(){
        $this->layout = FALSE;
        $outputArray['status'] = 0;
        $outputArray['resultData'] = array();
        try{
            $resChk = $this->checkRequest($this->request, 'addexpenses');
            if($resChk['status']==1){
                if (!empty(trim($this->request->header('token')))){
                    App::import('model', 'User');
                    $User = new User();
                    $params['token'] = $this->request->header('token');
                    $resToken = $User->getUserToken($params);
                    if (!empty($resToken)){
                        App::import('model', 'Expense');
                        $Expense = new Expense();
                        $res = $Expense->createExpense($this->request, $resToken);
                        if($res['status']==1){
                            $outputArray['status'] = 1;
                            $code = array('code'=>100, 'message'=>'Your expenses has been saved successfully');
                            $outputArray['resultData'] = array('id'=>$Expense->id);
                        }else{
                            $code = $this->getStatusCode($res['status']);
                        }
                    }else{
                        $code = $this->getStatusCode(8);
                    }
                }else{
                    $code = $this->getStatusCode(8);
                }
            }else{
                $code = $this->getStatusCode($resChk['status']);
            }
        }catch(Exception $e){
            $code['code'] = $e->getCode();
            $code['message'] = $e->getMessage();
        }
        $outputArray['code'] = $code['code'];
        $outputArray['message'] = $code['message'];
        echo json_encode($outputArray);
        exit;
    }

    public function syncData(){
    	$this->layout = FALSE;
        $outputArray['status'] = 0;
        $outputArray['resultData'] = array();
        $syncRes = array();
        try{
        	$resChk = $this->checkRequest($this->request, 'syncdata');
        	if($resChk['status']==1){
        		if (!empty(trim($this->request->header('token')))){
        			App::import('model', 'User');
                    $User = new User();
                    $params['token'] = $this->request->header('token');
                    $resInfo = $User->getUserInfoByToken($params);                    
                    if (!empty($resInfo)){
                        $resToken = $resInfo['User']['id'];
                        $role = $resInfo['User']['role'];
                    	if(!empty($this->request->data)){
                    		/*********** Customer **********************/
                    		if(!empty($this->request->data['customer'])){
                    			App::import('model', 'Code');
                    			$Code = new Code();
                    			$res = $Code->createCode($this->request->data['customer'], $resToken);                    			
                    			if($res['status']==1){
                    				$outputArray['status'] = 1;
		                            $code = array('code'=>100, 'message'=>'');
		                            $syncRes['customer'] = array('status'=>1, 'message'=>'Your Code has been saved successfully');
		                            
                    			}else{
                    				$code = $this->getStatusCode($res['status']);
                    				$syncRes['customer'] = array('status'=>0, 'message'=>$code['message']);
                    			}
                    		}
                    		/*********** Price ***********************/
                    		if(!empty($this->request->data['price'])){
                    			App::import('model', 'Price');
                    			$Price = new Price();
                    			$res = $Price->createPrice($this->request->data['price'], $resToken);                    			
                    			if($res['status']==1){
                    				$outputArray['status'] = 1;
		                            $code = array('code'=>100, 'message'=>'');
		                            $syncRes['price'] = array('status'=>1, 'message'=>'Your price has been saved successfully');
		                            
                    			}else{
                    				$code = $this->getStatusCode($res['status']);
                    				$syncRes['price'] = array('status'=>0, 'message'=>$code['message']);
                    			}
                    		}
                    		/********* Entry ************************/
                    		if(!empty($this->request->data['entry'])){
                    			App::import('model', 'Entry');
                    			$Entry = new Entry();
                    			$res = $Entry->createEntry($this->request->data['entry'], $resToken, $role);                   			
                    			if($res['status']==1){
                    				$outputArray['status'] = 1;
		                            $code = array('code'=>100, 'message'=>'');
		                            $syncRes['entry'] = array('status'=>1, 'message'=>'Your Entry has been saved successfully');
		                            
                    			}else{
                    				$code = $this->getStatusCode($res['status']);
                    				$syncRes['entry'] = array('status'=>0, 'message'=>$code['message']);
                    			}
                    		}
                    		/********* Expense ************************/
                    		if(!empty($this->request->data['expense'])){
                    			App::import('model', 'Expense');
                    			$Expense = new Expense();
                    			$res = $Expense->createExpense($this->request->data['expense'], $resToken);                    			
                    			if($res['status']==1){
                    				$outputArray['status'] = 1;
		                            $code = array('code'=>100, 'message'=>'');
		                            $syncRes['expense'] = array('status'=>1, 'message'=>'Your Expense has been saved successfully');
		                            
                    			}else{
                    				$code = $this->getStatusCode($res['status']);
                    				$syncRes['expense'] = array('status'=>0, 'message'=>$code['message']);
                    			}
                    		}
                    		$outputArray['resultData'] = $syncRes;
                    	}else{
                    		$code = $this->getStatusCode(10);
                    	}
                    }else{
                        $code = $this->getStatusCode(8);
                    }
        		}else{
        			$code = $this->getStatusCode(8);
        		}
        	}else{
        		$code = $this->getStatusCode($resChk['status']);
        	}
        }catch(Exception $e){
        	$code['code'] = $e->getCode();
            $code['message'] = $e->getMessage();
        }
        $outputArray['code'] = $code['code'];
        $outputArray['message'] = $code['message'];
        echo json_encode($outputArray);
        exit;
    }

    
    public function getManagerInfo(){
    	$this->layout = FALSE;
        $outputArray['status'] = 0;
        $outputArray['resultData'] = array();
        $syncRes = array();
        try{ 
        	$resChk = $this->checkRequest($this->request, 'getmanagerinfo');
        	if($resChk['status']==1){ 
        		if (!empty(trim($this->request->header('token')))){
        			App::import('model', 'User');
                    $User = new User();
                    $params['token'] = $this->request->header('token');
                    $resInfo = $User->getUserInfoByToken($params);
                    if(!empty($resInfo)){
                    	$resToken = $resInfo['User']['id'];
                        $role = $resInfo['User']['role'];                       
                        if(!empty($this->request->data['date'])){ 
                        	if($role==2){
                        		App::import('model', 'Entry');
        						$Entry = new Entry();
                                $agentInfo = $User->find('all', array('conditions'=>array('User.status'=>1, 'User.role'=>0, 'User.parent_id'=>$resToken), 'fields'=>array('User.fname', 'User.id', 'User.mobile_no', 'User.email')));
                                $agentData = array();
                                if(!empty($agentInfo)){
                                    foreach ($agentInfo as $key => $value) {
                                        $agentData[] = $value['User'];
                                    }
                                }

        						$res = $managerData = $Entry->getManagerData($resToken, $this->request->data['date']);
        						if($res['status']==1){ 
        							$outputArray['status'] = 1;
		                            $code = array('code'=>100, 'message'=>'Success');
                                    $res['data']['agentData'] = $agentData;
		                            $outputArray['resultData'] = $res['data'];

        						}elseif(!empty($agentData)){
                                    $outputArray['status'] = 1;
                                    $code = array('code'=>100, 'message'=>'Success');                                    
                                    $outputArray['resultData'] = array('managerData'=>array(), 'agentData'=>$agentData);
                                }else{
        							$code = $this->getStatusCode(11);
        						}
                        	}else{
                        		$code = $this->getStatusCode(8);
                        	}
                        }else{
                        	$code = $this->getStatusCode(10);
                        }
                    }else{
                    	$code = $this->getStatusCode(8);
                    }
        		}else{
        			$code = $this->getStatusCode(8);
        		}
        	}else{
        		$code = $this->getStatusCode($resChk['status']);
        	}
        }catch(Exception $e){
        	$code['code'] = $e->getCode();
            $code['message'] = $e->getMessage();
        }
    	
    	$outputArray['code'] = $code['code'];
        $outputArray['message'] = $code['message'];
        echo json_encode($outputArray);
        exit;

    }
    
} /*** class end *****/
