<?PHP

class Application_Model_TranslationsMapper
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
            $this->setDbTable('Application_Model_DbTable_Translations');
        }
        return $this->_dbTable;
    }

    public function save (Application_Model_Translations $Translations)
    {
        $data = array(
                'translation_id' => $Translations->getTranslationId(),
                'msgid' => $Translations->getMsgid(),
                'msgstring' => $Translations->getMsgstring(),
                'locale' => $Translations->getLocale(),
                'module' => $Translations->getModule(),
                'controller' => $Translations->getController(),
                'time_created' => $Translations->getTimeCreated(),
                'time_edited' => $Translations->getTimeEdited(),
                'edited_by' => $Translations->getEditedBy()
        );
        
        if (null === ($id = $Translations->getTranslationId())) {
            unset($data['translation_id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, 
                    array(
                            'translation_id = ?' => $id
                    ));
        }
    }

    public function find ($id, Application_Model_Translations $Translations)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $Translations->setTranslationId($row['translation_id'])
            ->setMsgid($row['msgid'])
            ->setMsgstring($row['msgstring'])
            ->setLocale($row['locale'])
            ->setModule($row['module'])
            ->setController($row['controller'])
            ->setTimeCreated($row['time_created'])
            ->setTimeEdited($row['time_edited'])
            ->setEditedBy($row['edited_by']);
    }

    public function fetchAll ()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Translations();
            $entry->setTranslationId($row['translation_id'])
                ->setMsgid($row['msgid'])
                ->setMsgstring($row['msgstring'])
                ->setLocale($row['locale'])
                ->setModule($row['module'])
                ->setController($row['controller'])
                ->setTimeCreated($row['time_created'])
                ->setTimeEdited($row['time_edited'])
                ->setEditedBy($row['edited_by']);
            $entries[] = $entry;
        }
        return $entries;
    }
}