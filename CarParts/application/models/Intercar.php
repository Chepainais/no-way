<?php

class Application_Model_Intercar
{
    
    private $_bad_symbols = array(' ', '\\', '/');
    
    /**
     * 
     * @var Zend_Db
     */
    public $db;
    
    function __construct(){
        $config = Zend_Registry::get ( 'config' );
        $db = Zend_Registry::get ( 'dbIntercars' );
        $this->db = $db;
    }
    public function getItemPrice($code, $vendor){
        
        $subCode = str_replace($this->_bad_symbols, '', $code);
        
        $sql = "SELECT kh.NAZ, t.KOD, tc.CEN FROM `tow` t 
LEFT JOIN tow_cen tc ON t.KOD = tc.TOW_KOD 
LEFT JOIN kh ON t.KH_KOD = kh.KOD
WHERE KOD_P2 LIKE '$subCode' OR KOD_P LIKE '$code' AND kh.NAZ = '$vendor' LIMIT 1";
        
        $result = $this->db->query ( $sql );
        foreach ( $result->fetchAll () as $tow ) {
            return ($tow);
        }
    }

}

