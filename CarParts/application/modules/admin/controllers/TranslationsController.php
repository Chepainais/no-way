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
        $session = new Zend_Session_Namespace('admin');
        
        $this->db = Zend_Registry::get('db');
        $auth = Zend_Auth::getInstance();
        if(in_array($auth->getIdentity()->username, array('root', 'beatrise'))){
            $this->view->googleTranslate = true;
        }
        
        $availablelanguages = Zend_Registry::get('config')->availablaLanguages->toArray();

        $this->view->availablelanguages = $availablelanguages;
        
        Zend_Db_Table::setDefaultAdapter('db');
        $translations = new Application_Model_DbTable_Translations();
        $rows = $translations->selectDistinctMsgid();
        
        if(!$session->translationLanguages){
            $this->view->languages = $availablelanguages;
        } else {
            $this->view->languages = $session->translationLanguages;
        }
        
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
    
    public function selectlanguagesAction(){
        $session = new Zend_Session_Namespace('admin');
        $languages = $this->getParam('languages');
        $selectedLanguages = array();
        foreach ($languages as $language => $active){
            if($active) {
                $selectedLanguages[] = $language;
            }
        }
        $session->translationLanguages = $selectedLanguages;
        
        $this->redirect($this->view->url(array('action' => 'index')));
    }
    
    public function googletranslateAction(){

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        require_once 'Google/Google_Client.php';
        require_once 'Google/contrib/Google_TranslateService.php';
        
        $client = new Google_Client();
        $client->setApplicationName('Bilparts admin translate');
        
        $service = new Google_TranslateService($client);

        $name = $this->getParam('name');
        $name = str_replace('_', '', $name);
        $languageTo = $this->getParam('language');

        $translations = $service->translations->listTranslations($name, $languageTo, array('source' => 'en'));
        echo $translations['translations'][0]['translatedText'];
    }
}