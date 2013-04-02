<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{

    public function _initLayout ()
    {       
        
        // Uzsetojam layout uz admin.phtml

        Zend_Layout::getMvcInstance()->setLayout('admin');

    }

    protected function _initDoctype ()
    {}

    protected function _initViewController ()
    {}

    protected function _initAuth ()
    {

    }
    
    protected function _initPlugins()
    {
        $bootstrap = $this->getApplication();
        $bootstrap->bootstrap('frontcontroller');
        $front = $bootstrap->getResource('frontcontroller');
    
        $front->registerPlugin(new Admin_Plugin_Layout());
    }
}
