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