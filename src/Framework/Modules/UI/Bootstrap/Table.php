<?php 
namespace Framework\Modules\UI\Bootstrap;

use Framework\Modules\UI\Container;
use Framework\Modules\UI\TableColumn;
use Framework\Modules\UI\DataComponent;
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
class Table extends DataComponent {

    protected $_aColumns = [];

    /**
     * Pre contructor
     */
    public function __construct() {
        parent::__construct();
        $this->_sOpeningTag = "table";
        $this->_sClosingTag = "table";
        $this->addClass("table");
        $this->setIsSubmitComponent(TRUE);
    }

    public function addTableColumn(TableColumn $oColumn) {
        $this->_aColumns[] = $oColumn;
        return $oColumn;
    }

    protected function _preRender() {
        parent::_preRender();

        if ($this->getDataSet() != NULL) {
            $this->_aColumns = $this->getDataSet()->getColumns();
        }

    }

    public function render() {

        Log::debug("Component render " . $this->getComponentID());

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

        $sHtml .= "<thead>";
        $sHtml .= "<tr>";
        foreach($this->_aColumns as $oColumn) {
            $sHtml .= "<th>" . $oColumn->getLabel() . "</th>";
        }
        $sHtml .= "</tr>";
        $sHtml .= "</thead>";
        $sHtml .= "<tbody>";

        if ($this->getDataSet() != NULL) {
            foreach ($this->getDataSet()->getItems() as $oRow) {
                $sHtml .= "<tr>";
                foreach ($this->_aColumns as $oColumn) {
                    $sHtml .= "<td>";
                    if ($oColumn->getHref() != "") {
                        $sHtml .= "<a href=\"" . $this->_replaceKeys($oColumn->getHref(), $oRow) . "\">";
                    }
                    if (is_array($oRow)) {
                        $oRow = collect($oRow);
                    }
                    $sHtml .= $oRow->get($oColumn->getMapping());

                    if ($oColumn->getHref() != "") {
                        $sHtml .= "</a>";
                    }
                    $sHtml .= "</td>";
                }
                $sHtml .= "</tr>";
            }
        }

        $sHtml .= "</tbody>";

        
       
        // Close component
        if ($this->_sClosingTag === NULL) {
            $sHtml .= "/>";
        } else {
            $sHtml .= "</" . $this->_sClosingTag . ">";
        }

        $sJs .= $this->_renderJS();
        $this->setIsRendered(TRUE);

        $this->_aRenderedComponent = [
            "HTML" => $sHtml,
            "JS" => $sJs
        ];

        return $this->_aRenderedComponent;



    }

    protected function _replaceKeys($s, $aKeys) {
        foreach($aKeys as $sKey => $sValue) {
            if (!is_array($sValue)) {
                $s = str_replace("{" . $sKey . "}", $sValue, $s);
            }
        }
        return $s;
    }



}

?>