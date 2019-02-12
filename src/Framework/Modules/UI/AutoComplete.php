<?php 
namespace Framework\Modules\UI;

trait AutoComplete {

    protected $_bAutocompleteEnabled = FALSE;
    

    public function getAutocompleteEnabled() {
        return $this->_bAutocompleteEnabled;
    }

    public function setAutocompleteEnabled($bEnabled) {
        $this->_bAutocompleteEnabled = $bEnabled;
        return $this;
    }

    

}

?>