<?php

/**
 * TranslationsController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';

class Admin_TranslationsController extends Zend_Controller_Action
{

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        
        $languages = Zend_Registry::get('config')->availablaLanguages->toArray();
        
        Zend_Db_Table::setDefaultAdapter('db');
        $translations = new Application_Model_DbTable_Translations();
        $rows = $translations->selectDistinctMsgid();

        $this->view->rows = $rows;
        
    }
}
