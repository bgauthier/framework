<?php 
namespace Framework\Modules\UI\Bootstrap;

use Framework\Modules\UI\Container;
use Framework\Modules\UI\Bootstrap\Div;

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
class Column extends Div {

    protected $_nSize = NULL;
	protected $_nSizeMedium = NULL;
	protected $_nSizeSmall = NULL;
	protected $_nSizeExtraSmall = NULL;

	protected $_bScrollable = false;

	public function __construct() {

		parent::__construct();
	
	}
	
	public function getSizeMedium() {
		return $this->_nSizeMedium;
	}
	
	public function setSizeMedium($nSize) {
		$this->_nSizeMedium = $nSize;
		return $this;
	}
	
	public function getSizeSmall() {
		return $this->_nSizeSmall;
	}
	
	public function setSizeSmall($nSize) {
		$this->_nSizeSmall = $nSize;
		return $this;
	}
	
	public function getSizeExtraSmall() {
		return $this->_nSizeExtraSmall;
	}
	
	public function setSizeExtraSmall($nSize) {
		$this->_nSizeExtraSmall = $nSize;
		return $this;
	}

	public function setSize($nSize, $nMediumSize = NULL, $nSmallSize = 12, $nExtraSmallSize = 12) {

		$this->_nSize = $nSize;
		if ($nMediumSize === NULL) {
			$this->_nSizeMedium = $nSize;
		} else {
			$this->_nSizeMedium = $nMediumSize;
		}
		
		$this->_nSizeSmall = $nSmallSize;
		$this->_nSizeExtraSmall = $nExtraSmallSize;
		
		return $this;
		
	}

	public function getSize() {

		return $this->_nSize;
	
	}

	public function setScrollable($bScrollable) {

		$this->_bScrollable = $bScrollable;
		return $this;
	
	}

	public function getScrollable() {

		return $this->_bScrollable;
	
    }
    
    protected function _preRender() {

        $this->addClass("col-xs-" . $this->_nSizeExtraSmall);
        $this->addClass("col-sm-" . $this->_nSizeSmall);
        $this->addClass("col-md-" . $this->_nSizeMedium);
        $this->addClass("col-lg-" . $this->_nSize);

    }

}

?>