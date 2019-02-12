<?php 
namespace Framework\Modules\Core;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;
use Framework\Modules\Build\Compiler;

class CoreModule extends Module {

    public function __constructor() {
        parent::__constructor();
        $this->_sModuleBasePath = "Core";
    }


    /**
     * Module initialize
     */
    public function initialize() {

        parent::initialize();
        
        /**
         * Add Framework JS files
         */
        Theme::addScriptTag("/vendor/framework/libs/bootbox/bootbox.min.js");
        Theme::addScriptTag("/vendor/framework/js/Framework.js");
        Theme::addCSSTag("/vendor/framework/css/style.css");
       
    }

    /**
     * Add routes for this module
     */
    protected function _addModuleRoutes() {

    }

    /**
     * Module compile instructions for "php artisan framework:build"
     */
    public function compile() {

        /**
         * Add core JS file to compiler
         */
        Compiler::addJSFile(__DIR__ . "/js/Framework.js", "Framework");
        Compiler::addJSFile(__DIR__ . "/js/Log.js", "Framework");
        Compiler::addJSFile(__DIR__ . "/js/Dialog.js", "Framework");   
        Compiler::addJSFile(__DIR__ . "/js/Localization.js", "Framework");     
        Compiler::addJSFile(__DIR__ . "/js/Ajax.js", "Framework");     
        Compiler::addJSFile(__DIR__ . "/js/Navigation.js", "Framework");   
        Compiler::addJSFile(__DIR__ . "/js/Utilities.js", "Framework");
        Compiler::addJSFile(__DIR__ . "/js/UI.js", "Framework");
        Compiler::addJSFile(__DIR__ . "/js/Init.js", "Framework");

        Compiler::buildJSFiles("Framework");

    }

}

?>