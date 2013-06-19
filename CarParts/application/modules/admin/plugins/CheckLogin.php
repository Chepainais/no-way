<?php

class Admin_Plugin_CheckLogin extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        if ('admin' != $request->getModuleName()) {
            // If not in this module, return early
            return;
        }
        
        $this->db = Zend_Registry::get('db');
        $auth = Zend_Auth::getInstance();
        
        $storage = new Zend_Auth_Storage_Session('administration');
        $auth->setStorage($storage);
        
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();
        $authorization = $auth->getIdentity();
        if (! $authorization && $request->getControllerName() != 'login') {

            $view->assign('loggedIn', false);
            // Ja nav ielogojies - pārmetam uz autorizācijas logu
            $request->setModuleName('admin')
                ->setControllerName('login')
                ->setActionName('index')
                ->setDispatched(false);
        } elseif($authorization) {
          $view->assign('loggedIn', true);
        }
    }
}

?>