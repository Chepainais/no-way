<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initApplication ()
    {
        $this->bootstrap('frontcontroller');
        $front = $this->getResource('frontcontroller');
        $front->addModuleDirectory(dirname(__FILE__) . '/modules');
    }

    protected function _initConfig ()
    {
        $config = new Zend_Config($this->getOptions(), true);
        
        Zend_Registry::set('config', $config);
        
        return $config;
    }

    protected function _initDb ()
    {
        // REGISTER DATABASE CONNECTION
        $configFile = APPLICATION_PATH . '/configs/application.ini';
        $config = new Zend_Config_Ini($configFile, 'general');
        Zend_Registry::set('config', $config);
        $dbTecdoc = Zend_Db::factory($config->dbTecdoc->adapter, 
                $config->dbTecdoc->params->toArray());
        Zend_Registry::set('dbTecdoc', $dbTecdoc);
        
        $dbIntercar = Zend_Db::factory($config->dbIntercars->adapter,
                $config->dbIntercars->params->toArray());
        Zend_Registry::set('dbIntercars', $dbIntercar);        
        
        $db = Zend_Db::factory($config->db->adapter, 
                $config->db->params->toArray());
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);
    }

    protected function _initDoctype ()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        // @todo jāieliek pareiza valoda
        $view->headMeta()
            ->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8')
            ->appendHttpEquiv('Content-Language', 'en-US');
        $view->addHelperPath('Custom/View/Helper', 'Custom_View_Helper');
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
                new Zend_Controller_Router_Route(
                        'parts/vendor/:vendor_id/model/:model_id', 
                        array(
                                'controller' => 'parts',
                                'action' => 'model'
                        )));
        $router->addRoute('parts-vendor-model-type', 
                new Zend_Controller_Router_Route(
                        'parts/vendor/:vendor_id/model/:model_id/type/:typ_id', 
                        array(
                                'controller' => 'parts',
                                'action' => 'type'
                        )));
        $router->addRoute('parts-vendor-model-type-cat', 
                new Zend_Controller_Router_Route(
                        'parts/vendor/:vendor_id/model/:model_id/type/:typ_id/cat/:str_id', 
                        array(
                                'controller' => 'parts',
                                'action' => 'type'
                        )));
        $router->addRoute('parts-vendor-model-type-cat-art', 
                new Zend_Controller_Router_Route(
                        'parts/vendor/:vendor_id/model/:model_id/type/:typ_id/cat/:str_id/:art', 
                        array(
                                'controller' => 'parts',
                                'action' => 'articles'
                        )));
        $router->addRoute('parts-vendor-model-type-cat-article', 
                new Zend_Controller_Router_Route(
                        'parts/vendor/:vendor_id/model/:model_id/type/:typ_id/cat/:str_id/art/:art_id', 
                        array(
                                'controller' => 'parts',
                                'action' => 'article'
                        )));
        $router->addRoute('article-read',
                new Zend_Controller_Router_Route(
                        'article/read/:article_alias',
                        array(
                                'controller' => 'article',
                                'action' => 'read'
                        )));
        $router->addRoute('order',
        		new Zend_Controller_Router_Route(
        				'order/:order_id/:token',
        				array(
        						'controller' => 'order',
        						'action' => 'order'
        				)));
    }
    
    protected function _initRegisterPlugins(){
        
        $defaultLocale = Zend_Registry::set('Zend_Locale', new Zend_Locale());
        
        $front = Zend_Controller_Front::getInstance();
        // Ielādējam lokāles un tulkojumus no DB
        $front->registerPlugin(new Application_Plugin_LocaleCheck());
        // Layout mainīgo ielāde
        $front->registerPlugin(new Application_Plugin_LayoutVariables());

    }
}