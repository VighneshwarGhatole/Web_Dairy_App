<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array('Session', 'Flash', 'RequestHandler');
    public $helper = array('Flash', 'Common');
    public $uses = array('User');

    // public $helpers = array('Minify.Minify','Js' => 'Jquery','Paginator');



    public function beforeRender() {
        if (in_array($this->request->action, array('agentlist')) && $this->request->controller == 'Users') {
			$this->set('tabList', 'aList');

		}else if(in_array($this->request->action, array('customer')) && $this->request->controller == 'Users'){
            $this->set('tabList', 'cList');

        }else if(in_array($this->request->action, array('price')) && $this->request->controller == 'Users'){
            $this->set('tabList', 'pList');

        }else if(in_array($this->request->action, array('entry')) && $this->request->controller == 'Users'){
            $this->set('tabList', 'eList');

        }else if(in_array($this->request->action, array('Expense')) && $this->request->controller == 'Users'){
            $this->set('tabList', 'exList');

        }else if(in_array($this->request->action, array('managerlist')) && $this->request->controller == 'Users'){
            $this->set('tabList', 'mList');

        }else {
			$this->set('tabList', '');

		}
		$this->response->disableCache();
    }

    public function beforeFilter() {        
        $this->request->params['webURL'] = Router::url('/', true);
        $this->request->params['dashboardURL'] = Router::url('/', true);
        $this->request->params['imgURL'] = Router::url('/app/webroot/img', true);
        $this->request->params['fancyboxURL'] = Router::url('/app/webroot/source', true);
        $this->set('params', $this->request->params);
    }

    function afterFilter() {
        if ($this->response->statusCode() == '404') {
            /*     $this->set("referer",$this->referer());
              $this->redirect(ABSOLUTE_URL."users/errors"
              ); */
        }
    }

    

    /**
     * Check URL against USER ROLE, IF match return true otherwise false
     * @param URL to be validated
     * @return boolean
     * @author Rohit
     * Date 19-Sep-16
     */
    public function checkPageAccess($URL) {
        try {
            if ($this->Session->read('UserDetails.roleId') == 1) {
                $arrActions = array(                    
                    'Users/agentlist',                  
                    'Users/customer',                  
                    'Users/price',                  
                    'Users/entry',                  
                    'Users/expense',                  
                    'Users/add',                 
                    'Users/edit',                 
                    'Users/delete',                 
                    'Users/updateagentstatus',                    
                    'Users/managerlist',                    
                    'Users/updatemanagerstatus',					
                );
                if (in_array($URL, $arrActions) && ($this->Session->read('UserDetails.id') > 0)) {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    

	
	 
}
