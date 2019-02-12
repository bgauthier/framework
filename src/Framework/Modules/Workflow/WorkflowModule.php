<?php 
namespace Framework\Modules\Workflow;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;
use Illuminate\Support\Facades\Log;

class WorkflowModule extends Module {

    public function __constructor() {
        parent::__constructor();
        $this->_sModuleBasePath = "Workflow";
    }

    public function initialize() {
        
        parent::initialize();
       
    }

}

?>