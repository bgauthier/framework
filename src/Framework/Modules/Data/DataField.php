<?php 
namespace Framework\Modules\Data;

define ( 'TYPE_VARCHAR', 1 );
define ( 'TYPE_INT', 2 );
define ( 'TYPE_NUMERIC', 3 );
define ( 'TYPE_DATE', 4 );
define ( 'TYPE_DATETIME', 5 );
define ( 'TYPE_BOOLEAN', 6 );
define ( 'TYPE_BINARY', 7 );
define ( 'TYPE_TIME', 8 );
define ( 'TYPE_TEXT', 9 );
define ( 'TYPE_MEDIUMTEXT', 10 );
define ( 'TYPE_DECIMAL', 11 );
define ( 'TYPE_DOUBLE', 12 );
define ( 'TYPE_LONGTEXT', 13 );
define ( 'TYPE_TIMESTAMP', 14 );


class DataField {

    const TYPE_VARCHAR = 1;
	const TYPE_INT = 2;
	const TYPE_NUMERIC = 3;
	const TYPE_DATE = 4;
	const TYPE_DATETIME = 5;
	const TYPE_BOOLEAN = 6;
	const TYPE_BINARY = 7;
	const TYPE_TIME = 8;
	const TYPE_TEXT = 9;
	const TYPE_MEDIUMTEXT = 10;
	const TYPE_DECIMAL = 11;
	const TYPE_DOUBLE = 12;
    const TYPE_LONGTEXT = 13;
    const TYPE_TIMESTAMP = 14;
	
	const TOTALFUNCTION = "_TOTALFUNCTION";
	
	/**
	 *
	 * The name of the database the field is in
	 * 
	 * @var String
	 * @todo Should we drop this field?
	 */
	protected $_databaseName;
	/**
	 * The name of the table this field is a member of
	 */
	protected $_tableName;
	/**
	 * The name of the field
	 */
	protected $_fieldName;
	/**
	 */
	protected $_dataType = TYPE_VARCHAR;
	/**
	 */
	protected $_dataLength;
	/**
	 */
	protected $_dataPrecision;
	/**
	 */
	protected $_allowNull = TRUE;
	/**
	 */
	protected $_defaultValue;
	/**
	 */
	protected $_value;
	/**
	 */
	protected $_isDirty = false;
	/**
	 */
	protected $_isPrimaryKey = false;
	/**
	 */
	protected $_isForeignKey = false;
	/**
	 */
	protected $_fkTable = '';
	/**
	 */
	protected $_fkField = '';
	protected $_bIsFieldTableColumn = FALSE;
	
	/**
	 *
	 * Indicates if the field is part of the SearchKey field
	 * 
	 * @var boolean
	 */
	protected $_isSearchKeyField = false;
	protected $_isEncryptedField = false;
	
	/**
	 *
	 * @param $dbname string
	 *        	Name of the database the field is located in
	 */
	public function __construct($dbname, $tablename, $fieldname, $datatype = TYPE_VARCHAR, $datalength = NULL, $dataprecision = NULL, $allownull = NULL, $defaultvalue = NULL, $value = NULL, $fktable = NULL, $fkfield = NULL, $isSearchKey = false, $isEncryptedField = false, $bIsFieldColumn = FALSE) {
		$this->_databaseName = $dbname;
		$this->_tableName = $tablename;
		$this->_fieldName = $fieldname;
		$this->_dataType = $datatype;
		$this->_dataLength = $datalength;
		$this->_dataPrecision = $dataprecision;
		$this->_allowNull = $allownull;
		$this->_defaultValue = $defaultvalue;
		$this->_value = $value;
		$this->_fkTable = $fktable;
		$this->_fkField = $fkfield;
		$this->_isSearchKeyField = $isSearchKey;
		$this->_isEncryptedField = $isEncryptedField;
		$this->_bIsFieldTableColumn = $bIsFieldColumn;
	}
	public function getIsFieldTableColumn() {
		return $this->_bIsFieldTableColumn;
	}
	public function setIsFieldTableColumn($bFieldColumn) {
		$this->_bIsFieldTableColumn = $bFieldColumn;
		return $this;
	}
	
	public function getIsEncryptedField() {
		return $this->_isEncryptedField;
	}
	
	public function setIsEncryptedField($isEncryptedField) {
		$this->_isEncryptedField = $isEncryptedField;
		return $this;
	}
	
	/**
	 */
	public function getDatabaseName() {
		return $this->_databaseName;
	}
	/**
	 */
	public function setDatabaseName($dbname) {
		$this->_databaseName = $dbname;
	}
	
	/**
	 */
	public function getTableName() {
		return $this->_tableName;
	}
	/**
	 */
	public function setTableName($tablename) {
		$this->tableName = $tablename;
	}
	
