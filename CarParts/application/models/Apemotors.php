<?php

class Application_Model_Apemotors
{

    private $_url = "http://webshop.apemotors.lv/ProductInfoBySupplierCode.aspx";

    function getPrice ($itemCode)
    {
        $data['productCodes'] = $itemCode;
        $response = $this->request($data);
        
        $xml = simplexml_load_string($response);
        $items = array();
        foreach ($xml->Product as $item) {
            if ((string) $item->Found == 'True') {
                $id = array_search((string) $item->SupplierProductCode, $itemCode);
                $items[$id]['price'] = (string) $item->ProductDetails->Price;
                $items[$id]['availableQuantity'] = (string) $item->ProductDetails->AvailableQuantity;
                $items[$id]['description'] = (string) $item->ProductDetails->Description;
                $items[$id]['supplierName'] = (string) $item->ProductDetails->SupplierName;
            }
        }
        
        return $items;
    }

    function getPrices (Array $itemCodes)
    {
        $data['productCodes'] = join(';', $itemCodes);
        $response = $this->request($data);
        
        $xml = simplexml_load_string($response);
        $items = array();
        foreach ($xml->Product as $item) {
            if ((string) $item->Found == 'True') {
                $id = array_search((string) $item->SupplierProductCode, $itemCodes);
                $items[$id]['price'] = (string) $item->ProductDetails->Price;
                $items[$id]['availableQuantity'] = (string) $item->ProductDetails->AvailableQuantity;
                $items[$id]['description'] = (string) $item->ProductDetails->Description;
                $items[$id]['supplierName'] = (string) $item->ProductDetails->SupplierName;
            }
        }

        return $items;
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