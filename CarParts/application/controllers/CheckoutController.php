<?php

class CheckoutController extends Zend_Controller_Action
{

    private $checkout = null;

    /**
     * Zend Auth
     * @var Zend_Auth
     *
     */
    private $auth = null;

    public function init()
    {
        $this->auth = Zend_Auth::getInstance();
        $this->checkout = new Zend_Session_Namespace('checkout');
    }

    public function indexAction()
    {
        // action body
    }

    public function clientAction()
    {
        $this->view->clientInformation = $this->auth->getIdentity();
        $form = new Application_Form_ShippingAddress();
        
        foreach($form->getElements() as $element){
            $element->setBelongsTo('shipping');
        }
        // Company information

        if($this->getRequest()->getParam('action') == 'company') {
            $this->checkout->type = 'company';
            $formCompany = new Application_Form_CheckoutCompanies();
            
            $formCompany->addDisplayGroup($formCompany->getElements(), 'company_information', array('legend' => 'Company information'));
            
            $formCompany->removeElement('Submit');
            
            foreach($formCompany->getElements() as $element){
                $element->setBelongsTo('company');
            }
            
            $form->addSubForm($formCompany, 'Company details');
        } else {
            $this->checkout->type = 'client';
        }
        
        // Shiping information
        $form->addDisplayGroup($form->getElements(), 'shipping_information', array('legend' => 'Shipping information'));
        if(!$this->auth->hasIdentity()){
            return false;
        }
        if ($this->_request->isPost()) {
            
            if ($form->isValid($this->getRequest()->getParams())) {
                
                $this->checkout->shipping = $this->getRequest()->getParam('shipping');
                $this->checkout->company = $this->getRequest()->getParam('company');

                $this->_redirect($this->view->url(array('action' => 'summary')));
            }
        }
        
        $this->view->form = $form;
    }

    /**
     * Copany checkout - forwards to client checkout with added company data
     * fields
     */
    public function companyAction ()
    {
        $this->_forward('client');
    }

    public function overviewAction()
    {
        $this->view->data = $this->checkout->data;
    }

    public function summaryAction()
    {
        unset($this->checkout->shipping['Submit']);
        $this->view->client = $this->auth->getIdentity();
        $this->view->shipping = $this->checkout->shipping;
        $this->view->company = $this->checkout->company;
        
        // Translatable values
        $this->view->shipping['country'] = $this->view->translate($this->view->shipping['country']);
        
        $this->view->clientInformation = $this->auth->getIdentity();
        
        $cart = new Zend_Session_Namespace('cart');
        $this->view->cartItems = $cart->items;
    }


}