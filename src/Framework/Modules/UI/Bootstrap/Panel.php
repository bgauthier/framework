<?php 
namespace Framework\Modules\UI\Bootstrap;

use Framework\Modules\UI\Container;
use Framework\Modules\UI\Bootstrap\Div;
use Illuminate\Support\Facades\Log;

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
class Panel extends Div {

    protected $_sHeaderLabel = NULL;

    public function __construct() {
        parent::__construct();
        $this->addClass("card");
    }

    public function setHeaderLabel($sLabel) {
        $this->_sHeaderLabel = $sLabel;
        return $this;
    }

    public function getHeaderLabel() {
        return $this->_sHeaderLabel;        
    }

    public function render() {

        Log::debug("Component render " . $this->getID());

        if ($this->_aRenderedComponent !== NULL) {
            return $this->_aRenderedComponent;
        }

        $this->_preRender();

        $sJs = "";
        $sHtml = "";
        if ($this->_sOpeningTag === NULL) {
            throw new MissingPropertyException("Component OpeningTag");
        }

        // Open component
        $sHtml .= "<" . $this->_sOpeningTag;

        // Add component attributes
        $sHtml .= $this->_renderAttributes();

        // Close opening component tag
        $sHtml .= ">";

        if ($this->getHeaderLabel() != "") {
            $sHtml .= "<div class=\"card-header\">";
            if ($this->getIcon() != "") {
                $sHtml .= "<i class=\"" . $this->getIcon() . "\"></i>&nbsp;";
            }
            $sHtml .= "<strong>" . $this->getHeaderLabel() . "</strong>";
            $sHtml .= "</div>";
        }

        $sJs .= $this->_renderJS();

        $sHtml .= "<div class=\"card-body\">" . PHP_EOL;
        $sHtml .= $this->_getComponentContent();
        foreach($this->_aChildItems as $oComponent) {
            $sHtml .= $oComponent->render()["HTML"];
            $sJs .= $oComponent->render()["JS"];
        }

        $sHtml .= "</div>";

        // Close component
        if ($this->_sClosingTag === NULL) {
            $sHtml .= "/>";
        } else {
            $sHtml .= "</" . $this->_sClosingTag . ">";
        }



        $this->setIsRendered(TRUE);

        $this->_aRenderedComponent = [
            "HTML" => $sHtml,
            "JS" => $sJs
        ];

        return $this->_aRenderedComponent;

    }
    

}

?>