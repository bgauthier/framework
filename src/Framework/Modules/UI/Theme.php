<?php 
namespace Framework\Modules\UI;

use Framework\Modules\UI\Fonts\FontAwesome;
use Theme\ThemeSettings;
use Framework\Modules\Http\Url;
use Framework\Modules\UI\Theme;
use Illuminate\Support\Facades\Log;
use Framework\Modules\Core\Framework;
use Illuminate\Support\Facades\Config;


/**
 *
 *	LICENSE: This source file is subject to the LogikSuite Framework license
 * 	that is available at the following file: LICENSE.md
 * 	If you did not receive a copy of the LogikSuite Framework License and
 * 	are unable to obtain it through the web, please send a note to
 * 	support@intelogie.com so we can mail you a copy immediately.
 *
 *	@package 	LogikSuite
 * 	@author 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	INTELOGIE.COM INC.
 * 	@license 	LICENSE.md
 */
class Theme {

    const THEME_TOKEN_HTML_TAG = "html_tag";
    const THEME_TOKEN_HEAD_TAG = "head_tag";
    const THEME_TOKEN_TITLE = "title";
    const THEME_TOKEN_HEAD_TOP = "head_top";
    const THEME_TOKEN_HEAD_BOTTOM = "head_bottom";
    const THEME_TOKEN_BODY_CLASS = "body_class";
    const THEME_TOKEN_BODY_TAG = "body_tag";
    const THEME_TOKEN_BODY_TOP = "body_top";
    const THEME_TOKEN_BODY_BOTTOM = "body_bottom";
    const THEME_TOKEN_PAGE_CONTENT = "page_content";
    const THEME_TOKEN_SCRIPT_BOTTOM = "script_bottom";
    const THEME_TOKEN_SCRIPT_ONREADY_BOTTOM = "script_onready_bottom";
    const THEME_TOKEN_CSS_TAGS = "css_tags";
    const THEME_TOKEN_SCRIPT_TAGS = "script_tags";
    const THEME_TOKEN_LOGO = "logo";
    const THEME_TOKEN_DESCRIPTION = "description";
    const THEME_TOKEN_AUTHOR = "author";
    const THEME_TOKEN_KEYWORDS = "keywords";
    const THEME_TOKEN_BREADCRUMBS = "breadcrumbs";
    const THEME_TOKEN_TOOLBAR_ITEMS = "toolbar_items";
    const THEME_TOKEN_USER_MENU = "user_menu";

    /**
     * @var null
     */
    protected static $_sThemeBasePath = NULL;
    /**
     * @var array
     */
    protected static $_aScriptTags = [];
    /**
     * @var array
     */
    protected static $_aCSSTags = [];
    /**
     * @var array
     */
    protected static $_aTokens = [];
    /**
     * @var array
     */
    protected static $_aBreadcrumbs = [];
    /**
     * @var null
     */
    protected static $_sCurrentView = NULL;
    /**
     * @var array
     */
    protected static $_aToolbarItems = [];
    /**
     * @var null
     */
    protected static $_oThemeSettings = NULL;
    /**
     * @var array
     */
    protected static $_aUserMenuItems = [];

    /**
     * Initialize theme
     */
    public static function initialize() {

        Log::debug("Theme::initialize");

        static::$_oThemeSettings = new ThemeSettings();
        static::$_oThemeSettings->initialize();

        static::setToken(Theme::THEME_TOKEN_LOGO, config("framework.logo", "/vendor/framework/images/logiksuite_full.png"));
        static::_loadUserMenu();

    }

    protected static function _loadUserMenu() {

        $aMenuItems = config("framework.userMenu", []);

        foreach($aMenuItems as $aItem) {

            $oMenuItem = new MenuItem();
            $oMenuItem->setLabel(array_get($aItem, "Label"));
            $oMenuItem->setIcon(array_get($aItem, "Icon"));
            $oMenuItem->setHref(array_get($aItem, "Href"));

            Theme::addUserMenu($oMenuItem);

        }


    }

