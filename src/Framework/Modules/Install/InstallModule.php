<?php 
namespace Framework\Modules\Install;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;

class InstallModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->_sModuleBasePath = "Install";
    }


    public function initialize() {

        parent::initialize();
       
    }

}

?>