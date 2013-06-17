<?PHP

class Application_Model_OrderItemsMapper
{

    private $_dbTable = null;
    
    public function setDbTable ($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (! $dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable ()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_OrderItems');
        }
        return $this->_dbTable;
    }

    public function save (Application_Model_OrderItems $OrderItems)
    {
        $data = array(
                'order_item_id' => $OrderItems->getOrderItemId(),
                'order_id' => $OrderItems->getOrderId(),
                'td_id' => $OrderItems->getTdId(),
                'amount' => $OrderItems->getAmount(),
                'price' => $OrderItems->getPrice(),
                'time_created' => $OrderItems->getTimeCreated(),
                'created_by' => $OrderItems->getCreatedBy(),
                'time_edited' => $OrderItems->getTimeEdited(),
                'edited_by' => $OrderItems->getEditedBy()
        );
        
        if (null === ($id = $OrderItems->getOrderItemId())) {
            unset($data['order_item_id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, 
                    array(
                            'order_item_id = ?' => $id
                    ));
        }
    }

    public function find ($id, Application_Model_OrderItems $OrderItems)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $OrderItems->setOrderItemId($row['order_item_id'])
            ->setOrderId($row['order_id'])
            ->setTdId($row['td_id'])
            ->setAmount($row['amount'])
            ->setPrice($row['price'])
            ->setTimeCreated($row['time_created'])
            ->setCreatedBy($row['created_by'])
            ->setTimeEdited($row['time_edited'])
            ->setEditedBy($row['edited_by']);
    }

    public function fetchAll ()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_OrderItems();
            $entry->setOrderItemId($row['order_item_id'])
                ->setOrderId($row['order_id'])
                ->setTdId($row['td_id'])
                ->setAmount($row['amount'])
                ->setPrice($row['price'])
                ->setTimeCreated($row['time_created'])
                ->setCreatedBy($row['created_by'])
                ->setTimeEdited($row['time_edited'])
                ->setEditedBy($row['edited_by']);
            $entries[] = $entry;
        }
        return $entries;
    }
}