'use strict';

class PageComponent {
 
    constructor() {

        /**
         * Indicates if page is loaded or not
         */
        this.isLoaded = false;

        /**
         * Indicates if the page is dirty or not
         */
        this._isDirty = false;

        /**
         * List of items (components) on the page
         */
        this.items = {};

        this.controls = {};

        this.onAfterSave = null;

        this.newRoute = null;

        this.saveRoute = null;

        this.deleteRoute = null;


    }


    /**
     * Called once on page load
     */
    onPageLoad() {

        if (this.isLoaded) {
            return;
        }

        for(var key in this.items) {
        	if (typeof this.items[key] === 'object') {
				this.initializeComponent(this.items[key]);
			}
        }	
        
        /**
         * Enable all bootstrap popovers
         */
        jQuery('[data-toggle="popover"]').popover();


        this.isLoaded = true;

    }


    /**
     * 
     * @param {Set page dirty or not} bIsDirty 
     */
    setIsDirty(bIsDirty) {
        this._isDirty = bIsDirty;
    }

    /**
     * Add item (Component) to form
     */
    add(oControl) {

        this.controls[oControl.id] = oControl;
		
		if (this.items[oControl.id] != undefined) {
			Framework.modules.Logging.warn('Duplicate ID from component ' + oControl.id);
			Framework.modules.Logging.warn(this.items[oControl.id]);
		}
		
		
		this.items[oControl.id] = oControl;
				
		if (this.isLoaded) {
			// Init the object 
			oControl.initialize();
			//this._hookEvents();
		}

    }

    /**
     * Alias for add
     * @param {*} oControl 
     */
    addItem(oControl) {
        this.add(oControl);
    }

    /**
     * Return page item
     * @param {*} sID 
     */
    get(sID) {
        if (this.controls[sID] != undefined) {
            return this.controls[sID];
        }
        return null;
    }

    /**
     * Alias for get
     * @param {*} sID 
     */
    findItem(sID) {
        return this.get(sID);
    }


    /**
     * Enables page controls
     */
    enable() {

    }

    /**
     * Disable page controls
     */
    disable() {
        
    }

    initializeComponent(oControl) {

        if (oControl != null) {
			if (typeof oControl.initialize === 'function') {
				if (!oControl.getIsInitialized()) {
					oControl.initialize();
				}
			}	
			// Check for child object 										
			for(var key in oControl.items) {
                //
				if (typeof oControl.items[key] === 'object') {					
					if (!oControl.items[key]._isInitialized) {
						this.initializeComponent(oControl.items[key]);
					}
				} 
			}
		} // end if obj is null		

    }

    /**
     * Submit page 
     */
    submit() {

        if (!this.validate()) {
            return false;
        }

        var aData = [];
        jQuery('[fw-submit="true"]').each(function() {
            aData[aData.length] = {
                Name : jQuery(this).attr('id'),
                Value : Page.findItem(jQuery(this).attr('id')).getValue(),
                MappingObject : jQuery(this).attr('fw-mapping-object'),
                MappingField : jQuery(this).attr('fw-mapping-field')
            };
        });


        Framework.modules.Ajax.executeWithLoadMask(Framework.modules.Navigation.url(this.saveRoute), {data : aData}, function(response) {

            Framework.modules.Ajax.processResponse(response);

            Page.setIsDirty(false);

            if (Page.onAfterSave) {
                Page.onAfterSave();
            }

        }, 'Saving');


    }

    /**
     * Validates page
     */
    validate() {

        var bIsValid = true;
        jQuery('[fw-required="true"]').each(function() {
            if ( Framework.modules.Utilities.isEmpty(Page.findItem(jQuery(this).attr('id')).getValue()) ) {
                Framework.modules.Dialog.alert(Page.findItem(jQuery(this).attr('id')).getIsRequiredMessage());
                Page.findItem(jQuery(this).attr('id')).focus();
                bIsValid = false;
                return;
            }
        });

        return bIsValid;

    }


}
'use strict';

class Component {

    constructor() {
        
        /**
         * Component ID
         */
        this.id = null;

        /**
         * List of child components
         */
        this.items = {};

        /**
         * Indicates if item is initialized
         */
        this._isInitialized = false;

        this._isDirty = false;

        this._value = {};

        this._requiredMessage = null;

        /**
         * EVENTS
         */
        this.onClick = null;
        this.onBlur = null;
        this.onChange = null;
        this.onSelect = null;
        this.onDblClick = null;
        this.onFocus = null;
        this.onFocusIn = null;
        this.onFocusOut = null;
        this.onHover = null;
        this.onKeyDown = null;
        this.onKeyPress = null;
        this.onKeyUp = null;
        this.onMouseDown = null;
        this.onMouseEnter = null;
        this.onMouseLeave = null;
        this.onMouseMove = null;
        this.onMouseOut = null;
        this.onMouseUp = null;
        this.onResize = null;
        this.onScroll = null;
        this.onSubmit = null;
        this.onUnload = null;
        this.onLoad = null;

        /**
         * Custom events
         */
        this.onValueChanged = null;

        /**
         * Indicates if the component is an input type component
         * @type {boolean}
         */
        this.isInputComponent = false;


    }

