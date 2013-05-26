<?php
class PartsController extends Zend_Controller_Action 

{
	/**
	 *
	 * @var Zend_Db
	 */
	private $db;
	public function init() {
		$db = Zend_Registry::get ( 'db' );
		$this->db = $db;
		
		// Uzsetojam meklēšanas formu
		// @todo šo vajadzētu darīt kaut kur, kur vienmēr šo ielādē (partial,
		// bootstrap ?)
		
		$form = new Application_Form_ModelSearch ();
		$parts = new Application_Model_Parts ();
		$vendor = $form->getElement ( 'vendor' );
		$vendor->setValue ( $this->getRequest ()->getParam ( 'vendor_id' ) );
		$fuel_type = $form->getElement('fuel');
		$year = $form->getElement('year');
		$year->setValue($this->getRequest ()->getParam ( 'year' ));
		
		$model = $form->getElement ( 'model' );
		if($this->getRequest ()->getParam ( 'vendor_id' )){
			$model->addMultiOptions ( $parts->retrieveVendorModels ( $this->getRequest ()->getParam ( 'vendor_id' ), true ) );
			if ($this->getRequest ()->getParam ( 'model_id' )) {
				$model->setValue ( $this->getRequest ()->getParam ( 'model_id' ) );
				$fuel_type->addMultiOptions($parts->getModelFuels($this->getParam('model_id')));
				$year->addMultiOptions($parts->getModelYears($this->getParam('model_id')));
				if($this->getRequest()->getParam('fuel')){
					$fuel_type->setValue($this->getRequest()->getParam('fuel'));
				}
			}
		}


		
		$this->view->formModel = $form;
		$js = <<<EOF
	$(document).ready(function(){
		$('#vendor, #model').change(function(){
				$('#fuel, #year').val('');
				$(this).parent('form').submit();
				});
	});
EOF;
		$this->view->headScript()->appendScript($js);
	}
	public function indexAction() {
		// action body
	}
	
	/**
	 * Ražotāju saraksts
	 */
	public function vendorsAction() {
		$parts = new Application_Model_Parts ();
		$this->view->vendors = $parts->retrieveVendors ();
	}
	
	/**
	 * Ražotāja modeļu saraksts
	 */
	public function vendorAction() {
		$vendor_id = $this->getRequest ()->getParam ( 'vendor_id' );
		$this->view->vendor_id = $vendor_id;
		$parts = new Application_Model_Parts ( $vendor_id );
		$this->view->models = $parts->retrieveVendorModels ( $vendor_id );
	}
	
