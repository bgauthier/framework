<?php 
namespace Framework\Modules\UI;

use Framework\Modules\Localization\Language;
use Framework\Modules\UI\View;
use Framework\Modules\Http\Url;
use Framework\Modules\Core\Random;
use Illuminate\Support\Facades\Log;
use Framework\Modules\UI\InputGroup;
use Framework\Modules\DataType\Boolean;
use Framework\Modules\DataType\Strings;
use Framework\Modules\Security\Session;
use Framework\Modules\UI\Bootstrap\Label;
use Framework\Modules\UI\Fonts\FontAwesome;
use Framework\Modules\UI\IJavascriptEvents;
use Framework\Modules\Exception\MissingPropertyException;

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
abstract class Component implements IJavascriptEvents {

    /**
     * Component unique ID
     */
    protected $_sID = NULL;

    /**
     * Component name
     */
    protected $_sName = NULL;

    /**
     * Array of component attributes
     */
    protected $_aAttributes = [];

    /**
     * List of required attribute for component
     */
    protected $_aRequiredAttributes = [];

    /**
     * Array of component classes
     */
    protected $_aClasses = [];

    /**
     * Array of excluded classes that must not be part of the class list
     */
    protected $_aRemovedClasses = [];
    
    /**
     * Indicates if the component is rendered
     */
    protected $_bIsRendered = FALSE;

    /**
     * Component label
     */
    protected $_sLabel = NULL;

    /**
     * If we display componentlabel or not
     */
    protected $_bShowLabel = TRUE;

    /**
     * Component place holder texte
     */
    protected $_sPlaceholder = NULL;

    /**
     * If component value is required
     */
    protected $_bIsRequired = FALSE;

    /**
     * Message to display if field is required and empty
     */
    protected $_sIsRequiredMessage = NULL;

    /**
     * If component is visible or not
     */
    protected $_bIsVisible = TRUE;

    /**
     * If component is enabled
     */
    protected $_bIsEnabled = TRUE;

    /**
     * If component is readonly
     */
    protected $_bIsReadOnly = FALSE;

    /**
     * Component icon class
     */
    protected $_sIcon = NULL;

    /**
     * Component width
     */
    protected $_sWidth = NULL;

    /**
     * Component height
     */
    protected $_sHeight = NULL;

    /**
     * Prefix to prepend to control 
     */
    protected $_sComponentPrefix = NULL;

    /**
     * Opening tag (div, span ...)
     */
    protected $_sOpeningTag = NULL;
    
    /**
     * Closing tag, leave blank if no closing tag for example (img)
     */
    protected $_sClosingTag = NULL;

    /**
     * View object that this component is member of
     */
    protected $_oView = NULL;

    /**
     * List of component child objects
     */
    protected $_aChildItems = [];

    /**
     * Rendered component HTML and JS
     */
    protected $_aRenderedComponent = NULL;

    /**
	 * List of event javascript code
	 */
	protected $_aEventCode = [];
    
    /**
     * Indicates if we render event code directly in html tag
     */
    protected $_bRenderEventsInTag = FALSE;

    /**
     * Javascript object class associated to this component
     */
    protected $_sJSClassName = "Component";

    /**
     * Parent on containing component
     */
    protected $_oContainer = NULL;

    /**
     * Html code to prepend component
     */
    protected $_sComponentPrepend = NULL;

    /**
     * Html to append to component
     */
    protected $_sComponentAppend = NULL;

    /**
     * Indicates if control has a label
     */
    protected $_bHasLabel = TRUE;

    /**
     * Indicates if we must submit control on save
     */
    protected $_bIsSubmitComponent = FALSE;

    /**
	 * List of styles for the component
	 */
    protected $_aStyles = array();
    
    /**
	 * Sequence name for field
	 * @var unknown $_sSequenceName
	 */
    protected $_sSequenceName = NULL;
    
    /**
	 * The value of the text field (array for multilingual components)
	 */
	protected $_aValue = [];

