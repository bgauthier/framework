<?php 
namespace Framework\Modules\Security\Views;

use Framework\Modules\UI\View;
use Framework\Modules\UI\Bootstrap\Button;

class UserViewBase extends View {
    
    protected $_btnTest = NULL;

    public function __construct() {
        parent::__construct();        
    }

    protected function _initializeView() {
        
        parent::_initializeView();

        $this->_btnTest = new Button();        
        $this->_btnTest->setID("btnTest");
        $this->_btnTest->setLabel("Click me!");
        $this->_btnTest->setOnClick("alert('Hello world');");
        $this->addComponent($this->_btnTest);

    }

}

?>