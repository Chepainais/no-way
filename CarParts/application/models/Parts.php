<?php

/**
 * Vehicles Parts Model
 * @author Aleksis
 *
 */
class Application_Model_Parts {
    
	public $language = 33;
	/**
	 *
	 * @var Zend_Db
	 */
	private $db;
	function __construct() {
	    $config = Zend_Registry::get ( 'config' );
	    $language_code = Zend_Registry::get ( 'Zend_Locale' ) ;
	    $this->language = $config->tecdocLanguageCodes->$language_code;
		$db = Zend_Registry::get ( 'dbTecdoc' );
		$this->db = $db;
	}
	
	/**
	 * Retrieve Vehicle parts Vendors
	 *
	 * @param $asArray Return
	 *        	as array vendor[id] = vendorName
	 */
	public function retrieveVendors($asArray = false) {
		$sql = "SELECT
					MFA_ID,
					MFA_BRAND
				FROM
					MANUFACTURERS
				ORDER BY
					MFA_BRAND
#				LIMIT
#					100
				;";
		$result = $this->db->query ( $sql );
		if (! $asArray) {
			return $result->fetchAll ();
		} else {
			$vendorList = array ();
			foreach ( $result->fetchAll () as $vendor ) {
				$vendorList [$vendor ['MFA_ID']] = $vendor ['MFA_BRAND'];
			}
			
			return $vendorList;
		}
	}
	
	/**
	 * Returns vendor models list
	 *
	 * @param int $vendor_id        	
	 */
	public function retrieveVendorModels($vendor_id, $asArray = false) {
		if (( int ) $vendor_id) {
			$sql = "SELECT MOD_ID, TEX_TEXT AS MOD_CDS_TEXT, MOD_PCON_START,  MOD_PCON_END, MOD_PC, MOD_CV
      	FROM
       		 MODELS
      	INNER JOIN COUNTRY_DESIGNATIONS ON CDS_ID = MOD_CDS_ID
        INNER JOIN DES_TEXTS ON TEX_ID = CDS_TEX_ID
        WHERE
       		MOD_MFA_ID = $vendor_id AND
       		CDS_LNG_ID = $this->language
        ORDER BY
       		MOD_CDS_TEXT;";
			$result = $this->db->query ( $sql );
			if (! $asArray) {
				return $result->fetchAll ();
			} else {
				$modelList = array ();
				foreach ( $result->fetchAll () as $model ) {
					$modelList [$model ['MOD_ID']] = $model ['MOD_CDS_TEXT'];
				}
				return $modelList;
			}
		} else {
			throw new Zend_Exception ( 'No vendor is set' );
		}
	}
	
