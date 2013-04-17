<?php

/**
 *
 * @author Aleksis
 * @version 
 */
class Admin_ArticlesController extends Zend_Controller_Action
{

    /**
     *
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     * @return NULL
     */
    public function indexAction ()
    {
        $mapper = new Application_Model_ArticlesMapper();
        $this->view->articles = $articles = $mapper->fetchByLanguage('lv');
    }

    public function createAction ()
    {
        $form = new Admin_Form_ArticleCreate();
        if ($this->getRequest()->getPost()) {
            if ($form->isValid($this->getRequest()
                ->getQuery())) {
                $article = new Application_Model_Articles();
                $article->setName($this->getParam('name'))
                    ->setText($this->getParam('text'))
                    ->setLanguage($this->getParam('language'))
                    ->setAlias($this->getParam('alias'));
                $mapper = new Application_Model_ArticlesMapper();
                $mapper->save($article);
                $this->_redirect($this->view->url(array('action' =>
                'index')));
            } else {
                echo 'nav ievadīti dati';
                // $this->_redirect($this->view->url());
            }
        }
        
        $this->view->form = $form;
    }

    public function editAction(){
        $form = new Admin_Form_ArticleCreate();
        
        if ($this->getRequest()->getPost()) {
            
            if ($form->isValid(
                    $this->getRequest()
                        ->getQuery())) {
                $article = new Application_Model_Articles();
                $mapper = new Application_Model_ArticlesMapper();
                $mapper->find($this->getParam('article_id'), $article);
                
                $article->setName($this->getParam('name'))
                ->setText($this->getParam('text'))
                ->setLanguage($this->getParam('language'))
                ->setAlias($this->getParam('alias'));
                
                $mapper->save($article);
                $this->_redirect($this->view->url(array(
                        'action' => 'index'
                )));
            } else {
                echo 'nav ievadīti dati';
                // $this->_redirect($this->view->url());
            }
        }
        
        $this->_helper->viewRenderer->setRender('create');

        $article = new Application_Model_Articles();
        
        $articleMapper = new Application_Model_ArticlesMapper();
        $articleMapper->find($this->getParam('article_id'), $article);

        $language = $form->getElement('language');
        $language->setValue($article->getLanguage());
        
        $name = $form->getElement('name');
        $name->setValue($article->getname());

        $alias = $form->getElement('alias');
        $alias->setValue($article->getalias());
        
        $text = $form->getElement('text');
        $text->setValue($article->gettext());
        
        $submit = $form->getElement('submit');
        $submit->setLabel('Edit Article');
        
        $this->view->form = $form;
        
        
    }
    
    /**
     * Sets the view field
     *
     * @param $view Zend_View_Interface            
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
