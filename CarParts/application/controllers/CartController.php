<?php

class CartController extends Zend_Controller_Action
{

    /**
     * 
     * @var Application_Model_Cart
     */
    private $cart;

    public function init ()
    {
        $this->cart = new Zend_Session_Namespace('cart');
    }

    public function indexAction ()
    {
        $parts = new Application_Model_Parts();
        $intercar = new Application_Model_Intercar();
        
        $items = $this->cart->items;
        foreach($items as $key => $item){
            $params = $parts->retrieveArticle($item['id']);
            $items[$key]['params'] = $params;
            $items[$key]['price'] = $intercar->getItemPrice($params['ART_ARTICLE_NR'], $params['SUP_BRAND']);
        }
        
        $this->view->items = $items;
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



