<?php 
namespace Framework\Modules\Build\Controllers;

use Framework\Modules\UI\Theme;
use Framework\Modules\UI\Breadcrumb;
use Framework\Modules\Build\Compiler;
use Illuminate\Support\Facades\Artisan;
use Framework\Modules\UI\Bootstrap\Button;
use Framework\Modules\Security\Views\UserView;
use Framework\Modules\Build\Views\ControllerView;
use Framework\Modules\Build\Controllers\ControllerControllerBase;

class ViewController extends ViewControllerBase {

    public function listAction() {

        Theme::addBreadcrumb(new Breadcrumb("Build"));
        Theme::addBreadcrumb(new Breadcrumb("Controller"));
        Theme::addBreadcrumb(new Breadcrumb("List"));

        return "Controller list";

    }

    public function editAction($id) {

        Theme::addBreadcrumb(new Breadcrumb("Build"));
        Theme::addBreadcrumb(new Breadcrumb("Controller"));
        Theme::addBreadcrumb(new Breadcrumb("Edit"));

        $btnSave = new Button();
        $btnSave->setID("btnSave");
        $btnSave->setLabel("Save");
        Theme::addToolbarItem($btnSave);

        $btnDelete = new Button();
        $btnDelete->setID("btnDelete");
        $btnDelete->setLabel("Delete");
        $btnDelete->addClass("btn-danger");
        Theme::addToolbarItem($btnDelete);

        $oView = new ControllerView();
        return $oView->getView();      
        
    }

    public function createAction() {

        Theme::addBreadcrumb(new Breadcrumb("Build"));
        Theme::addBreadcrumb(new Breadcrumb("Controller"));
        Theme::addBreadcrumb(new Breadcrumb("New"));

        $btnSave = new Button();
        $btnSave->setID("btnSave");
        $btnSave->setLabel("Save");
        Theme::addToolbarItem($btnSave);

        $oView = new ControllerView();
        return $oView->getView();  
  
    }

    public function saveAction() {

        return "Controller save";
  
    }

    public function deleteAction() {

        return "Controller delete";
  
     }

}

?>