    /**
     * @param MenuItem $oItem
     */
    public static function addUserMenu(MenuItem $oItem) {
        static::$_aUserMenuItems[] = $oItem;
    }

    /**
     * @return array
     */
    public static function getUserMenuItems() {
        return static::$_aUserMenuItems;
    }

    /**
     * @param $oComponent
     */
    public static function addToolbarItem($oComponent) {
        static::$_aToolbarItems[] = $oComponent;        
    }

    /**
     * @param Breadcrumb $oBreadcrumb
     */
    public static function addBreadcrumb(Breadcrumb $oBreadcrumb) {
        static::$_aBreadcrumbs[] = $oBreadcrumb;
    }

    /**
     * Returns list of breadcrumbs
     * @return array Breadcrumbs
     */
    public static function getBreadcrumbs() {
        return static::$_aBreadcrumbs;
    }

    /**
     * Returns theme base path
     */
    public static function getThemeBasePath() {
        return static::$_sThemeBasePath;
    }

    /**
     * Sets theme base path
     */
    public static function setThemeBasePath($sBasePath) {
        
        if ($sBasePath == "") {
            return FALSE;
        }

        static::$_sThemeBasePath = \base_path($sBasePath);

    }

    /**
     * Add Script tag to page
     */
    public static function addScriptTag($sScriptSrc, $sType = "text/javascript") {
        static::$_aScriptTags[$sScriptSrc] = [
            "Src" => $sScriptSrc,
            "Type" => $sType
        ];        
    }

    /**
     * @return array Script tags
     */
    public static function getScriptTags() {
        return static::$_aScriptTags;
    }
   

    /**
     * Set token value
     */
    public static function setToken($sTokenKey, $mValue) {

        $mValue = static::_getValueForToken($sTokenKey, $mValue);
        static::$_aTokens[$sTokenKey] = $mValue;

    }

    /**
     * Returns value for a token depending on token we want
     * @param $sTokenKey
     * @param $mValue
     * @return mixed
     */
    protected static function _getValueForToken($sTokenKey, $mValue) {

        if (is_object($mValue)) {
            switch($sTokenKey) {
                case Theme::THEME_TOKEN_PAGE_CONTENT:
                    return $mValue->render()["HTML"];
                    break;
                case Theme::THEME_TOKEN_SCRIPT_BOTTOM:
                    return $mValue->render()["JS"];
                    break;
                case Theme::THEME_TOKEN_SCRIPT_ONREADY_BOTTOM:
                    return $mValue->render()["JS"];
                    break;
            }
        } else {
            return $mValue;
        }

    }

    /**
     * Append value to token
     */
    public static function appendToken($sTokenKey, $mValue) {

        $mValue = static::_getValueForToken($sTokenKey, $mValue);

        if (!array_has(static::$_aTokens, $sTokenKey)) {
            static::$_aTokens[$sTokenKey] = $mValue;
        } else {
            static::$_aTokens[$sTokenKey] .= $mValue;
        }
        
    }

    /**
     * Set page content value
     * @param $sValue
     */
    public static function setContent($sValue) {
        static::setToken(Theme::THEME_TOKEN_PAGE_CONTENT, $sValue);
    }

    /**
     * Append value to a page content
     * @param $mValue
     */
    public static function appendContent($mValue) {
        static::appendToken(Theme::THEME_TOKEN_PAGE_CONTENT, $mValue);
    }

    public static function getToolbarItems() {
        return static::$_aToolbarItems;
    }

