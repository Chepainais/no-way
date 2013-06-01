<?php

class Application_Form_CheckoutPrivate extends Zend_Form
{

    public function init ()
    {
        $this->setMethod('post');
        // Add an first_name element
        $this->addElement('Text', 'first_name', 
                array(
                        'label' => 'first name',
                        'required' => true
                )
                );
        // Add an last_name element
        $this->addElement('Text', 'last_name', 
                array(
                        'label' => 'last name',
                        'required' => true
                )
                );
        // Add an title element
        $this->addElement('Radio', 'title',
                array(
                        'label' => 'title',
                        'required' => true,
                        'multiOptions' => array('mr' => 'mr', 'ms' => 'ms'),
                        'separator' => ''
                )
        );
        // Add an email element
        $this->addElement('Text', 'email', 
                array(
                        'label' => 'email',
                        'required' => true,
                        'validators' => array('emailAddress')
                )
                );
        // Add an phone element
        $this->addElement('Text', 'phone', 
                array(
                        'label' => 'phone',
                        'required' => true
                )
                );

        // Add an country element
        $this->addElement('Select', 'country', 
                array(
                        'label' => 'country',
                        'required' => true,
                        'multiOptions' => array('no' => 'Norway', 'sv' => 'Sweden', 'fi' => 'Finland'),
                )
                );
        // Add an password element
        $this->addElement('Password', 'password', 
                array(
                        'label' => 'password',
                        'required' => true
                )
                );
        $this->addElement('Password', 'password_repeated',
                array(
                        'label' => 'password repeated',
                        'required' => true
                )
        );        
        // Add an status element
//         $this->addElement('Radio', 'status', 
//                 array(
//                         'label' => 'status',
//                         'required' => false
//                 )
//                 );
//         Add an Submit button
        $this->addElement('submit', 'submit', 
                array(
                        'ignore' => true,
                        'label' => 'Submit'
                ));
    }
}

?>