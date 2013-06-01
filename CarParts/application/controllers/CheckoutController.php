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
        $form->addDisplayGroup($form->getElements(), 'shipping_information', array('legend' => 'Shipping information'));
        if(!$this->auth->hasIdentity()){
            return false;
        }
        if ($this->_request->isPost()) {
            
            if ($form->isValid(
                    $this->getRequest()
                        ->getParams())) {
                $this->checkout->type = 'client';
                $shipping = array();
                foreach ($form->getElements() as $element => $params) {
                    $shipping[$element] = $this->getRequest()->getParam($element);
                }
                
                $this->checkout->shipping = $shipping;
                $this->_redirect($this->view->url(array('action' => 'summary')));
            }
        }
        
        $this->view->form = $form;
    }

    public function companyAction()
    {
        $formCompany = new Application_Form_CheckoutCompanies();
        
        if ($this->_request->isPost()) {
            if ($formCompany->isValid($this->getRequest()
                    ->getParams())) {
                $this->checkout->type = 'company';
                $this->checkout->data = $this->getRequest()->getParams();
                $this->_redirect(
                        $this->view->url(array(
                                'action' => 'overview'
                        ), 'default'));
            }
        }
        
        $this->view->form = $formCompany;
    }

    public function overviewAction()
    {
        $this->view->data = $this->checkout->data;
    }

    public function summaryAction()
    {
        $this->view->clientInformation = $this->auth->getIdentity();
        
    }


}









