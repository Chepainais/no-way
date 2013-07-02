<?php

class OrderController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function orderAction()
    {
        $order_id =  $this->getParam('order_id');
        $order = new Application_Model_Orders();
        $orderMapper = new Application_Model_OrdersMapper();
        $orderMapper->find($order_id, $order);
        
        if($order->getToken() == $this->getParam('token')){
            $client = new Application_Model_Clients();
            $clientMapper = new Application_Model_ClientsMapper();
            $clientMapper->find($order->getClientId(), $client);
            
			if($order->getCompanyId()) {
			    $company = new Application_Model_Companies();
			    $companyMapper = new Application_Model_CompaniesMapper();
			    $companyMapper->find($order->getCompanyId(), $company);
			}
			
			$shipping_address = new Application_Model_ShippingAddresses();
			$shipping_addressMapper = new Application_Model_ShippingAddressesMapper();
            $shipping_addressMapper->find($order->getShippingAddressId(), $shipping_address);
			
            $order_itemsMapper = new Application_Model_OrderItemsMapper();
            $order_items = $order_itemsMapper->fetchOrderItems($order->getOrderId());   
            
            $this->view->order = $order;
            $this->view->client = $client;
            if($company){
            	$this->view->company = $company;
            }
            $this->view->shipping = $shipping_address;
            $this->view->order_items = $order_items;
        }
        

    }


}



