<?php 
namespace Framework\Modules\Settings;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;

class SettingsModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->_sModuleBasePath = "Settings";
    }


    public function initialize() {

        parent::initialize();
       
    }

}

?>