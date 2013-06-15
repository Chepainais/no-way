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
WHERE KOD_P2 LIKE " . $this->db->quote($subCode). " OR KOD_P LIKE " . $this->db->quote($code) . " AND kh.NAZ = " . $this->db->quote($vendor) . " ORDER BY t.CEN DESC LIMIT 1";
//         die( $sql );
        $result = $this->db->query ( $sql );
        foreach ( $result->fetchAll () as $tow ) {
            $tow['CEN'] = Application_Model_Currency::convert($tow['CEN'], 'LVL', 'NOK', 1.21);
            return ($tow['CEN']);
        }
    }

}

