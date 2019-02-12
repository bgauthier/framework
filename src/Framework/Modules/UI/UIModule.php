<?php 
namespace Framework\Modules\UI;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;
use Framework\Modules\Build\Compiler;
use Framework\Modules\UI\Fonts\FontAwesome;

class UIModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->_sModuleBasePath = "UI";
    }


    public function initialize() {
        
        parent::initialize();


        FontAwesome::enableProVersion(config('framework.fontAwesomeProEnabled'));

        /**
         * Set theme from env file
         */
        Theme::setThemeBasePath(getenv("LOGIKSUITE_THEME_BASE_PATH"));
        /**
         * Initialize the theme
         */
        Theme::initialize();
        Theme::addScriptTag("/vendor/framework/libs/moment/moment.js");
        Theme::addScriptTag("/vendor/framework/js/UI.js");




    }

    /**
     * Compile module resources
     */
    public function compile() {

         /**
         * Add core JS file to compiler
         */
        Compiler::addJSFile(__DIR__ . "/js/PageComponent.js", "UI");
        Compiler::addJSFile(__DIR__ . "/js/Component.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Store.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Div.js", "UI");     
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Alert.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Anchor.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Button.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/CheckBox.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Column.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Form.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Heading.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Icon.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Image.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Label.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Li.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Ol.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Panel.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Paragraph.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Pre.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/RadioButton.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Row.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Select.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Span.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Table.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/Text.js", "UI");
        Compiler::addJSFile(__DIR__ . "/Bootstrap/js/TextArea.js", "UI");
        Compiler::addJSFile(__DIR__ . "/js/Page.js", "UI");

        Compiler::addJSFile(__DIR__ . "/js/AlertAction.js", "UI");
        Compiler::addJSFile(__DIR__ . "/js/JQueryAction.js", "UI");

        Compiler::buildJSFiles("UI");

    }
   

}

?>