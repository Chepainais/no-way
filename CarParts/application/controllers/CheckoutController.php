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
        $cart = new Zend_Session_Namespace('cart');
        $client = $this->auth->getIdentity();
        
        // Ja tiek apstiprināts pasūtījums
        if ($this->_request->isPost()) {
            if($this->getParam('accept_terms')) {
                
                // @todo create order
                
                $order = new Application_Model_Orders();
                $order_mapper = new Application_Model_OrdersMapper();
                
                $order->setClientId($client->client_id);
                
                $order_mapper->save($order);
                $order->setClientId($client->client_id);
                if ($this->checkout->type == 'company') {
                    $company = new Application_Model_Companies();
                    $company->setOptions($this->checkout->company);
                    // Save company
                    $companyMapper = new Application_Model_CompaniesMapper();
                    $company->setActive(true);
                    $companyMapper->save($company);
                    
                    $this->checkout->company['company_id'] = $company->getCompanyId();
                    
                    $order->setCompanyId($company->getCompanyId());
                    
                    $clientCompany = new Application_Model_ClientCompanies();
                    $clientCompanyMapper = new Application_Model_ClientCompaniesMapper();
                    $clientCompanyMapper->findByClientAndCompany($client->client_id, $company->getCompanyId(), $clientCompany);
                    
                    if(!$clientCompany->getIdClientCompanie()){
                        $clientCompany->setClientId($client->client_id)
                                      ->setCompanyId($company->getCompanyId())
                                      ->setActive(true);
                        $clientCompanyMapper->save($clientCompany);
                    } else {
                        $clientCompany->setActive(true);
                        $clientCompanyMapper->save($clientCompany);
                    }
                }
                // save order details
                
                foreach($cart->items as $id => $item) {
                    $orderItem = new Application_Model_OrderItems();
                    $orderItem->setTdId($id)
                         ->setOrderId($order->getOrderId())
                         ->setAmount($item['count'])
                         ->setPrice($item['price']);
                    $itemMapper = new Application_Model_OrderItemsMapper;
                    $itemMapper->save($orderItem);
                }

                // @todo save shipping details
                
                $shipping = new Application_Model_ShippingAddresses;
                $shippingMapper = new Application_Model_ShippingAddressesMapper;
                $shipping->setOptions($this->checkout->shipping);
                $shipping->setClientId($client->client_id)
                         ->setCompanyId($company->getCompanyId());
                $shippingMapper->save($shipping);
                
                $order->setShippingAddressId($shipping->getIdShippingAddress());
                $order_mapper->save($order);
                // @todo clear cart
                $cart = new Zend_Session_Namespace('cart');
                $cart->unsetAll();
                // @todo redirect to thank you page
                $this->_redirect($this->view->url(array('controller' => 'article', 'action' => 'read', 'article_alias' => 'thankyou')));
            }
        }
        
        unset($this->checkout->shipping['Submit']);
        
        $this->view->client = $this->auth->getIdentity();
        $this->view->shipping = $this->checkout->shipping;
        $this->view->company = $this->checkout->company;
        // Nerādam uzņēmuma id sarakstā
        unset($this->view->company['company_id']);
        
        // Translatable values
        $this->view->shipping['country'] = $this->view->translate($this->view->shipping['country']);
        
        $this->view->clientInformation = $this->auth->getIdentity();
        
        
        $this->view->cartItems = $cart->items;
    }


}