<?php 
namespace Framework\Modules\Security;

use Framework\Modules\UI\Theme;
use Framework\Modules\Core\Module;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class SecurityModule extends Module {

    public function __construct() {
        parent::__construct();
        Log::debug("Setting base path to Security");
        $this->_sModuleBasePath = "Security";
    }


    public function initialize() {

        parent::initialize();
       
    }


    protected function _addModuleRoutes() {

        parent::_addModuleRoutes();
        
        Log::debug("--- Adding routes /" . $this->_sModuleBasePath);

        /**
         * Override show login and registration form
         */
        Route::get('login', 'Framework\\Modules\\Security\\Controllers\\SecurityController@showLoginForm')
            ->name('login')
            ->middleware(['web','guest']);        
        Route::get('register', 'Framework\\Modules\\Security\\Controllers\\SecurityController@showRegistrationForm')
            ->name('register')
            ->middleware(['web','guest']);
              
        Route::post('login', 'App\\Http\\Controllers\\Auth\\LoginController@login')->middleware(['web','guest']);

        Route::post('logout', 'App\\Http\\Controllers\\Auth\\LoginController@logout')
            ->name('logout')
            ->middleware(['web']);

        Route::get('logout', 'App\\Http\\Controllers\\Auth\\LoginController@logout')
            ->name('logout')
            ->middleware(['web']);

        Route::post('register', 'App\\Http\\Controllers\\Auth\\RegisterController@register')->middleware(['web','guest']);

       



    }


}

?>