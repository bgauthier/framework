<?php 
namespace Framework\Modules\UI;

use Framework\Modules\UI\Container;

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
class DataContainer extends Container {

    protected $_sDataSetClassName = NULL;
    protected $_oDataSetInstance = NULL;
    protected $_sMappingObject = NULL;
    protected $_sMappingField = NULL;

    public function __construct() {
        parent::__construct();
    }

    public function getDataSet() {
        return $this->_oDataSetInstance;
    }

    public function setDataSet($oDataSet) {
        $this->_oDataSetInstance = $oDataSet;
        return $this;
    }


    public function getDataSetClassName() {
        return $this->_sDataSetClassName;
    }

    public function setDataSetClassName($sDataSetClassName) {
        $this->_sDataSetClassName = $sDataSetClassName;
        return $this;
    }

    public function getMappingObject() {
        return $this->_sMappingObject;
    }

    public function getMappingField() {
        return $this->_sMappingField;
    }

}

?>