	protected $_sHref = "";

    protected $_sText = "";
    
 
    /**
     * Component constructor
     */
    public function __construct() {

        /**
         * Generate unique ID for component
         */
        $this->_sID = Random::uniqueID("LSFW");
        static::loadRequiredScriptFiles();

        /**
         * Load componentClasses from config file
         */
        $this->addClass(array_get(config('framework.componentClasses'), class_basename($this)));
        $this->addClass(array_get(config('framework.componentClasses'), "*")); // Add classes fro all components

        /**
         * Load componentStyles from config file
         */
        $this->addStyle(array_get(config('framework.componentStyles'), class_basename($this)));
        $this->addStyle(array_get(config('framework.componentStyles'), "*")); // Add classes fro all components


    }

    public function getHref() {
        return $this->_sHref;
    }

    public function setHref($sHref) {
        $this->_sHref = $sHref;
        return $this;
    }

    public function getIcon() {
        return $this->_sIcon;
    }

    public function setIcon($sIcon) {
        $this->_sIcon = $sIcon;
        return $this;
    }


    /**
     * Returns styles 
     */
    public function getStyles() {

		return $this->_aStyles;
	
	}

    /**
     * Add a style to the component
     * @param $mStyle
     * @return $this
     */
	public function addStyle($mStyle) {

	    $sStyles = $mStyle;
	    if (is_array($mStyle)) {
            $sStyles = "";
	        foreach($mStyle as $sStyle) {
                $sStyles .= str_finish($sStyle, ";");
            }
        }

		$aStyles = explode(";", $sStyles);
	    foreach($aStyles as $sStyle) {
	        $aParts = explode(":" , $sStyle);
            $this->_aStyles[$aParts[0]] = $sStyle;
        }

		return $this;
	
	}
	
	/**
	 * Synonym of add style
	 * @param unknown $sStyle
	 */
	public function setStyle($sStyle) {	
		return $this->addStyle($sStyle);	
	}

    /**
     * Remove a style from the list of styles
     */
	public function removeStyle($sStyleName) {

		if (array_key_exists($sStyleName, $this->_aStyles)) {
			unset($this->_aStyles[$sStyleName]);
		}
		return $this;
	
	}


    public function getHeight() {
        return $this->_sHeight;
    }

    /**
     * Set component height
     */
    public function setHeight($sHeight) {
        /**
         * 10
         * 10px
         * 1.2em
         * 10%
         */
        $sHeight = str_replace(" ", "", $sHeight);

        if (is_numeric($sHeight) && !ends_with($sHeight, "px") && !ends_with($sHeight, "%") && !ends_with($sHeight, "em")) {
            // Default format is px
            $sHeight .= "px";
        }
        $this->_sHeight = $sHeight;
        return $this;
    }

    public function getWidth() {
        return $this->_sWidth;
    }

    /**
     * Set component width
     */
    public function setWidth($sWidth) {

        $sWidth = str_replace(" ", "", $sWidth);

        if (is_numeric($sWidth) && !ends_with($sWidth, "px") && !ends_with($sWidth, "%") && !ends_with($sWidth, "em")) {
            // Default format is px
            $sWidth .= "px";
        }
        $this->_sWidth = $sWidth;
        return $this;
    }

    public function getIsSubmitComponent() {
        return $this->_bIsSubmitComponent;
    }
    
    public function setIsSubmitComponent($bIsSubmitComponent) {
        $this->_bIsSubmitComponent = $bIsSubmitComponent;
        return $this;
    }

    public function getHasLabel() {
        return $this->_bHasLabel;
    }

    public function setHasLabel($bHasLabel) {
        $this->_bHasLabel = $bHasLabel;
        return $this;
    }

    public function getComponentPrepend() {
        return $this->_sComponentPrepend;
    }

    public function setComponentPrepend($sPrepend) {
        $this->_sComponentPrepend = $sPrepend;
        return $this;
    }

