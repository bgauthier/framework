<?php 
namespace Framework\Modules\Exception;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;

class ExceptionModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->_sModuleBasePath = "Exception";
    }


    public function initialize() {

        parent::initialize();
       
    }

}

?>