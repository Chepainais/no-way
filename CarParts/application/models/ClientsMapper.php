<?PHP

class Application_Model_ClientsMapper
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
            $this->setDbTable('Application_Model_DbTable_Clients');
        }
        return $this->_dbTable;
    }

    function save (Application_Model_Clients $Clients)
    {
        $data = array(
                'client_id' => $Clients->getClientId(),
                'first_name' => $Clients->getFirstName(),
                'last_name' => $Clients->getLastName(),
                'email' => $Clients->getEmail(),
                'phone' => $Clients->getPhone(),
                'title' => $Clients->getTitle(),
                'country' => $Clients->getCountry(),
                'password' => $Clients->getPassword(),
                'status' => $Clients->getStatus(),
                'time_created' => $Clients->getTimeCreated(),
                'created_by' => $Clients->getCreatedBy(),
                'time_edited' => $Clients->getTimeEdited(),
                'edited_by' => $Clients->getEditedBy()
        );
        
        if (null === ($id = $Clients->getClientId())) {
            unset($data['client_id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array(
                    'client_id = ?' => $id
            ));
        }
    }

    public function find ($id, Application_Model_Clients $Clients)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $Clients->setClientId($row['client_id'])
            ->setFirstName($row['first_name'])
            ->setLastName($row['last_name'])
            ->setEmail($row['email'])
            ->setPhone($row['phone'])
            ->setTitle($row['title'])
            ->setCountry($row['country'])
            ->setPassword($row['password'])
            ->setStatus($row['status'])
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
            $entry = new Application_Model_Clients();
            $entry->setClientId($row['client_id'])
                ->setFirstName($row['first_name'])
                ->setLastName($row['last_name'])
                ->setEmail($row['email'])
                ->setPhone($row['phone'])
                ->setTitle($row['title'])
                ->setCountry($row['country'])
                ->setPassword($row['password'])
                ->setStatus($row['status'])
                ->setTimeCreated($row['time_created'])
                ->setCreatedBy($row['created_by'])
                ->setTimeEdited($row['time_edited'])
                ->setEditedBy($row['edited_by']);
            $entries[] = $entry;
        }
        return $entries;
    }
}