    public function appendComponentPrepend($sPrepend) {
        $this->_sComponentPrepend .= $sPrepend;
        return $this;
    }

    public function getComponentAppend() {
        return $this->_sComponentAppend;
    }

    public function setComponentAppend($sAppend) {
        $this->_sComponentAppend = $sAppend;
        return $this;
    }

    public function prependComponentAppend($sAppend) {
        $this->_sComponentAppend = $sAppend . $this->_sComponentAppend;
        return $this;
    }

    public function appendComponentAppend($sAppend) {
        $this->_sComponentAppend .= $sAppend;
        return $this;
    }

    public function setAttribute($sName, $sValue, $bSkipUrl = FALSE) {

		/*if (strtolower($sName) == "src" || strtolower($sName) == "href") {
			if (!$bSkipUrl) {
				$sValue = Url::url($sValue);
			}
		}*/
		
		$this->_aAttributes[$sName] = $sValue;
		return $this;
	
	}

    /**
     * Sets component prefix
     */
    public function setComponentPrefix($sPrefix) {

        // Ensure component prefix ends with an underscore _
        if ($sPrefix != "") {
			if (!ends_with($sPrefix, "_")) {
				$sPrefix = $sPrefix . "_";
			}
		}

        $this->_sComponentPrefix = $sPrefix;		        
		$this->setAttribute("ctlPrefix", $sPrefix); 
		return $this;
	}
	
	public function getComponentPrefix() {
                
        // Ensure component prefix ends with an underscore _
		if ($this->_sComponentPrefix != "") {
			if (!ends_with($this->_sComponentPrefix, "_")) {
				return $this->_sComponentPrefix . "_";
			}
		}
		
		return $this->_sComponentPrefix;
	}

    public function getComponentID() {
        return $this->getComponentPrefix() . $this->getID(); 
    }

    /**
     * Returns parent component     
     */     
    public function getContainer() {

		return $this->_oContainer;
	
	}

     /**
     * Returns parent component (alias of getContainer)
     */     
	public function getParent() {

		return $this->getContainer();
	
	}

     /**
     * Sets component parent container
     */     
	public function setContainer($oContainer) {

		$this->_oContainer = $oContainer;
		return $this;
	
	}

    /**
     * Set components containing view
     */
    public function setView(View $oView) {
        $this->_oView = $oView;
        return $this;         
    }

    /**
     * Returns component containing view
     */
    public function getView() {
        return $this->_oView;         
    }

    /**
     * Get component unique ID
     */ 
    public function getID()
    {
        return $this->_sID;
    }

    /**
     * Set component unique ID
     *
     * @return  self
     */ 
    public function setID($_sID)
    {
        $this->_sID = $_sID;

        return $this;
    }

    /**
     * Get component name
     */
    public function getName() {
        return $this->_sName;
    }

    /**
     * Set component name
     */
    public function setName($sName) {
         $this->_sName = $sName;
         return $this;
    }

    /**
     * Returns component label or text
     */
    public function getLabel() {
        return $this->_sLabel;
    }

    /**
     * Sets component label
     */
    public function setLabel($sLabel) {
        $this->_sLabel = $sLabel;
        return $this;
    }


    /**
     * Returns if component has been rendered 
     */
    public function getIsRendered() {
        return $this->_bIsRendered;
    }

    /**
     * Sets if component has been rendered
     */
    public function setIsRendered($bIsRendered) {
        $this->_bIsRendered = $bIsRendered;
        return $this;
    }

    /**
     * Sets if component value is required
     */
    public function setIsRequired($bRequired, $sRequiredMessage = NULL) {

		$this->_bIsRequired = Boolean::toBool($bRequired);
		$this->_sIsRequiredMessage = $sRequiredMessage;
		return $this;
	
	}

    /**
     * Sets required message to display
     */
	public function setIsRequiredMessage($sRequiredMessage) {

		$this->_sIsRequiredMessage = $sRequiredMessage;
		return $this;
	
	}

