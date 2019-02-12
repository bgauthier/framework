<?php 
namespace Framework\Modules\Build;

use Illuminate\Support\Facades\Route;
use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;
use Illuminate\Support\Facades\Log;

class BuildModule extends Module {
    
    public function __construct() {
        parent::__construct();
        Log::debug("Setting base path to Build");
        $this->_sModuleBasePath = "Build";        
    }


    public function initialize() {
        parent::initialize();
    }

    protected function _addModuleRoutes() {
        parent::_addModuleRoutes();
        
        Log::debug("--- Adding routes /" . $this->_sModuleBasePath);
        Route::get("/build/compile/buildJS", "\Framework\Modules\Build\Controllers\CompilerController@buildJSAction")
            ->name("build.compile.js");        
        Route::get("/build/compile/buildTranslation", "\Framework\Modules\Build\Controllers\CompilerController@buildTranslationAction")
            ->name("build.compile.lang");
        Route::get("/build/compile/buildImages", "\Framework\Modules\Build\Controllers\CompilerController@buildImagesAction")
            ->name("build.compile.images");

    }

}

?>