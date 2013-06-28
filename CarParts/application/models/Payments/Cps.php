<?php
class Application_Model_Payments_Cps {
	/**
	 * 
	 * @var Zend_Config
	 */
	private $config = null;
	
	public function __construct() {
		$configFile = APPLICATION_PATH . '/configs/cps.ini';
		$this->config = new Zend_Config_Ini ( $configFile, 'general' );
	}
	
	public function buildXml() {
		
		$digiSign = '';
		$order_id = mt_rand(1000,9999);
		
		$amount = mt_rand(1, 90);
		$currency = 'USD';
		$product_name = 'Product name';
		$product_url = 'http://www.bilpart.no';
		$client = array (
				'first_name' => 'FirstName',
				'last_name' => 'LastName', 
				'street' => 'Street', 
				'city' => 'City', 
				'zip' => '54321', 
				'country' => 'LV', 
				'email' => 'email@domain.com' 
		);
		$dataToSign = 'sendForAuth'.$this->config->user.$order_id.$amount.$currency.$product_name;
		$digiSign =  $this->digisign($dataToSign);
		$writer = new XMLWriter ();
		// Output directly to the user
		
		$writer->openMemory ();
		$writer->startDocument ( '1.0' , 'UTF-8');
		
		$writer->setIndent ( 4 );
		
		$writer->startElement('cpsxml');
		$writer->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$writer->writeAttribute('xsi:schemaLocation', 'https://3ds.cps.lv/GatorServo/Gator_SendForAuth.xsd');
		$writer->writeAttribute('xmlns', 'http://www.cps.lv/xml/ns/cpsxml');
		$writer->startElement('header');
			$writer->writeElement('responsetype', 'direct');
			$writer->writeElement('user', $this->config->user);
			$writer->writeElement('transType', 'DB');		
			$writer->writeElement('digiSignature', $digiSign);		
// 			$writer->writeElement('callbackUrl', $digiSign);		
			$writer->writeElement('redirectUrl', 'http://ru.bilparts.test.chepa.lv/payment/cps_result');		
		$writer->endElement(); // header
		$writer->startElement('request');
			$writer->writeElement('orderNumber', $order_id);
			$writer->startElement('cardholder');
				$writer->writeElement('firstName', $client['first_name']);
				$writer->writeElement('lastName', $client['last_name']);
				$writer->writeElement('street', $client['street']);
				$writer->writeElement('zip', $client['zip']);
				$writer->writeElement('city', $client['city']);
				$writer->writeElement('country', $client['country']);
				$writer->writeElement('email', $client['email']);
				$writer->writeElement('ip',$_SERVER['REMOTE_ADDR']);
			$writer->endElement(); // cardholder
			$writer->startElement('amount');
				$writer->writeElement('value', $amount);
				$writer->writeElement('currency', $currency);
			$writer->endElement(); // amount
			$writer->startElement('product');
			$writer->writeElement('productName', $product_name);
			$writer->writeElement('productUrl', $product_url);
			$writer->endElement(); // product
			
		$writer->endElement(); // request
		$writer->endElement(); // cpsxml
		
		$writer->endDocument ();
		$xml = $writer->outputMemory(true);
		echo  '<pre><code>' . htmlentities($xml) . '</pre></code>';
	}
	
	private function digisign($toSign){
		$signature = null;
		$pemkey = null;
		$p12cert = array ();
		$file = $this->config->cert_dir . '/' . $this->config->cert_file;
		if (file_exists ( $file )) {
			$fd = fopen ( $file, 'r' );
			if ($fd) {
				$p12buf = fread ( $fd, filesize ( $file ) );
				fclose ( $fd );
				if(!openssl_pkcs12_read ( $p12buf, $p12cert, 'pasS%123' )){
					 $msg = openssl_error_string();
				    throw new Exception('Cannot red cps certificate');
				}
				
				$configArgs['config']  = $this->config->configArgs->config;		
						
				openssl_pkey_export ( $p12cert ['pkey'], $pemkey, null, $configArgs );
				if(!openssl_sign ( $toSign, $signature, $pemkey, OPENSSL_ALGO_SHA1 )){
					$msg = openssl_error_string();

				    throw new Exception('Cannot sign cps request');
				}
// 				openssl_free_key ( $pemkey );
				$base64_sig = base64_encode ( $signature );
				return $base64_sig;
				
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}