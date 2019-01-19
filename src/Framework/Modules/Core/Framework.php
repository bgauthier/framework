<?php
namespace Framework\Modules\Core;


class Framework {

    protected static $_bIsInitialized = FALSE;

    public static function getIsInitialized() {
        return static::$_bIsInitialized;
    }
    

    public static function initialize() {

        if (static::$_bIsInitialized) {
            return FALSE;
        }

        


        static::$_bIsInitialized = TRUE;
        return TRUE;
    }

}

?>