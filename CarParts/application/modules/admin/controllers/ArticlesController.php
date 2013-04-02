<?php
/**
 *
 * @author Aleksis
 * @version 
 */
class Admin_ArticlesController extends Zend_Controller_Action {
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */
	public function indexAction() {
		return null;
	}
	
	public function createAction(){
		$form = new Admin_Form_ArticleCreate();
		
		if($this->getRequest()->getPost()){
			if($form->isValid($this->getRequest()->getQuery())){
				//$this->_redirect($this->view->url(array('action' => 'index')));
			}
			else{
				echo 'nav ievadīti dati';
			//	$this->_redirect($this->view->url());
			}
			
		}
		
		
		
		$this->view->form = $form;
	}
	
	/**
	 * Sets the view field
	 * 
	 * @param $view Zend_View_Interface        	
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
