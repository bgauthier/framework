<?php 
namespace Framework\Modules\Build\Controllers;

use Framework\Modules\Http\Controller;

abstract class ViewControllerBase extends Controller {
    
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
                    "URI" => "/build/view/list",
                    "Name" => "build.view.list",
                    "Action" => "\Framework\Modules\Build\Controllers\ViewController@listAction",
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
                    "URI" => "/build/view/edit",
                    "Name" => "build.view.create",
                    "Action" => "\Framework\Modules\Build\Controllers\ViewController@createAction",
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
                    "URI" => "/build/view/edit/{id}",
                    "Name" => "build.view.edit",
                    "Action" => "\Framework\Modules\Build\Controllers\ViewController@editAction",
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
                    "URI" => "/build/view/save",
                    "Name" => "build.view.save",
                    "Action" => "\Framework\Modules\Build\Controllers\ViewController@saveAction",
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
                    "URI" => "/build/view/delete",
                    "Name" => "build.view.delete",
                    "Action" => "\Framework\Modules\Build\Controllers\ViewController@deleteAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
    ];

}

?>