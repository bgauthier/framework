<?php 
namespace Framework\Modules\Build;

use Illuminate\Support\Facades\Log;
use Illuminate\Filesystem\Filesystem;

class Compiler {

    protected static $_aJSFiles = [];

    public static function addJSFile($sFile, $sLibraryName = "Framework") {

        if (!array_has(static::$_aJSFiles, $sLibraryName)) {
            static::$_aJSFiles[$sLibraryName] = [];
        }

        if (!in_array($sFile, static::$_aJSFiles)) {
            static::$_aJSFiles[$sLibraryName][] = $sFile;
        }
    }

    public static function buildJSFiles($sLibraryName = "Framework", $sOutputPath = NULL, $bMinify = TRUE) {

        $sDistJS = "";

        if ($sOutputPath == "") {            
            $sOutputPath = __DIR__ . "/../../../../build/js";                
        }

        $sOutputPath = \realpath($sOutputPath);

        if (!file_exists($sOutputPath)) {
            Log::debug("Compiler creating output path " . $sOutputPath);
            mkdir($sOutputPath);
        }

        foreach(static::$_aJSFiles[$sLibraryName] as $sFile) {
			$sJSFileContent =  file_get_contents($sFile);
			$sDistJS .= $sJSFileContent . PHP_EOL;
        }
        
        file_put_contents($sOutputPath . "/" . $sLibraryName . ".js", $sDistJS);

        if ($bMinify) {
            /**
             * Build minified version
             */
            $oMinifier = new \MatthiasMullie\Minify\JS();
            $oMinifier->add($sOutputPath . "/" . $sLibraryName . ".js");
            $sDistJSMin = $oMinifier->minify();
            
            file_put_contents($sOutputPath . "/" . $sLibraryName . ".min.js", $sDistJSMin);
        }

    }

    public static function buildTranslationFiles() {

        /**
         * Find lang files and copy them to build folder
         */
        $sModulesPath = \realpath(__DIR__ . "/..");
        $sBuildPath = \realpath(__DIR__ . "/../../../../build");
        $oFileSystem = new Filesystem();

        $aFiles = scandir($sModulesPath);
        
        foreach($aFiles as $sModuleName) {
            if (file_exists($sModulesPath . "/" . $sModuleName . "/resources/lang")) {
                // Module has translation files
                Log::debug("Module " . $sModuleName . " has translation files");
                Log::debug("Copying translation files from " . $sModulesPath . "/" . $sModuleName . "/resources/lang");
                Log::debug("to " . $sBuildPath . "/lang");
                $oFileSystem->copyDirectory($sModulesPath . "/" . $sModuleName . "/resources/lang", $sBuildPath . "/lang");
            }
        }
    
        
    }


    public static function buildImages() {

        /**
         * Find lang files and copy them to build folder
         */
        $sModulesPath = \realpath(__DIR__ . "/..");
        $sBuildPath = \realpath(__DIR__ . "/../../../../build");
        $oFileSystem = new Filesystem();

        $aFiles = scandir($sModulesPath);
        
        foreach($aFiles as $sModuleName) {
            if (file_exists($sModulesPath . "/" . $sModuleName . "/resources/images")) {
                // Module has translation files
                Log::debug("Module " . $sModuleName . " has images");
                Log::debug("Copying images from " . $sModulesPath . "/" . $sModuleName . "/resources/images");
                Log::debug("to " . $sBuildPath . "/images");
                $oFileSystem->copyDirectory($sModulesPath . "/" . $sModuleName . "/resources/images", $sBuildPath . "/images");
            }
        }
    
        
    }

}

?>