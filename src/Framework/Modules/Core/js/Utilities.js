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