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