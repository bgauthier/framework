<?php 
namespace Framework\Modules\Settings\Schemas;

use Framework\Modules\Data\Table;
use Illuminate\Support\Facades\Schema;
use Framework\Modules\Data\Blueprint;

class ControlPanelTable extends Table {

	public function __construct() {
		parent::__construct();
		$this->setTableName("ControlPanels");
	}

	public function getParentTables() {
		return [
			$this->buildTableName("Applications")
		];
	}

	public function getTableBluePrint() {

		$oTable = new Blueprint($this->getTableName());
		
		if (!Schema::hasTable($this->getTableName())) {
			$oTable->create();
		}

		// Fields		
		$oTable->increments('ID');
		$oTable->string("Code");
		$oTable->string("Name");
		$oTable->string("Description")->nullable();
		$oTable->integer("DisplayIndex")->default("1");
		$oTable->string("Link")->nullable();
		$oTable->boolean("IsVisible")->default("1");
		$oTable->boolean("IsAdminConfig")->default("0");
		$oTable->string("Icon", 64)->nullable();
		$oTable->integer("ApplicationID");
		$oTable->decimal("AAA", 20,2);
			

		// Indexes
		$oTable->primary("ID", "PRIMARY");
		$oTable->index("DisplayIndex", "ndx_" . $this->getTableName() . "_DisplayIndex");
		$oTable->unique("Code", "unq_" . $this->getTableName() . "_Code");

		// Foreign keys
		$oTable->foreign("ApplicationID", "fk_CD_RO_" . $this->getTableName() . "_ApplicationID")->references("ID")->on($this->buildTableName("Applications"));

		return $oTable;
		

	}

	


}

?>