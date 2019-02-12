<?php
namespace Framework\Modules\UI\Views;

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

abstract class DefaultListViewBase extends View {

    protected $_row1 = NULL;
    protected $_row2 = NULL;
    protected $_col1 = NULL;
    protected $_col2 = NULL;
    protected $_col3 = NULL;
    protected $_panList = NULL;
    protected $_tblList = NULL;
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


        $this->_panList = new Panel();
        $this->_panList->setID("panList");
        //$this->_panList->setHeaderLabel($this->getTitle());
        $this->_frm->add($this->_panList);

        $this->_tblList = new Table();
        $this->_tblList->setID("tblList");


        $this->_panList->add($this->_tblList);

    }

    public function panList() {
        return $this->_panList;
    }

}

?>