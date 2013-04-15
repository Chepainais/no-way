<?php

class Application_Model_DbTable_Articles extends Zend_Db_Table_Abstract
{

    protected $_name = 'articles';

    public function fetchByLanguage ($language)
    {
        $select = $this->_db->select()
            ->from($this->_name)
            ->where('language = ?', $language);
        return $this->_db->fetchAll($select);
    }
}

?>