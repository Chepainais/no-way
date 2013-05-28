<?php

class UserController extends Zend_Controller_Action
{
    /**
     * 
     * @var Zend_Db
     */
    public $db;
    public function init()
    {
        $this->db = Zend_Registry::get('db');
        
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
    
    public function loggedinAction(){
        
    }

    public function loginAction ()
    {
        $form = new Application_Form_Login();
        
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->db, 'clients',
                'email', 'password', 'MD5(?)');
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                
                // Pārbaudam autorizāciju
                $authAdapter->setIdentity($this->_request->getParam('login'));
                $authAdapter->setCredential(
                        $this->_request->getParam('password'));
                
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);
                if (! $result->isValid()) {
                    // Ja nav ielogojies - pārmetam uz autorizācijas logu
                    $this->_redirect($this->view->url(array('controller' =>'user', 'action' => 'index'), 'default'));
                } else {
                    $data = $authAdapter->getResultRowObject(null, 'password');
                    $auth->getStorage()->write($data);
                    $this->_redirect($this->view->url(array('controller' =>'user', 'action' => 'loggedin'), 'default'));
                }
            }
        } 
        
        $auth = Zend_Auth::getInstance();
        
        if ($auth->getIdentity()) {
            $this->redirect($this->view->url(array('controller' => 'index', 'action' => 'index')));
        }
        $this->view->form = $form;
    }
    
    public function logoutAction(){
//         $_SESSION = array();
//         session_destroy();
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->redirect('/');
    }

    public function registerAction()
    {
        $session = new Zend_Session_Namespace('formData');
        $form = new Application_Form_CheckoutPrivate();
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->_request->getParams())){
                // Save data
                $client = new Application_Model_Clients();
                $client->setFirstName($this->getParam('first_name'))
                       ->setLastName($this->getParam('last_name'))
                       ->setEmail($this->getParam('email'))
                       ->setPhone($this->getParam('phone'))
                       ->setTitle($this->getParam('title'))
                       ->setCountry($this->getParam('country'))
                       ->setPassword(md5($this->getParam('password')))
                       ->setStatus($this->getParam('status'))
                       ->setTimeCreated($this->getParam('time_created'));
                $mapper = new Application_Model_ClientsMapper();
                $mapper->save($client);
                
                $this->redirect($this->view->url(array('controller' => 'user', 'action' => 'registered')));
            } else {
            $session->messages = $form->getMessages();
            $session->values = $form->getValues();
                        
            $this->redirect($this->view->url());
            }
        }
        if ($session->values) {
            foreach ($session->values as $field => $value) {
                $element = $form->getElement($field);
                if ($element) {
                    $element->setValue($value);
                }
            }
            unset($session->messages);
        }
        
        $this->view->formMessages = $session->messages;
        $this->view->form = $form;
    }
    

    public function registeredAction()
    {
        // action body
    }


}







