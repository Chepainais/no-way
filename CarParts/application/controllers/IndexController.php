<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	$this->config = Zend_Registry::get('config');
    }

    public function indexAction()
    {
        $this->_request->setControllerName('article')
                       ->setActionName('read')
                       ->setParam('article_alias', 'main')
                       ->setDispatched(false);
    }
    
    public function requestByVinAction(){
        $form = new Application_Form_RequestByVin();
        
        if($this->_request->isPost()){
            if($form->isValid($this->_request->getParams())){
                // @todo send email
            	$email_transport = new Zend_Mail_Transport_Smtp($this->config->email->smtp);
            	$mail = new Zend_Mail('UTF-8');
            	
            	$mail->setFrom($this->_request->getParam('email'));
            	$mail->addTo($this->config->email->system_email);
            	$mail->setSubject('Website: Request by VIN');
            	
            	// Prepare email body
            	$html = new Zend_View();
            	$html->setScriptPath(APPLICATION_PATH . '/views/scripts/emails/');
            	
            	// prepare values
            	$values = array();
            	foreach($form->getElements() as $key => $element){
            		$values[$this->view->translate($key)] = $this->getParam($key);
            	}
            	$html->assign('values', $values);
            	$body = $html->render('_request_by_vin.phtml');
            	
            	$mail->setBodyHtml($body);
            	// Send email
            	$mail->send($email_transport);
            	// redirect to thank you page
            	$this->_redirect($this->view->url(array('controller' => 'article', 'action' => 'read', 'article_alias' => 'request_by_vin_thank_you'), 'article-read'));
            	
            }
        }
        
        $this->view->form = $form;
        
    }


}