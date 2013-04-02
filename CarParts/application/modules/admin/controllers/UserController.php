<?php

/**
 * UserController
 * 
 * @author
 * @version 
 */

class Admin_UserController extends Zend_Controller_Action
{

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        Zend_Db_Table::setDefaultAdapter('db');
        $users = new Zend_Db_Table('application_users');
        $select = $users->select();
        $rows = $users->fetchAll($select);
        var_dump($rows);
        // TODO Auto-generated UserController::indexAction() default action
    }
}
