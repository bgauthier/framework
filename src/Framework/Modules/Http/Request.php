<?php

namespace Framework\Modules\Http;

class Request extends \Illuminate\Http\Request {


    /**
     * Sets a value for the request object
     * @param unknown $sKey
     * @param unknown $mValue
     */
    public static function set($key, $mValue) {
        $_REQUEST[$key] = $mValue;
    }


    /**
     * Returns data portion of request parameters
     * Data parameter is passed by forms using ajax calls, it is structured as a key pair array field = value
     * @param unknown $key
     * @param unknown $sDefaultValue
     */
    public static function getData($key, $sDefaultValue = NULL) {

        if (array_key_exists ( $key, $_REQUEST )) {
            return $_REQUEST[$key];
        } elseif (array_key_exists ( "data", $_REQUEST )) {

            if (is_array ( $_REQUEST["data"] )) {
                foreach ( $_REQUEST ["data"] as $k => $oData ) {
                    if (array_get($oData, "Name") == $key) {
                        return array_get($oData, "Value");
                    }
                }

            }
        }

        return NULL;

    }

}

?>