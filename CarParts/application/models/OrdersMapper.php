<?PHP

class Application_Model_OrdersMapper
{

    public $_dbTable;
    
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
            $this->setDbTable('Application_Model_DbTable_Orders');
        }
        return $this->_dbTable;
    }

    public function save (Application_Model_Orders $Orders)
    {
        $data = array(
                'order_id' => $Orders->getOrderId(),
                'client_id' => $Orders->getClientId(),
                'company_id' => $Orders->getCompanyId(),
                'shipping_address_id'   => $Orders->getShippingAddressId(),
//                 'token' => $Orders->getToken()
        );
        
        if (null === ($id = $Orders->getOrderId())) {
            unset($data['order_id']);
            $order_id = $this->getDbTable()->insert($data);
            $Orders->setOrderId($order_id);
        } else {
            $this->getDbTable()->update($data, array(
                    'order_id = ?' => $id
            ));
        }
    }

    public function find ($id, Application_Model_Orders $Orders)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $Orders->setOrderId($row['order_id'])
            ->setClientId($row['client_id'])
            ->setCompanyId($row['company_id'])
            ->setShippingAddressId($row['shipping_address_id'])
            ->setToken($row['token'])
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
            $entry = new Application_Model_Orders();
            $entry->setOrderId($row['order_id'])
                ->setClientId($row['client_id'])
                ->setCompanyId($row['company_id'])
                ->setShippingAddressId($row['shipping_address_id'])
                ->setToken($row['token'])
                ->setTimeCreated($row['time_created'])
                ->setCreatedBy($row['created_by'])
                ->setTimeEdited($row['time_edited'])
                ->setEditedBy($row['edited_by']);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public function fetchByStatus ($status)
    {
        $select = new Zend_Db_Table_Select($this->getDbTable());
        $select->where('status = ?', $status)
               ->order('order_id DESC');
        $stmt = $select->query();
        $result = $stmt->fetchAll();
        
        foreach($result as $key => $row) {
            $client = new Application_Model_Clients();
            $clientMapper = new Application_Model_ClientsMapper();
            $clientMapper->find($row['client_id'], $client);
            $result[$key]['client'] = $client;
        }
        
        return $result;
    }
}