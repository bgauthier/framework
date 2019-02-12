<?php

namespace Framework\Modules\Build\Console;

use Illuminate\Console\Command;
use Framework\Modules\Build\Compiler;
use Framework\Modules\Core\Framework;
use Laravel\Telescope\Contracts\ClearableRepository;

class BuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'framework:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build and deploy framework files';

    /**
     * Execute the console command.
     *
     * @param  \Laravel\Telescope\Contracts\ClearableRepository  $storage
     * @return void
     */
    public function handle(ClearableRepository $storage)
    {
        
        $this->info('Building Framework resources');
        foreach(Framework::getModules() as $sModuleName => $aModuleClass) {
            $this->info('-- Building Framework resources for module ' . $aModuleClass["Name"]);
            $sModuleClass = $aModuleClass["Use"];
            $oModule = new $sModuleClass();
            $oModule->compile();
        }

        $this->info('Building Framework lang');
        Compiler::buildTranslationFiles();
        $this->info('Building Framework images');
        Compiler::buildImages();

        /**
         * PUBLISH
         */
        $this->info('Publishing framework-config files');
        $this->call('vendor:publish', ['--tag' => 'framework-config', '--force' => NULL]);

        $this->info('Publishing framework-lang files');
        $this->call('vendor:publish', ['--tag' => 'framework-lang', '--force' => NULL]);

        $this->info('Publishing framework-images files');
        $this->call('vendor:publish', ['--tag' => 'framework-images', '--force' => NULL]);

        $this->info('Publishing framework-js files');
        $this->call('vendor:publish', ['--tag' => 'framework-js', '--force' => NULL]);

        /**
         * DONE
         */
        $this->info('Framework build completed!');
    }
}
