<?php

class PaymentController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function cpsAction()
    {
    	
    	
        // action body
        $payment = new Application_Model_Payments_Cps();
        $xml = $payment->buildXml();
        
        $this->view->form = new Application_Form_CardHolder();
        
    }


}