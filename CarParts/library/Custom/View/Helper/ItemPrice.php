<?php

class Custom_View_Helper_ItemPrice extends Zend_View_Helper_Abstract
{

    public function ItemPrice (Array $article)
    {
       if(isset($article['LA_ART_ID'])){
           $id = $article['LA_ART_ID'];
       } elseif(isset($article['ARL_ART_ID'])){
            $id = $article['ARL_ART_ID'];
       }
        
        
        $xhtml = $this->view->form('itemAdd' . $id, 
                array(
                        'method' => 'post',
                        'action' => $this->view->url(
                                array(
                                        'controller' => 'cart',
                                        'action' => 'itemAdd',
                                        'item_id' => $id
                                ), 'default')
                ));
        // Izmetam ārā tukšās cenas
        foreach ($article['prices'] as $index => $price) {
            if(empty($price)){
                unset($article['prices'][$index]);
            }
        }
            
        // Atrodam un zemāko cenu
        $lowest = array_search(min($article['prices']), $article['prices']);
        // Rādam zemākās cenas formas lauku
        $xhtml .= "<div class=\"price $lowest\">" .
                 $this->view->formHidden('price' . $id, $lowest) .
                 $this->view->currency($article['prices'][$lowest], 'NOK') .
                 $this->view->formText('amount' . $id, 1, array('name' => 'amount', 'size' => 2)) . 
                "</div>";
        // Submit poga
        $xhtml .= $this->view->formSubmit('add_to_cart' .$id, 'Add to Cart', array('class' => 'itemAddToCart')) ;
        
        // Aizveram formu
        $xhtml .= '</form>';
        
        return $xhtml;
    }
}