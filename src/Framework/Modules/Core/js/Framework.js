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