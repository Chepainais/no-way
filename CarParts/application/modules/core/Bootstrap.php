<?php

class Core_Bootstrap extends Zend_Application_Module_Bootstrap
{

    public function _initLayout ()
    {       
        
        // Uzsetojam layout uz admin.phtml

        Zend_Layout::getMvcInstance()->setLayout('index');

    }

    protected function _initDoctype ()
    {}

    protected function _initViewController ()
    {}

    protected function _initAuth ()
    {

    }
}
