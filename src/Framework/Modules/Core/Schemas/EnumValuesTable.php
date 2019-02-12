<?php 
namespace Framework\Modules\Core\Schemas;

use Framework\Modules\Data\Table;
use Illuminate\Support\Facades\Schema;
use Framework\Modules\Data\Blueprint;

class EnumValuesTable extends Table {

	public function __construct() {
		parent::__construct();
		$this->setTableName("EnumValues");
	}

	public function getParentTables() {
		return [
			 $this->buildTableName("Enums")
		];
	}

	public function getTableBluePrint() {

		$oTable = new Blueprint($this->getTableName());
		
		if (!Schema::hasTable($this->getTableName())) {
			$oTable->create();
		}

		// Fields				
        $oTable->increments('ID');
        $oTable->integer("EnumID");
        $oTable->integer("EnumValueID");
        $oTable->string("Code", 64)->nullable();
        $oTable->string("Name", 128);
        $oTable->string("Description")->nullable();
        $oTable->string("FriendlyName")->nullable();
        $oTable->integer("DisplayIndex")->default(10);
        $oTable->timestampsTz();
       
        
        // Indexes
        $oTable->primary("ID", "PRIMARY");
        $oTable->unique("EnumID,EnumValueID", "unq_tblEnumValues_EnumValueID");
                    
        // Foreign keys
        $oTable->foreign("EnumID", "fk_CD_RO_" . $this->getTableName() . "_EnumID")->references("ID")->on($this->buildTableName("Enums"));
        

		return $oTable;
		

	}

	


}

?>