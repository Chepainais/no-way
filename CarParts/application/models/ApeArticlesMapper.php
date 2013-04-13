<?PHP

class Application_Model_ApeArticlesMapper
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
            $this->setDbTable('Application_Model_DbTable_ApeArticles');
        }
        return $this->_dbTable;
    }

    public function save (Application_Model_ApeArticles $ApeArticles)
    {
        $data = array(
                'id_ape_article' => $ApeArticles->getIdApeArticle(),
                'supplier_product_code' => $ApeArticles->getSupplierProductCode(),
                'ape_code' => $ApeArticles->getApeCode(),
                'description' => $ApeArticles->getDescription(),
                'supplier_code' => $ApeArticles->getSupplierCode(),
                'supplier_name' => $ApeArticles->getSupplierName(),
                'price_type' => $ApeArticles->getPriceType(),
                'price' => $ApeArticles->getPrice(),
                'available_quantity' => $ApeArticles->getAvailableQuantity(),
                'time_created' => $ApeArticles->getTimeCreated(),
                'time_edited' => $ApeArticles->getTimeEdited()
        );
        
        if (null === ($id = $ApeArticles->getIdApeArticle())) {
            unset($data['id_ape_article']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, 
                    array(
                            'id_ape_article = ?' => $id
                    ));
        }
    }

    public function find ($id, Application_Model_ApeArticles $ApeArticles)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $ApeArticles->setIdApeArticle($row->id_ape_article)
            ->setSupplierProductCode($row->supplier_product_code)
            ->setApeCode($row->ape_code)
            ->setDescription($row->description)
            ->setSupplierCode($row->supplier_code)
            ->setSupplierName($row->supplier_name)
            ->setPriceType($row->price_type)
            ->setPrice($row->price)
            ->setAvailableQuantity($row->available_quantity)
            ->setTimeCreated($row->time_created)
            ->setTimeEdited($row->time_edited);
    }

    public function fetchAll ()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_ApeArticles();
            $entry->setIdApeArticle($row->id_ape_article)
                ->setSupplierProductCode($row->supplier_product_code)
                ->setApeCode($row->ape_code)
                ->setDescription($row->description)
                ->setSupplierCode($row->supplier_code)
                ->setSupplierName($row->supplier_name)
                ->setPriceType($row->price_type)
                ->setPrice($row->price)
                ->setAvailableQuantity($row->available_quantity)
                ->setTimeCreated($row->time_created)
                ->setTimeEdited($row->time_edited);
            $entries[] = $entry;
        }
        return $entries;
    }
}