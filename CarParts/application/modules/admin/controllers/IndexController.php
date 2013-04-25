<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init ()
    {
        $this->db = Zend_Registry::get('db');
        $auth = Zend_Auth::getInstance();
        if (! $auth->getIdentity()) {
            // Ja nav ielogojies - pārmetam uz autorizācijas logu
            $this->_redirect($this->view->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'index')));
        }
        
        $this->view->formModel = 'FAKIT';
    }

    public function indexAction ()
    {
        // action body
    }

    public function main_menu ()
    {}
}

	