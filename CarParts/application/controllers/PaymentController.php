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
        $this->view->xml = $xml;
        //$this->view->form = new Application_Form_CardHolder();
        
    }

    public function cpsResultAction()
    {
    	$this->view->response = ($this->getParam('responseXml'));
    	
    	$xml = simplexml_load_string($this->getParam('responseXml'));

    	$reference_id = (string) $xml->transData->referenceId;
    	$amount = (string) $xml->transData->amount->value;
    	$currency = (string) $xml->transData->amount->currency;
    	$resultText = (string) $xml->result->resultText;
    	$resultMessage = (string) $xml->result->resultMessage;
    	$resultCode = (string) $xml->result->resultCode;
    	$order_id = (int) $xml->transData->orderId;
    	
    	$digiString = $reference_id.$order_id.$amount.$currency.$resultCode.$resultMessage.$resultText;
		$cps = new Application_Model_Payments_Cps();
		echo $cps->digisign($digiString);
		
    	if($resultMessage == 'Captured'){
    		
    		$digiString = $reference_id.$order_id.$amount.$currency.$resultCode.$resultMessage.$resultText;
    		
    	    $payment = new Application_Model_Payments();
    	    $paymentMapper = new Application_Model_PaymentsMapper();
    	    $payment->setAmount($amount)
    	    		->setCurrency($currency)
    	    		->setReferenceId($reference_id)
    	    		->setStatus(1)
    	    		->setOrderId($order_id);
    	    $paymentMapper->save($payment);
    	} else {
    		
    		$digiString = $reference_id.$order_id.$amount.$currency.$resultCode.$resultMessage.$resultText;
    		
    		$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/logs/payments.log' );
    	    $log = new Zend_Log($writer);
    	    $log->info('bad payment form order ' . $order_id . ' reference_id:' . $reference_id . '. text:' . $resultText . ' code:' . $resultCode . ' message ' . $resultMessage . '.');
    	}
    	
        // action body
    }


}

