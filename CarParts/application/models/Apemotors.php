<?php

class Application_Model_Apemotors
{

    private $_url = "http://webshop.apemotors.lv/ProductInfoBySupplierCode.aspx";
    private $_vendors = array('REINZ' ,'BOSCH' ,'LEMFÖRDER' ,'WALKER' ,'BLUE PRINT' ,'SKF' ,'BOGE' ,'SACHS' ,'ELSTOCK' ,'BUDWEG CALIPER' ,'SPIDAN' ,'MAHLE ORIGINAL' ,
                              'KILEN' ,'FERODO' ,'PAYEN' ,'FAE' ,'LUK' ,'GATES' ,'MOOG' ,'DENSO' ,'BOSAL' ,'METELLI' ,'ERA' ,'JP GROUP' ,'NISSENS' ,'AE' ,'INA' ,
                              'FEBI BILSTEIN' ,'KONI' ,'AP' ,'CORTECO' ,'GLYCO' ,'GRAF' ,'CONTITECH' ,'NGK' ,'SNR' ,'PIERBURG' ,'HERTH+BUSS JAKOPARTS' ,'SASIC' ,
                              'PEX' ,'AISIN' ,'VEMO' ,'FACET' ,'GOETZE ENGINE' ,'CALORSTAT BY VERNET' ,'MAGNETI MARELLI' ,'DAYCO' ,'CHAMPION' ,'CALIX' ,
                              'WIX FILTERS' ,'BREMBO' ,'FTE' ,'VAICO' ,'MESSMER' ,'GOETZE' ,'ZF PARTS' ,'BERAL' ,'PE AUTOMOTIVE' ,'FRI.TECH.' ,'OSRAM' ,'WOLF' ,
                              'ZAFFO' ,'SCANTECH' ,'FA1' ,'DELPHI' ,'MOTUL' ,'MANN-FILTER' ,'EXEDY' ,'BEHR THERMOT-TRONIK' ,'NÜRAL' ,'YUASA' ,'GSP' ,
                              'TRW ENGINE COMPONENT' ,'DT' ,'TRUSTING' ,'HERTH+BUSS ELPARTS' ,'ZF SRE' ,'BERGA');
    /**
     * @var Zend_Cache
     */
    private $cache;
    
    function __construct(){
        $frontendOptions = array(
                'lifetime' => 10800, // cache lifetime of 3 hours
                'automatic_serialization' => true
        );
        
        $backendOptions = array(
                'cache_dir' => APPLICATION_PATH. '/../misc/temp/' // Directory where to put the cache files
        );
        
        // getting a Zend_Cache_Core object
        $this->cache = Zend_Cache::factory('Core',
                'File',
                $frontendOptions,
                $backendOptions);
        
        
    }
/**
 * Get Ape Item prices
 * 
 * 
 * @param array $itemCodes
 * @return array
 */
    function getPrices (Array $itemCodes)
    {
//         var_dump($itemCodes);
        $itemCodesOrigin = $itemCodes;

        $items = array();
        // Savācam sakešotās vērtības, tās preces vairs neapstrādāsim
        foreach($itemCodes as $key => $code){
            // Ja nav Ape ražotāju sarakstā, tad vispār neapskatam
            if(!in_array(strtoupper($code['vendor']), $this->_vendors)){
                unset($itemCodes[$key]);
            } else {
                $item = $item = $this->cache->load('a'.$key);
                if($item){
                    $items[$key] = $item;
                    unset($itemCodes[$key]);
                }
            }
        }

        // Sagatavojam kodus Ape pieprasījumam
        $justCodes = array();
        foreach($itemCodes as $itemId => $itemParams){
            $justCodes[] = $itemParams['code'];
        }
        $data['productCodes'] = join(';', $justCodes);
        
        // Veidojam Ape pieprasījumu
        if(!empty($itemCodes)){
            $response = $this->request($data);
        }
        $xml = simplexml_load_string($response);

            // var_dump($itemCodesOrigin);
        foreach ($xml->Product as $item) {
            $itemCode = str_replace('productCodes=', '', $item->SupplierProductCode);
            $id = null;
            
            $id = $this->_findItemIdFromCode($itemCode, $itemCodesOrigin);
            
            $items[$id] = array( 'a' => '1');
            if ($id) {
                if ((string) $item->Found == 'True') {
                    foreach ($item->ProductDetails as $product) {
                        // return price ONLY if vendors matches
                        if((string)$product->SupplierName == $itemCodesOrigin[$id]['vendor']){
                            $items[$id]['price'] = (string) $product->Price;
                            $items[$id]['availableQuantity'] = (string) $product->AvailableQuantity;
                            $items[$id]['description'] = (string) $product->Description;
                            $items[$id]['supplierName'] = (string) $product->SupplierName;
                        }
                    }
                }
                
                if (! $this->cache->save($items[$id], 'a' . $id)) {
                   // @todo log error
                }
            } else {
                // @todo log itemnot found error
            }
        }

        return $items;
    }
    /**
     * Find item id in items array
     * 
     * @param string $code item code
     * @param array $items item array('id' => array('code', 'vendor'))
     * @return string
     */
    private function _findItemIdFromCode($code, $items){
        foreach($items as $itemId => $item){
            if($item['code'] == $code){
                return $itemId;
            }
        }
        return false;
    }

    private function request ($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query($data)));
        $return = curl_exec($ch);
        return $return;
    }
}