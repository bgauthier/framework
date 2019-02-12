<?php 
namespace Framework\Modules\UI;

trait Spellcheck {

    protected $_bSpellcheckEnabled = FALSE;
    

    public function getSpellcheckEnabled() {
        return $this->_bSpellcheckEnabled;
    }

    public function setSpellcheckEnabled($bEnabled) {
        $this->_bSpellcheckEnabled = $bEnabled;
        return $this;
    }

    

}

?>