<?php

class Application_Model_Intercar
{
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
        $sql = "SELECT t.`KOD`, tc.CEN FROM `tow` t
            LEFT JOIN tow_cen tc 
               ON t.KOD = tc.TOW_KOD
        WHERE KOD_P = '$code' 
          # OR KOD_2 = '$code'
          # OR KOD_P2 = '$code'
             LIMIT 1";
        
        $result = $this->db->query ( $sql );
        foreach ( $result->fetchAll () as $tow ) {
            return ($tow);
        }
    }

}

