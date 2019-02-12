<?php 
namespace Framework\Modules\UI\Bootstrap;

use Framework\Modules\UI\Container;
use Framework\Modules\UI\Bootstrap\Div;
use Framework\Modules\UI\Bootstrap\Column;

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
class Row extends Div {

    public function __construct() {
        parent::__construct();
        $this->addClass("row");
    }

    /**
     * Add Button component
     */
    public function addColumn($nColWidth, $sID = NULL) {

        $oItem = new Column();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}
		$oItem->setSize($nColWidth);
		$this->add($oItem);
	
		return $oItem;

    }

}

?>