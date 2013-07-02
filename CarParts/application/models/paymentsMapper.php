<?php

class Application_Model_PaymentsMapper
{

	private $_dbTable;
	
	public function setDbTable($dbTable)
	{
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	public function getDbTable()
	{
		if (null === $this->_dbTable) {
			$this->setDbTable('Application_Model_DbTable_Payments');
		}
		return $this->_dbTable;
	}

	public function save(Application_Model_Payments $Payments)
	{
		$data = array(
				'payment_id'   => $Payments->getPaymentId(),
				'order_id'   => $Payments->getOrderId(),
				'amount'   => $Payments->getAmount(),
				'currency'   => $Payments->getCurrency(),
				'status'   => $Payments->getStatus(),
				'reference_id'   => $Payments->getReferenceId(),
				'time_created'   => new Zend_Db_Expr('NOW()'),
		);

		if (null === ($id = $Payments->getPaymentId())) {
			unset($data['payment_id']);
			$this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('payment_id = ?' => $id));
		}
	}

	public function find($id, Application_Model_Payments $Payments)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		$Payments->setPaymentId($row['payment_id'])
		->setOrderId($row['order_id'])
		->setAmount($row['amount'])
		->setCurrency($row['currency'])
		->setStatus($row['status'])
		->setReferenceId($row['reference_id'])
		->setTimeCreated($row['time_created'])
		;
	}

	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Payments();
			$entry->setPaymentId($row['payment_id'])
			->setOrderId($row['order_id'])
			->setAmount($row['amount'])
			->setCurrency($row['currency'])
			->setStatus($row['status'])
			->setReferenceId($row['reference_id'])
			->setTimeCreated($row['time_created'])
			;
			$entries[] = $entry;
		}
		return $entries;
	}


}