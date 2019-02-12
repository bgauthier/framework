<?php
namespace Framework\Modules\Core;

use Framework\Modules\UI\Theme;
use Illuminate\Support\Facades\Log;
use Framework\Modules\DataType\Strings;


class Framework {

    protected static $_bIsInitialized = FALSE;
    protected static $_aModules = NULL;

    protected static $_aModuleLoadOrder = [
        "Core" => "Framework\\Modules\\Core\\CoreModule",
        "DataType" => "Framework\\Modules\\DataType\\DataTypeModule",
        "Data" => "Framework\\Modules\\Data\\DataModule",        
        "Settings" => "Framework\\Modules\\Settings\\SettingsModule",
        "Logging" => "Framework\\Modules\\Logging\\LoggingModule",        
        "Http" => "Framework\\Modules\\Http\\HttpModule",           
        "Exception" => "Framework\\Modules\\Exception\\ExceptionModule",
        "Localization" => "Framework\\Modules\\Localization\\LocalizationModule",
        "Security" => "Framework\\Modules\\Security\\SecurityModule",
        "Install" => "Framework\\Modules\\Install\\InstallModule",        
        "Help" => "Framework\\Modules\\Help\\HelpModule",
        "Reporting" => "Framework\\Modules\\Reporting\\ReportingModule",
        "Search" => "Framework\\Modules\\Search\\SearchModule",
        "Workflow" => "Framework\\Modules\\Workflow\\WorkflowModule",
        "Build" => "Framework\\Modules\\Build\\BuildModule",
        "UI" => "Framework\\Modules\\UI\\UIModule",
    ];
    
    public static function getIsInitialized() {
        return static::$_bIsInitialized;
    }
    
    /**
     * Initialize the framework
     */
    public static function initialize() {

        if (static::$_bIsInitialized) {
            return FALSE;
        }

        Log::debug('Framework::initialize');                



        static::_initializeModules();

        

        static::$_bIsInitialized = TRUE;
        return TRUE;

    }

    /**
     * Initialize all modules
     */
    protected static function _initializeModules() {

        foreach(static::$_aModuleLoadOrder as $sModuleName => $sModuleClassName) {            
            $oModule = new  $sModuleClassName();
            $oModule->initialize();
        }                

    }

   // public static function getModules() {
    //    return static::$_aModuleLoadOrder;
    //}

    public static function getModules($bInstanciateModules = FALSE) {

        if (static::$_aModules === NULL) {

            static::$_aModules =  [];
            
            $sPath = \realpath(__DIR__ . "/../");
            if (!file_exists($sPath)) {
                return  static::$_aModules;
            }

            $aFiles = scandir($sPath);
            
            foreach($aFiles as $sFile) {
                if ($sFile != "." && $sFile != ".." && !ends_with($sFile, "Base.php") && is_dir($sPath . "/" . $sFile)) {
                    // Controller file      
                    $sModuleName = str_replace(".php", "", $sFile);
                    $sClassName = $sModuleName . "Module";
                    $sUse = "\\Framework\\Modules\\" . $sModuleName . "\\" . $sClassName;
                    
                    static::$_aModules[\realpath($sPath . "/" . $sFile)] = [
                        "Name" => $sModuleName,
                        "FileName" => \realpath($sPath . "/" . $sFile),     
                        "ClassName" => $sClassName,     
                        "Use" => $sUse,
                        "Namespace" => "Framework\\Modules\\" . $sModuleName,
                        "Object" => ($bInstanciateModules ? new $sUse() : NULL),
                    ];
                } 
            }
    
        }
        

        return static::$_aModules;

    }

    public static function getControllers() {
        $aControllers = [];
        foreach(static::getModules(TRUE) as $aModule) {
            $aControllers = array_merge($aControllers, $aModule["Object"]->getControllers());
        }

        return $aControllers;
    }


    public static function getModels() {
        $aModels = [];
        foreach(static::getModules(TRUE) as $aModule) {
            $aModels = array_merge($aModels, $aModule["Object"]->getModels());
        }

        return $aModels;
    }

    public static function getViews() {
        $aViews = [];
        foreach(static::getModules(TRUE) as $aModule) {
            $aViews = array_merge($aViews, $aModule["Object"]->getViews());
        }

        return $aViews;
    }


}

?>