<?php
namespace Framework\Modules\Http\DataSets;

use Framework\Modules\Core\Framework;
use Framework\Modules\Data\ArrayDataSet;
use Framework\Modules\Data\Column;

class ControllerActionDataSet extends ArrayDataSet {

    public function __construct()
    {
        parent::__construct();

        $this->addColumn(new Column([
            "Mapping" => "Name",
            "Label" => "Action Name",
            "Href" => "/build/controller/edit/?c={ClassName}&n={Namespace}"
        ]));

        $this->addColumn(new Column([
            "Mapping" => "Type",
            "Label" => "Action Type",
            "Href" => "/build/controller/edit/?c={ClassName}&n={Namespace}"
        ]));

        $this->addColumn(new Column([
            "Mapping" => "ResponseType",
            "Label" => "Response type",
            "Href" => ""
        ]));

        /**
         * Load controllers
         */
        foreach(Framework::getControllers() as $aController) {
            $this->_aItems[$aController["Use"]] = collect($aController["Actions"]);
        }

    }

    public function getItems()
    {

        return collect($this->_aItems->all()[$this->getParameter("ControllerClass")]);

    }


}

?>