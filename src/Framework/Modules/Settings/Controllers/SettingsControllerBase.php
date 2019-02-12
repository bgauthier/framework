<?php 
namespace Framework\Modules\Settings\Controllers;

use Framework\Modules\Http\Controller;

abstract class SettingsControllerBase extends Controller {
    
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
                    "URI" => "/settings/list",
                    "Name" => "settings.list",
                    "Action" => "\Framework\Modules\Settings\Controllers\SettingsController@listAction",
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
                    "URI" => "/settings/edit",
                    "Name" => "settings.create",
                    "Action" => "\Framework\Modules\Settings\Controllers\SettingsController@createAction",
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
                    "URI" => "/settings/edit/{id}",
                    "Name" => "settings.edit",
                    "Action" => "\Framework\Modules\Settings\Controllers\SettingsController@editAction",
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
                    "URI" => "/settings/save",
                    "Name" => "settings.save",
                    "Action" => "\Framework\Modules\Settings\Controllers\SettingsController@saveAction",
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
                    "URI" => "/settings/delete",
                    "Name" => "settings.delete",
                    "Action" => "\Framework\Modules\Settings\Controllers\SettingsController@deleteAction",
                    "Middleware" => ['framework'],
                ],          
            ],
        ],
    ];

}

?>