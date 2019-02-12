<?php

namespace Framework\Modules\Build\Console;

use Illuminate\Console\Command;
use Framework\Modules\Build\Compiler;
use Framework\Modules\Core\Framework;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'framework:table {name} {tablename} {--force}';

    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a table class';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *     
     * @return void
     */
    public function handle()
    {
        
        
        
        $path = $this->getClassPath($this->argument('name'));
        $name = $this->argument('name');
        $tablename = $this->argument('tablename');

        if (!str_contains($name, "\\")) {
            $this->error("Class name missing full path, use double quotes");
            return;
        }

        $this->info('Creating table class ' . $name);

        //$this->info($name);
        //$this->info($this->getClassNamespace($name));
        //$this->info($this->getClassPath($name));
        //$this->info(class_basename($name));

        if (!$this->alreadyExists($name) || $this->option('force')) {
            $this->makeDirectory($path);
            $this->files->put($path, $this->buildClass($name, $tablename));
        } else {

            if ($this->confirm('File already exists overwrite file?')) {
                $this->question("Overwriting file");
                $this->makeDirectory($path);
                $this->files->put($path, $this->buildClass($name, $tablename));
            } else {
                $this->error("File already exists must use --force to overwrite");
            }
            
        }
        
    }


    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name, $tablename)
    {
        $stub = $this->files->get($this->getStub());

        $stub = str_replace("{Namespace}", $this->getClassNamespace($name), $stub);
        $stub = str_replace("{Classname}", class_basename($name), $stub);
        $stub = str_replace("{Tablename}", $tablename, $stub);

        return $stub;
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->exists($this->getClassPath($rawName));
    }


    protected function getClassNamespace($name) {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    protected function getClassPath($s) {
        $aTokens = explode("\\", $s);
        $sSubPath = "";
        if ($aTokens[0] == "Framework") {
            $sSubPath = "/vendor/logiksuite/framework/src";
        } else {
            $sSubPath = "/app";
        }       
        $sPath = str_replace("\\", "/" , $this->getClassNamespace($s));
        $sPath .= "/" . class_basename($s) . ".php";
        $sPath = \base_path() .  $sSubPath . "/" . $sPath;
        return $sPath;
    }
  

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['tablename', InputArgument::REQUIRED, 'The name of the database table'],
        ];
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {        
        return __DIR__ . '/stubs/table.stub';
    }

    
    protected function getOptions()
    {
        return [        
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite existing file'],
        ];
    }
    

}
