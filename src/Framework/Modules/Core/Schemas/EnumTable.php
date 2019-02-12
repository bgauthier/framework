<?php 
namespace Framework\Modules\Core\Schemas;

use Framework\Modules\Data\Table;
use Illuminate\Support\Facades\Schema;
use Framework\Modules\Data\Blueprint;

class EnumTable extends Table {

	public function __construct() {
		parent::__construct();
		$this->setTableName("Enums");
	}

	public function getParentTables() {
		return [
			// $this->buildTableName("TABLE_NAME")
		];
	}

	public function getTableBluePrint() {

		$oTable = new Blueprint($this->getTableName());
		
		if (!Schema::hasTable($this->getTableName())) {
			$oTable->create();
		}

		// Fields				
        $oTable->increments('ID');
		$oTable->string("Name", 128);
		$oTable->string("Description");
        $oTable->timestampsTz();
       
        
        // Indexes
		$oTable->primary("ID", "PRIMARY");
		$oTable->unique("Name", "unq_tblEnums_Name");
                    
        // Foreign keys
                

		return $oTable;
		

	}

	


}

?>