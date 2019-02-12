<?php 
namespace Framework\Modules\Data;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Schema;
use Framework\Modules\DataType\Boolean;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Database\Schema\Grammars\Grammar;

class Blueprint extends \Illuminate\Database\Schema\Blueprint {

    protected $_bDebugEnabled = FALSE;

    public function setDebugEnabled($bEnabled) {
        $this->_bDebugEnabled = $bEnabled;
        return $this;
    }

    protected function _debug($s) {
        if ($this->_bDebugEnabled) {
            dump($s);
        }
    }

    public function toSqlSync(Connection $connection, Grammar $grammar) {

        $aBluePrintColumns = $this->getColumns();
        $aCommands = $this->getCommands();

        $this->_debug("Start of analysis of table " . $this->getTable());

        if (Schema::hasTable($this->getTable())) {

            $this->_debug("-- Table exists in database, retreiving current table description");

            $rst = DB::select("desc " . $this->getTable());            
            
            

            $aDBColumns = [];
            foreach($rst as $aCol) {

                $aCol = json_decode(json_encode($aCol), TRUE);

                $aLength = $this->_parseLength($aCol);
                if ($aLength !== FALSE) {
                    $aCol["HasPrecision"] = TRUE;
                    $aCol["Total"] = $aLength["Total"];
                    $aCol["Places"] = $aLength["Places"];
                } else {
                    $aCol["HasPrecision"] = FALSE;
                }
                $aCol["Unsigned"] = $this->_parseUnsigned($aCol);
                $aCol["DataType"] = $this->_parseDataType($aCol);
                $aCol["AutoIncrement"] = $this->_parseAutoIncrement($aCol);
                $aDBColumns[$aCol["Field"]] = $aCol;
                

            }
            
            foreach($aBluePrintColumns as $oCol) {
                
                $aBluePrintCol = json_decode(json_encode($oCol), TRUE);

                // Does column exist in table
                if (!array_key_exists($aBluePrintCol["name"], $aDBColumns)) {
                    // New column add it
                    $o = new Blueprint($this->getTable());
                    $o->addColumnDefinition($oCol);
                    dump($o->toSql($connection, $grammar));
                } else {

                    $aDBCol = $aDBColumns[$aBluePrintCol["name"]];

                    // Column exists
                    // Check if different 
                    $bDifferent = FALSE;
                    if ( Boolean::toBool(array_get($aDBCol, "AutoIncrement")) != Boolean::toBool(array_get($aBluePrintCol, "autoIncrement")) ) {
                        $bDifferent = TRUE;
                    }
                    if ( Boolean::toBool( array_get(  $aDBCol, "Null") )  != Boolean::toBool( array_get($aBluePrintCol, "nullable") )) {
                        $bDifferent = TRUE;
                    }
                    if ( $aDBCol["Default"] != $aBluePrintCol["default"]) {
                        $bDifferent = TRUE;
                    }

                    if ($aDBCol["HasPrecision"]) {

                        if ( $aDBCol["Total"] != $aBluePrintCol["total"]) {
                            $bDifferent = TRUE;
                        }
                        if ( $aDBCol["Places"] != $aBluePrintCol["places"]) {
                            $bDifferent = TRUE;
                        }

                    }

                    // If different get sql
                    if ($bDifferent) {
                        $o = new Blueprint($this->getTable());
                        $o->addColumnDefinition($oCol)->change();
                        dump($o->toSql($connection, $grammar));
                    }
                }

            }

            foreach($aCommands as $oCommand) {

                $aCmd = json_decode(json_encode($oCommand), TRUE);
                

            }

        } else {
            // New table create it
            return $this->toSql($connection, $grammar);
        }

        
    

    }

    protected function _parseLength($aCol) {

        $sType = $aCol["Type"];
        if (str_contains($sType, "(")) {
            // We have a length
            $sTypeName = substr($sType, 0, strpos($sType, "("));
			$s = substr($sType, strpos($sType, "(") + 1);
			$s = substr($s, 0, strpos($s, ")"));
			if (strpos($s, ",") !== false) {
				$a = explode(",", $s);
				$sSize = $a[0];
				$sPrecision = $a[1];
			} else {
				$sSize = $s;
				$sPrecision = "";
            }
            
            return [
                "Total" => $sSize,
                "Places" => $sPrecision
            ];

        } 
        return FALSE;
    }

    protected function _parseUnsigned($aCol) {
        if (str_contains($aCol["Type"], " unsigned")) {
            return TRUE;
        }
        return FALSE;
    }

    protected function _parseDataType($aCol) {

        $sType = $aCol["Type"];
        if (str_contains($sType, "(")) {
            // We have a length
            $sTypeName = trim(substr($sType, 0, strpos($sType, "(")));
        } else {
            $sTypeName = trim($sType);
        }

        switch (strtolower($sTypeName)) {
            case "int":
                return "integer";
            case "varchar":
                return "string";
            case "decimal":
                return "decimal";
            default : 
                return $sTypeName;                            
        }

    }

    protected function _parseAutoIncrement($aCol) {
        if (str_contains(strtolower($aCol["Extra"]), "auto_increment")) {
            return TRUE;
        }
        return FALSE;
    }

    public function addColumnDefinition(ColumnDefinition $oCol) {
        $this->columns[] = $oCol;
        return $oCol;
    }

}

?>