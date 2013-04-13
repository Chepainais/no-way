<?PHP

class Application_Model_ApeArticles
{

    protected $_table = 'ape_articles';

    protected $_id_ape_article = null;

    protected $_supplier_product_code = null;

    protected $_ape_code = null;

    protected $_description = null;

    protected $_supplier_code = null;

    protected $_supplier_name = null;

    protected $_price_type = null;

    protected $_price = null;

    protected $_available_quantity = null;

    protected $_time_created = null;

    protected $_time_edited = null;

    public function setIdApeArticle ($id_ape_article)
    {
        $this->_id_ape_article = $id_ape_article;
        return $this;
    }

    public function getIdApeArticle ()
    {
        return $this->_id_ape_article;
    }

    public function setSupplierProductCode ($supplier_product_code)
    {
        $this->_supplier_product_code = $supplier_product_code;
        return $this;
    }

    public function getSupplierProductCode ()
    {
        return $this->_supplier_product_code;
    }

    public function setApeCode ($ape_code)
    {
        $this->_ape_code = $ape_code;
        return $this;
    }

    public function getApeCode ()
    {
        return $this->_ape_code;
    }

    public function setDescription ($description)
    {
        $this->_description = $description;
        return $this;
    }

    public function getDescription ()
    {
        return $this->_description;
    }

    public function setSupplierCode ($supplier_code)
    {
        $this->_supplier_code = $supplier_code;
        return $this;
    }

    public function getSupplierCode ()
    {
        return $this->_supplier_code;
    }

    public function setSupplierName ($supplier_name)
    {
        $this->_supplier_name = $supplier_name;
        return $this;
    }

    public function getSupplierName ()
    {
        return $this->_supplier_name;
    }

    public function setPriceType ($price_type)
    {
        $this->_price_type = $price_type;
        return $this;
    }

    public function getPriceType ()
    {
        return $this->_price_type;
    }

    public function setPrice ($price)
    {
        $this->_price = $price;
        return $this;
    }

    public function getPrice ()
    {
        return $this->_price;
    }

    public function setAvailableQuantity ($available_quantity)
    {
        $this->_available_quantity = $available_quantity;
        return $this;
    }

    public function getAvailableQuantity ()
    {
        return $this->_available_quantity;
    }

    public function setTimeCreated ($time_created)
    {
        $this->_time_created = $time_created;
        return $this;
    }

    public function getTimeCreated ()
    {
        return $this->_time_created;
    }

    public function setTimeEdited ($time_edited)
    {
        $this->_time_edited = $time_edited;
        return $this;
    }

    public function getTimeEdited ()
    {
        return $this->_time_edited;
    }

    public function readByIdApeArticle ($id_ape_article)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `id_ape_article` = '$id_ape_article' LIMIT 1";
        $res = $this->db->execSQL($query);
        $data = $this->db->fetchAssoc($res);
        if ($data) {
            $this->setOptions($data);
        }
    }

    public function readBySupplierProductCode ($supplier_product_code)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `supplier_product_code` = '$supplier_product_code' LIMIT 1";
        $res = $this->db->execSQL($query);
        $data = $this->db->fetchAssoc($res);
        if ($data) {
            $this->setOptions($data);
        }
    }

    public function readByApeCode ($ape_code)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `ape_code` = '$ape_code' LIMIT 1";
        $res = $this->db->execSQL($query);
        $data = $this->db->fetchAssoc($res);
        if ($data) {
            $this->setOptions($data);
        }
    }

    public function __set ($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid ape_articles property');
        }
        $this->$method($value);
    }

    public function __get ($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid ape_articles property');
        }
        return $this->$method();
    }

    public function setOptions (array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' .
                     ucfirst(
                            preg_replace('/(_|-)([a-z])/e', "strtoupper('\2')", 
                                    $key));
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
}