    /**
     * Returns if component value is required
     */
	public function getIsRequired() {

		return $this->_bIsRequired;
	
	}

    /**
     * Returns message to display if component is required and value is empty
     */
	public function getIsRequiredMessage() {
    	return $this->_sIsRequiredMessage;	
	}

    public function getSequenceName() {
		return $this->_sSequenceName;
	}
	
	public function setSequenceName($sSequenceName) {
		$this->_sSequenceName = $sSequenceName;
		return $this;
	}


    /**
     * Add CSS class to component
     * @param $mClassName
     * @return $this
     */
    public function addClass($mClassName) {

        if (is_array($mClassName)) {
            $mClassName = implode(" ", $mClassName);
        }

		if ($mClassName != "") {
			$aClasses = explode(" ", $mClassName);
			foreach ($aClasses as $k => $v) {
				if (trim($v) != "") {
					if (!array_key_exists($v, $this->_aRemovedClasses)) {
						$this->_aClasses[trim($v)] = trim($v);
					}
				}
			}
		}
		return $this;
	
    }
    
    /**
     * Returns label span for required star
     */
    protected function _getFieldIsRequireSpan() {

		return " <span id=\"" . $this->getComponentID() . "_isRequired\" style=\"color:#ff0000;" . ($this->getIsRequired() ? "" : "display:none;") . "\">*</span>";
	
    }
    
    /**
     * Returns label span if field is a sequence field
     */
    protected function _getFieldIsSequenceSpan() {
		if ($this->getSequenceName() != "") {
			return "<i class=\"" . FontAwesome::FAR_SORT_NUMERIC_UP . "\" title=\"" . "Sequence field" . "\"></i>&nbsp;";
		}
		return "";
    }

    protected $_aTooltips = [];

    public function getTooltips() {
        return $this->_aTooltips;
    }

    public function setTooltips($aTooltips) {
         $this->_aTooltips = $aTooltips;
         return $this;
    }

    public function addTooltip($sIcon, $sTitle, $sContent, $sClass = "", $sStyle="") {
        $this->_aTooltips[] = [
            "Icon" => $sIcon,
            "Title" => $sTitle,
            "Content" => $sContent,
            "Class" => $sClass,
            "Style" => $sStyle,
        ];        
    }
    
    protected function _getFieldTooltipIconSpan() {

        $s = "";
        foreach($this->_aTooltips as $aIcon) {            
            $s .= "<a tabindex=\"0\" class=\"fw-popover " . $aIcon["Icon"] . " " . $aIcon["Class"] . "\" style=\"" . $aIcon["Style"] . "\" data-container=\"body\" data-html=\"true\" data-toggle=\"popover\" role=\"button\" data-trigger=\"focus\" data-title=\"" . $aIcon["Title"] . "\" data-content=\"" .  $aIcon["Content"] . "\"></a>";
        }
        if ($s != "") {
            $s = "&nbsp;" . $s;
        }
        return $s;
	
	}


    /**
     * Render component attributes
     */
    protected function _renderAttributes() {

        $s = "";
        
        $s .= " id=\"" . $this->getID() . "\"";

        if ($this->getName() == "") {
            $this->setName($this->getID());
        }

        $s .= " name=\"" . $this->getName() . "\"";

        foreach($this->_aAttributes as $sName => $sValue) {
            $s .= " " . $sName . "=\"" . $sValue . "\"";
        }

        $sClasses = trim(implode(" ", $this->getClasses()));
        if ($sClasses != "") {
            $s .= " class=\"" . $sClasses . "\"";
        }

        $s .= $this->_renderStyles();

        return $s;

    }

    protected function _renderStyles($bIncludeStyleTag = TRUE) {

		$sStyle = "";
		foreach ($this->_aStyles as $k => $v) {
			$sStyle .= trim($v);
			if (! ends_with($sStyle, ";")) {
				$sStyle .= ";";
			}
		}
		
		if ($bIncludeStyleTag) {
			if ($sStyle != "") {
				$sStyle = " style=\"" . $sStyle . "\" ";
			}
		}
		
		return $sStyle;
	
	}