    /**
     * Initialize the component
     */
    initialize() {

        console.log('Initializing component ' + this.id);

        if (this._isInitialized) {
            return false;
        }

        if (this.isInputComponent) {

            /**
             * Enable onChange events for input type components
             */
            if (this.onValueChanged == null) {
                this.onValueChanged = function () {
                    Page.findItem(jQuery(this).attr('id')).setValue(jQuery(this).val());
                };
            } else {
                console.warn('Custom onValueChanged function');
            }

            jQuery('#' + this.id).on('change', this.onValueChanged);

        }

        /**
         * Attach js events to component
         */
        if (this.onClick != null) {
            jQuery('#' + this.id).on('click', this.onClick);
        }
        
        if (this.onDblClick != null) {
            jQuery('#' + this.id).on('dblclick', this.onDblClick);
        }
        
        if (this.onLoad != null) {
            jQuery('#' + this.id).on('load', this.onLoad);
        }
        
        if (this.onUnload != null) {
            jQuery('#' + this.id).on('unload', this.onUnload);
        }
        
        if (this.onSubmit != null) {
            jQuery('#' + this.id).on('submit', this.onSubmit);
        }
        
        if (this.onScroll != null) {
            jQuery('#' + this.id).on('scroll', this.onScroll);
        }
        
        if (this.onResize != null) {
            jQuery('#' + this.id).on('resize', this.onResize);
        }
        
        if (this.onMouseUp != null) {
            jQuery('#' + this.id).on('mouseup', this.onMouseUp);
        }
        
        if (this.onMouseOut != null) {
            jQuery('#' + this.id).on('mouseout', this.onMouseOut);
        }
        
        if (this.onMouseMove != null) {
            jQuery('#' + this.id).on('mousemove', this.onMouseMove);
        }
        
        if (this.onMouseLeave != null) {
            jQuery('#' + this.id).on('mouseleave', this.onMouseLeave);
        }
        
        if (this.onMouseEnter != null) {
            jQuery('#' + this.id).on('mouseenter', this.onMouseEnter);
        }
        
        if (this.onMouseDown != null) {
            jQuery('#' + this.id).on('mousedown', this.onMouseDown);
        }
        
        if (this.onKeyUp != null) {
            jQuery('#' + this.id).on('keyup', this.onKeyUp);
        }
        
        if (this.onKeyPress != null) {
            jQuery('#' + this.id).on('keypress', this.onKeyPress);
        }
        
        if (this.onKeyDown != null) {
            jQuery('#' + this.id).on('keydown', this.onKeyDown);
        }
        
        if (this.onHover != null) {
            jQuery('#' + this.id).on('hover', this.onHover);
        }
        
        if (this.onFocusOut != null) {
            jQuery('#' + this.id).on('focusout', this.onFocusOut);
        }
        
        if (this.onFocusIn != null) {
            jQuery('#' + this.id).on('focusin', this.onFocusIn);
        }
        
        if (this.onFocus != null) {
            jQuery('#' + this.id).on('focus', this.onFocus);
        }
        
        if (this.onChange != null) {
            jQuery('#' + this.id).on('change', this.onChange);
        }
        
        if (this.onBlur != null) {				
            jQuery('#' + this.id).on('blur', this.onBlur);
        }
        
        if (this.onFocus != null) {
            jQuery('#' + this.id).on('focus', this.onFocus);
        }
        
        if (this.onFocusIn != null) {
            jQuery('#' + this.id).on('focusin', this.onFocusIn);
        }
        
        if (this.onFocusOut != null) {
            jQuery('#' + this.id).on('focusout', this.onFocusOut);
        }
        
        if (this.onSelect != null) {				
            jQuery('#' + this.id).on('select', this.onSelect);
        }


        this._isInitialized = true;


    }

    /**
     * Add a child component 
     * @param {*} o 
     */
    add(o) {
        this.items[o.id] = o;
        Page.controls[o.id] = o;
    }

    /**
     * Returns component ID
     */
    getID() {
        return this.id;
    }

    /**
     * Sets component ID
     * @param {*} sID 
     */
    setID(sID) {
        this.id = sID;
    }

    /**
     * Retur5ns if component is initialized or not
     */
    getIsInitialized() {
        return this._isInitialized;
    }

