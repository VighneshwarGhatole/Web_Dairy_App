<?php

App::uses('CakeEventListener', 'Event');
App::uses('ClassRegistry', 'Utility');

class AuthorizationListener implements CakeEventListener {

/*
 * Timer instance signature
 *
 * @var \React\EventLoop\Timer\TimerInterface
 */
    private $__timer = null;

    /**
 * The ReactPHP event
 *
 * @var \React\EventLoop\LoopInterface
 */
    private $__loop;

    private $__connection;

    private $__connectionData;

    private $__wampServer;

    private $__event;

    private $batchList;

    private $prefix ='Authorization.';

    public function implementedEvents() {
        $eventArr = [];
        $eventArr['Rachet.WampServer.onSubscribe.'.$this->prefix.'Token' ] = 'validateToken';
        return $eventArr;
    }

    public function setVars($cakeEvent){
        $this->__event = $cakeEvent;
        $this->__wampServer = $cakeEvent->data['wampServer'];
        $this->__loop = $cakeEvent->data['wampServer']->getLoop();
        $this->__connection = $cakeEvent->data['connection'];
        $this->__connectionData = $cakeEvent->data['connectionData'];

        return true;
    }
    public function validateToken(CakeEvent $cakeEvent){
        $userAuthDetails = false;
        $validToken = 0;
        $this->setVars($cakeEvent);
        $userCookies = $this->__connection->WebSocket->request->getCookies();
        $clientIp = $this->__connection->remoteAddress;
        if(isset($userCookies['CakeCookie[chat_user]'])){
            $userAuthDetails = json_decode(rawurldecode($userCookies['CakeCookie[chat_user]']),true);
        }
        if(isset($userAuthDetails['id']) && $userAuthDetails['id'] > 0 && isset($userAuthDetails['token']) && !empty($userAuthDetails['token'])){
            $token =  $userAuthDetails['id'].strrev($userAuthDetails['token']); 
            
            $dbsource = ConnectionManager::getDataSource('default');
            if(!$dbsource->isConnected()){
                $dbsource->reconnect();
            }
            $ChatUser = ClassRegistry::init('ChatUser');         
            
            $conditions = [ 
                'user_id' => $userAuthDetails['id'],
                'token' => $token,
//                'ip_address' => $clientIp
                ];
            $fields = ['User.id','User.fname', 'User.lname','User.pic'];
            $validToken = $ChatUser->getUserDetail(array('conditions' => $conditions, 'fields' => $fields));
            
            $this->sendAutResponse($validToken);
            if(!empty($validToken)){
                if(isset($validToken['pic']) && !empty($validToken['pic']) ){
                    $validToken['pic'] = ASSETS_BASE_URL.$validToken['pic'];
                }
                $this->__connection->Session->set( $this->__connection->WAMP->sessionId, $validToken);
                return true;
            }
         }
        $this->sendAutResponse($validToken);
        $this->__connection->close();
        return false; 
    }

    public function sendAutResponse($result = []){
        if($result){
            $responseData = ['status' => 1, 'message' => 'Success'];
        }else{
            $responseData = ['status' => 0, 'message' => 'Failed'];
        }
        $this->__event->subject()->broadcast($this->prefix.'Token', $responseData);
    }

    public function usersList(CakeEvent $cakeEvent){
        $User = ClassRegistry::init('User');
        $usersList = $User->find('all', array(
            'field' => array('fname','lname'),
            'conditions' => array('User.status' =>1 ,'User.is_deleted'=>0 )
            ));
        $this->__loop = $cakeEvent->data['wampServer']->getLoop();
        $this->__connection = $cakeEvent->data['connection'];
        $this->__connectionData = $cakeEvent->data['connectionData'];
        $cakeEvent->subject()->broadcast($cakeEvent->data['topicName'], [
            'from' => 'subscribe',
            'users' => $usersList,
            
        ]);
    }

    public function newBroadcast($cakeEvent, $obj){

    }

    public function getAllSessions($cakeEvent){
    }
}


?>