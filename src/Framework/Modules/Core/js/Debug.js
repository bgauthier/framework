
Framework.modules.Debug = {

    /**
     * Server variables
     */
    _server : null,
    /**
     * Request variables
     */
    _request : null,

    /**
     * XDebug information (for development servers)
     */
    _xdebug_headers : null,

    /**
     * Session variables
     */
    _session : null,

    /**
     * Indicates if debug mode is enabled or not
     */
    _debugEnabled : false,

    /**
     * Indicates if we must output debug info in console
     */
    _bDebugConsole : false,

    /**
     * Debug information array
     */
    _debugData : [],

    /**
     * Debug dialog
     */
    _oDebugDialog : null,

    /**
     * Debug window
     */
    _debugWindow : null,









    /**
     * Returns Server variables in html table format
     */
    getServerVars : function() {
        var s = '';

        s = s + '<div class="row">';
        s = s + ' <div class="col-md-12 tab-block mb25">';
        s = s + ' <div style="overflow: auto;">';
        s = s + ' <table class="table table-bordered table-condensed">';
        for(x in Framework.modules.Debug._server) {
            s = s + '<tr>';
            s = s + '	<td style="width:175px;">' + x + '</td>';
            s = s + '	<td>' + Framework.modules.Debug._server[x] + '</td>';
            s = s + '</tr>';
        }
        s = s + ' </table>';
        s = s + ' </div>';
        // Col md 12
        s = s + ' </div>';
        s = s + '</div>';
        return s;
    },

    /**
     * Returns Request variables in html table format
     */
    getRequestVars : function() {
        var s = '';

        s = s + '<div class="row">';
        s = s + ' <div class="col-md-12 tab-block mb25">';
        s = s + ' <div style="overflow: auto;">';
        s = s + ' <table class="table table-bordered table-condensed">';
        for(x in Framework.modules.Debug._request) {
            s = s + '<tr>';
            s = s + '	<td style="width:175px;">' + x + '</td>';
            s = s + '	<td>' + Framework.modules.Debug._request[x] + '</td>';
            s = s + '</tr>';
        }
        s = s + ' </table>';
        s = s + ' </div>';
        // Col md 12
        s = s + ' </div>';
        s = s + '</div>';

        return s;
    },

    /**
     * Returns Session variables in html table format
     */
    getSessionVars : function() {

        var s = '';

        s = s + '<div class="row">';
        s = s + ' <div class="col-md-12 tab-block mb25">';
        s = s + ' <div style="overflow: auto;">';
        s = s + ' <table class="table table-bordered table-condensed">';
        for(x in Framework.modules.Debug._session) {
            s = s + '<tr>';
            s = s + '	<td style="width:175px;">' + x + '</td>';
            s = s + '	<td>' + Framework.modules.Debug._session[x] + '</td>';
            s = s + '</tr>';
        }
        s = s + ' </table>';
        s = s + ' </div>';
        // Col md 12
        s = s + ' </div>';
        s = s + '</div>';



        return s;
    },

    /**
     * Return list of JS events in table format
     */
    getJSCallStack : function() {

        var s = '';

        s = s + '<div class="row">';
        s = s + ' <div class="col-md-12 tab-block mb25">';
        s = s + ' <div style="overflow: auto;">';
        s = s + ' <table class="table table-bordered table-condensed">';
        for(var i = 0; i < Framework.modules.Debug._debugData.length; i++) {
            s = s + '<tr>';
            s = s + '	<td style="width:175px;">' + Framework.modules.Debug._debugData[i].DateTime + '</td>';
            s = s + '	<td>' + Framework.modules.Debug._debugData[i].Message + '</td>';
            s = s + '</tr>';
        }
        s = s + ' </table>';
        s = s + ' </div>';
        // Col md 12
        s = s + ' </div>';
        s = s + '</div>';

        return s;

    },

    showStatusPage : function () {




    },

    toggleDevTools : function() {
        jQuery('.devTools').toggle();
    },

    showDevTools : function() {
        jQuery('.devTools').show();
    },

    hideDevTools : function() {
        jQuery('.devTools').hide();
    },

    getStatusPage : function() {

        var s = '';

        s = s + '<div class="row">';
        s = s + ' <div class="col-md-12 tab-block mb25">';

        s = s + '<iframe src="https://status.logiksuite.com" style="width:100%;height:4000px;" frameborder="0" />';

        // Col md 12
        s = s + ' </div>';
        s = s + '</div>';

        return s;

    },

    _resizeIframe : function(el) {
        el.style.height = el.contentWindow.document.body.scrollHeight + 'px';
    },

    /**
     * Displays the debug window
     */
    showDebugWindow : function() {

        Framework.modules.Debug._debugWindow = window.open(Framework.modules.Navigation.url('/Debug/default'),'_debugWindow', 'width=1024,height=768,resizable=yes,scrollbars=yes');

    },

    showDebug : function() {
        if (this._oDebugDialog == null) {
            this._oDebugDialog = new Framework.modules.debug.dlgDebug({});
        }
        this._oDebugDialog.showForm();
    },


};