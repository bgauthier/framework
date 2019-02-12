<?php
namespace Framework\Modules\Build\Controllers;

use Framework\Modules\Http\Controller;

abstract class BuildControllerBase extends Controller {

    /**
     *
     */
    public $CONTROLLER_NAMESPACE = "Framework\\Modules\\Build\\Controllers";

    /**
     * @var Controller name
     */
    public $CONTROLLER_NAME = "BuildController";

    /**
     * @var Controller description
     */
    public $CONTROLLER_DESCRIPTION = "Build management";

    /**
     * Controller action metadata
     */
    public $CONTROLLER_ACTIONS = [
        "dashboardAction" => [
            "Name" => "dashboardAction",
            "Type" => Controller::ACTION_CUSTOM,
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
                    "URI" => "/build",
                    "Name" => "build.controller.dashboard",
                    "Action" => "\Framework\Modules\Build\Controllers\BuildController@dashboardAction",
                    "Middleware" => ['framework'],
                ],
            ],
        ],
    ];


}

?>