<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        // action body
    }

    public function registerAction()
    {
        $session = new Zend_Session_Namespace('formData');
        $form = new Application_Form_CheckoutPrivate();
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->_request->getParams())){
                $this->redirect($this->view->url(array('action' => 'registered')));
            } else {
            $session->messages = $form->getMessages();
            $session->values = $form->getValues();
                        
            $this->redirect($this->view->url());
            }
        }
        if ($session->values) {
            foreach ($session->values as $field => $value) {
                $element = $form->getElement($field);
                if ($element) {
                    $element->setValue($value);
                }
            }
        }
        
        $this->view->formMessages = $session->messages;
        $this->view->form = $form;
    }

    public function registeredAction()
    {
        // action body
    }


}







