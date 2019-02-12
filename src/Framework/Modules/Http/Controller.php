<?php 
namespace Framework\Modules\Http;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class Controller extends \App\Http\Controllers\Controller {
 
    const RESPONSE_HTTP = "_HTTP";
	const RESPONSE_JSON = "_JSON";
	
	const ACTION_CUSTOM = "_CUSTOM";
	const ACTION_LIST = "_LIST";
    const ACTION_EDIT = "_EDIT";
    const ACTION_CREATE = "_CREATE";
	const ACTION_SAVE = "_SAVE";
	const ACTION_DELETE = "_DELETE";
	const ACTION_SEARCH = "_SEARCH";
	const ACTION_FORM = "_FORM";
    const ACTION_CLONE = "_CLONE";
    
    /**
     * 
     */
    public $CONTROLLER_NAMESPACE = "";
    
	/**
	 * @var Controller name
	 */
    public $CONTROLLER_NAME = "";
    
	/**
	 * @var Controller description
	 */
	public $CONTROLLER_DESCRIPTION = "";

    protected $_aTokens = [];

    /**
     * 
     */
    public $CONTROLLER_ACTIONS = [];

    public function registerRoutes() {

        foreach($this->CONTROLLER_ACTIONS as $sActionName => $aAction) {
            
            foreach($aAction["Routes"] as $aRoute) {

                $oRoute = Route::addRoute($aRoute["Method"], $aRoute["URI"], $aRoute["Action"]);
                if ($aRoute["Name"] != "") {
                    $oRoute->name($aRoute["Name"]);
                }
                if (implode(",", $aRoute["Middleware"]) != "") {
                    $oRoute->middleware(implode(",", $aRoute["Middleware"]));
                }
            }

        }

    }

    public function getClassName() {
        return $this->CONTROLLER_NAME;
    }

    public function getNamespace() {
        return $this->CONTROLLER_NAMESPACE;
    }

    public function getDescription() {
        return $this->CONTROLLER_DESCRIPTION;
    }

    public function saveControllerDefinition() {

        Log::debug("Controller::saveControllerDefinition");

        // Init tokens
        $this->_aTokens = ["NAMESPACE" => "", "USE" => "", "NAME" => "", "ACTIONS" => "", "PREPOSTACTIONS" => "", "VARIABLES" => ""];

        $this->_aTokens["NAMESPACE"] = $this->CONTROLLER_NAMESPACE;
        $this->_aTokens["NAME"] = $this->CONTROLLER_NAME;
        $this->_aTokens["CLASSNAME"] = $this->CONTROLLER_NAME;


        $this->_addVariable("public \$CONTROLLER_NAMESPACE = \"" . addslashes($this->CONTROLLER_NAMESPACE) . "\";", "Controller namespace");
        $this->_addVariable("public \$CONTROLLER_NAME = \"" . $this->CONTROLLER_NAME . "\";", "Controller name");
        $this->_addVariable("public \$CONTROLLER_DESCRIPTION = \"" . $this->CONTROLLER_DESCRIPTION . "\";", "Controller description");

        $this->_aTokens["BASECLASSCONTENT"] = file_get_contents(__DIR__ . "/resources/templates/controllerbase.blade.php");
        $this->_aTokens["CLASSCONTENT"] = file_get_contents(__DIR__ . "/resources/templates/controller.blade.php");

        $this->_addUse("Framework\\Modules\\Http\\Controller");
        $this->_addUse("Framework\\Modules\\Http\\Response");
        $this->_addUse("Framework\\Modules\\Core\\Framework");
        //$this->_addUse("Framework\\Modules\\Security\\Session");


        $this->_buildActions();
        $this->_prepareBuild();

        $this->_saveFiles();

        Log::debug("Controller::saveControllerDefinition");


    }

    protected function __buildActions() {

    }

    /**
     * Prepares all variables for build
     */
    protected function _prepareBuild() {

        Log::debug("Controller::_prepareBuild");

        $sUse = "";
        if (is_array($this->_aTokens["USE"])) {
            foreach($this->_aTokens["USE"] as $s) {
                $sUse .= $s;
            }
        }
        $this->_aTokens["USE"] = $sUse;

        Log::debug("Controller::_prepareBuild");

    }

    /**
     * Adds a variable to the class
     * @param unknown $s
     */
    protected function _addVariable($s, $comment = NULL) {
        if (!ends_with($s, ";")) {
            $s .= ";";
        }

        if (str_contains($this->_aTokens["VARIABLES"], $s) === FALSE) {

            if ($comment !== NULL) {
                $this->_aTokens["VARIABLES"] .= "\t/**" . PHP_EOL;
                $this->_aTokens["VARIABLES"] .= "\t * @var " . wordwrap($comment, 80, PHP_EOL . "\t * ") . PHP_EOL;
                $this->_aTokens["VARIABLES"] .= "\t */" . PHP_EOL;
            }
            $this->_aTokens["VARIABLES"] .= "\t" . $s . PHP_EOL;

        }
    }

    /**
     * Add use statement
     * @param unknown $s
     */
    protected function _addUse($s) {
        if (trim($s) != "") {
            $this->_aTokens["USE"][$s] = "use " . $s . ";" . PHP_EOL;
        }
    }


    protected function _saveFiles() {

        $factory = app(ViewFactory::class);

        $sPath = $this->getPathFromNamespace($this->CONTROLLER_NAMESPACE);

        $sDataClassContent = $factory->file(__DIR__ . "/resources/templates/controllerbase.blade.php", $this->_getTokens())->render();
        /**
         * Always override data class
         */
        file_put_contents($sPath . "/" . $this->CONTROLLER_NAME . "Base.php", $sDataClassContent);

        $sClassContent = $factory->file(__DIR__ . "/resources/templates/controller.blade.php", $this->_getTokens())->render();
        /**
         * Only create main class if does not exist
         */
        if (!file_exists($sPath . "/" . $this->CONTROLLER_NAME . ".php")) {
            file_put_contents($sPath . "/" . $this->CONTROLLER_NAME . ".php", $sClassContent);
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


}

?>