<?php 
namespace Framework\Modules\UI;

trait InputGroup {

    protected $_bInputGroupEnabled = FALSE;
    protected $_aPrepend = [];
    protected $_aAppend = [];
    
    /**
     * Remove all prepend components
     */
    public function clearPrepend() {
        $this->_aPrepend = [];
        if (count($this->_aAppend) == 0 && count($this->_aPrepend) == 0) {
            $this->setInputGroupEnabled(FALSE);
        }
    }

    /**
     * Remove all append components
     */
    public function clearAppend() {
        $this->_aAppend = [];
        if (count($this->_aAppend) == 0 && count($this->_aPrepend) == 0) {
            $this->setInputGroupEnabled(FALSE);
        }
    }

    /**
     * Add a prepend component, can be string or component
     */
    public function addPrepend($mPrepend) {
        if (is_string($mPrepend)) {
            $mPrepend = "<span class=\"input-group-text\">" . $mPrepend . "</span>";
        }
        $this->_aPrepend[] = $mPrepend;
        $this->setInputGroupEnabled(TRUE);
        return $this;
    }

    /**
     * Add a append component, can be string or component
     */
    public function addAppend($mAppend) {
        if (is_string($mAppend)) {
            $mAppend = "<span class=\"input-group-text\">" . $mAppend . "</span>";
        }
        $this->_aAppend[] = $mAppend;
        $this->setInputGroupEnabled(TRUE);
        return $this;
    }

    /**
     * Returns array of append components
     */
    public function getAppends() {
        return $this->_aAppend;
    }

    /**
     * Returns array of prepend components
     */
    public function getPrepends() {
        return $this->_aPrepend;
    }

    /**
     * Indicates if component has a bootstrap input group
     */
    public function getInputGroupEnabled() {
        return $this->_bInputGroupEnabled;
    }

    /**
     * Sets if component has a bootstrap input group
     */
    public function setInputGroupEnabled($bEnabled) {
        $this->_bInputGroupEnabled = $bEnabled;
        return $this;
    }

        

}

?>