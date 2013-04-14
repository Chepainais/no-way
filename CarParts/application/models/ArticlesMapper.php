<?PHP
class Application_Model_ArticlesMapper
{

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
                    $dbTable = new $dbTable();
                }
                if (!$dbTable instanceof Zend_Db_Table_Abstract) {
                    throw new Exception('Invalid table data gateway provided');
                }
                $this->_dbTable = $dbTable;
                return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
                    $this->setDbTable('Application_Model_DbTable_Articles');
                }
                return $this->_dbTable;
    }

    public function save(Application_Model_Articles $Articles)
    {
        $data = array( 
        'article_id'   => $Articles->getArticleId(),
        'name'   => $Articles->getName(),
        'text'   => $Articles->getText(),
        'order_id'   => $Articles->getOrderId(),
        'status'   => $Articles->getStatus(),
        'time_created'   => $Articles->getTimeCreated(),
        'created_by'   => $Articles->getCreatedBy(),
        'time_edited'   => $Articles->getTimeEdited(),
        'edited_by'   => $Articles->getEditedBy(),
        ); 
        
        if (null === ($id = $Articles->getArticleId())) { 
        unset($data['article_id']); 
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('article_id = ?' => $id));
        }
    }

    public function find($id, Application_Model_Articles $Articles)
    {
        $result = $this->getDbTable()->find($id);
                if (0 == count($result)) {
                    return;
                }
                $row = $result->current();
        $Articles->setArticleId($row->article_id)
        ->setName($row->name)
        ->setText($row->text)
        ->setOrderId($row->order_id)
        ->setStatus($row->status)
        ->setTimeCreated($row->time_created)
        ->setCreatedBy($row->created_by)
        ->setTimeEdited($row->time_edited)
        ->setEditedBy($row->edited_by)
        ;
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
        $entry = new Application_Model_Articles();
                $entry->setArticleId($row->article_id)
        ->setName($row->name)
        ->setText($row->text)
        ->setOrderId($row->order_id)
        ->setStatus($row->status)
        ->setTimeCreated($row->time_created)
        ->setCreatedBy($row->created_by)
        ->setTimeEdited($row->time_edited)
        ->setEditedBy($row->edited_by)
        ;
             $entries[] = $entry;
           }
                return $entries;
    }


}