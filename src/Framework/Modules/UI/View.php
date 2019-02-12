<?php 
namespace Framework\Modules\UI;

use Framework\Modules\UI\Theme;
use Framework\Modules\UI\ViewContainer;

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
class View {

    /**
     * Name of view
     */
    public $VIEW_NAME = NULL;
    /**
     * Namespace of view
     */
    public $VIEW_NAMESPACE = NULL;
    /**
     * Description of view
     */
    public $VIEW_DESCRIPTION = NULL;

    /**
     * View prefix, will be prefixed to all components
     */
    protected $_sViewPrefix = NULL;
    /**
     * Array of view controls
     */
    protected $_aComponents = [];
    /**
     * Main container object 
     */
    protected $_oViewContainer = NULL;

    protected $_aRenderedView = NULL;

    protected $_aDataObjects = [];

    protected $_aViewRoutes = [];


    /**
     * 
     */
    public function __construct() {

        $this->_initializeView();

    }

    public function setSaveRoute($sRoute) {
        $this->_aViewRoutes["saveRoute"] = $sRoute;
        return $this;
    }

    public function setDeleteRoute($sRoute) {
        $this->_aViewRoutes["deleteRoute"] = $sRoute;
        return $this;
    }

    public function setNewRoute($sRoute) {
        $this->_aViewRoutes["newRoute"] = $sRoute;
        return $this;
    }

    /**
     * Build view components, must be redefined in view base class
     */
    protected function _initializeView() {

        $this->_oViewContainer = new ViewContainer();
        $this->_oViewContainer->setID("viewContainer");
		$this->_oViewContainer->setView($this);

    }

    public function add(Component $oComponent) {
        $oComponent->setView($this);
        $this->addComponent($oComponent);
        $this->getViewContainer()->add($oComponent); 
        return $this;
    }

    public function addComponent($oComponent) {
        $this->_aComponents[$oComponent->getID()] = $oComponent;
    }

    /**
     * Returns view main container
     */
    public function getViewContainer() {
		return $this->_oViewContainer;
    }
    
    /**
     * Sets view prefix and sets component prefix for all controls
     */
    public function setViewPrefix($sPrefix) {
		$this->_sViewPrefix = $sPrefix;
		
		foreach($this->_aComponents as $oControl) {
			$oControl->setComponentPrefix($sPrefix);
		}
		

		return $this;
	}
    
    /**
     * Returns view prefix
     */
	public function getViewPrefix() {
		return $this->_sViewPrefix;
    }
    
    /**
     * Returns view controls
     */
    public function getControls() {

		return $this->_aComponents;
	
    }
    
    /**
     * Enables or disable a view and all its components
     */
    public function setEnabled($bEnabled) {

		foreach ($this->_aComponents as $k => $oControl) {
			$oControl->setEnabled($bEnabled);
		}

		return $this;
    }
    
    public function render() {

        if ($this->_aRenderedView === NULL) {
            $this->_aRenderedView = $this->getViewContainer()->render();

            /**
             * Add page routes
             */
            foreach($this->_aViewRoutes as $sRouteName => $sRoute) {
                $this->_aRenderedView["JS"] .= "Page." . $sRouteName . " = '" . $sRoute . "';" . PHP_EOL;
            }


        }

        return $this->_aRenderedView;

    }
  
    public function getView() {
        
        Theme::appendToken(Theme::THEME_TOKEN_PAGE_CONTENT, $this);
        Theme::appendToken(Theme::THEME_TOKEN_SCRIPT_ONREADY_BOTTOM, $this);

        /**
         * @todo find a way to allow appending script before we get view
         */
        Theme::appendToken(Theme::THEME_TOKEN_SCRIPT_ONREADY_BOTTOM, "Page.onPageLoad();" . PHP_EOL);

        return Theme::getView();

    }

    public function addDataObject($oObject, $sKey) {

        $this->_aDataObjects[$sKey] = $oObject;
        $this->applyDataObjects();
        return $this;
    }

    public function applyDataObjects() {

        foreach($this->getControls() as $oControl) {

            if (method_exists($oControl, "getMappingObject")) {
                if ($oControl->getMappingObject() != "" && $oControl->getMappingField() != "") {
                    $oControl->setValue(mapping_get($this->_aDataObjects, $oControl->getMappingObject() . "." . $oControl->getMappingField()));
                }
            }
        }

    }


}

?>