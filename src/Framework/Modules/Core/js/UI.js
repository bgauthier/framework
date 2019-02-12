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
Framework.modules.UI = {

    /**
     * The last keyboard event
     */
    _lastEvent : null,

    /**
     * Timer for delay event
     */
    _delayTimer : 0,


    /**
     * Initialize module
     */
    initialize : function() {

        jQuery(window).on('keydown', function(e) {Framework.modules.UI._lastEvent = e;});
        jQuery(window).on('keyup', function(e) {Framework.modules.UI._lastEvent = e;});


    },


    /**
     * Delay execution of a function until user has stopped typing
     */
    delay : function(callback, ms, data){
        if (Framework.modules.UI._delayTimer != 0) {
            clearTimeout (Framework.modules.UI._delayTimer);
        }
        Framework.modules.UI._delayTimer = 0;
        Framework.modules.UI._delayTimer = setTimeout(callback, ms, data);
    },



};