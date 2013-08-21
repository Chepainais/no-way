<?php

/**
 * OrdersController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';
class Admin_OrdersController extends Zend_Controller_Action {
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$this->getRequest ()->setModuleName ( 'admin' )->setControllerName ( 'orders' )->setActionName ( 'view' )->setParam ( 'status', 1 )->setDispatched ( false );
	}
	public function orderAction() {
		$order = new Application_Model_Orders ();
		$orderMapper = new Application_Model_OrdersMapper ();
		$orderMapper->find ( $this->getParam ( 'order_id' ), $order );
		
		if ($order->getClientId ()) {
			$client = new Application_Model_Clients ();
			$clientMapper = new Application_Model_ClientsMapper ();
			$clientMapper->find ( $order->getClientId (), $client );
		}
		$company = null;
		if ($order->getCompanyId ()) {
			$company = new Application_Model_Companies ();
			$companyMapper = new Application_Model_CompaniesMapper ();
			$companyMapper->find ( $order->getCompanyId (), $company );
		}
		$shipping_address = null;
		if ($order->getShippingAddressId ()) {
			$shipping_address = new Application_Model_ShippingAddresses ();
			$shipping_addressMapper = new Application_Model_ShippingAddressesMapper ();
			$shipping_addressMapper->find ( $order->getShippingAddressId (), $shipping_address );
		}
		$order_items = null;
		$order_itemsMapper = new Application_Model_OrderItemsMapper ();
		$order_items = $order_itemsMapper->fetchOrderItems ( $order->getOrderId () );
		
		if ($this->_request->isPost ()) {
			$amounts = $this->getParam('amount');
			$prices = $this->getParam('price');
			$orderItemDbTable = new Application_Model_DBTable_OrderItems();
			foreach($order_items as $key => $order_item) {
				if((int)$amounts[$order_item->getOrderItemId()]) {
				    $order_item->setAmount($amounts[$order_item->getOrderItemId()]);
				    $order_item->setPrice($prices[$order_item->getOrderItemId()]);
				    $order_itemsMapper->save($order_item);
				} else {
				    // Remove item from order
				    if((int)$order_item->getOrderItemId()) {
					    $orderItemDbTable->delete('order_item_id = ' . $order_item->getOrderItemId());
					    // remove from items array
					    unset($order_items[$key]);
				    }
				}
			}
		}

		$this->view->order = $order;
		$this->view->client = $client;
		if(isset($company)){
			$this->view->company = $company;
		}
		$this->view->shipping_address = $shipping_address;
		$this->view->items = $order_items;
		
		// Add item form
		$addItemForm = new Zend_Form();
		$tecdocId = new Zend_Form_Element_Text('TecdocId');
		$tecdocId->setLabel('Tecdoc id');
		
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Name');
		
		$amount = new Zend_Form_Element_Text('amount');
		$amount->setLabel('Amount');
		
		$price = new Zend_Form_Element_Text('price');
		$price->setLabel('Price');
		
		$addItemFormElements = array($tecdocId, $name, $amount, $price);
		$addItemForm->addElements($addItemFormElements);
		$this->view->addItemForm = $addItemForm;
	}
	public function viewAction() {
		$status = $this->getParam ( 'status' );
		$orders = new Application_Model_OrdersMapper ();
		$this->view->orders = $orders->fetchByStatus ( $status );
	}
	public function additemAction(){
	    // @todo Add item to order
		$tdId = $this->_request->getParam('TecdocId');
		$name = $this->_request->getParam('name');
		$amount = $this->_request->getParam('amount');
		$price = $this->_request->getParam('price');
		if(!empty($tdId) || !empty($name)) {
		    $orderItem = new Application_Model_OrderItems();
		    $orderItemMapper = new Application_Model_OrderItemsMapper();
		    
		    $orderItem->setOrderId($this->getParam('order_id'))
		    		  ->setTdId($tdId)
		    		  ->setName($name)
		    		  ->setAmount($amount)
		    		  ->setPrice($price);
		    $orderItemMapper->save($orderItem);
		} else {
		    throw new Zend_Exception('Cannot add item');
		}
		
		// redirect back to order
		$this->_redirect($this->view->url(array('action' => 'order'), 'default'));
	}
}
