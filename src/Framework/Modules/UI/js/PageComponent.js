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