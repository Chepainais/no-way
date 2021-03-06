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

    public function itemAdd($item_id, $count = 1, $name= null, $price = null, $price_type = null, $code = null, $brand = null){
        
        if(isset($this->cart->items[$item_id])){
            $this->cart->items[$item_id]['count'] = $this->cart->items[$item_id]['count'] + $count;
        }
        else{
            $this->cart->items[$item_id]['count'] = $count;
            $this->cart->items[$item_id]['id'] = $item_id;
            $this->cart->items[$item_id]['name'] = $name;
            $this->cart->items[$item_id]['price'] = $price;
            $this->cart->items[$item_id]['price_type'] = $price_type;
            $this->cart->items[$item_id]['brand'] = $brand;
            $this->cart->items[$item_id]['code'] = $code;
            $this->cart->items[$item_id]['editCount'] = new Zend_Form_Element_Text($item_id);
        }
        return true;
    }
    
    public function changeItemCount($item_id, $count){
        if((int) $count == 0){
            unset($this->cart->items[$item_id]);
            return true;
        }
        $this->cart->items[$item_id]['count'] = (int) $count;
        return true;
    }
    
    public function getItemCount(){
        return count($this->cart->items);
    }
    
    public function getItems(){
        return $this->cart->items;
    }
    
    public function clear(){
        if($this->cart->items = null){
            return true;
        }
        return false;
    }
}

