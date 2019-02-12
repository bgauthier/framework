<?php 
namespace Framework\Modules\UI\Bootstrap;

use Framework\Modules\UI\Container;
use Framework\Modules\UI\DataComponent;

/**
 *
 *	LICENSE: This source file is subject to the LogikSuite Framework license
 * 	that is available at the following file: LICENSE.md
 * 	If you did not receive a copy of the LogikSuite Framework License and
 * 	are unable to obtain it through the web, please send a note to
 * 	support@intelogie.com so we can mail you a copy immediately.
 *
 *	@package 	LogikSuite
 * 	@author 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	INTELOGIE.COM INC.
 * 	@license 	LICENSE.md
 */
class CheckBox extends DataComponent {

    public function __construct() {
        parent::__construct();
        $this->_sOpeningTag = "input";
        $this->addClass("form-control");
        $this->setAttribute("type", "checkbox");
        $this->setComponentPrepend("<div class=\"form-group\">");
        $this->setComponentAppend("</div>");
        $this->setIsSubmitComponent(TRUE);
        
    }

}

?>