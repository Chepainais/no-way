<?php
class Application_Plugin_LayoutVariables extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();
        
        $view->cart = new Application_Model_Cart();
        
    }
}
