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