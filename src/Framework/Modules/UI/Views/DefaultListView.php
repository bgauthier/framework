<?php
namespace Framework\Modules\UI\Views;

class DefaultListView extends DefaultListViewBase {

    public function setListDataSet($oDataSet) {
        $this->_tblList->setDataSet($oDataSet);
        return $this;
    }

}

?>