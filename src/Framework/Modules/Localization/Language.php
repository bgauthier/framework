<?php 
namespace Framework\Modules\Localization;

class Language {

    /**
	 * @var int	Indicates to use current language as value
	 */
	const DEFAULT_LANGUAGE = -1;
	/**
	 * @var int	English language ID
	 */
	const ENGLISH = 1;
	const ENGLISH_CODE = "en";
	/**
	 * @var int	French language ID
	 */
	const FRENCH  = 2;
	const FRENCH_CODE  = "fr";
	
	protected static $_aLanguageInfo = [
		Language::ENGLISH => ["ID" => 1, "Code" => "en", "Name" => "_ENGLISH", "Flag" => "us"],
		Language::FRENCH  => ["ID" => 2, "Code" => "fr", "Name" => "_FRENCH", "Flag" => "fr"]
	];

	public static function getLanguageInfo() {
		return static::$_aLanguageInfo;
	}
	
	public static function getLanguageInfoFromID($nLngID) {
		$aLngInfo = array_get(static::$_aLanguageInfo, $nLngID);
		if (is_array($aLngInfo)) {
			return $aLngInfo;
		}
		return NULL;
	}

	public static function getLanguageInfoFromCode($sLanguageCode) {
		$nLngID = sttaic::getLanguageIDFromCode($sLanguageCode);
		if ($nLngID !== NULL) {
			return static::getLanguageInfoFromID($nLngID);
		}
		return NULL;
	}

	public static function getLanguageCodeFromID($nLngID) {
		$aLngInfo = array_get(static::$_aLanguageInfo, $nLngID);
		if (is_array($aLngInfo)) {
			return $aLngInfo["Code"];
		}
		return NULL;
	}

	public static function getLanguageIDFromCode($sLanguageCode) {
		foreach(static::$_aLanguageInfo as $nLngID => $aLngInfo) {
			if ($aLngInfo["Code"] == $sLanguageCode) {
				return $nLngID;
			}
		}
		return NULL;
	}
    

}

?>