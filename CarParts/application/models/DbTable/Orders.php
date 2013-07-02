<?php

/**
 * DB Table orders class
 *
 * This class extends Zend_Table
 */
class Application_Model_DBTable_Orders extends Zend_Db_Table_Abstract
{

    protected $_name = 'orders';

    protected $_referenceMap    = array(
            'Client' => array(
                    'columns'           => array('client_id'),
                    'refTableClass'     => 'Application_Model_DBTable_Clients',
                    'refColumns'        => array('client_id')
            ),
            'Company' => array(
                    'columns'           => array('company_id'),
                    'refTableClass'     => 'Application_Model_DBTable_Companies',
                    'refColumns'        => array('company_id')
            )
    );
    
    public function insert (Array $data)
    {
    	$data['token'] = substr(uniqid(md5(rand()), true), 0, 32);
        $data['time_created'] = new Zend_Db_Expr('NOW()');
        return parent::insert($data);
    }

    public function update (Array $data, $where)
    {
        $data['time_edited'] = new Zend_Db_Expr('NOW()');
        return parent::update($data, $where);
    }
}