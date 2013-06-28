<?PHP

class Application_Model_CompaniesMapper
{

    public $_dbTable = null;

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
            $this->setDbTable('Application_Model_DbTable_Companies');
        }
        return $this->_dbTable;
    }

    public function save (Application_Model_Companies $companie)
    {
        $data = array(
                'company_id' => $companie->getCompanyId(),
                'name' => $companie->getName(),
                'reg_number' => $companie->getRegNumber(),
                'vat_number' => $companie->getVatNumber(),
                'address' => $companie->getAddress(),
                'country' => $companie->getCountry(),
                'bank_name' => $companie->getBankName(),
                'swift' => $companie->getSwift(),
                'bank_account' => $companie->getBankAccount(),
                'email' => $companie->getEmail(),
                'phone' => $companie->getPhone(),
                'title' => $companie->getTitle(),
                'password' => $companie->getPassword(),
                'active' => $companie->getActive(),
                'time_created' => $companie->getTimeCreated(),
                'created_by' => $companie->getCreatedBy(),
                'time_edited' => $companie->getTimeEdited(),
                'edited_by' => $companie->getEditedBy()
        );
        
        if (null === ($id = $companie->getCompanyId())) {
            unset($data['company_id']);
            $company_id = $this->getDbTable()->insert($data);
            $companie->setCompanyId($company_id);
        } else {
            $this->getDbTable()->update($data, array(
                    'company_id = ?' => $id
            ));
        }
    }

    public function find ($id, Application_Model_Companies $Companies)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $Companies->setCompanyId($row['company_id'])
            ->setName($row['name'])
            ->setRegNumber($row['reg_number'])
            ->setVatNumber($row['vat_number'])
            ->setAddress($row['address'])
            ->setCountry($row['country'])
            ->setBankName($row['bank_name'])
            ->setSwift($row['swift'])
            ->setBankAccount($row['bank_account'])
            ->setEmail($row['email'])
            ->setPhone($row['phone'])
            ->setTitle($row['title'])
            ->setPassword($row['password'])
            ->setActive($row['active'])
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
            $entry = new Application_Model_Companies();
            $entry->setCompanyId($row['company_id'])
                ->setName($row['name'])
                ->setRegNumber($row['reg_number'])
                ->setVatNumber($row['vat_number'])
                ->setAddress($row['address'])
                ->setCountry($row['country'])
                ->setBankName($row['bank_name'])
                ->setSwift($row['swift'])
                ->setBankAccount($row['bank_account'])
                ->setEmail($row['email'])
                ->setPhone($row['phone'])
                ->setTitle($row['title'])
                ->setPassword($row['password'])
                ->setActive($row['active'])
                ->setTimeCreated($row['time_created'])
                ->setCreatedBy($row['created_by'])
                ->setTimeEdited($row['time_edited'])
                ->setEditedBy($row['edited_by']);
            $entries[] = $entry;
        }
        return $entries;
    }
}