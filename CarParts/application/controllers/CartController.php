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
    }

    public function itemaddAction()
    {
        $this->_helper->layout->disableLayout();
        
        $parts = new Application_Model_Parts();
        $cart = new Application_Model_Cart();
        
        $item_id = $this->getParam('item_id');
        
        $params = $parts->retrieveArticle($item_id);
        $priceType = $this->getParam('price' . $item_id);
        if($priceType == 'ic'){
            $intercar = new Application_Model_Intercar();
            $ic_price = $intercar->getItemPrice($params['ART_ARTICLE_NR'], $params['SUP_BRAND']);
            $price = $ic_price;
        } elseif($priceType == 'ape') {
            $ape = new Application_Model_Apemotors();
            $ape_price = current($ape->getPrices(array($item_id => array('code' => $params['ART_ARTICLE_NR'], 'vendor' => $params['SUP_BRAND']))));
            $price = $ape_price['ProductDetails']['Price'];
        }

        $cart->itemAdd($item_id, $this->getParam('amount'), $params['ART_COMPLETE_DES_TEXT'], $price, $priceType, $params['ART_ARTICLE_NR'], $params['SUP_BRAND']);

        $this->view->item_id = 'item:' . $this->getParam('item_id');
    }

    public function clearAction()
    {
        $cart = new Application_Model_Cart();
        $cart->clear();
        $this->_redirect($this->view->url(array('controller' => 'cart', 'action' =>'index'), 'default'));
    }

    public function checkoutAction()
    {

    }
    


}





