<?php 
namespace Framework\Modules\Settings\Controllers;

use Framework\Modules\UI\Theme;
use Framework\Modules\UI\Breadcrumb;
use Illuminate\Support\Facades\Artisan;
use Framework\Modules\UI\Bootstrap\Button;
use Framework\Modules\Security\Views\UserView;
use Framework\Modules\Build\Controllers\ControllerControllerBase;

class SettingsController extends SettingsControllerBase {

    public function listAction() {
        
        Theme::addBreadcrumb(new Breadcrumb("Settings"));
        Theme::addBreadcrumb(new Breadcrumb("List"));

        return "Controller list";

    }

    public function editAction($id) {
        
        Theme::addBreadcrumb(new Breadcrumb("Settings"));
        Theme::addBreadcrumb(new Breadcrumb("Edit"));

        return "Controller edit";   
        
    }

    public function createAction() {

        Theme::addBreadcrumb(new Breadcrumb("Settings"));
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