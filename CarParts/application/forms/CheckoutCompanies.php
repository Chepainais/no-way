<?php

class Application_Form_CheckoutCompanies extends Zend_Form
{

    public function init ()
    {
        $this->setMethod('post'); // Add an name element
        $this->addElement('Text', 'name', 
                array(
                        'label' => 'name',
                        'required' => true
                ));
        // Add an reg_number element
        $this->addElement('Text', 'reg_number', 
                array(
                        'label' => 'reg_number',
                        'required' => true
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
                        'required' => true
                ));
        // Add an country element
        $this->addElement('Text', 'country', 
                array(
                        'label' => 'country',
                        'required' => true
                ));
        // Add an bank_name element
        $this->addElement('Text', 'bank_name', 
                array(
                        'label' => 'bank_name',
                        'required' => true
                ));
        // Add an swift element
        $this->addElement('Text', 'swift', 
                array(
                        'label' => 'swift',
                        'required' => true
                ));
        // Add an bank_account element
        $this->addElement('Text', 'bank_account', 
                array(
                        'label' => 'bank_account',
                        'required' => true
                ));
        // Add an email element
        $this->addElement('Text', 'email', 
                array(
                        'label' => 'email',
                        'required' => true
                ));
        // Add an phone element
        $this->addElement('Text', 'phone', 
                array(
                        'label' => 'phone',
                        'required' => true
                ));
        $this->addElement('Submit', 'Submit');
    }
}