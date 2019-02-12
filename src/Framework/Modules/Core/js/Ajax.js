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
Framework.modules.Ajax = {
	
	/**
	 * List of active response actions
	 */
	_responseActions : null,
	/**
	 * List of response Data
	 */
	_responseData : null, 
	/**
	 * Current response action being processed
	 */
	_currentResponseAction : 0, 	
	/**
	 * CSRF token for validation
	 */
	_csrfToken : null,
	/**
	 * Request UID 
	 */
	_requestID : 1,
	/**
	 * Responses
	 */
	_aResponses : [],

    /**
     * Indicates that the request can take some time to execute
     */
    _bIsLongOperation : false,

	initialize : function() {
    	console.log('Initialize ajax module');
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

	},

		
	executeWithLoadMask : function (url, params, callback, msg, icon) {

	    if (this._bIsLongOperation) {
	        msg = msg + '<br/>' + Framework.modules.Localization.get('_POTENTIALLONGOPERATION');
        }

		Framework.modules.Dialog.hideLoadMask();
		Framework.modules.Dialog.showLoadMask(msg, icon);
		
		Framework.modules.Ajax.execute(url, params, callback, function() {
			Framework.modules.Dialog.hideLoadMask();			
		});
		
	},
		
	executeAsync : function (url, params, callback, completedCallback) {
		return this.execute(url, params, callback, completedCallback, true);
	},
		
	execute : function (url, params, callback, completedCallback, async, nTimeout) {

		async = (async == undefined) ? true : async;
		nTimeout = (nTimeout == undefined) ? 60000 : (nTimeout * 1000);

		if (this._bIsLongOperation) {
            nTimeout = 600000;
        }
					
		if (params == null || params == undefined) {
			params = {
				'ajaxToken' : Framework.modules.Ajax._csrfToken
			};
		} else {
			params['ajaxToken'] = Framework.modules.Ajax._csrfToken;
		}
		
		params['AjaxCallID'] = Framework.modules.Utilities.uniqid();
		
		jQuery.ajax({
			type: 'POST',
			url: Framework.modules.Navigation.url(url),
			dataType: 'json',
			async : async,
			timeout : nTimeout,
			data: params,
			success : function ( response ) {
                if (callback == undefined) {
                    Framework.modules.Ajax._defaultCallback(response);
                } else {
                    callback(response);
                }
            },
			error : function(data, s, e) {

                if(data.readyState == 0 || data.status == 0) {
                    return;  // it's not really an error, page reload
                }

                Framework.modules.Dialog.hideLoadMask();
                console.log('Error in ajax call [' + s + '] [' + e + ']');
                console.log(data);
                console.log(s);
                console.log(e);
                //if (Framework.modules.Debug._bDebugConsole) {
                //    alert('Error in ajax call [' + s + '] [' + e + ']');
                //}

            },
			complete : function(data, s) {
                if (jQuery.isFunction(completedCallback)) {
                    completedCallback();
                }
            }
		});
		
		//return params['AjaxCallID'];
		
	},
	
	_defaultCallback : function(response) {
		Framework.modules.Ajax.processResponse(response);
	},
	
	getResponse : function(AjaxCallID) {
		
		return Framework.modules.Ajax._aResponses[AjaxCallID];
		
	},

	/**
	 * Process Ajax response
	 */
	processResponse : function (response) {

		if (response.status == 1) {
			
			Framework.modules.Ajax._aResponses[response.AjaxCallID] = {
				timestamp : moment(),
				actions : response.actions,
				data : response.data,
				currentResponseAction : 0,
				isCompleted : false,
			};
			
			Framework.modules.Ajax._currentResponseAction = 0;
			Framework.modules.Ajax._responseActions = response.actions;						
			Framework.modules.Ajax._responseData = response.data;
			Framework.modules.Ajax.processNextAction(response.AjaxCallID);			

		} else {
			// Invalid Status
		}
		
	},
	
	/**
	 * Process next action received in ajax call
	 */
	processNextAction: function(AjaxCallID) {

		if (Framework.modules.Ajax.getResponse(AjaxCallID).actions instanceof Array) {
			if ( Framework.modules.Ajax.getResponse(AjaxCallID).actions.length > Framework.modules.Ajax.getResponse(AjaxCallID).currentResponseAction ) {
				var sClassName = Framework.modules.Ajax.getResponse(AjaxCallID).actions[Framework.modules.Ajax.getResponse(AjaxCallID).currentResponseAction].ActionType;
				var oAction = new window[sClassName];
				Framework.modules.Logging.debug('Processing action ' + sClassName);
				oAction.loadProperties( Framework.modules.Ajax.getResponse(AjaxCallID).actions[Framework.modules.Ajax.getResponse(AjaxCallID).currentResponseAction].Properties);
				Framework.modules.Ajax.getResponse(AjaxCallID).currentResponseAction++;
	
				oAction.execute(AjaxCallID);
			} else {
				Framework.modules.Ajax.getResponse(AjaxCallID).isCompleted = true;
			}
		}
		
	},
		
};