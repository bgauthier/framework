<?php 
namespace Framework\Modules\UI;

class TableColumn {

    protected $_sLabel = NULL;
    protected $_sMapping = NULL;

    public function __construct($options = NULL) {

        if (is_array($options)) {
            foreach($options as $sKey => $sValue) {

                $sSetter = "set" . $sKey;
                if (method_exists($this, $sSetter)) {
                    $this->$sSetter($sValue);
                }

            }
        }

    }

    public function getMapping() {
        return $this->_sMapping;
    }

    public function setMapping($sMapping) {
        $this->_sMapping = $sMapping;
        return $this;
    }

    public function getLabel() {
        return $this->_sLabel;
    }

    public function setLabel($sLabel) {
        $this->_sLabel = $sLabel;
        return $this;
    }

}

?>