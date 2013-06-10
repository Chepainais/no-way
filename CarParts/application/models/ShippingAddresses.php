<?php

class Application_Model_ShippingAddresses
{

    protected $_table = 'shipping_addresses';

    protected $_id_shipping_address = null;

    protected $_company_id = null;

    protected $_client_id = null;

    protected $_country = null;

    protected $_address = null;

    protected $_address2 = null;

    protected $_zip_code = null;

    protected $_phone = null;

    protected $_time_created = null;

    protected $_created_by = null;

    protected $_time_edited = null;

    protected $_edited_by = null;

    public function setIdShippingAddress ($id_shipping_address)
    {
        $this->_id_shipping_address = $id_shipping_address;
        return $this;
    }

    public function getIdShippingAddress ()
    {
        return $this->_id_shipping_address;
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

    public function setClientId ($client_id)
    {
        $this->_client_id = $client_id;
        return $this;
    }

    public function getClientId ()
    {
        return $this->_client_id;
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

    public function setAddress ($address)
    {
        $this->_address = $address;
        return $this;
    }

    public function getAddress ()
    {
        return $this->_address;
    }

    public function setAddress2 ($address2)
    {
        $this->_address2 = $address2;
        return $this;
    }

    public function getAddress2 ()
    {
        return $this->_address2;
    }

    public function setZipCode ($zip_code)
    {
        $this->_zip_code = $zip_code;
        return $this;
    }

    public function getZipCode ()
    {
        return $this->_zip_code;
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

    public function readByIdShippingAddress ($id_shipping_address)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `id_shipping_address` = '$id_shipping_address' LIMIT 1";
        $res = $this->db->execSQL($query);
        $data = $this->db->fetchAssoc($res);
        if ($data) {
            $this->setOptions($data);
        }
    }

    /**
     * Set company_id parameter
     *
     * @param
     *            unknown description
     */
    public function listByCompanyId ($company_id)
    {
        $query = "SELECT *
        FROM `$this->_table`
        WHERE `company_id` = '$company_id'";
        
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
            throw new Exception('Invalid shipping_addresses property');
        }
        $this->$method($value);
    }

    public function __get ($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid shipping_addresses property');
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
            $query = "UPDATE `shipping_addresses` SET " .
                     ($this->getCompanyId() ? ' `company_id` = "' . $this->CompanyId() . '", ' : '') .
                     ($this->getClientId() ? ' `client_id` = "' . $this->ClientId() . '", ' : '') .
                     ($this->getCountry() ? ' `country` = "' . $this->Country() . '", ' : '') .
                     ($this->getAddress() ? ' `address` = "' . $this->Address() . '", ' : '') .
                     ($this->getAddress2() ? ' `address2` = "' . $this->Address2() . '", ' : '') .
                     ($this->getZipCode() ? ' `zip_code` = "' . $this->ZipCode() . '", ' : '') .
                     ($this->getPhone() ? ' `phone` = "' . $this->Phone() . '", ' : '') .
                     ($this->getTimeEdited() ? ' `time_edited` = "' . $this->TimeEdited() . '", ' : '') .
                     ($this->getEditedBy() ? ' `edited_by` = "' . $this->EditedBy() . '", ' : '') .
                     "WHERE `id_shipping_address` = " .
                     $this->getIdShippingAddress() . ";";
        } else {
            $query = "INSERT INTO `shipping_addresses` SET " .
                    ($this->getCompanyId() ? ' `company_id` = "' . $this->CompanyId() . '", ' : '') .
                    ($this->getClientId() ? ' `client_id` = "' . $this->ClientId() . '", ' : '') .
                    ($this->getCountry() ? ' `country` = "' . $this->Country() . '", ' : '') .
                    ($this->getAddress() ? ' `address` = "' . $this->Address() . '", ' : '') .
                    ($this->getAddress2() ? ' `address2` = "' . $this->Address2() . '", ' : '') .
                    ($this->getZipCode() ? ' `zip_code` = "' . $this->ZipCode() . '", ' : '') .
                    ($this->getPhone() ? ' `phone` = "' . $this->Phone() . '", ' : '') .
                    '`time_created` = NOW()' .
                    ' `created_by` = "' . $this->CreatedBy();
                    
            if ($this->db->execSQL($query)) {
                $this->setIdShippingAddress($this->db->insertId());
                return true;
            }
        }
        return false;
    }
}