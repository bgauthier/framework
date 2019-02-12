<?php 
namespace Framework\Modules\UI;

use Framework\Modules\UI\Bootstrap\Div;
use Framework\Modules\UI\Bootstrap\Row;

class ViewContainer extends Div {

    public function __construct() {
        parent::__construct();        
        $this->addClass("viewContainer");
    }


}

?>