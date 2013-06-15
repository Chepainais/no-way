<?PHP

class Application_Model_Orders
{

    protected $_table = 'orders';

    protected $_order_id = null;

    protected $_client_id = null;

    protected $_token = null;

    protected $_time_created = null;

    protected $_created_by = null;

    protected $_time_edited = null;

    protected $_edited_by = null;

    public function setOrderId ($order_id)
    {
        $this->_order_id = $order_id;
        return $this;
    }

    public function getOrderId ()
    {
        return $this->_order_id;
    }

    public function setClientId ($client_id)
    {
        $this->_client_id = $client_id;
        return $this;
    }

    public function getClientId ()
    {
        return $this->_client_id;
    }

    public function setToken ($token)
    {
        $this->_token = $token;
        return $this;
    }

    public function getToken ()
    {
        return $this->_token;
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

    public function readByClientId ($client_id)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `client_id` = '$client_id' LIMIT 1";
        $res = $this->db->execSQL($query);
        $data = $this->db->fetchAssoc($res);
        if ($data) {
            $this->setOptions($data);
        }
    }

    /**
     * Set client_id parameter
     *
     * @param
     *            unknown description
     */
    public function listByClientId ($client_id)
    {
        $query = "SELECT *
                  FROM `$this->_table`
                  WHERE `client_id` = '$client_id'";
        
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
            throw new Exception('Invalid orders property');
        }
        $this->$method($value);
    }

    public function __get ($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid orders property');
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
            $query = "UPDATE `orders` SET " .
                     ($this->getTimeEdited() ? ' `time_edited` = "' .
                     $this->TimeEdited() . '", ' : '') .
                     ($this->getEditedBy() ? ' `edited_by` = "' .
                     $this->EditedBy() . '", ' : '') . "WHERE `order_id` = " .
                     $this->getOrderId() . ";";
        } else { 
         $query = "INSERT INTO `orders` SET " .
            ($this->getClientId() ? ' `client_id` = "' . $this->ClientId() . '", ' : '') .
            ($this->getToken() ? ' `token` = "' . $this->Token() . '", ' : '') .
            ($this->getTimeCreated() ? ' `time_created` = "' . $this->TimeCreated() . '", ' : '') .
            ' `created_by` = \'' . $this->CreatedBy() . "'";
                    
            if ($this->db->execSQL($query)) {
                $this->setOrderId($this->db->insertId());
                return true;
            }
        }
        return false;
    }
}