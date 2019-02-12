<?php 
namespace Framework\Modules\Localization;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;

class LocalizationModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->_sModuleBasePath = "Localization";
    }


    public function initialize() {

        parent::initialize();
       
    }

}

?>