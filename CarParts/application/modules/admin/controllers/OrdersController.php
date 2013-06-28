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
		
		$this->view->order = $order;
		$this->view->client = $client;
		$this->view->company = $company;
		$this->view->shipping_address = $shipping_address;
		$this->view->items = $order_items;
	}
	public function viewAction() {
		$status = $this->getParam ( 'status' );
		$orders = new Application_Model_OrdersMapper ();
		$this->view->orders = $orders->fetchByStatus ( $status );
	}
}