	/**
	 * Returns Vendor model types
	 *
	 * @param int $model_id        	
	 */
	public function retrieveModelTypes($model_id, $fuel = null, $year = null) {
		if ($fuel) {
			$fuel = "AND TYP_KV_FUEL_DES_ID = $fuel";
		}
		if ($year) {
			$year = " AND $year >= SUBSTRING(TYP_PCON_START, 1, 4) AND $year <= IF(TYP_PCON_END > 0, TYP_PCON_END, YEAR(NOW())) ";
			// (TYP_PCON_START BETWEEN " . $year . "00 AND ".$year."12 OR
		// TYP_PCON_END BETWEEN " . $year . "00 AND ".$year."12)";
		}
		if (( int ) $model_id) {
			$sql = "SELECT
				TYP_ID,
				MFA_BRAND,
				DES_TEXTS7.TEX_TEXT AS MOD_CDS_TEXT,
				DES_TEXTS.TEX_TEXT AS TYP_CDS_TEXT,
				TYP_PCON_START,
				TYP_PCON_END,
				TYP_CCM,
				TYP_KW_FROM,
				TYP_KW_UPTO,
				TYP_HP_FROM,
				TYP_HP_UPTO,
				TYP_CYLINDERS,
				ENGINES.ENG_CODE,
				DES_TEXTS2.TEX_TEXT AS TYP_ENGINE_DES_TEXT,
				DES_TEXTS3.TEX_TEXT AS TYP_FUEL_DES_TEXT,
				IFNULL(DES_TEXTS4.TEX_TEXT, DES_TEXTS5.TEX_TEXT) AS TYP_BODY_DES_TEXT,
				DES_TEXTS6.TEX_TEXT AS TYP_AXLE_DES_TEXT,
				TYP_MAX_WEIGHT
			FROM
				TYPES
				INNER JOIN MODELS ON MOD_ID = TYP_MOD_ID
				INNER JOIN MANUFACTURERS ON MFA_ID = MOD_MFA_ID
				INNER JOIN COUNTRY_DESIGNATIONS AS COUNTRY_DESIGNATIONS2 ON COUNTRY_DESIGNATIONS2.CDS_ID = MOD_CDS_ID AND COUNTRY_DESIGNATIONS2.CDS_LNG_ID = $this->language
				INNER JOIN DES_TEXTS AS DES_TEXTS7 ON DES_TEXTS7.TEX_ID = COUNTRY_DESIGNATIONS2.CDS_TEX_ID
				INNER JOIN COUNTRY_DESIGNATIONS ON COUNTRY_DESIGNATIONS.CDS_ID = TYP_CDS_ID AND COUNTRY_DESIGNATIONS.CDS_LNG_ID = $this->language
				INNER JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = COUNTRY_DESIGNATIONS.CDS_TEX_ID
				 LEFT JOIN DESIGNATIONS ON DESIGNATIONS.DES_ID = TYP_KV_ENGINE_DES_ID AND DESIGNATIONS.DES_LNG_ID = $this->language
				 LEFT JOIN DES_TEXTS AS DES_TEXTS2 ON DES_TEXTS2.TEX_ID = DESIGNATIONS.DES_TEX_ID
				 LEFT JOIN DESIGNATIONS AS DESIGNATIONS2 ON DESIGNATIONS2.DES_ID = TYP_KV_FUEL_DES_ID AND DESIGNATIONS2.DES_LNG_ID = $this->language
				 LEFT JOIN DES_TEXTS AS DES_TEXTS3 ON DES_TEXTS3.TEX_ID = DESIGNATIONS2.DES_TEX_ID
				 LEFT JOIN LINK_TYP_ENG ON LTE_TYP_ID = TYP_ID
				 LEFT JOIN ENGINES ON ENG_ID = LTE_ENG_ID
				 LEFT JOIN DESIGNATIONS AS DESIGNATIONS3 ON DESIGNATIONS3.DES_ID = TYP_KV_BODY_DES_ID AND DESIGNATIONS3.DES_LNG_ID = $this->language
				 LEFT JOIN DES_TEXTS AS DES_TEXTS4 ON DES_TEXTS4.TEX_ID = DESIGNATIONS3.DES_TEX_ID
				 LEFT JOIN DESIGNATIONS AS DESIGNATIONS4 ON DESIGNATIONS4.DES_ID = TYP_KV_MODEL_DES_ID AND DESIGNATIONS4.DES_LNG_ID = $this->language
				 LEFT JOIN DES_TEXTS AS DES_TEXTS5 ON DES_TEXTS5.TEX_ID = DESIGNATIONS4.DES_TEX_ID
				 LEFT JOIN DESIGNATIONS AS DESIGNATIONS5 ON DESIGNATIONS5.DES_ID = TYP_KV_AXLE_DES_ID AND DESIGNATIONS5.DES_LNG_ID = $this->language
				 LEFT JOIN DES_TEXTS AS DES_TEXTS6 ON DES_TEXTS6.TEX_ID = DESIGNATIONS5.DES_TEX_ID
			WHERE
				TYP_MOD_ID = $model_id
				$fuel
				$year
			ORDER BY
				MFA_BRAND,
				MOD_CDS_TEXT,
			    TYP_CDS_TEXT,
				TYP_PCON_START,
				TYP_CCM
			LIMIT
				100;";
			
			$result = $this->db->query ( $sql );
			
			return $result->fetchAll ();
		} else {
			throw new Zend_Exception ( 'No model set' );
		}
	}
	/**
	 * Auto detaļu kategoriju koks
	 *
	 * @param int $typ_id
	 *        	Auto type
	 * @param int $str_id
	 *        	Meklēšanas koka sakne (NULL by default)
	 */
	public function searchTree($typ_id, $str_id = NULL) {
		if ($str_id === NULL) {
			$str_id = 'NULL';
		}
		$sql = "SELECT
					STR_ID,
					TEX_TEXT AS STR_DES_TEXT,
					IF(
						EXISTS(
							SELECT
								*
							FROM
					        	SEARCH_TREE AS SEARCH_TREE2
							WHERE
								SEARCH_TREE2.STR_ID_PARENT <=> SEARCH_TREE.STR_ID
							LIMIT
								1
						), 1, 0) AS DESCENDANTS
				FROM
					           SEARCH_TREE
					INNER JOIN DESIGNATIONS ON DES_ID = STR_DES_ID
					INNER JOIN DES_TEXTS ON TEX_ID = DES_TEX_ID
				WHERE
					STR_ID_PARENT <=> $str_id AND
					DES_LNG_ID = $this->language AND
					EXISTS (
						SELECT * FROM LINK_GA_STR
							INNER JOIN LINK_LA_TYP ON LAT_TYP_ID = $typ_id AND
							                          LAT_GA_ID = LGS_GA_ID
							INNER JOIN LINK_ART ON LA_ID = LAT_LA_ID
						WHERE
							LGS_STR_ID = STR_ID
						LIMIT
							1
					);";
		$result = $this->db->query ( $sql );
		return $result->fetchAll ();
	}
	/**
	 * Preces apraksts un citi parametri
	 *
	 * @param int $article_id        	
	 */
	public function retrieveArticle($article_id) {
		$sql = "SELECT
		            ART_ID,
					ART_ARTICLE_NR,
					SUP_BRAND,
					DES_TEXTS.TEX_TEXT AS ART_COMPLETE_DES_TEXT,
					DES_TEXTS2.TEX_TEXT AS ART_DES_TEXT,
					DES_TEXTS3.TEX_TEXT AS ART_STATUS_TEXT
				FROM
					           ARTICLES
					INNER JOIN DESIGNATIONS ON DESIGNATIONS.DES_ID = ART_COMPLETE_DES_ID
					                       AND DESIGNATIONS.DES_LNG_ID = $this->language
					INNER JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = DESIGNATIONS.DES_TEX_ID
					 LEFT JOIN DESIGNATIONS AS DESIGNATIONS2 ON DESIGNATIONS2.DES_ID = ART_DES_ID
					                                        AND DESIGNATIONS2.DES_LNG_ID = $this->language
					 LEFT JOIN DES_TEXTS AS DES_TEXTS2 ON DES_TEXTS2.TEX_ID = DESIGNATIONS2.DES_TEX_ID
					INNER JOIN SUPPLIERS ON SUP_ID = ART_SUP_ID
					INNER JOIN ART_COUNTRY_SPECIFICS ON ACS_ART_ID = ART_ID
					INNER JOIN DESIGNATIONS AS DESIGNATIONS3 ON DESIGNATIONS3.DES_ID = ACS_KV_STATUS_DES_ID
					                                        AND DESIGNATIONS3.DES_LNG_ID = $this->language
					INNER JOIN DES_TEXTS AS DES_TEXTS3 ON DES_TEXTS3.TEX_ID = DESIGNATIONS3.DES_TEX_ID
				WHERE
					ART_ID = $article_id
				;";
		$result = $this->db->query ( $sql );
		
		$r = $result->fetchAll ();
		return $r [0];
	}
	/**
	 * Auto pieejamo detaļu ART_ID
	 *
	 * @param int $typ_id        	
	 * @param int $str_id        	
	 */
	public function retrieveArticles($typ_id, $str_id) {
		$sql = "SELECT
					LA_ART_ID
				FROM
					           LINK_GA_STR
					INNER JOIN LINK_LA_TYP ON LAT_TYP_ID = $typ_id AND
					                          LAT_GA_ID = LGS_GA_ID
					INNER JOIN LINK_ART ON LA_ID = LAT_LA_ID
				WHERE
					LGS_STR_ID = $str_id
				ORDER BY
					LA_ART_ID
				LIMIT
					100
				;";
		$result = $this->db->query ( $sql );
		return $result->fetchAll ();
	}
	/**
	 *
	 * @param int $MOD_ID        	
	 * @throws Zend_Exception
	 * @return string
	 */
	public function getModelSTR_ID($MOD_ID) {
		$sql = "SELECT MOD_PC, MOD_CV FROM MODELS WHERE MOD_ID = $MOD_ID LIMIT 1";
		$result = $this->db->query ( $sql );
		$result_array = $result->fetchAll ();
		// Ja vieglie auto
		if ($result_array [0] ['MOD_PC']) {
			return '10001';
		} 		// Ja smagie auto
		else if ($result_array [0] ['MOD_CV']) {
			return '20002';
		} else {
			throw new Zend_Exception ( 'Unknown vehicle type' );
		}
	}
	/**
	 * Iegūt preces attēla atrašanās vietu
	 *
	 * @param unknown_type $ART_ID        	
	 */
	public function getArtImageURL($ART_ID) {
		$sql = "SELECT
					CONCAT(
						'images/',
						GRA_TAB_NR, '/',
						GRA_GRD_ID, '.',
						IF(LOWER(DOC_EXTENSION)='jp2', 'jpg', LOWER(DOC_EXTENSION))
					) AS PATH
				FROM
					           LINK_GRA_ART
					INNER JOIN GRAPHICS ON GRA_ID = LGA_GRA_ID
					INNER JOIN DOC_TYPES ON DOC_TYPE = GRA_DOC_TYPE
				WHERE
					LGA_ART_ID = $ART_ID AND
					(GRA_LNG_ID = $this->language OR GRA_LNG_ID = 255) AND
					GRA_DOC_TYPE <> 2
				ORDER BY
					GRA_GRD_ID
				;";
		$result = $this->db->query ( $sql );
		$r = $result->fetchAll ();
		if ($r) {
			return $r [0] ['PATH'];
		} else {
			return false;
		}
	}
	public function getArtPrice($ART_ID) {
		$sql = "SELECT
					PRI_PRICE,
					DES_TEXTS.TEX_TEXT AS PRICE_UNIT_DES_TEXT,
					DES_TEXTS2.TEX_TEXT AS QUANTITY_UNIT_DES_TEXT,
					PRI_CURRENCY_CODE
				FROM
					           PRICES
					INNER JOIN DESIGNATIONS ON DESIGNATIONS.DES_ID = PRI_KV_PRICE_UNIT_DES_ID
					INNER JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = DESIGNATIONS.DES_TEX_ID
					INNER JOIN DESIGNATIONS AS DESIGNATIONS2 ON DESIGNATIONS2.DES_ID = PRI_KV_QUANTITY_UNIT_DES_ID
					INNER JOIN DES_TEXTS AS DES_TEXTS2 ON DES_TEXTS2.TEX_ID = DESIGNATIONS2.DES_TEX_ID
				WHERE
					PRI_ART_ID = $ART_ID AND
					DESIGNATIONS.DES_LNG_ID = $this->language AND
					DESIGNATIONS2.DES_LNG_ID = $this->language
				;";
		$result = $this->db->query ( $sql );
		$r = $result->fetchAll ();
		if ($r) {
			return $r [0];
		} else {
			return false;
		}
	}
	public function getArtAdditionalInfo($ART_ID) {
		$sql = "SELECT
					TMT_TEXT AS AIN_TMO_TEXT
				FROM
					           ARTICLE_INFO
					INNER JOIN TEXT_MODULES ON TMO_ID = AIN_TMO_ID
					INNER JOIN TEXT_MODULE_TEXTS ON TMT_ID = TMO_TMT_ID
				WHERE
					AIN_ART_ID = $ART_ID AND
					TMO_LNG_ID = $this->language
				ORDER BY
					AIN_TMO_TEXT	
				;";
		$result = $this->db->query ( $sql );
		
		$r = $result->fetchAll ();
		if ($r) {
			return $r [0];
		} else {
			return false;
		}
	}
	public function getArtCriteria($ART_ID) {
		$sql = "SELECT
					DES_TEXTS.TEX_TEXT AS CRITERIA_DES_TEXT,
					IFNULL(DES_TEXTS2.TEX_TEXT, ACR_VALUE) AS CRITERIA_VALUE_TEXT
				FROM
					          ARTICLE_CRITERIA
					LEFT JOIN DESIGNATIONS AS DESIGNATIONS2 ON DESIGNATIONS2.DES_ID = ACR_KV_DES_ID
					LEFT JOIN DES_TEXTS AS DES_TEXTS2 ON DES_TEXTS2.TEX_ID = DESIGNATIONS2.DES_TEX_ID
					LEFT JOIN CRITERIA ON CRI_ID = ACR_CRI_ID
					LEFT JOIN DESIGNATIONS ON DESIGNATIONS.DES_ID = CRI_DES_ID
					LEFT JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = DESIGNATIONS.DES_TEX_ID
				WHERE
					ACR_ART_ID = $ART_ID AND
					(DESIGNATIONS.DES_LNG_ID IS NULL OR DESIGNATIONS.DES_LNG_ID = $this->language) AND
					(DESIGNATIONS2.DES_LNG_ID IS NULL OR DESIGNATIONS2.DES_LNG_ID = $this->language)
				;";
		$result = $this->db->query ( $sql );
		return $result->fetchAll ();
	}
	public function getFuelTypes() {
		$sql = "SELECT DES_ID , TEX_TEXT as fuel_name FROM designations 
	LEFT JOIN des_texts
			ON des_texts.TEX_ID = designations.DES_TEX_ID
            WHERE DES_LNG_ID = $this->language AND DES_ID IN(
            5971, #Udenradis
            7668, #LPG
            14638,# Dabasgaze
            14665, #BiFuel
            16039, #Benzins/Elektro
            22924, #Dizelis
            40007, #Benzins
            40008, #Elektro
            40009, #Maisijums
            41639, #Flex-fuel
            48938, #Gaze
            67096, #Dabasgaze
            67185 #Dizelis/Elektro
            );";
		$result = $this->db->query ( $sql );
		$fuelList = array ();
		foreach ( $result->fetchAll () as $fuel ) {
			$fuelList [$fuel ['DES_ID']] = $fuel ['fuel_name'];
		}
		
		return $fuelList;
	}
	public function getModelFuels($MOD_ID) {
		$sql = "SELECT DISTINCT(TYP_KV_FUEL_DES_ID), DES_TEXTS.TEX_TEXT as fuel_name
			FROM `types` 
			INNER JOIN DESIGNATIONS ON DESIGNATIONS.DES_ID = TYP_KV_FUEL_DES_ID
				                       AND DESIGNATIONS.DES_LNG_ID = $this->language
			INNER JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = DESIGNATIONS.DES_TEX_ID
			WHERE TYP_MOD_ID = $MOD_ID";
		$result = $this->db->query ( $sql );
		$fuelList = array ();
		foreach ( $result->fetchAll () as $fuel ) {
			$fuelList [$fuel ['TYP_KV_FUEL_DES_ID']] = $fuel ['fuel_name'];
		}
		return $fuelList;
	}
	public function getModelYears($MOD_ID) {
		$sql = "SELECT SUBSTRING(MIN(TYP_PCON_START), 1, 4) as year_from, SUBSTRING(MAX(TYP_PCON_END), 1, 4) as year_to
				FROM types 
				WHERE TYP_MOD_ID = $MOD_ID";
		$result = $this->db->query ( $sql );
		$years = $result->fetchAll ();
		$y = array ();
		if (empty ( $years [0] ['year_to'] )) {
			$years [0] ['year_to'] = date('Y');
		}
		for($i = $years [0] ['year_from']; $i <= $years [0] ['year_to']; $i ++) {
			$y [$i] = $i;
		}
		return $y;
	}
}