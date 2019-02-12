<?php 
namespace Framework\Modules\UI\Bootstrap;

use Framework\Modules\UI\Container;
use Framework\Modules\UI\InputGroup;
use Framework\Modules\UI\Spellcheck;
use Framework\Modules\UI\Localizable;
use Framework\Modules\UI\AutoComplete;
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
class TextArea extends DataComponent {

    use Spellcheck, InputGroup, Localizable, AutoComplete;

    protected $_bResizeEnabled = FALSE;

    public function __construct() {
        parent::__construct();
        $this->setJSClassName("TextArea");
        $this->_sOpeningTag = "textarea";
        $this->_sClosingTag = "textarea";
        $this->addClass("form-control");
        $this->setAttribute("type", "text");
        $this->setComponentPrepend("<div class=\"form-group\">");
        $this->setComponentAppend("</div>");
        $this->setIsSubmitComponent(TRUE);
        $this->setHeight("90");
        
    }

    public function getResizeEnabled() {
        return $this->_bResizeEnabled;    
    }

    public function setResizeEnabled($bEnabled) {
        $this->_bResizeEnabled = $bEnabled;
        return $this;
    }

    public function _getComponentContent()
    {
        return $this->getValue();
    }

    protected function _preRender() {
        parent::_preRender();
        if (!$this->getResizeEnabled()) {
            $this->addStyle("resize: none;");
        }
        if (!$this->getAutocompleteEnabled()) {
            $this->setAttribute("autocomplete", "off");
        }
    }

    

}

?>