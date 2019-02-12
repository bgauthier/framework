<?php 
namespace Framework\Modules\Build\Controllers;

use Framework\Modules\Http\Controller;

abstract class ModelControllerBase extends Controller {
    
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
                    "URI" => "/build/model/list",
                    "Name" => "build.model.list",
                    "Action" => "\Framework\Modules\Build\Controllers\ModelController@listAction",
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
                    "URI" => "/build/model/edit",
                    "Name" => "build.model.create",
                    "Action" => "\Framework\Modules\Build\Controllers\ModelController@createAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
        "editAction" => [
            "Name" => "editAction",
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
                    "URI" => "/build/model/edit/{id}",
                    "Name" => "build.model.edit",
                    "Action" => "\Framework\Modules\Build\Controllers\ModelController@editAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
        "saveAction" => [
            "Name" => "saveAction",
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
                    "URI" => "/build/model/save",
                    "Name" => "build.model.save",
                    "Action" => "\Framework\Modules\Build\Controllers\ModelController@saveAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
        "deleteAction" => [
            "Name" => "deleteAction",
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
                    "URI" => "/build/model/delete",
                    "Name" => "build.model.delete",
                    "Action" => "\Framework\Modules\Build\Controllers\ModelController@deleteAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
    ];

}

?>