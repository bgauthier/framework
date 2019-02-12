<?php 
namespace Framework\Modules\Data;

class Table {

    protected $_sTablePrefix = "tbl";
    protected $_sTableName = NULL;
    
    public function __construct() {
		
	}

    public function setTablePrefix($sPrefix) {
        $this->_sTablePrefix = $sPrefix;
    }

    public function getTablePrefix() {
        return $this->_sTablePrefix;    
    }

    public function setTableName($sTableName) {
        $this->_sTableName = $sTableName;
    }

    public function getTableName($bWithPrefix = TRUE) {
        return   ($bWithPrefix ? $this->getTablePrefix() : "") . $this->_sTableName;  
    }

    public function buildTableName($sTable, $sPrefix = NULL) {
        if ($sPrefix === NULL) {
            $sPrefix = $this->getTablePrefix();
        }
        return   $sPrefix . $sTable;  
    }

}

?>