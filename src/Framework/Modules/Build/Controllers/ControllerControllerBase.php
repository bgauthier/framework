<?php 
namespace Framework\Modules\Build\Controllers;

use Framework\Modules\Http\Controller;

abstract class ControllerControllerBase extends Controller {

    /**
     *
     */
    public $CONTROLLER_NAMESPACE = "Framework\\Modules\\Build\\Controllers";

    /**
     * @var Controller name
     */
    public $CONTROLLER_NAME = "ControllerController";

    /**
     * @var Controller description
     */
    public $CONTROLLER_DESCRIPTION = "Controller management";

    /**
     * Controller action metadata
     */
    public $CONTROLLER_ACTIONS = [
        "listAction" => [
            "Name" => "listAction",
            "Type" => Controller::ACTION_LIST,            
            "Help" => [
                "fr" => "",
                "en" => "",
            ],
            "ResponseType" => Controller::RESPONSE_HTTP,
            "Middleware" => [],
            "Policies" =>[], 
            "DataSet" => "", 
            "Routes" => [
                [
                    "Domain" => "",
                    "Method" => "GET",
                    "URI" => "/build/controller/list",
                    "Name" => "build.controller.list",
                    "Action" => "\Framework\Modules\Build\Controllers\ControllerController@listAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
        "createAction" => [
            "Name" => "createAction",
            "Type" => Controller::ACTION_CREATE,            
            "Help" => [],
            "ResponseType" => Controller::RESPONSE_HTTP,
            "Middleware" => [],
            "Policies" =>[], 
            "DataSet" => "", 
            "Routes" => [
                [
                    "Domain" => "",
                    "Method" => "GET",
                    "URI" => "/build/controller/edit",
                    "Name" => "build.controller.create",
                    "Action" => "\Framework\Modules\Build\Controllers\ControllerController@createAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
        "editAction" => [
            "Name" => "listAction",
            "Type" => Controller::ACTION_EDIT,            
            "Help" => [
                "fr" => "",
                "en" => ""
            ],
            "ResponseType" => Controller::RESPONSE_HTTP,
            "Middleware" => [],
            "Policies" =>[], 
            "DataSet" => "", 
            "Routes" => [
                [
                    "Domain" => "",
                    "Method" => "GET",
                    "URI" => "/build/controller/edit",
                    "Name" => "build.controller.edit",
                    "Action" => "\Framework\Modules\Build\Controllers\ControllerController@editAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
        "saveAction" => [
            "Name" => "listAction",
            "Type" => Controller::ACTION_SAVE,            
            "Help" => [
                "fr" => "",
                "en" => "",
            ],
            "ResponseType" => Controller::RESPONSE_JSON,
            "Middleware" => [],
            "Policies" =>[], 
            "DataSet" => "", 
            "Routes" => [
                [
                    "Domain" => "",
                    "Method" => "POST",
                    "URI" => "/build/controller/save",
                    "Name" => "build.controller.save",
                    "Action" => "\Framework\Modules\Build\Controllers\ControllerController@saveAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
        "deleteAction" => [
            "Name" => "listAction",
            "Type" => Controller::ACTION_DELETE,            
            "Help" => [
                "fr" => "",
                "en" => "",
            ],
            "ResponseType" => Controller::RESPONSE_JSON,
            "Middleware" => [],
            "Policies" =>[], 
            "DataSet" => "", 
            "Routes" => [
                [
                    "Domain" => "",
                    "Method" => "DELETE",
                    "URI" => "/build/controller/delete",
                    "Name" => "build.controller.delete",
                    "Action" => "\Framework\Modules\Build\Controllers\ControllerController@deleteAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
    ];


}

?>