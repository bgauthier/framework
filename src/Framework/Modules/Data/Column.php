<?php
namespace Framework\Modules\Data;

class Column {

    protected $_sMapping = NULL;
    protected $_sLabel = NULL;
    protected $_sHref = NULL;



    public function __construct($fields = NULL)
    {

        foreach($fields as $sKey => $mValue) {
            $sSetter = "set" . $sKey;
            if (method_exists($this, $sSetter)) {
                $this->$sSetter($mValue);
            }
        }

    }

    /**
     * @return null
     */
    public function getHref()
    {
        return $this->_sHref;
    }

    /**
     * @param null $sHref
     */
    public function setHref($sHref)
    {
        $this->_sHref = $sHref;
    }

    /**
     * @return null
     */
    public function getMapping()
    {
        return $this->_sMapping;
    }

    /**
     * @param null $sMapping
     */
    public function setMapping($sMapping)
    {
        $this->_sMapping = $sMapping;
    }

    /**
     * @return null
     */
    public function getLabel()
    {
        return $this->_sLabel;
    }

    /**
     * @param null $sLabel
     */
    public function setLabel($sLabel)
    {
        $this->_sLabel = $sLabel;
    }



}

?>