    /**
     * Get component value
     * @param {*} sLngCode 
     */
    getValue(sLngCode = null) {

        if (sLngCode == null) {
            // Use session language
            sLngCode = 'fr';
        }

        return this._value[sLngCode];
    }

    /**
     * Returns component value
     * @param {*} sValue 
     * @param {*} sLngCode 
     */
    setValue(sValue, sLngCode = null) {

        if (sLngCode == null) {
            // Use session language
            sLngCode = 'fr';
        }

        if (this._value[sLngCode] != sValue) {            
            this._value[sLngCode] = sValue;
            jQuery('#' + this.id).val(this._value[sLngCode]);
            this.setIsDirty(true);            
        }
    }

    /**
     * Sets if component is dirty (changed)
     * @param {*} bIsDirty 
     */
    setIsDirty(bIsDirty) {
        this._bIsDirty = bIsDirty;
        if (bIsDirty) {
            Page.setIsDirty(true);
        }
    }

    /**
     * Returns is required alert message
     */
    getIsRequiredMessage() {
        return "Field is required";
    }

    /**
     * Set component focus
     */
    focus() {
        jQuery('#' + this.id).focus();
    }

}
'use strict';

/**
 * Data store object, used to keep data records
 */
class Store {

    /**
     * Data store constructor
     * @param oOptions Object of options to set upon object creation
     */
    constructor(oOptions) {

        /**
         * The parent component id this store is linked to
         * @type {null}
         */
        this.parentComponentID = null;

        /**
         * Primary key of the store
         * @type {string}
         */
        this.keyField = 'ID';

        /**
         * Model of data, column definition
         * @type {null}
         */
        this.model = null;

        /**
         * The actual data
         * @type {Array}
         */
        this.data = [];

        /**
         * List of columns that will be submitted
         * @type {Array}
         */
        this.submitFields = [];

        /**
         * Internal temporary IDs, use as PK for new records
         */
        this.privateID = -1;

        /**
         *  E V E N T S
         */

        /**
         * Before data is added to the store
         * @type {null}
         */
        this.onBeforeAdd = null;

        /**
         * After data has been added to the store
         * @type {null}
         */
        this.onAfterAdd = null;

        /**
         * Before we remove data from the store
         * @type {null}
         */
        this.onBeforeRemove = null;

        /**
         * After data has been removed from the store
         * @type {null}
         */
        this.onAfterRemove = null;


        /**
         * Load options if any passed by parameter to constructor
         */
        if (typeof oOptions == 'object') {
            this.parentComponentID = oOptions['parentComponentID'];
            this.keyField = oOptions['keyField'];
            this.model = oOptions['model'];
            this.data = oOptions['data'];
            this.submitFields = oOptions['submitFields'];
        }


    }

    /**
     * Add record to store
     * @param oRecord
     */
    add(oRecord) {

        if (this.onBeforeAdd != null) {
            this.onBeforeAdd(oRecord);
        }

        if (oRecord[this.keyField] != undefined) {
            oRecord._id = oRecord[this.keyField];
        } else {
            oRecord._id = this.privateID;
            oRecord[this.keyField] = oRecord._id;
            this.privateID--;
        }

        if (this.data == undefined) {
            this.data = [];
        }
        this.data[this.data.length] = oRecord;

        if (this.parentComponentID != null) {
            jQuery(this).trigger('onDataChanged', [this.parentComponentID, 'add', oRecord]);
        }

        if (this.onAfterAdd != null) {
            this.onAfterAdd(oRecord);
        }

    }

    /**
     * Remove record from store using key value
     * @param mKey
     * @returns {boolean}
     */
    remove(mKey) {

        for(var i = 0; i < this.data.length; i++) {
            if (this.data[i][this.keyField] == mKey) {

                if (this.onBeforeRemove != null) {
                    if (!this.onBeforeRemove(mKey)) {
                        return false;
                    }
                }

                this.data.splice(i,1);

                if (this.onAfterRemove != null) {
                    this.onAfterRemove(mKey);
                }

                return true;
            }
        }

        return false;

    }

    update(mKey, oRecord, bOverride) {

        /**
         * Override / crush values by default
         */
        if (bOverride == undefined) {
            bOverride = true;
        }

        if (this.find(mKey) != null) {

            for(var i = 0; i < this.data.length; i++) {
                if (this.data[i][this.keyField] == mKey) {

                    this.data[i]["_ISFLAGGED"] = false;

                    if (bOverride) {
                        for(var x in oRecord) {
                            this.data[i][x] = oRecord[x];
                        }
                    }
                    if (this.parentComponentID != null) {
                        jQuery(this).trigger('onDataChanged', [this.parentComponentID, 'update', this.data[i]]);
                    }


                    return true;
                }
            }

        } else {

            oRecord["_ISFLAGGED"] = false;
            return this.add(oRecord);

        }
        return false;

    }

