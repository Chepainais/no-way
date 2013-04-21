<?php

/**
 * TranslationsController
 * 
 * @author Aleksis
 * @version 0.1
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
        
        $this->view->languages = $languages;
        $this->view->rows = $rows;
        
        $this->view->translations = array();
        foreach ($translations->fetchAll() as $translation) {
            $this->view->translations[$translation->locale][$translation->msgid] = $translation->msgstring; 
        }
    }
    
    public function addAction(){
        $languages = Zend_Registry::get('config')->availablaLanguages->toArray();
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $field = $this->getParam('field');
        preg_match_all('/\[(.*)\]/U', $this->getParam('field'), $matches);
        

        list($locale, $msgid) = $matches[1];
        // Check locale string
        if(!in_array($locale, $languages)){
            throw new Exception('Bad language [' . $locale . ']');
        }
        
        // Insert record
        $tr = new Application_Model_DbTable_Translations;
        $insert = $tr->insertOrUpdate(
                    array('msgid' => $msgid, 
                          'locale' => $locale,  
                          'msgstring' => $this->getParam('value'),
                          'time_created' => new Zend_Db_Expr('NOW()'),
                     ), 
                    array('msgstring' => $this->getParam('value'), 'time_edited' => new Zend_Db_Expr('NOW()'),)
                );
        if($insert){
            echo 1;
        }
        else {
            echo 0;
        }
    }
}