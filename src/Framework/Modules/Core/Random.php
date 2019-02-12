<?php
namespace Framework\Modules\Core;

use Framework\Modules\DataType\Objects;

/**
 *
 *	LICENSE: This source file is subject to the LogikSuite Framework license
 * 	that is available at the following file: LICENSE.md
 * 	If you did not receive a copy of the LogikSuite Framework License and
 * 	are unable to obtain it through the web, please send a note to
 * 	support@intelogie.com so we can mail you a copy immediately.
 *
 *	@package 	LogikSuite
 * 	@author 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	INTELOGIE.COM INC.
 * 	@license 	LICENSE.md
 */
class Random {
	
	/**
	 * The seed from which a random number will be generated.
	 * @var int
	 */
	protected static $_nRandomSeed = NULL;

	/**
	 * Provides a unique id string
	 *
	 * @return string
	 */
	public static function uid () {

		return md5( uniqid(NULL, TRUE) );
	
	}
	
	public static function uniqueID($sPrefix = NULL) {
		return str_replace(".", "", uniqid($sPrefix, TRUE));
	}

	/**
	 * Provides a random 32 bit number
	 * if openssl is available, it is cryptographically secure.
	 * Otherwise all available entropy is gathered.
	 *
	 * @return number
	 */
	protected static function _generateRandomNumber () {
		// If openssl is present, use that to generate random.
		if ( function_exists( "openssl_random_pseudo_bytes" ) && FALSE ) {
			$random32bit = (int) ( hexdec( bin2hex( openssl_random_pseudo_bytes( 64 ) ) ) );
		} else {
			if ( self::$_nRandomSeed === null ) {
				$entropy = 1;
				
				if ( function_exists( "posix_getpid" ) )
					$entropy *= posix_getpid();
				
				if ( function_exists( "memory_get_usage" ) )
					$entropy *= memory_get_usage();
				
				list ( $usec, $sec ) = explode( " ", microtime() );
				$usec *= 1000000;
				$entropy *= $usec;
				self::$_nRandomSeed = $entropy;
				
				mt_srand( self::$_nRandomSeed );
			}
			
			$random32bit = mt_rand();
		}
		
		return $random32bit;
	
	}

	/**
	 * Provides a random 32 bit number
	 * 
	 * @param int $min        
	 * @param int $max        
	 * @return number
	 */
	public static function random ( $min = NULL, $max = NULL ) {

		if ( $min !== NULL ) {			
			return self::_generateRandomNumberFromRange( $min, $max );
		} else {
			return self::_generateRandomNumber();
		}
	
	}

	/**
	 * To generate a random number between the specified range.
	 *
	 * @param int $min        
	 * @param int $max        
	 * @return number
	 */
	protected static function _generateRandomNumberFromRange ( $min = 0, $max = NULL ) {

		if ( $max === NULL ) {
			$max = 1 << 31;
		}
		
		// If min is graeter than max reverse parameters
		if ( $min > $max ) {
			return self::_generateRandomNumberFromRange( $max, $min );
		}
		
		if ( $min >= 0 ) {
			return abs( self::random() ) % ( $max - $min ) + $min;
		} else {
			return ( abs( self::random() ) * -1 ) % ( $min - $max ) + $max;
		}
	
	}

	/**
	 * To generate a random string of specified length.
	 *
	 * @param int $Length        
	 * @return String
	 */
	public static function randomString ( $nLength = 32 ) {

		
		$sSeed = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijqlmnopqrtsuvwxyz0123456789";
		$max = strlen($sSeed) - 1;
		$s = "";
		for ($i = 0; $i < $nLength; ++$i) {
			$s .= $sSeed{intval(mt_rand(0.0, $max))};
		}
		return $s;
		
		
	
	}

}

?>