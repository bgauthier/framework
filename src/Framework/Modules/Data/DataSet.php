<?php 
namespace Framework\Modules\Data;


class DataSet extends Model {

    use Query;
    
    protected $_aParameters = [];
    protected $_aColumns = [];
    protected $_bIsQueryDataSet = TRUE;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return bool
     */
    public function getIsQueryDataSet()
    {
        return $this->_bIsQueryDataSet;
    }

    /**
     * @param bool $bIsQueryDataSet
     */
    public function setIsQueryDataSet($bIsQueryDataSet)
    {
        $this->_bIsQueryDataSet = $bIsQueryDataSet;
    }


    public function setParameter($sName, $mValue) {
        $this->_aParameters[$sName] = $mValue;
        return $this;
    }

    public function getParameter($sName) {
        return array_get($this->_aParameters, $sName);
    }

    public function getQuery() {
        
    }

    public function getColumns() {
        return $this->_aColumns;
    }

    public function addColumn(Column $oColumn) {
        $this->_aColumns[] = $oColumn;
        return $oColumn;
    }

    public function getItems() {

    }

}

?>