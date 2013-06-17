<?PHP

class Application_Model_ClientCompanies
{

    protected $_table = 'client_companies';

    protected $_id_client_companie = null;

    protected $_client_id = null;

    protected $_company_id = null;

    protected $_active = null;

    protected $_created_by = null;

    protected $_edited_by = null;

    protected $_time_edited = null;

    protected $_time_created = null;

    public function setIdClientCompanie ($id_client_companie)
    {
        $this->_id_client_companie = $id_client_companie;
        return $this;
    }

    public function getIdClientCompanie ()
    {
        return $this->_id_client_companie;
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

    public function setCompanyId ($company_id)
    {
        $this->_company_id = $company_id;
        return $this;
    }

    public function getCompanyId ()
    {
        return $this->_company_id;
    }

    public function setActive ($active)
    {
        $this->_active = $active;
        return $this;
    }

    public function getActive ()
    {
        return $this->_active;
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

    public function setEditedBy ($edited_by)
    {
        $this->_edited_by = $edited_by;
        return $this;
    }

    public function getEditedBy ()
    {
        return $this->_edited_by;
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

    public function setTimeCreated ($time_created)
    {
        $this->_time_created = $time_created;
        return $this;
    }

    public function getTimeCreated ()
    {
        return $this->_time_created;
    }

    public function readByIdClientCompanie ($id_client_companie)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `id_client_companie` = '$id_client_companie' LIMIT 1";
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
            throw new Exception('Invalid client_companies property');
        }
        $this->$method($value);
    }

    public function __get ($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid client_companies property');
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
            $query = "UPDATE `client_companies` SET " . 
                     ($this->getClientId() ? ' `client_id` = "' . $this->ClientId() . '", ' : '') .
                     ($this->getCompanyId() ? ' `company_id` = "' . $this->CompanyId() . '", ' : '') .
                     ($this->getActive() ? ' `active` = "' . $this->Active() .
                     '", ' : '');
            "WHERE `id_client_companie` = " . $this->getIdClientCompanie() . ";";
        } else {
            $query = "INSERT INTO `client_companies` SET " .
                     ($this->getClientId() ? ' `client_id` = "' . $this->ClientId() . '", ' : '') .
                     ($this->getCompanyId() ? ' `company_id` = "' . $this->CompanyId() . '", ' : '') .
                     ($this->getActive() ? ' `active` = "' . $this->Active() .
                     '", ' : '');
            if ($this->db->execSQL($query)) {
                $this->setIdClientCompanie($this->db->insertId());
                return true;
            }
        }
        return false;
    }
}