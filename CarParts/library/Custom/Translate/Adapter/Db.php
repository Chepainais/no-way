<?php

class Custom_Translate_Adapter_Db extends Zend_Translate_Adapter
{

    private $_data = array();

    /**
     * override the default Zend_Translate_Adapter function with our own
     */
    protected function _loadTranslationData ($filename, $locale, 
            array $options = array())
    {
        $this->_data = array();
        
        // get translations from db
        
        $db = Zend_Registry::get('db');
        
        $translation = new Application_Model_Translations(
                array(
                        'db' => $db
                ));
        $translations = $translation->getListByLocale(
                Zend_Registry::get('Zend_Locale')->__toString());
        
        foreach ($translations as $trans) {
            $this->_data[$locale][$trans['msgid']] = $trans['msgstring'];
        }
        
        return $this->_data;
    }

    public function toString ()
    {
        return 'Custom_Translate_Adapter_Db';
    }

    private function _untranslated ($messageId)
    {
        if($this->_options['insertUntranslated']){
        $locale = $this->getLocale();
        if (strlen($this->getLocale()) != 2) {
            $locale = substr($locale, 0, - strlen(strrchr($locale, '_')));
        }
        
        $translation = new Application_Model_DbTable_Translations();
        
        if (! $translation->insertOrUpdate(
                Array(
                        'msgid' => $messageId,
                        'msgstring' => '',
                        'locale' => $locale
                ))) {
            throw new Exception('Bad translation db table insert');
        }
    }
}

    /**
     * Translates the given string
     * returns the translation
     *
     * @see Zend_Locale
     * @param string|array $messageId
     *            Translation string, or Array for plural translations
     * @param string|Zend_Locale $locale
     *            (optional) Locale/Language to use, identical with
     *            locale identifier, @see Zend_Locale for more information
     * @return string
     */
    public function translate ($messageId, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->_options['locale'];
        }
        
        $plural = null;
        if (is_array($messageId)) {
            if (count($messageId) > 2) {
                $number = array_pop($messageId);
                if (! is_numeric($number)) {
                    $plocale = $number;
                    $number = array_pop($messageId);
                } else {
                    $plocale = 'en';
                }
                
                $plural = $messageId;
                $messageId = $messageId[0];
            } else {
                $messageId = $messageId[0];
            }
        }
        
        if (! Zend_Locale::isLocale($locale, true, false)) {
            if (! Zend_Locale::isLocale($locale, false, false)) {
                // language does not exist, return original string
                $this->_log($messageId, $locale);
                // use rerouting when enabled
                if (! empty($this->_options['route'])) {
                    if (array_key_exists($locale, $this->_options['route']) &&
                             ! array_key_exists($locale, $this->_routed)) {
                        $this->_routed[$locale] = true;
                        return $this->translate($messageId, 
                                $this->_options['route'][$locale]);
                    }
                }
                
                $this->_routed = array();
                if ($plural === null) {
                    return $messageId;
                }
                
                $rule = Zend_Translate_Plural::getPlural($number, $plocale);
                if (! isset($plural[$rule])) {
                    $rule = 0;
                }
                
                return $plural[$rule];
            }
            
            $locale = new Zend_Locale($locale);
        }
        
        $locale = (string) $locale;
        if ((is_string($messageId) || is_int($messageId)) &&
                 isset($this->_translate[$locale][$messageId])) {
            // return original translation
            if ($plural === null) {
                $this->_routed = array();
                if (! empty($this->_translate[$locale][$messageId])) {
                    return $this->_translate[$locale][$messageId];
                } else {
                    return $messageId;
                }
            }
            
            $rule = Zend_Translate_Plural::getPlural($number, $locale);
            if (isset($this->_translate[$locale][$plural[0]][$rule])) {
                $this->_routed = array();
                return $this->_translate[$locale][$plural[0]][$rule];
            }
        } else 
            if (strlen($locale) != 2) {
                // faster than creating a new locale and separate the leading
                // part
                $locale = substr($locale, 0, - strlen(strrchr($locale, '_')));
                
                if ((is_string($messageId) || is_int($messageId)) &&
                         isset($this->_translate[$locale][$messageId])) {
                    // return regionless translation (en_US -> en)
                    if ($plural === null) {
                        $this->_routed = array();
                        return $this->_translate[$locale][$messageId];
                    }
                    
                    $rule = Zend_Translate_Plural::getPlural($number, $locale);
                    if (isset($this->_translate[$locale][$plural[0]][$rule])) {
                        $this->_routed = array();
                        return $this->_translate[$locale][$plural[0]][$rule];
                    }
                }
            }
        
        $this->_log($messageId, $locale);
        $this->_untranslated($messageId);
        // use rerouting when enabled
        if (! empty($this->_options['route'])) {
            if (array_key_exists($locale, $this->_options['route']) &&
                     ! array_key_exists($locale, $this->_routed)) {
                $this->_routed[$locale] = true;
                return $this->translate($messageId, 
                        $this->_options['route'][$locale]);
            }
        }
        
        $this->_routed = array();
        if ($plural === null) {
            return $messageId;
        }
        
        $rule = Zend_Translate_Plural::getPlural($number, $plocale);
        if (! isset($plural[$rule])) {
            $rule = 0;
        }
        
        return $plural[$rule];
    }
}