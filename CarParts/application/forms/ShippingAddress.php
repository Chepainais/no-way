<?php

class Application_Form_ShippingAddress extends Zend_Form
{

    public function init ()
    {
        $country = new Zend_Form_Element_Select('country');
        $country->setLabel('country')->setRequired(true)->setMultioptions(array('no' => 'Norway', 'sv' => 'Sweden', 'fi' => 'Finland'));
        
        $address = new Zend_Form_Element_Text('address');
        $address->setLabel('address')->setRequired(true);
        
        $address2 = new Zend_Form_Element_Text('address2');
        $address2->setLabel('address line 2');
        
        $zip = new Zend_Form_Element_Text('zip_code');
        $zip->setLabel('zip code')->setRequired(true);
        
        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('phone')->setRequired(true);
        
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setLabel('Submit');
        
        $elements = array(
                $country, $address, $address2, $zip, $phone, $submit
        );
        $this->addElements($elements);
        
    }
}

?>