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
Framework.modules.Navigation = {
	
	/**
	 * User's home page
	 */
	_homePage : null,
	
	/**
	 * The method used to call the current page GET / POST
	 */
	_requestVerb : null,
		
	/**
	 * Url prefix to prepend, node name
	 */
	_urlPrefix : null,
	
	/**
	 * Indiates if this page has been processed by a reverse proxy
	 */
	_isRedirect : false,
	
	/**
	 * Current request controller
	 */
	_requestController : null,
	
	/**
	 * Current Request action 
	 */
	_requestAction : null,
	
	/**
	 * Redirects to user's home page
	 */
	gotoHomePage : function() {
		this.redirect(this._homePage);
	},

	/**
	 * Define current page as the user's default home page
	 */
	setHomePage : function() {
		Framework.modules.Ajax.execute('/User/setHomePage', {page: document.location.pathname}, function(response) {
			Framework.modules.Ajax.processResponse(response);
		});	
	},

    cancelHomePage : function() {
        Framework.modules.Ajax.execute('/User/setHomePage', {page: ''}, function(response) {
            Framework.modules.Ajax.processResponse(response);
        });
    },
	
	/**
	 * Reload current page is action is GET
	 * @todo If post must confirm before reloading page
	 */
	reloadPage : function() {
		if (Framework.modules.Navigation._requestVerb == 'GET') {
			document.location.href = document.location.href;
		} else {
			// Confirm page reload
			
		}
	},
	
	/**
	 * Calls specified controller using ajax
	 */
	callController : function(sUrl, aData, sConfirmMessage) {
        if (typeof aData == 'undefined')
        {
            aData = {};
        }

		if (typeof sConfirmMessage != 'undefined') {
			Framework.modules.Dialog.confirmDelete(Framework.modules.Localization.get(sConfirmMessage), function(result) {

				if (result) {
					Framework.modules.Ajax.execute(Framework.modules.Navigation.url(sUrl), aData, function(data) {
						Framework.modules.Ajax.processResponse(data);
					});
				}
	
			}); 
		} else {


			Framework.modules.Ajax.execute(Framework.modules.Navigation.url(sUrl), aData, function(data) {
				Framework.modules.Ajax.processResponse(data);
			});

		}
		
	},
	
	forceRedirect : function(sUrl, bNewWindow) {
		
		if (bNewWindow == undefined) {
			bNewWindow = false;
		}
		
		this.redirect(sUrl, bNewWindow, true);
	},
	
	/**
	 * Redirect page to sUrl
	 */
	redirect : function (sUrl, bNewWindow, bForce) {

		if (bNewWindow == undefined) {
			bNewWindow = false;
		}
		
		if (bForce == undefined) {
			bForce = false;
		}



		if (Framework.modules.UI._lastEvent != null) {
			if (Framework.modules.UI._lastEvent.ctrlKey) {
				bNewWindow = true;
			}	
		}
		
		
		if (bNewWindow) {
			return window.open(Framework.modules.Navigation.url(sUrl));			
		} else {
			
			if (Page != undefined && Page.isDirty && !bForce) {
				
				var sPageUrl = sUrl;
				Framework.modules.Dialog.confirm('_PAGEISDIRTY', function(result) {
					if (result) {
					    Page.isDirty = false;
						document.location.href = Framework.modules.Navigation.url(sPageUrl);
					}
				});
				
			} else {
			    // Force redirect
                Page.setIsDirty(false);
				document.location.href = Framework.modules.Navigation.url(sUrl);	
			}
			
			
		}
	},

	/**
	 * Redirect using post
	 */
	redirectPost: function(location, args, newWindow) {

		if (newWindow == undefined) {
			newWindow = false;
		}

		var sTarget = '';
		if (newWindow) {
			sTarget = ' target="_blank"';
		}
		
        var form = '';
        jQuery.each( args, function( key, value ) {
        	if (value) {
        		if (typeof value === 'object') {
        			value = btoa(JSON.stringify(value));
        		} else if (typeof value === 'string') { 
        			value = value.split('"').join('\"');
        		}
        	}
            form += '<input type="hidden" name="' + key + '" value="' + value + '">';
        });
        
        jQuery('<form action="' + Framework.modules.Navigation.url(location) + '" method="POST"' + sTarget +'>' + form + '</form>').appendTo(jQuery(document.body)).submit();
    },
	
    /**
     * Rebuilds url based on reverse proxy of page
     */
    url : function(sUrl) {
    	
    	if (sUrl == null) {
    		return '';
    	}
    	
    	if (this._isRedirect) {
    		/**
    		 * Exclude external http
    		 */
	    	if (sUrl.indexOf('http') == 0) {
				return sUrl;
			}
	    	/**
    		 * Exclude javascript: calls
    		 */
			if (sUrl.indexOf('javascript') == 0) {
				return sUrl;
			}		
			/**
    		 * Exclude mailto links
    		 */
			if (sUrl.indexOf('mailto') == 0) {
				return sUrl;
			}
			/**
    		 * Exclude tel: link
    		 */
			if (sUrl.indexOf('tel') == 0) {
				return sUrl;
			}
			/**
    		 * Exclude Framework calls
    		 */
	    	if (sUrl.indexOf('Framework.') == 0) {
				return sUrl;
			}
			
			/**
			 * Check if the urls already starts with prefic
			 */
			if (sUrl.indexOf(this._urlPrefix) == 0) {
				return sUrl;
			} else {
				return this._urlPrefix + sUrl;
			}
    	} else {
    		/**
    		 * Not a redirection, return original link
    		 */
    		return sUrl;
    	}
		
    },
    
    /**
     * Removed prefix / node name from url
     */
    clean_url : function(sUrl) {
       	return sUrl.replace(this._urlPrefix, '');
    }, 
    
    /**
     * Replace parameter in Url with new value
     */
    replaceUrlParameter: function(sUrl, parameterName, value) {

    	var newAdditionalURL = "";
        var tempArray = sUrl.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            for (i=0; i < tempArray.length; i++){
                if(tempArray[i].split('=')[0] != parameterName){
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }

        var rows_txt = temp + "" + parameterName + "=" + value;
        return baseURL + "?" + newAdditionalURL + rows_txt;

    },
    
    /**
     * Change current menu
     */
    changeMenu : function(roleCode) {

		Framework.modules.Ajax.execute('/Menu/changeMenu/' + roleCode, {}, function(data) {
			Framework.modules.Ajax.processResponse(data);			
		});

	},

	rewrite : function(sNewUrl) {
        window.history.pushState("", "", this.url(sNewUrl));
	},

};