<?php 
namespace Framework\Modules\Data;

use Illuminate\Support\Facades\DB;
use Framework\Modules\DataType\Boolean;
use Framework\Modules\DataType\Strings;
use Illuminate\Contracts\View\Factory as ViewFactory;


class Model  {

    const MODEL_MODULE_UNDEFINED = "N/A";

    const MODEL_TYPE_DATATABLE = "_DATATABLE";
    const MODEL_TYPE_VIEW = "_DATAVIEW";
    const MODEL_TYPE_ENUM = "_DATAENUM";

    const MODEL_CONSTRAINT_UNIQUE = "_UNIQUE";
	const MODEL_CONSTRAINT_NOTNULL = "_NOTNULL";
	const MODEL_CONSTRAINT_MINLENGTH = "_MINLENGTH";
	const MODEL_CONSTRAINT_MAXLENGTH = "_MAXLENGTH";
	const MODEL_CONSTRAINT_MINVALUE = "_MINVALUE";
	const MODEL_CONSTRAINT_MAXVALUE = "_MAXVALUE";
    const MODEL_CONSTRAINT_ISNUMERIC = "_ISNUMERIC";
    
    const MODEL_RELATIONSHIP_ONETOMANY = "_ONETOMANY";
	const MODEL_RELATIONSHIP_TRANSLATIONTABLE = "_TRANSLATIONTABLE";
	const MODEL_RELATIONSHIP_ONETOONE = "_ONETOONE";
	const MODEL_RELATIONSHIP_MANYTOMANY = "_MANYTOMANY";
	const MODEL_RELATIONSHIP_OUTBOUND = "_OUTBOUND";
	const MODEL_RELATIONSHIP_INBOUND = "_INBOUND";
    const MODEL_RELATIONSHIP_HASRELATIONSHIP = "_HASRELATIONSHIP";
    
    const MODEL_ACCESS_READONLY = "_READONLY";
	const MODEL_ACCESS_READWRITE = "_READWRITE";

    public $MODEL_NAME = NULL;
	public $MODEL_NAMESPACE = NULL;
	public $MODEL_TABLE = NULL;	
	public $MODEL_DESCRIPTION = NULL;
	public $MODEL_INCLUDEDATA = FALSE;
	public $MODEL_MODULE = Model::MODEL_MODULE_UNDEFINED;
	public $MODEL_TYPE = Model::MODEL_TYPE_DATATABLE;
	public $MODEL_COLUMNS = [];
	public $MODEL_CONSTRAINTS = [];
    public $MODEL_RELATIONSHIPS = [];
    
    protected $_aTokens = NULL;
    protected $_aFields = [];

    public function __construct()
    {
    }

    public function saveModel() {

        if (trim($this->MODEL_NAME) == "") {
            throw new CrashAndBurnException("Missing model name");
        }

        if (trim($this->MODEL_NAMESPACE) == "") {
            throw new CrashAndBurnException("Missing model namespace");
        }

        $this->_initTokens();

        $this->_addUse("Framework\\Modules\\Data\\Model");
		$this->_addUse("Framework\\Modules\\Data\\DataField");

        $this->_addVariable("public \$MODEL_NAME = \"" . $this->MODEL_NAME . "\"", "The name of the model");
		$this->_addVariable("public \$MODEL_NAMESPACE = \"" . addslashes($this->MODEL_NAMESPACE) . "\"", "The namespace for this model");
		$this->_addVariable("public \$MODEL_TABLE = \"" . $this->MODEL_TABLE . "\"", "The related database table for this model");
		$this->_addVariable("public \$MODEL_DESCRIPTION = \"" . addslashes($this->MODEL_DESCRIPTION) . "\"", "Description of model");
		

        $this->_scanTable();

        $this->_addVariable("public \$MODEL_COLUMNS = [
" . $this->_getToken("COLUMNS") . "
    ]", "List of column information");


        /**
         * Prepare tokens for merge
         */
        $this->_aTokens["USE"] = implode("", $this->_aTokens["USE"]);

        $this->_buildFiles();
        

    }

