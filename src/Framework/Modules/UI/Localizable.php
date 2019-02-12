<?php 
namespace Framework\Modules\UI;

trait Localizable {

    /**
     * Indicates if the component support multi lingual 
     */
    protected $_bIsLocalized = FALSE;
    

    public function getIsLocalized() {
        return $this->_bIsLocalized;
    }

    public function setIsLocalized($bIsLocalized) {
        $this->_bIsLocalized = $bIsLocalized;
        return $this;
    }

    

}

?>