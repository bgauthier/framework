<?php 
namespace Framework\Modules\Security\Controllers;

use Framework\Modules\UI\Theme;
use Framework\Modules\Build\Compiler;
use Framework\Modules\Http\Controller;
use Illuminate\Support\Facades\Artisan;

class SecurityController extends Controller {

    public function showLoginForm() {

        return view('theme::login', Theme::getTokens());

    }

    public function showRegistrationForm() {

        
        return view('theme::register', Theme::getTokens());

    }

}

?>