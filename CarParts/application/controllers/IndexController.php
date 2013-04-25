<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_request->setControllerName('article')
                       ->setActionName('read')
                       ->setParam('article_alias', 'main')
                       ->setDispatched(false);
    }


}

