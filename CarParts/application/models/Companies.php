<?PHP
class Application_Model_Companies
{

    protected $_table = 'companies';

    protected $_company_id = null;

    protected $_name = null;


    protected $_reg_number = null;

    protected $_vat_number = null;

    protected $_address = null;

    protected $_country = null;

    protected $_bank_name = null;

    protected $_swift = null;

    protected $_bank_account = null;

    protected $_email = null;

    protected $_phone = null;

    protected $_title = null;

    protected $_password = null;

    protected $_active = null;

    protected $_time_created = null;

    protected $_created_by = null;

    protected $_time_edited = null;

    protected $_edited_by = null;

    public function setCompanyId ($company_id)
    {
        $this->_company_id = $company_id;
        return $this;
    }

    public function getCompanyId ()
    {
        return $this->_company_id;
    }

    public function setName ($name)
    {
        $this->_name = $name;
        return $this;
    }

    public function getName ()
    {
        return $this->_name;
    }


    public function setRegNumber ($reg_number)
    {
        $this->_reg_number = $reg_number;
        return $this;
    }

    public function getRegNumber ()
    {
        return $this->_reg_number;
    }

    public function setVatNumber ($vat_number)
    {
        $this->_vat_number = $vat_number;
        return $this;
    }

    public function getVatNumber ()
    {
        return $this->_vat_number;
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

    public function setCountry ($country)
    {
        $this->_country = $country;
        return $this;
    }

    public function getCountry ()
    {
        return $this->_country;
    }

    public function setBankName ($bank_name)
    {
        $this->_bank_name = $bank_name;
        return $this;
    }

    public function getBankName ()
    {
        return $this->_bank_name;
    }

    public function setSwift ($swift)
    {
        $this->_swift = $swift;
        return $this;
    }

    public function getSwift ()
    {
        return $this->_swift;
    }

    public function setBankAccount ($bank_account)
    {
        $this->_bank_account = $bank_account;
        return $this;
    }

    public function getBankAccount ()
    {
        return $this->_bank_account;
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

    public function setPassword ($password)
    {
        $this->_password = $password;
        return $this;
    }

    public function getPassword ()
    {
        return $this->_password;
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

    public function readByCompanyId ($company_id)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `company_id` = '$company_id' LIMIT 1";
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
            throw new Exception('Invalid companies property');
        }
        $this->$method($value);
    }

    public function __get ($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid companies property');
        }
        return $this->$method();
    }

    public function setOptions (array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method_parts = explode('_', $key);
            $method = 'set'; 
            foreach($method_parts as $part) {
                $method .= ucfirst($part);
            }
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function save()
    {
        if ($this->exists()) {
        $query = "UPDATE `companies` SET " .
            ($this->getName() ? ' `name` = "' . $this->Name() . '", ' : '') .
            ($this->getRegNumber() ? ' `reg_number` = "' . $this->RegNumber() . '", ' : '') .
            ($this->getVatNumber() ? ' `vat_number` = "' . $this->VatNumber() . '", ' : '') .
            ($this->getAddress() ? ' `address` = "' . $this->Address() . '", ' : '') .
            ($this->getCountry() ? ' `country` = "' . $this->Country() . '", ' : '') .
            ($this->getBankName() ? ' `bank_name` = "' . $this->BankName() . '", ' : '') .
            ($this->getSwift() ? ' `swift` = "' . $this->Swift() . '", ' : '') .
            ($this->getEmail() ? ' `email` = "' . $this->Email() . '", ' : '') .
            ($this->getPhone() ? ' `phone` = "' . $this->Phone() . '", ' : '') .
            ($this->getTitle() ? ' `title` = "' . $this->Title() . '", ' : '') .
            ($this->getPassword() ? ' `password` = "' . $this->Password() . '", ' : '') .
            ($this->getActive() ? ' `active` = "' . $this->Active() . '", ' : '') .
            ' `time_edited` = NOW()' .
            ($this->getEditedBy() ? ' `edited_by` = "' . $this->EditedBy() . '", ' : '') .
         "WHERE `company_id` = " . $this->getCompanyId() . ";";
        }
         else { 
         $query = "INSERT INTO `companies` SET " .
            ($this->getName() ? ' `name` = "' . $this->Name() . '", ' : '') .
            ($this->getRegNumber() ? ' `reg_number` = "' . $this->RegNumber() . '", ' : '') .
            ($this->getVatNumber() ? ' `vat_number` = "' . $this->VatNumber() . '", ' : '') .
            ($this->getAddress() ? ' `address` = "' . $this->Address() . '", ' : '') .
            ($this->getCountry() ? ' `country` = "' . $this->Country() . '", ' : '') .
            ($this->getBankName() ? ' `bank_name` = "' . $this->BankName() . '", ' : '') .
            ($this->getSwift() ? ' `swift` = "' . $this->Swift() . '", ' : '') .
            ($this->getBankAccount() ? ' `bank_account` = "' . $this->BankAccount() . '", ' : '') .
            ($this->getEmail() ? ' `email` = "' . $this->Email() . '", ' : '') .
            ($this->getPhone() ? ' `phone` = "' . $this->Phone() . '", ' : '') .
            ($this->getTitle() ? ' `title` = "' . $this->Title() . '", ' : '') .
            ($this->getPassword() ? ' `password` = "' . $this->Password() . '", ' : '') .
            ($this->getActive() ? ' `active` = "' . $this->Active() . '", ' : '') .
            ' `time_created` = NOW()' .
            ($this->getCreatedBy() ? ' `created_by` = "' . $this->CreatedBy() . '", ' : '') ;
                    if ($this->db->execSQL($query)) {
                        $this->setCompanyId($this->db->insertId());
                        return true;
                    }
                }
        return false;
    }
}