    /**
     * Returns data that will be submitted
     */
    getSubmitData()  {

        var aData = [];

        for(var x in this.data) {
            var aItem = {};
            for(var col in this.submitFields) {
                if (this.data[x][ this.submitFields[col]] == undefined) {
                    aItem[this.submitFields[col]] = '';
                } else {
                    aItem[this.submitFields[col]] = this.data[x][ this.submitFields[col]];
                }
            }
            aData[aData.length] = aItem;
        }

        return aData;

    }

    setValue(mKey, sFieldName, mValue) {

        if (this.find(mKey) != null) {

            for(var i = 0; i < this.data.length; i++) {
                if (this.data[i][this.keyField] == mKey) {
                    this.data[i][sFieldName] = mValue;
                    if (this.parentComponentID != null) {
                        jQuery(this).trigger('onDataChanged', [this.parentComponentID, 'update', this.data[i]]);
                    }
                    return true;
                }
            }

        }
        return false;

    }

    /**
     * Set value for all items in store
     * sFieldName is the name of the field to set the value form
     * vvalue is the value
     */
    setValueAll(sFieldName, mValue) {

        for(var i = 0; i < this.data.length; i++) {
            this.data[i][sFieldName] = mValue;
            if (this.parentComponentID != null) {
                jQuery(this).trigger('onDataChanged', [this.parentComponentID, 'update', this.data[i]]);
            }
        }

        return true;

    };

    /**
     * Returns a record based on key
     * @param mKey
     * @returns {*}
     */
    get(mKey) {
        return this.find(mKey);
    }

    /**
     * Returns a record based on key
     * @param mValue
     * @returns {*}
     */
    find(mValue) {
        return this.findBy(this.keyField, mValue);
    }

    /**
     * Returns a record based on field name and value
     * @param sFieldName
     * @param mValue
     * @returns {*}
     */
    findBy(sFieldName, mValue) {

        var aResults = [];

        for(var i = 0; i < this.data.length; i++) {
            if (this.data[i][sFieldName] == mValue) {
                aResults[aResults.length] = this.data[i];
            }
        }

        if (aResults.length == 0) {
            return null;
        } else if (aResults.length == 1) {
            return aResults[0];
        } else {
            return aResults;
        }

        return null;
    }

    /**
     * Clears store data
     */
    clear() {
        this.data = [];
    }

    /**
     * set ISFLAGGED to true for all items in store
     */
    flagAll() {

        for (var x in this.data) {
            this.data[x]['_ISFLAGGED'] = true;
        }

    }

    /**
     * remove flagged items from store
     */
    deleteFlagged() {
        for (var i = this.data.length - 1; i >= 0; i--) {
            if (this.data[i]['_ISFLAGGED']) {
                this.data.splice(i,1);
            }
        }

    }


    /**
     * Update store with new array of items, missing items will be removed from store
     */
    updateStore(aStoreData) {
        this.flagAll();
        for(var x in aStoreData) {
            this.update(aStoreData[x][this.keyField], aStoreData[x]);
        }
        this.deleteFlagged();
    };

    count() {
        return this.data.length;
    }

    length() {
        return this.count();
    }


}
'use strict';

class Div extends Component {
}
'use strict';

class Alert extends Component {
}
'use strict';

class Anchor extends Component {
}
'use strict';

class Button extends Component {
}
'use strict';

class CheckBox extends Component {

    constructor() {
        super();
        this.isInputComponent = true;
    }

}
'use strict';

class Column extends Component {
}
'use strict';

class Form extends Component {
}
'use strict';

class Heading extends Component {
}
'use strict';

class Icon extends Component {
}
'use strict';

class Image extends Component {
}
'use strict';

class Label extends Component {
}
'use strict';

class Li extends Component {
}
'use strict';

class Ol extends Component {
}
'use strict';

class Panel extends Component {
}
'use strict';

class Paragraph extends Component {
}
'use strict';

class Pre extends Component {
}
'use strict';

class RadioButton extends Component {

    constructor() {
        super();
        this.isInputComponent = true;
    }

}
'use strict';

class Row extends Component {
}
'use strict';

class Select extends Component {

    constructor() {
        super();
        this.isInputComponent = true;
    }

}
'use strict';

class Span extends Component {
}
'use strict';

class Table extends Component {



}
'use strict';

class Text extends Component {

    constructor() {
        super();
        this.isInputComponent = true;
    }

}
'use strict';

class TextArea extends Component {

    constructor() {
        super();
        this.isInputComponent = true;
    }

}
var Page = new PageComponent();

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
