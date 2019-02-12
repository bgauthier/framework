<?php
namespace Framework\Modules\Core;

use Framework\Modules\UI\Theme;
use Illuminate\Support\Facades\Log;
use Framework\Modules\Core\Framework;
use Illuminate\Support\Facades\Route;
use Framework\Modules\Security\Session;
use Illuminate\Support\ServiceProvider;
use Framework\Modules\Build\Console\BuildCommand;
use Framework\Modules\Build\Console\CreateTableCommand;
use Framework\Modules\Build\Console\SyncDatabaseCommand;

class FrameworkServiceProvider extends ServiceProvider
{
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Log::debug('FrameworkServiceProvider::register');

        require_once(__DIR__ . "/helpers.php");

        /**
         * Merge framework configuration file
         */
        $this->mergeConfigFrom(
            __DIR__ . '/../../../../config/framework.php', 'framework'
        );

        /**
         * Add build commands for framework
         */
        $this->commands([
            BuildCommand::class,
            CreateTableCommand::class,           
            SyncDatabaseCommand::class, 
        ]);

    }

    /**
     * Service provider boot
     */
    public function boot() {
        
        /**
         * Init the LogikSuite framework
         */        
        Log::debug('FrameworkServiceProvider::boot');     

        /**
         * Register framework middleware group
         */        
        Route::middlewareGroup('framework', config('framework.middleware', []));
               
        /**
         * Register publish config
         */
        $this->_registerPublishing();

        /**
         * Init logiksuite framework
         */
        Framework::initialize();
        
        /**
         * Load theme views
         */
        $this->app['view']->addNamespace('theme', Theme::getThemeBasePath());
        
        
    }

    /**
     * Register package publish 
     */
    protected function _registerPublishing() {

        if ($this->app->runningInConsole()) {

            Log::debug('Framework Publishing Files'); 

            /**
            * Publish Framework configuration
            */
            Log::debug('Publishing framework configuration'); 
            $this->publishes([
                \realpath(__DIR__ . '/../../../../config/framework.php') => config_path('framework.php'),
            ], 'framework-config');

            /**
             * Publish build javascript file and images in public path
             */
            $this->publishes([
                \realpath(__DIR__ . '/../../../../build/js') => public_path('vendor/framework/js'),
            ], 'framework-js');
         
            $this->publishes([
                \realpath(__DIR__ . '/../../../../build/images') => public_path('vendor/framework/images'),
            ], 'framework-images');


            /**
             * Publish translation files
             */
            Log::debug('Publishing translation files');             
            $this->loadTranslationsFrom(\realpath(__DIR__ . '/../../../../build/lang'), 'framework');

            $this->publishes([
                \realpath(__DIR__ . '/../../../../build/lang/') => resource_path('lang/vendor/framework/'),            
            ], 'framework-lang');

            /**
             * Publish other public resources (3rd party libs)
             */
            $this->publishes([
                \realpath(__DIR__ . '/public') => public_path('vendor/framework'),
            ], 'framework-libs');

        }

    }


}
?>