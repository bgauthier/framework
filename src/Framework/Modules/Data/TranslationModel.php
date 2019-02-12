<?php 
namespace Framework\Modules\Data;

trait TranslationModel {

    protected $_sTranslationTable = NULL;
    protected $_sTranslationField = NULL;

    public function getTranslationTable() {
        return $this->_sTranslationTable;
    }

    public function setTranslationTable($sTableName) {
        $this->_sTranslationTable = $sTableName;
        return $this;
    }

    public function getTranslationField() {
        return $this->_sTranslationField;
    }

    public function setTranslationField($sFieldName) {
        $this->_sTranslationField = $sFieldName;
        return $this;
    }

}

?>