<?php
class Application_Form_RequestByVin extends Zend_Form {
    public function init() {
        
    	$phone = new Zend_Form_Element_Text('phone');
    	$phone->setLabel('Phone');
    	
    	$email = new Zend_Form_Element_Text('email');
    	$email->setLabel('Email')
    		  ->setRequired(true);
    	
    	$registration_number = new Zend_Form_Element_Text('registration_number');
    	$registration_number->setLabel('Registration number');
    	
    	$vin = new Zend_Form_Element_Text('vin');
    	$vin->setLabel('Vin code');
    	
    	$brand = new Zend_Form_Element_Text('brand');
    	$brand->setLabel('Brand');
    	
    	$model = new Zend_Form_Element_Text('model');
    	$model->setLabel('Model');
    	
    	$month = new Zend_Form_Element_Text('month');
    	$month->setLabel('Month');
    	
    	$country_of_origin = new Zend_Form_Element_Text('country_of_origin');
    	$country_of_origin->setLabel('Country of origin');
    	
    	$power = new Zend_Form_Element_Text('power');
    	$power->setLabel('Power');
    	
    	$volume = new Zend_Form_Element_Text('volume');
    	$volume->setLabel('Volume');
    	
    	$engine = new Zend_Form_Element_Text('engine');
    	$engine->setLabel('Engine');
    	
    	$body = new Zend_Form_Element_Text('body');
    	$body->setLabel('Body');
    	
    	$registration_reason_code = new Zend_Form_Element_Text('registration_reason_code');
    	$registration_reason_code->setLabel('Registration reason code');
    	
    	$comments = new Zend_Form_Element_Textarea('comments');
    	$comments->setLabel('comments');
    	
    	$captcha = new Zend_Form_Element_Captcha('captcha', array(
		    'label' => "Please verify you're a human",
		    'captcha' => array(
		        'captcha' => 'Figlet',
		        'wordLen' => 6,
// 		        'outputWidth' => 80,
		        'timeout' => 300,
		    ),
		));
    	
    	$submit = new Zend_Form_Element_Submit('Submit');
    	$submit->setLabel('Submit');
		$elements = array (
				$phone,
				$email,
				$registration_number,
				$vin,
				$brand,
				$model,
				$month,
				$country_of_origin,
				$power,
				$volume,
				$engine,
				$body,
				$registration_reason_code,
				$comments,
				$captcha,
				$submit,
		);
    	$this->addElements($elements);
    }
}