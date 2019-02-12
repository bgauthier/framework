<?php 
namespace Framework\Modules\Security\Controllers;

use Framework\Modules\UI\Theme;
use Framework\Modules\Build\Compiler;
use Illuminate\Support\Facades\Artisan;
use Framework\Modules\Security\Views\UserView;
use Framework\Modules\Security\Controllers\UserControllerBase;

class UserController extends UserControllerBase {

   public function editAction($id = NULL) {

      $oUserView = new UserView();
      return $oUserView->getView();

   }

}

?>