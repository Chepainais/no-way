<?PHP

class Application_Model_Clients
{

    protected $_table = 'clients';

    protected $_client_id = null;

    protected $_first_name = null;

    protected $_last_name = null;

    protected $_email = null;

    protected $_phone = null;

    protected $_title = null;

    protected $_country = null;

    protected $_password = null;

    protected $_status = null;

    protected $_time_created = null;

    protected $_created_by = null;

    protected $_time_edited = null;

    protected $_edited_by = null;

    public function setClientId ($client_id)
    {
        $this->_client_id = $client_id;
        return $this;
    }

    public function getClientId ()
    {
        return $this->_client_id;
    }

    public function setFirstName ($first_name)
    {
        $this->_first_name = $first_name;
        return $this;
    }

    public function getFirstName ()
    {
        return $this->_first_name;
    }

    public function setLastName ($last_name)
    {
        $this->_last_name = $last_name;
        return $this;
    }

    public function getLastName ()
    {
        return $this->_last_name;
    }

    public function setEmail ($email)
    {
        $this->_email = $email;
        return $this;
    }

    public function getEmail ()
    {
        return $this->_email;
    }

    public function setPhone ($phone)
    {
        $this->_phone = $phone;
        return $this;
    }

    public function getPhone ()
    {
        return $this->_phone;
    }

    public function setTitle ($title)
    {
        $this->_title = $title;
        return $this;
    }

    public function getTitle ()
    {
        return $this->_title;
    }

    public function setCountry ($country)
    {
        $this->_country = $country;
        return $this;
    }

    public function getCountry ()
    {
        return $this->_country;
    }

    public function setPassword ($password)
    {
        $this->_password = $password;
        return $this;
    }

    public function getPassword ()
    {
        return $this->_password;
    }

    public function setStatus ($status)
    {
        $this->_status = $status;
        return $this;
    }

    public function getStatus ()
    {
        return $this->_status;
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

    public function readByClientId ($client_id)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `client_id` = '$client_id' LIMIT 1";
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
            throw new Exception('Invalid clients property');
        }
        $this->$method($value);
    }

    public function __get ($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid clients property');
        }
        return $this->$method();
    }

    public function setOptions (array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst(
                    preg_replace('/(_|-)([a-z])/e', "strtoupper('\2')", $key));
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
}