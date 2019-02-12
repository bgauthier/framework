<?php 
namespace Framework\Modules\Reporting;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;

class ReportingModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->_sModuleBasePath = "Reporting";
    }

    
    public function initialize() {

        parent::initialize();
       
    }

}

?>