    public function getClasses() {
        return $this->_aClasses;
    }

    /**
     * Returns other component content than child items
     * For example if we want to set the html content of a div
     */
    protected function _getComponentContent() {
        return "";
    }

    /**
     * Called before compomnent is rendered
     */
    protected function _preRender() {

        if ($this->getIsSubmitComponent()) {
            $this->setAttribute("fw-submit", "true");
        }

        if ($this->getWidth() != "") {
            $this->addStyle("width:" . $this->getWidth() . ";");
        }

        if ($this->getHeight() != "") {
            $this->addStyle("height:" . $this->getHeight() . ";");
        }

        if ($this->getIsRequired()) {
            $this->setAttribute("fw-required", "true");
        }

        if ($this->getComponentPrefix() != "") {
            $this->setAttribute("fw-prefix", $this->getComponentPrefix());
        }

    }

    /**
     * Check if object uses a specific trait
     * 
     */
    protected function _usesTrait($sTrait) {        
        $aUses = class_uses($this);       
        return in_array($sTrait, $aUses);
    }


    public function render() {

        Log::debug("Component render " . $this->getID());
    
        if ($this->_aRenderedComponent !== NULL) {
            return $this->_aRenderedComponent;
        }

        /**
         * Call component pre rendering
         */
        $this->_preRender();

        $sJs = "";
        $sHtml = "";
        if ($this->_sOpeningTag === NULL) {
            throw new MissingPropertyException("Component OpeningTag");
        }

        $sHtml .= $this->getComponentPrepend();
        

        if ($this->getHasLabel() && $this->getLabel() != "" && get_class($this) != Label::class) {                       
            $oLabel = new Label();
            $oLabel->setAttribute("for", $this->getComponentID());
            $oLabel->setLabel($this->getLabel());
            $oLabel->setIsRequired($this->getIsRequired(), $this->getIsRequiredMessage());
            $oLabel->setTooltips($this->getTooltips());
            $oLabel->setSequenceName($this->getSequenceName());
            $sHtml .= $oLabel->render()["HTML"];            
        }

        if ($this->_usesTrait(InputGroup::class)) {
            if ($this->getInputGroupEnabled()) {
                $sHtml .= "<div class=\"input-group\">" . PHP_EOL;
                $sHtml .= " <div class=\"input-group-prepend\">" . PHP_EOL;
                $sHtml .= implode(PHP_EOL , $this->getPrepends());
                $sHtml .= " </div>" . PHP_EOL;
            }
        }

        // Open component
        $sHtml .= "<" . $this->_sOpeningTag;

        // Add component attributes
        $sHtml .= $this->_renderAttributes();

        // Close opening component tag
        if ($this->_sClosingTag !== NULL) {
            $sHtml .= ">";
        }
        
        $sHtml .= $this->_getComponentContent();

        /**
         * Render JS for this component before rendering for child components
         */
        $sJs .= $this->_renderJS();
        
        /**
         * Add child components
         */
        foreach($this->_aChildItems as $oComponent) {
            $sHtml .= $oComponent->render()["HTML"];
            $sJs .= $oComponent->render()["JS"];
        }


        // Close component
        if ($this->_sClosingTag === NULL) {
            $sHtml .= "/>";
        } else {
            $sHtml .= "</" . $this->_sClosingTag . ">";
        }
      
        if ($this->_usesTrait(InputGroup::class)) {
            if ($this->getInputGroupEnabled()) {
                $sHtml .= " <div class=\"input-group-append\">" . PHP_EOL;
                $sHtml .= implode(PHP_EOL, $this->getAppends());
                $sHtml .= "</div>" . PHP_EOL;
                // Close input group
                $sHtml .= "</div>" . PHP_EOL;
            }
        }

        $sHtml .= $this->getComponentAppend();

        $this->setIsRendered(TRUE);

        $this->_aRenderedComponent = [
            "HTML" => $sHtml,
            "JS" => $sJs
        ];

        return $this->_aRenderedComponent;


    }

