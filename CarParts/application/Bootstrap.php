<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initDoctype ()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
    }

    protected function _initRoutes ()
    {
        $ctrl = Zend_Controller_Front::getInstance();
        $router = $ctrl->getRouter();
        $router->addRoute('parts-vendor', 
                new Zend_Controller_Router_Route('parts/vendor/:vendor_id', 
                        array(
                                'controller' => 'parts',
                                'action' => 'vendor'
                        )));
        
        $router->addRoute('parts-vendor-model', 
                new Zend_Controller_Router_Route('parts/vendor/:vendor_id/model/:model_id', 
                        array(
                                'controller' => 'parts',
                                'action' => 'model'
                        )));
        $router->addRoute('parts-vendor-model-type',
        		new Zend_Controller_Router_Route('parts/vendor/:vendor_id/model/:model_id/type/:typ_id',
        				array(
        						'controller' => 'parts',
        						'action' => 'type'
        				)));
        $router->addRoute('parts-vendor-model-type-cat',
        		new Zend_Controller_Router_Route('parts/vendor/:vendor_id/model/:model_id/type/:typ_id/cat/:str_id',
        				array(
        						'controller' => 'parts',
        						'action' => 'type'
        				)));
        $router->addRoute('parts-vendor-model-type-cat-art',
        		new Zend_Controller_Router_Route('parts/vendor/:vendor_id/model/:model_id/type/:typ_id/cat/:str_id/:art',
        				array(
        						'controller' => 'parts',
        						'action' => 'articles'
        				)));
        $router->addRoute('parts-vendor-model-type-cat-article',
        		new Zend_Controller_Router_Route('parts/vendor/:vendor_id/model/:model_id/type/:typ_id/cat/:str_id/art/:art_id',
        				array(
        						'controller' => 'parts',
        						'action' => 'article'
        				)));
    }
}

