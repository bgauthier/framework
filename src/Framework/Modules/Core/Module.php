<?php 
namespace Framework\Modules\Core;

use Illuminate\Support\Facades\Log;
use Framework\Modules\DataType\Strings;

class Module {

    protected $_sModuleBasePath = NULL;
    protected $_aControllers = NULL;
    protected $_aViews = NULL;
    protected $_aModels = NULL;
    protected $_aPolicies = NULL;
    protected $_aSchemas = NULL;


    public function __construct() {
        
        if (__NAMESPACE__ != "") {
            $aTokens = explode("\\", __NAMESPACE__);
            $this->_sModuleBasePath = last($aTokens);
        }


    }

    public function initialize() {
        Log::debug("Initializing module " . get_class($this));
        $this->_addModuleRoutes();
    }

    /**
     * Registers module routes
     */
    protected function _addModuleRoutes() {
        Log::debug("- Adding module routes for " . get_class($this));

        /**
         * Get module controllers and register routes for each controllers
         */
        $aControllers = $this->getControllers();

        foreach($aControllers as $aController) {            
            $oController = new $aController["Use"]();
            if (method_exists($oController, "registerRoutes")) {
                $oController->registerRoutes();
            }
        }

    }

    public function compile() {
        
    }


    public function getControllers() {

        if ($this->_aControllers === NULL) {

            $this->_aControllers =  [];
            
            $sPath = \realpath(__DIR__ . "/../" . $this->_sModuleBasePath . "/Controllers");
            if (!file_exists($sPath)) {
                return  $this->_aControllers;
            }

            $aFiles = scandir($sPath);
            
            foreach($aFiles as $sFile) {
                if ($sFile != "." && $sFile != ".." && !ends_with($sFile, "Base.php") && !is_dir($sPath . "/" . $sFile)) {
                    // Controller file


                    $sClassName = str_replace(".php", "", $sFile);
                    $sUse = "\\Framework\\Modules\\" . $this->_sModuleBasePath . "\\Controllers\\" . $sClassName;

                    $oRef = new \ReflectionClass($sUse);
                    $aProperties = $oRef->getDefaultProperties();

                    $this->_aControllers[\realpath($sPath . "/" . $sFile)] = [
                        "ClassName" => $sClassName,
                        "FileName" => \realpath($sPath . "/" . $sFile),
                        "Use" => $sUse,
                        "Namespace" => "Framework\\Modules\\" . $this->_sModuleBasePath . "\\Controllers",
                        "Description" => array_get($aProperties, "CONTROLLER_DESCRIPTION"),
                        "Actions" => array_get($aProperties, "CONTROLLER_ACTIONS"),
                    ];
                } 
            }

            ksort($this->_aControllers);
    
        }
        

        return $this->_aControllers;

    }

    public function getModels() {

        if ($this->_aModels === NULL) {

            $this->_aModels =  [];
            
            $sPath = \realpath(__DIR__ . "/../" . $this->_sModuleBasePath . "/Models");
            if (!file_exists($sPath)) {
                return  $this->_aModels;
            }

            $aFiles = scandir($sPath);
            
            foreach($aFiles as $sFile) {
                if ($sFile != "." && $sFile != ".." && !ends_with($sFile, "Base.php") && !is_dir($sPath . "/" . $sFile)) {
                    // Controller file
                    $sClassName = str_replace(".php", "", $sFile);
                    $this->_aModels[\realpath($sPath . "/" . $sFile)] = [
                        "ClassName" => $sClassName,
                        "FileName" => \realpath($sPath . "/" . $sFile),
                        "Use" => "\\Framework\\Modules\\" . $this->_sModuleBasePath . "\\Controllers\\" . $sClassName, 
                        "Namespace" => "Framework\\Modules\\" . $this->_sModuleBasePath . "\\Controllers"                        
                    ];
                } 
            }
    
        }
        

        return $this->_aModels;

    }

    public function getViews() {

        if ($this->_aViews === NULL) {

            $this->_aViews =  [];
            
            $sPath = \realpath(__DIR__ . "/../" . $this->_sModuleBasePath . "/Views");
            if (!file_exists($sPath)) {
                return  $this->_aViews;
            }

            $aFiles = scandir($sPath);
            
            foreach($aFiles as $sFile) {
                if ($sFile != "." && $sFile != ".." && !ends_with($sFile, "Base.php") && !is_dir($sPath . "/" . $sFile)) {
                    // Controller file
                    $sClassName = str_replace(".php", "", $sFile);
                    $this->_aViews[\realpath($sPath . "/" . $sFile)] = [
                        "ClassName" => $sClassName,
                        "FileName" => \realpath($sPath . "/" . $sFile),
                        "Use" => "\\Framework\\Modules\\" . $this->_sModuleBasePath . "\\Controllers\\" . $sClassName, 
                        "Namespace" => "Framework\\Modules\\" . $this->_sModuleBasePath . "\\Controllers"                        
                    ];
                } 
            }
    
        }
        

        return $this->_aViews;

    }


    public function getPolicies() {

        if ($this->_aPolicies === NULL) {

            $this->_aPolicies =  [];
            
            $sPath = \realpath(__DIR__ . "/../" . $this->_sModuleBasePath . "/Policies");
            if (!file_exists($sPath)) {
                return  $this->_aPolicies;
            }

            $aFiles = scandir($sPath);
            
            foreach($aFiles as $sFile) {
                if ($sFile != "." && $sFile != ".." && !ends_with($sFile, "Base.php") && !is_dir($sPath . "/" . $sFile)) {
                    // Controller file
                    $sClassName = str_replace(".php", "", $sFile);
                    $this->_aPolicies[\realpath($sPath . "/" . $sFile)] = [
                        "ClassName" => $sClassName,
                        "FileName" => \realpath($sPath . "/" . $sFile),
                        "Use" => "\\Framework\\Modules\\" . $this->_sModuleBasePath . "\\Controllers\\" . $sClassName, 
                        "Namespace" => "Framework\\Modules\\" . $this->_sModuleBasePath . "\\Controllers"                        
                    ];
                } 
            }
    
        }
        

        return $this->_aPolicies;

    }


    public function getSchemas() {

        if ($this->_aSchemas === NULL) {

            $this->_aSchemas =  [];
            
            $sPath = \realpath(__DIR__ . "/../" . $this->_sModuleBasePath . "/Schemas");
            if (!file_exists($sPath)) {
                return  $this->_aSchemas;
            }

            $aFiles = scandir($sPath);
            
            foreach($aFiles as $sFile) {
                if ($sFile != "." && $sFile != ".." && !ends_with($sFile, "Base.php") && !is_dir($sPath . "/" . $sFile)) {
                    // Controller file
                    $sClassName = str_replace(".php", "", $sFile);
                    $this->_aSchemas[\realpath($sPath . "/" . $sFile)] = [
                        "ClassName" => $sClassName,
                        "FileName" => \realpath($sPath . "/" . $sFile),
                        "Use" => "\\Framework\\Modules\\" . $this->_sModuleBasePath . "\\Schemas\\" . $sClassName, 
                        "Namespace" => "Framework\\Modules\\" . $this->_sModuleBasePath . "\\Schemas"                        
                    ];
                } 
            }
    
        }
        

        return $this->_aSchemas;

    }


}

?>