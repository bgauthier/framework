<?php 
namespace Framework\Modules\Build\Controllers;

use Framework\Modules\Build\Views\ControllerView;
use Framework\Modules\Http\DataSets\ControllerActionDataSet;
use Framework\Modules\Http\DataSets\ControllerDataSet;
use Framework\Modules\Http\Response;
use Framework\Modules\UI\AlertAction;
use Framework\Modules\UI\Bootstrap\Button;
use Framework\Modules\UI\Breadcrumb;
use Framework\Modules\UI\InputGroup;
use Framework\Modules\UI\JQueryAction;
use Framework\Modules\UI\Theme;
use Framework\Modules\UI\Views\DefaultListView;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class ControllerController extends ControllerControllerBase {

    public function listAction() {

        Theme::addBreadcrumb(new Breadcrumb("Build", "/build"));
        Theme::addBreadcrumb(new Breadcrumb("Controller"));
        Theme::addBreadcrumb(new Breadcrumb("List"));

        $btnNew = new Button();
        $btnNew->setID("btnNew");
        $btnNew->setLabel("New");
        $btnNew->setOnClick("Framework.modules.Navigation.redirect('/build/controller/edit');");
        Theme::addToolbarItem($btnNew);

        $oView = new DefaultListView();
        $oView->setListDataSet(new ControllerDataSet());

        return $oView->getView();

    }

    public function editAction(Request $request) {

        if (count($request->all()) == 0) {
            return $this->createAction();
        }

        Theme::addBreadcrumb(new Breadcrumb("Build", "/build"));
        Theme::addBreadcrumb(new Breadcrumb("Controller", "/build/controller/list"));
        Theme::addBreadcrumb(new Breadcrumb("Edit"));

        $btnSave = new Button();
        $btnSave->setID("btnSave");
        $btnSave->setLabel("Save");
        $btnSave->setOnClick("Page.submit();");
        Theme::addToolbarItem($btnSave);

        $btnDelete = new Button();
        $btnDelete->setID("btnDelete");
        $btnDelete->setLabel("Delete");
        $btnDelete->addClass("btn-danger");
        Theme::addToolbarItem($btnDelete);

        $oView = new ControllerView();

        $oView->setSaveRoute("/build/controller/save");

        $sCtrlClass = "\\" . $request->get("n") . "\\" . $request->get("c");
        if (class_exists($sCtrlClass)) {
            $oController = new $sCtrlClass();
            $oView->addDataObject($oController, "Controller");
        }

        $oControllerActions = new ControllerActionDataSet();
        $oControllerActions->setParameter("ControllerClass", $sCtrlClass);

        $oView->tblActions()->setDataSet($oControllerActions);

        return $oView->getView();      
        
    }

    public function createAction() {

        Theme::addBreadcrumb(new Breadcrumb("Build", "/build"));
        Theme::addBreadcrumb(new Breadcrumb("Controller", "/build/controller/list"));
        Theme::addBreadcrumb(new Breadcrumb("New"));

        $btnSave = new Button();
        $btnSave->setID("btnSave");
        $btnSave->setLabel("Save");
        $btnSave->setOnClick("Page.submit();");
        Theme::addToolbarItem($btnSave);

        $oView = new ControllerView();
        return $oView->getView();  
  
    }

    public function saveAction() {

        $sControllerClass = \Framework\Modules\Http\Request::getData("txtNamespace") . "\\" . \Framework\Modules\Http\Request::getData("txtName");

        $oController = new $sControllerClass();

        $oController->CONTROLLER_NAME = \Framework\Modules\Http\Request::getData("txtName");
        $oController->CONTROLLER_NAMESPACE = \Framework\Modules\Http\Request::getData("txtNamespace");
        $oController->CONTROLLER_DESCRIPTION = \Framework\Modules\Http\Request::getData("txtDescription");

        $oController->saveControllerDefinition();



        return Response::renderJSONResponse();
  
    }

    public function deleteAction() {

        return "Controller delete";
  
     }

}

?>