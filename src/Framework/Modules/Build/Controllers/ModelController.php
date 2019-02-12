<?php 
namespace Framework\Modules\Build\Controllers;

use App\Http\Controllers\Controller;
use Framework\Modules\Build\Compiler;
use Framework\Modules\DataType\Strings;
use Illuminate\Support\Facades\Artisan;

class ModelController extends ModelControllerBase {


    public function listAction() {

        
        return "List models";

    }

    public function createAction() {

        return "Create model";

    }

    public function editAction() {

        return "Edit model";
    }


}

?>