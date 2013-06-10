<?PHP

class Application_Model_ShippingAddressesMapper
{

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
            $this->setDbTable('Application_Model_DbTable_ShippingAddresses');
        }
        return $this->_dbTable;
    }

    public function save (Application_Model_ShippingAddresses $ShippingAddresses)
    {
        $data = array(
                'id_shipping_address' => $ShippingAddresses->getIdShippingAddress(),
                'company_id' => $ShippingAddresses->getCompanyId(),
                'client_id' => $ShippingAddresses->getClientId(),
                'country' => $ShippingAddresses->getCountry(),
                'address' => $ShippingAddresses->getAddress(),
                'address2' => $ShippingAddresses->getAddress2(),
                'zip_code' => $ShippingAddresses->getZipCode(),
                'phone' => $ShippingAddresses->getPhone(),
                'time_created' => $ShippingAddresses->getTimeCreated(),
                'created_by' => $ShippingAddresses->getCreatedBy(),
                'time_edited' => $ShippingAddresses->getTimeEdited(),
                'edited_by' => $ShippingAddresses->getEditedBy()
        );
        
        if (null === ($id = $ShippingAddresses->getIdShippingAddress())) {
            unset($data['id_shipping_address']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, 
                    array(
                            'id_shipping_address = ?' => $id
                    ));
        }
    }

    public function find ($id, 
            Application_Model_ShippingAddresses $ShippingAddresses)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $ShippingAddresses->setIdShippingAddress($row['id_shipping_address'])
                            ->setCompanyId($row['company_id'])
                            ->setClientId($row['client_id'])
                            ->setCountry($row['country'])
                            ->setAddress($row['address'])
                            ->setAddress2($row['address2'])
                            ->setZipCode($row['zip_code'])
                            ->setPhone($row['phone'])
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
            $entry = new Application_Model_ShippingAddresses();
            $entry->setIdShippingAddress($row['id_shipping_address'])
                    ->setCompanyId($row['company_id'])
                    ->setClientId($row['client_id'])
                    ->setCountry($row['country'])
                    ->setAddress($row['address'])
                    ->setAddress2($row['address2'])
                    ->setZipCode($row['zip_code'])
                    ->setPhone($row['phone'])
                    ->setTimeCreated($row['time_created'])
                    ->setCreatedBy($row['created_by'])
                    ->setTimeEdited($row['time_edited'])
                    ->setEditedBy($row['edited_by']);
            $entries[] = $entry;
        }
        return $entries;
    }
}