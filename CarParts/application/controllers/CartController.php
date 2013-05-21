<?php

class CartController extends Zend_Controller_Action
{

    /**
     * @var Application_Model_Cart
     *
     */
    private $cart = null;

    public function init()
    {
        $this->cart = new Zend_Session_Namespace('cart');
    }

    public function indexAction()
    {
        $cart = new Application_Model_Cart();
        if($this->_request->getPost()){
            foreach($this->getParam('count') as $item_id => $count){
                $cart->changeItemCount($item_id, $count);
            }
            $this->redirect($this->view->url());
        }
        $items = $this->cart->items;   
        $this->view->items = $items;
        // action body
    }

    public function itemaddAction()
    {
        $this->_helper->layout->disableLayout();
        
        $parts = new Application_Model_Parts();
        $cart = new Application_Model_Cart();
        $intercar = new Application_Model_Intercar();
        
        $params = $parts->retrieveArticle($this->getParam('item_id'));
        $price = $intercar->getItemPrice($params['ART_ARTICLE_NR'], $params['SUP_BRAND']);
        $cart->itemAdd($this->getParam('item_id'), 1, $params['ART_COMPLETE_DES_TEXT'], $price, $params['ART_ARTICLE_NR'], $params['SUP_BRAND']);
        
        
        $this->view->item_id = 'item:' . $this->getParam('item_id');
    }

    public function clearAction()
    {
        $cart = new Application_Model_Cart();
        $cart->clear();
        $this->_redirect($this->view->url(array('controller' => 'cart', 'action' =>'index')));
    }

    public function checkoutAction()
    {
        $formPrivate = new Application_Form_CheckoutPrivate();
        $this->view->formPrivate = $formPrivate;
        
        $formCompany = new Application_Form_CheckoutCompanies();
        $this->view->formCompany = $formCompany;
    }
    


}





