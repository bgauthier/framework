<?php 
namespace Framework\Modules\Build\Views;

use Framework\Modules\UI\View;
use Framework\Modules\UI\TableColumn;
use Framework\Modules\UI\Bootstrap\Row;
use Framework\Modules\UI\Bootstrap\Form;
use Framework\Modules\UI\Bootstrap\Text;
use Framework\Modules\UI\Bootstrap\Panel;
use Framework\Modules\UI\Bootstrap\Table;
use Framework\Modules\UI\Bootstrap\Button;
use Framework\Modules\UI\Bootstrap\Column;
use Framework\Modules\UI\Fonts\FontAwesome;
use Framework\Modules\UI\Bootstrap\TextArea;
use Framework\Modules\Http\DataSets\ControllerDataSet;

abstract class ControllerViewBase extends View {
    
    protected $_panZone = NULL;
    protected $_row1 = NULL;
    protected $_row2 = NULL;
    protected $_col1 = NULL;
    protected $_col2 = NULL;
    protected $_col3 = NULL;
    protected $_txtName = NULL;
    protected $_txtNamespace = NULL;
    protected $_txtDescription = NULL;
    protected $_panActions = NULL;
    protected $_tblActions = NULL;
    protected $_frm = NULL;

    public function __construct() {
        parent::__construct();        
    }

    protected function _initializeView() {
        
        parent::_initializeView();

        $this->_row2 = new Row();
        $this->add($this->_row2);

        $this->_col1 = new Column();
        $this->_col1->setSize(12);
        $this->_row2->add($this->_col1);

        $this->_frm = new Form();
        $this->_col1->add($this->_frm);

        $this->_panZone = new Panel();
        $this->_panZone->setID("panZone");
        $this->_panZone->setHeaderLabel("Controller");
        $this->_frm->add($this->_panZone);

        $this->_row1 = new Row();
        $this->_panZone->add($this->_row1);

        $this->_col2 = new Column();
        $this->_col2->setSize(6);
        $this->_row1->add($this->_col2);

        $this->_col3 = new Column();
        $this->_col3->setSize(6);
        $this->_row1->add($this->_col3);


        $this->_txtName = new Text();
        $this->_txtName->setID("txtName");
        $this->_txtName->setLabel("Name");
        $this->_txtName->setIsRequired(TRUE);
        $this->_txtName->setMappingObject("Controller");
        $this->_txtName->setMappingField("getClassName");
        $this->_col2->add($this->_txtName);

        $this->_txtNamespace = new Text();
        $this->_txtNamespace->setID("txtNamespace");
        $this->_txtNamespace->setLabel("Namespace");
        $this->_txtNamespace->setMappingObject("Controller");
        $this->_txtNamespace->setMappingField("getNamespace");
        $this->_col3->add($this->_txtNamespace);

        $this->_txtDescription = new TextArea();
        $this->_txtDescription->setID("txtDescription");
        $this->_txtDescription->setLabel("Description");
        $this->_txtDescription->setMappingObject("Controller");
        $this->_txtDescription->setMappingField("getDescription");
        $this->_col2->add($this->_txtDescription);

        $this->_panActions = new Panel();
        $this->_panActions->setID("panActions");
        $this->_panActions->setHeaderLabel("Actions");
        $this->_frm->add($this->_panActions);

        $this->_tblActions = new Table();
        $this->_tblActions->setID("tblActions");        
        $this->_tblActions->addTableColumn(new TableColumn(["Label" => "Name", "Mapping" => "Name"]));
        $this->_tblActions->addTableColumn(new TableColumn(["Label" => "Type", "Mapping" => "Type"]));
        $this->_tblActions->addTableColumn(new TableColumn(["Label" => "Description", "Mapping" => "Description"]));
        //$this->_tblActions->setDataSet(ControllerDataSet::class);
        $this->_panActions->add($this->_tblActions);

    }

    public function tblActions() {
        return $this->_tblActions;
    }

}

?>