    protected function _scanTable() {
        
        foreach($this->MODEL_COLUMNS as $k => $aColumn) {
            $this->MODEL_COLUMNS[$k]["_ISDEPRECADED"] = TRUE;     
            $this->MODEL_COLUMNS[$k]["_ISRENDERED"] = FALSE;
        }

        $aTableFields = DB::select("DESC " . $this->MODEL_TABLE);

        foreach($aTableFields as $aField) {
            $k = $aField->Field;
            $this->MODEL_COLUMNS[$k]["_ISDEPRECADED"] = FALSE;    
            $this->MODEL_COLUMNS[$k]["Name"] = $aField->Field;  
            $this->MODEL_COLUMNS[$k]["IsVisible"] = TRUE;  
            $this->MODEL_COLUMNS[$k]["IsMultilingual"] = FALSE;  
            $this->MODEL_COLUMNS[$k]["SequenceName"] = "";  
            $this->MODEL_COLUMNS[$k]["Label"] = "";  
            $this->MODEL_COLUMNS[$k]["Type"] = $aField->Type; 
            $this->MODEL_COLUMNS[$k]["Help"] = []; 
            foreach(config('FrameworkConfig.availableLanguages') as $sLngCode) {                
                $this->MODEL_COLUMNS[$k]["Help"][$sLngCode] = "";
            } 

            $bEncrypted = FALSE;
            $this->_aTokens["DATAFIELDS"] .= "          \$this->addField(new DataField(NULL, \"" . $this->_aTokens["TABLENAME"] . "\", \"" . $aField->Field . "\", " . $this->_getDataType($aField->Type) . ", " . $this->_getDataLength($aField->Type) . ", 0, " . ($aField->Null == "YES" ? "TRUE" : "FALSE") . ",\"\", " . ($aField->Null == "YES" ? "NULL" : "\"\"") . ",NULL,NULL,FALSE," . Boolean::toString($bEncrypted) . "));" . PHP_EOL;

        }


        /**
         * Render column code
         */
        foreach($this->MODEL_COLUMNS as $k => $aColumn) {
            if (!$this->MODEL_COLUMNS[$k]["_ISDEPRECADED"]) {

                $this->_buildGettersAndSetters($this->MODEL_COLUMNS[$k]);

                $this->_aTokens["COLUMNS"] .= "         \"" . $k . "\" => [" . PHP_EOL;
                foreach($aColumn as $sPropName => $mPropertyValue) {

                    // Ignore properties that start with underscrore
                    if (!starts_with($sPropName, "_")) {

                        if (is_array($mPropertyValue)) {
                            // Array                        
                            $sArrayCode = var_export($mPropertyValue, TRUE);                        
                            $sArrayCode = str_replace("array (", "[", $sArrayCode);                        
                            $sArrayCode = str_replace(")", "                ]", $sArrayCode);
                            $aLines = explode(PHP_EOL, $sArrayCode);
                            for($i=1; $i < count($aLines) - 1; $i++) {
                                $aLines[$i] = "                 " . $aLines[$i];
                            }
                            $sArrayCode = implode(PHP_EOL, $aLines);
                            $this->_aTokens["COLUMNS"] .= "             \"" . $sPropName . "\" => " . $sArrayCode . "," . PHP_EOL;
                        } else {
                            switch ($sPropName) {
                                case "IsVisible":
                                case "IsMultilingual":
                                    $this->_aTokens["COLUMNS"] .= "             \"" . $sPropName . "\" => " . Boolean::toString($mPropertyValue) . "," . PHP_EOL;
                                    break;
                                default : 
                                    $this->_aTokens["COLUMNS"] .= "             \"" . $sPropName . "\" => \"" . $mPropertyValue . "\"," . PHP_EOL;
                            }
                            
                        }
                    }
                                        
                }
                $this->_aTokens["COLUMNS"] .= "         ]," . PHP_EOL;


            }   
        }

    }

