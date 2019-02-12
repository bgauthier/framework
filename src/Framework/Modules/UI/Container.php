<?php 
namespace Framework\Modules\UI;

use Framework\Modules\UI\Component;
use Framework\Modules\UI\Bootstrap\Li;
use Framework\Modules\UI\Bootstrap\Ol;
use Framework\Modules\UI\Bootstrap\Ul;
use Framework\Modules\UI\Bootstrap\Div;
use Framework\Modules\UI\Bootstrap\Pre;
use Framework\Modules\UI\Bootstrap\Row;
use Framework\Modules\UI\Bootstrap\Form;
use Framework\Modules\UI\Bootstrap\Icon;
use Framework\Modules\UI\Bootstrap\Span;
use Framework\Modules\UI\Bootstrap\Text;
use Framework\Modules\UI\Bootstrap\Alert;
use Framework\Modules\UI\Bootstrap\Image;
use Framework\Modules\UI\Bootstrap\Label;
use Framework\Modules\UI\Bootstrap\Panel;
use Framework\Modules\UI\Bootstrap\Table;
use Framework\Modules\UI\Bootstrap\Anchor;
use Framework\Modules\UI\Bootstrap\Button;
use Framework\Modules\UI\Bootstrap\Column;
use Framework\Modules\UI\Bootstrap\Select;
use Framework\Modules\UI\Bootstrap\Heading;
use Framework\Modules\UI\Bootstrap\CheckBox;
use Framework\Modules\UI\Bootstrap\TextArea;
use Framework\Modules\UI\Bootstrap\Paragraph;
use Framework\Modules\UI\Bootstrap\RadioButton;

/**
 *
 *	LICENSE: This source file is subject to the LogikSuite Framework license
 * 	that is available at the following file: LICENSE.md
 * 	If you did not receive a copy of the LogikSuite Framework License and
 * 	are unable to obtain it through the web, please send a note to
 * 	support@intelogie.com so we can mail you a copy immediately.
 *
 *	@package 	LogikSuite
 * 	@author 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	INTELOGIE.COM INC.
 * 	@license 	LICENSE.md
 */
abstract class Container extends Component {


    /**
     * 
     */
    public function __construct() {
        parent::__construct();
    }

    public function add(Component $oComponent) {
        $oComponent->setView($this->getView());
        $this->getView()->addComponent($oComponent);
        $oComponent->setContainer($this);
        $this->_aChildItems[] = $oComponent;
        return $oComponent;
    }

    public function getItems() {
        return $this->_aChildItems;
    }
    

    /**
     * Add alert component
     */
    public function addAlert($sID = NULL) {

        $oItem = new Alert();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Anchor component
     */
    public function addAnchor($sID = NULL) {

        $oItem = new Anchor();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Button component
     */
    public function addButton($sID = NULL) {

        $oItem = new Button();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add CheckBox component
     */
    public function addCheckBox($sID = NULL) {

        $oItem = new CheckBox();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }


    /**
     * Add Div component
     */
    public function addDiv($sID = NULL) {

        $oItem = new Div();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

     /**
     * Add Form component
     */
    public function addForm($sID = NULL) {

        $oItem = new Form();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

     /**
     * Add Heading component
     */
    public function addHeading($sID = NULL) {

        $oItem = new Heading();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

     /**
     * Add Icon component
     */
    public function addIcon($sID = NULL) {

        $oItem = new Icon();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

     /**
     * Add Image component
     */
    public function addImage($sID = NULL) {

        $oItem = new Image();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

     /**
     * Add Label component
     */
    public function addLabel($sID = NULL) {

        $oItem = new Label();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

     /**
     * Add Li component
     */
    public function addLi($sID = NULL) {

        $oItem = new Li();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Ol component
     */
    public function addOl($sID = NULL) {

        $oItem = new Ol();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Panel component
     */
    public function addPanel($sID = NULL) {

        $oItem = new Panel();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Paragraph component
     */
    public function addParagraph($sID = NULL) {

        $oItem = new Paragraph();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Pre component
     */
    public function addPre($sID = NULL) {

        $oItem = new Pre();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add RadioButton component
     */
    public function addRadioButton($sID = NULL) {

        $oItem = new RadioButton();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Row component
     */
    public function addRow($sID = NULL) {

        $oItem = new Row();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Select component
     */
    public function addSelect($sID = NULL) {

        $oItem = new Select();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Span component
     */
    public function addSpan($sID = NULL) {

        $oItem = new Span();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Table component
     */
    public function addTable($sID = NULL) {

        $oItem = new Table();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Text component
     */
    public function addText($sID = NULL) {

        $oItem = new Text();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add TextArea component
     */
    public function addTextArea($sID = NULL) {

        $oItem = new TextArea();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Add Ul component
     */
    public function addUl($sID = NULL) {

        $oItem = new Ul();
		if ($sID !== NULL) {
			$oItem->setID($sID);
		}		
		$this->add($oItem);
	
		return $oItem;

    }

    /**
     * Alias for add
     */
    public function addItem(Component $oComponent) {
							
		return $this->add($oComponent);
					
	}



}

?>