	/**
	 */
	public function getFieldName() {
		return $this->_fieldName;
	}
	/**
	 */
	public function setFieldName($fieldname) {
		$this->_fieldName = $fieldname;
	}
	
	/**
	 */
	public function getDataType() {
		return $this->_dataType;
	}
	/**
	 */
	public function setDataType($dataType) {
		$this->_dataType = $dataType;
	}
	
	/**
	 */
	public function getDataLength() {
		return $this->_dataLength;
	}
	/**
	 */
	public function setDataLength($dataLength) {
		$this->_dataLength = $dataLength;
	}
	
	/**
	 */
	public function getDataPrecision() {
		return $this->_dataPrecision;
	}
	/**
	 */
	public function setDataPrecision($dataPrecision) {
		$this->_dataPrecision = $dataPrecision;
	}
	
	/**
	 */
	public function getAllowNull() {
		return $this->_allowNull;
	}
	/**
	 */
	public function setAllowNull($allowNull) {
		$this->_allowNull = $allowNull;
	}
	
	/**
	 */
	public function getDefaultValue() {
		return $this->_defaultValue;
	}
	/**
	 */
	public function setDefaultValue($defaultValue) {
		$this->_defaultValue = $defaultValue;
	}
	/**
	 */
	public function getSQLValue() {
		switch ($this->getDataType ()) {
			case TYPE_INT :
			case TYPE_NUMERIC :
			case TYPE_DOUBLE :
			case TYPE_DECIMAL :
				return $this->intToNull ( DB::escape ( $this->getValue ( FALSE ) ) );
				break;
			case TYPE_BOOLEAN :
				return $this->intToNull ( DB::escape ( $this->getValue ( FALSE ) ) );
				break;
			case TYPE_DATETIME :
			case TYPE_TIME :
			case TYPE_DATE :
			case TYPE_BINARY :
			case TYPE_VARCHAR :
			case TYPE_MEDIUMTEXT :
			case TYPE_TEXT :
			case TYPE_LONGTEXT :
				$v = $this->getValue ( FALSE );
				if ($v == "") {
					$v = $this->stringToNull ( $v );
					if ($v == "NULL") {
						return $v;
					}
				}
				return '"' . DB::escape($v) . '"';
				break;
			default :
				throw new ExceptionBase("Invalid data type " . $this->getDataType () );
				break;
		}
	}
	protected function intToNull($value) {
		if ($this->getAllowNull ()) {
			if ($value == "" || $value === NULL) {
				return "NULL";
			}
		} else {
			if ($value == "") {
				return "''";
			}
		}
		return $value;
	}
	protected function stringToNull($value) {
		if ($this->getAllowNull ()) {
			if ($value == "") {
				return "NULL";
			}
		}
		return $value;
	}
	/**
	 */
	public function getValue($decrypt = FALSE) {
		if ($this->_isEncryptedField && $decrypt && $this->_value != "") {
			return Encryption::decrypt( $this->_value );
		} else {
			return $this->_value;
		}
	}
	/**
	 */
	public function setValue($value, $encrypt = FALSE) {
		
		if ($value === "select_an_option__") {
			$value = NULL;
		}
		
		if ($this->_isEncryptedField && $encrypt && $value != "") {
			$this->_value = Encryption::encrypt ( $value );
		} else {			
			if ($this->_value !== $value) {
				$this->_value = $value;
				$this->_isDirty = TRUE;
			}
		
		}
		
	}
	
	/**
	 */
	public function getIsPrimaryKey() {
		return $this->_isPrimaryKey;
	}
	/**
	 */
	public function setIsPrimaryKey($isPK) {
		$this->_isPrimaryKey = $isPK;
	}
	
	/**
	 */
	public function getIsForeignKey() {
		return $this->_isForeignKey;
	}
	/**
	 */
	public function setIsForeignKey($isFK) {
		$this->_isForeignKey = $isFK;
	}
	
	/**
	 */
	public function getFKTable() {
		return $this->_fkTable;
	}
	/**
	 */
	public function setFKTable($fkTable) {
		$this->_fkTable = $fkTable;
	}
	
	/**
	 */
	public function getFKField() {
		return $this->_fkField;
	}
	/**
	 */
	public function setFKField($fkField) {
		$this->_fkField = $fkField;
	}
	
	/**
	 */
	public function getIsSearchKeyField() {
		return $this->_isSearchKeyField;
	}
	/**
	 */
	public function setIsSearchKeyField($isSearchKeyField) {
		$this->_isSearchKeyField = $isSearchKeyField;
	}
	
	/**
	 * Returns if field is dirty / modified
	 */
	public function getIsDirty() {
		return $this->_isDirty;
	}
	
	/**
	 * Sets if the field is dirty / modified
	 * 
	 * @param unknown_type $v        	
	 */
	public function setIsDirty($v) {
		$this->_isDirty = $v;
	}

}

?>