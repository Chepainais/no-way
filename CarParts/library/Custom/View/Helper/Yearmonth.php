<?php
class Custom_View_Helper_Yearmonth extends Zend_View_Helper_Abstract {
    public function Yearmonth($from, $to){
        return substr($from, 0,4) . '-' .substr($to, 0, 4) ;
    }
}