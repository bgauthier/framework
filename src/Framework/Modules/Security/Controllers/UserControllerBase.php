<?php 
namespace Framework\Modules\Security\Controllers;

use Framework\Modules\Http\Controller;

abstract class UserControllerBase extends Controller {
    
    /**
     * Controller action metadata
     */
    public $CONTROLLER_ACTIONS = [
        "listAction" => [
            "Name" => "listAction",
            "Type" => Controller::ACTION_LIST,            
            "Help" => [
                "fr" => "Liste des utilisateurs",
                "en" => "User list",
            ],
            "ResponseType" => Controller::RESPONSE_HTTP,
            "Middleware" => [],
            "Policies" =>[], 
            "DataSet" => "", 
            "Routes" => [
                [
                    "Domain" => "",
                    "Method" => "GET",
                    "URI" => "/security/user/list",
                    "Name" => "user.list",
                    "Action" => "\Framework\Modules\Security\Controllers\UserController@listAction",
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
                    "URI" => "/security/user/edit",
                    "Name" => "user.create",
                    "Action" => "\Framework\Modules\Security\Controllers\UserController@createAction",
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
                    "URI" => "/security/user/edit/{id}",
                    "Name" => "user.edit",
                    "Action" => "\Framework\Modules\Security\Controllers\UserController@editAction",
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
                    "URI" => "/security/user/save",
                    "Name" => "user.save",
                    "Action" => "\Framework\Modules\Security\Controllers\UserController@saveAction",
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
                    "URI" => "/security/user/delete",
                    "Name" => "user.delete",
                    "Action" => "\Framework\Modules\Security\Controllers\UserController@deleteAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
    ];

}

?>