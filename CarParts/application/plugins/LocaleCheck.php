<?php
class Application_Plugin_LocaleCheck extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        
       $domain = $_SERVER['HTTP_HOST'];

       $config = Zend_Registry::get('config');
       
       $locale = new Zend_Locale($config->domainLanguages->$domain);
       Zend_Registry::set('Zend_Locale', $locale);
       
//        var_dump(Zend_Registry::get('Zend_Locale')); die();
       if($locale){
          try{

              if (Zend_Locale::isLocale($locale)) {
                  if (Zend_Registry::isRegistered('Zend_Locale')) {
                      Zend_Registry::get('Zend_Locale')->setLocale($locale);
                  }
                  $translate = new Zend_Translate('Custom_Translate_Adapter_Db', 'DO NOT EMPTY THIS SHIT', Zend_Registry::get('Zend_Locale'),
                          array('disableNotices' => true, 'insertUntranslated' => true));
                  Zend_Registry::set('Zend_Translate', $translate);
                  
              }
              else {
                  throw new Zend_Exception('Bad locale');
              }
           } catch (Zend_Locale_Exception $e){
               // Default locale
              $usLocale = new Zend_Locale('en');
           }
           $currency = new Zend_Currency('sv_SE');
           Zend_Registry::set('Zend_Currency', $currency);
          
       }
       else {
           throw new Zend_Exception('No language found');
       }
    }
}