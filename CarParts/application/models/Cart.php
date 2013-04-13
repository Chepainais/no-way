<?php

class Application_Model_Cart
{
    /**
     * @var Zend_Session_Namespace
     */
    private $cart;
    function __construct(){
        $this->cart = new Zend_Session_Namespace('cart');
    }

    public function itemAdd($item_id, $count = 1, $name= null, $price = null){
        
        if(isset($this->cart->items[$item_id])){
            $this->cart->items[$item_id] = $this->cart->items[$item_id] + $count;
        }
        else{
            $this->cart->items[$item_id]['count'] = $count;
            $this->cart->items[$item_id]['id'] = $item_id;
            $this->cart->items[$item_id]['name'] = $name;
            $this->cart->items[$item_id]['price'] = $price;
        }
        return true;
    }
    
    public function getItemCount(){
        return count($this->cart->items);
    }
    
    public function clear(){
        if($this->cart->items = null){
            return true;
        }
        return false;
    }
}

