<?php

class Application_Form_CheckoutPrivate extends Zend_Form
{

    public function init ()
    {
        $this->setMethod('post');
        // Add an first_name element
        $this->addElement('Text', 'first_name', 
                array(
                        'label' => 'first_name',
                        'required' => true
                )
                );
        // Add an last_name element
        $this->addElement('Text', 'last_name', 
                array(
                        'label' => 'last_name',
                        'required' => true
                )
                );
        // Add an email element
        $this->addElement('Text', 'email', 
                array(
                        'label' => 'email',
                        'required' => true
                )
                );
        // Add an phone element
        $this->addElement('Text', 'phone', 
                array(
                        'label' => 'phone',
                        'required' => true
                )
                );
        // Add an title element
        $this->addElement('Radio', 'title', 
                array(
                        'label' => 'title',
                        'required' => true, 
                        'multiOptions' => array('mr', 'ms'),
                        'separator' => ''
                )
                );
        // Add an country element
        $this->addElement('Select', 'country', 
                array(
                        'label' => 'country',
                        'required' => true,
                        'multiOptions' => array('no' => 'Norvegia', 'sv' => 'Sveden', 'lv' => 'Latvia', 'fi' => 'Finland'),
                )
                );
        // Add an password element
//         $this->addElement('Password', 'password', 
//                 array(
//                         'label' => 'password',
//                         'required' => false
//                 )
//                 );
        // Add an status element
//         $this->addElement('Radio', 'status', 
//                 array(
//                         'label' => 'status',
//                         'required' => false
//                 )
//                 );
        // Add an Submit button
//         $this->addElement('submit', 'submit', 
//                 array(
//                         'ignore' => true,
//                         'label' => 'Submit'
//                 ));
    }
}

?>