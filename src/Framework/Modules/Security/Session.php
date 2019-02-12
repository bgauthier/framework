<?php 
namespace Framework\Modules\Security;

use Framework\Modules\Localization\Language;

class Session {

    public static function getLanguageCode() {
        return app()->getLocale();
    }

    public static function getLanguageID() {
        return Language::getLanguageIDFromCode(app()->getLocale());
    }

}

?>