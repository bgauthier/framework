<?php 
namespace Framework\Modules\Logging;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;

class LoggingModule extends Module {


    public function __construct() {
        parent::__construct();
        $this->_sModuleBasePath = "Logging";
    }

    
    public function initialize() {

        parent::initialize();
       
    }

}

?>