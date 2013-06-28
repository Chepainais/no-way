<?PHP

class Application_Model_OrderItems
{

    protected $_table = 'order_items';

    protected $_order_item_id = null;

    protected $_order_id = null;

    protected $_td_id = null;
    
    protected $_td_info = null;

    protected $_amount = null;

    protected $_price = null;

    protected $_time_created = null;

    protected $_created_by = null;

    protected $_time_edited = null;

    protected $_edited_by = null;

    public function setOrderItemId ($order_item_id)
    {
        $this->_order_item_id = $order_item_id;
        return $this;
    }

    public function getOrderItemId ()
    {
        return $this->_order_item_id;
    }

    public function setOrderId ($order_id)
    {
        $this->_order_id = $order_id;
        return $this;
    }

    public function getOrderId ()
    {
        return $this->_order_id;
    }

    public function setTdId ($td_id)
    {
        $this->_td_id = $td_id;
        return $this;
    }
    
    public function setTdInfo($td_info){
        $this->_td_info = $td_info;
    }
    
    public  function getTdInfo(){
        return $this->_td_info;
    }

    public function getTdId ()
    {
        return $this->_td_id;
    }

    public function setAmount ($amount)
    {
        $this->_amount = $amount;
        return $this;
    }

    public function getAmount ()
    {
        return $this->_amount;
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

    public function setTimeCreated ($time_created)
    {
        $this->_time_created = $time_created;
        return $this;
    }

    public function getTimeCreated ()
    {
        return $this->_time_created;
    }

    public function setCreatedBy ($created_by)
    {
        $this->_created_by = $created_by;
        return $this;
    }

    public function getCreatedBy ()
    {
        return $this->_created_by;
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

    public function setEditedBy ($edited_by)
    {
        $this->_edited_by = $edited_by;
        return $this;
    }

    public function getEditedBy ()
    {
        return $this->_edited_by;
    }

    public function readByOrderId ($order_id)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `order_id` = '$order_id' LIMIT 1";
        $res = $this->db->execSQL($query);
        $data = $this->db->fetchAssoc($res);
        if ($data) {
            $this->setOptions($data);
        }
    }

    /**
     * Set order_id parameter
     *
     * @param
     *            unknown description
     */
    public function listByOrderId ($order_id)
    {
        $query = "SELECT *
                  FROM `$this->_table`
                  WHERE `order_id` = '$order_id'";
        
        $res = $this->db->execSQL($query);
        $return = array();
        while ($r = $this->db->fetchAssoc($res)) {
            $return[] = $r;
        }
        if (count($return) > 0) {
            return $return;
        } else {
            return false;
        }
    }

    public function __set ($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid order_items property');
        }
        $this->$method($value);
    }

    public function __get ($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid order_items property');
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

    public function save ()
    {
        if ($this->exists()) {
            $query = "UPDATE `order_items` SET " .
                     ($this->getOrderId() ? ' `order_id` = "' . $this->OrderId() .
                     '", ' : '') .
                     ($this->getTdId() ? ' `td_id` = "' . $this->TdId() . '", ' : '') .
                     ($this->getAmount() ? ' `amount` = "' . $this->Amount() .
                     '", ' : '') .
                     ($this->getPrice() ? ' `price` = "' . $this->Price() . '", ' : '') .
                     ($this->getTimeEdited() ? ' `time_edited` = "' .
                     $this->TimeEdited() . '", ' : '') .
                     ($this->getEditedBy() ? ' `edited_by` = "' .
                     $this->EditedBy() . '", ' : '') . "WHERE `order_item_id` = " .
                     $this->getOrderItemId() . ";";
        } else { 
         $query = "INSERT INTO `order_items` SET " .
            ($this->getOrderId() ? ' `order_id` = "' . $this->OrderId() . '", ' : '') .
            ($this->getTdId() ? ' `td_id` = "' . $this->TdId() . '", ' : '') .
            ($this->getAmount() ? ' `amount` = "' . $this->Amount() . '", ' : '') .
            ($this->getPrice() ? ' `price` = "' . $this->Price() . '", ' : '') .
            ($this->getTimeCreated() ? ' `time_created` = "' . $this->TimeCreated() . '", ' : '') .
            ($this->getCreatedBy() ? ' `created_by` = "' . $this->CreatedBy() . '", ' : '') ;
                    
            if ($this->db->execSQL($query)) {
                $this->setOrderItemId($this->db->insertId());
                return true;
            }
        }
        return false;
    }
}