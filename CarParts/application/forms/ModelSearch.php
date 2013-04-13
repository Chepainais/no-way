<?php

class Application_Form_ModelSearch extends Zend_Form
{
    public $elementDecorators = array(
            'ViewHelper',
            'Errors',
            array('Description', array('tag' => 'span', 'class' => 'description')),
          //  array('HtmlTag',     array('tag' => '', 'class' => 'form-element')),
            array('Label',       array('class' => 'form-label', 'requiredSuffix' => '*'))
    );
    public $buttonDecorators = array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'span', 'class' => 'form-button'))
    );
    

    public function init()
    {
    	$this->setMethod('POST');
    	$this->setAction('/parts/search');
        $parts = new Application_Model_Parts();
        $this->setDecorators(array('FormElements','Form'));
        $vendor = new Zend_Form_Element_Select('vendor');
        $vendor->setLabel('Vendor');
        $vendor->setDecorators($this->elementDecorators);
        $vendor->addMultiOption('', '...');
        $vendor->addMultiOptions($parts->retrieveVendors(true));
//         $vendor->setAttrib('onChange', 'submit();');
		;

        $model = new Zend_Form_Element_Select('model');
        $model->addMultiOption('', '...')
        		->setDecorators($this->elementDecorators)
        		->setLabel('Model')
//         		->setAttrib('onChange', 'submit();');
        		;
        
        $fuelType = new Zend_Form_Element_Select('fuel');
        $fuelType->setDecorators($this->elementDecorators)
        		->addMultiOption('', '...')
        		->setAttrib('onChange', 'submit();')
        		->setLabel('Fuel');
        
        $year = new Zend_Form_Element_Select('year');
        $year->setDecorators($this->elementDecorators)
      		    ->addMultiOption('', '...')
      			->setAttrib('onChange', 'submit();')
        		->setLabel('Year');
      		  
        
//         $submit = new Zend_Form_Element_Submit('search');
//         $submit->setDecorators($this->buttonDecorators)
//         		->setIgnore(true);
        
        $this->addElements(array($vendor, $model, $fuelType, $year));
    }

}

