<?php 
namespace Framework\Modules\UI;

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
abstract class ThemeSettingsBase {

    const FEATURE_LOGIN_PAGE = 'login_page';
    const FEATURE_REGISTER_PAGE = 'register_page';
    const FEATURE_BREADCRUMBS = 'breadcrumbs';
    const FEATURE_SIDEBAR_LEFT = 'sidebar_left';
    const FEATURE_SIDEBAR_RIGHT = 'sidebar_right';
    const FEATURE_STATUSBAR = 'statusbar';
    const FEATURE_USERMENU = 'user_menu';
    const FEATURE_TOPMENU = 'top_menu';
    const FEATURE_LOGO = 'logo';
    const FEATURE_TOOLBAR_ITEMS = 'toolbar_items';


    protected $_aSupportedFeatures = NULL;

    abstract public function getSupportedFeatures($sView);
    abstract public function renderComponent($sFeature, $data);
    

    /**
     * Check if current view supports a feature
     */
    public function isSupportedFeature($sView, $sFeature) {

        return $this->getSupportedFeatures($sView)[$sFeature];
    }

    /**
     * Renders breadcrumb using template css and structure
     * aItem [
     *  Classes => "List of additional classes for example active",
     *  Text => "Text of the breadcrumb",
     *  Link => "Url for the breadcrumb"
     * ]
     */
    public function renderBreadcrumbs($aBreadcrumbs) {

        return "";

    }

    /**
     * Returns login view file name
     */
    public function getLoginView() {
        return "login.blade.php";
    }

    /**
     * Returns register form view file name
     */
    public function getRegisterView() {
        return "register.blade.php";
    }

    /**
     * Returns main view file name
     */
    public function getMainView() {
        return "index.blade.php";
    }

    /**
     * Initialize theme template
     */
    public function initialize() {

    }

}

?>