<?php
class Application_Form_CardHolder extends Zend_Form{
    
	public function init(){
	    $this->setMethod('post');
	    
	    $firstName = new Zend_Form_Element_Text('firstName');
	    $firstName->setLabel('first_name')->setRequired(true);
	    
	    $lastName = new Zend_Form_Element_Text('lastName');
	    $lastName->setLabel('last_name')->setRequired(true);
	    
	    $street = new Zend_Form_Element_Text('street');
	    $street->setLabel('street')->setRequired(true);
	    
	    $zip = new Zend_Form_Element_Text('zip');
	    $zip->setLabel('zip')->setRequired(true);
	    
	    $city = new Zend_Form_Element_Text('city');
	    $city->setLabel('city')->setRequired(true);
	    
	    $city = new Zend_Form_Element_Text('city');
	    $city->setLabel('city')->setRequired(true);
	    
	    $state = new Zend_Form_Element_Text('state');
	    $state->setLabel('state')->setRequired(true);
	    
	    $country = new Zend_Form_Element_Text('country');
	    $country->setLabel('country')->setRequired(true);
	    
	    $telephone = new Zend_Form_Element_Text('telephone');
	    $telephone->setLabel('telephone')->setRequired(true);
	    
	    $email = new Zend_Form_Element_Text('email');
	    $email->setLabel('email')->setRequired(true);
	    
	    $submit = new Zend_Form_Element_Submit('Submit');
	    
	    $elements = array($firstName, $lastName, $street, $zip, $city, $state, $country, $telephone, $email, $submit);
	    $this->addElements($elements);
	}
	
}