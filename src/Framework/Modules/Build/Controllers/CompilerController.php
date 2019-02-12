<?php 
namespace Framework\Modules\Build\Controllers;

use App\Http\Controllers\Controller;
use Framework\Modules\Build\Compiler;
use Illuminate\Support\Facades\Artisan;

class CompilerController extends Controller {

    public function buildJSAction() {

        Compiler::buildJSFiles();

        /**
         * Call vendor:publish to copy newly compiled files to public folder
         */        
        /*Artisan::call('vendor:publish',[            
            '--tag' => 'public',
            '--force' => true,
            '--provider' => 'Framework\Modules\Core\FrameworkServiceProvider'
        ]);*/

        return "Compile completed";

    }

    public function buildTranslationAction() {

        Compiler::buildTranslationFiles();

        return "Translation completed";

    }

    public function buildImagesAction() {

        Compiler::buildImages();

        return "Images completed";

    }

}

?>