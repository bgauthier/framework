<?php
namespace Framework\Modules\UI;

class MenuItem extends Container {

    const TYPE_HEADER = "header";
    const TYPE_SEPARATOR = "separator";
    const TYPE_ITEM = "item";

    protected $_nMenuItemType = "item";

    public function getMenuItemType() {
        return $this->_nMenuItemType;
    }

    public function setMenuItemType($nType) {
        $this->_nMenuItemType = $nType;
        return $this;
    }

}

?>