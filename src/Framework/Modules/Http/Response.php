<?php

namespace Framework\Modules\Http;

use Framework\Modules\Core\Random;

class Response extends \Illuminate\Http\Response {


    const ACTION_SUCCESS 	= "success";
    const ACTION_ERROR 		= "error";
    const ACTION_PENDING 	= "pending";
    const ACTION_CANCELED 	= "canceled";


    protected static $_aActions = [];
    protected static $_aData = [];
    protected static $_nStatus = Response::ACTION_PENDING;



    /**
     * Sets a data value based on key
     *
     * @param unknown $key
     * @param unknown $value
     */
    public static function setData($key, $value) {
        static::$_aData[$key] = $value;
    }

    /**
     * Returns a data value based on key
     * @param unknown $key
     */
    public static function getData($key) {
        if (array_key_exists($key, static::$_aData)) {
            return static::$_aData[$key];
        } else {
            return NULL;
        }
    }

    /**
     * Add an action to execute (browser side)
     *
     * @param JSAction $oAction
     */
    public static function addAction($oAction) {

        static::$_aActions[] = $oAction;


    }

    /**
     * Remove all actions
     * @return $this
     */
    public static function clearActions() {

        static::$_aActions = [];

    }

    /**
     * Set response status
     * @param $nStatus
     * @return $this
     */
    public static function setStatus($nStatus) {
       // $this->_nStatus = $nStatus;
        //return $this;
    }

    public static function getStatus() {
        //return $this->_nStatus;
    }


    public static function renderJSONResponse() {

        // Convert data to json string and call parent
        $aResponse = array();

        $aResponse["id"] = Random::uniqueID();
        //$aResponse["AjaxCallID"] = Input::get("AjaxCallID");
        $aResponse["status"] = 1;
        //$aResponse["status"] = static::$_nStatusCode;
        //$aResponse["message"] = $this->statusText;
        //$aResponse["status"] = $this->getStatus();

        $aResponse["actions"] = array();
        // Add all actions to array
        foreach (static::$_aActions as $k => $oAction) {
            $aResponse["actions"][] = $oAction->toArray();
        }

        $aResponse["data"] = array();
        foreach(static::$_aData as $k => $oData) {
            $aResponse["data"][$k] = $oData;
        }

        $sJSON = json_encode($aResponse);

        return $sJSON;

    }

}

?>