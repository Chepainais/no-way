<?php

class ArticleController extends Zend_Controller_Action
{

    public function init ()
    {
        /* Initialize action controller here */
    }

    public function indexAction ()
    {
        // action body
    }

    public function readAction ()
    {
        $locale = Zend_Registry::get('Zend_Locale')->toString();
        
        $article = new Application_Model_Articles();
        $mapper = new Application_Model_ArticlesMapper();
        $this->view->articles = array();
        if ($this->getParam('article_alias')) {
            $articles = $mapper->readByAliasAndLanguage($this->getParam('article_alias'), $locale);
        }
        else {
            throw new Zend_Exception('No article alias');
        }
        $this->view->articles = $articles;
    }
}