	/**
	 * Modeļi
	 */
	public function modelAction() {
		$vendor_id = $this->view->vendor_id = $this->getRequest ()->getParam ( 'vendor_id' );
		$model_id = $this->view->model_id = $this->getRequest ()->getParam ( 'model_id' );
		$parts = new Application_Model_Parts ();
		$this->view->str_id = $parts->getModelSTR_ID ( $model_id );
		$this->view->types = $parts->retrieveModelTypes ( $model_id, $this->getParam('fuel'), $this->getParam('year') );
	}
	/**
	 * Modeļa tips (apakšmodelis)
	 */
	public function typeAction() {
		$vendor_id = $this->view->vendor_id = $this->getRequest ()->getParam ( 'vendor_id' );
		$model_id = $this->view->model_id = $this->getRequest ()->getParam ( 'model_id' );
		$typ_id = $this->view->typ_id = $this->getRequest ()->getParam ( 'typ_id' );
		$str_id = $this->view->str_id = $this->getRequest ()->getParam ( 'str_id' );
		
		$parts = new Application_Model_Parts ();
		$this->view->searchTree = $parts->searchTree ( $typ_id, $str_id );
	}
	/**
	 * Detaļas pēc auto modeļa, tipa un detaļas kategorijas
	 */
	public function articlesAction() {
		$parts = new Application_Model_Parts ();
		$ApeMotors = new Application_Model_Apemotors();
		$Intercar = new Application_Model_Intercar();
		
		$vendor_id = $this->view->vendor_id = $this->getRequest ()->getParam ( 'vendor_id' );
		$model_id = $this->view->model_id = $this->getRequest ()->getParam ( 'model_id' );
		$typ_id = $this->view->typ_id = $this->getRequest ()->getParam ( 'typ_id' );
		$str_id = $this->view->str_id = $this->getRequest ()->getParam ( 'str_id' );
		
		$searchTree = $parts->retrieveArticles ( $typ_id, $str_id );
		
		$searchTree2 = array();
		foreach ( $searchTree as $id => $st ) {
		    $params  = $parts->retrieveArticle ( $st ['LA_ART_ID'] );
		    $searchTree2 [$st ['LA_ART_ID']] = $st;
			$searchTree2 [$st ['LA_ART_ID']] ['params'] = $params;
// 			$searchTree2 [$st ['LA_ART_ID']] ['image'] = $parts->getArtImageURL ( $st ['LA_ART_ID'] );
			$codes[$st ['LA_ART_ID']] = Array ('code' => $params['ART_ARTICLE_NR'], 'vendor' => $params['SUP_BRAND']);
			
			$searchTree2 [$st ['LA_ART_ID']]['intercar'] = $Intercar->getItemPrice($params['ART_ARTICLE_NR'], $params['SUP_BRAND']);
			
		}
            
            // Ievācam Ape Motors cenas. Vācam atsevišķi, lai var vienā
        // pieprasījumā visas pieprasīt.
        $ApePrices = $ApeMotors->getPrices($codes);
        foreach ($ApePrices as $itemId => $ApePrice) {
            if (isset($ApePrice['ProductDetails'])) {
                $ApePrice['ProductDetails']['Price'] = Application_Model_Currency::convert( $ApePrice['ProductDetails']['Price'], 'LVL', 'NOK', 1.21);
                $searchTree2[$itemId]['Ape'] = $ApePrice;
            }
        }
        foreach ($searchTree2 as $item_id => $st) {
            if (! isset($st['Ape']) && ! isset($st['intercar'])) {
                unset($searchTree2[$item_id]);
            } else {
                $searchTree2[$item_id]['image'] = $parts->getArtImageURL($item_id);
                $searchTree2[$item_id]['criteria'] = array();
                $searchTree2[$item_id]['criteria'] = $parts->getArtCriteria($item_id);
            }
        }

		$this->view->searchTree2 = $searchTree2;
	}
	
	/**
	 * Viena prece
	 */
	public function articleAction() {
		$parts = new Application_Model_Parts ();
		$ApeMotors = new Application_Model_Apemotors();
		
		$vendor_id = $this->view->vendor_id = $this->getRequest ()->getParam ( 'vendor_id' );
		$model_id = $this->view->model_id = $this->getRequest ()->getParam ( 'model_id' );
		$typ_id = $this->view->typ_id = $this->getRequest ()->getParam ( 'typ_id' );
		$str_id = $this->view->str_id = $this->getRequest ()->getParam ( 'str_id' );
		$art_id = $this->view->art_id = $this->getRequest ()->getParam ( 'art_id' );
		
		$article = $parts->retrieveArticle ( $art_id );
		$this->view->article = $article;
		$this->view->article ['params'] = $parts->getArtAdditionalInfo ( $art_id );
		
		$price = current($ApeMotors->getPrices(Array($article['ART_ID'] => array( 'code' => $article['ART_ARTICLE_NR'], 'vendor' => $article['SUP_BRAND']))));
		
		$this->view->article ['price'] = $price;
		$this->view->article ['image'] = $parts->getArtImageURL ( $art_id );
		$this->view->article ['criteria'] = $parts->getArtCriteria ( $art_id );
	}
	/**
	 * Meklēšanas darbība
	 */
	public function searchAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		if($this->getParam('vendor')){
			if($this->getParam('model')){
				$this->_redirect('/parts/vendor/' . 
								$this->getParam('vendor') . 
								'/model/' . 
								$this->getParam('model') . 
								'?fuel=' .$this->getParam('fuel') .
								'&year=' .$this->getParam('year') 
						);
				return;
			}
			$this->_redirect('/parts/vendor/' . $this->getParam('vendor'));
		}
	}
}

