<?php 
namespace Framework\Modules\Search;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;

class SearchModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->_sModuleBasePath = "Search";
    }

    
    public function initialize() {

        parent::initialize();
       
    }

}

?>