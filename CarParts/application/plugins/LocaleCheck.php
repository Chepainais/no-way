<?php
class Application_Plugin_LocaleCheck extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        
       $domain = $_SERVER['HTTP_HOST'];

       $config = Zend_Registry::get('config');
       
       $locale = $config->domainLanguages->$domain;
       Zend_Registry::set('language', $locale);
       if($locale){
          try{

              if (Zend_Locale::isLocale($locale)) {
                  if (Zend_Registry::isRegistered('Zend_Locale')) {
                      Zend_Registry::get('Zend_Locale')->setLocale($locale);
                  }
                  if (Zend_Registry::isRegistered('Zend_Translate')) {
                      Zend_Registry::get('Zend_Translate')->setLocale($locale);
                  }
              }
              else {
                  throw new Zend_Exception('Bad locale');
              }
           } catch (Zend_Locale_Exception $e){
               // Default locale
              $usLocale = new Zend_Locale('en_US');
           }
              
          
       }
       else {
           throw new Zend_Exception('No language found');
       }
    }
}