    protected function _buildGettersAndSetters($aColumn) {
    
        $this->_aTokens["GETTERS_AND_SETTERS"] .= "\t/**" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t * " . "" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t * @return " . "" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t */" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\tpublic function get" . $aColumn["Name"] . "() {" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t	return \$this->getFieldValue(\"" . $aColumn["Name"] . "\");" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t}" . PHP_EOL;
			
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t" . PHP_EOL;
			
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t/**" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t * " . "" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t * @param " . "" . " \$" . $this->_getVarPrefix($aColumn["Type"]) . $aColumn["Name"] . "" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t */" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\tpublic function set" . $aColumn["Name"] . "(\$" .  $this->_getVarPrefix($aColumn["Type"]) . $aColumn["Name"] . ") {" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t	\$this->setFieldValue(\"" .  $aColumn["Name"]. "\" , \$" .  $this->_getVarPrefix($aColumn["Type"]) . $aColumn["Name"] . ");" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t	return \$this;" . PHP_EOL;
		$this->_aTokens["GETTERS_AND_SETTERS"] .= "\t}" . PHP_EOL;
    }

    protected function _buildFiles() {
    
        $factory = app(ViewFactory::class);
        $sPath = $this->getPathFromNamespace($this->MODEL_NAMESPACE);

        $sDataClassContent = $factory->file($this->_getToken("DATA_CLASS_TEMPLATE_FILENAME"), $this->_getTokens())->render();
        /**
         * Always override data class
         */
        file_put_contents($sPath . "/" . $this->MODEL_NAME . "Data.php", $sDataClassContent);

        $sClassContent = $factory->file($this->_getToken("CLASS_TEMPLATE_FILENAME"), $this->_getTokens())->render();
        /**
         * Only create main class if does not exist
         */
        if (!file_exists($sPath . "/" . $this->MODEL_NAME . ".php")) {
            file_put_contents($sPath . "/" . $this->MODEL_NAME . ".php", $sClassContent);
        }
        
    }

    public function getPathFromNamespace($sNamespace = NULL) {

        if ($sNamespace === NULL) {
            $sNamespace = $this->MODEL_NAMESPACE;
        }

        $sPath = "";
        if (starts_with($sNamespace, "App\\")) {
            $sNamespace = str_replace("App\\", "", $sNamespace);
        }

        $sNamespace = str_replace("\\", "/", $sNamespace);

        return app_path($sNamespace);

    }

    protected function _setToken($sToken, $sValue) {
        $this->_aTokens[$sToken] = $sValue;
    }

    protected function _appendToken($sToken, $sValue) {
        if (array_has($this->_aTokens, $sToken)) {
            $this->_aTokens[$sToken] .= $sValue;
        } else {
            $this->_aTokens[$sToken] = $sValue;
        }
    }

    protected function _getToken($sToken) {
        return array_get($this->_aTokens, $sToken);
    }

    protected function _getTokens() {
        return $this->_aTokens;
    }

    /**
     * Initializes required tokens for template file
     */
    protected function _initTokens() {

        $this->_aTokens = [];

        $this->_setToken("DATA_CLASS_TEMPLATE_FILENAME", __DIR__ . "/resources/templates/data.blade.php");
        $this->_setToken("CLASS_TEMPLATE_FILENAME", __DIR__ . "/resources/templates/class.blade.php");

        $this->_setToken("PHP_OPEN_TAG", "<?php");
        $this->_setToken("PHP_CLOSE_TAG", "?>");

        $this->_setToken("NAMESPACE", $this->MODEL_NAMESPACE);
        $this->_setToken("CLASSNAME", $this->MODEL_NAME);

        $this->_setToken("USE", []);
        $this->_setToken("DESCRIPTION", $this->MODEL_DESCRIPTION);
        $this->_setToken("CONSTANTS", "");
        $this->_setToken("VARIABLES", "");        
        $this->_setToken("DATABASENAMESET", "");
        $this->_setToken("SETOBJECTMODULE", "");
        $this->_setToken("DATAFIELDS", "");
        $this->_setToken("TRANSLATIONTABLE", "");
        $this->_setToken("GETTERS_AND_SETTERS", "");
        $this->_setToken("TABLENAME", $this->MODEL_TABLE);
        $this->_setToken("DATABASENAME", "");
        $this->_setToken("BEFORESAVE", "");
        $this->_setToken("AFTERSAVE", "");
        $this->_setToken("BEFOREDELETE", "");
        $this->_setToken("AFTERDELETE", "");
        $this->_setToken("VALIDATIONS", "");
        $this->_setToken("RELATIONSHIPCODE", "");
        $this->_setToken("STORECLIENTWHERE", "");
        $this->_setToken("COUNTFILTERCLIENTID", "");
        $this->_setToken("COLUMNS", "");
        

    }

    protected function _addVariable($sVariable, $sComment = NULL) {
		if (!ends_with($sVariable, ";")) {
			$sVariable .= ";";
		}
		if ($sComment !== NULL) {
			$this->_aTokens["VARIABLES"] .= "\t/**" . PHP_EOL;
			$this->_aTokens["VARIABLES"] .= "\t * @var " . wordwrap($sComment, 80, PHP_EOL . "\t * ") . PHP_EOL;
			$this->_aTokens["VARIABLES"] .= "\t */" . PHP_EOL;
		}
		$this->_aTokens["VARIABLES"] .= "\t" . $sVariable . PHP_EOL;
	}

    protected function _addConstant($sConstant, $sComment = NULL) {
		if (!ends_with($sConstant, ";")) {
			$sConstant .= ";";
		}
		if ($sComment !== NULL) {
			$this->_aTokens["CONSTANTS"] .= "\t/**" . PHP_EOL;
			$this->_aTokens["CONSTANTS"] .= "\t * @var " . wordwrap($sComment, 80, PHP_EOL . "\t * ") . PHP_EOL;
			$this->_aTokens["CONSTANTS"] .= "\t */" . PHP_EOL;
		}
		$this->_aTokens["CONSTANTS"] .= "\t" . $sConstant . PHP_EOL;
	}

    protected function _addUse($sNamespace) {

        if (trim($sNamespace) == "") {
            return FALSE;
        }

        // Remove \ is namespace starts with \
        if (starts_with($sNamespace, "\\")) {
            $s = substr($sNamespace, 1);
        }

        $this->_aTokens["USE"][$sNamespace] = "use " . $sNamespace . ";" . PHP_EOL;
        
    }

/**
	 * Returns variable prefix based on column data type
	 * @param unknown $sType
	 * @return string
	 */
	protected function _getVarPrefix($sType) {
			
		$sType = substr($sType, 0, strpos($sType, "("));
			
		switch (strtolower($sType)) {
			case "int":
				return "n";
			case "tinyint":
				return "b";
			case "varchar":
			case "mediumtext":
			case "longtext":
			case "text":
				return "s";
			default:
				return "o";
		}
			
			
    }
    
    /**
	 * 
	 * @param unknown $sType
	 */
	protected function _getDataType($sType) {
	
		if (str_contains($sType, "(")) {
			$sType = substr($sType, 0, strpos($sType, "("));
		}
	
		switch (strtolower($sType)) {
			case "int":
				return "TYPE_INT";
			case "tinyint":
				return "TYPE_BOOLEAN";
			case "varchar":
				return "TYPE_VARCHAR";
			case "text":
				return "TYPE_TEXT";
			case "longtext":
				return "TYPE_TEXT";
			case "mediumtext":
				return "TYPE_LONGTEXT";
			case "datetime":
				return "TYPE_DATETIME";
			case "decimal":
				return "TYPE_DECIMAL";
			case "double":
                return "TYPE_DOUBLE";
            case "timestamp":
				return "TYPE_TIMESTAMP";
			default:
				return "TYPE_VARCHAR";
		}
	
	
    }
    

    /**
	 * Returns the length of a data type
	 * @param unknown $sType
	 */
	protected function _getDataLength($sType) {
	
		if (strpos($sType, "(") === FALSE) {
			return 0;
		} else {
			$sLength = substr($sType, strpos($sType, "(") + 1);
            $sLength = str_replace(")", "", $sLength);
            $sLength = str_replace("unsigned", "", $sLength);
			return trim($sLength);
		}
	
	
	}

    /**
	 * Adds a field, DataField object, to the array of fields
	 */
	public function addField($oField) {

		if (is_object($oField)) {
			$this->_aFields[$oField->getFieldName()] = $oField;
		} else {
			throw new ExceptionBase('Invalid field type, must be object of type DataField');
		}
	
	}

    
}

?>