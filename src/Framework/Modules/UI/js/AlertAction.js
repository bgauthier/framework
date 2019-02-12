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
function AlertAction() {

    this.sMessage = null;
    this.sSize = 'medium';

    this.loadProperties = function(aProps) {
        for (var i = 0; i < aProps.length; i++) {
            if (aProps[i].Name == 'Message') {
                this.sMessage = aProps[i].Value;
            } else if (aProps[i].Name == 'Size') {
                this.sSize = aProps[i].Value;
            }
        }

    };

    this.execute = function(AjaxCallID) {
        Framework.modules.Dialog.alert(this.sMessage, function() {
            Framework.modules.Ajax.processNextAction(AjaxCallID);
        }, this.sSize);
    };

};