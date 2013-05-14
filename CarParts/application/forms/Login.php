<?php

class Application_Form_Login extends Zend_Form
{

    public function init ()
    {
        $email = new Zend_Form_Element_Text('login');
        $email->setLabel('Email')
              ->setRequired(true)
              ->addValidator('EmailAddress');
        
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
                 ->setRequired(true);
        
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setLabel('Login');
        
        $this->addElements(array($email, $password, $submit));
    }
}