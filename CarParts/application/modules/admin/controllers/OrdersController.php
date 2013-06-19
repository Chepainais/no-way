<?php

/**
 * OrdersController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';

class Admin_OrdersController extends Zend_Controller_Action
{

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        $this->getRequest()->setModuleName('admin')
                ->setControllerName('orders')
                ->setActionName('view')
                ->setParam('status', 1)
                ->setDispatched(false);
    }

    public function orderAction ()
    {
        
    }
    
    public function viewAction(){
        $status = $this->getParam('status');
        $orders = new Application_Model_OrdersMapper();
        $this->view->orders = $orders->fetchByStatus($status);
    }
}
