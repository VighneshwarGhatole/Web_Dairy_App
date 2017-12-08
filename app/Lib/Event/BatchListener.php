<?php

App::uses('CakeEventListener', 'Event');
App::uses('ClassRegistry', 'Utility');

class BatchListener implements CakeEventListener {

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

    private $__session;
    private $__connectionData;
    private $__event;

    private $batchList;
    private $chatRoomList;

    private $prefix ='Batch.';

    private $roomBatch=[];

    private $Chat ;

    public function __construct(){

        $Batch = ClassRegistry::init('Batch');
        $ChatRoom = ClassRegistry::init('ChatRoom');
        $this->Chat = ClassRegistry::init('Chat');
        $chatRoomList = $ChatRoom->getAllRoom();
        if(!empty($chatRoomList) && count($chatRoomList)>0){
            $this->chatRoomList = $chatRoomList; 
        }
    }

    public function implementedEvents() {
        $eventArr = [];
        $roomBatch = [];
        if(!empty($this->chatRoomList) && count($this->chatRoomList)>0){
            foreach ($this->chatRoomList as $chatRoomData) {
                $chatRoomData = $chatRoomData['ChatRoom'];
                $roomBatch[$this->prefix.$chatRoomData['room_level_id']] = $chatRoomData['id'];
                $eventArr['Rachet.WampServer.onSubscribe.'.$this->prefix.$chatRoomData['room_level_id'] ] = 'usersList';
                $eventArr['Rachet.WampServer.onPublish.'.$this->prefix.$chatRoomData['room_level_id'] ] = 'newMessage';
                $eventArr['Rachet.WampServer.onPublish' ] = 'newMessage1';
                $eventArr['Rachet.WampServer.onUnSubscribe.'.$this->prefix.$chatRoomData['room_level_id'] ] = 'usersListAfter';
            }
        }
        $this->roomBatch = $roomBatch;
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
    public function usersList(CakeEvent $cakeEvent){
        $this->setVars($cakeEvent);
        $usersList = $this->getUserList(); 
        $topicUsers = array('online' => (count($usersList)),'users_list' => $usersList); 
        $this->broadcastToAllListners($topicUsers);
    }

    public function usersListAfter(CakeEvent $cakeEvent){
        $this->setVars($cakeEvent);
        $this->__connection->Session->remove( $this->__connection->WAMP->sessionId);
        $usersList = $this->getUserList(); 
        $topicUsers = array('online' => (count($usersList)),'users_list' => $usersList);  
        $this->broadcastToAllListners($topicUsers);

    }

    public function getUserCount(){
        return $topicUsers = $this->__connectionData['topics'][$this->__event->data['topicName']]->count();
    }
    
    public function getUserList(){
        $usersList =[];
        $allUsers = $this->__connection->Session->all();
        foreach ($this->__connectionData['topics'][$this->__event->data['topicName']]->getIterator() as $client) {
            if(isset($this->__connection->Session->all()[ $client->WAMP->sessionId])){
                $usersList[$allUsers[ $client->WAMP->sessionId]['id']] = $allUsers[$client->WAMP->sessionId];
            }
        }
        return $usersList;
    }

    public function newMessage1( CakeEvent $cakeEvent){
        return false;
    }
    public function newMessage( CakeEvent $cakeEvent){
        $dbsource = ConnectionManager::getDataSource('default');
        if(!$dbsource->isConnected()){
            $dbsource->reconnect();
        }
        $this->setVars($cakeEvent);
        $msg = ['message_data' => [
                    'from' => $this->__connection->Session->get( $this->__connection->WAMP->sessionId),
                    'message' => $cakeEvent->data['event'],
                    'timestamp' => time()
            ]
        ];

        $cakeEvent->subject()->broadcast($this->__event->data['topicName'], $msg );
        $this->saveChat($msg);
        return false;
    }
    public function broadcastToAllListners($msg){
        $this->__connectionData['topics'][$this->__event->data['topicName']]->broadcast($msg);
    }

    public function saveChat($msg){
        $data = [
            'room_id' => $this->roomBatch[$this->__event->data['topicName']],
            'message' => $msg['message_data']['message']['message'],
            'from_user' => $msg['message_data']['from']['id'],
            'ip_address' => $this->__connection->remoteAddress
        ];
        if($this->Chat->newMessage($data)){
        }else{
        }
        return;
    }

}


?>