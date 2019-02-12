<?php
namespace Framework\Modules\UI;


/**
 *
 *	LICENSE: This source file is subject to the LogikBuild license
 * 	that is available at the following URI:
 * 	src/Framework/LICENSE.md.  If you did not receive a copy of
 * 	the LogikBuild License and are unable to obtain it through the web, please
 * 	send a note to support@intelogie.com so we can mail you a copy immediately.
 *
 *	@package 	LogikBuild
 * 	@author 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	INTELOGIE.COM INC.
 * 	@license 	src/Framework/LICENSE.md
 */
class AlertAction extends Action {

    /**
     * Message to be displayed
     *
     * @var unknown
     */
    protected $_sMessage = NULL;

    /**
     * Class constructor
     *
     * @param string $sMessage
     * @return \Framework\Modules\UI\AlertAction
     */
    public function __construct($sMessage = NULL) {

        if (substr($sMessage, 0, 1) == "_") {
            $this->_sMessage = __($sMessage);
        } else {
            $this->_sMessage = $sMessage;
        }
        return $this;

    }

    /**
     * Converts object to array
     * (non-PHPdoc)
     *
     * @see \Framework\Modules\UI\JSAction::toArray()
     */
    public function toArray() {

        $aData = array();

        $aData["ActionType"] = "AlertAction";
        $aData["Properties"] = array();
        $aData["Properties"][] = array(
            "Name" => "Message",
            "Value" => $this->_sMessage
        );

        return $aData;

    }

}

?>