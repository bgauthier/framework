/**
 *
 *
 * LICENSE: This source file is subject to the LogikBuild license
 * that is available at the following URI:
 * src/Framework/LICENSE.md. If you did not receive a copy of
 * the LogikBuild License and are unable to obtain it through the web, please
 * send a note to support@intelogie.com so we can mail you a copy immediately.
 *
 * @package LogikBuild
 * @subpackage Core
 * @author Benoit Gauthier bgauthier@intelogie.com
 * @copyright Benoit Gauthier bgauthier@intelogie.com
 * @copyright INTELOGIE.COM INC.
 * @license src/Framework/LICENSE.md
 */
function JQueryAction() {

    this.sCode = null;

    this.loadProperties = function(aProps) {
        for (var i = 0; i < aProps.length; i++) {
            if (aProps[i].Name == 'Code') {
                this.sCode = aProps[i].Value;
            }
        }

    };

    this.execute = function(AjaxCallID) {
        if (this.sCode != null) {
            eval(decodeURIComponent(atob(this.sCode)));
        }
        Framework.modules.Ajax.processNextAction(AjaxCallID);
    };

};