    public function getJSClassName() {
        return $this->_sJSClassName;
    }

    public function setJSClassName($sJSClassName) {
        $this->_sJSClassName = $sJSClassName;
        return $this;
    }

    public function setValue($sValue, $sLngCode = NULL) {
	
		if ($sLngCode == NULL) {
			$sLngCode = Session::getLanguageCode();
		}
	
		$this->_aValue[$sLngCode] = $sValue;
		return $this;
	
	}
	
	public function getValue($sLngCode = NULL) {
	
		if ($sLngCode == NULL) {
			$sLngCode = Session::getLanguageCode();
		}
	
		if (array_key_exists($sLngCode, $this->_aValue)) {		
			return $this->_aValue[$sLngCode];
		} else {
			return NULL;
		}
	
	}


    protected function _renderJS() {

        $s = "";

        $s .= "/**" . PHP_EOL;
        $s .= " * Creating component " . $this->getComponentID() . PHP_EOL;
        $s .= "*/" . PHP_EOL;
        $s .= "var " . $this->getComponentID() . " = new " . $this->getJSClassName() . "();" . PHP_EOL;        
        $s .= $this->getComponentID() . ".id = '" . $this->getComponentID() . "';" . PHP_EOL;
        $s .= $this->getComponentID() . "._isRequired = " . Boolean::toStringJS($this->getIsRequired()) . ";" . PHP_EOL;
        $s .= $this->getComponentID() . "._requiredMessage = '" . addslashes($this->getIsRequiredMessage()) . "';" . PHP_EOL;


        if ($this->_usesTrait(Localizable::class)) {
            $s .= $this->getComponentID() . "._value = {" . PHP_EOL;
            foreach (Language::getLanguageInfo() as $aLng) {
                $s .= " " . $aLng["Code"] . " : '" . addslashes($this->getValue()) . "'," . PHP_EOL;
            }
            $s .= "};" . PHP_EOL;
        } else {
            $s .= $this->getComponentID() . "._value =  '" . addslashes($this->getValue()) . "';" . PHP_EOL;
        }


        foreach($this->_aEventCode as $sEventName => $sCode) {
            $s .= $this->getComponentID() . "." . $sEventName . " = function() {" . PHP_EOL;
            $s .= " " . $sCode . PHP_EOL;
            $s .= "}" . PHP_EOL;
        }


        if ($this->getParent() !== NULL) {
            $s .= $this->getParent()->getComponentID() . ".add(" . $this->getComponentID()  . ");" . PHP_EOL;
        } else {
            $s .= "Page.add(" . $this->getComponentID()  . ");" . PHP_EOL;
        }

        return $s;

    }

    /**
     * If we render js code in html tag or not
     */
    public function setRenderEventsInTag($bEnable) {

		$this->_bRenderEventsInTag = $bEnable;
		return $this;
	
	}

    /**
     * If we render js code in html tag or not
     */
	public function getRenderEventsInTag() {

		return $this->_bRenderEventsInTag;
	
	}


    /**
     * IJavascript Events
     */

     /**
     * Returns javascript event code
     */
	public function getEventCode($sEventName) {
    
        return array_get($this->_aEventCode, $sEventName);
	
	}
    
    /**
     * Sets javascript event code
     */
	public function setEventCode($sEventName, $sJsCode, $bRenderEventInTag = FALSE) {
	
		if ($bRenderEventInTag) {
			$this->setRenderEventsInTag(TRUE);
		}
		
		$this->_aEventCode[$sEventName] = $sJsCode;
		return $this;
	
    }
    

    public function getOnClick() {
        return $this->getEventCode(IJavascriptEvents::EVENT_ONCLICK);	
	}

	public function setOnClick($sJsCode, $bRenderEventInTag = FALSE) {

        $this->setEventCode(IJavascriptEvents::EVENT_ONCLICK, $sJsCode, $bRenderEventInTag );		
		return $this;
	
	}
	
