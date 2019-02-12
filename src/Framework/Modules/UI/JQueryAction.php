<?php
namespace Framework\Modules\UI;


class JQueryAction extends Action {

    /**
     * Code to be executed
     *
     * @var unknown
     */
    protected $_sCode = NULL;

    /**
     * Class constructor
     *
     * @param string $sMessage
     */
    public function __construct($sCode = NULL) {

        $this->_sCode = $sCode;
        $this->_sCode = str_replace(array(
            PHP_EOL,
            "\r\n",
            "\n"
        ), " ", $this->_sCode);


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

        $aData["ActionType"] = "JQueryAction";
        $aData["Properties"] = array();
        $aData["Properties"][] = array(
            "Name" => "Code",
            "Value" => base64_encode($this->_encodeURIComponent($this->_sCode))
        );

        return $aData;

    }

    protected function _encodeURIComponent($str) {
        $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
        return strtr(rawurlencode($str), $revert);
    }

    public function __toString() {

        return $this->_sCode;

    }

}

?>