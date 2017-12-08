<?php

/**
 * AppShell file
 *
 * PHP 5
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
 * @since         CakePHP(tm) v 2.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
// App::uses('CakeEmail', 'Network/Email');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class ChatShell extends AppShell {

    /**
     * Function for start server
     * @return boolean
     */

    public function start(){
        $pid = $this->checkPid();
//        echo $pid =  $this->args[0];
//        die();
//        $pid = 0;
//        if(isset($this->args[0]) && !empty($this->args[0])){
//            $pid =  $this->args[0];
//        }
        if($pid>0 && $pid!=getmypid()){
            echo "Server Alredy Running".PHP_EOL; 
        }else{
            $this->dispatchShell('Ratchet.websocket start'); 
        }
        exit();
    }
    /**
     * Function for restart server
     * @return boolean
     */
    public function restart(){
        $this->stop();
        $this->start();
//        echo $pid =  $this->args[0];
//        if( $pid > 0){
//            shell_exec("kill -9 $pid");
//            
//        }
//        $this->dispatchShell('Ratchet.websocket start'); 
    }

    /**
     * Function for check pid
     * @return boolean
     */
    public function checkPid(){
        return $pid = shell_exec("ps aux | grep 'Chat' | grep -v grep | awk '{ print $2 }' | head -1");
    }

    /**
     * Function for stop server
     * @return boolean
     */
    public function stop(){
        $pid = $this->checkPid();
        if( $pid>0 && $pid!=getmypid()){
            echo shell_exec("kill -9 $pid");
            echo "Server Stopped.".PHP_EOL;
        }else{
            echo "Server Not running.".PHP_EOL;
        }
    }
    

}