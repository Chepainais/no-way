<?PHP

class Application_Model_Articles
{

    protected $_table = 'articles';

    protected $_article_id = null;

    protected $_name = null;

    protected $_alias = null;

    protected $_text = null;

    protected $_order_id = null;

    protected $_language = null;

    protected $_status = null;

    protected $_time_created = null;

    protected $_created_by = null;

    protected $_time_edited = null;

    protected $_edited_by = null;

    protected $_users_user_id = null;

    public function setArticleId ($article_id)
    {
        $this->_article_id = $article_id;
        return $this;
    }

    public function getArticleId ()
    {
        return $this->_article_id;
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

    public function setAlias ($alias)
    {
        $this->_alias = $alias;
        return $this;
    }

    public function getAlias ()
    {
        return $this->_alias;
    }

    public function setText ($text)
    {
        $this->_text = $text;
        return $this;
    }

    public function getText ()
    {
        return $this->_text;
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

    public function setLanguage ($language)
    {
        $this->_language = $language;
        return $this;
    }

    public function getLanguage ()
    {
        return $this->_language;
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

    public function setUsersUserId ($users_user_id)
    {
        $this->_users_user_id = $users_user_id;
        return $this;
    }

    public function getUsersUserId ()
    {
        return $this->_users_user_id;
    }

    public function readByArticleId ($article_id)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `article_id` = '$article_id' LIMIT 1";
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
            throw new Exception('Invalid articles property');
        }
        $this->$method($value);
    }

    public function __get ($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid articles property');
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