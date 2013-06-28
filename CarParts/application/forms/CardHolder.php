<?php
class Application_Form_CardHolder extends Zend_Form{
    
	public function init(){
	    $this->setMethod('post');
	    
	    $firstName = new Zend_Form_Element_Text('firstName');
	    $firstName->setLabel('first_name')->setRequired(true);
	    
	    $lastName = new Zend_Form_Element_Text('lastName');
	    $lastName->setLabel('last_name')->setRequired(true);
	    
	    $elements = array($firstName, $lastName);
	    $this->addElements($elements);
	}
	
}