<?php 
namespace Framework\Modules\Core\Schemas;

use Framework\Modules\Data\Table;
use Illuminate\Support\Facades\Schema;
use Framework\Modules\Data\Blueprint;


class ApplicationTable extends Table {

	public function __construct() {
		parent::__construct();
		$this->setTableName("Applications");
	}

	public function getParentTables() {
		return [
			$this->buildTableName("EnumValues")
		];
	}

	public function getTableBluePrint() {

		$oTable = new Blueprint($this->getTableName());
		
		if (!Schema::hasTable($this->getTableName())) {
			$oTable->create();
		}

		// Fields		
		$oTable->integer("ID");
        $oTable->string("Code", 32);	
        $oTable->boolean("IsActive")->default("1");
        $oTable->integer("CategoryID");
        $oTable->string("AppClass");
        $oTable->boolean("CanUninstall")->default("1");
        $oTable->string("SmallIcon");
        $oTable->string("LargeIcon")->nullable();
        $oTable->boolean("IsTestApp")->default("0");                
        
        
        // Indexes
        $oTable->primary("ID", "PRIMARY");
        $oTable->unique("Code", "unq_" . $this->getTableName() . "_Code");
            
        // Foreign keys
        $oTable->foreign("CategoryID", "fk_SN_RO_" . $this->getTableName() . "_CategoryID")->references("ID")->on($this->buildTableName("EnumValues"));

		return $oTable;
		

	}

	


}

?>