<?php

class Application_Plugin_LayoutVariables extends Zend_Controller_Plugin_Abstract
{

    public function dispatchLoopStartup (
            Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();
        // Grozs
        $view->cart = new Application_Model_Cart();
        // Valodu linkiem sagataves
        $link = '//' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $link = preg_replace('|//[a-z]{2}\.|', '//%s.', $link);
        $view->languageLink = $link;
        
        $auth = Zend_Auth::getInstance();
        
        if (! $auth->getIdentity()) {
            $view->loggedIn = false;
        } elseif ($auth->getIdentity()) {
            $view->loggedIn = true;
        }
    }
}
