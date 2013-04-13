<?php

class Application_Model_Translations
{
    public function getListByLocale ($locale){
        $translations = new Application_Model_DbTable_Translations;
        return $translations->getListByLocale($locale);
//         return Application_Model_DbTable_Translations::getListByLocale($locale);
    }
    
}

