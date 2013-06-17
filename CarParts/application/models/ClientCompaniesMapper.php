<?php

class Application_Model_ClientCompaniesMapper
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
            $this->setDbTable('Application_Model_DbTable_ClientCompanies');
        }
        return $this->_dbTable;
    }

    public function save (Application_Model_ClientCompanies $ClientCompanies)
    {
        $data = array(
                'id_client_companie' => $ClientCompanies->getIdClientCompanie(),
                'client_id' => $ClientCompanies->getClientId(),
                'company_id' => $ClientCompanies->getCompanyId(),
                'active' => $ClientCompanies->getActive(),
                'created_by' => $ClientCompanies->getCreatedBy(),
                'edited_by' => $ClientCompanies->getEditedBy(),
                'time_edited' => $ClientCompanies->getTimeEdited(),
                'time_created' => $ClientCompanies->getTimeCreated()
        );
        
        if (null === ($id = $ClientCompanies->getIdClientCompanie())) {
            unset($data['id_client_companie']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, 
                    array(
                            'id_client_companie = ?' => $id
                    ));
        }
    }

    public function find ($id, 
            Application_Model_ClientCompanies $ClientCompanies)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $ClientCompanies->setIdClientCompanie($row['id_client_companie'])
            ->setClientId($row['client_id'])
            ->setCompanyId($row['company_id'])
            ->setActive($row['active']);
    }

    public function fetchAll ()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_ClientCompanies();
            $entry->setIdClientCompanie($row['id_client_companie'])
                ->setClientId($row['client_id'])
                ->setCompanyId($row['company_id'])
                ->setActive($row['active']);
            $entries[] = $entry;
        }
        return $entries;
    }
}