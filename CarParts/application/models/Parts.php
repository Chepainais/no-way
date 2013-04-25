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
					`manufacturers`
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
       		 models
      	INNER JOIN country_designations ON CDS_ID = MOD_CDS_ID
        INNER JOIN des_texts ON TEX_ID = CDS_TEX_ID
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
				des_texts7.TEX_TEXT AS MOD_CDS_TEXT,
				des_texts.TEX_TEXT AS TYP_CDS_TEXT,
				TYP_PCON_START,
				TYP_PCON_END,
				TYP_CCM,
				TYP_KW_FROM,
				TYP_KW_UPTO,
				TYP_HP_FROM,
				TYP_HP_UPTO,
				TYP_CYLINDERS,
				engines.ENG_CODE,
				des_texts2.TEX_TEXT AS TYP_ENGINE_DES_TEXT,
				des_texts3.TEX_TEXT AS TYP_FUEL_DES_TEXT,
				IFNULL(des_texts4.TEX_TEXT, des_texts5.TEX_TEXT) AS TYP_BODY_DES_TEXT,
				des_texts6.TEX_TEXT AS TYP_AXLE_DES_TEXT,
				TYP_MAX_WEIGHT
			FROM
				types
				INNER JOIN models ON MOD_ID = TYP_MOD_ID
				INNER JOIN manufacturers ON MFA_ID = MOD_MFA_ID
				INNER JOIN country_designations AS country_designations2 ON country_designations2.CDS_ID = MOD_CDS_ID AND country_designations2.CDS_LNG_ID = $this->language
				INNER JOIN des_texts AS des_texts7 ON des_texts7.TEX_ID = country_designations2.CDS_TEX_ID
				INNER JOIN country_designations ON country_designations.CDS_ID = TYP_CDS_ID AND country_designations.CDS_LNG_ID = $this->language
				INNER JOIN des_texts ON des_texts.TEX_ID = country_designations.CDS_TEX_ID
				 LEFT JOIN designations ON designations.DES_ID = TYP_KV_ENGINE_DES_ID AND designations.DES_LNG_ID = $this->language
				 LEFT JOIN des_texts AS des_texts2 ON des_texts2.TEX_ID = designations.DES_TEX_ID
				 LEFT JOIN designations AS designations2 ON designations2.DES_ID = TYP_KV_FUEL_DES_ID AND designations2.DES_LNG_ID = $this->language
				 LEFT JOIN des_texts AS des_texts3 ON des_texts3.TEX_ID = designations2.DES_TEX_ID
				 LEFT JOIN link_typ_eng ON LTE_TYP_ID = TYP_ID
				 LEFT JOIN engines ON ENG_ID = LTE_ENG_ID
				 LEFT JOIN designations AS designations3 ON designations3.DES_ID = TYP_KV_BODY_DES_ID AND designations3.DES_LNG_ID = $this->language
				 LEFT JOIN des_texts AS des_texts4 ON des_texts4.TEX_ID = designations3.DES_TEX_ID
				 LEFT JOIN designations AS designations4 ON designations4.DES_ID = TYP_KV_MODEL_DES_ID AND designations4.DES_LNG_ID = $this->language
				 LEFT JOIN des_texts AS des_texts5 ON des_texts5.TEX_ID = designations4.DES_TEX_ID
				 LEFT JOIN designations AS designations5 ON designations5.DES_ID = TYP_KV_AXLE_DES_ID AND designations5.DES_LNG_ID = $this->language
				 LEFT JOIN des_texts AS des_texts6 ON des_texts6.TEX_ID = designations5.DES_TEX_ID
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
					        	search_tree AS search_tree2
							WHERE
								search_tree2.STR_ID_PARENT <=> search_tree.STR_ID
							LIMIT
								1
						), 1, 0) AS DESCENDANTS
				FROM
					           search_tree
					INNER JOIN designations ON DES_ID = STR_DES_ID
					INNER JOIN des_texts ON TEX_ID = DES_TEX_ID
				WHERE
					STR_ID_PARENT <=> $str_id AND
					DES_LNG_ID = $this->language AND
					EXISTS (
						SELECT * FROM link_ga_str
							INNER JOIN link_la_typ ON LAT_TYP_ID = $typ_id AND
							                          LAT_GA_ID = LGS_GA_ID
							INNER JOIN link_art ON LA_ID = LAT_LA_ID
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
					des_texts.TEX_TEXT AS ART_COMPLETE_DES_TEXT,
					des_texts2.TEX_TEXT AS ART_DES_TEXT,
					des_texts3.TEX_TEXT AS ART_STATUS_TEXT
				FROM
					           articles
					INNER JOIN designations ON designations.DES_ID = ART_COMPLETE_DES_ID
					                       AND designations.DES_LNG_ID = $this->language
					INNER JOIN des_texts ON des_texts.TEX_ID = designations.DES_TEX_ID
					 LEFT JOIN designations AS designations2 ON designations2.DES_ID = ART_DES_ID
					                                        AND designations2.DES_LNG_ID = $this->language
					 LEFT JOIN des_texts AS des_texts2 ON des_texts2.TEX_ID = designations2.DES_TEX_ID
					INNER JOIN suppliers ON SUP_ID = ART_SUP_ID
					INNER JOIN art_country_specifics ON ACS_ART_ID = ART_ID
					INNER JOIN designations AS designations3 ON designations3.DES_ID = ACS_KV_STATUS_DES_ID
					                                        AND designations3.DES_LNG_ID = $this->language
					INNER JOIN des_texts AS des_texts3 ON des_texts3.TEX_ID = designations3.DES_TEX_ID
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
					           link_ga_str
					INNER JOIN link_la_typ ON LAT_TYP_ID = $typ_id AND
					                          LAT_GA_ID = LGS_GA_ID
					INNER JOIN link_art ON LA_ID = LAT_LA_ID
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
		$sql = "SELECT MOD_PC, MOD_CV FROM models WHERE MOD_ID = $MOD_ID LIMIT 1";
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
					           link_gra_art
					INNER JOIN graphics ON GRA_ID = LGA_GRA_ID
					INNER JOIN doc_types ON DOC_TYPE = GRA_DOC_TYPE
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
					des_texts.TEX_TEXT AS PRICE_UNIT_DES_TEXT,
					des_texts2.TEX_TEXT AS QUANTITY_UNIT_DES_TEXT,
					PRI_CURRENCY_CODE
				FROM
					           prices
					INNER JOIN designations ON designations.DES_ID = PRI_KV_PRICE_UNIT_DES_ID
					INNER JOIN des_texts ON des_texts.TEX_ID = designations.DES_TEX_ID
					INNER JOIN designations AS designations2 ON designations2.DES_ID = PRI_KV_QUANTITY_UNIT_DES_ID
					INNER JOIN des_texts AS des_texts2 ON des_texts2.TEX_ID = designations2.DES_TEX_ID
				WHERE
					PRI_ART_ID = $ART_ID AND
					designations.DES_LNG_ID = $this->language AND
					designations2.DES_LNG_ID = $this->language
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
					           article_info
					INNER JOIN text_modules ON TMO_ID = AIN_TMO_ID
					INNER JOIN text_module_texts ON TMT_ID = TMO_TMT_ID
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
					des_texts.TEX_TEXT AS CRITERIA_DES_TEXT,
					IFNULL(des_texts2.TEX_TEXT, ACR_VALUE) AS CRITERIA_VALUE_TEXT
				FROM
					          article_criteria
					LEFT JOIN designations AS designations2 ON designations2.DES_ID = ACR_KV_DES_ID
					LEFT JOIN des_texts AS des_texts2 ON des_texts2.TEX_ID = designations2.DES_TEX_ID
					LEFT JOIN criteria ON CRI_ID = ACR_CRI_ID
					LEFT JOIN designations ON designations.DES_ID = CRI_DES_ID
					LEFT JOIN des_texts ON des_texts.TEX_ID = designations.DES_TEX_ID
				WHERE
					ACR_ART_ID = $ART_ID AND
					(designations.DES_LNG_ID IS NULL OR designations.DES_LNG_ID = $this->language) AND
					(designations2.DES_LNG_ID IS NULL OR designations2.DES_LNG_ID = $this->language)
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
		$sql = "SELECT DISTINCT(TYP_KV_FUEL_DES_ID), des_texts.TEX_TEXT as fuel_name
			FROM `types` 
			INNER JOIN designations ON designations.DES_ID = TYP_KV_FUEL_DES_ID
				                       AND designations.DES_LNG_ID = $this->language
			INNER JOIN des_texts ON des_texts.TEX_ID = designations.DES_TEX_ID
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