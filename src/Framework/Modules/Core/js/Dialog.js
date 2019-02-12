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