    /**
     * Returns token array
     */
    public static function getTokens() {

        $oRef = new \ReflectionClass("Framework\\Modules\\UI\\Theme");

        // Set all tokens to empty if they do not exist
        foreach($oRef->getConstants() as $sConstName => $sConstValue) {
            if (!array_has( static::$_aTokens, $sConstValue)) {
                static::$_aTokens[$sConstValue] = "";
            }
        }

        /**
         * Append all toolbar items to bottom script
         */
        foreach(static::getToolbarItems() as $oItem) {
            Theme::appendToken(Theme::THEME_TOKEN_SCRIPT_ONREADY_BOTTOM, $oItem->render()["JS"]);
        }

        /**
         * Special tokens
         */
        static::$_aTokens[Theme::THEME_TOKEN_CSS_TAGS] = static::_renderCSSTags();
        static::$_aTokens[Theme::THEME_TOKEN_SCRIPT_TAGS] = static::_renderScriptTags();
       

        // If breadcrums are supported
        if (static::$_oThemeSettings->isSupportedFeature(static::$_sCurrentView, ThemeSettingsBase::FEATURE_BREADCRUMBS)) {
            static::$_aTokens[Theme::THEME_TOKEN_BREADCRUMBS] = static::$_oThemeSettings->renderBreadcrumbs(static::getBreadcrumbs());
        }

        if (static::$_oThemeSettings->isSupportedFeature(static::$_sCurrentView, ThemeSettingsBase::FEATURE_TOOLBAR_ITEMS)) {
            static::$_aTokens[Theme::THEME_TOKEN_TOOLBAR_ITEMS] = static::renderToolbarItems();
        }

        if (static::$_oThemeSettings->isSupportedFeature(static::$_sCurrentView, ThemeSettingsBase::FEATURE_USERMENU)) {
            static::$_aTokens[Theme::THEME_TOKEN_USER_MENU] = static::$_oThemeSettings->renderComponent(ThemeSettingsBase::FEATURE_USERMENU, static::getUserMenuItems());
        }


        return static::$_aTokens;
    }

    /**
     * @return string Render all toolbar items
     */
    public static function renderToolbarItems() {
        $s = "";
        foreach(static::$_aToolbarItems as $oComponent) {
            $s .= $oComponent->render()["HTML"];
        }
        return $s;
    }

     /**
     * Add csstag to page
     */
    public static function addCSSTag($sUrl, $sType = "text/css", $sRel = "stylesheet", $sMedia = "", $aAttributes = NULL) {
        static::$_aCSSTags[$sUrl] = [
            "Url" => $sUrl,
            "Type" => $sType,
            "Rel" => $sRel,
            "Media" => $sMedia,
            "Attributes" => $aAttributes
        ];
    }

    /**
     * @return array List of CSS tags to include in page
     */
    public static function getCSSTags() {
        return static::$_aCSSTags;
    }

    /**
     * Generate link tags for css files
     */
    protected static function _renderCSSTags() {

        $sCssTags = "<!-- Theme CSS Tags -->" . PHP_EOL;
        foreach(Theme::getCSSTags() as $k => $aCSSTag) {
            $sCssTags .= "<link rel=\"" . $aCSSTag["Rel"] . "\" href=\"" . Url::url($aCSSTag["Url"]) . "\"";
            if (is_array($aCSSTag["Attributes"])) {
                foreach($aCSSTag["Attributes"] as $sKey => $sValue) {
                    $sCssTags .= " " . $sKey . "=\"" . $sValue . "\"";
                }
            }
            $sCssTags .= ">" . PHP_EOL;
        }        
        $sCssTags .= "<!-- End of Theme CSS Tags -->" . PHP_EOL;

        return $sCssTags;

    }

    /**
     * Generate script tags for script files
     */
    protected static function _renderScriptTags() {

        $sScriptTags = "<!-- Theme Script Tags -->" . PHP_EOL;

        foreach(Theme::getScriptTags() as $k => $aScriptTag) {
            $sScriptTags .= "<script type=\"" . $aScriptTag["Type"] . "\" src=\"" . Url::url($aScriptTag["Src"]) . "\"></script>" . PHP_EOL;
        }

        $sScriptTags .= "<!-- End of Theme Script Tags -->" . PHP_EOL;

        return $sScriptTags;

    }

    /**
     * Returns specified view
     */
    public static function getView($sViewName = NULL) {

        if ($sViewName === NULL) {
            /**
             * @todo put this in framwork config file
             */
            $sViewName = "theme::index";
        }

        static::$_sCurrentView = $sViewName;
        return view(static::$_sCurrentView, static::getTokens());

    }


}

?>