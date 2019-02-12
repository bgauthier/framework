<?php 
namespace Framework\Modules\Data;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;

class DataModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->_sModuleBasePath = "Data";
    }


    public function initialize() {

        parent::initialize();
     

    }

}

?>