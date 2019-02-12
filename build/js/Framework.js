var Framework = {

    /**
	 * Array of initiailize callback
	 */
	_onInitialize : [],

    initialize : function() {

        Framework.modules.Logging.debug('============================================================');
		Framework.modules.Logging.debug('Framework initialize');

        /**
		 * Call initialize on modules, if the function exists
		 */
		for(var x in Framework.modules) {
			if (typeof Framework.modules[x].initialize == "function") {
				Framework.modules.Logging.debug('Framework calling initialize on module ' + x);
				Framework.modules[x].initialize();
			} 
			
        }
        
        /**
		 * Call other initialize scripts
		 */
		for(var i = 0; i < this._onInitialize.length; i++) {
			this._onInitialize[i]();
		}

    },

    /**
	 * Adds a callback to the list of callbacks to execute on framwork init
	 */
	onInitialize : function(callback) {
		if (typeof callback == "function") {
			this._onInitialize[this._onInitialize.length] = callback;
		} else {
			Framework.modules.Logging.error('Callback is not a valid function');
			Framework.modules.Logging.dir(callback);
		}
	},

    /**
     * List of framework modules
     */
    modules : {}

};

Framework.modules.Logging = {


    debug : function(sMessage) {
        console.debug(sMessage);
    },

    log : function(sMessage) {
        console.log(sMessage);
    },

    warn : function(sMessage) {
        console.warn(sMessage);
    },

    error : function(sMessage) {
        console.error(sMessage);
    },

    clear : function(sMessage) {
        console.clear();
    },

    group : function(sTitle) {
        console.group(sTitle);
    },

    groupEnd : function() {
        console.groupEnd();
    },

    dir : function(o) {
        console.dir(o);
    },

    info : function(sMessage) {
        console.info(sMessage);
    },

    profile : function(sName) {
        console.profile(sName);
    },

    profileEnd : function(sName) {
        console.profileEnd(sName);
    }

};
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
Framework.modules.Dialog = {
		
	_id : 0,
		
	_activeWindowFields : [],
	
	/**
	 * Displays a load mask
	 */
	_loadMask : null,
	/**
	 * 
	 */
	_formParameter : null,
	/**
	 * 
	 */
	_activeDialog : null,
	/**
	 * 
	 */
	_formDialogCallback : null,
	/**
	 * Dialog variables
	 */
	_vars : {},
	/**
	 * 
	 */
	_Stacks : {
		      stack_top_right: {
		        "dir1": "down",
		        "dir2": "left",
		        "push": "top",
		        "spacing1": 10,
		        "spacing2": 10
		      },
		      stack_top_left: {
		        "dir1": "down",
		        "dir2": "right",
		        "push": "top",
		        "spacing1": 10,
		        "spacing2": 10
		      },
		      stack_bottom_left: {
		        "dir1": "right",
		        "dir2": "up",
		        "push": "top",
		        "spacing1": 10,
		        "spacing2": 10
		      },
		      stack_bottom_right: {
		        "dir1": "left",
		        "dir2": "up",
		        "push": "top",
		        "spacing1": 10,
		        "spacing2": 10
		      },
		      stack_bar_top: {
		        "dir1": "down",
		        "dir2": "right",
		        "push": "top",
		        "spacing1": 0,
		        "spacing2": 0
		      },
		      stack_bar_bottom: {
		        "dir1": "up",
		        "dir2": "right",
		        "spacing1": 0,
		        "spacing2": 0
		      },
		      stack_context: {
		        "dir1": "down",
		        "dir2": "left",
		        "context": $("#stack-context")
		      }
		    },
	
		    
	initialize : function() {
		
		bootbox.setLocale(Framework.modules.Localization._currentLanguageCode);
		
		/**
		 * Bug fix padding-right bootstrap 3.2
		 */
		if ($.fn.modal != undefined) {
			var oldSSB = $.fn.modal.Constructor.prototype.setScrollbar;
	        $.fn.modal.Constructor.prototype.setScrollbar = function () 
	        {
	            oldSSB.apply(this);
	            $('body').css('padding-right', '');
	            if(this.bodyIsOverflowing && this.scrollbarWidth) 
	            {
	                $('.navbar-fixed-top, .navbar-fixed-bottom').css('padding-right', this.scrollbarWidth);
	            }       
	        };
	
	        var oldRSB = $.fn.modal.Constructor.prototype.resetScrollbar;
	        $.fn.modal.Constructor.prototype.resetScrollbar = function () 
	        {
	            oldRSB.apply(this);
	            $('.navbar-fixed-top, .navbar-fixed-bottom, body').css('padding-right', '');
	        };
		}
		
	},
		    
		
	/**
	 * Displays alert dialog
	 */	
	alert: function(msg, oCallback, sSize, sTitle) {
		
		msg = Framework.modules.Localization.get(msg);
		
		if (sSize == undefined) {
			sSize = 'small';
		}
		
		if (sTitle == undefined) {
			sTitle = Framework.modules.Localization.get('_WARNING');
		}
		
		msg = msg.replace(/(?:\r\n|\r|\n)/g, '<br/>');

		bootbox.alert({
			title : sTitle,
			message: msg,
			callback: oCallback,
			size: sSize 
		});
		
	}, 

	/**
	 * Displays confirmation message box
	 */
	confirm: function(msg, callback) {	
		msg = Framework.modules.Localization.get(msg);
		msg = msg.replace(/(?:\r\n|\r|\n)/g, '<br/>');			
		return bootbox.confirm(msg, callback);
	}, 
	
	confirmYesNoCancel : function(msg, callback) {
		msg = Framework.modules.Localization.get(msg);
		msg = msg.replace(/(?:\r\n|\r|\n)/g, '<br/>');			
		return bootbox.dialog({
			message: msg,			
			closeButton : false,
			buttons: {
			        btnYes : {
			            label: Framework.modules.Localization.get('_YES'),
			            className: 'btn-primary',
			            callback : function() {
				           	return callback.call(this, true, false);
				        }	
			        },
			        btnNo : {
			            label: Framework.modules.Localization.get('_NO'),
			            className: 'btn-danger',
			            callback : function() {
				           	return callback.call(this, false, false);
				        }
			        },
			        btnCancel : {
			            label: Framework.modules.Localization.get('_CANCEL'),
			            className: 'btn-default',
			            callback : function() {
			            	return callback.call(this, false, true);
			            }
			        }
			},
			callback : callback 
		});
		
	},

	
	/**
	 * Displays a custom dialog
	 */	
	customDialog : function(msg, title, cancel, confirm, callbackOK) {
		msg = Framework.modules.Localization.get(msg);
		msg = msg.replace(/(?:\r\n|\r|\n)/g, '<br/>');
		
		
		
		return bootbox.dialog(
		 {
		    message : msg,
		    buttons :
		    {
		        danger :
		        {
			        className: "btn-default",
		            label : cancel
		        },
		        success :
		        {
			        className: "btn-primary",
		            label : confirm,
		            callback : callbackOK
		        }
		    }
		 });
	},
	
	/**
	 * Displays confirm deletion message box
	 */
	confirmDelete: function(msg, callback) {	
		msg = Framework.modules.Localization.get(msg);
		msg = msg.replace(/(?:\r\n|\r|\n)/g, '<br/>');		
		return bootbox.confirm(msg, callback);
	}, 
	
	confirmDeleteWithValidation : function(msg, confirmMessage, callback) {
		msg = Framework.modules.Localization.get(msg);
		msg = msg + '\n\n' + '<input name="_confirmCheckbox" id="_confirmCheckbox" type="checkbox"/><label for="_confirmCheckbox">&nbsp;' + confirmMessage + '</label>';
		
		msg = msg.replace(/(?:\r\n|\r|\n)/g, '<br/>');		
		return bootbox.confirm(msg, callback);
	},

	/**
	 * Displays prompt message box
	 */
	prompt: function(msg, callbackFnc, defaultValue) {
		msg = Framework.modules.Localization.get(msg);
		msg = msg.replace(/(?:\r\n|\r|\n)/g, '<br/>');	
		
		return bootbox.prompt({
			title : msg,
			value: defaultValue, 
			callback : callbackFnc}
		);
		
	},
	
	promptTextArea : function(msg, callbackFnc, defaultValue) {
		msg = Framework.modules.Localization.get(msg);
		msg = msg.replace(/(?:\r\n|\r|\n)/g, '<br/>');	
		
		return bootbox.prompt({
			title : msg,
			inputType: 'textarea',
			value: defaultValue, 
			callback : callbackFnc
			});
		
	},
	
	buildDialogFields : function(jqEl) {

		var aItems = {};
		jqEl.each(function() {
			aItems[jQuery(this).attr('id')] = window[jQuery(this).attr('id')];
		});
		
		this._activeWindowFields = aItems;

		return aItems;
		
	},
	
	buildSubmitFields : function(jqEl) {
		var aItems = {};
		jqEl.each(function() {
			var sID = jQuery(this).attr('id');
			if (jQuery(this).attr('ctlPrefix') != null && jQuery(this).attr('ctlPrefix') != undefined) {
				sID = sID.replace(jQuery(this).attr('ctlPrefix') + '_','', sID);				
			}
			if (typeof window[jQuery(this).attr('id')].getValue == 'function') {
				aItems[sID] = window[jQuery(this).attr('id')].getValue();
			}
		});
		
		this._activeWindowFields = aItems;

		return aItems;
	},
	
	releaseDialogFields : function() {
		
		/**
		 * Clean up CKEditors
		 */
		//if (CKEDITOR != undefined) {
		//	for(var name in CKEDITOR.instances) {
		//	    CKEDITOR.instances[name].destroy(true);
		//	}
		//}
		
		for(var x in this._activeWindowFields) {
			delete this._activeWindowFields[x];
			this._activeWindowFields[x] = undefined;
			window[x] = undefined;
		}
		
	},

	getNextID : function() {
		this._id--;
		return this._id;
	},
	
	buildDialogRecord : function(jqEl) {

		var aItems = {};
		
		if (Framework.modules.Dialog._activeDialog.objectInstance != null && Framework.modules.Dialog._activeDialog.objectInstance != undefined) {
			aItems = Framework.modules.Dialog._activeDialog.objectInstance;
		} else {
			aItems['ID'] = this.getNextID();
		}
		
		jqEl.each(function() {
			if (window[jQuery(this).attr('id')].mappingField != '' && window[jQuery(this).attr('id')].mappingField != null || window[jQuery(this).attr('id')].id == 'objectID') {
				
				var sFieldName = window[jQuery(this).attr('id')].mappingField;
				sFieldName = sFieldName.replace(/_/g, '');
				console.log(sFieldName);
				if (window[jQuery(this).attr('id')].isMultilingual) {
					aItems[sFieldName] = {
						fr : window[jQuery(this).attr('id')].getValue('fr'),
						en : window[jQuery(this).attr('id')].getValue('en'),
					};				
				} else {
					aItems[sFieldName] = window[jQuery(this).attr('id')].getValue();
				}
				if (window[jQuery(this).attr('id')].type == 'Framework\\Modules\\UI\\Bootstrap\\SelectInput') {
					aItems[sFieldName + 'DisplayValue'] = window[jQuery(this).attr('id')].getRawValue();
					aItems[sFieldName + 'DisplayValue_' + Framework.modules.Localization._currentLanguageCode] = window[jQuery(this).attr('id')].getRawValue();
				}
			}
		});
		
		
		return aItems;
		
	},

	closeActiveDialog : function() {

		if (this._activeDialog != null) {

			this._activeDialog.modal('hide');
			this._activeDialog = null;
			
		}
		
	},
	
	showDialog: function (options) {

		//this._formParameter = oOptions;


		if (options.buttons == undefined) {
		
			aButtons = {
				btnOk : {
					label : Framework.modules.Localization.get('_OK'),
					className: 'btn-primary',
					callback: function() {

						var bResult = true;
						var sFormID = jQuery('[role="form"][id^="' + options.dialogID + '_"]').attr('id');
						var sOnDialogOKJS = options.onDialogOk;

						jQuery('body').css('overflow-y','auto');
						jQuery('body').css('overflow-x','hidden');
						
						/**
						 * Check validations
						 */ 
						if (window[sFormID] != undefined) {
							if (window[sFormID].validateForm(false)) {
								bResult = true;
							} else {
								bResult = false;
							}
						} else {
							bResult = true;
						}

						if (bResult) {
							/**
							 * Call user defined OK code
							 */
                            if (sOnDialogOKJS != null && sOnDialogOKJS != undefined && sOnDialogOKJS != '') {

                                var oFnc = new Function('result', 'fields', 'rec', 'params', sOnDialogOKJS);
                                bResult = oFnc(
                                    true,
                                    Framework.modules.Dialog.buildDialogFields(jQuery('#' + options.dialogID + '_viewContainer .isSubmitCtl')),
                                    Framework.modules.Dialog.buildDialogRecord(jQuery('#' + options.dialogID + '_viewContainer .isSubmitCtl')),
                                    options.parameters
                                );

                            }

							if (Framework.modules.Dialog._activeDialog.dialogOkCallBack != null) {
							 	bResult = Framework.modules.Dialog._activeDialog.dialogOkCallBack(
										Framework.modules.Dialog.buildDialogFields(jQuery('#' + options.dialogID + '_viewContainer .isSubmitCtl')),
										Framework.modules.Dialog.buildDialogRecord(jQuery('#' + options.dialogID + '_viewContainer .isSubmitCtl')),
										options.parameters
								);
						     }
							 /**
							  * Call dialog close 
							  */
							 if (bResult) { 
								 Framework.modules.Dialog._activeDialog.dialogCallback(true, 
										Framework.modules.Dialog.buildDialogFields(jQuery('#' + options.dialogID + '_viewContainer .isSubmitCtl')),
										Framework.modules.Dialog.buildDialogRecord(jQuery('#' + options.dialogID + '_viewContainer .isSubmitCtl')),
										options.parameters,
										Framework.modules.Dialog.buildSubmitFields(jQuery('#' + options.dialogID + '_viewContainer .isSubmitCtl'))
								 );
							 }
						}

						
						jQuery('body').css('overflow-y','auto');
						jQuery('body').css('overflow-x','hidden');
						
						return bResult;
						
					}
				},
				btnCancel : {
					label : Framework.modules.Localization.get('_CANCEL'),
					className: 'btn-default',
					callback: function() {
						
						jQuery('body').css('overflow-y','auto');
						jQuery('body').css('overflow-x','hidden');
						
						if (Framework.modules.Dialog._activeDialog != null) {
							if (typeof Framework.modules.Dialog._activeDialog.dialogCallback != 'undefined') {
								Framework.modules.Dialog._activeDialog.dialogCallback(false, [], [], options.parameters);								
							} else {							
								jQuery('.modal').modal('hide');
							}
						} else {							
							jQuery('.modal').modal('hide');
						}
						jQuery('body').css('overflow-y','auto');
						jQuery('body').css('overflow-x','hidden');
						return true;
					}
				},
				btnDialogDelete : {
					id : 'btnDialogDelete', 
					label : Framework.modules.Localization.get('_DELETE'),
					className: 'btn-danger pull-left',
					callback: function() {
						
						jQuery('body').css('overflow-y','auto');
						jQuery('body').css('overflow-x','hidden');
						
						if (Framework.modules.Dialog._activeDialog.dialogDeleteCallBack != null) {
							Framework.modules.Dialog._activeDialog.dialogDeleteCallBack(true, 
									Framework.modules.Dialog.buildDialogFields(jQuery('#' + options.dialogID + '_viewContainer .isSubmitCtl')),
									Framework.modules.Dialog.buildDialogRecord(jQuery('#' + options.dialogID + '_viewContainer .isSubmitCtl')),
									options.parameters,
									Framework.modules.Dialog.buildSubmitFields(jQuery('#' + options.dialogID + '_viewContainer .isSubmitCtl'))
							 );
						}
						
						
						
						
					},
				}
			};
			
			if (Framework.modules.Dialog._activeDialog.dialogDeleteCallBack == null) {
				aButtons.btnDialogDelete.className = 'btn-danger pull-left hidden';
			}

		} else {
			aButtons = options.buttons;
		}

		var size = 'large';
        var nWidth = 900;

		if (options.size != undefined) {
			size = options.size;
		}

		// Medium size
		if (size == 'medium') {
			size = null;
			nWidth = 600;
		}
		
		var oBootBox = bootbox.dialog({
			title: options.Title, 
			message: options.FormHTML,
			buttons: aButtons,
			id : 'mainDlgWindow',
			width : nWidth,
			height: 400,
			size : size,
			onEscape : function () {
				jQuery('body').css('overflow-y','auto');
				jQuery('body').css('overflow-x','hidden');
				return true;
			}
		});

		
		/**
		 * Call after load event
		 */
		if (options.onAfterLoad != undefined) {
			options.onAfterLoad();
		}

		if (options.onShown != undefined) {				
			oBootBox.on('shown.bs.modal', {options : options}, options.onShown);
			oBootBox.on('shown.bs.modal', function() {
				jQuery('#' + options.dialogID + '_viewContainer input:text, #' + options.dialogID + '_viewContainer textarea, #' + options.dialogID + '_viewContainer select').first().focus();
			});
			
		}

		if (Framework.modules.Dialog._activeDialog.onDialogLoaded != null) {
			oBootBox.on('shown.bs.modal', Framework.modules.Dialog._activeDialog.onDialogLoaded);
		}
		
		return oBootBox;
		
	},

	showForm: function (oForm, oOptions, callback) {

		this._formParameter = oOptions;

		var size = 'large';
		if (oOptions != undefined) {
			if (oOptions.size != undefined) {
				size = oOptions.size; 
			}
		}
		
		var oBootBox = bootbox.dialog({
			title: oForm.getTitle(), 
			message: oForm.getForm(),
			buttons: oForm.getButtons(callback),
			id:'dlgDetails',
			width:900,
			height: 400,				
			size: size,
			onEscape : function () {
				jQuery('body').css('overflow-y','auto');
				jQuery('body').css('overflow-x','hidden');
				return true;
			}
		});

		/**
		 * Call after load event
		 */
		oForm.onAfterLoad();

		oBootBox.on('shown.bs.modal', {options: oForm}, oForm.onShown);

		this._activeDialog = oBootBox;
		
		return oBootBox;
		
	},
	
	/**
	 * Displays a load mask
	 */
	showLoadMask: function(msg, icon) {

		if (this._loadMask != null) {
			return;
		}
		
		if (msg == '' || msg == null) {
			msg = Framework.modules.Localization.get('_LOADING'); 
		} else {
			msg = Framework.modules.Localization.get(msg);
		}
		
		if (icon == '' || icon == null) {
			icon = '<img src="' + Framework.modules.Navigation.url('/vendor/framework/images/loading48.gif') + '" align="absmiddle"/>&nbsp;';
			//icon = '<i class="far fa-spinner fa-pulse" aria-hidden="true"></i>&nbsp;';
		}

		this._loadMask = bootbox.dialog({
		    message: '<p class="text-center"><h4 class="text-center">' + icon + msg + '</h4></p>',
		    closeButton: false
		});
		
		this._loadMask.on('hidden.bs.modal', function () {
			// Fix : requires modal-open if there is anothe modal form 
			if (jQuery('.bootbox.modal').length > 0) {
				jQuery('body').addClass('modal-open');
			}
		});
		
	},

	/**
	 * Hides load mask
	 */
	hideLoadMask: function() {
		
		if (this._loadMask == null) {
			return;
		}
		this._loadMask.modal('hide');
		this._loadMask = null;		
		
		
		
		
	}, 
	
	/**
	 * Displays a notify message
	 */
	notify : function(sTitle, sMessage, sNoteType) {

		if (sNoteType == undefined || sNoteType == '') {
			sNoteType = 'success';
		}
		
		var noteStyle = sNoteType;
		var noteShadow = true;
		var noteOpacity = 1;
		var noteStack = 'stack_bottom_right';
		var width = "290px";

		if (noteStack == "stack_bar_top") {
			width = "100%";
	    }
	    if (noteStack == "stack_bar_bottom") {
	    	width = "70%";
		} else {
			width = "290px";
		}
		
		new PNotify({
	        title: sTitle,
	        text: sMessage,
	        shadow: noteShadow,
	        opacity: noteOpacity,
	        addclass: noteStack,
	        type: noteStyle,
	        stack: this._Stacks[noteStack],
	        width: width,
	        delay: 5000
	      });
	},		
	
	
	closeFormDialog: function() {
		jQuery('#__formDialogWindow').dialog( 'close' );
		if (this._formDialogCallback != null) {
			this._formDialogCallback();
		}	  
		this._formDialogCallback = null;
	}, // end if closeFormDialog

	/**
	 * Redirects to a webpage, allows us to display warning message
	 */
	redirectWebPage: function(url, showWarning) {

		if (showWarning) {
			Framework.modules.Dialog.confirm(Framework.modules.Localization.get('_LEAVINGWEBSITEWARNING'), function(result) {
				if (result) {
					Framework.modules.Dialog.windowOpen(url, '', null, null);
				}
			});
		} else {
			Framework.modules.Dialog.windowOpen(url, '', null, null);
		}
		
	}, 

	/**
	 * Opens url in a new window 
	 */
	windowOpen: function(url, title, w, h) {
		if (w == null) {
			w = screen.width;
		}
		if (h == null) {
			h = screen.height;
		}
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(Framework.modules.Navigation.url(url), title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}, 
	
	
		
};
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
Framework.modules.Localization = {
	
		
	/**
	 * Session current language ID
	 */
    _currentLanguageID : null,
    
	/**
	 * Session current language code
	 */
	_currentLanguageCode : null,
		
	/**
	 * Translation data array
	 */
	data : {},
		
	/**
	 * Returns a translation key
	 */
	get : function(key) {
        return key;
        //@todo
		if (this.data[key] != undefined) {
			return this.data[key].Value;
		} else {
			return key;
		}
	},
	
	/**
	 * Change current language
	 */
	changeLanguage: function(nLanguageID) {
		
		Framework.modules.Storage.setItem('LanguageID', nLanguageID);		
		Framework.modules.Ajax.execute('/Session/set/LanguageID/' + nLanguageID, {});
		
	},
	
	
    translate : function(sCtrlID, sFrom, sTo) {

        if (sFrom == sTo) {
            Framework.modules.Dialog.alert('_SOURCEANDDESTINATIONARESAME');
            return;
        }

        Framework.modules.Ajax.executeWithLoadMask('/Google/translate', {
            Text : Page.findItem(sCtrlID).getValue(sFrom),
            Target : sTo,
            CtrlID : sCtrlID
        });

       
    },

    newTranslationKey : function(sKey, callback) {


        var dlg = new DialogItem();

        dlg.dialogOkCallBack = function(result,fields, rec, params) {

            return true;

        };

        dlg.onDialogLoaded = function() {

			window.dlg_txtKey.setValue(sKey);

        };

        dlg.loadForm('dlg', '\\Framework\\Modules\\Localization\\frmTranslate', '', {}, {title:'_TRANSLATE'}, function(result, fields, rec, params) {

            if (result) {

                Framework.modules.Ajax.executeWithLoadMask('/Translation/save', {
                    AppID : '',
                    sKey : window.dlg_txtKey.getValue(),
                    aItem : {English : dlg_txtEN.getValue(), French : dlg_txtFR.getValue(), TranslationKey : sKey},
                }, function(response) {
                    Framework.modules.Ajax.processResponse(response);
                    if (callback != undefined) {
                        callback();
                    }
                });


            }

        });


	},
	
};
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
Framework.modules.Utilities = {

    _uniqidSeed : null,
    
    initialize : function() {
        
        console.log('Init utilities');
        Number.prototype.round = function(places) {
              return +(Math.round(this + "e+" + places)  + "e-" + places);
        };
        
    },
    
    isEmpty : function(v) {
        return (v === undefined || v == null || v == '') ? true : false;
    },
    
    parseFloat : function(v) {
        if (v == '') {
            v = '0';
        }
        return parseFloat(v);
    },

    
    uniqid : function(prefix, moreEntropy) {
        
        var retId;
        var reqWidth = 8;
        
        if (typeof prefix === 'undefined') {
            prefix = '';
        }
        
        if (this._uniqidSeed == null) {
            this._uniqidSeed = Math.floor(Math.random() * 0x75bcd15);	
        }
        this._uniqidSeed++;
        
        retId = prefix;
        
        retId += this._formatSeed(parseInt(new Date().getTime() / 1000, 10), reqWidth);
        retId += this._formatSeed(this._uniqidSeed, reqWidth);
        
        if (moreEntropy) {
            // for more entropy we add a float lower to 10
            retId += (Math.random() * 10).toFixed(8).toString();
        }
        
        return retId;
        
    },
    
    _formatSeed : function(seed, reqWidth) {
        
        seed = parseInt(seed, 10).toString(16);
        if (reqWidth < seed.length) {
            seed = seed.slice(seed.length - reqWidth);
        }
        if (reqWidth > seed.length) {
            seed = Array(1 + (reqWidth - seed.length)).join('0') + seed;
        }
        
        return seed;
        
    },
    
    
};
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
jQuery( document ).ready(function() {

    Framework.initialize();

});
