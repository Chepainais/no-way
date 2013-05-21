<?php

class Application_Model_Currency
{
    static function convert($amount, $currency_from, $currency_to, $addVAT = 1){
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/currencies.ini',
                'global');
        if($config->rates->$currency_from->$currency_to){
            
            if($addVAT){
                $vat = $addVAT;
            } 
            return ($amount * $config->rates->$currency_from->$currency_to * $addVAT);
        } else {
            throw new Exception('No correncies configured');
        }
    }
}

?>