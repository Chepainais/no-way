<?php
class Application_Model_Payments {
	protected $_table = 'payments';
	protected $_payment_id = null;
	protected $_order_id = null;
	protected $_amount = null;
	protected $_currency = null;
	protected $_status = null;
	protected $_reference_id = null;
	protected $_time_created = null;
	public function setPaymentId($payment_id) {
		$this->_payment_id = $payment_id;
		return $this;
	}
	public function getPaymentId() {
		return $this->_payment_id;
	}
	public function setOrderId($order_id) {
		$this->_order_id = $order_id;
		return $this;
	}
	public function getOrderId() {
		return $this->_order_id;
	}
	public function setAmount($amount) {
		$this->_amount = $amount;
		return $this;
	}
	public function getAmount() {
		return $this->_amount;
	}
	public function setCurrency($currency) {
		$this->_currency = $currency;
		return $this;
	}
	public function getCurrency() {
		return $this->_currency;
	}
	public function setStatus($status) {
		$this->_status = $status;
		return $this;
	}
	public function getStatus() {
		return $this->_status;
	}
	public function setReferenceId($reference_id) {
		$this->_reference_id = $reference_id;
		return $this;
	}
	public function getReferenceId() {
		return $this->_reference_id;
	}
	public function setTimeCreated($time_created) {
		$this->_time_created = $time_created;
		return $this;
	}
	public function getTimeCreated() {
		return $this->_time_created;
	}
	public function readByPaymentId($payment_id) {
		$query = "SELECT * FROM `$this->_table` WHERE `payment_id` = '$payment_id' LIMIT 1";
		$res = $this->db->execSQL ( $query );
		$data = $this->db->fetchAssoc ( $res );
		if ($data) {
			$this->setOptions ( $data );
		}
	}
	public function readByOrderId($order_id) {
		$query = "SELECT * FROM `$this->_table` WHERE `order_id` = '$order_id' LIMIT 1";
		$res = $this->db->execSQL ( $query );
		$data = $this->db->fetchAssoc ( $res );
		if ($data) {
			$this->setOptions ( $data );
		}
	}
	public function __set($name, $value) {
		$method = 'set' . $name;
		if (('mapper' == $name) || ! method_exists ( $this, $method )) {
			throw new Exception ( 'Invalid payments property' );
		}
		$this->$method ( $value );
	}
	public function __get($name) {
		$method = 'get' . $name;
		if (('mapper' == $name) || ! method_exists ( $this, $method )) {
			throw new Exception ( 'Invalid payments property' );
		}
		return $this->$method ();
	}
	public function setOptions(array $options) {
		$methods = get_class_methods ( $this );
		foreach ( $options as $key => $value ) {
			$method = 'set' . ucfirst ( preg_replace ( '/(_|-)([a-z])/e', "strtoupper('\2')", $key ) );
			if (in_array ( $method, $methods )) {
				$this->$method ( $value );
			}
		}
		return $this;
	}
	public function save() {
		if ($this->exists ()) {
			$query = "UPDATE `payments` SET " . 
					($this->getOrderId () ? ' `order_id` = "' . $this->OrderId () . '", ' : '') . 
					($this->getAmount () ? ' `amount` = "' . $this->Amount () . '", ' : '') . 
					($this->getCurrency () ? ' `currency` = "' . $this->Currency () . '", ' : '') . 
					($this->getStatus () ? ' `status` = "' . $this->Status () . '", ' : '') . 
					($this->getReferenceId () ? ' `reference_id` = "' . $this->ReferenceId () . '", ' : '') .
					 "WHERE `payment_id` = " . $this->getPaymentId () . ";";
		} else {
			$query = "INSERT INTO `payments` SET " .
					($this->getOrderId() ? ' `order_id` = "' . $this->OrderId() . '", ' : '') .
					($this->getAmount() ? ' `amount` = "' . $this->Amount() . '", ' : '') .
					($this->getCurrency() ? ' `currency` = "' . $this->Currency() . '", ' : '') .
					($this->getStatus() ? ' `status` = "' . $this->Status() . '", ' : '') .
					($this->getReferenceId() ? ' `reference_id` = "' . $this->ReferenceId() . '", ' : '') .
					' `time_created` = NOW()';
					
			if ($this->db->execSQL ( $query )) {
				$this->setPaymentId ( $this->db->insertId () );
				return true;
			}
		}
		return false;
	}
}