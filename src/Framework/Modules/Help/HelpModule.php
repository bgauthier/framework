<?php 
namespace Framework\Modules\Help;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;

class HelpModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->_sModuleBasePath = "Help";
    }


    public function initialize() {

        parent::initialize();
       
    }

}

?>