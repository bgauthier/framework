<?php 
namespace Framework\Modules\Data;

use Illuminate\Support\Collection;

class ArrayDataSet extends DataSet {

    protected $_aItems = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->_bIsQueryDataSet = FALSE;
        $this->_aItems = collect([]);
    }

    public function getItems() {
        return $this->_aItems;
    }



}

?>