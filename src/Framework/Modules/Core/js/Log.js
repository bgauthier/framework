
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