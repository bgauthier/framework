<?php
namespace Framework\Modules\DataType;

use Framework\Modules\Localization\Resources;

class Boolean {

	const YES = TRUE;
	const NO = FALSE;
	const ALL = '_ALL';
	
	/**
	 * Converts a value in a boolean value
	 * 
	 * @param unknown $mValue        	
	 */
	public static function toBool($mValue) {

		if (is_bool($mValue)) {
			return $mValue;
		}
		
		$mValue = strtolower(trim($mValue));
		
		$aTrueValues = array(
							"1",
							"y",
							"o",
							"yes",
							"true",
							"oui",
							"vrai",
							"on",
							"checked",
							true,
                            1
		);
		
		if (in_array($mValue, $aTrueValues, TRUE)) {
			return TRUE;
		} else {
			return FALSE;
		}
	
	}
	
	/**
	 * Converts boolean value to SQL 
	 * @param unknown $bValue
	 */
	public static function toStringSQL($bValue) {
		if (Boolean::toBool($bValue)) {
			return "1";
		} else {
			return "0";
		}
	}
	
	/**
	 * Converts boolean to yes / no string
	 * @param unknown $bValue
	 */
	public static function toStringYesNo($bValue) {
		if (Boolean::toBool($bValue)) {
			return __("_YES");
		} else {
			return __("_NO");
		}
	}
	
	/**
	 * Converts boolean to TRUE/FALSE string
	 * @param unknown $bValue
	 */
	public static function toString($bValue) {
		if (Boolean::toBool($bValue)) {
			return "TRUE";
		} else {
			return "FALSE";
		}
	}
	
	/**
	 * Converts boolean to true/false Javascript string
	 * @param unknown $bValue
	 */
	public static function toStringJS($bValue) {
		if (Boolean::toBool($bValue)) {
			return "true";
		} else {
			return "false";
		}
	}

}

?>