<?php
class PartsController extends Zend_Controller_Action

{
/**
 * 
 * @var Zend_Db
 */
    private $db;

    public function init ()
    {
        $db = Zend_Registry::get('db');
        $this->db = $db;
    }

    public function indexAction ()
    {
        // action body
    }

    /**
     * Ražotāju saraksts
     */
    public function vendorsAction ()
    {
        $parts = new Application_Model_Parts();
        $this->view->vendors = $parts->retrieveVendors();
    }

    /**
     * Ražotāja modeļu saraksts
     */
    public function vendorAction ()
    {
        
        $vendor_id = $this->getRequest()->getParam('vendor_id');
        $this->view->vendor_id = $vendor_id;
        $parts = new Application_Model_Parts($vendor_id);
        $this->view->models = $parts->retrieveVendorModels($vendor_id);

    }

    /**
     * Modeļi
     */
    public function modelAction ()
    {
        $vendor_id = $this->view->vendor_id = $this->getRequest()->getParam('vendor_id');
        $model_id = $this->view->model_id = $this->getRequest()->getParam('model_id');
        $parts = new Application_Model_Parts();
        $this->view->str_id = $parts->getModelSTR_ID($model_id);
        $this->view->types = $parts->retrieveModelTypes($model_id);    	
    }
    /**
     * Modeļa tips (apakšmodelis)
     */
    public function typeAction(){
        
        $vendor_id = $this->view->vendor_id = $this->getRequest()->getParam('vendor_id');
        $model_id = $this->view->model_id = $this->getRequest()->getParam('model_id');
        $typ_id = $this->view->typ_id = $this->getRequest()->getParam('typ_id');
        $str_id = $this->view->str_id = $this->getRequest()->getParam('str_id');
        
        $parts = new Application_Model_Parts();
    	$this->view->searchTree = $parts->searchTree($typ_id, $str_id);
    }
    /**
     * Detaļas pēc auto modeļa, tipa un detaļas kategorijas
     */
    public function articlesAction(){
        $parts = new Application_Model_Parts();
        $vendor_id = $this->view->vendor_id = $this->getRequest()->getParam('vendor_id');
        $model_id = $this->view->model_id = $this->getRequest()->getParam('model_id');
        $typ_id = $this->view->typ_id = $this->getRequest()->getParam('typ_id');
        $str_id = $this->view->str_id = $this->getRequest()->getParam('str_id');
        
        
        $this->view->searchTree2 = $parts->retrieveArticles($typ_id, $str_id);
        foreach($this->view->searchTree2 as $id => $st){
        	$this->view->searchTree2[$id]['params'] = $parts->retrieveArticle($st['LA_ART_ID']);
        	$this->view->searchTree2[$id]['image'] = $parts->getArtImageURL($st['LA_ART_ID']);
        }
    }
    
    public function articleAction(){
        $parts = new Application_Model_Parts();
        $vendor_id = $this->view->vendor_id = $this->getRequest()->getParam('vendor_id');
        $model_id = $this->view->model_id = $this->getRequest()->getParam('model_id');
        $typ_id = $this->view->typ_id = $this->getRequest()->getParam('typ_id');
        $str_id = $this->view->str_id = $this->getRequest()->getParam('str_id');    
        $art_id = $this->view->art_id = $this->getRequest()->getParam('art_id');    

        $this->view->article = $parts->retrieveArticle($art_id);
        $this->view->article['params'] = $parts->getArtAdditionalInfo($art_id);
        $this->view->article['price'] = $parts->getArtPrice($art_id);
        $this->view->article['image'] = $parts->getArtImageURL($art_id);
    }
}

