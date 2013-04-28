<?php

class Application_Form_CheckoutCompanies extends Zend_Form
{

    public function init ()
    {
        $this->setMethod('post'); // Add an name element
        $this->addElement('Text', 'name', 
                array(
                        'label' => 'name',
                        'required' => false
                ));
        // Add an reg_number element
        $this->addElement('Text', 'reg_number', 
                array(
                        'label' => 'reg_number',
                        'required' => false
                ));
        // Add an vat_number element
        $this->addElement('Text', 'vat_number', 
                array(
                        'label' => 'vat_number',
                        'required' => false
                ));
        // Add an address element
        $this->addElement('Text', 'address', 
                array(
                        'label' => 'address',
                        'required' => false
                ));
        // Add an country element
        $this->addElement('Text', 'country', 
                array(
                        'label' => 'country',
                        'required' => false
                ));
        // Add an bank_name element
        $this->addElement('Text', 'bank_name', 
                array(
                        'label' => 'bank_name',
                        'required' => false
                ));
        // Add an swift element
        $this->addElement('Text', 'swift', 
                array(
                        'label' => 'swift',
                        'required' => false
                ));
        // Add an bank_account element
        $this->addElement('Text', 'bank_account', 
                array(
                        'label' => 'bank_account',
                        'required' => false
                ));
        // Add an email element
        $this->addElement('Text', 'email', 
                array(
                        'label' => 'email',
                        'required' => false
                ));
        // Add an phone element
        $this->addElement('Text', 'phone', 
                array(
                        'label' => 'phone',
                        'required' => false
                ));
    }
}