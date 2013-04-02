<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init ()
    {
        $this->db = Zend_Registry::get('db');
        
        $auth = Zend_Auth::getInstance();

        if (!$auth->getIdentity()) {
            // Ja nav ielogojies - pārmetam uz autorizācijas logu
            $this->_redirect('/admin/login');
        }
        
        $this->view->formModel = 'FAKIT';
        
    }

    public function indexAction ()
    {
        echo 'pacaramm';
        // action body
    }
    
    public function main_menu(){
        
    }
}

	