<?php

class Application_Model_Translations
{

    protected $_table = 'translations';

    protected $_translation_id = null;

    protected $_msgid = null;

    protected $_msgstring = null;

    protected $_locale = null;

    protected $_module = null;

    protected $_controller = null;

    protected $_time_created = null;

    protected $_time_edited = null;

    protected $_edited_by = null;

    public function setTranslationId ($translation_id)
    {
        $this->_translation_id = $translation_id;
        return $this;
    }

    public function getTranslationId ()
    {
        return $this->_translation_id;
    }

    public function setMsgid ($msgid)
    {
        $this->_msgid = $msgid;
        return $this;
    }

    public function getMsgid ()
    {
        return $this->_msgid;
    }

    public function setMsgstring ($msgstring)
    {
        $this->_msgstring = $msgstring;
        return $this;
    }

    public function getMsgstring ()
    {
        return $this->_msgstring;
    }

    public function setLocale ($locale)
    {
        $this->_locale = $locale;
        return $this;
    }

    public function getLocale ()
    {
        return $this->_locale;
    }

    public function setModule ($module)
    {
        $this->_module = $module;
        return $this;
    }

    public function getModule ()
    {
        return $this->_module;
    }

    public function setController ($controller)
    {
        $this->_controller = $controller;
        return $this;
    }

    public function getController ()
    {
        return $this->_controller;
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

    public function setEditedBy ($edited_by)
    {
        $this->_edited_by = $edited_by;
        return $this;
    }

    public function getEditedBy ()
    {
        return $this->_edited_by;
    }

    public function readByTranslationId ($translation_id)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `translation_id` = '$translation_id' LIMIT 1";
        $res = $this->db->execSQL($query);
        $data = $this->db->fetchAssoc($res);
        if ($data) {
            $this->setOptions($data);
        }
    }

    public function readByMsgid ($msgid)
    {
        $query = "SELECT * FROM `$this->_table` WHERE `msgid` = '$msgid' LIMIT 1";
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
            throw new Exception('Invalid translations property');
        }
        $this->$method($value);
    }

    public function __get ($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception('Invalid translations property');
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

    public function getListByLocale ($locale)
    {
        $translations = new Application_Model_DbTable_Translations();
        return $translations->getListByLocale($locale);
    }
}