	public function getOnDblClick() {
        return $this->getEventCode(IJavascriptEvents::EVENT_ONDBLCLICK);	        
	}
	
	public function setOnDblClick($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONDBLCLICK, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
		
	public function getOnChange() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONCHANGE);	
			
	}
	
	public function setOnChange($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONCHANGE, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
		
	
	public function getOnSelect() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONSELECT);	
	
	}
	
	public function setOnSelect($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONSELECT, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnBlur() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONBLUR);		
	
	}
	
	public function setOnBlur($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONBLUR, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnFormula() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONFORMULA);	
		
	}
	
	public function setOnFormula($sJsCode, $bRenderEventInTag = FALSE) {
            
        $this->setEventCode(IJavascriptEvents::EVENT_ONFORMULA, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
		
	public function getOnFocus() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONFOCUS);	
	
	}
	
	public function setOnFocus($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONFOCUS, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnFocusIn() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONFOCUSIN);			
	
	}
	
	public function setOnFocusIn($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONFOCUSIN, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnFocusOut() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONFOCUSOUT);	
	
	}
	
	public function setOnFocusOut($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONFOCUSOUT, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
		
	public function getOnHover() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONHOVER);	
		
	}
	
	public function setOnHover($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONHOVER, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}

	public function getOnKeyDown() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONKEYDOWN);	
		
	}
	
	public function setOnKeyDown($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONKEYDOWN, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}

	public function getOnKeyPress() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONKEYPRESS);	
	
	}
	
	public function setOnKeyPress($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONKEYPRESS, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnKeyUp() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONKEYUP);	
	
	}
	
	public function setOnKeyUp($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONKEYUP, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnMouseDown() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONMOUSEDOWN);	
	
	}
	
	public function setOnMouseDown($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONMOUSEDOWN, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnMouseEnter() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONMOUSEENTER);	
	
	}
	
	public function setOnMouseEnter($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONMOUSEENTER, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnMouseLeave() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONMOUSELEAVE);	
	
	}
	
	public function setOnMouseLeave($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONMOUSELEAVE, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnMouseMove() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONMOUSEMOVE);	
	
	}
	
	public function setOnMouseMove($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONMOUSEMOVE, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnMouseOut() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONMOUSEOUT);	

    }
	
	public function setOnMouseOut($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONMOUSEOUT, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnMouseUp() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONMOUSEUP);	
	
	}
	
	public function setOnMouseUp($sJsCode, $bRenderEventInTag = FALSE) {

        $this->setEventCode(IJavascriptEvents::EVENT_ONMOUSEUP, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnResize() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONRESIZE);	
	
	}
	
	public function setOnResize($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONRESIZE, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}

	public function getOnScroll() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONSCROLL);	
	
	}
	
	public function setOnScroll($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONSCROLL, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnSubmit() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONSUBMIT);	
	
	}
	
	public function setOnSubmit($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONSUBMIT, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnUnload() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONUNLOAD);	
	
	}
	
	public function setOnUnload($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONUNLOAD, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
	
	public function getOnLoad() {
    
        return $this->getEventCode(IJavascriptEvents::EVENT_ONLOAD);	
		
	}
	
	public function setOnLoad($sJsCode, $bRenderEventInTag = FALSE) {
    
        $this->setEventCode(IJavascriptEvents::EVENT_ONLOAD, $sJsCode, $bRenderEventInTag );				
		return $this;
	
	}
        
    /**
     * This will load any required script files for the component
     */
    public static function loadRequiredScriptFiles() {
        
    }  

    public function __toString() {
        return $this->render()["HTML"];
    }

    public function setText($sText) {
        $this->_sText = $sText;
        return $this;
    }

    public function getText() {
        return $this->_sText;
    }

}

?>