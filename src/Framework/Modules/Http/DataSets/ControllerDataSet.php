<?php 
namespace Framework\Modules\Http\DataSets;

use Framework\Modules\Core\Framework;
use Framework\Modules\Data\ArrayDataSet;
use Framework\Modules\Data\Column;

class ControllerDataSet extends ArrayDataSet {

    public function __construct()
    {
        parent::__construct();

        $this->addColumn(new Column([
            "Mapping" => "ClassName",
            "Label" => "Name",
            "Href" => "/build/controller/edit/?c={ClassName}&n={Namespace}"
        ]));

        $this->addColumn(new Column([
            "Mapping" => "Namespace",
            "Label" => "Namespace",
            "Href" => "/build/controller/edit/?c={ClassName}&n={Namespace}"
        ]));

        $this->addColumn(new Column([
            "Mapping" => "Description",
            "Label" => "Description",
            "Href" => ""
        ]));

        /**
         * Load controllers
         */
        foreach(Framework::getControllers() as $aController) {
            $this->getItems()->push(collect($aController));
        }

    }


}

?>