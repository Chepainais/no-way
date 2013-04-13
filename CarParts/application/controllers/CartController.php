<?php

class CartController extends Zend_Controller_Action
{

    private $cart;

    public function init ()
    {}

    public function indexAction ()
    {
        // action body
    }

    public function itemaddAction ()
    {
        $cart = new Application_Model_Cart();
        $cart->itemAdd($this->getParam('item_id'));
        $this->_helper->layout->disableLayout();
        
        $this->view->item_id = 'item:' . $this->getParam('item_id');
    }
}



