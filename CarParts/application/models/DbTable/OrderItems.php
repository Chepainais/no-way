<?php

/**
 * DB Table order_items class
 *
 * This class extends Zend_Table
 */
class Application_Model_DBTable_OrderItems extends Zend_Db_Table_Abstract
{

    protected $_name = 'order_items';

    public function insert (Array $data)
    {
        $data['time_created'] = new Zend_Db_Expr('NOW()');
        return parent::insert($data);
    }

    public function update (Array $data, $where)
    {
        $data['time_edited'] = new Zend_Db_Expr('NOW()');
        return parent::update($data, $where);
    }
}