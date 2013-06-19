<?php

/**
 * LoginController
 * 
 * @author
 * @version 
 */
class Admin_LoginController extends Zend_Controller_Action
{

    /**
     *
     * @var Zend_Db
     */
    var $db;

    public function init ()
    {
        $this->db = Zend_Registry::get('db');
    }

    /**
     * The default action - login screen
     */
    public function indexAction ()
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->db, 
                'users', 'username', 'password', 'MD5(?)');
        if ($_POST) {
            // Pārbaudam autorizāciju
            $authAdapter->setIdentity($this->_request->getParam('username'));
            $authAdapter->setCredential($this->_request->getParam('password'));
            
            $auth = Zend_Auth::getInstance();
            $storage = new Zend_Auth_Storage_Session('administration');
            $auth->setStorage($storage);
            $result = $auth->authenticate($authAdapter);
            if (! $result->isValid()) {
                // Ja nav ielogojies - pārmetam uz autorizācijas logu
                $this->_redirect('/admin/login');
            } else {
                $data = $authAdapter->getResultRowObject(null, 'password');
                $auth->getStorage()->write($data);
                $this->_redirect('/admin');
            }
        } else {
            
            // LOGIN forma
            $form = new Zend_Form('login');
            $form->addElement('text', 'username', 
                    array(
                            'label' => 'Lietotājvārds'
                    ));
            $form->addElement('password', 'password', 
                    array(
                            'label' => 'Parole'
                    ));
            $form->addElement('submit', 'submit', 
                    array(
                            'value' => 'Pievienot'
                    ));
            $this->view->form = $form;
        }
    }

    /**
     * Logoff
     */
    public function logoutAction ()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect('/admin/login');
    }
}
