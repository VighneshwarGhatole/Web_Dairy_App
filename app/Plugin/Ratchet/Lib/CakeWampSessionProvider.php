<?php
/*
use Ratchet\ConnectionInterface;

class CakeWampSessionProvider extends \Ratchet\Session\SessionProvider {
    
    /**
     * {@inheritdoc}
     */
/*
    function onOpen(ConnectionInterface $conn) {
        echo $iniSessionName = 'CAKEPHP'; // = ini_get('session.name');
        ini_set('session.name', $iniSessionName);
        $return = parent::onOpen($conn);
        ini_set('session.name', $iniSessionName);
        return $return;
    }
    
}

*/

// App::uses('CakeSession', 'Model');
use Ratchet\ConnectionInterface;

class CakeWampSessionProvider
{
    protected $storage;

    protected $Session;

    public function __construct(ConnectionInterface $conn, $debug = false)
    {
        try
        {
            $sessionName     = Configure::read('Session.cookie') ?: 'CAKEPHP';
            $sessionId       = $conn->WebSocket->request->getCookie($sessionName);
            $this->Session   = new CakeSession();


die();

            $sessionData     = $this->Session->read($sessionId, $debug);
            $this->storage   = $this->unserialize_session($sessionData);

            if ($debug) {
                CakeLog::write('debug', 'SESSION_NAME: ' . $sessionName);
                CakeLog::write('debug', 'SESSION_ID: '   . $sessionId);
                CakeLog::write('debug', 'SESSION_DATA: ' . $sessionData);
                CakeLog::write('debug', '_STORAGE: '     . $this->storage);
            }
        }
        catch (\Exception $e)
        {
            $this->storage   = array();
            //Log
            //CakeLog::write('error', $e->getMessage());
        }
    }

    public function isAuthorized()
    {
        return $this->storage ? true : false;
    }

    public function read($key)
    {
        try
        {
            return Set::classicExtract($this->storage, $key);
        }
        catch (\Exception $e)
        {
            return null;
        }
    }

    private function unserialize_session($sessionData)
    {
        if (!$sessionData || !is_string($sessionData)) {
            return array();
        }

        $vars = preg_split(
            '/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/',
            $sessionData,
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );

        $result = array();
        $count  = count($vars);

        for ($i = 0; $i < $count; $i = $i + 2) {
            $result[$vars[$i]] = unserialize($vars[$i + 1]);
        }

        return $result;
    }
}



?>