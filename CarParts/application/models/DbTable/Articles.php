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
    
    public function readById($article_id){
        $select = $this->_db->select()
        ->from($this->_name)
        ->where('article_id = ?', $article_id);
        return $this->_db->fetchAll($select);
    }
}

?>