<?php

class Application_Model_DbTable_Translations extends Zend_Db_Table
{

    protected $_name = 'translations';

    protected $_primary = 'translation_id';

    public function getListByLocale ($locale)
    {
        $select = $this->_db->select()
            ->from($this->_name, array(
                'msgid',
                'msgstring'
        ))
            ->where('locale = ?', $locale);
        
        return $this->_db->fetchAll($select);
    }
    
    public function selectDistinctMsgid(){
        
        $select = $this->_db->select()
        ->distinct()
        ->from($this->_name, 'msgid');
        
        return $this->_db->fetchAll($select);
    }
    /**
     * Updeito tabulu, jau ieraksts jau eksistē
     * 
     * @param Array $arrayData
     * @return 
     */
    public function insertOrUpdate($arrayData)
    {
        $query = 'INSERT INTO `'. $this->_name.'` ('.implode(',',array_keys($arrayData)).') VALUES ('.implode(',',array_fill(1, count($arrayData), '?')).') ON DUPLICATE KEY UPDATE '.implode(' = ?,',array_keys($arrayData)).' = ?';
        return $this->getAdapter()->query($query,array_merge(array_values($arrayData),array_values($arrayData)));
    }
}

?>