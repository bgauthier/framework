<?php

namespace Framework\Modules\Build\Console;

use Illuminate\Console\Command;
use Framework\Modules\Build\Compiler;
use Framework\Modules\Core\Framework;
use Laravel\Telescope\Contracts\ClearableRepository;
use Illuminate\Support\Facades\DB;

class SyncDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'framework:dbsync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync database with current schema classes';

    protected $_aSchemas = [];

    protected $_aCommands = [];

    /**
     * Execute the console command.
     *
     * @param  \Laravel\Telescope\Contracts\ClearableRepository  $storage
     * @return void
     */
    public function handle(ClearableRepository $storage)
    {
        
        $this->info('Scanning modules for schema classes');
        
        foreach(Framework::getModules() as $sModuleName => $aModuleClass) {
            $this->info('-- Loading schema classes for module ' . $aModuleClass["Name"]);
            $sModuleClass = $aModuleClass["Use"];
            $oModule = new $sModuleClass();
            $aSchemas = $oModule->getSchemas();
            if (count($aSchemas) > 0) {
                $this->_aSchemas = array_merge($this->_aSchemas, $aSchemas);            
            }
            $aSchemas = [];
        }

        $this->info("Found " . count($this->_aSchemas) . " schemas");

        foreach($this->_aSchemas as $aSchema) {
            $sSchemaClass = $aSchema["Use"];
            $oSchema = new $sSchemaClass();
            $oTable = $oSchema->getTableBluePrint();
            $aCommands = $oTable->toSQL(DB::connection(), new \Illuminate\Database\Schema\Grammars\MySqlGrammar());
            $oTable->setDebugEnabled(TRUE);
            $oTable->toSqlSync(DB::connection(), new \Illuminate\Database\Schema\Grammars\MySqlGrammar());
            $this->_aCommands[$oSchema->getTableName()] = [
                "IsOrdered" => FALSE,
                "TableName" => $oSchema->getTableName(),
                "Commands" => $aCommands,
                "ParentTables" => $oSchema->getParentTables(),
            ];
        }

        

        $this->info("Found " . count($this->_aCommands) . " table command to execute");
        if (count($this->_aCommands) > 0) {
            if ($this->confirm('Execute commands and update database?')) {
        
                $this->orderCommands();

                DB::beginTransaction();
                foreach($this->_aCommands as $sTableName => $aCommand) {
                    $this->question("Executing commands from " . $aCommand["TableName"]);
                    foreach($aCommand["Commands"] as $sCommand) {
                        $this->info('-- > ' . $sCommand);   
                    }
                //    DB::statement($sCommand);
                }
                DB::commit();
               
                
            } else {
                $this->error("Execute canceled at your request, database has not been updated");
            }
        } else {
            $this->info("Nothing to do database is in sync");
        }


        /**
         * DONE
         */
        $this->info('Database sync completed!');
    }

    protected function orderCommands() {

        $aOrderedCommands = [];
        $nPreviousUnprocessedTable = NULL;
        while (!$this->areAllTableProcessed() && $nPreviousUnprocessedTable !== $this->getUnprocessedTableCount()) {

            foreach($this->_aCommands as $sTableName => $aCommand) {
                if (count($aCommand["ParentTables"]) == 0 && $aCommand["IsOrdered"] == FALSE) {
                    $aOrderedCommands[] = $aCommand;
                    // Remove from main command array 
                    $this->_aCommands[$sTableName]["IsOrdered"] = TRUE;                
                } elseif ($this->areRelatedTableProcessed($aCommand["ParentTables"]) && $aCommand["IsOrdered"] == FALSE) {
                    $aOrderedCommands[] = $aCommand;
                    // Remove from main command array 
                    $this->_aCommands[$sTableName]["IsOrdered"] = TRUE;     
                }
            }

        }

        $this->_aCommands = $aOrderedCommands;



    }

    protected function getUnprocessedTableCount() {
        $nCpt = 0;
        foreach($this->_aCommands as $sTableName => $aCommand) {
            if ($aCommand["IsOrdered"] == FALSE) {
                $nCpt++;
            }
        }
        return $nCpt;
    }

    protected function areAllTableProcessed() {
        foreach($this->_aCommands as $sTableName => $aCommand) {
            if ($aCommand["IsOrdered"] == FALSE) {
                return FALSE;
            }
        }
        return TRUE;
    }

    protected function areRelatedTableProcessed($aTables) {
        foreach($aTables as $sName) {
            if ($this->_aCommands[$sName]["IsOrdered"] == FALSE) {
                return FALSE;
            }
        }
        return TRUE;
    }

}
?>